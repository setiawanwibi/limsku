# PRD LIMS — Balai Besar POM di Bandar Lampung | Versi 1.0

## PRODUCT REQUIREMENTS DOCUMENT

### LABORATORY INFORMATION MANAGEMENT SYSTEM (LIMS)

**Balai Besar POM di Bandar Lampung**

| Informasi Nilai | |
|---|---|
| Nama Sistem | LIMS — Laboratory Information Management System |
| Instansi | Balai Besar POM di Bandar Lampung |
| Versi PRD | 1.0 |
| Tanggal | 29 September 2026 |
| Framework | CodeIgniter 3 (CI3) |
| Bahasa Pemrograman | PHP 7.4 |
| Frontend | HTML5, CSS3, Bootstrap, JavaScript, jQuery |
| Database | MySQL |
| Target Pengguna | Admin, Petugas Laboratorium, Analis, Reviewer, Verifikator, Kepala/Pimpinan, Manajemen |
| Jenis Aplikasi | Web-based internal application |

## 1. Ringkasan Singkat

LIMS adalah aplikasi berbasis web untuk mengelola seluruh siklus pekerjaan laboratorium secara terintegrasi, mulai dari penerimaan dan registrasi sampel, permintaan pengujian, distribusi sampel, penugasan analis, pelaksanaan pengujian, pencatatan hasil, review/verifikasi, persetujuan hasil, penerbitan laporan/sertifikat hasil pengujian, hingga pelaporan dan monitoring.

Sistem ditujukan untuk mengurangi pencatatan manual, duplikasi input, kesalahan administrasi, kesulitan penelusuran status sampel, dan keterlambatan penyusunan laporan. Setiap tahapan memiliki status, pengguna yang bertanggung jawab, timestamp, serta riwayat perubahan sehingga proses dapat ditelusuri dari sampel masuk sampai hasil diterbitkan.

PRD ini menggunakan pola template yang diberikan: setiap layanan diterjemahkan menjadi modul/menu, setiap langkah alur menjadi status proses, setiap data menjadi field database, dan setiap aturan bisnis menjadi validasi otomatis.

### Tujuan Utama

- Membangun satu sumber data terpusat untuk kegiatan laboratorium.
- Mempercepat proses registrasi, distribusi, pengujian, review, dan penerbitan hasil.
- Memudahkan pelacakan posisi dan status setiap sampel.
- Mengurangi risiko kesalahan input dan kehilangan dokumen/rekaman pengujian.
- Menyediakan dashboard dan laporan operasional secara real-time.
- Menyediakan audit trail atas aktivitas penting pengguna dan perubahan data.
- Mendukung pengelolaan metode uji, parameter, alat, bahan/reagen, standar, dan kompetensi analis.

## 2. Siapa Saja yang Akan Memakai Sistem

Akses menggunakan akun pengguna dan role-based access control. Satu pengguna dapat memiliki satu atau lebih hak akses sesuai penugasan.

| Peran | Contoh Pengguna | Hak/Aktivitas Utama |
|---|---|---|
| Admin TI | Petugas TI | Mengatur master data (user, format form, template excel), dan memantau keseluruhan sistem. |
| Petugas Penerimaan Sampel | Petugas penerimaan & administrasi | Mengimpor data sampel via Excel atau dengan melakukan input data manual, dan memastikan data masuk ke database sampel. |
| Penguji | Analis laboratorium | Memilih/mengklaim sampel dari antrean, memilih form pengujian yang sesuai (12 form kimia / 1 form mikro), mengisi hasil uji, dan mengirimkannya ke Penyelia. |
| Penyelia (Verifikator) | Pejabat/penyelia verifikator | Menerima hasil pengujian dari penguji, memeriksa data, melakukan verifikasi, atau mengembalikan (revisi) ke penguji. |
| Approver/Pimpinan (Manajer Teknis) | Kepala/pejabat berwenang | Menyetujui hasil akhir dan penerbitan laporan/sertifikat sesuai kewenangan. |

### Hak Akses Minimum

- Menu dan tombol ditampilkan berdasarkan role.
- Pengguna hanya dapat mengubah data pada tahap proses yang menjadi tanggung jawabnya.
- Data yang sudah disetujui/locked tidak dapat diubah secara langsung.
- Perubahan terhadap data kritis harus meninggalkan audit trail.
- Penghapusan data operasional dilakukan dengan mekanisme pembatalan/void atau soft delete, bukan menghilangkan jejak transaksi.

## 3. Modul yang Ingin Dibuat Sistemnya

Setiap layanan di bawah diperlakukan sebagai modul/menu dan memiliki alur status sebagaimana prinsip template PRD.

### Komoditi Obat

### Modul A — Registrasi dan Penerimaan Sampel

**a) Langkah-langkahnya:**

1. Petugas melakukan import data sampel menggunakan format Excel (berdasarkan template baku) ke dalam sistem.
2. Data dari Excel tersebut akan otomatis terbaca (mapping kolom) dan masuk ke dalam database obat dengan kolom/field yang sesuai.
3. Petugas juga difasilitasi form input manual jika ada sampel yang tidak melalui Excel. Kolom form disesuaikan dengan database (terdapat indikator kolom wajib/opsional).
4. Setelah disimpan, data sampel berstatus "Menunggu Pengujian" dan masuk ke antrean (dashboard Penguji).

**b) Data yang disimpan:**

- Data entitas sampel (Nama obat, nomor bets, tanggal terima, pengirim, dll), petugas penginput, timestamp.

**c) Aturan khusus:**

- Nomor sampel harus unik; sampel yang ditolak wajib memiliki alasan; timestamp penerimaan otomatis.

### Modul B — Pemilihan Tugas dan Pelaksanaan Pengujian

**a) Langkah-langkahnya:**

1. Penguji membuka dashboard dan memilih sampel dari antrean yang ditujukan kepadanya (Klaim mandiri / Self-assignment agar mencegah bottleneck distribusi).
2. Penguji memilih jenis form pengujian. Sistem menyediakan 12 form pengujian Kimia dan 1 form pengujian Mikro.
3. Penguji mengisi data hasil uji. Setiap form memiliki isian (field) dan format pelaporan cetak yang berbeda-beda.
4. Setelah selesai dan disimpan, form dikirimkan ke Penyelia.

**b) Data yang disimpan:**

- ID Penguji, waktu mulai uji, data form (hasil uji dinamis), ID Template Form, status.

**c) Aturan khusus:**

- Saat Penguji meng-klik suatu sampel, sistem melakukan Locking agar sampel tersebut tidak bisa diklaim oleh Penguji lain secara bersamaan.

### Modul C — Verifkasi Hasil oleh Penyelia

**a) Langkah-langkahnya:**

1. Penyelia membuka dashboard "Menunggu Verifikasi" yang berisi data uji dari Penguji.
2. Penyelia memverifikasi data. Terdapat tombol "Verifikasi (Lanjut ke MT)" atau "Return/Revisi (Kembali ke Penguji)".

**b) Data yang disimpan:**

- ID Penyelia, waktu verifikasi, catatan/alasan verifikasi, status dokumen.

**c) Aturan khusus:**

- Analis hanya dapat menerima tugas sesuai kewenangan; konflik penugasan dapat ditandai oleh sistem.

### Modul D — Approval dan Tanda Tangan Otomatis Manajer Teknis (MT)

**a) Langkah-langkahnya:**

1. MT menerima data yang telah diverifikasi oleh Penyelia.
2. MT melakukan approval (Persetujuan).
3. Sistem secara otomatis men-generate laporan uji, membubuhkan Tanda Tangan Otomatis dan Cap MT, lalu menyimpannya sebagai file PDF read-only.

**b) Data yang disimpan:**

- ID Manajer Teknis, log persetujuan, file PDF final, timestamp

**c) Aturan khusus:**

- Data mentah tidak boleh dihapus setelah submission; koreksi harus tercatat sebagai revisi dengan alasan, dan File image tanda tangan dan cap MT harus disimpan di direktori yang terproteksi (tidak bisa diakses publik/URL langsung).

### Modul E — Penerbitan COA dan Arsip Laporan

**a) Langkah-langkahnya:**

1. Sistem mengambil hasil pengujian yang telah di-approve oleh Manajer Teknis (MT).
2. Sistem secara otomatis membentuk dokumen laporan (Certificate of Analysis / COA) sesuai dengan template form pengujian yang digunakan.
3. Sistem meng-generate dan memberikan nomor dokumen/sertifikat secara otomatis.
4. Laporan yang telah terbentuk (PDF) akan otomatis masuk ke menu Riwayat.
5. Pengguna berwenang (Petugas/Penguji/Penyelia/MT) dapat melihat, mencari histori, melakukan preview, serta mengunduh (soft file PDF) atau mencetak dokumen langsung dari menu Riwayat.

**b) Data yang tersimpan:**

- Nomor laporan/COA, nomor dokumen pengujian, identitas sampel, parameter uji, metode, hasil pengujian, satuan, spesifikasi, status kesesuaian, tanggal laporan diterbitkan, pejabat penandatangan (MT), path file PDF di server, dan checksum/versi file.

**c) Aturan khusus:**

- Dokumen final hanya dapat diterbitkan oleh sistem JIKA data hasil uji sudah berstatus Approved.
- Nomor dokumen/sertifikat yang di-generate sistem harus unik dan tidak boleh dipakai ulang (harus berurutan/otomatis).
- Dokumen PDF pada tahap ini telah dikunci (Locked), perubahan data pengujian sama sekali tidak diizinkan.

### Modul J — Monitoring, Dashboard dan Pelaporan

**a) Langkah-langkahnya:**

1. Dashboard menampilkan ringkasan operasional secara real-time, meliputi: jumlah sampel masuk (menunggu pengujian), sedang diuji, menunggu verifikasi penyelia, menunggu approval Manajer Teknis (MT), pekerjaan selesai, dan pekerjaan yang melewati batas waktu (terlambat).
2. Pengguna dapat memfilter data berdasarkan periode tanggal, jenis sampel, laboratorium/unit, metode, parameter, analis, dan status alur sampel.
3. Pengguna berwenang dapat mencetak atau mengekspor data laporan ke format Excel/PDF sesuai kebutuhan manajemen.

**b) Data yang disimpan:**

- Data agregasi transaksi, durasi waktu proses (lead time), status progres, indikator SLA, jenis sampel, parameter uji, analis penanggung jawab, hasil pengujian, dan status kesesuaian mutu.

**c) Aturan khusus:**

- Hak akses melihat laporan dan menu dashboard wajib mengikuti batasan role pengguna (Role-Based Access Control).
- Data yang tampil pada dashboard wajib konsisten secara real-time dengan tabel transaksi sumber di database.

## Alur Status Utama Sampel

| Tahap | Status | Keterangan / Trigger (Pemicu Perubahan Status) |
|---|---|---|
| 1 | Menunggu Pengujian | Data sampel berhasil di-import via Excel atau diinput manual oleh Petugas. Sampel masuk ke kolam antrean (queue) pengujian. |
| 2 | Dalam Pengujian | Penguji (Kimia/Mikro) memilih/mengklaim sampel dari antrean, memilih form pengujian yang sesuai (12 Kimia / 1 Mikro), dan sedang mengisi worksheet/hasil uji. |
| 3 | Menunggu Verifikasi | Penguji telah selesai mengisi form dan menekan tombol simpan/submit. Data uji otomatis terkirim ke dashboard Penyelia. |
| 4 | Perlu Perbaikan (Opsional) | Penyelia atau Manajer Teknis mengembalikan data uji ke Penguji untuk direvisi karena ditemukan ketidaksesuaian/kesalahan data. |
| 5 | Menunggu Approval MT | Penyelia telah memeriksa dan memverifikasi data uji, lalu meneruskannya secara resmi ke Manajer Teknis (MT). |
| 6 | Approved / Selesai (Laporan Terbit) | Manajer Teknis memberikan approval. Sistem secara otomatis menempelkan tanda tangan & cap digital (TTE), menerbitkan dokumen PDF Laporan/COA, dan menguncinya (locked). |

## Data Master yang Diperlukan

- User, Role, Permission, Unit/Laboratorium (Laboratorium Kimia dan Mikrobiologi).
- Jenis sampel, kategori produk, matriks, asal/pemilik sampel, serta Template Master Format Excel (untuk validasi import data sampel otomatis).
- Master Form Pengujian (13 Template Form Dinamis: 12 Form Kimia + 1 Form Mikro), metode uji, parameter, satuan, spesifikasi/batas persyaratan, dan versi metode.
- Alat/instrumen, lokasi, status kelayakan, jadwal kalibrasi, dan pemeliharaan.
- Bahan, reagen, dan standar, supplier, nomor batch, satuan, tanggal kedaluwarsa, dan lokasi penyimpanan.
- Jenis dokumen, template laporan/COA, serta Master Pejabat Penandatangan beserta konfigurasi file Tanda Tangan & Cap otomatis untuk Manajer Teknis.
- Status alur (6 tahap), prioritas, SLA, dan konfigurasi penomoran dokumen/sertifikat unik otomatis.

## Struktur Data/Entitas Utama

| Entitas | Fungsi |
|---|---|
| users | Akun dan identitas pengguna. |
| roles / permissions | Hak akses berbasis peran (Admin TI, Petugas, Penguji, Penyelia, Manajer Teknis). |
| laboratories / units | Unit atau laboratorium pelaksana (Laboratorium Kimia & Mikrobiologi). |
| samples | Identitas dan status sampel obat (hasil import Excel atau input manual). |
| form_templates | Master data yang menyimpan daftar Master Form Pengujian yang terdiri dari 2 pilihan yaitu Kimia dan Mikrobiologi (12 Template Form Dinamis: 12 Form Kimia, dan 2 Form Mikrobiologi), metode uji, parameter, satuan, spesifikasi/batas persyaratan, dan versi metode.
| test_results | Tabel transaksi pengujian. Menyimpan isian data hasil uji dari penguji (disarankan menggunakan tipe kolom JSON agar fleksibel menampung variasi field dari 13 form berbeda). |
| methods | Master metode uji dan versinya. |
| specifications | Batas / spesifikasi / persyaratan mutu parameter. |
| worksheets | Rekaman lembar kerja dan lampiran pengujian analis. |
| instruments | Master alat laboratorium. |
| Instrument_calibrations | Rekaman kalibrasi / verifikasi alat. |
| reagents / materials | Master bahan, reagen, dan standar. |
| reagent_usage | Rekaman pemakaian bahan pada pekerjaan pengujian. |
| workflow_logs | Menyimpan riwayat transisi status alur kerja (Verifikasi Penyelia & Approval Manajer Teknis). |
| reports / coa | Rekaman dokumen laporan akhir / COA berformat PDF yang telah dikunci (locked) beserta Tanda Tangan Otomatis (TTE). |
| attachments | Lampiran dokumen pendukung. |
| notifications | Sistem notifikasi tugas dan status. |
| audit_logs | Riwayat aktivitas penting user dan perubahan data. |

## 4. Laporan & Dashboard yang Dibutuhkan

Dashboard adalah ringkasan kondisi operasional saat pengguna masuk. Laporan adalah keluaran yang dapat ditampilkan, dicetak, atau diekspor. Prinsip ini mengikuti template PRD yang membedakan dashboard dan laporan.

| Dashboard/Laporan | Isi | Filter Utama | Output |
|---|---|---|---|
| Dashboard Eksekutif | Total sampel, pekerjaan aktif, selesai, terlambat, menunggu review/approval. | Periode, unit, jenis sampel | Web/PDF |
| Dashboard Laboratorium | Antrian pekerjaan, tugas analis, status worksheet, SLA. | Lab, analis, status, periode | Web |
| Monitoring Sampel | Daftar sampel dari diterima sampai closed. | Nomor, periode, status, jenis sampel | Excel/PDF |
| Rekap Pengujian | Jumlah parameter/pengujian per periode. | Metode, parameter, lab, analis | Excel/PDF |
| Laporan TAT/SLA | Waktu dari penerimaan sampai hasil/laporan. | Periode, lab, kategori | Excel/PDF |
| Laporan Hasil Uji | Daftar hasil dan status kesesuaian. | Sampel, parameter, periode | Excel/PDF |
| Laporan COA | Dokumen final per sampel. | Nomor sampel/COA | PDF |
| Kinerja Analis | Jumlah tugas, selesai, terlambat, return/revisi. | Analis, periode | Web/Excel |
| Status Alat | Alat aktif, jatuh tempo kalibrasi, maintenance. | Lab, status, periode | Web/Excel |
| Stok Reagen | Stok, minimum, expired/near expired. | Lokasi, kategori | Web/Excel |
| Audit Trail | Aktivitas user dan perubahan data penting. | User, modul, periode | Excel/PDF |

### Indikator Dashboard

- Total sampel diterima hari ini/bulan ini.
- Total pengujian aktif dan selesai.
- Jumlah pekerjaan berdasarkan status.
- Jumlah pekerjaan melewati SLA.
- Rata-rata waktu penyelesaian.
- Jumlah hasil yang perlu review/verifikasi/approval.
- Jumlah alat mendekati jatuh tempo kalibrasi.
- Jumlah reagen mendekati kedaluwarsa.
- Tren jumlah sampel/pengujian per periode.
- Distribusi hasil berdasarkan status kesesuaian.

# 5. Catatan untuk AI Coding Assistant

Bagian ini menjadi instruksi implementasi. Struktur dasarnya mengikuti aturan template: layanan menjadi modul, langkah menjadi status, data menjadi field database, aturan menjadi validasi, dan laporan menjadi dashboard/ekspor.

## 5.1 Teknologi Wajib

| Komponen | Ketentuan |
|---|---|
| Backend | CodeIgniter 3 |
| PHP | PHP 7.4 |
| Database | MySQL |
| Frontend | HTML5, CSS3, Bootstrap, JavaScript, jQuery |
| AJAX | jQuery AJAX untuk proses tanpa reload bila sesuai. |
| Table | DataTables untuk daftar data yang besar. |
| Form | Validasi server-side CI3 dan validasi client-side. |
| PDF | Gunakan library PDF yang kompatibel dengan PHP 7.4, misalnya mPDF/Dompdf sesuai lingkungan instalasi. |
| Export | Excel/CSV menggunakan library yang kompatibel dengan PHP 7.4. |
| Arsitektur | MVC CodeIgniter 3, controller tipis, business logic pada model/service yang terstruktur. |

## 5.2 Struktur Menu

- Dashboard
- Registrasi Sampel (Import Excel & Input Manual)
- Pengujian & Worksheet (Antrean Sampel & 13 Form Dinamis)
- Verifikasi (Penyelia)
- Approval (Manajer Teknis & TTE)
- Laporan / COA (Dokumen Final)
- Metode & Parameter
- Alat Laboratorium
- Bahan / Reagen / Standar
- Monitoring & Laporan
- Notifikasi
- Master Data
- Manajemen Pengguna
- Audit Trail
- Pengaturan Sistem

## 5.3 Aturan UX/UI

- Gunakan Bootstrap dengan tampilan responsif desktop, tablet dan mobile.
- Gunakan sidebar menu, topbar/header, breadcrumb dan footer yang konsisten.
- Gunakan card/dashboard dengan indikator warna yang mudah dipahami.
- Gunakan DataTables untuk pencarian, filter, sorting dan pagination.
- Gunakan modal untuk konfirmasi aksi penting.
- Form dibuat bertahap/sectioned agar input data laboratorium yang panjang tetap mudah digunakan.
- Field wajib diberi penanda dan validasi pesan kesalahan dalam bahasa Indonesia.
- Status harus terlihat jelas pada badge.
- Dokumen dan hasil dapat dipreview sebelum dicetak.

## 5.4 Keamanan

- Gunakan session authentication CodeIgniter.
- Password disimpan menggunakan hashing yang aman dan bukan plaintext.
- Gunakan CSRF protection.
- Validasi dan sanitasi seluruh input.
- Gunakan query binding/Query Builder untuk mencegah SQL Injection.
- Batasi upload berdasarkan ekstensi, MIME, ukuran dan lokasi penyimpanan.
- Gunakan permission per menu dan per aksi: view, create, update, delete/void, approve, export.
- Catat login, logout, create, update, status transition, approval, export dan perubahan data kritis pada audit log.
- Gunakan soft delete/void untuk data transaksi; jangan menghapus rekaman yang diperlukan untuk penelusuran.
- Data approved/locked bersifat read-only.

## 5.5 Aturan Database

- Gunakan mesin penyimpanan InnoDB pada MariaDB.
- Gunakan primary key numerik dan foreign key secara konsisten bila sesuai desain.
- Nomor sampel dan nomor laporan/COA harus unik (referensi nomor permintaan/tugas yang sudah ditiadakan telah dihapus).
- Gunakan kolom standar created_at, updated_at, serta created_by / updated_by pada tabel transaksi utama.
- Gunakan kolom status pada transaksi untuk mengendalikan alur kerja (workflow 6 tahap).
- Gunakan indeks pada nomor sampel, tanggal, status, unit, dan foreign key untuk optimalisasi performa kueri.
- Penyimpanan Data 13 Form Pengujian Dinamis: Menggunakan tipe data kolom JSON pada MariaDB guna menampung variasi field yang berbeda-beda dari 12 form Kimia dan 5 form Mikrobiologi secara fleksibel.
- File / lampiran wajib memiliki metadata lengkap: nama file, path, ukuran, MIME type, uploader, tanggal unggah, dan keterkaitan transaksi.

## 5.6 Validasi Workflow

| Aturan Perilaku Sistem | |
|---|---|
| Registrasi | Tidak dapat disimpan jika field wajib (mandatory) belum lengkap atau format import Excel tidak sesuai template. |
| Penerimaan sampel | Sistem wajib men-generate nomor sampel unik dan berstatus "Menunggu Pengujian". |
| Penugasan | Tidak dapat ditutup jika belum ada analis/penanggung jawab. |
| Pengujian | Metode yang dipilih harus aktif dan sesuai parameter. |
| Alat | Alat tidak layak/expired tidak dapat digunakan bila aturan metode mensyaratkannya. |
| Reagen | Reagen expired tidak dapat dipilih. |
| Submission | Worksheet wajib lengkap sebelum submit. |
| Review | Reviewer wajib mengisi catatan jika return. |
| Approval | Approval hanya dapat dilakukan oleh role berwenang. |
| Final | Hasil approved tidak dapat diedit langsung. |
| COA | COA hanya dapat dibuat dari hasil approved. |
| Audit | Setiap perubahan kritis dicatat beserta user, waktu, aksi dan referensi transaksi. |

## 5.7 Notifikasi

- Notifikasi ketersediaan sampel baru di antrean bagi Penguji.
- Notifikasi pekerjaan mendekati atau melewati batas SLA.
- Notifikasi hasil uji masuk bagi Penyelia untuk segera diverifikasi.
- Notifikasi hasil verifikasi yang masuk ke Manajer Teknis untuk approval & TTE.
- Notifikasi pekerjaan yang dikembalikan ke penguji (perlu perbaikan).
- Notifikasi alat laboratorium yang mendekati jatuh tempo kalibrasi.
- Notifikasi stok reagen/bahan yang mendekati tanggal kedaluwarsa (near expired).

## 5.8 Kriteria Penerimaan Sistem

- Admin TI dapat mengelola akun pengguna, role, permission, dan unit laboratorium secara fleksibel.
- Petugas dapat mendaftarkan sampel melalui fitur Import Excel maupun Input Manual hingga memperoleh nomor sampel yang unik.
- Penguji dapat melakukan klaim mandiri (self-assignment) terhadap sampel dari antrean yang tersedia.
- Penguji dapat mengisi dan menyimpan data pengujian menggunakan 13 Template Form Pengujian Dinamis (12 Kimia + 1 Mikro).
- Penyelia dapat melakukan verifikasi terhadap data hasil uji yang dikirimkan oleh penguji, serta mengembalikannya (perlu perbaikan) jika ditemukan ketidaksesuaian.
- Manajer Teknis dapat melakukan approval hasil uji dan sistem secara otomatis memicu Tanda Tangan Elektronik (TTE)/cap digital.
- Sistem hanya dapat menerbitkan dokumen COA/laporan final dari hasil pengujian yang sudah berstatus approved dan terkunci (locked).
- Seluruh perubahan status dan riwayat pengujian dapat ditelusuri dengan mudah berdasarkan nomor sampel.
- Dashboard menampilkan ringkasan data operasional secara real-time sesuai dengan transaksi sistem.
- Laporan operasional, SLA, dan rekapitulasi dapat difilter dan diekspor ke format Excel atau PDF.
- Audit trail dapat merekam secara transparan terkait siapa yang melakukan aksi, apa yang diubah, dan kapan waktu kejadiannya.
- Role atau pengguna yang tidak berwenang tidak diizinkan mengubah data pada tahap alur kerja (workflow) yang bukan hak aksesnya.
- Data yang sudah final dan terkunci (locked) tetap dapat diakses untuk penelusuran histori tanpa menghilangkan jejak rekamannya.

## 5.9 Tahapan Pengembangan yang Disarankan

| Tahap | Modul | Hasil |
|---|---|---|
| Fase 1 | Login, user, role, master unit | Fondasi keamanan dan master. |
| Fase 2 | Registrasi Sampel (Import Excel & Input Manual) | Sampel terdaftar dalam database dan dapat dilacak nomor uniknya. |
| Fase 3 | Antrean & Pengujian (Self-Assignment + 13 Form Dinamis) | Penguji dapat mengklaim sampel dan mengisi form pengujian. |
| Fase 4 | Verifikasi Penyelia | Pengendalian kualitas dan validasi hasil uji oleh Penyelia. |
| Fase 5 | Approval Manajer Teknis & TTE Otomatis | Persetujuan akhir dan pembubuhan Tanda Tangan Elektronik. |
| Fase 6 | COA/laporan | Dokumen hasil uji berformat PDF terkunci (locked). |
| Fase 7 | Alat, reagen, Metode, dan spesifikasi | Traceability sumber daya dan instrumen laboratorium. |
| Fase 8 | Dashboard, Monitoring SLA, & Audit Trail | Pemantauan operasional dan riwayat aktivitas sistem. |
| Fase 9 | UAT, keamanan, backup, deployment | Sistem siap operasional. |

## 5.10 Prompt Utama untuk AI Coding Assistant

Bangun aplikasi LIMS berbasis web untuk Balai Besar POM di Bandar Lampung menggunakan CodeIgniter 3, PHP 7.4, Bootstrap, jQuery/AJAX, dan MariaDB. Gunakan PRD ini sebagai sumber requirement utama. Implementasikan arsitektur MVC yang rapi, role-based access control (Admin TI, Petugas, Penguji, Penyelia, Manajer Teknis), status workflow 6 tahap, validasi server/client, audit trail, dashboard, DataTables, upload dokumen, ekspor PDF/Excel, serta desain database relasional MariaDB dengan tipe kolom JSON untuk 13 form pengujian dinamis.

Bangun modul secara bertahap mulai dari:

1. Autentikasi dan master data.
2. Registrasi sampel (Import Excel & Input Manual).
3. Antrean pengujian dan self-assignment oleh penguji.
4. Pengisian 13 form pengujian dinamis (12 Kimia + 1 Mikro).
5. Verifikasi oleh Penyelia.
6. Approval oleh Manajer Teknis beserta pemicu TTE otomatis.
7. Penerbitan COA/laporan final.
8. Manajemen alat, reagen, metode, dashboard, dan laporan.

Jangan menghapus histori transaksi. Semua perubahan data penting harus tercatat dalam audit trail. Setiap modul harus memiliki controller, model, view, route, validasi, permission, dan migrasi/SQL yang jelas. Gunakan bahasa Indonesia pada label UI, pesan validasi, dan status.

## Catatan Penting untuk Implementasi

PRD ini merupakan kebutuhan produk dan rancangan fungsional. Detail seperti struktur organisasi laboratorium, daftar metode uji, parameter resmi, format COA, aturan penomoran dokumen, SLA, pejabat yang berwenang melakukan approval, serta integrasi dengan sistem lain perlu dikonfirmasi sebelum implementasi final. Jika suatu aturan belum ditetapkan oleh unit kerja, jangan dibuat sebagai aturan permanen tanpa persetujuan pemilik proses.
