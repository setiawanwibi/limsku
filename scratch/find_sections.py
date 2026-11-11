with open('application/views/pengujian/proses_sesi.php', 'r', encoding='utf-8') as f:
    for i, line in enumerate(f):
        if 'sections' in line:
            print(f"{i+1}: {line.strip()[:100]}")
