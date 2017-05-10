<?php 
$xmlStr = file_get_contents('php://input');
if(isset($xmlStr)&&!empty($xmlStr)){
	$data = XML::convertToArr($xmlStr);
	Helper::writeLog(json_encode($data));
}else{
	echo '{"data":"success"}';
}
exit;
?>