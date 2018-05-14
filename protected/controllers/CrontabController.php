<?php
/**
 * 
 * @author dys
 *系统定时任务
 *
 */
class  CrontabController extends Controller
{   
	public function actionSentCuponToBirthDay(){
		//生日赠券 提前一周发券
		WxCupon::getOneMonthByBirthday();
	} 
	// 生成日结统计数据
	public function actionRijieStatistics(){
		$result = WxRiJie::rijieStatistics();
	}
	public function actionRedisOrder(){
		$sql = 'select dpid from nb_company where type=1 and delete_flag = 0';
		$dpids = Yii::app()->db->createCommand($sql)->queryColumn();
		foreach ($dpids as $dpid){
			// 确保redis里订单数据生成订单
			WxRedis::dealRedisData($dpid);
		}
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