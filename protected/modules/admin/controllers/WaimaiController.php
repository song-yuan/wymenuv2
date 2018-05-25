<?php
header("Content-Type: text/html;charset=utf-8");
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
		$signkey = MtUnit::signkey;
		$epoiid= 'type=1 and ePoiId='.$companyId." and delete_flag=0";
		$tokenmodel = MeituanToken::model()->find($epoiid);
		// print_r($tokenmodel);exit;
		$criteria = " dpid=".$companyId." and delete_flag=0";
		$productmodels = Product::model()->findAll($criteria);
		$setmodels = ProductSet::model()->findAll($criteria);
		// print_r($productmodels);exit;
		$this->render('caipinyingshe',array(
				"productmodels"=>$productmodels,
				"tokenmodel"=>$tokenmodel,
				"companyId"=>$companyId,
				"setmodels"=>$setmodels,
				'signkey'=>$signkey
			));
	}
	public function actionDpbd(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$epoiid = "type=1 and ePoiId=".$companyId." and delete_flag=0";
		$tokenmodel = MeituanToken::model()->find($epoiid);
		$developerId = MtUnit::developerId;
		$signkey = MtUnit::signkey;
		$this->render('dpbd',array(
			'companyId'=>$companyId,
			'developerId'=>$developerId,
			'signkey'=>$signkey,
			'tokenmodel'=>$tokenmodel
			));
	}
	public function actionJcbd(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$signkey = MtUnit::signkey;
		$epoiid = "type=1 and ePoiId=".$companyId." and delete_flag=0";
		$tokenmodel = MeituanToken::model()->find($epoiid);
		$this->render('jcbd',array(
			'tokenmodel' =>$tokenmodel,
			'signkey'=>$signkey
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
		$type = Yii::app()->request->getParam('type');
		if(!empty($type)){
			$dpid = $this->companyId;
			$developerId = MtUnit::developerId;
			$re = MtOrder::downgrade($dpid,$developerId);
			$re = json_decode($re);
			$re = $re->data;
			// foreach ($re as $key => $value) {
			// 	echo $value->daySeq;
			// }
			// exit();
			if(!isset($re)){
				Yii::app()->user->setFlash('error' , yii::t('app','拉取失败或者没有降级城市'));
				$this->redirect(array('/admin/waimai/order','companyId'=>$this->companyId));
			}
		}else{
			$re = "";
		}
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
				$hasOrder = true;
			}else{
				if($orderType==1){
					$data = MtOrder::getOrderById($this->companyId, $orderId);
				}else{
					$data = Elm::getOrderById($this->companyId, $orderId);
				}
			}
		}
		$this->render('order',array('hasOrder'=>$hasOrder,'orderId'=>$orderId,'orderType'=>$orderType,'data'=>$data,'re'=>$re));
	}
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
			if($reslut){
				$msg = array('status'=>true);
			}else{
				$msg = array('status'=>false);
			}
			$reslut = json_encode($msg);
		}
		echo $reslut;exit;
	}
	
}
?>