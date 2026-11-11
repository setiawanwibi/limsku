with open('application/views/pengujian/proses_sesi.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

for i, l in enumerate(lines):
    if 'foreach ($forms as' in l:
        print(f"Line {i+1}: {l.strip()}")
        for j in range(i+1, min(len(lines), i+40)):
            print(f"  {j+1}: {lines[j]}", end='')
        break

