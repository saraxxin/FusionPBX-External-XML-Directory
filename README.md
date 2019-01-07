# FusionPBX-External-XML-Directory
External XML directory for 3rd party applications.


Change username and password on below line.
$users = array('admin' => 'password', 'guest' => 'locked!!@#');

Update DB credentials in dbcon.php

$dbconn = pg_connect("host=192.168.0.1 dbname=fusionpbx user=fusionpbx password=password123")

Specify domain in URL. Example, http://dir.yourdomain.com/index.php?domain=pbx.yourdomain.com
