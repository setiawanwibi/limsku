with open('scratch/tofixed_results.txt', 'r', encoding='utf-8') as f:
    lines = f.readlines()

for line in lines:
    line_no = int(line.split(':')[0].replace('Line ', ''))
    if 3900 <= line_no <= 4120 or 3300 <= line_no <= 3500:
        print(line.strip())

