import os, re

matches = []
for root, dirs, files in os.walk('.'):
    if any(p in root for p in ['.git', 'vendor', 'system', 'node_modules', '.gemini']):
        continue
    for f in files:
        path = os.path.join(root, f)
        try:
            with open(path, 'r', encoding='utf-8', errors='ignore') as fp:
                for line in fp:
                    if 'f.01a' in line.lower() or 'f_01a' in line.lower():
                        matches.append((path, line.strip()))
        except:
            pass

for p, l in matches[:40]:
    print(f"{p}: {l[:120]}")

