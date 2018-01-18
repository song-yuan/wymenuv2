<?php
/**
 * 
 * 
 * @author dys
 * 收钱吧异步通知累
 * 
 */
class MtpNotify{
	/**
	 * 
	 * @param unknown $type
	 * $type wap wapapi支付  pre-create 预下单
	 * 
	 */
	public function Handle($type){
		if($type=='pre-create'){
			$result = $this->callUserFunc(array($this, 'preCreateCallBack'));
			if($result){
				$this->ReplyNotify(true);
			}else{
				$this->ReplyNotify(false);
			}
		}
	}
	public function preCreateCallBack($data){
		$objArr = json_decode($data,true);
		if(!array_key_exists("sn", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		
		$sn = $objArr['sn'];
		$client_sn = $objArr['client_sn'];
		$client_sn_arr = explode('-',$client_sn);
		$orderid = $client_sn_arr[0];
		$orderdpid = $client_sn_arr[1];
		
		$devicemodel = WxCompany::getSqbPayinfo($orderdpid);
		$terminal_sn = $devicemodel['terminal_sn'];
		$terminal_key = $devicemodel['terminal_key'];
		$preData = array(
				'terminal_sn'=>$terminal_sn,
				'terminal_key'=>$terminal_key,
				'sn'=>$sn,
		);
		$result = SqbPay::query($preData);
		Helper::writeLog($result);
		$resArr = json_decode($result,true);
		if($resArr['result_code']=='200'&&$resArr['biz_response']['result_code']=='SUCCESS'){
			if($resArr['biz_response']['data']['status']=='SUCCESS'&&$resArr['biz_response']['data']['order_status']=='PAID'){
				$this->checkNotify($data);
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	public function checkNotify($data){
		$obj = json_decode($data,true);
		$sn = $obj[''];
		$sql = 'SELECT (SELECT count(*) FROM nb_notify_wxwap WHERE sn = "' .$data['transaction_id']. '") + (SELECT count(*) FROM nb_notify_wxwap WHERE client_sn = "' .$data['client_sn']. '") as count';
		$count = Yii::app()->db->createCommand($sql)->queryRow();
		if(!$count['count']){
			$this->insertNotify($data);
		}
	}
	public function insertNotify($data){
		Helper::writeLog('notify insert');
		// 预订单下单通知
	}
	private function callUserFunc($callback){
		$data = file_get_contents('php://input');
		Helper::writeLog('sqb-pre-create-notify'.$data);
		return call_user_func($callback,$data);
	}
	private function ReplyNotify($status = true)
	{
		if($status){
			echo 'success';
		}else {
			echo 'error';
		}
	}
}
?>