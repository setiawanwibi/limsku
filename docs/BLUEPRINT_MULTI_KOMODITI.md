# BLUEPRINT PENGEMBANGAN MULTI-KOMODITI LIMSKU
**Laboratorium Informasi Manajemen Sistem Kualitas & Uji (LIMSKU)**
*Dokumen Rencana Masa Depan Pasca-Peluncuran Komoditi Obat*

---

## 1. Latar Belakang & Prinsip Utama
Meskipun saat ini LIMSKU difokuskan dan diselesaikan secara tuntas untuk **Komoditi Obat**, sistem dirancang agar siap berkembang ke seluruh komoditi uji laboratorium pengawasan mutu:
1. **Obat** (Terapetik & Narkotika) — *Status: Selesai / Siap Presentasi*
2. **Pangan** (Pangan Olahan, Makanan, Minuman, Air)
3. **Obat Bahan Alam / Herbal** (Jamu, OHT, Fitofarmaka, Suplemen)
4. **Kosmetik** (Produk Perawatan, Riasan)

Setiap komoditi memiliki **personil (penguji & penyelia), alur kerja, metode acuan regulasi, dan formula perhitungan** yang spesifik dan berbeda antar laboratorium.

---

## 2. Model Arsitektur: "Departmental Workspace" (Satu Aplikasi LIMSKU)
Untuk menghindari fragmentasi data dan duplikasi kode sistem, LIMSKU tetap menggunakan **satu platform terpadu** dengan pemisahan workspace:

```
LIMSKU Enterprise Core
│
├── 🔑 Core Auth, Audit Trail & Hak Akses
├── 🧪 Manajemen Reagen & Instrumentasi (Shared)
│
├── 📁 Workspace Lab Obat (Obat Jadi, Narkotika)
│   ├── Personil: Penguji & Penyelia Lab Obat
│   └── Acuan: Farmakope Indonesia (FI), LCP Kadar/Disolusi
│
├── 📁 Workspace Lab Pangan (Pangan & Air)
│   ├── Personil: Penguji & Penyelia Lab Pangan
│   └── Acuan: SNI, PerBPOM Pangan, LCP ALT Pangan, BTP
│
├── 📁 Workspace Lab Herbal / Obat Bahan Alam
│   ├── Personil: Penguji & Penyelia Lab Herbal
│   └── Acuan: Farmakope Herbal Indonesia (FHI), LCP Skrining BKO
│
└── 📁 Workspace Lab Kosmetik
    ├── Personil: Penguji & Penyelia Lab Kosmetik
    └── Acuan: PerBPOM Kosmetik, ASEAN Cosmetic Directive, Logam Berat
```

---

## 3. Aspek Utama yang Akan Diterapkan
1. **Assignment by Commodity:**
   - Personil analis/penyelia dipetakan ke unit laboratorium masing-masing sehingga antrean dan verifikasi terisolasi secara rapi.
   - Manajemen (Manajer Teknis / Kepala Balai) dapat memantau secara menyeluruh (cross-commodity).
2. **Template LCP Dinamis per Komoditi:**
   - Memanfaatkan *Dynamic Template Engine* LIMSKU untuk mendaftarkan formula kalkulasi spesifik tanpa perlu merombak controller inti.
3. **Standarisasi Registrasi Sampel:**
   - Penyesuaian atribut identitas sampel (No Izin Edar, No Notifikasi NA, Kadaluarsa, P-IRT, dll.) berdasarkan komoditi yang dipilih.
4. **Laporan & Monitoring Multidimensi:**
   - Filter komoditi pada modul monitoring dan pembedaan format template laporan PDF sesuai standar masing-masing bidang.

---
*Dokumen ini disimpan sebagai referensi untuk diagendakan kembali setelah sesi evaluasi dan presentasi dengan pihak Laboratorium Obat dan Admin TI selesai.*
