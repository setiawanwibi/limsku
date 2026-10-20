import subprocess, os, re
edge_path = r'C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe'

html_doc = """<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
@page {
    size: A4 portrait;
    margin: 0;
}
html, body {
    margin: 0;
    padding: 0;
    background: #fff;
}
.page {
    width: 210mm;
    height: 297mm;
    box-sizing: border-box;
    padding: 10mm;
    page-break-after: always;
}
.page:last-child {
    page-break-after: avoid;
}
</style>
</head>
<body>
<div class="page"><h1>Page 1</h1></div>
<div class="page"><h1>Page 2</h1></div>
</body>
</html>"""

with open('scratch/test_2page.html', 'w', encoding='utf-8') as f:
    f.write(html_doc)

pdf_out = os.path.abspath('scratch/test_2page.pdf')
html_file = os.path.abspath('scratch/test_2page.html')
subprocess.run([edge_path, '--headless', '--disable-gpu', '--no-margins', '--no-pdf-header-footer', f'--print-to-pdf={pdf_out}', f'file:///{html_file}'])

with open(pdf_out, 'rb') as f:
    c = f.read()

counts = re.findall(rb'/Count\s+(\d+)', c)
print('Pages count in test_2page.pdf:', counts)
