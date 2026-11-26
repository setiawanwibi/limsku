import pymysql

conn = pymysql.connect(host='localhost', user='root', password='', database='limsku')
cur = conn.cursor(pymysql.cursors.DictCursor)

cur.execute("""
    SELECT kode_metode, nama_metode, nama_zat_aktif, jenis_sediaan, bentuk_sediaan, 
           pustaka_acuan, parameter_uji, metode_analisa, persyaratan, bidang_pengujian, 
           template_id, komoditi, composite_hash, kategori, status 
    FROM methods 
    WHERE komoditi = 'pangan' 
    ORDER BY id ASC
""")
rows = cur.fetchall()

def esc(val):
    if val is None:
        return 'NULL'
    clean = str(val).replace('\\', '\\\\').replace("'", "\\'")
    return f"'{clean}'"

out_path = 'database/update_master_metode_pangan.sql'
with open(out_path, 'w', encoding='utf-8') as f:
    f.write("-- LIMSKU MASTER METODE PANGAN (PANGAN OLAHAN DAN PIRT)\n")
    f.write("-- Aman dijalankan di phpMyAdmin (menggunakan INSERT IGNORE agar tidak error ID)\n\n")
    
    batch_size = 50
    for i in range(0, len(rows), batch_size):
        chunk = rows[i:i+batch_size]
        f.write("INSERT IGNORE INTO `methods` (`kode_metode`, `nama_metode`, `nama_zat_aktif`, `jenis_sediaan`, `bentuk_sediaan`, `pustaka_acuan`, `parameter_uji`, `metode_analisa`, `persyaratan`, `bidang_pengujian`, `kelas_terapi_id`, `template_id`, `komoditi`, `composite_hash`, `kategori`, `status`) VALUES\n")
        val_lines = []
        for r in chunk:
            tpl = str(r['template_id']) if r['template_id'] else 'NULL'
            line = f"({esc(r['kode_metode'])}, {esc(r['nama_metode'])}, {esc(r['nama_zat_aktif'])}, {esc(r['jenis_sediaan'])}, {esc(r['bentuk_sediaan'])}, {esc(r['pustaka_acuan'])}, {esc(r['parameter_uji'])}, {esc(r['metode_analisa'])}, {esc(r['persyaratan'])}, {esc(r['bidang_pengujian'])}, NULL, {tpl}, 'pangan', {esc(r['composite_hash'])}, {esc(r['kategori'])}, 'Aktif')"
            val_lines.append(line)
        f.write(',\n'.join(val_lines) + ';\n\n')

print(f"Generated clean SQL: {out_path} with {len(rows)} rows.")
