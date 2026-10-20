import subprocess, os, re
edge_path = r'C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe'

with open('scratch/test_page1_only.html', 'r', encoding='utf-8') as f:
    h = f.read()

# Let's inspect CSS and margins in test_page1_only.html
print('Has document-wrapper min-height:', 'min-height: 297mm;' in h)
print('Has document-wrapper padding:', 'padding: 8mm 10mm;' in h)

# In test_page1_only:
# If table height is 732pt (=258mm), plus 8mm top padding + 8mm bottom padding = 274mm.
# If page margin is 8mm top + 8mm bottom, total required height is 274 + 16 = 290mm.
# A4 is 297mm, so it's barely 7mm away from overflow!
# If @page margin is 0 and document-wrapper width is 210mm, height: 297mm:
h_fixed = h.replace(
'''        @page {
            size: A4 portrait;
            margin: 8mm 10mm 8mm 10mm;
        }''',
'''        @page {
            size: A4 portrait;
            margin: 0;
        }'''
)

h_fixed = h_fixed.replace(
'''        .document-wrapper {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto 30px auto;
            padding: 8mm 10mm;
            background-color: #fff;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            page-break-after: always;
            box-sizing: border-box;
        }''',
'''        .document-wrapper {
            width: 210mm;
            height: 297mm;
            max-height: 297mm;
            margin: 0 auto;
            padding: 6mm 10mm;
            background-color: #fff;
            box-sizing: border-box;
            page-break-after: always;
            overflow: hidden;
        }'''
)

with open('scratch/test_page1_fixed.html', 'w', encoding='utf-8') as f:
    f.write(h_fixed)

pdf_out = os.path.abspath('scratch/test_page1_fixed.pdf')
html_file = os.path.abspath('scratch/test_page1_fixed.html')
subprocess.run([edge_path, '--headless', '--disable-gpu', '--no-margins', '--no-pdf-header-footer', f'--print-to-pdf={pdf_out}', f'file:///{html_file}'])

with open(pdf_out, 'rb') as f:
    c = f.read()
print('Fixed Page 1 PDF count:', re.findall(rb'/Count\s+(\d+)', c))
