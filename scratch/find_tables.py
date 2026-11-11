with open('application/views/pengujian/proses_sesi.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

for i, line in enumerate(lines):
    if 'tabel_hplc' in line or 'tabel_preparasi' in line or 'perhitungan_kadar' in line:
        if any(kw in line for kw in ['<div', '<table', '<section', 'class="col', 'parameter-section']):
            print(f"Line {i+1}: {line.strip()[:100]}")

