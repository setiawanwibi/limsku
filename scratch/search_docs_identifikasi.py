import os

for f in os.listdir('docs'):
    path = os.path.join('docs', f)
    if os.path.isfile(path):
        with open(path, 'r', encoding='utf-8', errors='ignore') as fp:
            for i, line in enumerate(fp):
                if 'identifikasi' in line.lower():
                    print(f"{f}:{i+1}: {line.strip()[:140]}")

