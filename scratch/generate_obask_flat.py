import pandas as pd

excel_src = 'scratch/temp_obask_audit.xlsx'
out_excel = r'C:\Users\heris\OneDrive\Documents\MagangHub BBPOM\LIMSKU\SK Pedoman Sampling\Master_Metode_OBASK_Siap_Import.xlsx'

df_bko = pd.read_excel(excel_src, sheet_name='Identifikasi BKO (Herbal)')
df_oba = pd.read_excel(excel_src, sheet_name='Mutu Obat Bahan Alam')
df_kuasi = pd.read_excel(excel_src, sheet_name='Mutu Obat Kuasi')
df_suplemen = pd.read_excel(excel_src, sheet_name='Mutu Suplemen Kesehatan')
df_fito = pd.read_excel(excel_src, sheet_name='Produk Fitofarmaka')

rows_herbal = []
rows_suplemen = []

# ==========================================
# 1. HERBAL: BKO (Identifikasi Bahan Kimia Obat)
# ==========================================
for idx, r in df_bko.iterrows():
    klaim = str(r['Klaim / Indikasi Khasiat']).strip()
    sediaan = str(r['Bentuk Sediaan']).strip()
    bko_param = str(r['Parameter BKO (Bahan Kimia Obat) yang Diuji']).strip()
    
    nama_met = f"Skrining BKO ({bko_param[:50]}...) Jamu {klaim}" if len(bko_param) > 50 else f"Skrining BKO ({bko_param}) Jamu {klaim}"
    
    rows_herbal.append({
        'Komoditi': 'herbal',
        'Kategori / Sediaan': f"[BKO] Jamu {klaim}",
        'Bentuk Sediaan': sediaan,
        'Parameter Uji': f"Identifikasi BKO Jamu {klaim}",
        'Nama Senyawa Target': bko_param,
        'Bidang': 'Kimia',
        'Metode Analisa': 'Kromatografi Lapis Tipis (KLT) / KCKT / LC-MS/MS',
        'Persyaratan': 'Negatif / Tidak Boleh Ada BKO',
        'Pustaka Acuan': 'PerBPOM No. 131 Tahun 2026 / MA PPOMN'
    })

# ==========================================
# 2. HERBAL: Mutu Obat Bahan Alam (Lampiran 42)
# ==========================================
param_mutu_oba = [
    ('Organoleptik', 'Uji Fisik Organoleptik (Bentuk, Bau, Rasa, Warna)', 'Organoleptik', 'Kimia', 'Visual & Sensori', 'Khas sediaan, tidak tengik / berjamur', 'FHI / SNI'),
    ('Aflatoksin', 'Cemaran Aflatoksin Total (B1, B2, G1, G2)', 'Aflatoksin Total', 'Kimia', 'KCKT Fluoresensi / KLT Densitometri', 'Maks. 20 mcg/kg (Aflatoksin B1 maks. 5 mcg/kg)', 'PerBPOM No. 32 Tahun 2019'),
    ('Pengawet', 'Penetapan Kadar Pengawet (Benzoat / Sorbat)', 'Natrium Benzoat & Asam Sorbat', 'Kimia', 'KCKT / HPLC', 'Sesuai batas PerBPOM BTP (Maks. 2000 mg/kg)', 'PerBPOM No. 11 Tahun 2019'),
    ('Waktu Hancur', 'Uji Waktu Hancur Sediaan Padat Herbal', 'Waktu Hancur', 'Kimia', 'Disintegrator Basket-Rack', 'Pil < 60 mnt, Kapsul & Tablet < 30 mnt', 'FHI / Farmakope Indonesia'),
    ('Pemanis', 'Penetapan Kadar Pemanis Sintetis (Sakarin & Siklamat)', 'Sakarin & Siklamat', 'Kimia', 'KCKT / HPLC UV', 'Sesuai batas PerBPOM BTP', 'PerBPOM No. 11 Tahun 2019'),
    ('Logam Berat', 'Cemaran Logam Berat (Pb, Cd, As, Hg)', 'Logam Timbal (Pb), Kadmium (Cd), Arsen (As), Merkuri (Hg)', 'Kimia', 'ICP-MS / GFA-AAS / HVG-AAS', 'Pb <= 10 mg/kg, Cd <= 0.3 mg/kg, As <= 5 mg/kg, Hg <= 0.5 mg/kg', 'PerBPOM No. 32 Tahun 2019'),
    ('Kadar Etanol/Metanol', 'Kadar Etanol & Metanol Sediaan Cair Herbal', 'Etanol & Metanol', 'Kimia', 'Kromatografi Gas (GC-FID)', 'Etanol <= 1.0% v/v, Metanol <= 0.1% v/v', 'PerBPOM No. 32 Tahun 2019'),
    ('Cemaran EG-DEG', 'Cemaran Etilen Glikol (EG) & Dietilen Glikol (DEG)', 'Etilen Glikol & Dietilen Glikol', 'Kimia', 'Kromatografi Gas (GC-FID / GC-MS)', 'Maks. 0.1% (1000 mg/kg)', 'Farmakope Indonesia'),
    ('ALT/AKK', 'Uji Angka Lempeng Total (ALT) & Angka Kapang Khamir (AKK)', 'ALT & AKK', 'Mikrobiologi', 'Hitungan Cawan Tuang (PCA / PDA)', 'ALT <= 10^4 koloni/g, AKK <= 10^3 koloni/g', 'PerBPOM No. 32 Tahun 2019'),
    ('Mikroba Patogen', 'Uji Cemaran Mikroba Patogen (E. coli, Salmonella, S. aureus, P. aeruginosa)', 'Bakteri Patogen', 'Mikrobiologi', 'Pengayaan & Media Selektif', 'Negatif / Tidak Boleh Ada per 1 g atau 1 mL', 'PerBPOM No. 32 Tahun 2019')
]

for idx, r in df_oba.iterrows():
    sediaan = str(r['Bentuk Sediaan']).strip()
    for col_key, param_nama, senyawa, bidang, metode, syarat, ref in param_mutu_oba:
        val = str(r[col_key]).strip()
        if 'V' in val:
            rows_herbal.append({
                'Komoditi': 'herbal',
                'Kategori / Sediaan': f"[MUTU-HERBAL] {sediaan}",
                'Bentuk Sediaan': sediaan,
                'Parameter Uji': param_nama,
                'Nama Senyawa Target': senyawa,
                'Bidang': bidang,
                'Metode Analisa': metode,
                'Persyaratan': syarat,
                'Pustaka Acuan': ref
            })

# ==========================================
# 3. HERBAL: Obat Kuasi (Lampiran 43)
# ==========================================
param_mutu_kuasi = [
    ('Organoleptik', 'Uji Organoleptik Obat Kuasi', 'Organoleptik', 'Kimia', 'Visual & Sensori', 'Khas sediaan, konsistensi seragam', 'Spesifikasi Industri / FHI'),
    ('Logam Berat', 'Cemaran Logam Berat (Pb, Cd, As)', 'Pb, Cd, As', 'Kimia', 'AAS / ICP-MS', 'Pb <= 10 mg/kg, Cd <= 0.3 mg/kg, As <= 5 mg/kg', 'PerBPOM Standar Keamanan'),
    ('Pengawet', 'Penetapan Kadar Pengawet Obat Kuasi', 'Bahan Pengawet', 'Kimia', 'KCKT / HPLC', 'Sesuai formula izin edar', 'PerBPOM BTP'),
    ('Pemanis', 'Penetapan Kadar Pemanis Tablet Hisap Kuasi', 'Pemanis Sintetis', 'Kimia', 'KCKT / HPLC', 'Sesuai formula izin edar', 'PerBPOM BTP'),
    ('Senyawa Aktif', 'Identifikasi / Penetapan Kadar Senyawa Aktif Kuasi (e.g. Mentol, Kamfer, Metil Salisilat)', 'Minyak Atsiri / Senyawa Aktif', 'Kimia', 'GC-FID / KCKT / Titrimetri', '90.0% - 110.0% dari klaim etiket', 'Farmakope Indonesia / Spesifikasi'),
    ('Kadar Etanol/Metanol', 'Penetapan Kadar Etanol & Metanol Obat Kuasi', 'Etanol & Metanol', 'Kimia', 'GC-FID', 'Sesuai batas izin edar', 'Farmakope Indonesia'),
    ('ALT/AKK', 'Uji Angka Lempeng Total & Kapang Khamir Obat Kuasi', 'ALT & AKK', 'Mikrobiologi', 'Hitungan Cawan Tuang', 'ALT <= 10^3 CFU/g, AKK <= 10^2 CFU/g', 'PerBPOM Mikrobiologi'),
    ('Mikroba Patogen', 'Uji Patogen Spesifik Obat Kuasi (S. aureus, P. aeruginosa)', 'Bakteri Patogen', 'Mikrobiologi', 'Media Selektif', 'Negatif / gram atau mL', 'PerBPOM Mikrobiologi')
]

for idx, r in df_kuasi.iterrows():
    sediaan = str(r['Bentuk Sediaan']).strip()
    for col_key, param_nama, senyawa, bidang, metode, syarat, ref in param_mutu_kuasi:
        if col_key in r:
            val = str(r[col_key]).strip()
            if 'V' in val:
                rows_herbal.append({
                    'Komoditi': 'herbal',
                    'Kategori / Sediaan': f"[KUASI] {sediaan}",
                    'Bentuk Sediaan': sediaan,
                    'Parameter Uji': param_nama,
                    'Nama Senyawa Target': senyawa,
                    'Bidang': bidang,
                    'Metode Analisa': metode,
                    'Persyaratan': syarat,
                    'Pustaka Acuan': ref
                })

# ==========================================
# 4. HERBAL: Fitofarmaka (Lampiran 46)
# ==========================================
for idx, r in df_fito.iterrows():
    kat = str(r['Kategori']).strip()
    komposisi = str(r['Komposisi']).strip()
    param = str(r['Parameter Uji']).strip()
    syarat = str(r['Persyaratan']).strip()
    rows_herbal.append({
        'Komoditi': 'herbal',
        'Kategori / Sediaan': f"[FITOFARMAKA] {kat}",
        'Bentuk Sediaan': komposisi,
        'Parameter Uji': f"{param} ({kat})",
        'Nama Senyawa Target': komposisi,
        'Bidang': 'Kimia',
        'Metode Analisa': 'Spektrofotometri UV-Vis / KCKT',
        'Persyaratan': syarat,
        'Pustaka Acuan': 'PerBPOM No. 131/2026 Lampiran 46 / FHI'
    })

# ==========================================
# 5. SUPLEMEN KESEHATAN (Lampiran 44)
# ==========================================
param_mutu_sup = [
    ('Organoleptik', 'Uji Fisik Organoleptik Suplemen', 'Organoleptik', 'Kimia', 'Visual & Sensori', 'Sesuai spesifikasi produk', 'Spesifikasi Industri'),
    ('Pemanis', 'Penetapan Kadar Pemanis Buatan Suplemen', 'Pemanis Sintetis', 'Kimia', 'KCKT / HPLC UV', 'Sesuai batas PerBPOM Suplemen', 'PerBPOM BTP'),
    ('Logam Berat', 'Cemaran Logam Berat Suplemen (Pb, Cd, As, Hg)', 'Pb, Cd, As, Hg', 'Kimia', 'ICP-MS / AAS', 'Pb <= 0.5 mg/kg, Cd <= 0.2 mg/kg, As <= 0.5 mg/kg, Hg <= 0.05 mg/kg', 'PerBPOM Standar Mutu Suplemen'),
    ('Kadar Vitamin / Zat Aktif', 'Penetapan Kadar Vitamin / Mineral / Zat Aktif Suplemen', 'Vitamin (A, B-Kompleks, C, D3, E) / Mineral', 'Kimia', 'KCKT / HPLC / Spektrofotometri UV-Vis / AAS', '90.0% - 125.0% dari jumlah yang tertera pada etiket', 'Farmakope Indonesia / USP / FHI'),
    ('Kadar Etanol/Metanol', 'Penetapan Kadar Etanol & Metanol Suplemen Cair', 'Etanol & Metanol', 'Kimia', 'GC-FID', 'Etanol <= 1.0% v/v, Metanol <= 0.1% v/v', 'PerBPOM Suplemen Kesehatan'),
    ('Cemaran EG-DEG', 'Cemaran EG & DEG Suplemen Cair (Sirup/Drops)', 'Etilen Glikol & Dietilen Glikol', 'Kimia', 'GC-FID / GC-MS', 'Maks. 0.1% (1000 mg/kg)', 'Farmakope Indonesia Suplemen'),
    ('ALT/AKK', 'Uji Angka Lempeng Total & Angka Kapang Khamir Suplemen', 'ALT & AKK', 'Mikrobiologi', 'Hitungan Cawan Tuang (PCA / PDA)', 'ALT <= 5 x 10^3 CFU/g (padat) / 5 x 10^2 CFU/mL (cair), AKK <= 5 x 10^2 CFU/g', 'PerBPOM Standar Mutu Suplemen'),
    ('Mikroba Patogen', 'Uji Cemaran Mikroba Patogen Suplemen (E. coli & Salmonella)', 'Bakteri Patogen', 'Mikrobiologi', 'Media Selektif', 'Negatif / 10 g atau 10 mL', 'PerBPOM Standar Mutu Suplemen')
]

for idx, r in df_suplemen.iterrows():
    sediaan = str(r['Bentuk Sediaan']).strip()
    for col_key, param_nama, senyawa, bidang, metode, syarat, ref in param_mutu_sup:
        val = str(r[col_key]).strip()
        if 'V' in val:
            rows_suplemen.append({
                'Komoditi': 'suplemen',
                'Kategori / Sediaan': f"[SUPLEMEN] {sediaan}",
                'Bentuk Sediaan': sediaan,
                'Parameter Uji': param_nama,
                'Nama Senyawa Target': senyawa,
                'Bidang': bidang,
                'Metode Analisa': metode,
                'Persyaratan': syarat,
                'Pustaka Acuan': ref
            })

df_flat_herbal = pd.DataFrame(rows_herbal)
df_flat_suplemen = pd.DataFrame(rows_suplemen)

print(f"Total Baris Siap Import - HERBAL: {len(df_flat_herbal)} baris")
print(f"Total Baris Siap Import - SUPLEMEN: {len(df_flat_suplemen)} baris")

with pd.ExcelWriter(out_excel, engine='openpyxl') as writer:
    df_flat_herbal.to_excel(writer, sheet_name='Master Metode Herbal (OBASK)', index=False)
    df_flat_suplemen.to_excel(writer, sheet_name='Master Metode Suplemen', index=False)

print("File Excel Siap Import berhasil disimpan di:", out_excel)
