<?php
// Connecting, selecting database
$dbconn = pg_connect("host=192.168.0.1 dbname=fusionpbx user=fusionpbx password=password123")
    or die('Could not connect: ' . pg_last_error());
?>
