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
class WxCard{  //工厂
	private	$CARD_TYPE = Array("GENERAL_COUPON", 
				"GROUPON", "DISCOUNT",
				"GIFT", "CASH", "MEMBER_CARD",
				"SCENIC_TICKET", "MOVIE_TICKET" );
	
	public function __construct($card_type, $base_info)
	{
		if (!in_array($card_type, $this->CARD_TYPE))
			exit("CardType Error");
		if (! $base_info instanceof BaseInfo )
			exit("base_info Error");
		$this->card_type = $card_type;
		switch ($card_type)
		{
			case $this->CARD_TYPE[0]:
				$this->general_coupon = new WxGeneralCoupon($base_info);
				break;
			case $this->CARD_TYPE[1]:
				$this->groupon = new Groupon($base_info);
				break;
			case $this->CARD_TYPE[2]:
				$this->discount = new Discount($base_info);
				break;
			case $this->CARD_TYPE[3]:
				$this->gift = new WxGift($base_info);
				break;
			case $this->CARD_TYPE[4]:
				$this->cash = new WxCash($base_info);
				break;
			case $this->CARD_TYPE[5]:
				$this->member_card = new MemberCard($base_info);
				break;
			case $this->CARD_TYPE[6]:
				$this->scenic_ticket = new ScenicTicket($base_info);
				break;
			case $this->CARD_TYPE[8]:
				$this->movie_ticket = new MovieTicket($base_info);
				break;
			default:
				exit("CardType Error");
		}
		return true;
	}
	public function get_card()
	{
		switch ($this->card_type)
		{
			case $this->CARD_TYPE[0]:
				return $this->general_coupon;
			case $this->CARD_TYPE[1]:
				return $this->groupon;
			case $this->CARD_TYPE[2]:
				return $this->discount;
			case $this->CARD_TYPE[3]:
				return $this->gift;
			case $this->CARD_TYPE[4]:
				return $this->cash;
			case $this->CARD_TYPE[5]:
				return $this->member_card;
			case $this->CARD_TYPE[6]:
				return $this->scenic_ticket;
			case $this->CARD_TYPE[8]:
				return $this->movie_ticket;
			default:
				exit("GetCard Error");
		}
	}
	public function toJson()
	{
		return "{ \"card\":" . urldecode(json_encode($this, JSON_UNESCAPED_UNICODE)) . "}";
	}
};
?>