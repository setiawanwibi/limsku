# -*- coding: utf-8 -*-
import sys

filepath = 'application/views/pengujian/proses_sesi.php'
with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

is_crlf = '\r\n' in content
newline = '\r\n' if is_crlf else '\n'

norm_content = content.replace('\r\n', '\n')

start_marker = "        function hitungSpektrofotometri() {"
end_marker = "        // =========================================================================\n        // Auto-Calculator Engine untuk Formulir GC-MS (KIM-09)"

if start_marker not in norm_content:
    print("Start marker hitungSpektrofotometri not found!")
    sys.exit(1)
if end_marker not in norm_content:
    print("End marker GCMS not found!")
    sys.exit(1)

pre_code = norm_content[:norm_content.find(start_marker)]
post_code = norm_content[norm_content.find(start_marker):]

hplc_start_marker = "        // Auto-Calculator Engine untuk Formulir HPLC / KCKT (KIM-08)"
if hplc_start_marker not in post_code:
    print("HPLC start marker not found in post_code!")
    sys.exit(1)

spektro_part = post_code[:post_code.find(hplc_start_marker)]
hplc_part = post_code[post_code.find(hplc_start_marker):]

gcms_marker = "        // Auto-Calculator Engine untuk Formulir GC-MS (KIM-09)"
if gcms_marker not in hplc_part:
    print("GCMS marker not found in hplc_part!")
    sys.exit(1)

gcms_post_code = hplc_part[hplc_part.find(gcms_marker):]

new_spektro_and_helpers = '''        function hitungSpektrofotometri() {
            var panes = document.querySelectorAll('.form-tab-pane-uji');
            panes.forEach(function(pane) {
                var kodeTpl = (pane.getAttribute('data-kode-template') || '').toUpperCase();
                var namaTpl = (pane.getAttribute('data-nama-template') || '').toLowerCase();
                var paramTpl = (pane.getAttribute('data-parameter') || '').toLowerCase();
                if (kodeTpl === 'KIM-05' || namaTpl.indexOf('spektro') !== -1 || paramTpl.indexOf('spektro') !== -1 || pane.querySelector('input[name*="[tabel_preparasi]"]')) {
                    hitungSpektrofotometriPane(pane);
                }
            });
        }

        function hitungSpektrofotometriPane(pane) {
            if (!pane) return;
            var kbInput = pane.querySelector('input[name*="[kadar_baku_persen]"]'); // M25
            var brInput = pane.querySelector('input[name*="[bobot_rata_rata_tablet]"], input[name*="[bobot_rata_tablet]"]'); // K46
            var keInput = pane.querySelector('input[name*="[klaim_etiket]"]'); // K47
            var bmInput = pane.querySelector('input[name*="[faktor_konversi_bm]"]');
            var metodeKadar = getMetodeKadar(pane);
            var volEtiketInput = pane.querySelector('input[name*="[volume_takaran_etiket]"]');
            var Vetiket = (volEtiketInput && parseFloat(volEtiketInput.value) > 0) ? parseFloat(volEtiketInput.value) : 5.0;

            var Kb = kbInput ? parseFloat(kbInput.value) || 0 : 0;
            var Br = brInput ? parseFloat(brInput.value) || 0 : 0;
            var Ke = keInput ? parseFloat(keInput.value) || 0 : 0;
            var bmFactor = evalMathExpression(bmInput ? bmInput.value : 1.0);
            updateBMPreviewInContainer(pane);

            // Baris 0: Baku | Baris 1: Uji 1 | Baris 2: Uji 2
            var wadahZatBaku = pane.querySelector('input[name*="[tabel_preparasi][0][wadah_zat]"]');
            var wadahSisaBaku = pane.querySelector('input[name*="[tabel_preparasi][0][wadah_sisa]"]');
            var bobotZatBakuEl = pane.querySelector('input[name*="[tabel_preparasi][0][bobot_zat]"]');
            var fbEl = pane.querySelector('input[name*="[tabel_preparasi][0][faktor_pengenceran]"]');
            var cBakuEl = pane.querySelector('input[name*="[tabel_preparasi][0][konsentrasi]"]');
            var abEl = pane.querySelector('input[name*="[tabel_preparasi][0][serapan]"]');

            var wadahZatU1 = pane.querySelector('input[name*="[tabel_preparasi][1][wadah_zat]"]');
            var wadahSisaU1 = pane.querySelector('input[name*="[tabel_preparasi][1][wadah_sisa]"]');
            var bobotZatU1El = pane.querySelector('input[name*="[tabel_preparasi][1][bobot_zat]"]');
            var fu1El = pane.querySelector('input[name*="[tabel_preparasi][1][faktor_pengenceran]"]');
            var cU1El = pane.querySelector('input[name*="[tabel_preparasi][1][konsentrasi]"]');
            var au1El = pane.querySelector('input[name*="[tabel_preparasi][1][serapan]"]');

            var wadahZatU2 = pane.querySelector('input[name*="[tabel_preparasi][2][wadah_zat]"]');
            var wadahSisaU2 = pane.querySelector('input[name*="[tabel_preparasi][2][wadah_sisa]"]');
            var bobotZatU2El = pane.querySelector('input[name*="[tabel_preparasi][2][bobot_zat]"]');
            var fu2El = pane.querySelector('input[name*="[tabel_preparasi][2][faktor_pengenceran]"]');
            var cU2El = pane.querySelector('input[name*="[tabel_preparasi][2][konsentrasi]"]');
            var au2El = pane.querySelector('input[name*="[tabel_preparasi][2][serapan]"]');

            if (!kbInput && !abEl) return;

            // 1. Hitung bobot zat = wadah+zat - wadah+sisa (presisi penuh tanpa pembulatan paksa)
            var Bb = 0, Bu1 = 0, Bu2 = 0;
            if (wadahZatBaku && wadahSisaBaku) {
                var wzb = parseFloat(wadahZatBaku.value) || 0;
                var wsb = parseFloat(wadahSisaBaku.value) || 0;
                if (wzb > 0 && wsb > 0 && wzb >= wsb) {
                    Bb = wzb - wsb;
                    if (bobotZatBakuEl && !bobotZatBakuEl.dataset.manualEdited) {
                        bobotZatBakuEl.value = parseFloat(Bb.toPrecision(12)).toString();
                    }
                } else if (bobotZatBakuEl) {
                    Bb = parseFloat(bobotZatBakuEl.value) || 0;
                }
            } else if (bobotZatBakuEl) {
                Bb = parseFloat(bobotZatBakuEl.value) || 0;
            }

            if (metodeKadar === 'tablet_cemplung') {
                Bu1 = 1.0;
                Bu2 = 1.0;
                if (bobotZatU1El && (!bobotZatU1El.value || bobotZatU1El.value === '0')) bobotZatU1El.value = "1";
                if (bobotZatU2El && (!bobotZatU2El.value || bobotZatU2El.value === '0')) bobotZatU2El.value = "1";
            } else if (metodeKadar === 'sirup') {
                if (wadahZatU1 && parseFloat(wadahZatU1.value) > 0) {
                    Bu1 = parseFloat(wadahZatU1.value);
                    if (bobotZatU1El && !bobotZatU1El.dataset.manualEdited) bobotZatU1El.value = wadahZatU1.value;
                } else if (bobotZatU1El) {
                    Bu1 = parseFloat(bobotZatU1El.value) || 0;
                }
                if (wadahZatU2 && parseFloat(wadahZatU2.value) > 0) {
                    Bu2 = parseFloat(wadahZatU2.value);
                    if (bobotZatU2El && !bobotZatU2El.dataset.manualEdited) bobotZatU2El.value = wadahZatU2.value;
                } else if (bobotZatU2El) {
                    Bu2 = parseFloat(bobotZatU2El.value) || 0;
                }
            } else {
                if (wadahZatU1 && wadahSisaU1) {
                    var wzu1 = parseFloat(wadahZatU1.value) || 0;
                    var wsu1 = parseFloat(wadahSisaU1.value) || 0;
                    if (wzu1 > 0 && wsu1 > 0 && wzu1 >= wsu1) {
                        Bu1 = wzu1 - wsu1;
                        if (bobotZatU1El && !bobotZatU1El.dataset.manualEdited) {
                            bobotZatU1El.value = parseFloat(Bu1.toPrecision(12)).toString();
                        }
                    } else if (bobotZatU1El) {
                        Bu1 = parseFloat(bobotZatU1El.value) || 0;
                    }
                } else if (bobotZatU1El) {
                    Bu1 = parseFloat(bobotZatU1El.value) || 0;
                }

                if (wadahZatU2 && wadahSisaU2) {
                    var wzu2 = parseFloat(wadahZatU2.value) || 0;
                    var wsu2 = parseFloat(wadahSisaU2.value) || 0;
                    if (wzu2 > 0 && wsu2 > 0 && wzu2 >= wsu2) {
                        Bu2 = wzu2 - wsu2;
                        if (bobotZatU2El && !bobotZatU2El.dataset.manualEdited) {
                            bobotZatU2El.value = parseFloat(Bu2.toPrecision(12)).toString();
                        }
                    } else if (bobotZatU2El) {
                        Bu2 = parseFloat(bobotZatU2El.value) || 0;
                    }
                } else if (bobotZatU2El) {
                    Bu2 = parseFloat(bobotZatU2El.value) || 0;
                }
            }

            var Ab = abEl ? parseFloat(abEl.value) || 0 : 0;
            var Fb = fbEl ? parseFloat(fbEl.value) || 1 : 1;
            var Au1 = au1El ? parseFloat(au1El.value) || 0 : 0;
            var Fu1 = fu1El ? parseFloat(fu1El.value) || 1 : 1;
            var Au2 = au2El ? parseFloat(au2El.value) || 0 : 0;
            var Fu2 = fu2El ? parseFloat(fu2El.value) || 1 : 1;

            // 1b. Hitung otomatis Konsentrasi (mcg/mL = mg * 1000 / FP) HANYA jika belum dimodifikasi manual
            if (cBakuEl && Bb > 0 && Fb > 0 && !cBakuEl.dataset.manualEdited && cBakuEl.value === '') {
                cBakuEl.value = parseFloat(((Bb * 1000) / Fb).toPrecision(8)).toString();
            }
            if (cU1El && Bu1 > 0 && Fu1 > 0 && !cU1El.dataset.manualEdited && cU1El.value === '') {
                cU1El.value = parseFloat(((Bu1 * 1000) / Fu1).toPrecision(8)).toString();
            }
            if (cU2El && Bu2 > 0 && Fu2 > 0 && !cU2El.dataset.manualEdited && cU2El.value === '') {
                cU2El.value = parseFloat(((Bu2 * 1000) / Fu2).toPrecision(8)).toString();
            }

            // 2. Hitung Kadar Uji 1 & 2 (%) berdasarkan METODE PERHITUNGAN
            var kadarU1 = 0, kadarU2 = 0;
            var faktorKb = (Kb > 1) ? (Kb / 100) : (Kb > 0 ? Kb : 1.0);

            if (Ab > 0 && Ke > 0 && Bb > 0) {
                if (metodeKadar === 'tablet_cemplung') {
                    if (Au1 > 0) kadarU1 = (Au1 / Ab) * Bb * (Fu1 / Fb) * (1.0 / Ke) * faktorKb * bmFactor * 100;
                    if (Au2 > 0) kadarU2 = (Au2 / Ab) * Bb * (Fu2 / Fb) * (1.0 / Ke) * faktorKb * bmFactor * 100;
                } else if (metodeKadar === 'sirup') {
                    if (Bu1 > 0 && Au1 > 0) kadarU1 = (Au1 / Ab) * (Bb / Bu1) * (Fu1 / Fb) * (Vetiket / Ke) * faktorKb * bmFactor * 100;
                    if (Bu2 > 0 && Au2 > 0) kadarU2 = (Au2 / Ab) * (Bb / Bu2) * (Fu2 / Fb) * (Vetiket / Ke) * faktorKb * bmFactor * 100;
                } else {
                    if (Bu1 > 0 && Au1 > 0 && Br > 0) kadarU1 = (Au1 / Ab) * (Bb / Bu1) * (Fu1 / Fb) * (Br / Ke) * faktorKb * bmFactor * 100;
                    if (Bu2 > 0 && Au2 > 0 && Br > 0) kadarU2 = (Au2 / Ab) * (Bb / Bu2) * (Fu2 / Fb) * (Br / Ke) * faktorKb * bmFactor * 100;
                }
            }

            var kadarU1Input = pane.querySelector('input[name*="[kadar_uji_1]"]');
            var kadarU2Input = pane.querySelector('input[name*="[kadar_uji_2]"]');
            var kadarRataPersenInput = pane.querySelector('input[name*="[kadar_rata_rata_persen]"]');
            var kadarRataMgInput = pane.querySelector('input[name*="[kadar_rata_rata_mg]"]');
            var rpdInput = pane.querySelector('input[name*="[rpd_hitung]"], input[name*="[nilai_rpd]"]');
            var horwitzInput = pane.querySelector('input[name*="[syarat_rpd_horwitz]"], input[name*="[syarat_horwitz]"]');

            if (kadarU1Input) kadarU1Input.value = (kadarU1 > 0) ? kadarU1.toFixed(2) : '';
            if (kadarU2Input) kadarU2Input.value = (kadarU2 > 0) ? kadarU2.toFixed(2) : '';

            if (kadarU1 > 0 || kadarU2 > 0) {
                var rataPersen = (kadarU1 > 0 && kadarU2 > 0) ? (kadarU1 + kadarU2) / 2 : (kadarU1 || kadarU2);
                var rataMg = Ke > 0 ? (rataPersen * Ke) / 100 : 0;

                if (kadarRataPersenInput) kadarRataPersenInput.value = rataPersen.toFixed(2);
                if (kadarRataMgInput) kadarRataMgInput.value = rataMg.toFixed(2);

                if (kadarU1 > 0 && kadarU2 > 0 && rataPersen > 0) {
                    var rpd = (Math.abs(kadarU1 - kadarU2) / rataPersen) * 100;
                    if (rpdInput) rpdInput.value = rpd.toFixed(2);

                    var C_fraksi = rataPersen / 100;
                    if (C_fraksi > 0) {
                        var horw = 0.5 * Math.pow(2, (1 - 0.5 * (Math.log10(C_fraksi))));
                        if (horwitzInput) horwitzInput.value = horw.toFixed(2);
                    }
                }

                evaluasiKesimpulanPane(pane);
            }
        }

        // Listener untuk KIM-05 Spektrofotometri & Multi-Metode Kadar
        document.addEventListener('input', function(e) {
            if (e.target && e.target.matches('input[name*="[tabel_preparasi]"], input[name*="[kadar_baku_persen]"], input[name*="[bobot_rata_rata_tablet]"], input[name*="[klaim_etiket]"], input[name*="[faktor_konversi_bm]"], input[name*="[volume_takaran_etiket]"]')) {
                hitungSpektrofotometri();
            }
        });

        document.addEventListener('change', function(e) {
            if (e.target && e.target.matches('select[name*="[metode_perhitungan_kadar]"]')) {
                var pane = e.target.closest('.form-tab-pane-uji');
                if (pane) {
                    updateMetodeKadarUI(pane);
                    hitungSpektrofotometri();
                    hitungHPLC();
                    evaluasiKesimpulanPane(pane);
                }
            }
        });

        // =========================================================================
        // Evaluator Ekspresi Matematika Aman untuk Faktor Koreksi BM (BM1 / BM2)
        // Menerima angka biasa (1.0, 0.896, 0,896) atau rumus pembagian (425.9/41, (314.4/350.87))
        // =========================================================================
        function evalMathExpression(expr) {
            if (expr === null || expr === undefined) return 1.0;
            var str = String(expr).trim();
            if (str === '') return 1.0;

            str = str.replace(/,/g, '.');

            if (!isNaN(str) && !isNaN(parseFloat(str))) {
                var num = parseFloat(str);
                return num > 0 ? num : 1.0;
            }

            if (!/^[0-9\.\s\(\)\+\-\*\/]+$/.test(str)) {
                return 1.0;
            }

            try {
                var res = Function('"use strict"; return (' + str + ');')();
                if (typeof res === 'number' && !isNaN(res) && isFinite(res) && res > 0) {
                    return res;
                }
            } catch (e) {}
            return 1.0;
        }

        // Helper update badge live preview hasil hitung BM di formulir
        function updateBMPreviewInContainer(container) {
            if (!container) container = document;
            var bmInputs = container.querySelectorAll('.input-faktor-bm, input[name*="[faktor_konversi_bm]"]');
            bmInputs.forEach(function(bmInput) {
                var bmCol = bmInput.closest('.col-md-6') || bmInput.closest('.col-12') || bmInput.parentElement;
                if (!bmCol) return;
                var previewEl = bmCol.querySelector('.bm-calc-preview');
                if (!previewEl) return;
                var evalValEl = previewEl.querySelector('.bm-eval-val');
                var bmRaw = String(bmInput.value || '').trim();
                var bmFactor = evalMathExpression(bmRaw);
                var hasFormula = bmRaw !== '' && (bmRaw.indexOf('/') !== -1 || bmRaw.indexOf('*') !== -1 || bmRaw.indexOf('(') !== -1 || isNaN(bmRaw.replace(/,/g, '.')));
                if (hasFormula || (bmRaw !== '' && parseFloat(bmRaw) !== 1.0)) {
                    if (evalValEl) evalValEl.textContent = parseFloat(bmFactor.toPrecision(8)).toString();
                    previewEl.style.display = 'block';
                } else {
                    previewEl.style.display = 'none';
                }
            });
        }

        // Listener universal input faktor koreksi BM
        document.addEventListener('input', function(e) {
            if (e.target && e.target.matches('.input-faktor-bm, input[name*="[faktor_konversi_bm]"]')) {
                var pane = e.target.closest('.form-tab-pane-uji') || document;
                updateBMPreviewInContainer(pane);
                hitungHPLC();
                hitungSpektrofotometri();
                hitungDisolusi();
            }
        });

'''

# In disolusi part of post_code, replace update preview if needed, or keep disolusi intact
# Now check disolusi part inside spektro_part:
# Disolusi is in spektro_part! Let's check where Disolusi starts in spektro_part:
disolusi_marker = "        // Auto-Calculator Engine untuk Formulir Disolusi (KIM-06)"
if disolusi_marker not in spektro_part:
    print("Disolusi marker not found in spektro_part!")
    sys.exit(1)

disolusi_code = spektro_part[spektro_part.find(disolusi_marker):]

new_hplc_section = '''        // Auto-Calculator Engine untuk Formulir HPLC / KCKT (KIM-08)
        // Mendukung 3 Metode: Tablet BR, Tablet Cemplung, dan Sirup / Cairan
        // Rumus Resmi: Kadar = (Lu/Lb) * [Faktor Perkalian Sediaan] * Kb * [Faktor BM] * 100%
        // RPD = |K1 - K2| / Kadar * 100%, Horwitz = 0.5 * 2^(1 - 0.5*log10(C))
        // =========================================================================
        function hitungHPLC() {
            var panes = document.querySelectorAll('.form-tab-pane-uji');
            panes.forEach(function(pane) {
                var kodeTpl = (pane.getAttribute('data-kode-template') || '').toUpperCase();
                var namaTpl = (pane.getAttribute('data-nama-template') || '').toLowerCase();
                var paramTpl = (pane.getAttribute('data-parameter') || '').toLowerCase();
                if (kodeTpl === 'KIM-08' || namaTpl.indexOf('kckt') !== -1 || namaTpl.indexOf('hplc') !== -1 || paramTpl.indexOf('kadar') !== -1 || pane.querySelector('table input[name*="[respon_puncak]"]')) {
                    hitungHPLCPane(pane);
                }
            });
        }

        function hitungHPLCPane(pane) {
            if (!pane) return;

            // 1. Tipe Metode Kadar (Tablet BR / Tablet Cemplung / Sirup)
            var metodeKadar = getMetodeKadar(pane);
            var volEtiketInput = pane.querySelector('input[name*="[volume_takaran_etiket]"]');
            var Vetiket = (volEtiketInput && parseFloat(volEtiketInput.value) > 0) ? parseFloat(volEtiketInput.value) : 5.0;

            // 2. Koreksi Kadar Kemurnian Baku
            var bakuKadarInput = pane.querySelector('input[name*="[baku_kadar]"]');
            var bakuSpInput = pane.querySelector('input[name*="[baku_sp]"]');
            var bakuTerkoreksiInput = pane.querySelector('input[name*="[baku_kadar_terkoreksi]"]');

            var Kb = 100.0;
            if (bakuKadarInput && parseFloat(bakuKadarInput.value) > 0) {
                var rawKadar = parseFloat(bakuKadarInput.value) || 0;
                var sp = parseFloat(bakuSpInput ? bakuSpInput.value : 0) || 0;
                Kb = (100 - sp) * (rawKadar / 100);
                if (bakuTerkoreksiInput) {
                    bakuTerkoreksiInput.value = parseFloat(Kb.toPrecision(8)).toString();
                }
            } else if (bakuTerkoreksiInput && parseFloat(bakuTerkoreksiInput.value) > 0) {
                Kb = parseFloat(bakuTerkoreksiInput.value);
            }

            // 3. Faktor Koreksi BM / BR (BM1 / BM2)
            var bmInput = pane.querySelector('input[name*="[faktor_konversi_bm]"]');
            var bmFactor = evalMathExpression(bmInput ? bmInput.value : 1.0);
            updateBMPreviewInContainer(pane);

            // 4. Hitung Bobot Bersih Tiap Baris Tabel HPLC
            var rows = pane.querySelectorAll('table tbody tr');
            rows.forEach(function(tr) {
                hitungBarisHPLC(tr, metodeKadar);
            });

            // 5. Parameter Tambahan: Br (Bobot rata tablet) & Ke (Klaim etiket)
            var brInput = pane.querySelector('input[name*="[bobot_rata_tablet]"], input[name*="[bobot_rata_rata_tablet]"]');
            var keInput = pane.querySelector('input[name*="[klaim_etiket]"]');
            var Br = parseFloat(brInput ? brInput.value : 0) || 0;
            var Ke = parseFloat(keInput ? keInput.value : 0) || 0;

            // 6. Ambil Data Baku Pembanding & Zat Uji Replikasi 1 & 2 secara dinamis
            var bakuRow = null;
            var uji1Row = null;
            var uji2Row = null;

            rows.forEach(function(tr) {
                var katInput = tr.querySelector('input[name*="[kategori]"], td:first-child input, td:first-child');
                var katText = (katInput ? (katInput.value || katInput.textContent || '') : '').toLowerCase();
                if (katText.indexOf('baku pembanding') !== -1 || (katText.indexOf('baku') !== -1 && katText.indexOf('internal') === -1)) {
                    if (!bakuRow) bakuRow = tr;
                } else if (katText.indexOf('uji 1') !== -1 || katText.indexOf('replikasi 1') !== -1) {
                    uji1Row = tr;
                } else if (katText.indexOf('uji 2') !== -1 || katText.indexOf('replikasi 2') !== -1) {
                    uji2Row = tr;
                }
            });

            // Fallback berdasarkan index jika teks kategori tidak cocok
            if (!bakuRow && rows.length > 0) bakuRow = rows[0];
            if (!uji1Row && rows.length > 2) uji1Row = rows[2];
            else if (!uji1Row && rows.length > 1) uji1Row = rows[1];
            if (!uji2Row && rows.length > 3) uji2Row = rows[3];
            else if (!uji2Row && rows.length > 2) uji2Row = rows[2];

            var Bb = 0, Fb = 1, Lb = 0;
            if (bakuRow) {
                var bbEl = bakuRow.querySelector('input[name*="[bobot_bersih]"]');
                var fbEl = bakuRow.querySelector('input[name*="[faktor_pengenceran]"]');
                var lbEl = bakuRow.querySelector('input[name*="[respon_puncak]"]');
                Bb = parseFloat(bbEl ? bbEl.value : 0) || 0;
                Fb = parseFloat(fbEl ? fbEl.value : 1) || 1;
                Lb = parseFloat(lbEl ? lbEl.value : 0) || 0;
            }

            // 7. Hitung Kadar Uji 1 dan Uji 2
            var k1 = hitungKadarSatuBaris(uji1Row, Lb, Bb, Fb, Br, Ke, Kb, bmFactor, metodeKadar, Vetiket);
            var k2 = hitungKadarSatuBaris(uji2Row, Lb, Bb, Fb, Br, Ke, Kb, bmFactor, metodeKadar, Vetiket);

            var k1Input = pane.querySelector('input[name*="[kadar_replikasi_1]"], input[name*="[kadar_uji_1]"]');
            var k2Input = pane.querySelector('input[name*="[kadar_replikasi_2]"], input[name*="[kadar_uji_2]"]');

            if (k1Input) k1Input.value = (k1 > 0) ? k1.toFixed(2) : '';
            if (k2Input) k2Input.value = (k2 > 0) ? k2.toFixed(2) : '';

            // 8. Rata-rata Kadar, RPD & Horwitz
            var rataPersenInput = pane.querySelector('input[name*="[kadar_rata_persen]"], input[name*="[kadar_rata_rata_persen]"]');
            var rataMgInput = pane.querySelector('input[name*="[kadar_rata_mg]"], input[name*="[kadar_rata_rata_mg]"]');
            var rpdInput = pane.querySelector('input[name*="[nilai_rpd]"], input[name*="[rpd_hitung]"]');
            var horwitzInput = pane.querySelector('input[name*="[syarat_horwitz]"], input[name*="[syarat_rpd_horwitz]"]');

            if (k1 > 0 || k2 > 0) {
                var rataPersen = (k1 > 0 && k2 > 0) ? (k1 + k2) / 2 : (k1 || k2);
                var rataMg = Ke > 0 ? (rataPersen / 100) * Ke : 0;
                var rpd = (k1 > 0 && k2 > 0 && rataPersen > 0) ? (Math.abs(k1 - k2) / rataPersen) * 100 : 0;

                var C = rataPersen / 100;
                var horwitz = 0;
                if (C > 0) {
                    horwitz = 0.5 * Math.pow(2, (1 - (0.5 * (Math.log(C) / Math.LN10))));
                }

                if (rataPersenInput) rataPersenInput.value = rataPersen.toFixed(2);
                if (rataMgInput) rataMgInput.value = rataMg.toFixed(2);
                if (rpdInput) rpdInput.value = (rpd > 0) ? rpd.toFixed(2) : '0.00';
                if (horwitzInput) horwitzInput.value = (horwitz > 0) ? horwitz.toFixed(2) : '0.00';

                evaluasiKesimpulanPane(pane);
            }
        }

        function hitungBarisHPLC(tr, metodeKadar) {
            if (!tr) return;
            var wZatInput = tr.querySelector('input[name*="[wadah_zat]"]');
            var wSisaInput = tr.querySelector('input[name*="[wadah_sisa]"]');
            var bBersihInput = tr.querySelector('input[name*="[bobot_bersih]"]');
            var fpInput = tr.querySelector('input[name*="[faktor_pengenceran]"]');
            var cInput = tr.querySelector('input[name*="[konsentrasi]"]');

            var katInput = tr.querySelector('input[name*="[kategori]"], td:first-child input, td:first-child');
            var katText = (katInput ? (katInput.value || katInput.textContent || '') : '').toLowerCase();
            var isUjiRow = (katText.indexOf('uji') !== -1 || katText.indexOf('sampel') !== -1);

            var mode = metodeKadar || 'tablet_br';

            // Penanganan khusus untuk Tablet Cemplung dan Sirup pada baris Zat Uji:
            if (isUjiRow && mode === 'tablet_cemplung') {
                if (wZatInput) wZatInput.placeholder = "1 Tab Utuh";
                if (wSisaInput) wSisaInput.placeholder = "(Digabung)";
                if (bBersihInput && (!bBersihInput.value || bBersihInput.value === '0')) {
                    bBersihInput.value = "1";
                }
            } else if (isUjiRow && mode === 'sirup') {
                if (wSisaInput) wSisaInput.placeholder = "(Digabung)";
                if (wZatInput && parseFloat(wZatInput.value) > 0) {
                    if (bBersihInput && !bBersihInput.dataset.manualEdited) {
                        bBersihInput.value = wZatInput.value;
                    }
                }
            } else {
                // Mode standar / Baku Pembanding: Wadah + Zat - Wadah + Sisa (presisi sebenarnya tanpa pembulatan paksa)
                if (wZatInput && wSisaInput && bBersihInput) {
                    var wZat = parseFloat(wZatInput.value) || 0;
                    var wSisa = parseFloat(wSisaInput.value) || 0;
                    if (wZat > 0 && wSisa > 0 && wZat >= wSisa) {
                        var diff = wZat - wSisa;
                        var cleanVal = parseFloat(diff.toPrecision(12)).toString();
                        if (!bBersihInput.dataset.manualEdited || bBersihInput.value === '') {
                            bBersihInput.value = cleanVal;
                        }
                    }
                }
            }

            var bBersih = parseFloat(bBersihInput ? bBersihInput.value : 0) || 0;

            // Hitung dan tampilkan nilai konsentrasi secara otomatis HANYA jika belum diubah manual oleh pengguna
            if (cInput && fpInput && bBersih > 0) {
                var fpVal = parseFloat(fpInput.value) || 0;
                if (fpVal > 0 && !cInput.dataset.manualEdited && cInput.value === '') {
                    var bMg = (bBersih < 2.0) ? (bBersih * 1000) : bBersih;
                    var cVal = bMg / fpVal; // mg/mL
                    var cFormatted = (cVal >= 0.0001) ? parseFloat(cVal.toPrecision(8)).toString() : cVal.toPrecision(4);
                    cInput.value = cFormatted;
                }
            }
        }

        function hitungKadarSatuBaris(tr, Lb, Bb, Fb, Br, Ke, Kb, bmFactor, metodeKadar, Vetiket) {
            if (!tr || Lb <= 0 || Bb <= 0 || Fb <= 0 || Ke <= 0 || Kb <= 0) return 0;
            var fBM = (typeof bmFactor === 'number' && bmFactor > 0) ? bmFactor : 1.0;
            var mode = metodeKadar || 'tablet_br';
            var V_et = (typeof Vetiket === 'number' && Vetiket > 0) ? Vetiket : 5.0;

            var buEl = tr.querySelector('input[name*="[bobot_bersih]"]');
            var fuEl = tr.querySelector('input[name*="[faktor_pengenceran]"]');
            var luEl = tr.querySelector('input[name*="[respon_puncak]"], input[name*="[serapan]"]');

            var Bu = parseFloat(buEl ? buEl.value : 0) || 0;
            var Fu = parseFloat(fuEl ? fuEl.value : 1) || 1;
            var Lu = parseFloat(luEl ? luEl.value : 0) || 0;

            if (Lu <= 0) return 0;

            // Pastikan satuan bobot baku dalam mg jika penimbangan dalam gram (< 2.0 g)
            var Bb_mg = (Bb < 2.0) ? (Bb * 1000) : Bb;

            var kadar = 0;
            if (mode === 'tablet_cemplung') {
                // Rumus 2: Tablet Cemplung (1 tablet utuh direct dissolution)
                // Kadar = (Lu / Lb) * Bb_mg * (Fu / Fb) * (1 / Ke) * (Kb / 100) * FactorBM * 100%
                kadar = (Lu / Lb) * Bb_mg * (Fu / Fb) * (1.0 / Ke) * (Kb / 100) * fBM * 100;
            } else if (mode === 'sirup') {
                // Rumus 3: Sirup / Suspensi / Cairan Oral (Volume Vu dalam mL)
                if (Bu <= 0) return 0;
                kadar = (Lu / Lb) * (Bb_mg / Bu) * (Fu / Fb) * (V_et / Ke) * (Kb / 100) * fBM * 100;
            } else {
                // Rumus 1: Tablet / Kapsul BR (Metode 20 Tablet Digerus / Default)
                if (Bu <= 0 || Br <= 0) return 0;
                var Bu_mg = (Bu < 2.0) ? (Bu * 1000) : Bu;
                kadar = (Lu / Lb) * (Bb_mg / Bu_mg) * (Fu / Fb) * (Br / Ke) * (Kb / 100) * fBM * 100;
            }

            return isNaN(kadar) ? 0 : kadar;
        }

        // Listener reaktif HPLC input dan perubahan manual bobot bersih / konsentrasi
        document.addEventListener('input', function(e) {
            if (!e.target) return;
            if (e.target.matches('input[name*="[konsentrasi]"]')) {
                e.target.dataset.manualEdited = "true";
            }
            if (e.target.matches('input[name*="[bobot_bersih]"]')) {
                e.target.dataset.manualEdited = "true";
                hitungHPLC();
                hitungSpektrofotometri();
            }
            if (e.target.matches('input[name*="[baku_kadar]"], input[name*="[baku_sp]"], input[name*="[tabel_hplc]"], input[name*="[bobot_rata_tablet]"], input[name*="[klaim_etiket]"], input[name*="[faktor_konversi_bm]"], input[name*="[volume_takaran_etiket]"], input[name*="[respon_puncak]"], input[name*="[faktor_pengenceran]"]')) {
                hitungHPLC();
            }
        });

        document.addEventListener('change', function(e) {
            if (e.target && e.target.matches('input[name*="[tabel_hplc]"], select[name*="[metode_perhitungan_kadar]"]')) {
                hitungHPLC();
            }
        });

'''

final_content = pre_code + new_spektro_and_helpers + disolusi_code + new_hplc_section + gcms_post_code

if is_crlf:
    out_content = final_content.replace('\n', '\r\n')
else:
    out_content = final_content

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(out_content)

print("Full proses_sesi.php patch applied successfully!")

