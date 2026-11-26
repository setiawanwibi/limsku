import pandas as pd
import pymysql
import hashlib

df_k = pd.read_excel('scratch/temp_pangan.xlsx', sheet_name='Fisika-Kimia Pangan')
df_m = pd.read_excel('scratch/temp_pangan.xlsx', sheet_name='Mikrobiologi Pangan')

conn = pymysql.connect(host='localhost', user='root', password='', database='limsku')
cur = conn.cursor()

# Get current max MET-PNG number
cur.execute("SELECT MAX(CAST(SUBSTRING(kode_metode, 9) AS UNSIGNED)) FROM methods WHERE kode_metode LIKE 'MET-PNG-%'")
res = cur.fetchone()[0]
cur_num = int(res) if res else 0
print(f"Current max MET-PNG: {cur_num}")

cur.execute("SELECT composite_hash FROM methods WHERE composite_hash IS NOT NULL")
existing_hashes = set(r[0] for r in cur.fetchall())
print(f"Existing hashes in db: {len(existing_hashes)}")

all_rows = []

for idx, r in df_k.iterrows():
    param = str(r['Parameter Uji']).strip() if pd.notna(r['Parameter Uji']) else ''
    sediaan = str(r['Jenis Pangan']).strip() if pd.notna(r['Jenis Pangan']) else ''
    kat = str(r['Kategori Pangan']).strip() if pd.notna(r['Kategori Pangan']) else ''
    indeks = str(r['Indeks Katpang']).strip() if pd.notna(r['Indeks Katpang']) else ''
    syarat = str(r['Syarat / Batas']).strip() if pd.notna(r['Syarat / Batas']) else ''
    ref = str(r['Referensi Regulasi']).strip() if pd.notna(r['Referensi Regulasi']) else ''
    metode = str(r['Metode Analisa']).strip() if pd.notna(r['Metode Analisa']) else ''
    if not param:
        continue
    
    nama_metode = f"{metode} {param} {sediaan}" if metode and metode != '-' else f"{param} {sediaan}"
    nama_metode = ' '.join(nama_metode.split())[:150]
    
    all_rows.append({
        'nama_metode': nama_metode,
        'nama_zat_aktif': param[:150],
        'jenis_sediaan': f"[{indeks}] {kat}"[:100] if indeks else kat[:100],
        'bentuk_sediaan': sediaan[:100],
        'pustaka_acuan': ref[:100],
        'parameter_uji': param[:150],
        'metode_analisa': metode[:150],
        'persyaratan': syarat,
        'bidang_pengujian': 'Kimia',
        'kategori': 'Kimia'
    })

for idx, r in df_m.iterrows():
    param = str(r['Parameter Uji']).strip() if pd.notna(r['Parameter Uji']) else ''
    sediaan = str(r['Jenis Pangan']).strip() if pd.notna(r['Jenis Pangan']) else ''
    kat = str(r['Kategori Pangan']).strip() if pd.notna(r['Kategori Pangan']) else ''
    indeks = str(r['Indeks Katpang']).strip() if pd.notna(r['Indeks Katpang']) else ''
    syarat = str(r['Syarat / Kriteria 3-Kelas']).strip() if pd.notna(r['Syarat / Kriteria 3-Kelas']) else ''
    ref = str(r['Referensi Regulasi']).strip() if pd.notna(r['Referensi Regulasi']) else ''
    metode = str(r['Metode Analisa']).strip() if pd.notna(r['Metode Analisa']) else ''
    cara = str(r['Cara Uji']).strip() if pd.notna(r['Cara Uji']) else ''
    if not param:
        continue
    
    full_metode = f"{cara} {metode}".strip() if cara else metode
    nama_metode = f"{param} {sediaan}"
    nama_metode = ' '.join(nama_metode.split())[:150]
    
    all_rows.append({
        'nama_metode': nama_metode,
        'nama_zat_aktif': param[:150],
        'jenis_sediaan': f"[{indeks}] {kat}"[:100] if indeks else kat[:100],
        'bentuk_sediaan': sediaan[:100],
        'pustaka_acuan': ref[:100],
        'parameter_uji': param[:150],
        'metode_analisa': full_metode[:150],
        'persyaratan': syarat,
        'bidang_pengujian': 'Mikrobiologi',
        'kategori': 'Mikrobiologi'
    })

inserted = 0
skipped = 0

for item in all_rows:
    comp_str = f"{item['nama_zat_aktif'].lower()}_{item['bentuk_sediaan'].lower()}_{item['bidang_pengujian'].lower()}_pangan"
    chash = hashlib.md5(comp_str.encode('utf-8')).hexdigest()
    
    if chash in existing_hashes:
        skipped += 1
        continue
    
    existing_hashes.add(chash)
    cur_num += 1
    kode = f"MET-PNG-{cur_num:03d}"
    
    sql = """
        INSERT INTO methods (
            kode_metode, nama_metode, nama_zat_aktif, jenis_sediaan, bentuk_sediaan,
            pustaka_acuan, parameter_uji, metode_analisa, persyaratan,
            bidang_pengujian, kelas_terapi_id, template_id, komoditi,
            composite_hash, kategori, status
        ) VALUES (
            %s, %s, %s, %s, %s,
            %s, %s, %s, %s,
            %s, NULL, NULL, 'pangan',
            %s, %s, 'Aktif'
        )
    """
    cur.execute(sql, (
        kode, item['nama_metode'], item['nama_zat_aktif'], item['jenis_sediaan'], item['bentuk_sediaan'],
        item['pustaka_acuan'], item['parameter_uji'], item['metode_analisa'], item['persyaratan'],
        item['bidang_pengujian'], chash, item['kategori']
    ))
    inserted += 1

conn.commit()
conn.close()
print(f"Import Selesai! Berhasil Insert: {inserted}, Skipped: {skipped}, Max Kode Terakhir: MET-PNG-{cur_num:03d}")
