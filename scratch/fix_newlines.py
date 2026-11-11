with open('application/views/pengujian/proses_sesi.php', 'rb') as f:
    content = f.read()

# Replace any \r\r\n with \r\n
while b'\r\r\n' in content:
    content = content.replace(b'\r\r\n', b'\r\n')

with open('application/views/pengujian/proses_sesi.php', 'wb') as f:
    f.write(content)

with open('application/views/pengujian/proses_sesi.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

print(f"Cleaned proses_sesi.php line count: {len(lines)}")

