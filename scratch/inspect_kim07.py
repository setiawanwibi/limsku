import json

with open('database/templates/KIM-07.json', 'r', encoding='utf-8') as f:
    d = json.load(f)

print("Title:", d.get('nama_template'))
for sec in d['skema_form']['sections']:
    print(f"\n--- Section: {sec.get('title')} ({sec.get('id')}) ---")
    for fld in sec.get('fields', []):
        print(f"  Field: {fld.get('id')} | label: {fld.get('label')} | type: {fld.get('type')}")
        if fld.get('type') == 'table':
            print("    Columns:")
            for col in fld.get('columns', []):
                print(f"      - {col.get('id')} ({col.get('label')}) [{col.get('type')}]")

