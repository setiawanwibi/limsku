with open('application/views/pengujian/cetak_lcp_kckt.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

for i, line in enumerate(lines):
    if any(k in line for k in ['tabel_hplc', 'faktor_konversi_bm', 'rumus-box', 'bobot_rata', 'kadar_rata', 'Perhitungan Kadar', 'Kesesuaian Sistem']):
        print(f"Line {i+1}: {line.strip()[:100]}")

