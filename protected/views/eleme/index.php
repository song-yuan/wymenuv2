<?php 
$xmlStr = file_get_contents('php://input');
Helper::writeLog($xmlStr);
echo '{"data":"success"}';
exit;
?>