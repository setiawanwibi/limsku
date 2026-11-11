with open('application/views/pengujian/proses_sesi.php', 'r', encoding='utf-8') as f:
    c = f.read()

idx = c.find('function updateMetodeKadarUI')
print(c[idx:idx+800])

