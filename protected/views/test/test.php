<?php 
$data = '{"requestId":"200014323281254890","type":12,"appId":11949142,"message":"{\"orderId\":\"3022682819183740026\",\"state\":\"valid\",\"shopId\":156804037,\"updateTime\":1525231066,\"role\":3}","shopId":156804037,"timestamp":1525231066767,"signature":"3E83E0524C5C624C2A04DD4A4D53B58E","userId":51321174359737349}';
$remt = Elm::dealElmData($data);
var_dump($remt);
?>


