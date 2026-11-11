with open('application/controllers/Pengujian.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

for i in range(490, 560):
    if i < len(lines):
        print(f"{i+1}: {lines[i].strip()[:100]}")

