<html>
   <head>
        <title>Contact List</title>
   </head>

<?php
$realm = 'Restricted area';

//user => password
$users = array('admin' => 'password', 'guest' => 'locked!!@#');


if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Digest realm="'.$realm.
           '",qop="auth",nonce="'.uniqid().'",opaque="'.md5($realm).'"');

    die('Text to send if user hits Cancel button');
}


// analyze the PHP_AUTH_DIGEST variable
if (!($data = http_digest_parse($_SERVER['PHP_AUTH_DIGEST'])) ||
    !isset($users[$data['username']]))
    die('Wrong Credentials!');


// generate the valid response
$A1 = md5($data['username'] . ':' . $realm . ':' . $users[$data['username']]);
$A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
$valid_response = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);

if ($data['response'] != $valid_response)
    die('Wrong Credentials!');

// function to parse the http auth header
function http_digest_parse($txt)
{
    // protect against missing data
    $needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
    $data = array();
    $keys = implode('|', array_keys($needed_parts));

    preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

    foreach ($matches as $m) {
        $data[$m[1]] = $m[3] ? $m[3] : $m[4];
        unset($needed_parts[$m[1]]);
    }

    return $needed_parts ? false : $data;
}
?>

<body>

         &lt;&#63;xml version=&quot;1.0&quot; encoding=&quot;utf-8&quot;&#63;&gt; <br><br>
                &lt;content&gt; <br>
                &emsp;&lt;contacts&gt; <br><br>

<?php

// Get variable from URL

$domain=$_GET['domain'];

// Connecting, selecting database

include 'dbcon.php';

// Performing SQL query
$query = "SELECT v_extensions.extension as extension, v_extensions.directory_first_name as first_name, v_extensions.directory_last_name as last_name FROM v_extensions JOIN v_domains ON v_extensions.domain_uuid = v_domains.domain_uuid WHERE v_domains.domain_name = '$domain' AND v_extensions.directory_visible = 'true';";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());

// Printing results in HTML
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    echo "&emsp;&emsp;&lt;contact&gt; <br>" .
         "&emsp;&emsp;&emsp;&lt;firstName&gt;{$line['first_name']}&lt;&#47;firstName&gt; <br>" .
         "&emsp;&emsp;&emsp;&lt;lastName&gt;{$line['last_name']}&lt;&#47;lastName&gt; <br>" .
         "&emsp;&emsp;&emsp;&lt;extension&gt;{$line['extension']}&lt;&#47;extension&gt> <br>" .
         "&emsp;&emsp;&lt;contact&#47;&gt;<br><br>";
    }

// Closing connection
pg_close($dbconn);
?>

&emsp;&lt;&#47;contacts&gt; <br>
&lt;&#47;content&gt;


</body>

</html>
