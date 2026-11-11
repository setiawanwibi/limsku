import json, os

for f in sorted(os.listdir('database/templates')):
    if f.endswith('.json'):
        with open(os.path.join('database/templates', f), 'r', encoding='utf-8') as jf:
            d = json.load(jf)
            print(f"{f}: {d.get('kode_template')} - {d.get('nama_template')}")

