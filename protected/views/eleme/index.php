<?php 
$xmlStr = file_get_contents('php://input');
if(!$xmlStr){
	echo '{"data":"success"}';
}else{
	// 	$data = XML::convertToArr($xmlStr);
	Helper::writeLog($xmlStr);
}
exit;
?>