with open('application/views/pengujian/proses_sesi.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

for i in range(500, 990):
    if 'is_identifikasi' in lines[i]:
        print(f"Line {i+1}: {lines[i].strip()}")

