# LIMSKU — Database

## 1. Database

- MySQL / MariaDB
- InnoDB
- Primary Key: `id`
- Gunakan Foreign Key untuk relasi
- Gunakan timestamp untuk data waktu
- Gunakan index pada field yang diperlukan

## 2. Tabel Utama

Struktur awal database:

- `users`
- `roles`
- `permissions`
- `role_permissions`
- `laboratories`
- `samples`
- `methods`
- `parameters`
- `form_templates`
- `test_results`
- `workflow_logs`
- `reports`
- `attachments`
- `notifications`
- `audit_logs`

## 3. Samples

Tabel `samples` wajib mengikuti format Excel data sampel yang diberikan.

Field utama:

```text
id
no
kategori_sampel
sub_kategori
jenis_kelas_terapi
kategori_sarana
nama_sarana
kabupaten_kota
tanggal_sampling
kode_sampel_manual
no_sipt
nama_sampel
nomor_izin_edar
kondisi_produk
no_bets
kedaluwarsa
kemasan
nama_alamat_perusahaan
komposisi
jumlah_kimia
jumlah_mikro
jumlah_arsip
jumlah_penandaan
jumlah_total
penyimpanan
penandaan
harga
tie
mk
tmk
surtug
balai_penguji

Tambahkan field teknis seperlunya seperti:

status
created_at
updated_at
created_by
updated_by

Jangan mengubah field Excel tanpa alasan yang jelas.

4. Pengujian

Relasi utama:

samples
   ↓
test_results
   ├── methods
   └── form_templates

Sistem memiliki 17 form pengujian:

12 Chemistry
5 Microbiology

Detail field setiap form mengikuti template pengujian dan belum perlu diisi dengan data dummy.

5. Workflow

Status utama:

Menunggu Pengujian
↓
Sedang Diuji
↓
Menunggu Verifikasi
↓
Menunggu Approval
↓
Approved / Final

Penolakan:

Menunggu Verifikasi
↓
Ditolak / Perlu Perbaikan
↓
Kembali ke Penguji
↓
Revisi

Workflow lengkap mengikuti WORKFLOW.md.

6. Relasi
roles
  ↓
users
  ↓
samples
  ↓
test_results
  ↓
reports

workflow_logs, attachments, notifications, dan audit_logs terhubung sesuai kebutuhan masing-masing.

7. Aturan
Buat struktur tabel saja terlebih dahulu.
Jangan membuat INSERT data dummy.
Jangan mengisi master data otomatis.
Data akan diinput manual.
Jangan mengarang field yang belum memiliki sumber.
Struktur samples mengikuti Excel.
Detail database lainnya mengikuti kebutuhan PRD.md.

PRD = kebutuhan
Excel = struktur data sampel
WORKFLOW = alur proses