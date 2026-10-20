import openpyxl, json, html, re

wb = openpyxl.load_workbook(r'd:\xampp\htdocs\limsku\template_kualitatif.xlsx', data_only=True)
ws = wb['Rapih']

col_widths_char = [ws.column_dimensions[col_let].width for col_let in 'ABCDEFGHIJKLMNOPQRST']
tot_chars = sum(col_widths_char)
col_widths_pct = [round((ch / tot_chars) * 100, 3) for ch in col_widths_char]

# Map merged ranges
merged_lookup = {}
for mr in sorted(list(ws.merged_cells.ranges), key=lambda x: (x.min_row, x.min_col)):
    for r in range(mr.min_row, mr.max_row + 1):
        for c in range(mr.min_col, mr.max_col + 1):
            merged_lookup[(r, c)] = {
                'is_origin': (r == mr.min_row and c == mr.min_col),
                'min_r': mr.min_row,
                'max_r': mr.max_row,
                'min_c': mr.min_col,
                'max_c': mr.max_col,
                'rowspan': mr.max_row - mr.min_row + 1,
                'colspan': mr.max_col - mr.min_col + 1
            }

def get_block_borders(r, c):
    if (r, c) in merged_lookup:
        m = merged_lookup[(r, c)]
        if not m['is_origin']:
            return None
        min_r, max_r = m['min_r'], m['max_r']
        min_c, max_c = m['min_c'], m['max_c']
    else:
        min_r, max_r = r, r
        min_c, max_c = c, c

    top = None
    for col in range(min_c, max_c + 1):
        b = ws.cell(min_r, col).border
        if b and b.top and b.top.style:
            top = b.top.style
            break

    bottom = None
    for col in range(min_c, max_c + 1):
        b = ws.cell(max_r, col).border
        if b and b.bottom and b.bottom.style:
            bottom = b.bottom.style
            break

    left = None
    for row in range(min_r, max_r + 1):
        b = ws.cell(row, min_c).border
        if b and b.left and b.left.style:
            left = b.left.style
            break

    right = None
    for row in range(min_r, max_r + 1):
        b = ws.cell(row, max_c).border
        if b and b.right and b.right.style:
            right = b.right.style
            break

    # Outer border enforcement
    if min_r == 1: top = 'medium'
    if max_r == 94: bottom = 'medium'
    if min_c == 1: left = 'medium'
    if max_c == 20: right = 'medium'

    return {'t': top, 'b': bottom, 'l': left, 'r': right}

# Dynamic cell overrides
dynamic_cells = {
    # Target mikroba (R5-R7)
    (5, 5): "<?php echo (!empty($p['target_staphylococcus']) ? '&#9745;' : '&#9744;'); ?>",
    (5, 10): "<?php echo (!empty($p['target_candida']) ? '&#9745;' : '&#9744;'); ?>",
    (5, 14): "<?php echo (!empty($p['target_salmonella']) ? '&#9745;' : '&#9744;'); ?>",
    (6, 5): "<?php echo (!empty($p['target_pseudomonas']) ? '&#9745;' : '&#9744;'); ?>",
    (6, 10): "<?php echo (!empty($p['target_shigella']) ? '&#9745;' : '&#9744;'); ?>",
    (6, 14): "<?php echo (!empty($p['target_ecoli']) ? '&#9745;' : '&#9744;'); ?>",
    (7, 14): "<?php echo (!empty($p['target_lainnya_check']) ? '&#9745;' : '&#9744;'); ?>",
    (7, 15): "Lainnya: <?php echo !empty($p['target_lainnya_text']) ? '<u>' . html_escape($p['target_lainnya_text']) . '</u>' : '..............'; ?>",

    # Lab location (R8)
    (8, 2): "Laboratorium Mikrobiologi Balai Besar POM di Bandar Lampung",

    # Sample info (R10-R12)
    (10, 2): "Nama Sampel Uji :",
    (11, 2): "<strong style=\"font-size: 11pt; color: #000; line-height: 1.25;\"><?php echo html_escape($sampel['nama_sampel'] ?? '-'); ?></strong>",
    (10, 9): "Kode Sampel Uji :",
    (11, 9): "<strong style=\"font-size: 11pt; color: #000; line-height: 1.25;\"><?php echo html_escape(!empty($sampel['kode_sampel_manual']) ? $sampel['kode_sampel_manual'] : (!empty($sampel['no']) ? 'NO-' . $sampel['no'] : 'SMP-' . $sampel['id'])); ?></strong>",
    (10, 18): "<span style=\"font-size: 10pt;\"><?php echo !empty($sampel['tanggal_sampling']) ? date('d-m-Y', strtotime($sampel['tanggal_sampling'])) : (!empty($sampel['created_at']) ? date('d-m-Y', strtotime($sampel['created_at'])) : '-'); ?></span>",
    (11, 18): "<span style=\"font-size: 10pt; font-weight: bold;\"><?php echo html_escape($penguji['nama_lengkap'] ?? ($sesi['nama_penguji'] ?? '-')); ?></span>",
    (12, 18): "<span style=\"font-size: 10pt;\"><?php echo !empty($sesi['waktu_mulai']) ? date('d-m-Y', strtotime($sesi['waktu_mulai'])) : date('d-m-Y'); ?></span>",

    # Kesesuaian metode (R15-R19)
    (15, 5): "<?php echo (isset($p['pelarut_kesesuaian']) && $p['pelarut_kesesuaian'] === 'MLB') ? '&#9745;' : '&#9744;'; ?>",
    (16, 5): "<?php echo (isset($p['pelarut_kesesuaian']) && $p['pelarut_kesesuaian'] === 'TSB') ? '&#9745;' : '&#9744;'; ?>",
    (17, 5): "<?php echo (isset($p['pelarut_kesesuaian']) && $p['pelarut_kesesuaian'] === 'TSB + 30 g/L polysorbate 80') ? '&#9745;' : '&#9744;'; ?>",
    (18, 5): "<?php echo (isset($p['pelarut_kesesuaian']) && $p['pelarut_kesesuaian'] === 'TSB + 30 g/L polysorbate 80 + 3 g/L Lesitin') ? '&#9745;' : '&#9744;'; ?>",
    (19, 5): "<?php echo (isset($p['pelarut_kesesuaian']) && $p['pelarut_kesesuaian'] === 'Lainnya') ? '&#9745;' : '&#9744;'; ?>",
    (19, 6): "Lainnya: <?php echo !empty($p['pelarut_kesesuaian_lainnya']) ? '<u>' . html_escape($p['pelarut_kesesuaian_lainnya']) . '</u>' : '…..........................'; ?>",

    (16, 12): "<?php echo (!empty($p['baku_staphylococcus']) ? '&#9745;' : '&#9744;'); ?>",
    (16, 13): "Staphylococcus aures ATCC / WDCM <?php echo !empty($p['baku_staphylococcus_no']) ? '<u>' . html_escape($p['baku_staphylococcus_no']) . '</u>' : '…................'; ?>",
    (17, 12): "<?php echo (!empty($p['baku_pseudomonas']) ? '&#9745;' : '&#9744;'); ?>",
    (17, 13): "Pseudomonas aeruginosa ATCC / WDCM <?php echo !empty($p['baku_pseudomonas_no']) ? '<u>' . html_escape($p['baku_pseudomonas_no']) . '</u>' : '…...............'; ?>",
    (18, 12): "<?php echo (!empty($p['baku_candida']) ? '&#9745;' : '&#9744;'); ?>",
    (18, 13): "Candida albicans ATCC / WDCM <?php echo !empty($p['baku_candida_no']) ? '<u>' . html_escape($p['baku_candida_no']) . '</u>' : '…...............'; ?>",
    (19, 12): "<?php echo (!empty($p['baku_lainnya']) ? '&#9745;' : '&#9744;'); ?>",
    (19, 13): "Lainnya: <?php echo !empty($p['baku_lainnya']) ? '<u>' . html_escape($p['baku_lainnya']) . '</u>' : '…..........................'; ?>",

    # Homogenisasi awal (R22-R27)
    (22, 6): "<strong style=\"font-size: 11pt;\"><?php echo html_escape($p['bobot_sampel'] ?? '10.0'); ?></strong>",
    (23, 6): "<strong style=\"font-size: 11pt;\"><?php echo html_escape($p['volume_pelarut'] ?? '90.0'); ?></strong>",
    (23, 8): "<?php echo (isset($p['jenis_pelarut_homogenisasi']) && $p['jenis_pelarut_homogenisasi'] === 'MLB') ? '&#9745;' : '&#9744;'; ?>",
    (24, 8): "<?php echo (isset($p['jenis_pelarut_homogenisasi']) && $p['jenis_pelarut_homogenisasi'] === 'TSB') ? '&#9745;' : '&#9744;'; ?>",
    (25, 8): "<?php echo (isset($p['jenis_pelarut_homogenisasi']) && $p['jenis_pelarut_homogenisasi'] === 'TSB + 30 g/L polysorbate 80') ? '&#9745;' : '&#9744;'; ?>",
    (26, 8): "<?php echo (isset($p['jenis_pelarut_homogenisasi']) && $p['jenis_pelarut_homogenisasi'] === 'TSB + 30 g/L polysorbate 80 + 3 g/L Lesitin') ? '&#9745;' : '&#9744;'; ?>",
    (27, 8): "<?php echo (isset($p['jenis_pelarut_homogenisasi']) && $p['jenis_pelarut_homogenisasi'] === 'Lainnya') ? '&#9745;' : '&#9744;'; ?>",
    (27, 9): "Lainnya : <?php echo !empty($p['pelarut_homogenisasi_lainnya']) ? '<u>' . html_escape($p['pelarut_homogenisasi_lainnya']) . '</u>' : '….......................'; ?>",

    # Inokulum tingkat (R31-R33)
    (31, 12): "<?php echo (isset($p['inokulum_tingkat']) && $p['inokulum_tingkat'] === '1 g atau mL') ? '&#9745;' : '&#9744;'; ?>",
    (31, 14): "<?php echo (isset($p['inokulum_tingkat']) && $p['inokulum_tingkat'] === '1 g atau mL') ? '&#9745;' : '&#9744;'; ?>",
    (31, 16): "<?php echo (isset($p['inokulum_tingkat']) && $p['inokulum_tingkat'] === '1 g atau mL') ? '&#9745;' : '&#9744;'; ?>",
    (31, 18): "<?php echo (isset($p['inokulum_tingkat']) && $p['inokulum_tingkat'] === '1 g atau mL') ? '&#9745;' : '&#9744;'; ?>",

    (32, 12): "<?php echo (isset($p['inokulum_tingkat']) && $p['inokulum_tingkat'] === '0,1 g atau mL') ? '&#9745;' : '&#9744;'; ?>",
    (32, 14): "<?php echo (isset($p['inokulum_tingkat']) && $p['inokulum_tingkat'] === '0,1 g atau mL') ? '&#9745;' : '&#9744;'; ?>",
    (32, 16): "<?php echo (isset($p['inokulum_tingkat']) && $p['inokulum_tingkat'] === '0,1 g atau mL') ? '&#9745;' : '&#9744;'; ?>",
    (32, 18): "<?php echo (isset($p['inokulum_tingkat']) && $p['inokulum_tingkat'] === '0,1 g atau mL') ? '&#9745;' : '&#9744;'; ?>",

    (33, 12): "<?php echo (isset($p['inokulum_tingkat']) && $p['inokulum_tingkat'] === 'Lainnya') ? '&#9745;' : '&#9744;'; ?>",
    (33, 14): "<?php echo (isset($p['inokulum_tingkat']) && $p['inokulum_tingkat'] === 'Lainnya') ? '&#9745;' : '&#9744;'; ?>",
    (33, 16): "<?php echo (isset($p['inokulum_tingkat']) && $p['inokulum_tingkat'] === 'Lainnya') ? '&#9745;' : '&#9744;'; ?>",
    (33, 18): "<?php echo (isset($p['inokulum_tingkat']) && $p['inokulum_tingkat'] === 'Lainnya') ? '&#9745;' : '&#9744;'; ?>",

    # Stage 0: Pra Pengayaan (R34)
    (34, 2): "<?php echo !empty($t0['tanggal']) ? date('d-m-Y', strtotime($t0['tanggal'])) : (!empty($contoh_mode) ? 'N/A' : ''); ?>",
    (34, 12): "<?php echo !empty($t0['sampel_spike']) ? html_escape($t0['sampel_spike']) : (!empty($contoh_mode) ? 'N/A' : ''); ?>",
    (34, 14): "<?php echo !empty($t0['media_spike']) ? html_escape($t0['media_spike']) : (!empty($contoh_mode) ? 'N/A' : ''); ?>",
    (34, 16): "<?php echo !empty($t0['kontrol_media']) ? html_escape($t0['kontrol_media']) : (!empty($contoh_mode) ? 'N/A' : ''); ?>",
    (34, 18): "<?php echo !empty($t0['sampel_uji']) ? html_escape($t0['sampel_uji']) : (!empty($contoh_mode) ? 'N/A' : ''); ?>",

    # Stage 1: Pengayaan (R37)
    (37, 2): "<?php echo !empty($t1['tanggal']) ? date('d-m-Y', strtotime($t1['tanggal'])) : ''; ?>",
    (37, 12): "<?php echo !empty($t1['sampel_spike']) ? html_escape($t1['sampel_spike']) : ''; ?>",
    (37, 14): "<?php echo !empty($t1['media_spike']) ? html_escape($t1['media_spike']) : 'Keruh'; ?>",
    (37, 16): "<?php echo !empty($t1['kontrol_media']) ? html_escape($t1['kontrol_media']) : ''; ?>",
    (37, 18): "<?php echo !empty($t1['sampel_uji']) ? html_escape($t1['sampel_uji']) : ''; ?>",

    # Stage 2: Seleksi (R45)
    (45, 2): "<?php echo !empty($t2['tanggal']) ? date('d-m-Y', strtotime($t2['tanggal'])) : ''; ?>",
    (45, 12): "<?php echo !empty($t2['sampel_spike']) ? html_escape($t2['sampel_spike']) : ''; ?>",
    (45, 14): "<?php echo !empty($t2['media_spike']) ? html_escape($t2['media_spike']) : 'Keruh'; ?>",
    (45, 16): "<?php echo !empty($t2['kontrol_media']) ? html_escape($t2['kontrol_media']) : ''; ?>",
    (45, 18): "<?php echo !empty($t2['sampel_uji']) ? html_escape($t2['sampel_uji']) : ''; ?>",

    # Stage 3: Isolasi (R50)
    (50, 2): "<?php echo !empty($t3['tanggal']) ? date('d-m-Y', strtotime($t3['tanggal'])) : ''; ?>",
    (50, 12): "<?php echo !empty($t3['sampel_spike']) ? html_escape($t3['sampel_spike']) : ''; ?>",
    (50, 14): "<?php echo !empty($t3['media_spike']) ? html_escape($t3['media_spike']) : 'Positif'; ?>",
    (50, 16): "<?php echo !empty($t3['kontrol_media']) ? html_escape($t3['kontrol_media']) : ''; ?>",
    (50, 18): "<?php echo !empty($t3['sampel_uji']) ? html_escape($t3['sampel_uji']) : ''; ?>",

    # Stage 4: Konfirmasi (R60)
    (60, 2): "<?php echo !empty($t4['tanggal']) ? date('d-m-Y', strtotime($t4['tanggal'])) : ''; ?>",
    (60, 12): "<?php echo !empty($t4['sampel_spike']) ? html_escape($t4['sampel_spike']) : ''; ?>",
    (60, 14): "<?php echo !empty($t4['media_spike']) ? nl2br(html_escape($t4['media_spike'])) : 'Biokimia Kit / Konvensional *)<br><br>...............................<br><br>'; ?>",
    (60, 16): "<?php echo !empty($t4['kontrol_media']) ? html_escape($t4['kontrol_media']) : 'N/A'; ?>",
    (60, 18): "<?php echo !empty($t4['sampel_uji']) ? html_escape($t4['sampel_uji']) : ''; ?>",

    # Hasil Uji, Pustaka, Syarat, Kesimpulan (R84-R89)
    (84, 4): ": <strong style=\"font-size: 11pt; color: #000;\"><?php echo html_escape($p['hasil_uji'] ?? ($hasil['hasil_uji'] ?? '-')); ?></strong>",
    (86, 4): ": <span style=\"font-size: 10.5pt;\"><?php echo html_escape($p['pustaka_metode'] ?? ($hasil['pustaka_acuan'] ?? ($metode['pustaka_acuan'] ?? '-'))); ?></span>",
    (86, 14): "Syarat : <strong style=\"font-size: 10.5pt; color: #c00;\"><?php echo html_escape($p['syarat'] ?? ($hasil['persyaratan'] ?? ($metode['persyaratan'] ?? '-'))); ?></strong>",
    (89, 4): """<?php
        $ks = $p['kesimpulan_lcp'] ?? ($hasil['kesimpulan'] ?? '');
        $ms_style = (strpos($ks, 'MS') !== false && strpos($ks, 'TMS') === false) ? 'font-weight: bold; background: #e8f5e9; padding: 2px 6px; border: 1.5px solid #2e7d32; border-radius: 3px;' : 'color: #333;';
        $tms_style = (strpos($ks, 'TMS') !== false) ? 'font-weight: bold; background: #ffebee; padding: 2px 6px; border: 1.5px solid #c62828; border-radius: 3px;' : 'color: #333;';
        $hpst_style = (strpos($ks, 'HPST') !== false) ? 'font-weight: bold; background: #e3f2fd; padding: 2px 6px; border: 1.5px solid #1565c0; border-radius: 3px;' : 'color: #333;';
    ?>
    <span style="<?php echo $ms_style; ?>">Memenuhi Syarat (MS)</span> / 
    <span style="<?php echo $tms_style; ?>">Tidak Memenuhi Syarat (TMS)</span> / 
    <span style="<?php echo $hpst_style; ?>">Hasil Pengujian Seperti Tersebut (HPST)</span> *)
    """,

    # Signatures (R91-R93)
    (91, 2): "Tanda Tangan Penguji :",
    (91, 14): "&nbsp;&nbsp;Diperiksa oleh :",
    (92, 2): """<?php if (!empty($ttd_penguji_img)): ?>
        <div style="height: 48px; display: flex; align-items: center; justify-content: flex-start; padding-left: 20px;">
            <img src="<?php echo $ttd_penguji_img; ?>" style="max-height: 44px; max-width: 140px; object-fit: contain;" alt="TTD Penguji">
        </div>
    <?php else: ?>
        <div style="height: 48px; display: flex; align-items: center; padding-left: 20px; color: #777; font-size: 9pt; font-style: italic;">
            [ Tanda Tangan Penguji ]
        </div>
    <?php endif; ?>
    <div style=\"font-size: 9.5pt; font-weight: bold; padding-left: 20px;\"><?php echo html_escape($penguji['nama_lengkap'] ?? ($sesi['nama_penguji'] ?? '-')); ?></div>
    """,
    (92, 14): """<?php if ($status_sesi === 'Approved / Final' || $status_sesi === 'Menunggu Approval'): ?>
        <?php if (!empty($ttd_penyelia_img)): ?>
            <div style="height: 48px; display: flex; align-items: center; justify-content: flex-start; padding-left: 20px;">
                <img src="<?php echo $ttd_penyelia_img; ?>" style="max-height: 44px; max-width: 140px; object-fit: contain;" alt="TTD Penyelia">
            </div>
        <?php else: ?>
            <div style="height: 48px; display: flex; align-items: center; padding-left: 20px; color: #2e7d32; font-size: 8.5pt; font-weight: bold;">
                [ VERIFIED DIGITAL ]
            </div>
        <?php endif; ?>
        <div style=\"font-size: 9.5pt; font-weight: bold; padding-left: 20px;\"><?php echo html_escape($verifier['nama_lengkap'] ?? ($sesi['nama_verifier'] ?? '-')); ?></div>
    <?php else: ?>
        <div style="height: 48px; display: flex; align-items: center; padding-left: 20px; color: #999; font-size: 8.5pt; font-style: italic;">
            (Menunggu Verifikasi Penyelia)
        </div>
        <div style=\"font-size: 9.5pt; color: #777; padding-left: 20px;\">(TTD, Nama)</div>
    <?php endif; ?>
    """,
    (93, 2): "Tanggal : <span style=\"font-size: 10pt;\"><?php echo !empty($sesi['waktu_selesai']) ? date('d-m-Y', strtotime($sesi['waktu_selesai'])) : date('d-m-Y'); ?></span>",
    (93, 14): "&nbsp;&nbsp;Tanggal : <span style=\"font-size: 10pt;\"><?php echo ($status_sesi === 'Approved / Final' || $status_sesi === 'Menunggu Approval') && !empty($sesi['waktu_verifikasi']) ? date('d-m-Y', strtotime($sesi['waktu_verifikasi'])) : '-'; ?></span>"
}

def render_cell(r, c):
    cell = ws.cell(r, c)
    val = cell.value

    # Check merge info
    rowspan = 1
    colspan = 1
    if (r, c) in merged_lookup:
        m = merged_lookup[(r, c)]
        if not m['is_origin']:
            return ""  # Skip non-origin cells
        rowspan = m['rowspan']
        colspan = m['colspan']

    # Get outer borders of block
    bb = get_block_borders(r, c)

    # Styles
    styles = []
    
    # Border styles
    if bb:
        if bb['t'] == 'medium': styles.append("border-top: 1.5pt solid #000")
        elif bb['t'] == 'thin': styles.append("border-top: 0.75pt solid #000")

        if bb['b'] == 'medium': styles.append("border-bottom: 1.5pt solid #000")
        elif bb['b'] == 'thin': styles.append("border-bottom: 0.75pt solid #000")

        if bb['l'] == 'medium': styles.append("border-left: 1.5pt solid #000")
        elif bb['l'] == 'thin': styles.append("border-left: 0.75pt solid #000")

        if bb['r'] == 'medium': styles.append("border-right: 1.5pt solid #000")
        elif bb['r'] == 'thin': styles.append("border-right: 0.75pt solid #000")

    # Font styles
    fn = cell.font.name if cell.font and cell.font.name else 'Times New Roman'
    fs = cell.font.size if cell.font and cell.font.size else 10.0
    bold = cell.font.bold if cell.font else False
    italic = cell.font.italic if cell.font else False

    # Title rows special font
    if r in [2, 3]:
        fs = 14.5
        bold = True
    elif (r, c) == (8, 2):
        fs = 10.0
        bold = False

    if fs != 10.0:
        styles.append(f"font-size: {fs}pt")
    if bold:
        styles.append("font-weight: bold")
    if italic:
        styles.append("font-style: italic")

    # Alignment
    ah = cell.alignment.horizontal if cell.alignment else None
    av = cell.alignment.vertical if cell.alignment else None
    wrap = cell.alignment.wrap_text if cell.alignment else False

    if ah == 'center':
        styles.append("text-align: center")
    elif ah == 'right':
        styles.append("text-align: right")
    elif ah == 'left':
        styles.append("text-align: left")

    if av == 'top':
        styles.append("vertical-align: top")
    elif av == 'bottom':
        styles.append("vertical-align: bottom")
    else:
        styles.append("vertical-align: middle")

    if wrap:
        styles.append("white-space: normal")

    # Content
    if (r, c) in dynamic_cells:
        content = dynamic_cells[(r, c)]
    elif val is not None:
        str_val = str(val)
        if str_val == '☐':
            content = "&#9744;"
            styles.append("font-size: 11pt")
        elif str_val == '☑':
            content = "&#9745;"
            styles.append("font-size: 11pt")
        else:
            content = html.escape(str_val).replace("\n", "<br>")
    else:
        content = ""

    # Special header logo container in R2C1
    if (r, c) == (2, 1):
        content = f"""<div style="position: relative; width: 100%; min-height: 48px; display: flex; align-items: center; justify-content: center;">
            <div style="position: absolute; left: 18px; top: -6px;">
                <img src="<?php echo base_url('assets/img/bpom_logo_excel.png'); ?>" style="height: 54px; width: auto;" alt="Logo BPOM">
            </div>
            <div>
                <div style="font-size: 14pt; font-weight: bold; letter-spacing: 0.5px;">LAMPIRAN CATATAN PENGUJIAN</div>
            </div>
        </div>"""

    # Tag attributes
    attrs = []
    if rowspan > 1:
        attrs.append(f'rowspan="{rowspan}"')
    if colspan > 1:
        attrs.append(f'colspan="{colspan}"')
    if styles:
        attrs.append(f'style="{"; ".join(styles)}"')

    attr_str = (" " + " ".join(attrs)) if attrs else ""
    return f"<td{attr_str}>{content}</td>"

print("Compiling PHP view file...")

php_header = """<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * View Cetak / Simpan PDF Lembar Catatan Pengujian (LCP)
 * Cemaran Mikroba Kualitatif Sediaan Farmasi
 * Nomor Dokumen: POM-06.02/CFM.01/SOP.01/F.01B.13
 * Replikasi presisi 100% dari file Excel Template Resmi.
 */

$status_sesi = $sesi['status'] ?? 'Draft';
$p = !empty($hasil['data_hasil']) ? json_decode($hasil['data_hasil'], true) : array();
if (isset($p['parameter_values']) && is_array($p['parameter_values'])) {
    $p = array_merge($p, $p['parameter_values']);
}
$tahapan = isset($p['tabel_tahapan']) && is_array($p['tabel_tahapan']) ? $p['tabel_tahapan'] : array();
$t0 = $tahapan[0] ?? array();
$t1 = $tahapan[1] ?? array();
$t2 = $tahapan[2] ?? array();
$t3 = $tahapan[3] ?? array();
$t4 = $tahapan[4] ?? array();

// Tanda tangan path checking
$ttd_penguji_img = '';
if (!empty($signature_penguji) && !empty($signature_penguji['signature_file'])) {
    $fpath = FCPATH . 'uploads/signatures/' . $signature_penguji['signature_file'];
    if (file_exists($fpath)) {
        $ttd_penguji_img = base_url('uploads/signatures/' . $signature_penguji['signature_file']);
    }
}
$ttd_penyelia_img = '';
if (!empty($signature_verifier) && !empty($signature_verifier['signature_file'])) {
    $fpath = FCPATH . 'uploads/signatures/' . $signature_verifier['signature_file'];
    if (file_exists($fpath)) {
        $ttd_penyelia_img = base_url('uploads/signatures/' . $signature_verifier['signature_file']);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak LCP - POM-06.02/CFM.01/SOP.01/F.01B.13 - <?php echo html_escape($sampel['nama_sampel'] ?? 'Sampel'); ?></title>
    <style>
        /* Reset and Print Setup */
        @page {
            size: A4 portrait;
            margin: 0;
        }

        *, *::before, *::after {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        body {
            margin: 0;
            padding: 20px 0;
            background-color: #525659;
            font-family: "Times New Roman", Times, serif;
            font-size: 10pt;
            color: #000;
            line-height: 1.15;
        }

        /* Floating Controls */
        .no-print {
            position: fixed;
            top: 15px;
            right: 25px;
            z-index: 9999;
            display: flex;
            gap: 10px;
            background: rgba(255, 255, 255, 0.95);
            padding: 8px 14px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(4px);
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: 13px;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-print {
            background-color: #198754;
            color: #fff;
        }
        .btn-print:hover {
            background-color: #146c43;
        }

        .btn-back {
            background-color: #6c757d;
            color: #fff;
        }
        .btn-back:hover {
            background-color: #565e64;
        }

        /* Document Paper */
        .document-wrapper {
            width: 210mm;
            height: 297mm;
            max-height: 297mm;
            margin: 0 auto 30px auto;
            padding: 5mm 10mm 4mm 10mm;
            background-color: #fff;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            box-sizing: border-box;
            page-break-after: always;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .document-wrapper:last-child {
            margin-bottom: 0;
            page-break-after: avoid;
        }

        /* Excel Exact Grid Table */
        table.excel-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-family: "Times New Roman", Times, serif;
            font-size: 10pt;
            color: #000;
        }

        table.excel-table td {
            padding: 1px 3px;
            overflow: hidden;
            word-wrap: break-word;
            line-height: 1.15;
        }

        /* Print Media Overrides */
        @media print {
            body {
                background: none !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .document-wrapper {
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                width: 100% !important;
                min-height: auto !important;
            }
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body>

    <!-- Floating Print / Save Controls -->
    <div class="no-print">
        <button onclick="window.print()" class="btn-action btn-print">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
            </svg>
            Cetak / Simpan PDF
        </button>
        <button onclick="window.close()" class="btn-action btn-back">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
            </svg>
            Tutup
        </button>
    </div>
"""

# Render Colgroup
colgroup_html = "        <colgroup>\n"
for i, pct in enumerate(col_widths_pct):
    colgroup_html += f'            <col style="width: {pct}%;">\n'
colgroup_html += "        </colgroup>\n"

# Page 1 Table (Rows 1 to 44)
p1_table = f"""
    <!-- HALAMAN 1 -->
    <div class="document-wrapper">
        <table class="excel-table">
{colgroup_html}            <tbody>
"""

for r in range(1, 45):
    h = ws.row_dimensions[r].height or 15.0
    p1_table += f'                <tr style="height: {h}pt;">\n'
    for c in range(1, 21):
        cell_html = render_cell(r, c)
        if cell_html:
            p1_table += f"                    {cell_html}\n"
    p1_table += "                </tr>\n"

# Close Page 1 table and add Page 1 footer
p1_table += """            </tbody>
        </table>

        <!-- Footer Halaman 1 -->
        <table class="excel-table" style="margin-top: 4px;">
""" + colgroup_html + """            <tbody>
                <tr style="height: 15.5pt;">
                    <td style="border: none;"></td>
                    <td colspan="15" style="border: none; font-size: 11pt;">POM-06.02/CFM.01/SOP.01/F.01B.13</td>
                    <td colspan="4" style="border: none; font-size: 11pt; text-align: right;">Lembar 1 dari 2 halaman</td>
                </tr>
            </tbody>
        </table>
    </div>
"""

# Page 2 Table (Rows 45 to 94)
p2_table = f"""
    <!-- HALAMAN 2 -->
    <div class="document-wrapper page-break">
        <table class="excel-table">
{colgroup_html}            <tbody>
"""

for r in range(45, 90):
    h = ws.row_dimensions[r].height or 15.0
    if r == 70:
        h = 18.0  # Adjust spacer row height to prevent A4 print boundary spill
    p2_table += f'                <tr style="height: {h}pt;">\n'
    for c in range(1, 21):
        cell_html = render_cell(r, c)
        if cell_html:
            p2_table += f"                    {cell_html}\n"
    p2_table += "                </tr>\n"

# Custom Signature Block Rows 90 to 94
# Row 90: Top padding spacer
p2_table += '''                <tr style="height: 6.75pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="12"></td>
                    <td colspan="6" style="border-left: 0.75pt solid #000;"></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
'''

# Row 91: Labels
p2_table += '''                <tr style="height: 15.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="12" style="font-size: 11pt; vertical-align: middle;">Tanda Tangan Penguji :</td>
                    <td colspan="6" style="border-left: 0.75pt solid #000; font-size: 11pt; vertical-align: middle; padding-left: 8px;">Diperiksa oleh :</td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
'''

# Row 92: Digital Signature & Names
p2_table += '''                <tr style="height: 52pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="12" style="vertical-align: middle; padding-left: 15px;">
                        <?php if (!empty($ttd_penguji_img)): ?>
                            <div style="height: 44px; display: flex; align-items: center; justify-content: flex-start;">
                                <img src="<?php echo $ttd_penguji_img; ?>" style="max-height: 42px; max-width: 140px; object-fit: contain;" alt="TTD Penguji">
                            </div>
                        <?php else: ?>
                            <div style="height: 40px; display: flex; align-items: center; color: #888; font-size: 9pt; font-style: italic;">
                                [ Tanda Tangan Digital Penguji ]
                            </div>
                        <?php endif; ?>
                        <div style="font-size: 10pt; font-weight: bold; margin-top: 2px; color: #000;"><?php echo html_escape($penguji['nama_lengkap'] ?? ($sesi['nama_penguji'] ?? '-')); ?></div>
                    </td>
                    <td colspan="6" style="border-left: 0.75pt solid #000; vertical-align: middle; padding-left: 15px;">
                        <?php if ($status_sesi === 'Approved / Final' || $status_sesi === 'Menunggu Approval'): ?>
                            <?php if (!empty($ttd_penyelia_img)): ?>
                                <div style="height: 44px; display: flex; align-items: center; justify-content: flex-start;">
                                    <img src="<?php echo $ttd_penyelia_img; ?>" style="max-height: 42px; max-width: 140px; object-fit: contain;" alt="TTD Penyelia">
                                </div>
                            <?php else: ?>
                                <div style="height: 40px; display: flex; align-items: center; color: #198754; font-size: 9pt; font-weight: bold;">
                                    [ VERIFIED DIGITAL ]
                                </div>
                            <?php endif; ?>
                            <div style="font-size: 10pt; font-weight: bold; margin-top: 2px; color: #000;"><?php echo html_escape($verifier['nama_lengkap'] ?? ($sesi['nama_verifier'] ?? '-')); ?></div>
                        <?php else: ?>
                            <div style="height: 40px; display: flex; align-items: center; color: #888; font-size: 8.5pt; font-style: italic;">
                                (Menunggu Verifikasi Penyelia)
                            </div>
                            <div style="font-size: 9.5pt; color: #666; margin-top: 2px;">(TTD, Nama)</div>
                        <?php endif; ?>
                    </td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
'''

# Row 93: Date
p2_table += '''                <tr style="height: 15.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="12" style="font-size: 11pt; vertical-align: middle;">
                        Tanggal : <span style="font-size: 10pt;"><?php echo !empty($sesi['waktu_selesai']) ? date('d-m-Y', strtotime($sesi['waktu_selesai'])) : date('d-m-Y'); ?></span>
                    </td>
                    <td colspan="6" style="border-left: 0.75pt solid #000; font-size: 11pt; vertical-align: middle; padding-left: 8px;">
                        Tanggal : <span style="font-size: 10pt;"><?php echo ($status_sesi === 'Approved / Final' || $status_sesi === 'Menunggu Approval') && !empty($sesi['waktu_verifikasi']) ? date('d-m-Y', strtotime($sesi['waktu_verifikasi'])) : '-'; ?></span>
                    </td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
'''

# Row 94: Bottom border
p2_table += '''                <tr style="height: 6.75pt;">
                    <td style="border-left: 1.5pt solid #000; border-bottom: 1.5pt solid #000;"></td>
                    <td colspan="12" style="border-bottom: 1.5pt solid #000;"></td>
                    <td colspan="6" style="border-left: 0.75pt solid #000; border-bottom: 1.5pt solid #000;"></td>
                    <td style="border-right: 1.5pt solid #000; border-bottom: 1.5pt solid #000;"></td>
                </tr>
'''

# Close Page 2 table and add Page 2 footer (Row 95)
p2_table += """            </tbody>
        </table>

        <!-- Footer Halaman 2 (Row 95) -->
        <table class="excel-table" style="margin-top: 4px;">
""" + colgroup_html + """            <tbody>
                <tr style="height: 15.5pt;">
                    <td style="border: none;"></td>
                    <td colspan="15" style="border: none; font-size: 11pt;">POM-06.02/CFM.01/SOP.01/F.01B.13</td>
                    <td colspan="4" style="border: none; font-size: 11pt; text-align: right;">Lembar 2 dari 2 halaman</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
"""

full_content = php_header + p1_table + p2_table

target_path = r'd:\xampp\htdocs\limsku\application\views\pengujian\cetak_lcp_kualitatif.php'
with open(target_path, 'w', encoding='utf-8') as f:
    f.write(full_content)

print(f"Successfully generated view: {target_path} (length: {len(full_content)} chars)")
