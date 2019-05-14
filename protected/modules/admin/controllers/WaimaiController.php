<?php
class WaimaiController extends BackendController
{
	public function actions() {
		return array(
			'upload'=>array(
				'class'=>'application.extensions.swfupload.SWFUploadAction',
				//注意这里是绝对路径,.EXT是文件后缀名替代符号
				'filepath'=>Helper::genFileName().'.EXT',
				//'onAfterUpload'=>array($this,'saveFile'),
			)
		);
	}
	public function actionList(){
		// $companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		
		$this->render('list');
	}
	public function actionIndex(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$models = MeituanToken::model()->findAll('dpid=:dpid and type=:type and delete_flag=0',array(':dpid'=>$companyId,':type'=>'1'));
		// print_r($models);exit();
		$this->render('index',array(
			'companyId'=>$companyId,
			'models'=>$models
			));
	}
	public function actionCaipinyingshe(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$timestamp = Helper::getMillisecond();
		$epoiid= 'type=1 and ePoiId='.$companyId." and delete_flag=0";
		$tokenmodel = MeituanToken::model()->find($epoiid);
		$criteria = " dpid=".$companyId." and delete_flag=0";
		$productmodels = Product::model()->findAll($criteria);
		$setmodels = ProductSet::model()->findAll($criteria);
		
		$data = array('appAuthToken'=>$tokenmodel['appAuthToken'],'ePoiId'=>$companyId,'timestamp'=>$timestamp);
		$sign = MtUnit::sign($data);
		$this->render('caipinyingshe',array(
				"productmodels"=>$productmodels,
				"tokenmodel"=>$tokenmodel,
				"companyId"=>$companyId,
				"setmodels"=>$setmodels,
				"timestamp"=>$timestamp,
				'sign'=>$sign
			));
	}
	public function actionDpbd(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$epoiid = "type=1 and ePoiId=".$companyId." and delete_flag=0";
		$tokenmodel = MeituanToken::model()->find($epoiid);
		$developerId = MtUnit::developerId;
		$timestamp = Helper::getMillisecond();
		$data = array('developerId'=>$developerId,'businessId'=>2,'ePoiId'=>$companyId,'timestamp'=>$timestamp);
		$sign = MtUnit::sign($data);
		$this->render('dpbd',array(
			'companyId'=>$companyId,
			'developerId'=>$developerId,
			'timestamp'=>$timestamp,
			'sign'=>$sign,
			'tokenmodel'=>$tokenmodel
			));
	}
	public function actionJcbd(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$epoiid = "type=1 and ePoiId=".$companyId." and delete_flag=0";
		$tokenmodel = MeituanToken::model()->find($epoiid);
		$timestamp = Helper::getMillisecond();
		$data = array('appAuthToken'=>$tokenmodel['appAuthToken'],'businessId'=>2,'timestamp'=>$timestamp);
		$sign = MtUnit::sign($data);
		$this->render('jcbd',array(
			'tokenmodel' =>$tokenmodel,
			'timestamp'=>$timestamp,
			'sign'=>$sign
			));
	}
	public function actionPeisong(){
		if(Yii::app()->request->getParam('meituan')){
			$meituan = Yii::app()->request->getParam('meituan');
			$dpid = $meituan['companyId'];
			$orderId = $meituan['orderid'];
			$courierName = $meituan['name'];
			$courierPhone = $meituan['phone'];
			$result = MtOrder::orderDistr($dpid,$orderId,$courierName,$courierPhone);

		}
		$this->render('peisong');
	}
	public function actionSetting(){
		$model = WaimaiSetting::model()->find('dpid='.$this->companyId.' and delete_flag=0');
		if(!$model){
			$model = new WaimaiSetting();
			$model->create_at = date('Y-m-d H:i:s',time());
	        $model->update_at=date('Y-m-d H:i:s',time());
		}
		$model->dpid = $this->companyId ;
		if(Yii::app()->request->isPostRequest) {
			$sql = "dpid=$this->companyId and delete_flag=0";
			$res = $model->find($sql);
			$model->attributes = Yii::app()->request->getPost('WaimaiSetting');
			$se=new Sequence("waimai_setting");
	        $model->lid = $se->nextval();
	        //var_dump($model);exit;
			if(isset($res['dpid'])){
				if($model->save()){
					Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
				} 
			}else{
				if($model->save()){
					Yii::app()->user->setFlash('success' , yii::t('app','添加成功'));
				}
			}
		}
		$this->render('setting',array(
			"model"=>$model
			));
	}
	public function actionOrder(){
		$hasOrder = false;
		$orderId = '';
		$orderType = 1;
		$data = '';
		if(Yii::app()->request->isPostRequest) {
			$orderType = Yii::app()->request->getPost('orderType');
			$orderId = Yii::app()->request->getPost('orderId');
			if($orderId == ''){
				Yii::app()->user->setFlash('error' , yii::t('app','请输入订单号'));
				$this->redirect(array('/admin/waimai/order','companyId'=>$this->companyId));
			}
			$sql = 'select * from nb_order where account_no='.$orderId;
			$order = Yii::app()->db->createCommand($sql)->queryRow();
			if($order){
				$data = json_encode($order);
				$hasOrder = true;
			}else{
				if($orderType==1){
					$data = MtOrder::getOrderById($this->companyId, $orderId);
				}elseif($orderType==2){
					$data = Elm::getOrderById($this->companyId, $orderId);
				}else{
					$data = '';
				}
			}
		}
		$this->render('order',array('hasOrder'=>$hasOrder,'orderId'=>$orderId,'orderType'=>$orderType,'data'=>$data));
	}
	public function actionReal(){
		$type = Yii::app()->request->getPost('type');
		if(!empty($type)){
			$dpid = $this->companyId;
			$re = MtOrder::privacyNumber($dpid);
			$re = json_decode($re);
			if(isset($re->error)){
				Yii::app()->user->setFlash('error' , yii::t('app','参数格式错误或不存在降级城市'));
				$this->redirect(array('/admin/waimai/real','companyId'=>$this->companyId));
			}else{
				$re = $re->data;
			}
		}else{
			$re = "";
		}
		$this->render('real',array('re'=>$re));
	}
	/**
	 * 重新生成外卖订单
	 */ 
	public function actionDealOrder(){
		$type = Yii::app()->request->getParam('type');
		$data = Yii::app()->request->getParam('data');
		if($type==1){
			$obj = json_decode(urldecode($data),true);
			$obj['data']['ctime'] = $obj['data']['cTime'];
			$obj['data']['status'] = 4;
			unset($obj['data']['cTime']);
			$data = json_encode($obj['data']);
			$reslut = MtOrder::dealOrder($data, $this->companyId, 2);
		}else{
			$obj = json_decode(urldecode($data));
			$data = $obj->result;
			$reslut = Elm::dealOrder($data, $this->companyId, 4);
		}
		if($reslut){
			$msg = array('status'=>true);
		}else{
			$msg = array('status'=>false);
		}
		$reslut = json_encode($msg);
		echo $reslut;exit;
	}
	/**
	 * 重新推送订单
	 */
	public function actionPushOrder(){
		$orderId = Yii::app()->request->getPost('orderId');
		$dpid = $this->companyId;
		$order = WxOrder::getOrder($orderId, $dpid);
		$orderPlatform = array();
		
		$pfsql = 'select * from nb_order_platform where dpid='.$dpid.' and order_id='.$orderId;
		$platform = Yii::app()->db->createCommand($pfsql)->queryRow();
		if($platform){
			$orderPlatform = $platform;
		}
		$orderArr = array();
	 	$orderArr['nb_site_no'] = array();
	 	$orderArr['nb_order_platform'] = $orderPlatform;
	 	$order['order_status'] = 3;
	 	$orderArr['nb_order'] = $order;
	 	$orderId = $order['lid'];
	 	$dpid = $order['dpid'];
		$orderProducts = WxOrder::getOrderProductData($orderId, $dpid);
		// 获取收款机内容 并放入redis缓存
		$orderAddressArr = array();
		$orderPays = WxOrderPay::get($dpid, $orderId);
		if(in_array($order['order_type'],array(2,3))){
			$orderAddress = WxOrder::getOrderAddress($orderId, $dpid);
		}
		$orderDiscount = WxOrder::getOrderAccountDiscount($orderId, $dpid);
		$orderArr['nb_order_product'] = $orderProducts;
		$orderArr['nb_order_pay'] = $orderPays;
		if(!empty($orderAddress)){
			array_push($orderAddressArr, $orderAddress);
		}
		$orderArr['nb_order_address'] = $orderAddressArr;
		$orderArr['nb_order_taste'] = $order['taste'];
		$orderArr['nb_order_account_discount'] = $orderDiscount;
		$orderStr = json_encode($orderArr);
		$result = WxRedis::pushPlatform($dpid, $orderStr);
		$msg = array('status'=>true);
		if(!$result){
			$msg = array('status'=>false);
		}
		echo json_encode($msg);
		exit;
	}
}
?>