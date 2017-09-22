<?php 
/**
 * 
 * 
 * 微信端代金券类
 *
 * 
 * 
 */
class WxRiJie
{
	/**
	 * 
	 * 生成日结的Code 
	 * 根据Code进行日结
	 * create_at 执行日结操作的POS机编码
	 * poscode 执行日结操作的POS机编码
	 * btime 前一次日结时间
	 * etime 本次日结时间
	 * rjcode 日结编码的结构：dpid(4)+日期(8)+次数(2)
	 * 
	 */
	public static function setRijieCode($dpid,$create_at,$poscode,$btime,$etime,$rjcode){
		if(empty($dpid)||empty($create_at)||empty($poscode)||empty($btime)||empty($etime)||empty($rjcode)){
			return json_encode ( array (
					'status' => false,
					'msg' => '缺少参数'
			) );
		}
		$sql = 'select * from nb_rijie_code where dpid='.$dpid.' and pos_code="'.$poscode.'" and begin_time="'.$btime.'" and end_time="'.$etime.'" and delete_flag=0';
		$result = Yii::app()->db->createCommand($sql)->queryRow();
		if($result){
			return json_encode ( array (
					'status' => true,
					'msg' => ''
			) );
		}
		
		$lid = new Sequence("rijie_code");
		$id = $lid->nextval();
		$data = array(
				'lid'=>$id,
				'dpid'=>$dpid,
				'create_at'=>$create_at,
				'update_at'=>date('Y-m-d H:i:s',time()),
				'pos_code'=>$poscode,
				'begin_time'=>$btime,
				'end_time'=>$etime,
				'rijie_num'=>1,
				'rijie_code'=>$rjcode,
				'is_rijie'=>'0',
				'delete_flag'=>'0',
				'is_sync'=>'11111',
		);
		$result = Yii::app()->db->createCommand()->insert('nb_rijie_code',$data);
		if($result){
			return json_encode ( array (
					'status' => true,
					'msg' => ''
			) );
		}
		return json_encode ( array (
					'status' => false,
					'msg' => '日结失败'
			) );
	}
}