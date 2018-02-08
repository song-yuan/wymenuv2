<?php 
// 过滤掉emoji表情
function filterEmoji($str)
{
	$str = preg_replace_callback(
				'/./us',
				function (array $match) {
					var_dump($match);
					return strlen($match[0]) >= 4 ? '' : $match[0];
				},
				$str
			);
	return $str;
 }
$data = 'developerId=100943&ePoiId=0000000028&sign=719817a77033ec93609aea669e0200a7ac40a7b7&order=%7B%22avgSendTime%22%3A2400.0%2C%22caution%22%3A%22%E5%A4%9A%E6%94%BE%E8%BE%A3%E6%A4%92a%F0%9F%98%84%F0%9F%98%8E%F0%9F%98%94%F0%9F%98%AB%F0%9F%98%A4%5Cn+%5E_%5E%5Cn%2F%22%2C%22cityId%22%3A999999%2C%22ctime%22%3A1518058906%2C%22daySeq%22%3A%223%22%2C%22deliveryTime%22%3A0%2C%22detail%22%3A%22%5B%7B%5C%22app_food_code%5C%22%3A%5C%22026191000218%5C%22%2C%5C%22box_num%5C%22%3A1%2C%5C%22box_price%5C%22%3A0%2C%5C%22food_discount%5C%22%3A1%2C%5C%22food_name%5C%22%3A%5C%22%E8%84%86%E7%9A%AE%E6%89%8B%E6%9E%AA%E8%85%BF%5C%22%2C%5C%22food_property%5C%22%3A%5C%22%5C%22%2C%5C%22price%5C%22%3A1%2C%5C%22quantity%5C%22%3A1%2C%5C%22sku_id%5C%22%3A%5C%22026191000218%5C%22%2C%5C%22spec%5C%22%3A%5C%22%5C%22%2C%5C%22unit%5C%22%3A%5C%22%E4%BB%BD%5C%22%7D%2C%7B%5C%22app_food_code%5C%22%3A%5C%22026195200226%5C%22%2C%5C%22box_num%5C%22%3A1%2C%5C%22box_price%5C%22%3A0%2C%5C%22food_discount%5C%22%3A1%2C%5C%22food_name%5C%22%3A%5C%22%E9%BB%84%E9%87%91%E8%9D%B4%E8%9D%B6%E8%99%BE%284%E4%B8%AA%29%5C%22%2C%5C%22food_property%5C%22%3A%5C%22%5C%22%2C%5C%22price%5C%22%3A5%2C%5C%22quantity%5C%22%3A1%2C%5C%22sku_id%5C%22%3A%5C%22026195200226%5C%22%2C%5C%22spec%5C%22%3A%5C%22%5C%22%2C%5C%22unit%5C%22%3A%5C%22%E4%BB%BD%5C%22%7D%5D%22%2C%22dinnersNumber%22%3A0%2C%22ePoiId%22%3A%220000000028%22%2C%22extras%22%3A%22%5B%7B%7D%5D%22%2C%22hasInvoiced%22%3A0%2C%22invoiceTitle%22%3A%22%22%2C%22isFavorites%22%3Atrue%2C%22isPoiFirstOrder%22%3Afalse%2C%22isThirdShipping%22%3A0%2C%22latitude%22%3A29.77449%2C%22logisticsCode%22%3A%220000%22%2C%22longitude%22%3A95.369272%2C%22orderId%22%3A16650443632263245%2C%22orderIdView%22%3A16650443632263245%2C%22originalPrice%22%3A15.0%2C%22payType%22%3A1%2C%22poiAddress%22%3A%22%E5%8D%97%E6%9E%81%E6%B4%B204%E5%8F%B7%E7%AB%99%22%2C%22poiFirstOrder%22%3Afalse%2C%22poiId%22%3A1665044%2C%22poiName%22%3A%22kfpttest_zl8_33%22%2C%22poiPhone%22%3A%224009208801%22%2C%22poiReceiveDetail%22%3A%22%7B%5C%22actOrderChargeByMt%5C%22%3A%5B%7B%5C%22comment%5C%22%3A%5C%22%E6%B4%BB%E5%8A%A8%E6%AC%BE%5C%22%2C%5C%22feeTypeDesc%5C%22%3A%5C%22%E6%B4%BB%E5%8A%A8%E6%AC%BE%5C%22%2C%5C%22feeTypeId%5C%22%3A10019%2C%5C%22moneyCent%5C%22%3A0%7D%5D%2C%5C%22actOrderChargeByPoi%5C%22%3A%5B%5D%2C%5C%22foodShareFeeChargeByPoi%5C%22%3A0%2C%5C%22logisticsFee%5C%22%3A900%2C%5C%22onlinePayment%5C%22%3A1500%2C%5C%22wmPoiReceiveCent%5C%22%3A1500%7D%22%2C%22recipientAddress%22%3A%22%E8%89%B2%E9%87%91%E6%8B%89+%28123%E5%8F%B7%28%26nbsp%3B%29%281%2B2%3D3%29%29%40%23%E8%A5%BF%E8%97%8F%E8%87%AA%E6%B2%BB%E5%8C%BA%E6%9E%97%E8%8A%9D%E5%B8%82%E5%A2%A8%E8%84%B1%E5%8E%BF%E8%89%B2%E9%87%91%E6%8B%89%22%2C%22recipientName%22%3A%22%E9%BB%98%E9%BB%98%28%E5%85%88%E7%94%9F%29%22%2C%22recipientPhone%22%3A%2218601739563%22%2C%22shipperPhone%22%3A%22%22%2C%22shippingFee%22%3A9.0%2C%22status%22%3A2%2C%22total%22%3A15.0%2C%22utime%22%3A1518058906%7D';
$data = urldecode($data);
var_dump($data);
//$data = filterEmoji($data);
$data = Helper::dealString($data);
var_dump($data);

?>

