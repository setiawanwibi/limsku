with open('application/controllers/Pengujian.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

for i, l in enumerate(lines):
    if 'function proses_sesi' in l or 'ambil_hasil_by_session' in l:
        print(f"Line {i+1}: {l.strip()[:100]}")

