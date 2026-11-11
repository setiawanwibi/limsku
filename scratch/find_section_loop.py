with open('application/views/pengujian/proses_sesi.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

for i, l in enumerate(lines):
    if 'foreach ($skema[\'sections\'] as' in l or 'foreach ($f[\'skema_form\'][\'sections\']' in l or 'foreach ($sections as' in l:
        print(f"Section loop at line {i+1}: {l.strip()}")
        for j in range(i+1, min(len(lines), i+30)):
            print(f"  {j+1}: {lines[j]}", end='')
        break

