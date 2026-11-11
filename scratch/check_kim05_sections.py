import json

with open('database/templates/KIM-05.json', 'r', encoding='utf-8') as f:
    d = json.load(f)

for sec in d.get('skema_form', {}).get('sections', []):
    print("Section:", sec.get('id'), "|", sec.get('title'))
    for fld in sec.get('fields', []):
        if 'kadar' in fld.get('id', '') or 'hasil' in fld.get('id', '') or 'tabel' in fld.get('id', ''):
            print("  Field:", fld.get('id'), "|", fld.get('label'))

