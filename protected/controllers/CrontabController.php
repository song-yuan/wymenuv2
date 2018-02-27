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
}