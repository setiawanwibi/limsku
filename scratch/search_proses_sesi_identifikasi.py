with open('application/views/pengujian/proses_sesi.php', 'r', encoding='utf-8') as f:
    for i, line in enumerate(f):
        if 'identifikasi' in line.lower():
            print(f"Line {i+1}: {line.strip()[:140]}")

