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
class BaseInfo{
	public function __construct($logo_url, $brand_name, $code_type, $title, $color, $notice, $service_phone, $description, $date_info, $sku){
		if (! $date_info instanceof DateInfo )
			exit("date_info Error");
		if (! $sku instanceof Sku )
			exit("sku Error");
		if (! is_int($code_type) )
			exit("code_type must be integer");
		$this->logo_url = $logo_url;
		$this->brand_name = $brand_name;
		$this->code_type = $code_type;
		$this->title = $title;
		$this->color = $color;
		$this->notice = $notice;
		$this->service_phone = $service_phone;
		$this->description = $description;
		$this->date_info = $date_info;
		$this->sku = $sku;
	}
	public function set_sub_title($sub_title){
		$this->sub_title = $sub_title;
	}
	public function set_use_limit($use_limit){
		if (! is_int($use_limit) )
			exit("use_limit must be integer");
		$this->use_limit = $use_limit;
	}
	public function set_get_limit($get_limit){
		if (! is_int($get_limit) )
			exit("get_limit must be integer");
		$this->get_limit = $get_limit;
	}
	public function set_use_custom_code($use_custom_code){
		$this->use_custom_code = $use_custom_code;
	}
	public function set_bind_openid($bind_openid){
		$this->bind_openid = $bind_openid;
	}
	public function set_can_share($can_share){
		$this->can_share = $can_share;
	}
	public function set_can_give_friend($can_give_friend){
		$this->can_give_friend = $can_give_friend;
	}
	public function set_location_id_list($location_id_list){
		$this->location_id_list = $location_id_list;
	}
	public function set_url_name_type($url_name_type){
		if (! is_int($url_name_type) )
			exit( "url_name_type must be int" );
		$this->url_name_type = $url_name_type;
	}
	public function set_custom_url($custom_url){
		$this->custom_url = $custom_url;
	}
}
?>