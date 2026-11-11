with open('application/views/pengujian/proses_sesi.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

for i, l in enumerate(lines):
    if 'f_param_name' in l or 'is_identifikasi' in l:
        print(f"Line {i+1}: {l.strip()[:100]}")

