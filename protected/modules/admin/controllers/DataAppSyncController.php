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
	$posinfo = DataSyncOperation::getDataSyncPosInfor($code);
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
   $dpid = Yii::app()->request->getParam('dpid',null);
   $tableName = Yii::app()->request->getParam('tn',null);
   $tableStruct = DataSyncOperation::getDataSyncData($dpid,$tableName);
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
	$msg = json_encode ( array (
			'status' => true,
			'verinfo' => '00.00.0003',
			'type' => '1',
			'appType' => '1'
	) );
	echo $msg;exit;
	$result = DataSyncAppVersion::checkVersion($_POST);
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
 	$result = DataSyncOperation::operateCloseAccount($dpid,$userId);
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

}