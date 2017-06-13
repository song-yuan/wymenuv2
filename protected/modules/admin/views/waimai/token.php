<?php
$xmlStr = file_get_contents('php://input');
$ePoiId = $_POST['ePoiId'];
$appAuthToken = $_POST['appAuthToken'];
if(!empty($xmlStr)){
	$fileType = mb_detect_encoding($xmlStr , array('UTF-8','GBK','LATIN1','BIG5')) ;
	if( $fileType != 'UTF-8'){
		$xmlStr = mb_convert_encoding($xmlStr ,'utf-8' , $fileType);
	}
}
file_put_contents ("log.txt", date ( "Y-m-d H:i:s" ) . "  " . $xmlStr . "\r\n", FILE_APPEND );
$conn = new mysqli("127.0.0.1", "root", "MYmenu123","newdb");  
// Check connection  

if ($conn->connect_error) {  
    die("连接失败: " . $conn->connect_error);  
}
$sql = "insert into nb_token values($ePoiId,'$appAuthToken')";
$conn->query($sql);
$conn->close();
echo '{"data":"success"}';
exit;
?>