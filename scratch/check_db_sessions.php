<?php
require_once 'index.php';
$CI =& get_instance();
$CI->load->database();

// Check recent test_results or sessions
$query = $CI->db->query("
    SELECT tr.id, tr.session_id, tr.method_id, tr.template_id, 
           m.parameter_uji, m.nama_metode, t.nama_template, t.kode_template
    FROM test_results tr
    LEFT JOIN methods m ON m.id = tr.method_id
    LEFT JOIN form_templates t ON t.id = tr.template_id
    ORDER BY tr.id DESC LIMIT 10
");
$results = $query->result_array();
foreach ($results as $r) {
    echo "ID: " . $r['id'] . " | Session: " . $r['session_id'] . " | Param: " . $r['parameter_uji'] . " | Metode: " . $r['nama_metode'] . " | Template: " . $r['kode_template'] . " (" . $r['nama_template'] . ")\n";
}
