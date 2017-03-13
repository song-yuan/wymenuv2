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
		//生日赠券
		WxCupon::getOneMonthByBirthday();
	} 
}