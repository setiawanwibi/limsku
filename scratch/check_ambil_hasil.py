with open('application/models/Model_Pengujian.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

for i, l in enumerate(lines):
    if 'function ambil_hasil_by_session' in l:
        for j in range(i, min(len(lines), i+30)):
            print(f"{j+1}: {lines[j]}", end='')
        break

