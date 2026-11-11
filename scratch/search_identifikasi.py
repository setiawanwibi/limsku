import os, re

files_to_check = []
for root, dirs, files in os.walk('.'):
    if any(p in root for p in ['.git', 'vendor', 'system', 'node_modules', '.gemini']):
        continue
    for f in files:
        if f.endswith(('.php', '.json', '.sql', '.md')):
            files_to_check.append(os.path.join(root, f))

print(f"Total files to check: {len(files_to_check)}")

matches = {}
for fp in files_to_check:
    try:
        with open(fp, 'r', encoding='utf-8', errors='ignore') as f:
            content = f.read()
            if 'identifikasi' in content.lower():
                # count occurrences
                count = len(re.findall(r'identifikasi', content, re.IGNORECASE))
                matches[fp] = count
    except Exception as e:
        pass

for fp, cnt in sorted(matches.items(), key=lambda x: x[1], reverse=True)[:35]:
    print(f"{fp}: {cnt}")

