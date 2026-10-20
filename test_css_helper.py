import json, sys

with open('template_processed_grid.json', encoding='utf-8') as f:
    grid = json.load(f)

col_pct = grid['col_pct']
rows = grid['rows']

def style_to_css(cell, r_height):
    fn = cell['fn']
    fs = cell['fs']
    # Scale font size slightly for print fit: 1pt in Excel is ~1.33px, or use pt directly
    # Excel uses 96 DPI: 1pt = 1/72 inch = 1.333px. In CSS print, pt is exact.
    # However in high density print, 7.5pt - 11pt fits exactly.
    b = cell['b']
    i = cell['i']
    ah = cell['ah']
    av = cell['av']
    borders = cell['borders']
    wrap = cell['wrap']
    
    css = []
    css.append(f"font-family: '{fn}', 'Times New Roman', serif;")
    css.append(f"font-size: {fs}pt;")
    if b: css.append("font-weight: bold;")
    if i: css.append("font-style: italic;")
    
    if ah in ['center', 'right', 'left']:
        css.append(f"text-align: {ah};")
    else:
        css.append("text-align: left;")
        
    if av == 'center':
        css.append("vertical-align: middle;")
    elif av == 'top':
        css.append("vertical-align: top;")
    elif av == 'bottom':
        css.append("vertical-align: bottom;")
    else:
        css.append("vertical-align: middle;")

    if wrap:
        css.append("white-space: normal; word-break: break-word;")
    else:
        css.append("white-space: nowrap;")

    # borders
    b_map = {'thin': '0.75pt solid #000', 'medium': '1.5pt solid #000', 'double': '2.5pt double #000', 'thick': '2pt solid #000'}
    if borders:
        if borders.get('t'): css.append(f"border-top: {b_map.get(borders['t'], '0.75pt solid #000')};")
        if borders.get('b'): css.append(f"border-bottom: {b_map.get(borders['b'], '0.75pt solid #000')};")
        if borders.get('l'): css.append(f"border-left: {b_map.get(borders['l'], '0.75pt solid #000')};")
        if borders.get('r'): css.append(f"border-right: {b_map.get(borders['r'], '0.75pt solid #000')};")

    return ' '.join(css)

print("CSS helper defined.")
