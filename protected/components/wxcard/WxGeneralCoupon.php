<?php
/*
微信卡包api SDK V1.0
!!README!!：
base_info的构造函数的参数是必填字段，有set接口的可选字段。
针对某一种卡的必填字段（参照文档）仍然需要手动set（比如团购券Groupon的deal_detail），通过card->get_card()拿到card的实体对象来set。
ToJson就能直接转换为符合规则的json。
Signature是方便生成签名的类，具体用法见示例。
注意填写的参数是int还是string或者bool或者自定义class。
更具体用法见最后示例test，各种细节以最新文档为准。
*/
class WxGeneralCoupon extends CardBase{
	function set_default_detail($default_detail){
		$this->default_detail = $default_detail;
	}
};
?>