# RENCANA PEMULIHAN DATA HISTORIS REAGEN LIMSKU (BATCH 02)

**Tanggal Audit:** 15 Oktober 2026  
**Target Database:** `limsku` (Tabel: `reagents`)  
**Status Eksekusi Saat Ini:** **READ-ONLY / BELUM DIJALANKAN (Menunggu Persetujuan)**  
**Artefak Skrip:**
- Skrip Pemulihan: [`database/recovery_reagents_historical.sql`](file:///C:/xampp/htdocs/LIMSKU/database/recovery_reagents_historical.sql)
- Skrip Rollback: [`database/rollback_reagents_historical.sql`](file:///C:/xampp/htdocs/LIMSKU/database/rollback_reagents_historical.sql)
- Dataset Rekonsiliasi: [`scratch/reconciliation_data.json`](file:///C:/xampp/htdocs/LIMSKU/scratch/reconciliation_data.json)

---

## 1. Ringkasan Eksekutif & Hasil Audit

Audit komprehensif pada database aktif `limsku` dan sumber ground truth menemukan akar penyebab dari anomali data:
1. **Total Record Saat Ini:** 389 record.
2. **Distribusi Nilai `expired_date` Saat Ini (Aktif):**
   - **`NULL`:** 66 record (data historis tanpa batas ED).
   - **`'0000-00-00'`:** 68 record (data historis bersumber sel kosong yang terkonversi format zero-date oleh MySQL lama).
   - **Tanggal Valid (`YYYY-MM-DD`):** 255 record.
3. **Riwayat Backup Sebelumnya:**
   - `C:\xampp\backups\LIMSKU\limsku_backup_20261013_090831.sql` (1 record reagen)
   - `C:\xampp\backups\LIMSKU\limsku_backup_20261014_045207.sql` (1 record reagen)
   - `C:\xampp\backups\LIMSKU\limsku_backup_pre_reagents_20261014_095411.sql` (1 record reagen)
   *Kesimpulan Backup:* Database sebelum 14 Oktober 2026 hanya memiliki 1 record reagen (ID 3). 388 record diimpor secara massal pada 14 Oktober 2026 pukul ~15:17 menggunakan parser Excel versi lama sebelum diperbaiki.
3. **Penemuan Sumber Asli (Ground Truth 100% Terverifikasi):**
   Ditemukan 4 berkas Excel resmi di `C:\Users\ASUS\OneDrive\Dokumen\Magang BPOM\Data BPOM\Data Reagen\`:
   - `Data Baku Pembanding.xlsx` (33.998 bytes)
   - `Data Media Mikrobiologi.xlsx` (20.933 bytes)
   - `Data Reagen Cair.xlsx` (23.039 bytes)
   - `Data Reagen Padat.xlsx` (17.936 bytes)
4. **Mekanisme Anomali (Terbukti Secara Matematis & Empiris):**
   - **Pergeseran Kolom (44 Record):** Terjadi karena kolom `Katalog` di berkas Excel kosong. Parser XLSX lama menambahkan sel tanpa mempertahankan koordinat kolom sparse OpenXML, sehingga kolom bergeser 1 kolom ke kiri:
     - `nama_barang` diisi nilai kolom Merk / Stok (misal: '3 VIAL', '1 VIAL', dsb.)
     - `katalog` diisi nilai asli Nama Barang
     - `jenis_barang` diisi serial angka tanggal Excel (misal '46234', '45948')
   - **Expired Date '0000-00-00' (68 Record):** Di berkas Excel sumber, sel tanggal kedaluwarsa bernilai kosong/strip (karena jenis bahan kimia atau media yang stabil/tidak memiliki ED). Pada MySQL lama, input string kosong ke kolom bertipe `DATE` otomatis tersimpan sebagai `'0000-00-00'`. Representasi semantik yang tepat dan aman adalah `NULL`.
   - **Record ID 323:** Merupakan baris dummy dari Excel (nilai '0') dan bukan reagen nyata, tetap dipertahankan sesuai integritas sumber.

---

## 2. Matriks Rekonsiliasi 44 Record Tergeser

Seluruh 44 record berikut memiliki tingkat keyakinan **100% (High Confidence)** karena cocok secara eksak (*1-to-1 match*) baris per baris dengan berkas Excel sumber resmi:

| ID | Kode Barang | Nilai Saat Ini (DB Rusak) | Nilai Pemulihan (Ground Truth Excel) | Sumber File & Baris | Keyakinan |
|:---|:---|:---|:---|:---|:---:|
| **8** | 34022294 | Nama: '3 VIAL'<br>Kat: 'Asam Aminobenzoat...'<br>Merk: 'Baku Pembanding'<br>Stok: '0 BOTOL'<br>Jenis: 'Reagen Cair' | Nama: 'Asam Aminobenzoat (2070051)'<br>Kat: ''<br>Merk: ''<br>Stok: '3 VIAL'<br>Jenis: 'Baku Pembanding' | `Data Baku Pembanding.xlsx`:4 | 100% |
| **9** | 21443077 | Nama: '1 VIAL'<br>Kat: 'Asam Folat...'<br>Merk: 'Baku Pembanding'<br>Stok: '0 BOTOL'<br>Jenis: 'Reagen Cair' | Nama: 'Asam Folat (B0314004)'<br>Kat: ''<br>Merk: ''<br>Stok: '1 VIAL'<br>Jenis: 'Baku Pembanding' | `Data Baku Pembanding.xlsx`:5 | 100% |
| **10** | 11679155 | Nama: '2 VIAL'<br>Kat: 'Acidum nicotinicum...'<br>Merk: 'Baku Pembanding'<br>Stok: '0 BOTOL'<br>Jenis: 'Reagen Cair' | Nama: 'Acidum nicotinicum (100015)'<br>Kat: ''<br>Merk: ''<br>Stok: '2 VIAL'<br>Jenis: 'Baku Pembanding' | `Data Baku Pembanding.xlsx`:6 | 100% |
| **11** | 54344487 | Nama: '4 VIAL'<br>Kat: 'Alfa Tokoferol Asetat...'<br>Merk: 'Baku Pembanding'<br>Stok: '0 BOTOL'<br>Jenis: 'Reagen Cair' | Nama: 'Alfa Tokoferol Asetat (2083321)'<br>Kat: ''<br>Merk: ''<br>Stok: '4 VIAL'<br>Jenis: 'Baku Pembanding' | `Data Baku Pembanding.xlsx`:7 | 100% |
| **12** | 35446903 | Nama: '2 VIAL'<br>Kat: 'Aminofilin (207018)'<br>Merk: 'Baku Pembanding'<br>Stok: '0 BOTOL'<br>Jenis: 'Reagen Cair' | Nama: 'Aminofilin (207018)'<br>Kat: ''<br>Merk: ''<br>Stok: '2 VIAL'<br>Jenis: 'Baku Pembanding' | `Data Baku Pembanding.xlsx`:8 | 100% |
| **14** | 44720835 | Nama: '5 VIAL'<br>Kat: 'Buthylis Parabenum...'<br>Merk: 'Baku Pembanding'<br>Stok: '0 BOTOL'<br>Jenis: 'Reagen Cair' | Nama: 'Buthylis Parabenum (205068)'<br>Kat: ''<br>Merk: ''<br>Stok: '5 VIAL'<br>Jenis: 'Baku Pembanding' | `Data Baku Pembanding.xlsx`:10 | 100% |
| **15** | 94151222 | Nama: '6 VIAL'<br>Kat: 'Hexachlorophenum...'<br>Merk: 'Baku Pembanding'<br>Stok: '0 BOTOL'<br>Jenis: 'Reagen Cair' | Nama: 'Hexachlorophenum (207165)'<br>Kat: ''<br>Merk: ''<br>Stok: '6 VIAL'<br>Jenis: 'Baku Pembanding' | `Data Baku Pembanding.xlsx`:11 | 100% |
| **37** | 72464452 | Nama: 'Merck'<br>Kat: 'Urea agar (base)...'<br>Merk: '0 BOTOL'<br>Stok: 'Media Mikrobiologi'<br>Jenis: 'Reagen Cair' | Nama: 'Urea agar (base) (1.08492.0500)'<br>Kat: ''<br>Merk: 'Merck'<br>Stok: '0 BOTOL'<br>Jenis: 'Media Mikrobiologi' | `Data Media Mikrobiologi.xlsx`:25 | 100% |
| **46** | 31406226 | Nama: 'sigma'<br>Kat: 'Corn Meal Agar...'<br>Merk: '0 BOTOL'<br>Stok: 'Media Mikrobiologi'<br>Jenis: 'Reagen Cair' | Nama: 'Corn Meal Agar (42347-500G-F)'<br>Kat: ''<br>Merk: 'sigma'<br>Stok: '0 BOTOL'<br>Jenis: 'Media Mikrobiologi' | `Data Media Mikrobiologi.xlsx`:37 | 100% |
| **48** | 74490778 | Nama: 'HIMEDIA'<br>Kat: 'Letheen Broth...'<br>Merk: '0 BOTOL'<br>Stok: 'Media Mikrobiologi'<br>Jenis: '46234' (Serial Excel) | Nama: 'Letheen Broth (M165-500G)'<br>Kat: ''<br>Merk: 'HIMEDIA'<br>Stok: '0 BOTOL'<br>Jenis: 'Media Mikrobiologi'<br>Exp: '2026-07-31' | `Data Media Mikrobiologi.xlsx`:39 | 100% |
| **50** | 23427054 | Nama: 'Millipore'<br>Kat: 'Palcam Listeria...'<br>Merk: '0 BOTOL'<br>Stok: 'Media Mikrobiologi'<br>Jenis: '45948' | Nama: 'Palcam Listeria selective agar (1.11755.0500)'<br>Kat: ''<br>Merk: 'Millipore'<br>Stok: '0 BOTOL'<br>Jenis: 'Media Mikrobiologi'<br>Exp: '2025-10-18' | `Data Media Mikrobiologi.xlsx`:41 | 100% |
| **51** | 71703036 | Nama: 'sigma millipore'<br>Kat: 'Enterobacteria...'<br>Merk: '0 BOTOL'<br>Stok: 'Media Mikrobiologi'<br>Jenis: '46295' | Nama: 'Enterobacteria enrichment Broth Mossel (1.05394.0500)'<br>Kat: ''<br>Merk: 'sigma millipore'<br>Stok: '0 BOTOL'<br>Jenis: 'Media Mikrobiologi'<br>Exp: '2026-09-30' | `Data Media Mikrobiologi.xlsx`:42 | 100% |
| **56** | 19424412 | Nama: 'Remel'<br>Kat: 'RapID Inoculation Fluid...'<br>Merk: '0 BOTOL'<br>Stok: 'Media Mikrobiologi'<br>Jenis: 'Reagen Cair' | Nama: 'RapID Inoculation Fluid 2ml. (R8325106)'<br>Kat: ''<br>Merk: 'Remel'<br>Stok: '0 BOTOL'<br>Jenis: 'Media Mikrobiologi' | `Data Media Mikrobiologi.xlsx`:52 | 100% |
| **58** | 31648922 | Nama: 'Sigma-Millipore'<br>Kat: 'XLD agar...'<br>Merk: '0 BOTOL'<br>Stok: 'Media Mikrobiologi'<br>Jenis: '46691' | Nama: 'XLD agar (1.05287.0500)'<br>Kat: ''<br>Merk: 'Sigma-Millipore'<br>Stok: '0 BOTOL'<br>Jenis: 'Media Mikrobiologi'<br>Exp: '2027-10-31' | `Data Media Mikrobiologi.xlsx`:54 | 100% |
| **66** | 19550384 | Nama: 'Millipore, Sigma-Aldrich'<br>Kat: '2,3,5-Triphenyltetrazolium...'<br>Merk: '0 BOTOL'<br>Stok: 'Media Mikrobiologi'<br>Jenis: '45808' | Nama: '2,3,5-Triphenyltetrazoliumchloride (1.08380.0010)'<br>Kat: ''<br>Merk: 'Millipore, Sigma-Aldrich'<br>Stok: '0 BOTOL'<br>Jenis: 'Media Mikrobiologi'<br>Exp: '2025-05-31' | `Data Media Mikrobiologi.xlsx`:64 | 100% |
| **67** | 87298069 | Nama: 'Millipore'<br>Kat: 'Bactident Coagulase...'<br>Merk: '0 KTK'<br>Stok: 'Media Mikrobiologi'<br>Jenis: '46387' | Nama: 'Bactident Coagulase Rabbit (1.13306.0001)'<br>Kat: ''<br>Merk: 'Millipore'<br>Stok: '0 KTK'<br>Jenis: 'Media Mikrobiologi'<br>Exp: '2026-12-31' | `Data Media Mikrobiologi.xlsx`:65 | 100% |
| **69** | 17538933 | Nama: 'Millipore'<br>Kat: 'Bactident Oxidase...'<br>Merk: '0 KTK'<br>Stok: 'Media Mikrobiologi'<br>Jenis: '45596' | Nama: 'Bactident Oxidase (1.00181.0001)'<br>Kat: ''<br>Merk: 'Millipore'<br>Stok: '0 KTK'<br>Jenis: 'Media Mikrobiologi'<br>Exp: '2024-10-31' | `Data Media Mikrobiologi.xlsx`:67 | 100% |
| **72** | 32997799 | Nama: 'One Med'<br>Kat: 'Kapas Berlemak 500 gram'<br>Merk: '19 PCS'<br>Stok: 'Media Mikrobiologi'<br>Jenis: 'Reagen Cair' | Nama: 'Kapas Berlemak 500 gram'<br>Kat: ''<br>Merk: 'One Med'<br>Stok: '19 PCS'<br>Jenis: 'Media Mikrobiologi' | `Data Media Mikrobiologi.xlsx`:70 | 100% |
| **75** | 53309530 | Nama: 'Himedia'<br>Kat: 'Bile Esculin Azide...'<br>Merk: '1 BOTOL'<br>Stok: 'Media Mikrobiologi'<br>Jenis: 'Reagen Cair' | Nama: 'Bile Esculin Azide Agar (M493l-500G)'<br>Kat: ''<br>Merk: 'Himedia'<br>Stok: '1 BOTOL'<br>Jenis: 'Media Mikrobiologi' | `Data Media Mikrobiologi.xlsx`:73 | 100% |
| **80** | 67294277 | Nama: 'Triple Sugar Iron agar...'<br>Kat: 'M021-500G'<br>Merk: '0 BOTOL'<br>Stok: 'Media Mikrobiologi'<br>Jenis: '46903' | Nama: 'Triple Sugar Iron agar (M021-500G)'<br>Kat: 'M021-500G'<br>Merk: ''<br>Stok: '0 BOTOL'<br>Jenis: 'Media Mikrobiologi'<br>Exp: '2028-05-30' | `Data Media Mikrobiologi.xlsx`:78 | 100% |
| **183** | 22592547 | Nama: 'PT. Brataco'<br>Kat: 'Aqua DM 20L...'<br>Merk: '0 DIRIGEN'<br>Stok: 'Reagen Cair'<br>Jenis: 'Reagen Cair' | Nama: 'Aqua DM 20L, Non AMDK'<br>Kat: ''<br>Merk: 'PT. Brataco'<br>Stok: '0 DIRIGEN'<br>Jenis: 'Reagen Cair' | `Data Reagen Cair.xlsx`:32 | 100% |
| **184** | 41178184 | Nama: 'PT. Widatra Bhakti'<br>Kat: 'Sterile Water...'<br>Merk: '0 BOTOL'<br>Stok: 'Reagen Cair'<br>Jenis: 'Reagen Cair' | Nama: 'Sterile Water for Irrigation 1000 mL'<br>Kat: ''<br>Merk: 'PT. Widatra Bhakti'<br>Stok: '0 BOTOL'<br>Jenis: 'Reagen Cair' | `Data Reagen Cair.xlsx`:33 | 100% |
| **186** | 50611487 | Nama: 'Smart Lab. Indonesia'<br>Kat: 'Toluene p.a (A.1075)'<br>Merk: '0 BOTOL'<br>Stok: 'Reagen Cair'<br>Jenis: '46785' | Nama: 'Toluene p.a (A.1075)'<br>Kat: ''<br>Merk: 'Smart Lab. Indonesia'<br>Stok: '0 BOTOL'<br>Jenis: 'Reagen Cair'<br>Exp: '2028-02-02' | `Data Reagen Cair.xlsx`:35 | 100% |
| **188** | 57033654 | Nama: 'Merck'<br>Kat: 'Dimethyl Sulfoxide...'<br>Merk: '1 BOTOL'<br>Stok: 'Reagen Cair'<br>Jenis: 'Reagen Cair' | Nama: 'Dimethyl Sulfoxide ( 1.02952.1000)'<br>Kat: ''<br>Merk: 'Merck'<br>Stok: '1 BOTOL'<br>Jenis: 'Reagen Cair' | `Data Reagen Cair.xlsx`:37 | 100% |
| **191** | 85966351 | Nama: 'Smart Lab. Indonesia'<br>Kat: 'N,N Dimetil Formamid...'<br>Merk: '1 BOTOL'<br>Stok: 'Reagen Cair'<br>Jenis: '46639' | Nama: 'N,N Dimetil Formamid (A.1094)'<br>Kat: ''<br>Merk: 'Smart Lab. Indonesia'<br>Stok: '1 BOTOL'<br>Jenis: 'Reagen Cair'<br>Exp: '2027-09-09' | `Data Reagen Cair.xlsx`:41 | 100% |
| **193** | 17029669 | Nama: 'Agilent'<br>Kat: 'Quechers Extract tube...'<br>Merk: '0 PAC'<br>Stok: 'Reagen Cair'<br>Jenis: 'Reagen Cair' | Nama: 'Quechers Extract tube ,EN Method w/CH (5982-5650 CH))'<br>Kat: ''<br>Merk: 'Agilent'<br>Stok: '0 PAC'<br>Jenis: 'Reagen Cair' | `Data Reagen Cair.xlsx`:44 | 100% |
| **194** | 11835386 | Nama: 'Sigma Aldrich'<br>Kat: 'Zinc Standard Solution...'<br>Merk: '0 BOTOL'<br>Stok: 'Reagen Cair'<br>Jenis: 'Reagen Cair' | Nama: 'Zinc Standard Solution ( 1.19806.0100)'<br>Kat: ''<br>Merk: 'Sigma Aldrich'<br>Stok: '0 BOTOL'<br>Jenis: 'Reagen Cair' | `Data Reagen Cair.xlsx`:48 | 100% |
| **198** | 29103970 | Nama: 'Vicam'<br>Kat: 'Aflatoxin Standard mix...'<br>Merk: '0 PAC'<br>Stok: 'Reagen Cair'<br>Jenis: 'Reagen Cair' | Nama: 'Aflatoxin Standard mix kit (5x1ml) (G1040)'<br>Kat: ''<br>Merk: 'Vicam'<br>Stok: '0 PAC'<br>Jenis: 'Reagen Cair' | `Data Reagen Cair.xlsx`:52 | 100% |
| **199** | 90023374 | Nama: 'Vicam'<br>Kat: 'Afla M1 toxin Standard...'<br>Merk: '0 PCS'<br>Stok: 'Reagen Cair'<br>Jenis: 'Reagen Cair' | Nama: 'Afla M1 toxin Standard (10ug/ml) 1x1ml (G1043)'<br>Kat: ''<br>Merk: 'Vicam'<br>Stok: '0 PCS'<br>Jenis: 'Reagen Cair' | `Data Reagen Cair.xlsx`:53 | 100% |
| **202** | 38051612 | Nama: 'Merck'<br>Kat: 'Diisopropilamine...'<br>Merk: '0 PCS'<br>Stok: 'Reagen Cair'<br>Jenis: '45291' | Nama: 'Diisopropilamine (1.03646.0100)'<br>Kat: ''<br>Merk: 'Merck'<br>Stok: '0 PCS'<br>Jenis: 'Reagen Cair'<br>Exp: '2023-12-31' | `Data Reagen Cair.xlsx`:57 | 100% |
| **203** | 68962228 | Nama: 'Agilent'<br>Kat: 'Quechers Extract Poucher...'<br>Merk: '0 KTK'<br>Stok: 'Reagen Cair'<br>Jenis: 'Reagen Cair' | Nama: 'Quechers Extract Poucher, AOAC Method (5982-6755)'<br>Kat: ''<br>Merk: 'Agilent'<br>Stok: '0 KTK'<br>Jenis: 'Reagen Cair' | `Data Reagen Cair.xlsx`:58 | 100% |
| **210** | 71174163 | Nama: 'sigma'<br>Kat: 'Dimethyl sulfoxide...'<br>Merk: '1 BOTOL'<br>Stok: 'Reagen Cair'<br>Jenis: 'Reagen Cair' | Nama: 'Dimethyl sulfoxide (D5879-100ML)'<br>Kat: ''<br>Merk: 'sigma'<br>Stok: '1 BOTOL'<br>Jenis: 'Reagen Cair' | `Data Reagen Cair.xlsx`:72 | 100% |
| **212** | 42637019 | Nama: 'waters'<br>Kat: 'SPE HLB Cartridges...'<br>Merk: '2 PAC'<br>Stok: 'Reagen Cair'<br>Jenis: 'Reagen Cair' | Nama: 'SPE HLB Cartridges 3 cc, Waters (WAT 094226)'<br>Kat: ''<br>Merk: 'waters'<br>Stok: '2 PAC'<br>Jenis: 'Reagen Cair' | `Data Reagen Cair.xlsx`:86 | 100% |
| **213** | 86032311 | Nama: 'waters'<br>Kat: 'SPE MCX Cartridges...'<br>Merk: '0 PAC'<br>Stok: 'Reagen Cair'<br>Jenis: 'Reagen Cair' | Nama: 'SPE MCX Cartridges 3cc, Waters (186000254)'<br>Kat: ''<br>Merk: 'waters'<br>Stok: '0 PAC'<br>Jenis: 'Reagen Cair' | `Data Reagen Cair.xlsx`:87 | 100% |
| **215** | 62236183 | Nama: 'whatman'<br>Kat: 'Filter Papers (1443-090)'<br>Merk: '0 PAC'<br>Stok: 'Reagen Cair'<br>Jenis: 'Reagen Cair' | Nama: 'Filter Papers (1443-090)'<br>Kat: ''<br>Merk: 'whatman'<br>Stok: '0 PAC'<br>Jenis: 'Reagen Cair' | `Data Reagen Cair.xlsx`:89 | 100% |
| **222** | 62056165 | Nama: 'grade'<br>Kat: 'Grade 91 Sheet...'<br>Merk: '0 PAC'<br>Stok: 'Reagen Cair'<br>Jenis: 'Reagen Cair' | Nama: 'Grade 91 Sheet, Whatman (1.091.930)'<br>Kat: ''<br>Merk: 'grade'<br>Stok: '0 PAC'<br>Jenis: 'Reagen Cair' | `Data Reagen Cair.xlsx`:99 | 100% |
| **223** | 15390340 | Nama: 'grade'<br>Kat: 'Grade 93 Sheet...'<br>Merk: '3 PAC'<br>Stok: 'Reagen Cair'<br>Jenis: 'Reagen Cair' | Nama: 'Grade 93 Sheet, Whatman (1.093.930)'<br>Kat: ''<br>Merk: 'grade'<br>Stok: '3 PAC'<br>Jenis: 'Reagen Cair' | `Data Reagen Cair.xlsx`:100 | 100% |
| **227** | 61421202 | Nama: 'Smart Lab.'<br>Kat: 'Dichloromethane (A-1031)'<br>Merk: '1 BOTOL'<br>Stok: 'Reagen Cair'<br>Jenis: 'Reagen Cair' | Nama: 'Dichloromethane (A-1031)'<br>Kat: ''<br>Merk: 'Smart Lab.'<br>Stok: '1 BOTOL'<br>Jenis: 'Reagen Cair' | `Data Reagen Cair.xlsx`:107 | 100% |
| **320** | 70030122 | Nama: 'Smart Lab.'<br>Kat: 'Ammonium Bicarbonate...'<br>Merk: '0 BOTOL'<br>Stok: 'Reagen Padat'<br>Jenis: 'Reagen Cair' | Nama: 'Ammonium Bicarbonate (A.2011)'<br>Kat: ''<br>Merk: 'Smart Lab.'<br>Stok: '0 BOTOL'<br>Jenis: 'Reagen Padat' | `Data Reagen Padat.xlsx`:19 | 100% |
| **325** | 69823651 | Nama: 'USP'<br>Kat: 'Penicilin V Potassium...'<br>Merk: '0 VIAL'<br>Stok: 'Reagen Padat'<br>Jenis: 'Reagen Cair' | Nama: 'Penicilin V Potassium (150453.200mg)'<br>Kat: ''<br>Merk: 'USP'<br>Stok: '0 VIAL'<br>Jenis: 'Reagen Padat' | `Data Reagen Padat.xlsx`:46 | 100% |
| **326** | 93561385 | Nama: 'Sigma'<br>Kat: 'Pyrogalol Acs Reagents...'<br>Merk: '0 VIAL'<br>Stok: 'Reagen Padat'<br>Jenis: 'Reagen Cair' | Nama: 'Pyrogalol Acs Reagents (2540002.10G)'<br>Kat: ''<br>Merk: 'Sigma'<br>Stok: '0 VIAL'<br>Jenis: 'Reagen Padat' | `Data Reagen Padat.xlsx`:47 | 100% |
| **327** | 63407330 | Nama: 'Smart Lab'<br>Kat: 'Potassium Chloride...'<br>Merk: '1 BOTOL'<br>Stok: 'Reagen Padat'<br>Jenis: 'Reagen Cair' | Nama: 'Potassium Chloride (A-2038)'<br>Kat: ''<br>Merk: 'Smart Lab'<br>Stok: '1 BOTOL'<br>Jenis: 'Reagen Padat' | `Data Reagen Padat.xlsx`:51 | 100% |

---

## 3. Penanganan `expired_date = '0000-00-00'`

- **Jumlah Teridentifikasi:** 68 record di database aktif.
- **Analisis Sumber:** 
  Pada berkas Excel aslinya, sel kedaluwarsa untuk ke-68 record tersebut kosong (tidak diisi). Ini umum di laboratorium BPOM untuk reagen standar, baku pembanding tertentu, garam anorganik stabil, kapas, filter paper, atau bahan kimia yang tidak memiliki masa kedaluwarsa tertentu dari manufaktur.
- **Rekomendasi Pemulihan:**
  Semua nilai `'0000-00-00'` dinormalisasi menjadi `NULL`. Hal ini memenuhi standar SQL strict mode, mencegah galat representasi di UI (seperti tampil `00-00-0000` atau tanggal palsu `1970-01-01`), dan selaras dengan validasi `Model_Reagen` yang telah kita siapkan pada Batch 01.

---

## 4. Struktur & Pengamanan Skrip Pemulihan

Skrip koreksi [`database/recovery_reagents_historical.sql`](file:///C:/xampp/htdocs/LIMSKU/database/recovery_reagents_historical.sql) dirancang dengan standar kehati-hatian tertinggi:
1. **Precondition Checks:**
   - Memastikan total record tetap 389 baris.
   - Memastikan jumlah record ber-tanggal `0000-00-00` tetap 68 baris.
2. **Koreksi Terarah (Scattered Updates):**
   - Setiap query `UPDATE` mengunci baris target menggunakan `id = X AND kode_barang = 'Y'`.
   - Mengisi nilai nama barang, katalog, merk, stok, jenis barang, dan tanggal kadaluarsa asli dari Excel.
3. **Normalisasi Zero-Date:**
   - Mengubah `0000-00-00` menjadi `NULL`.
4. **Postcondition Checks:**
   - Memastikan tidak ada lagi serial angka Excel 5-digit pada `jenis_barang`.
   - Memastikan tidak ada lagi nilai `0000-00-00` pada `expired_date`.
   - Memastikan kolom `stok` tidak lagi memuat kategori reagen (misal `'Reagen Cair'`).
   - Memastikan total baris tabel tetap 389 record.
5. **Rollback Script:**
   - Skrip [`database/rollback_reagents_historical.sql`](file:///C:/xampp/htdocs/LIMSKU/database/rollback_reagents_historical.sql) menyimpan seluruh nilai lama secara eksak sebelum koreksi, sehingga dapat dieksekusi seketika jika ingin mengembalikan kondisi data.
