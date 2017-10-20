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
}