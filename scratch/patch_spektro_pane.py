with open('application/views/pengujian/proses_sesi.php', 'r', encoding='utf-8') as f:
    text = f.read()

target = """        function hitungSpektrofotometriPane(pane) {
            if (!pane) return;"""

replacement = """        function hitungSpektrofotometriPane(pane) {
            if (!pane) return;
            var pName = (pane.getAttribute('data-parameter') || '').toLowerCase();
            var tName = (pane.getAttribute('data-nama-template') || '').toLowerCase();
            if (pName.indexOf('identifikasi') !== -1 || tName.indexOf('identifikasi') !== -1) {
                return; // Skip hitung kadar untuk LCP Identifikasi
            }"""

if target in text:
    text = text.replace(target, replacement, 1)
    with open('application/views/pengujian/proses_sesi.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Updated hitungSpektrofotometriPane successfully!")
else:
    print("Target not found with exact match, searching lines...")
    # Normalize newlines
    lines = text.splitlines(keepends=True)
    for i, line in enumerate(lines):
        if 'function hitungSpektrofotometriPane(pane) {' in line:
            print(f"Found line {i}: {repr(line)}")
            lines.insert(i+2, "            var pName = (pane.getAttribute('data-parameter') || '').toLowerCase();\n            var tName = (pane.getAttribute('data-nama-template') || '').toLowerCase();\n            if (pName.indexOf('identifikasi') !== -1 || tName.indexOf('identifikasi') !== -1) {\n                return;\n            }\n")
            with open('application/views/pengujian/proses_sesi.php', 'w', encoding='utf-8') as f:
                f.writelines(lines)
            print("Inserted successfully!")
            break

