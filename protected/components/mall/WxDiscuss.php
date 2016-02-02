<?php 
/**
 * 
 * 
 * 大屏类
 * 
 * 
 */
class WxDiscuss
{
	public static $emotionArr = array(
			 '/::)',
			 '/::~',
			 '/::B',
			 '/::|',
			 '/:8-)',
			 '/::$',
			 '/::X',
			 '/::Z',
			 '/::-|',
			 '/::@',
			 '/::P',
			 '/::D',
			 '/::O',
			 '/::(',
			 '/::+',
			 '/:--b',
			 '/::Q',
			 '/::T',
			 '/:,@P',
			 '/:,@-D',
			 '/::d',
			 '/:,@o',
			 '/::g',
			 '/:|-)',
			 '/::!',
			 '/::L',
			 '/::,@',
			 '/:,@f',
			 '/::-S',
			 '/:?',
			 '/:,@x',
			 '/:,@@',
			 '/::8',
			 '/:,@!',
			 '/:!!!',
			 '/:xx',
			 '/:bye',
			 '/:wipe',
			 '/:dig',
			 '/:handclap',
			 '/:B-)',
			 '/::-O',
			 '/:P-(',
			 '/:X-)',
			 '/::*',
			 '/:@x',
			 '/:8*',
			 '/:pd',
			 '/:beer',
			 '/:basket',
			 '/:oo',
			 '/:pig',
			 '/:rose',
			 '/:strong',
			 '/:share',
			 '/:v',
			 '/:ok',
			);
	public static $emotionReplace=array(
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/0.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/1.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/2.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/3.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/4.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/6.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/7.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/8.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/10.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/11.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/12.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/13.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/14.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/15.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/16.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/17.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/18.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/19.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/20.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/21.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/22.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/23.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/24.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/25.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/26.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/27.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/29.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/30.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/31.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/32.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/33.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/34.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/35.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/36.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/37.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/38.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/40.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/41.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/42.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/44.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/47.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/49.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/51.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/52.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/53.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/54.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/55.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/57.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/58.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/59.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/62.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/63.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/79.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/81.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/82.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/89.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/90.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/92.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/93.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/95.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/96.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/97.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/98.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/100.gif"/>',
				'<img src="https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/102.gif"/>',
			);
	public static function get($dpid,$screenId = 0){
		$sql = 'select t.lid,t.content,t1.nickname from nb_discuss t,nb_brand_user t1 where t.dpid=t1.dpid and t.branduser_lid =t1.lid and t.dpid=:dpid and t.show_flag=0 and t.delete_flag=0';
		$discusses = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->queryAll();
		foreach($discusses as $k=>$discus){
			self::showDiscuss($discus['lid'],$discus['dpid']);
			$discusses[$k]['content'] = self::dealWithEmo($discus['content']);
		}
		
	    return $discusses;
	}
	
	public static function insert($param){
		$time = time();
		$se = new Sequence("discuss");
	    $lid = $se->nextval();
		$insertData = array(
							'lid'=>$lid,
				        	'dpid'=>$param['dpid'],
				        	'create_at'=>date('Y-m-d H:i:s',$time),
				        	'update_at'=>date('Y-m-d H:i:s',$time), 
				        	'branduser_lid'=>$param['user_id'],
				        	'content'=>$param['content'],
				        	'is_sync'=>DataSync::getInitSync(),
							);
		$result = Yii::app()->db->createCommand()->insert('nb_discuss', $insertData);
		return $result;
	}
	public static function showDiscuss($lid,$dpid){
		$isSync = DataSync::getInitSync();
		$sql = 'update nb_discuss set show_flag=1,is_sync='.$isSync.' where dpid='.$dpid.' and lid='.$lid;
		$result = Yii::app()->db->createCommand($sql)->execute();
	}
	public static function dealWithEmo($message){
		if(strstr($message,'/:')){
			$message = str_replace(self::$emotionArr,self::$emotionReplace,$message);
		}
		return $message;
	}
}