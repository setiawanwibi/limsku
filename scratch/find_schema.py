import re

with open('application/views/pengujian/proses_sesi.php', 'r', encoding='utf-8') as f:
    content = f.read()

# find sections
matches = re.findall(r'(section[\'"]?\s*=>\s*[\'"][^\'"]+[\'"]|\$sec_key|\$section_name|\$field_name|metode_perhitungan_kadar|bobot_rata_rata_tablet|faktor_konversi_bm|tabel_hplc|tabel_preparasi)', content)
print("Unique matches:")
print(set(matches[:30]))

# find where fields loop
for m in re.finditer(r'foreach\s*\(\$f\[[\'"]schema[\'"]\]\[[\'"]sections[\'"]\]', content):
    start = max(0, m.start() - 100)
    end = min(len(content), m.end() + 200)
    print("Schema loop context:\n", content[start:end])

