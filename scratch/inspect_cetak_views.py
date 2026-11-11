import re

files = [
    'application/views/pengujian/cetak_lcp_kualitatif.php',
    'application/views/pengujian/cetak_lcp_klt.php',
    'application/views/pengujian/cetak_lcp_reaksi_warna.php',
    'application/views/pengujian/cetak_lcp_kckt.php',
    'application/views/pengujian/cetak_lcp_spektrofotometri.php'
]

for f in files:
    try:
        with open(f, 'r', encoding='utf-8') as fp:
            content = fp.read()
            title_m = re.findall(r'<title>(.*?)</title>', content, re.IGNORECASE)
            title1_m = re.findall(r'class="title-[12]">(.*?)</div>', content, re.IGNORECASE)
            print(f"=== {f} ===")
            print("  Title tag:", title_m)
            print("  Header titles:", title1_m)
    except Exception as e:
        print(f"Error {f}: {e}")

