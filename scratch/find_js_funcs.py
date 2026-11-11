with open('application/views/pengujian/proses_sesi.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

for i, l in enumerate(lines):
    if 'function hitungHPLCPane' in l or 'function hitungSpektrofotometriPane' in l or 'function updateMetodeKadarUI' in l:
        print(f"Line {i+1}: {l.strip()}")

