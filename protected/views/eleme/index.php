<?php 
$xmlStr = file_get_contents('php://input');
if($xmlStr){
// 	$data = XML::convertToArr($xmlStr);
	Helper::writeLog($xmlStr);
}else{
	echo '{"data":"success"}';
}
exit;
?>