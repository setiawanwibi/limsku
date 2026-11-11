with open('application/views/pengujian/proses_sesi.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

for i, line in enumerate(lines):
    if 'addEventListener' in line:
        print(f"Line {i+1}: {line.strip()[:100]}")

