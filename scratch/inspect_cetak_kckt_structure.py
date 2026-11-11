with open('application/views/pengujian/cetak_lcp_kckt.php', 'r', encoding='utf-8') as f:
    c = f.read()

for marker in ['Bobot Rata-rata', 'Klaim Etiket', 'Faktor Koreksi BM', 'tabel_hplc', 'Kadar Rata-rata', 'Hasil Uji', 'Pustaka']:
    pos = c.find(marker)
    print(f'Marker "{marker}": pos {pos}')

