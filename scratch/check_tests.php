<?php
$m = new mysqli('localhost', 'root', '', 'limsku');
$r = $m->query('SELECT tr.id, tr.session_id, tr.template_id, tr.status, tr.kesimpulan, t.kode_template, t.nama_template FROM test_results tr LEFT JOIN form_templates t ON t.id = tr.template_id ORDER BY tr.id DESC LIMIT 10');
while($row = $r->fetch_assoc()) {
    echo json_encode($row) . PHP_EOL;
}
