import json, os

for f in sorted(os.listdir('database/templates')):
    if not f.endswith('.json'): continue
    path = os.path.join('database/templates', f)
    with open(path, 'r', encoding='utf-8') as jf:
        d = json.load(jf)
    
    raw = json.dumps(d)
    has_bobot_rata = 'bobot_rata' in raw
    has_klaim = 'klaim' in raw
    has_kadar = 'kadar' in raw
    has_bm = 'faktor_konversi_bm' in raw or 'faktor_koreksi' in raw
    has_metode_kadar = 'metode_kadar' in raw
    
    print(f"=== {f} ({d.get('kode_template')} - {d.get('nama_template')}) ===")
    print(f"  has_metode_kadar: {has_metode_kadar}, has_bobot_rata: {has_bobot_rata}, has_klaim: {has_klaim}, has_bm: {has_bm}, has_kadar: {has_kadar}")
    
    # check tables
    for sec in d.get('skema_form', {}).get('sections', []):
        for fld in sec.get('fields', []):
            if fld.get('type') == 'table':
                print(f"  Table field: {fld.get('id')} - {fld.get('label')}")
                if 'columns' in fld:
                    cols = [c.get('id') or c.get('name') for c in fld['columns']]
                    print(f"    cols: {cols}")
                if 'rows' in fld:
                    print(f"    rows: {fld['rows']}")

