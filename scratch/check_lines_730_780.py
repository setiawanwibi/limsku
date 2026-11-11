with open('application/views/pengujian/proses_sesi.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

for i in range(730, 780):
    if i < len(lines):
        print(f"{i+1}: {lines[i]}", end='')

