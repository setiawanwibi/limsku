import json

for fname in ['KIM-01.json', 'KIM-05.json', 'KIM-07.json', 'KIM-08.json', 'KIM-09.json']:
    with open('database/templates/' + fname, 'r', encoding='utf-8') as f:
        d = json.load(f)
    print(f"\n================== {fname} ==================")
    print("Kode:", d.get('kode_template'))
    print("Nama:", d.get('nama_template'))
    for sec in d.get('skema_form', {}).get('sections', []):
        print("  Section:", sec.get('id'), "|", sec.get('title'))
        for fld in sec.get('fields', []):
            if fld.get('type') == 'table':
                print(f"    Table: {fld.get('id')} ({fld.get('label')})")
                if 'columns' in fld:
                    print("      cols:", [c.get('id') for c in fld['columns']])
                if 'rows' in fld:
                    print("      default rows:", fld['rows'])
            elif any(k in fld.get('id', '') for k in ['kadar', 'bobot_rata', 'klaim', 'faktor', 'metode']):
                print(f"    Field: {fld.get('id')} ({fld.get('label')}) [type: {fld.get('type')}]")

