<?php 
// $data = '{"avgSendTime":2399.0,"caution":"😉🤩😖😤🤯","cityId":999999,"ctime":1517192656,"daySeq":"2","deliveryTime":0,"detail":"[{\"app_food_code\":\"026191000218\",\"box_num\":1,\"box_price\":0,\"food_discount\":1,\"food_name\":\"脆皮手枪腿\",\"food_property\":\"\",\"price\":1,\"quantity\":1,\"sku_id\":\"026191000218\",\"spec\":\"\",\"unit\":\"份\"}]","dinnersNumber":0,"ePoiId":"0000000028","extras":"[{}]","hasInvoiced":0,"invoiceTitle":"","isFavorites":true,"isPoiFirstOrder":false,"isThirdShipping":0,"latitude":29.77449,"logisticsCode":"0000","longitude":95.369272,"orderId":16650443174389601,"orderIdView":16650443174389601,"originalPrice":10.0,"payType":1,"poiAddress":"南极洲04号站","poiFirstOrder":false,"poiId":1665044,"poiName":"kfpttest_zl8_33","poiPhone":"4009208801","poiReceiveDetail":"{\"actOrderChargeByMt\":[{\"comment\":\"活动款\",\"feeTypeDesc\":\"活动款\",\"feeTypeId\":10019,\"moneyCent\":0}],\"actOrderChargeByPoi\":[],\"foodShareFeeChargeByPoi\":0,\"logisticsFee\":900,\"onlinePayment\":1000,\"wmPoiReceiveCent\":1000}","recipientAddress":"色金拉 (123号(&nbsp;)(1+2=3))@#西藏自治区林芝市墨脱县色金拉\\t","recipientName":"默默(先生)","recipientPhone":"18601739563","shipperPhone":"","shippingFee":9.0,"status":2,"total":10.0,"utime":1517192656}';
// $data = Helper::dealString($data);
// var_dump($data);
// $obj = json_decode($data);
// $caution = $obj->caution;
// $jcaution = json_encode($caution);
// $recipientAddress = $obj->recipientAddress;
// var_dump($caution);
// var_dump($jcaution);
// var_dump($recipientAddress);
$url = 'http://openpay.zc.st.meituan.com/auth?bizId=31140&mchId=4282256&redirect_uri=http%3A%2F%2Fmenu.wymenu.com%2Fwymenuv2%2Fmall%2FmtPayOrder%3FcompanyId%3D0000000027%26dpid%3D0000000027%26outTradeNo%3D0000035000-0000000027-422%26totalFee%3D720%26subject%3D%25E7%2589%25A9%25E6%2598%2593%25E7%25BD%2591%25E7%25BB%259C%25E7%25A7%2591%25E6%258A%2580%25E6%259C%2589%25E9%2599%2590%25E5%2585%25AC%25E5%258F%25B8%25E5%25A3%25B9%25E7%2582%25B9%25E5%2590%2583%25E6%25BC%2594%25E7%25A4%25BA%25E5%25BA%2597-%25E5%25BE%25AE%25E4%25BF%25A1%25E7%2582%25B9%25E9%25A4%2590%25E8%25AE%25A2%25E5%258D%2595%26body%3D%25E7%2589%25A9%25E6%2598%2593%25E7%25BD%2591%25E7%25BB%259C%25E7%25A7%2591%25E6%258A%2580%25E6%259C%2589%25E9%2599%2590%25E5%2585%25AC%25E5%258F%25B8%25E5%25A3%25B9%25E7%2582%25B9%25E5%2590%2583%25E6%25BC%2594%25E7%25A4%25BA%25E5%25BA%2597-%25E5%25BE%25AE%25E4%25BF%25A1%25E7%2582%25B9%25E9%25A4%2590%25E8%25AE%25A2%25E5%258D%2595%26channel%3Dwx_scan_pay%26expireMinutes%3D5%26tradeType%3DJSAPI%26notifyUrl%3Dhttp%253A%252F%252Fmenu.wymenu.com%252Fwymenuv2%252Fsqbpay%252Fwappayresult';
header("Location: ".$url);
exit;
?>

