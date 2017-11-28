<?php
/**
 * 数据同步几大规则
 * 1：app的初始化，重要的事情提示三遍，初始化时，先检查有无数据还没有同步到云端，
 * 如果有就先同步，然后删除本地的所有的表结构及数据，最后从云端下载表结构及数据。
 * 2：下载数据同步，只同步基础数据，表结构不变化，先删除所有基础数据，然后下载
 * 3：日常数据同步，默认是自动打开的，也可以关闭。只要打开，就检查本地的所有的
 * 
 */
class DataAppSyncController extends Controller
{	
	/**
	 * 获取服务器端图片列表
	*/
	public function actionServerImglist(){
	    $company_id = Yii::app()->request->getParam('companyId',0);
	    $filesnames1 = scandir("uploads/company_".$company_id);
	    $fnj=  json_encode($filesnames1);
	    Yii::app()->end($fnj);
	}
	/**
	 * 
	 * 获取服务器时间
	 * 
	 */
	public function actionGetServerTime(){
		$now = time();
		$date = date('Y-m-d H:i:s');
		return $date;
	}
	/**
	 * 
	 * 获取pos设备信息
	 * 
	 */
	public function actionGetSyncPosInfo(){
		$code = Yii::app()->request->getParam('code',0);
		$mac = Yii::app()->request->getParam('mac','');
		$posinfo = DataSyncOperation::getDataSyncPosInfor($code,$mac);
		echo json_encode($posinfo);exit;
	}
	/**
	 * 
	 * 获取基础数据表
	 * 
	 */ 
	 public function actionGetSyncBaseTables(){
	   $baseTable = DataSyncOperation::getDataSyncBaseTables();
	   echo json_encode($baseTable);exit;
	}
	/**
	 * 
	 * 获取全部数据表
	 * 
	 */ 
	 public function actionGetSyncAllTables(){
	   $allTable = DataSyncOperation::getDataSyncAllTables();
	   echo json_encode($allTable);exit;
	}
	/**
	 * 
	 * 获取基础数据表 数据
	 * 
	 */ 
	public function actionGetSyncTableData(){
	   $data = $_GET;
	   $tableStruct = DataSyncOperation::getDataSyncData($data);
	   echo json_encode($tableStruct);exit;
	}
	/**
	 * 
	 * 
	 * 
	 */
	public function actionGetData(){
	   echo json_encode(array('status'=>true,'msg'=>'success'));exit;
	}
	/**
	 * 
	 * pad实时获取云端数据
	 * is_sync 不为0时是需要同步的数据
	 * 返回json格式
	 * array(array('table_name'="",data=>array()),array('table_name'="",data=>array()));
	 * 
	 */
	 public function actionGetSyncData(){
	    $dpid = Yii::app()->request->getParam('dpid');
	    $result = DataSyncOperation::getSyncData($dpid);
	 	echo $result;exit;
	 }
	 /**
	  * 
	  * pos机接收到订单验证
	  * 
	  */
	 public function actionSyncDataCb(){
	 	$dpid = Yii::app()->request->getParam('dpid');
	 	$data = Yii::app()->request->getParam('data');
	 	$userName = Yii::app()->request->getParam('username','');
	 	$result = DataSyncOperation::syncDataCb($dpid,$data,$userName);
	 	echo $result;exit;
	 }
	/**
	 * 
	 * 登录验证
	 * 
	 */
	 public function actionValidateLogin(){
	 	$result = DataSyncOperation::validateUser($_POST);
	 	echo $result;exit;
	}
	/**
	 * 
	 * 检验是否有新的表结构数据
	 * 
	 */
	public function actionValidateNewPosTableData(){
		$result = DataSyncOperation::getNewPosTableData($_POST);
		echo $result;exit;
	}
	/**
	 * 
	 * 
	 * 检验是否有新数据
	 * 
	 */
	public function actionValidateNewData(){
		$result = DataSyncOperation::getNewDataByTime($_POST);
		echo $result;exit;
	}
	/**
	 * 
	 * 获取订单状态
	 * 
	 */
	  public function actionGetOrderStatus(){
	  	$dpid = Yii::app()->request->getParam('companyId');
	    $orderId = Yii::app()->request->getParam('orderId');
	 	$result = DataSyncOperation::getOrderStaus($dpid,$orderId);
	 	echo $result;exit;
	}
	/**
	 * 
	 * 
	 * 
	 */
	/**
	 * 
	 * app订单同步云端
	 * sid 台号
	 * isTemp 是否是临时台
	 * post传参
	 * 
	 */
	 public function actionCreateOrder(){
	 	$result = DataSyncOperation::operateOrder($_POST);
	 	echo $result;exit;
	}
	/*
	 * app更新
	 */
	public function actionAppUpdate(){
		$result = DataSyncAppVersion::checkVersion($_POST);
		echo $result;exit;
	}
	/*
	 * app更新
	*/
	public function actionGetConnectinfo(){
		$result = DataSyncAppVersion::getConnectUsInfo($_POST);
		echo $result;exit;
	}
	/**
	 * 
	 * 退单
	 * 按照订单详情来退
	 * 
	 */
	public function actionRetreatOrder(){
		$result = DataSyncOperation::retreatOrder($_POST);
		echo $result;exit;
	}
	/**
	 * 
	 * 增加会员卡
	 * 
	 */
	public function actionAddMemberCard(){
	 	$result = DataSyncOperation::addMemberCard($_POST);
	 	echo $result;exit;
	}
	/**
	 *
	 * 获取会员卡 信息
	 *
	 */
	public function actionGetMemberCard(){
		$result = DataSyncOperation::getMemberCard($_POST);
		echo $result;exit;
	}
	/**
	 * 
	 * 获取会员卡余额
	 * 
	 */
	public function actionGetMemberCardYue(){
		$result = DataSyncOperation::getMemberCardYue($_POST);
		echo $result;exit;
	}
	/**
	 * 
	 *会员卡支付 
	 * 
	 */
	 public function actionPayByMemberCard(){
	 	$result = DataSyncOperation::payMemberCard($_POST);
	 	echo $result;exit;
	}
	/**
	 *
	 * 会员卡充值
	 *
	 */
	public function actionChargeMemberCard(){
		$result = DataSyncOperation::chargeMemberCard($_POST);
		echo $result;exit;
	}
	
	/**
	 *
	 * 旧会员卡更换新会员卡
	 *
	 */
	public function actionBindMemberCard(){
		$result = DataSyncOperation::bindMemberCard($_POST);
		echo $result;exit;
	}
	/**
	 * 
	 * 批量同步
	 * 
	 */
	public function actionBatchSync(){
		$result = DataSyncOperation::batchSync($_POST);
		echo $result;exit;
	}
	/**
	 * 
	 * 会员卡退款
	 * 
	 */
	public function actionRefundMemberCard(){
		$result = DataSyncOperation::refundMemberCard($_POST);
		echo $result;exit;
	}
	/**
	 * 日结订单
	 * 
	 */
	 public function actionCloseAccount(){
	 	$dpid = Yii::app()->request->getParam('dpid');
	 	$userId = Yii::app()->request->getParam('uid');
	 	$create_at = Yii::app()->request->getParam('create_at');
	 	$poscode = Yii::app()->request->getParam('poscode');
	 	$btime = Yii::app()->request->getParam('btime');
	 	$etime = Yii::app()->request->getParam('etime');
	 	$rjcode = Yii::app()->request->getParam('rjcode');
	 	$result = WxRiJie::setRijieCode($dpid,$create_at,$poscode,$btime,$etime,$rjcode);
	 	echo $result;exit;
	}
	/**
	 * 
	 * 获取双屏活动
	 * 
	 */
	public function actionDoubleScreen(){
	 	$dpid = Yii::app()->request->getParam('dpid');
	 	$result = DataSyncOperation::getDoubleScreen($dpid);
	 	echo $result;exit;
	}
	/**
	 *
	 *获取微信会员信息
	 *
	 */
	public function actionGetUserInfo(){
		$result = DataSyncOperation::getUserInfo($_POST);
		echo $result;exit;
	}
	/**
	 * 
	 * 微信会员卡提交
	 * 
	 */
	public function actionDealWxHykPay(){
		$result = DataSyncOperation::dealWxHykPay($_POST);
		echo $result;exit;
	}
	/**
	 *
	 * 原材料 消耗
	 *
	 */
	public function actionGetMaterial(){
		$dpid = Yii::app()->request->getParam('dpid');
		$startTime = Yii::app()->request->getParam('start_time');
		$endTime = Yii::app()->request->getParam('end_time');
		$result = DataSyncOperation::getMaterial($dpid,$startTime,$endTime);
		echo json_encode($result);exit;
	}
	/**
	 *
	 * 餐桌开台
	 *
	 */
	public function actionOpenSite(){
		$dpid = Yii::app()->request->getParam('dpid');
		$adminId = Yii::app()->request->getParam('admin_id');
		$posId = Yii::app()->request->getParam('pos_id');
		$siteId = Yii::app()->request->getParam('site_id');
		$number = Yii::app()->request->getParam('number');
		$result = SiteClass::openSite($dpid,$number,'0',$siteId,$adminId,$posId);
		echo json_encode($result);exit;
	}
	/**
	 *
	 * 餐桌撤台
	 *
	 */
	public function actionCloseSite(){
		$dpid = Yii::app()->request->getParam('dpid');
		$adminId = Yii::app()->request->getParam('admin_id');
		$posId = Yii::app()->request->getParam('pos_id');
		$siteId = Yii::app()->request->getParam('site_id');
		$result = SiteClass::closeSite($dpid,'0',$siteId);
		echo json_encode($result);exit;
	}
	/**
	 *
	 * 新上铁接口
	 *
	 */
	public function actionOrderToXst(){
		$yesterDateBegain = date('Y-m-d 00:00:00',strtotime("-2 day"));
		$yesterDateEnd = date('Y-m-d 23:59:59',strtotime("-2 day"));
		$platforms = ThirdPlatform::getXstInfo();
		foreach ($platforms as $platform){
			$sql = 'Select t.lid,t.dpid,t.create_at,t.order_type,t.should_total,t1.paytype,t2.is_retreat from nb_order t left join nb_order_pay t1 on t.dpid=t1.dpid and t.lid=t1.order_id left join nb_order_product t2 on t.dpid=t2.dpid and t.lid=t2.order_id where t.dpid='.$platform['dpid'].' and t.create_at >= "'.$yesterDateBegain.'" and t.create_at <= "'.$yesterDateEnd.'" and t.order_status in (3,4,8) and t1.paytype!=11 group by lid,dpid';
			$orders = Yii::app()->db->createCommand($sql)->queryAll();
			foreach ($orders as $order){
				if($order['order_type']==0){
					$sourcetype = 'POS机';
				}else{
					$sourcetype = '网络配餐';
				}
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
				$order = array(
						'lid'=>$order['lid'],
						'create_at'=>$order['create_at'],
						'total'=>$order['should_total'],
						'payment'=>$payment,
						'transtype'=>$transtype,
						'sourcetype'=>$sourcetype,
				);
				ThirdPlatform::xst($order,$platform);
			}
		}
	}
}