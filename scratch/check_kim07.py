import json

with open('database/templates/KIM-07.json', 'r', encoding='utf-8') as f:
    d = json.load(f)

print("Title:", d.get('nama_template'))
for sec in d.get('skema_form', {}).get('sections', []):
    print("Section:", sec.get('id'), "|", sec.get('title'))
    for fld in sec.get('fields', []):
        if fld.get('type') == 'table':
            print("  Table:", fld.get('id'))
            print("  Cols:", [c['id'] for c in fld.get('columns', [])])
            print("  Default rows:", [r.get('nama_totolan', r.get('kategori', '')) for r in fld.get('default_rows', [])])

