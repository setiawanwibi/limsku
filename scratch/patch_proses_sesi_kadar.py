# -*- coding: utf-8 -*-
import sys

filepath = 'application/views/pengujian/proses_sesi.php'
with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Update updateMetodeKadarUI
old_ui = '''        function updateMetodeKadarUI(pane) {
            if (!pane) return;
            var mode = getMetodeKadar(pane);

            var wrapVol = pane.querySelector('[data-field-wrapper="volume_takaran_etiket"]');
            var wrapBr = pane.querySelector('[data-field-wrapper="bobot_rata_rata_tablet"], [data-field-wrapper="bobot_rata_tablet"]');
            var txtRumus = pane.querySelector('.text-rumus-kadar');
            var labelKe = pane.querySelector('[data-field-wrapper="klaim_etiket"] label');
            var labelKadarMg = pane.querySelector('[data-field-wrapper="kadar_rata_rata_mg"] label, [data-field-wrapper="kadar_rata_mg"] label');

            var volInp = pane.querySelector('input[name*="[volume_takaran_etiket]"]');
            var vEt = (volInp && parseFloat(volInp.value) > 0) ? volInp.value : '5.0';

            if (mode === 'tablet_cemplung') {
                if (wrapVol) wrapVol.style.display = 'none';
                if (wrapBr) wrapBr.style.display = 'none';
                if (txtRumus) {
                    txtRumus.innerHTML = '<strong>Rumus Aktif (Tablet Cemplung):</strong> Kadar (%) = (A<sub>u</sub> / A<sub>b</sub>) &times; B<sub>b</sub> &times; (F<sub>u</sub> / F<sub>b</sub>) &times; (1 / Ke) &times; K<sub>b</sub> &times; [f<sub>BM</sub>] &times; 100% <span class="badge bg-success ms-2">Hasil: mg/tablet (Direct Dissolution Utuh)</span>';
                }
                if (labelKe) labelKe.innerHTML = 'Klaim Etiket per Tablet (Ke) [mg]';
                if (labelKadarMg) labelKadarMg.innerHTML = 'Kadar Rata-rata per Tablet (mg/tab)';
            } else if (mode === 'sirup') {
                if (wrapVol) wrapVol.style.display = '';
                if (wrapBr) wrapBr.style.display = 'none';
                if (txtRumus) {
                    txtRumus.innerHTML = '<strong>Rumus Aktif (Sirup/Cairan):</strong> Kadar (%) = (A<sub>u</sub> / A<sub>b</sub>) &times; (B<sub>b</sub> / V<sub>u</sub>) &times; (F<sub>u</sub> / F<sub>b</sub>) &times; (' + vEt + ' / Ke) &times; K<sub>b</sub> &times; [f<sub>BM</sub>] &times; 100% <span class="badge bg-primary ms-2">Hasil: mg per ' + vEt + ' mL</span>';
                }
                if (labelKe) labelKe.innerHTML = 'Klaim Etiket per ' + vEt + ' mL (Ke) [mg]';
                if (labelKadarMg) labelKadarMg.innerHTML = 'Kadar Rata-rata (mg / ' + vEt + ' mL)';
            } else { // tablet_br
                if (wrapVol) wrapVol.style.display = 'none';
                if (wrapBr) wrapBr.style.display = '';
                if (txtRumus) {
                    txtRumus.innerHTML = '<strong>Rumus Aktif (Tablet BR):</strong> Kadar (%) = (A<sub>u</sub> / A<sub>b</sub>) &times; (B<sub>b</sub> / B<sub>u</sub>) &times; (F<sub>u</sub> / F<sub>b</sub>) &times; (Br / Ke) &times; K<sub>b</sub> &times; [f<sub>BM</sub>] &times; 100% <span class="badge bg-secondary ms-2">Hasil: mg/tablet (Metode 20 Tablet Digerus)</span>';
                }
                if (labelKe) labelKe.innerHTML = 'Klaim Etiket per Tablet (Ke) [mg]';
                if (labelKadarMg) labelKadarMg.innerHTML = 'Kadar Rata-rata per Tablet (mg/tab)';
            }
        }'''

new_ui = '''        function updateMetodeKadarUI(pane) {
            if (!pane) return;
            var mode = getMetodeKadar(pane);

            var wrapVol = pane.querySelector('[data-field-wrapper="volume_takaran_etiket"]');
            var wrapBr = pane.querySelector('[data-field-wrapper="bobot_rata_rata_tablet"], [data-field-wrapper="bobot_rata_tablet"]');
            var txtRumus = pane.querySelector('.text-rumus-kadar');
            var labelKe = pane.querySelector('[data-field-wrapper="klaim_etiket"] label');
            var labelKadarMg = pane.querySelector('[data-field-wrapper="kadar_rata_rata_mg"] label, [data-field-wrapper="kadar_rata_mg"] label');

            var volInp = pane.querySelector('input[name*="[volume_takaran_etiket]"]');
            var vEt = (volInp && parseFloat(volInp.value) > 0) ? volInp.value : '5.0';

            var tableRows = pane.querySelectorAll('table tbody tr');

            if (mode === 'tablet_cemplung') {
                if (wrapVol) wrapVol.style.display = 'none';
                if (wrapBr) wrapBr.style.display = 'none';
                if (txtRumus) {
                    txtRumus.innerHTML = '<strong>Rumus Aktif (Tablet Cemplung):</strong> Kadar (%) = (A<sub>u</sub> / A<sub>b</sub>) &times; B<sub>b</sub> &times; (F<sub>u</sub> / F<sub>b</sub>) &times; (1 / Ke) &times; K<sub>b</sub> &times; [f<sub>BM</sub>] &times; 100%<div class="mt-1 text-success fw-bold" style="font-size: 0.78rem;"><i class="bi bi-info-circle-fill me-1"></i>Keterangan Rumus Tablet Cemplung: Pada penetapan kadar tablet cemplung / effervescent / direct dissolution, pengujian menggunakan 1 tablet utuh yang dilarutkan langsung secara utuh tanpa penggerusan dan penimbangan serbuk sisa (bagian zat uji: wadah zat, wadah sisa, dan bobot uji digabung menjadi 1 tablet utuh).</div>';
                }
                if (labelKe) labelKe.innerHTML = 'Klaim Etiket per Tablet (Ke) [mg]';
                if (labelKadarMg) labelKadarMg.innerHTML = 'Kadar Rata-rata per Tablet (mg/tab)';

                tableRows.forEach(function(tr) {
                    var kat = (tr.querySelector('input[name*="[kategori]"], td:first-child input, td:first-child')?.value || tr.querySelector('td:first-child')?.textContent || '').toLowerCase();
                    if (kat.indexOf('uji') !== -1 || kat.indexOf('sampel') !== -1) {
                        var wZat = tr.querySelector('input[name*="[wadah_zat]"]');
                        var wSisa = tr.querySelector('input[name*="[wadah_sisa]"]');
                        var bBersih = tr.querySelector('input[name*="[bobot_bersih]"], input[name*="[bobot_zat]"]');
                        if (wZat) wZat.placeholder = "1 Tab Utuh";
                        if (wSisa) wSisa.placeholder = "(Digabung)";
                        if (bBersih) {
                            bBersih.placeholder = "1 Tab";
                            if (!bBersih.value || bBersih.value === '0') bBersih.value = "1";
                        }
                    }
                });
            } else if (mode === 'sirup') {
                if (wrapVol) wrapVol.style.display = '';
                if (wrapBr) wrapBr.style.display = 'none';
                if (txtRumus) {
                    txtRumus.innerHTML = '<strong>Rumus Aktif (Sirup/Cairan):</strong> Kadar (%) = (A<sub>u</sub> / A<sub>b</sub>) &times; (B<sub>b</sub> / V<sub>u</sub>) &times; (F<sub>u</sub> / F<sub>b</sub>) &times; (' + vEt + ' / Ke) &times; K<sub>b</sub> &times; [f<sub>BM</sub>] &times; 100%<div class="mt-1 text-primary fw-bold" style="font-size: 0.78rem;"><i class="bi bi-info-circle-fill me-1"></i>Keterangan Rumus Sirup: Pada penetapan kadar sirup / suspensi / cairan oral, pengujian menggunakan pemipetan volume sampel uji (Vu dalam mL) per takaran saji ' + vEt + ' mL (bagian wadah zat dan wadah sisa digabung menjadi satu bagian volume uji Vu).</div>';
                }
                if (labelKe) labelKe.innerHTML = 'Klaim Etiket per ' + vEt + ' mL (Ke) [mg]';
                if (labelKadarMg) labelKadarMg.innerHTML = 'Kadar Rata-rata (mg / ' + vEt + ' mL)';

                tableRows.forEach(function(tr) {
                    var kat = (tr.querySelector('input[name*="[kategori]"], td:first-child input, td:first-child')?.value || tr.querySelector('td:first-child')?.textContent || '').toLowerCase();
                    if (kat.indexOf('uji') !== -1 || kat.indexOf('sampel') !== -1) {
                        var wZat = tr.querySelector('input[name*="[wadah_zat]"]');
                        var wSisa = tr.querySelector('input[name*="[wadah_sisa]"]');
                        var bBersih = tr.querySelector('input[name*="[bobot_bersih]"], input[name*="[bobot_zat]"]');
                        if (wZat) wZat.placeholder = "Vol. Pipet Vu (mL)";
                        if (wSisa) wSisa.placeholder = "(Digabung)";
                        if (bBersih) bBersih.placeholder = "Vu (mL)";
                    }
                });
            } else { // tablet_br
                if (wrapVol) wrapVol.style.display = 'none';
                if (wrapBr) wrapBr.style.display = '';
                if (txtRumus) {
                    txtRumus.innerHTML = '<strong>Rumus Aktif (Tablet BR):</strong> Kadar (%) = (A<sub>u</sub> / A<sub>b</sub>) &times; (B<sub>b</sub> / B<sub>u</sub>) &times; (F<sub>u</sub> / F<sub>b</sub>) &times; (Br / Ke) &times; K<sub>b</sub> &times; [f<sub>BM</sub>] &times; 100%<div class="mt-1 text-secondary fw-bold" style="font-size: 0.78rem;"><i class="bi bi-info-circle-fill me-1"></i>Metode 20 Tablet Digerus: Penimbangan serbuk uji (Wadah+Zat - Wadah+Sisa) dikalikan Bobot Rata-rata 20 Tablet (Br).</div>';
                }
                if (labelKe) labelKe.innerHTML = 'Klaim Etiket per Tablet (Ke) [mg]';
                if (labelKadarMg) labelKadarMg.innerHTML = 'Kadar Rata-rata per Tablet (mg/tab)';

                tableRows.forEach(function(tr) {
                    var wZat = tr.querySelector('input[name*="[wadah_zat]"]');
                    var wSisa = tr.querySelector('input[name*="[wadah_sisa]"]');
                    var bBersih = tr.querySelector('input[name*="[bobot_bersih]"], input[name*="[bobot_zat]"]');
                    if (wZat) wZat.placeholder = "";
                    if (wSisa) wSisa.placeholder = "";
                    if (bBersih) bBersih.placeholder = "";
                });
            }
        }'''

# Normalize newlines for matching
is_crlf = '\r\n' in content
newline = '\r\n' if is_crlf else '\n'

norm_content = content.replace('\r\n', '\n')
norm_old_ui = old_ui.replace('\r\n', '\n')
norm_new_ui = new_ui.replace('\r\n', '\n')

if norm_old_ui in norm_content:
    norm_content = norm_content.replace(norm_old_ui, norm_new_ui)
    print("SUCCESS: updateMetodeKadarUI replaced.")
else:
    print("ERROR: old_ui not found in content!")
    sys.exit(1)

# Write back
if is_crlf:
    out_content = norm_content.replace('\n', '\r\n')
else:
    out_content = norm_content

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(out_content)

print("Saved processes_sesi.php successfully.")

