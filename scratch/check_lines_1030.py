with open('application/views/pengujian/proses_sesi.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

for i in range(1030, 1130):
    if i < len(lines):
        print(f"{i+1}: {lines[i]}", end='')

