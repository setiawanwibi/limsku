import subprocess, os, re
edge_path = r'C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe'

with open('scratch/test_render_lcp.html', 'r', encoding='utf-8') as f:
    h = f.read()

# Replace page margin and document wrapper
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
            padding: 5mm 10mm 4mm 10mm;
            background-color: #fff;
            box-sizing: border-box;
            page-break-after: always;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }'''
)

# For page 2 height: in Excel, rows 45 to 89 + 90-94 signature block
# In page 2, Row 70 in Excel has height 45.75pt which is mostly empty spacer!
# Changing Row 70 to 18pt brings page 2 comfortably within 280mm!
h_fixed = h_fixed.replace('style="height: 45.75pt;"', 'style="height: 18.0pt;"')

with open('scratch/test_both_pages.html', 'w', encoding='utf-8') as f:
    f.write(h_fixed)

pdf_out = os.path.abspath('scratch/test_both_pages.pdf')
html_file = os.path.abspath('scratch/test_both_pages.html')
subprocess.run([edge_path, '--headless', '--disable-gpu', '--no-margins', '--no-pdf-header-footer', f'--print-to-pdf={pdf_out}', f'file:///{html_file}'])

with open(pdf_out, 'rb') as f:
    c = f.read()
print('Both pages test PDF count:', re.findall(rb'/Count\s+(\d+)', c))
