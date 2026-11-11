with open('application/views/pengujian/proses_sesi.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

for i, l in enumerate(lines[:1200]):
    if '<table' in l:
        print(f"Table at line {i+1}: {l.strip()[:100]}")
    if 'data-field-wrapper' in l:
        print(f"Field wrapper at {i+1}: {l.strip()[:100]}")
