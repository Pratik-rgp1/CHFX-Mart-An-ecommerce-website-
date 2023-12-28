<?php 
$conn = oci_connect('chfxmart', 'Pratik#123', '//localhost/xe'); if (!$conn) {
    $m = oci_error();
    echo $m['message'], "\n";
    exit; } else {
    print ""; 
} 

