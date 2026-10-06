# LIMSKU — Workflow

## 1. Tujuan

Dokumen ini menjelaskan workflow final LIMSKU dari penerimaan sample sampai laporan final.

Workflow ini mengikuti flowchart sistem yang telah ditetapkan.

---

## 2. Aktor

Workflow terdiri dari:

1. Admin Lab / Petugas Penerima Sample
2. Penguji
3. Penyelia
4. Manajer Teknis (MT)
5. Admin IT

Laporan final dapat diakses oleh semua role.

---

## 3. Workflow Utama

```text
MULAI
  ↓
LOGIN
  ↓
TERIMA SAMPLE
  ↓
INPUT DATA SAMPLE
(Manual / Import Excel)
  ↓
KIRIM SAMPLE KE SISTEM
  ↓
Sample masuk daftar yang harus diuji
Status: Menunggu Pengujian
  ↓
PENGUJI
  ↓
Lihat daftar sample yang harus diuji
  ↓
Pilih Sample
(status menjadi Sedang Diuji)
  ↓
Pilih Metode Pengujian
  ↓
Sistem menampilkan Form Pengujian
sesuai template metode
  ↓
Input hasil uji sesuai parameter
  ↓
Simpan
  ↓
Data hasil uji tersimpan di database
  ↓
Sistem otomatis membentuk
Laporan Hasil Uji (PDF)
  ↓
MENUNGGU VERIFIKASI
  ↓
PENYELIA
  ↓
Lihat daftar laporan yang harus diverifikasi
  ↓
Pilih Laporan
  ↓
Verifikasi?
 ┌───────────────┴───────────────┐
 YA                              TIDAK
 ↓                                ↓
Laporan Terverifikasi             Tolak + wajib isi
Status: Menunggu Approval         komentar/alasan penolakan
 ↓                                ↓
Simpan hasil verifikasi           Laporan ditolak kembali
 ↓                                ke Penguji
 ↓                                ↓
MENUNGGU APPROVAL                  Revisi
 ↓                                ↓
MT                                Kembali ke Penguji
 ↓
Lihat daftar laporan
yang harus di-approve
 ↓
Pilih Laporan
 ↓
Laporan di Approve
 ↓
Simpan hasil approval
 ↓
Sistem generate PDF final
dengan tanda tangan otomatis
 ↓
Laporan Final / Tersedia
untuk semua role
4. Detail Setiap Tahap
4.1 Admin Lab / Petugas Penerima Sample

Proses:

Login
 ↓
Terima Sample
 ↓
Input Data Sample
(Manual / Import Excel)
 ↓
Kirim Sample ke Sistem

Setelah dikirim, sample masuk ke daftar pengujian dengan status:

Menunggu Pengujian
4.2 Penguji

Penguji:

Login
 ↓
Lihat daftar sample yang harus diuji
 ↓
Pilih Sample
 ↓
Pilih Metode Pengujian
 ↓
Sistem menampilkan Form Pengujian
sesuai template metode
 ↓
Input hasil uji sesuai parameter
 ↓
Simpan

Saat sample dipilih, status menjadi:

Sedang Diuji

Setelah hasil disimpan:

Data hasil uji tersimpan di database

Sistem kemudian otomatis membentuk:

Laporan Hasil Uji (PDF)

Setelah itu laporan masuk tahap:

Menunggu Verifikasi
4.3 Penyelia

Penyelia:

Login
 ↓
Lihat daftar laporan yang harus diverifikasi
 ↓
Pilih Laporan
 ↓
Verifikasi

Terdapat dua kemungkinan.

Jika Verifikasi = Ya
Laporan Terverifikasi
Status: Menunggu Approval
 ↓
Simpan hasil verifikasi
 ↓
Menunggu Approval MT
Jika Verifikasi = Tidak

Penyelia wajib mengisi:

Komentar / Alasan Penolakan

Kemudian:

Laporan ditolak
 ↓
Kembali ke Penguji
 ↓
Penguji melakukan revisi

Setelah revisi, laporan kembali mengikuti proses pengujian dan verifikasi.

4.4 Manajer Teknis (MT)

MT:

Login
 ↓
Lihat daftar laporan yang harus di-approve
 ↓
Pilih Laporan
 ↓
Laporan di Approve
 ↓
Simpan hasil approval

Setelah approval berhasil:

Sistem generate PDF final
dengan tanda tangan otomatis

Hasil akhirnya:

Laporan Final
Tersedia untuk semua role
4.5 Admin IT

Admin IT memiliki akses untuk:

Login
 ↓
Kelola Pengguna & Hak Akses
 ↓
Kelola Master Data

Master data meliputi kebutuhan sistem seperti:

Metode
Parameter
Template
dan master data lainnya
5. Akses Laporan Final

Setelah laporan final tersedia, semua role dapat mengakses laporan.

Laporan Tersedia
       ↓
Lihat daftar laporan
(Approved / Ditolak)
       ↓
Pilih Laporan
       ↓
Download PDF
       ↓
Selesai
6. Status Workflow

Status utama yang digunakan:

Menunggu Pengujian
        ↓
Sedang Diuji
        ↓
Menunggu Verifikasi
        ↓
Menunggu Approval
        ↓
Approved / Final

Jika ditolak oleh Penyelia:

Menunggu Verifikasi
        ↓
Ditolak
        ↓
Kembali ke Penguji
        ↓
Revisi
        ↓
Pengujian / Verifikasi kembali
7. Aturan Utama
Sample harus diterima dan dimasukkan ke sistem sebelum dapat diuji.
Penguji memilih sample yang tersedia untuk diuji.
Metode pengujian dipilih sebelum form pengujian ditampilkan.
Form pengujian mengikuti template metode.
Hasil uji disimpan ke database.
Sistem otomatis membentuk Laporan Hasil Uji (PDF).
Laporan harus diverifikasi oleh Penyelia.
Penolakan wajib disertai komentar/alasan.
Laporan yang ditolak kembali ke Penguji untuk revisi.
Laporan yang terverifikasi menunggu approval MT.
Setelah approval, sistem menghasilkan PDF final dengan tanda tangan otomatis.
Laporan final tersedia untuk semua role.
Aktivitas dan perubahan penting harus dapat ditelusuri melalui sistem.
8. Alur Singkat Final
ADMIN LAB
Terima Sample
     ↓
Input / Import Excel
     ↓
Kirim ke Sistem
     ↓
PENGUJI
Pilih Sample
     ↓
Pilih Metode
     ↓
Isi Form Pengujian
     ↓
Simpan Hasil
     ↓
Laporan Hasil Uji PDF
     ↓
PENYELIA
Verifikasi
  ↙       ↘
YA         TIDAK
↓           ↓
Menunggu    Komentar /
Approval    Alasan Penolakan
↓           ↓
MT          Kembali ke Penguji
↓
Approve
↓
PDF Final + Tanda Tangan Otomatis
↓
Laporan Final
↓
Dapat Diakses Semua Role
↓
Download PDF
↓
SELESAI

9. Reference

Workflow ini merupakan acuan proses bisnis LIMSKU.

Dokumen lain:

PRD.md → kebutuhan sistem
DESIGN.md → desain UI/UX
ARCHITECTURE.md → struktur teknis
DATABASE.md → struktur data

Jika terdapat kebutuhan baru yang mengubah workflow, perubahan harus disesuaikan dengan workflow final dan requirement sistem.