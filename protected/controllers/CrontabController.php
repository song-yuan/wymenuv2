<?php
/**
 * 
 * @author dys
 *系统定时任务
 *
 */
class  CrontabController extends Controller
{   
	/**
	 * 生成失败的数据放入redis 12点前定时
	 * 12点后退款的话 会退款失败
	 */
	public function actionSyncfail(){
		$dpid = 0;
		$syncData = DataSyncOperation::getAllSyncFailure($dpid, 1);
		$syncArr = json_decode($syncData,true);
		if(!empty($syncArr)){
			foreach ($syncArr as $sync){
				$lid = $sync['lid'];
				$dpid = $sync['dpid'];
				$orderData = $sync['content'];
				$result = WxRedis::pushOrder($dpid, $orderData);
				if($result){
					DataSyncOperation::delSyncFailure($lid,$dpid);
				}
			}
		}
		// 有些订单生成过程中 进程结束
		$skeys = Yii::app()->redis->keys('order-*');
		foreach ($skeys as $key){
			$orderData = Yii::app()->redis->get($key);
			if($orderData){
				Yii::app()->redis->delete($key);
				WxRedis::pushOrder('0', $orderData);
			}
		}
	}
	public function actionSentCuponToBirthDay(){
		//生日赠券 提前一周发券
		WxCupon::getOneMonthByBirthday();
	} 
	// 生成日结统计数据
	public function actionRijieStatistics(){
		WxRiJie::rijieStatistics();
	}
	// 同步失败的数据 重新同步
	public function actionRedisOrder(){
		$dpid = 0;
		$syncData = DataSyncOperation::getAllSyncFailure($dpid, 1);
		$syncArr = json_decode($syncData,true);
		if(!empty($syncArr)){
			foreach ($syncArr as $sync){
				$lid = $sync['lid'];
				$dpid = $sync['dpid'];
				$orderData = $sync['content'];
				$orderDataArr = json_decode($orderData,true);
				if(!is_array($orderDataArr)){
					continue;
				}
				$type = $orderDataArr['type'];
				if($type==2){
					// 新增订单
					$result = DataSyncOperation::operateOrder($orderDataArr);
				}elseif($type==4){
					// 退款
					$result = DataSyncOperation::retreatOrder($orderDataArr);
				}elseif($type==3){
					// 增加会员卡
					$result = DataSyncOperation::addMemberCard($orderDataArr);
				}elseif($type==5){
					$content = $orderDataArr['data'];
					$contentArr = explode('::', $content);
					$rjDpid = $contentArr[0];
					$rjUserId = $contentArr[1];
					$rjCreateAt = $contentArr[2];
					$rjPoscode = $contentArr[3];
					$rjBtime = $contentArr[4];
					$rjEtime = $contentArr[5];
					$rjcode = $contentArr[6];
					$result = WxRiJie::setRijieCode($rjDpid,$rjCreateAt,$rjPoscode,$rjBtime,$rjEtime,$rjcode);
				}
				$resObj = json_decode($result);
				if($resObj->status){
					DataSyncOperation::delSyncFailure($lid,$dpid);
				}else{
					Helper::writeLog('再次同步失败:同步内容:'.$dpid.json_encode($sync).'错误信息:'.$resObj->msg);
				}
			}
		}
	}
	/**
	 * 盘点数据处理 
	 * 
	 */
	public function actionStockTaking(){
		WxRiJie::dealPandian();
	}
	/**
	 *
	 * 新上铁接口
	 *
	 */
	public function actionOrderToXst(){
		$yesterDateBegain = date('Y-m-d 00:00:00',strtotime("-1 day"));
		$yesterDateEnd = date('Y-m-d 23:59:59',strtotime("-1 day"));
		$platforms = ThirdPlatform::getXstInfo();
		foreach ($platforms as $platform){
			$sql = 'Select t.lid,t.dpid,t.create_at,t.order_type,t.should_total,t1.paytype,t2.is_retreat from nb_order t left join nb_order_pay t1 on t.dpid=t1.dpid and t.lid=t1.order_id left join nb_order_product t2 on t.dpid=t2.dpid and t.lid=t2.order_id where t.dpid='.$platform['dpid'].' and t.create_at >= "'.$yesterDateBegain.'" and t.create_at <= "'.$yesterDateEnd.'" and t.order_status in (3,4,8) and t1.paytype!=11 group by lid,dpid';
			$orders = Yii::app()->db->createCommand($sql)->queryAll();
			foreach ($orders as $order){
				$sourcetype = 'POS机';
				if($order['paytype']==0){
					$payment = '现金';
				}else{
					$payment = '非现金';
				}
				if($order['is_retreat']==0){
					$transtype = '销售';
				}else{
					$transtype = '退货';
				}
				$xstData = array(
						'lid'=>$order['lid'],
						'create_at'=>$order['create_at'],
						'total'=>$order['should_total'],
						'payment'=>$payment,
						'transtype'=>$transtype,
						'sourcetype'=>$sourcetype,
				);
				ThirdPlatform::xst($xstData,$platform);
			}
		}
	}
	/**
	 * 查询饿了么token如果过期了 系统自动刷新token
	 * 
	 */
	public function actionGetelemeToken(){
		$time = strtotime('+2 day')+600;
		$sql = 'select * from nb_eleme_token where expires_in < '.$time.' and delete_flag=0';
		$elemeTokens = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($elemeTokens as $token){
			$dpid = $token['dpid'];
			$refresh_token = $token['refresh_token'];
			$key = ElmConfig::key;
			$secret = ElmConfig::secret;
			$token_url = ElmConfig::token;
			$header = array(
					"Authorization: Basic " . base64_encode(urlencode($key) . ":" . urlencode($secret)),
					"Content-Type: application/x-www-form-urlencoded; charset=utf-8",
					"Accept-Encoding: gzip");
			$body = array(
					"grant_type" => "refresh_token",
					"refresh_token"=>$refresh_token
			);
			$re = ElUnit::postHttpsHeader($token_url,$header,$body);
			$obj = json_decode($re);
			if(isset($obj->access_token)){
				$access_token = $obj->access_token;
				$expires_in = time() + $obj->expires_in;
				$refresh_token = $obj->refresh_token;
				$sql = 'update nb_eleme_token set access_token="'.$access_token.'",expires_in='.$expires_in.',refresh_token="'.$refresh_token.'" where dpid='.$dpid.' and delete_flag=0';
				$res = Yii::app()->db->createCommand($sql)->execute();
				Helper::writeLog('eleme_token--'.$dpid.'--'.$access_token);
			}
		}
	}
}