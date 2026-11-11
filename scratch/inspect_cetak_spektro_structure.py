with open('application/views/pengujian/cetak_lcp_spektrofotometri.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

for i, l in enumerate(lines):
    if any(k in l for k in ['tabel_preparasi', 'Kadar Rata-rata', 'Bobot Rata-rata', 'Klaim Etiket', 'Faktor Koreksi BM', 'Hasil Uji', 'Pustaka']):
        print(f"Line {i+1}: {l.strip()[:100]}")

