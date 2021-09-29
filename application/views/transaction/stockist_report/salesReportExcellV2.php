<?php
$data = '&quot;Name&quot;t&quot;City&quot;t&quot;Country&quot;';
$data .= '&quot;Ashok&quot;t&quot;New Delhi&quot;t&quot;India&quot;';
$data .= '&quot;Krishna&quot;t&quot;Bangalore&quot;t&quot;India&quot;';

$f = fopen('data.xls' , 'wb');
fwrite($f , $data );
fclose($f);
?>