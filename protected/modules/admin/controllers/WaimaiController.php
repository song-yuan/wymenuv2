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
		$data = '';
		if(Yii::app()->request->isPostRequest) {
			$orderType = Yii::app()->request->getPost('orderType');
			$orderId = Yii::app()->request->getPost('orderId');
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
		$this->render('order',array('hasOrder'=>$hasOrder,'data'=>$data));
	}
	public function actionDealOrder(){
		$data = Yii::app()->request->getParam('data');
		$reslut = MtOrder::dealOrder($data, $this->companyId, 2);
		echo $reslut;exit;
	}
	
}
?>