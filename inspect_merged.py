import openpyxl, sys

sys.stdout.reconfigure(encoding='utf-8')
wb = openpyxl.load_workbook(r'd:\xampp\htdocs\limsku\template_kualitatif.xlsx', data_only=True)
ws = wb['Rapih']

merged_list = sorted(list(ws.merged_cells.ranges), key=lambda x: (x.min_row, x.min_col))
print(f"Total merged ranges: {len(merged_list)}")
for mr in merged_list:
    v = ws.cell(mr.min_row, mr.min_col).value
    print(f"{mr}: {repr(v)}")
