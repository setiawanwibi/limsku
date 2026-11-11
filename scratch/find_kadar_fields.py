with open('application/views/pengujian/proses_sesi.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

for i, line in enumerate(lines):
    if any(k in line for k in ['metode_perhitungan_kadar', 'bobot_rata_rata_tablet', 'faktor_konversi_bm', 'tabel_preparasi', 'perhitungan_kadar_hplc']):
        print(f"Line {i+1}: {line.strip()[:100]}")

