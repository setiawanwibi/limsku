import re

with open('application/views/pengujian/proses_sesi.php', 'r', encoding='utf-8') as f:
    ps = f.read()

with open('application/views/pengujian/cetak_lcp_kckt.php', 'r', encoding='utf-8') as f:
    ck = f.read()

assert 'hplcInput.closest(\'table\')' in ps, "HPLC table scoping check failed"
assert 'cInput.value === \'\'' not in ps, "konsentrasi value empty check should be removed"
assert 'toPrecision(12)' in ps, "unrounded toPrecision(12) check failed"
assert 'toFixed(2)' not in ps.split('function hitungHPLCPane')[1].split('function hitungBarisHPLC')[0], "toFixed(2) found in hitungHPLCPane!"
assert 'toFixed(2)' not in ps.split('function hitungSpektrofotometri')[1].split('function evalMathExpression')[0], "toFixed(2) found in hitungSpektrofotometri!"
assert 'targetName.indexOf(\'[wadah_zat]\')' in ps, "Live input listener for wadah_zat missing"
assert 'targetName.indexOf(\'[respon_puncak]\')' in ps, "Live input listener for respon_puncak missing"

assert "number_format((float)$r['wadah_zat'], 4" not in ck, "number_format 4 found for wadah_zat in cetak_lcp_kckt"
assert "number_format((float)$r['bobot_bersih'], 4" not in ck, "number_format 4 found for bobot_bersih in cetak_lcp_kckt"

print("ALL PENETAPAN KADAR LIVE & UNROUNDED CHECKS PASSED 100%!")

