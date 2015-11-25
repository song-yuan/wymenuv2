<?php

$myfile = fopen("/tmp/notify.txt", "w") or die("Unable to open file!");
fwrite($myfile, 'aaa');
fwrite($myfile, 'bbbbbbb');
fwrite($myfile, 'cccc');
fclose($myfile);
$notify = new Notify();
$notify->Handle(false);

?>

