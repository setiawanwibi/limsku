with open('application/controllers/Pengujian.php', 'r', encoding='utf-8') as f:
    for i, line in enumerate(f):
        if 'cetak_lcp' in line:
            print(f"Line {i+1}: {line.strip()}")

