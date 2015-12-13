<?php 
/**
 * 
 * 
 * 消费充值返现类
 * $type=0 消费  $type=1 充值
 * 
 * 
 */
class WxCashBack
{
	
	
	public $dpid;
	public $userId;
	public $type;
	
	public function __construct($dpid,$userId,$type){
		$this->dpid = $dpid;
		$this->userId = $userId;
		$this->type = $type;
	}
	
	/**
	 * 
	 * 获取消费或充值 模板
	 * 
	 */
	public function getCashTpl(){
		if($this->type==0){
			$sql = 'select * from nb_consumer_cash_proportion';
		}else{
			
		}
	}
	
}