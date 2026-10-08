# LIMSKU Workflow Audit

## 1. Actual Workflow

Current actual implementation traced across code and database:

1. **Penguji:**
   - Sampel status: `Menunggu Pengujian`
   - Action: Klaim Sampel (`pengujian/klaim/$sample_id`) -> Status: `Sedang Diuji`
   - Action: Pilih Jenis (`Kimia` / `Mikrobiologi`) -> Select Templates -> `mulai_sesi()`
   - Action: Fill Multi-form Results (`pengujian/proses_sesi/$session_id`) -> Submit
   - Database update: `testing_sessions.status` = `Menunggu Verifikasi`, `test_results.status` = `Menunggu Verifikasi`, `samples.status` = `Menunggu Verifikasi`.

2. **Penyelia:**
   - Workspace: `/pengujian/verifikasi` or `/pengujian/detail_sesi/$session_id`
   - Action: Review forms & click "Terima & Kirim ke MT" (`pengujian/verifikasi_sesi/$session_id`)
   - Database update: `testing_sessions.status` = `Menunggu Approval`, `test_results.status` = `Menunggu Approval`, `samples.status` = `Menunggu Approval`, `verifier_id` = User ID, `waktu_verifikasi` = NOW().

3. **Manajer Teknis Queue & Routing Issue (The Bug Location):**
   - Workspace: `/pengujian/approval`
   - Queue query (`Model_Pengujian->ambil_antrean_approval()`): Fetches `testing_sessions` where `status = 'Menunggu Approval'`. **(QUERY IS CORRECT)**
   - UI Link in [`approval.php`](file:///c:/xampp/htdocs/LIMSKU/application/views/pengujian/approval.php): Renders `<a href="site_url('pengujian/detail/' . $v['id'])">Periksa & Approve</a>`.
   - **FAILURE:** `$v['id']` is `testing_sessions.id` (e.g. `2`). Clicking the link opens `/pengujian/detail/2`. Controller `Pengujian::detail(2)` calls `Model_Pengujian->ambil_hasil_by_id(2)` which queries `test_results WHERE id = 2`. `test_results` record #2 belongs to Session #1 (status `Sedang Diuji`).
   - Outcome: Manajer Teknis opens the wrong detail view where status is `Sedang Diuji` (or 404), so the "Approve & Finalize" button **does not appear**.

---

## 2. Expected Workflow

```
Penguji (Klaim & Input Sesi)
  ↓ status = Sedang Diuji
Menunggu Verifikasi
  ↓ Penyelia Review
Penyelia Approve (verifikasi_sesi)
  ↓ status = Menunggu Approval
Menunggu Approval Queue (/pengujian/approval)
  ↓ MT Clicks "Periksa & Approve" -> opens /pengujian/detail_sesi/$session_id
Manajer Teknis Approval (approve_sesi)
  ↓ status = Approved / Final
TTD Digital / Metadata & Signature Block
  ↓
Generate LHU PDF Final
  ↓
Laporan Final Available for Download
```

---

## 3. Database Trace

| Step | Table | Field | Before | After | Status |
| :--- | :--- | :--- | :--- | :--- | :---: |
| 1. Penguji Submit | `testing_sessions` | `status` | `Sedang Diuji` | `Menunggu Verifikasi` | **PASS** |
| | `test_results` | `status` | `Sedang Diuji` | `Menunggu Verifikasi` | **PASS** |
| | `samples` | `status` | `Sedang Diuji` | `Menunggu Verifikasi` | **PASS** |
| 2. Penyelia Verify | `testing_sessions` | `status`, `verifier_id`, `waktu_verifikasi` | `Menunggu Verifikasi`, `NULL`, `NULL` | `Menunggu Approval`, `4`, `TIMESTAMP` | **PASS** |
| | `test_results` | `status`, `verifier_id`, `waktu_verifikasi` | `Menunggu Verifikasi`, `NULL`, `NULL` | `Menunggu Approval`, `4`, `TIMESTAMP` | **PASS** |
| | `samples` | `status`, `updated_by` | `Menunggu Verifikasi`, `3` | `Menunggu Approval`, `4` | **PASS** |
| 3. MT Queue Query | `testing_sessions` | `status` = `Menunggu Approval` | - | Returns Session #2 | **PASS** |
| 4. MT Queue Action Link | View `approval.php` | `href` URL target | Expected: `detail_sesi/2` | Actual: `detail/2` (Targeting `test_results.id = 2`) | **FAIL (ROUTING BUG)** |
| 5. MT Approval | `testing_sessions` | `status`, `approver_id`, `waktu_approval` | `Menunggu Approval`, `NULL`, `NULL` | `Approved / Final`, `5`, `TIMESTAMP` | **PASS** |
| | `test_results` | `status`, `approver_id`, `waktu_approval` | `Menunggu Approval`, `NULL`, `NULL` | `Approved / Final`, `5`, `TIMESTAMP` | **PASS** |
| | `samples` | `status`, `updated_by` | `Menunggu Approval`, `4` | `Approved / Final`, `5` | **PASS** |

---

## 4. Penyelia Verification Audit

- **Controller Method:** `Pengujian::verifikasi_sesi($session_id)`
- **Model Method:** `Model_Pengujian::verifikasi_sesi($session_id, $verifier_id)`
- **Transaction:** Standard CI DB Transaction (`trans_start`, `trans_complete`).
- **Updates Performed:**
  - `testing_sessions`: `status = 'Menunggu Approval'`, `verifier_id = $verifier_id`, `waktu_verifikasi = NOW()`, `alasan_penolakan = NULL`
  - `test_results`: `status = 'Menunggu Approval'`, `verifier_id = $verifier_id`, `waktu_verifikasi = NOW()`, `alasan_penolakan = NULL`
  - `samples`: `status = 'Menunggu Approval'`, `updated_by = $verifier_id`
- **Audit Logging:** `VERIFIKASI_SESI` logged to `audit_logs` table.
- **Verification Result:** **VERIFICATION TRANSITION IS WORKING PERFECTLY IN DATABASE.**

---

## 5. Manager Approval Queue Audit

- **Controller:** `Pengujian::approval()`
- **Model Method:** `Model_Pengujian::ambil_antrean_approval()`
- **Primary SQL Query:**
  ```sql
  SELECT ts.*, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel, u.nama_lengkap as nama_penguji, uv.nama_lengkap as nama_verifier,
      (SELECT COUNT(*) FROM test_results tr WHERE tr.session_id = ts.id) as jumlah_form,
      (SELECT GROUP_CONCAT(DISTINCT m.nama_metode SEPARATOR ", ") FROM test_results tr JOIN methods m ON m.id = tr.method_id WHERE tr.session_id = ts.id) as nama_metode,
      (SELECT GROUP_CONCAT(DISTINCT t.nama_template SEPARATOR ", ") FROM test_results tr JOIN form_templates t ON t.id = tr.template_id WHERE tr.session_id = ts.id) as nama_template,
      (SELECT GROUP_CONCAT(DISTINCT tr.kesimpulan SEPARATOR ", ") FROM test_results tr WHERE tr.session_id = ts.id) as kesimpulan
  FROM testing_sessions ts
  INNER JOIN samples s ON s.id = ts.sample_id
  LEFT JOIN users u ON u.id = ts.penguji_id
  LEFT JOIN users uv ON uv.id = ts.verifier_id
  WHERE ts.status = 'Menunggu Approval'
  ORDER BY ts.id ASC
  ```
- **Permission Check:** `pengujian_approve` (Role: Manajer Teknis).
- **Audit Findings:** Query works correctly and retrieves verified sessions. However, the action button in [`approval.php`](file:///c:/xampp/htdocs/LIMSKU/application/views/pengujian/approval.php) line 70 generates `site_url('pengujian/detail/' . $v['id'])` passing `session_id` into a single-form test result route (`pengujian/detail/$test_id`), creating an **ID Mismatch** and preventing the Approval button from rendering.

---

## 6. Root Cause

### **PRIMARY ROOT CAUSE: ROUTING BUG & ID MISMATCH IN APPROVAL VIEW**

1. **Specific Location:**
   - File: [`application/views/pengujian/approval.php`](file:///c:/xampp/htdocs/LIMSKU/application/views/pengujian/approval.php) (Line 70)
   - Code: `<a href="<?php echo site_url('pengujian/detail/' . $v['id']); ?>" class="btn btn-primary btn-sm fw-semibold">Periksa & Approve</a>`
2. **Empirical Evidence:**
   - `$v['id']` coming from `ambil_antrean_approval()` is `testing_sessions.id` (e.g., `2`).
   - Passing `2` to `/pengujian/detail/2` causes `Pengujian::detail(2)` to load `test_results WHERE id = 2`.
   - `test_results.id = 2` belongs to a different session (Session 1, status `Sedang Diuji`).
   - Consequently, `detail.php` renders status as `Sedang Diuji`, hiding the approval action buttons for Manajer Teknis.
3. **Correct Target Route:**
   - Multi-form sessions must link to **`pengujian/detail_sesi/' . $v['id']`** which loads [`detail_sesi.php`](file:///c:/xampp/htdocs/LIMSKU/application/views/pengujian/detail_sesi.php).
   - On [`detail_sesi.php`](file:///c:/xampp/htdocs/LIMSKU/application/views/pengujian/detail_sesi.php), when status is `Menunggu Approval`, the **"Approve Final Sesi"** button correctly posts to `pengujian/approve_sesi/$session_id`.

---

## 7. TTD Audit

- **Current Implementation:** Approval metadata block & PDF Signature Block.
- **Identifikasi Detail:**
  - Metadata approval (`approver_id`, `waktu_approval`) is stored in `testing_sessions` and `test_results`.
  - Identity of Manajer Teknis (Nama Lengkap & NIP) is fetched from `users` table via `approver_id`.
  - On the PDF, a dedicated **3-column Signature Block** renders Penguji, Penyelia, and Manajer Teknis names, NIPs, and timestamps.
- **Classification:** **Blok Tanda Tangan PDF & Metadata Approval.** (Not full PKI cryptographic TTE certificates, but fully structured audit & visual signature blocks in PDF).

---

## 8. PDF Audit

- **Trigger:** Automatic upon `approve_sesi($session_id)` execution in `Pengujian.php`.
- **Status Prerequisite:** `testing_sessions.status` = `Approved / Final`.
- **Multi-form Rendering:** `Laporan_PDF::buat_laporan_sesi` renders all form templates, dynamic fields, observations, form conclusions, and the 3-column signature block.
- **Database File Path:** Saved into `test_results.file_laporan` as `uploads/laporan/LHU_FINAL_...pdf`.
- **Filesystem Verification:** Verified created in `uploads/laporan/`.

---

## 9. Security & RBAC

| Role | Permission | Action Allowed | Result |
| :--- | :--- | :--- | :---: |
| **Manajer Teknis** | `laporan_view`, `pengujian_approve` | Open approval queue, approve sessions, trigger LHU PDF | **PASS** |
| **Penyelia** | `laporan_view`, `pengujian_verify` | Verify sessions, reject sessions to penguji | **PASS** |
| **Penguji** | `laporan_view`, `pengujian_input`, `pengujian_view` | Input test results, revise rejected sessions | **PASS** |
| **Admin TI** | `laporan_view` | View history & logs (No approval capability) | **PASS** |

---

## 10. Recommended Fix

*(Note: Per Hard Lock instruction, NO CODE CHANGES are implemented in this audit milestone).*

To resolve the issue completely in the next phase, apply the following minimal fix:

1. **Update `approval.php` Action Link:**
   - In [`application/views/pengujian/approval.php`](file:///c:/xampp/htdocs/LIMSKU/application/views/pengujian/approval.php) line 70, change:
     ```php
     <a href="<?php echo site_url('pengujian/detail/' . $v['id']); ?>" class="btn btn-primary btn-sm fw-semibold">
     ```
     to:
     ```php
     <a href="<?php echo site_url('pengujian/detail_sesi/' . $v['id']); ?>" class="btn btn-primary btn-sm fw-semibold">
     ```
2. **Update `riwayat.php` Session Detail Links:**
   - Ensure session records in [`riwayat.php`](file:///c:/xampp/htdocs/LIMSKU/application/views/pengujian/riwayat.php) direct users to `pengujian/detail_sesi/$session_id` when inspecting session-level testing.
