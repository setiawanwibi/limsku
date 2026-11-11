with open('application/views/pengujian/proses_sesi.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

print(f"Total lines: {len(lines)}")
for i, l in enumerate(lines):
    if 'foreach ($forms as' in l or 'foreach ($daftar_forms as' in l or 'foreach ($sesi_forms as' in l or 'Perhitungan Kadar & Verifikasi Presisi' in l or 'perhitungan_kadar' in l:
        print(f"Line {i+1}: {l.strip()[:100]}")

