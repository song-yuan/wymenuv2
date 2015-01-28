<?php
/**
 * 注：外部调用，必须包含Ilifebox.php文件和getmac.js文件
 * 方法一：获取mac地址
 * 如果返回为0，则调用js getmac()方法，并再次调用方法一，如果还为0说明不再网络环境中
 * 
 * 方法二：校正方法,2个参数
 * $momac：用户校正终端的mac地址，可通过方法一获取
 * $apmac：ilifebox的mac地址
 * 返回值：如果返回'rpbench:'这个字符串说明校正成功，
 * 		如果返回false或空则说明校正失败，或参数错误
 * 
 * 方法三：获取校正点列表，1个参数
 * $momac：为手持终端的mac地址，可以通过方法一获取
 * 返回值：如果返回false则说明校正失败，或参数错误
 * 		如果正确将返回'currentvplist:A1&B1||A2&B2||.。||'格式的字符串，A1等为校正点编号，B1等为权值
 * 
 * 方法四：设置开关状态，2个参数
 * $mac为ilifebox设备mac地址
 * $nf为要设置的状态符号，为'n'则为开启，为'f'则为关闭
 * 返回值：为true，则设置成功
 * 		为false，则说明网络环境有问题，需要再次尝试
 * 
 * 方法五，统计人数,3个参数
 * $starttime：开始时间
 * $endtime：结束时间
 * $mac：要统计ilifebox的mac地址
 * 返回值：若为数字则为统计的数量
 * 		若为connect error 则是网络或参数有问题，需重新尝试
 * 
 * 方法六，取得ilifebox状态，1参数
 * $maclist为要查询状态的ilifebox的mac地址说组成的mac列表，json格式，格式如下
 * 示例：$maclist='{"maclist":[{"mac":"A0A1A2A3A4A5"},{"mac":"080027F67419"},{"mac":"111121F67419"}]}';
 * 输入：MAC列表
 * json格式：
 * {"maclist":
 * [{"mac":"XXXXXXXXXX"},
 * {"mac":"XXXXXXXXXX"},
 * {"mac":"XXXXXXXXXX"},
 * {"mac":"XXXXXXXXXX"},
 * ......
 * ]}
 * 
 * 若正常运行，返回状态json，错误则返回false
 * 输出：MAC、状态（1正常，0异常）、异常描述
 * json格式：
 * {"boxstate":
 * [{"mac":"XXXXXXXXXX",ip:"192.168.0.1","state":1,"errmsg":"XXXXXXXXXXXXXXXXX"},
 * {"mac":"XXXXXXXXXX",ip:"192.168.0.1","state":1,"errmsg":"XXXXXXXXXXXXXXXXX"},
 * {"mac":"XXXXXXXXXX",ip:"192.168.0.1","state":1,"errmsg":"XXXXXXXXXXXXXXXXX"},
 * {"mac":"XXXXXXXXXX",ip:"192.168.0.1","state":1,"errmsg":"XXXXXXXXXXXXXXXXX"},
 * ......
 * ]}
 * 
 * 方法七，取得网关状态，1参数
 * $maclist为要查询状态的网关的mac地址说组成的mac列表，json格式，格式如下
 * 示例：$maclist='{"maclist":[{"mac":"A0A1A2A3A4A5"},{"mac":"080027F67419"},{"mac":"111121F67419"}]}';
 * 输入：MAC列表
 * json格式：
 * {"maclist":
 * [{"mac":"XXXXXXXXXX"},
 * {"mac":"XXXXXXXXXX"},
 * {"mac":"XXXXXXXXXX"},
 * {"mac":"XXXXXXXXXX"},
 * ......
 * ]}
 * 
 * 若正常运行，返回状态json，错误则返回false
 * 输出：MAC、状态（1正常，0异常）、异常描述
 * json格式：
 * {"boxstate":
 * [{"mac":"XXXXXXXXXX",ip:"192.168.0.1","state":1,"errmsg":"XXXXXXXXXXXXXXXXX"},
 * {"mac":"XXXXXXXXXX",ip:"192.168.0.1","state":1,"errmsg":"XXXXXXXXXXXXXXXXX"},
 * {"mac":"XXXXXXXXXX",ip:"192.168.0.1","state":1,"errmsg":"XXXXXXXXXXXXXXXXX"},
 * {"mac":"XXXXXXXXXX",ip:"192.168.0.1","state":1,"errmsg":"XXXXXXXXXXXXXXXXX"},
 * ......
 * ]}
 * 
 * 8，取轨迹
**/
class Ilifebox{
//	const HOST='203.156.196.182';
//	const PORT='50613';
	// 获取mac地址方法
	public function getMac()
	{
		if(isset($_SESSION['mac'])&&$_SESSION['mac']){
			return $_SESSION['mac'];
		}else{
			return 0;
		}
	}
	//向服务器发送校正点信息进行校正,输入笔记本mac和ilifebox的mac，正确则返回rpbench:，
	public function sendRpMsg($momac,$apmac)
	{
		if($momac&&$apmac){
			$str='rpbench:momac:'.$momac.'||apmac:'.$apmac.'||';
			$input=SocketSetup::getInstance()->conct($str);
			return $input;
		}else{
			return false;
		}
	}
	//发送mmac，获取校正点列表
	public function getRpList($momac)
	{
		if($momac){
			$str='currentrplist:'.$momac; 
			$input=SocketSetup::getInstance()->conct($str);
			return $input;
		}else{
			return false;
		}
	}
	//设置ap开关状态 
	public function setApState($mac,$nf)
	{
		$db=MongoSetup::getinitconn();
//		$db=SetupMongo::getInstance()->conct();
		if($db){
			$collection=$db->appoint;
			$mac=hex2bin($mac);
			$mac=new MongoBinData($mac,0);
			$array=array('_id'=>$mac,'s'=>$nf);
			$collection->save($array);
			return true;
		}else{
			return false;
		}
	}
	//统计人数
	public function countNum($starttime,$endtime,$mac)
	{
		$db=MongoSetup::getinitconn();
//		$db=SetupMongo::getInstance()->conct();
		if($db&&$starttime&&$endtime&&$mac){
			$collection=$db->apmomac;
			//将检测点mac地址转化为二进制
			$adata=hex2bin($mac);
			//用MongoBinData类，将转化为二进制后的检测点mac转化为对象，进行比较。
			$amac=new MongoBinData($adata,0);
			//distinct搜出不重复的数据，在统计有多少条记录,大于比较时间
			$count=$collection->distinct('_id.m',array('_id.a'=>$amac,'_id.t'=>array('$gte'=>$starttime,'$lte'=>$endtime)));
			$count=count($count);
			return $count;
		}else{
			return 'connect error';
		}
	}
	//取得ilifebox状态 
	public function getBBoxState($maclist)
	{
		$db=MongoSetup::getinitconn();
		if($maclist&&$db){
			$collectionbox=$db->boxstate;
			$arr=json_decode($maclist);
			$arr=$arr->maclist;
			foreach($arr as $ar){
				$arrs[]=$ar->mac;
			}
			$selectbox=$collectionbox->find(array('_id'=>array('$in'=>$arrs)));
			$arraybox=iterator_to_array($selectbox,false);
			$count=count($arraybox);
			$boxstate='{"boxstate":[';
			if($count>0){
				for($i=0;$i<$count;$i++){
					if($i==$count-1){
						$boxstate.='{"mac":"'.$arraybox[$i]['_id'].'","time":'.$arraybox[$i]['time'].',"ip":"'.$arraybox[$i]['ip'].'","state":'.$arraybox[$i]['sta'].',"errmsg":"'.$arraybox[$i]['msg'].'"}';
					}else{
						$boxstate.='{"mac":"'.$arraybox[$i]['_id'].'","time":'.$arraybox[$i]['time'].',"ip":"'.$arraybox[$i]['ip'].'","state":'.$arraybox[$i]['sta'].',"errmsg":"'.$arraybox[$i]['msg'].'"},';
					}
				}
			}
			$boxstate.=']}';
			return $boxstate;
		}else{
			return false;
		}
	}
	//取得网关的状态
	public function getRBoxState($maclist)
	{
		$db=MongoSetup::getinitconn();
		if($maclist&&$db){
			$collectionbox=$db->gwstate;
			$arr=json_decode($maclist);
			$arr=$arr->maclist;
			foreach($arr as $ar){
				$arrs[]=$ar->mac;
			}
			$selectbox=$collectionbox->find(array('_id'=>array('$in'=>$arrs)));
			$arraybox=iterator_to_array($selectbox,false);
			$count=count($arraybox);
			$boxstate='{"boxstate":[';
			if($count>0){
				for($i=0;$i<$count;$i++){
					if($i==$count-1){
						$boxstate.='{"mac":"'.$arraybox[$i]['_id'].'","time":'.$arraybox[$i]['time'].',"ip":"'.$arraybox[$i]['ip'].'","state":'.$arraybox[$i]['sta'].',"errmsg":"'.$arraybox[$i]['msg'].'"}';
					}else{
						$boxstate.='{"mac":"'.$arraybox[$i]['_id'].'","time":'.$arraybox[$i]['time'].',"ip":"'.$arraybox[$i]['ip'].'","state":'.$arraybox[$i]['sta'].',"errmsg":"'.$arraybox[$i]['msg'].'"},';
					}
				}
			}
			$boxstate.=']}';
			return $boxstate;
		}else{
			return false;
		}
	}
	//取轨迹
	public function getLocus()
	{
	}
}
?>
