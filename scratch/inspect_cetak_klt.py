with open('application/views/pengujian/cetak_lcp_klt.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

for i, l in enumerate(lines):
    if any(k in l for k in ['klt_items', 'Baku', 'Pelarut', 'Zat Uji', 'Spike', 'Hasil Uji', 'Pustaka', 'kadar']):
        print(f"Line {i+1}: {l.strip()[:100]}")

