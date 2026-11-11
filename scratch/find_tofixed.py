import re

with open('application/views/pengujian/proses_sesi.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

out = []
for i, line in enumerate(lines):
    if 'toFixed' in line or 'Math.round' in line:
        out.append(f"Line {i+1}: {line.strip()[:100]}")

with open('scratch/tofixed_results.txt', 'w', encoding='utf-8') as f:
    f.write("\n".join(out))

print(f"Found {len(out)} lines with toFixed/Math.round")
