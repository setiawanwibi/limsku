import json

with open('database/templates/KIM-05.json', 'r', encoding='utf-8') as f:
    d = json.load(f)

for sec in d.get('skema_form', {}).get('sections', []):
    for fld in sec.get('fields', []):
        if fld.get('type') == 'table':
            print("Table ID:", fld.get('id'))
            print("Cols:", [c['id'] for c in fld.get('columns', [])])
            print("Default rows:", [r.get('kategori', r.get('nama_larutan', '')) for r in fld.get('default_rows', [])])

