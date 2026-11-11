with open('application/controllers/Pengujian.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

for i, l in enumerate(lines):
    if 'public function proses_sesi' in l:
        print(f"proses_sesi starts at line {i+1}")
        for j in range(i, min(i+60, len(lines))):
            print(f"{j+1}: {lines[j].strip()[:100]}")
        break

