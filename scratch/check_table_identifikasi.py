with open('application/views/pengujian/proses_sesi.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

for i, l in enumerate(lines):
    if '$is_identifikasi && in_array($fld_id' in l:
        for j in range(max(0, i-5), min(len(lines), i+30)):
            print(f"{j+1}: {lines[j]}", end='')
        break

