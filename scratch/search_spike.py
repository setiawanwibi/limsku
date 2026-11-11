import os

for root, dirs, files in os.walk('.'):
    if any(p in root for p in ['.git', 'vendor', 'system', 'node_modules', '.gemini']):
        continue
    for f in files:
        if f.endswith(('.php', '.json', '.sql', '.md')):
            path = os.path.join(root, f)
            try:
                with open(path, 'r', encoding='utf-8', errors='ignore') as fp:
                    content = fp.read()
                    if 'spike' in content.lower():
                        print(f"Found 'spike' in: {path}")
            except:
                pass

