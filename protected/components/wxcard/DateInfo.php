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
class DateInfo{
	public function __construct($type, $arg0, $arg1 = null) 
	{
		if (!is_int($type) )
			exit("DateInfo.type must be integer");
		$this->type = $type;
		if ( $type == 1 )  //固定日期区间
		{
			if (!is_int($arg0) || !is_int($arg1))
				exit("begin_timestamp and  end_timestamp must be integer");
			$this->begin_timestamp = $arg0;
			$this->end_timestamp = $arg1;
		}
		else if ( $type == 2 )  //固定时长（自领取后多少天内有效）
		{
			if (!is_int($arg0))
				exit("fixed_term must be integer");
			$this->fixed_term = $arg0;
			$this->fixed_begin_term = $arg1;
		}else
			exit("DateInfo.tpye Error");
	}
}
?>