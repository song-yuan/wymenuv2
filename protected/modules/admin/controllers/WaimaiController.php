<?php
class WaimaiController extends BackendController
{
	public $signkey = '8isnqx6h2xewfmiu';
	public $developerId = 100746;
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
		$this->render('index');
	}
	public function actionCaipinyingshe(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$epoiid= 'type=1 and ePoiId='.$companyId." and delete_flag=0";
		$tokenmodel = MeituanToken::model()->find($epoiid);
		// print_r($tokenmodel);exit;
		$criteria = " dpid=".$companyId." and is_show=1 and delete_flag=0";
		$productmodels = Product::model()->findAll($criteria);
		$setmodels = ProductSet::model()->findAll($criteria);
		// print_r($productmodels);exit;
		$this->render('caipinyingshe',array(
				"productmodels"=>$productmodels,
				"tokenmodel"=>$tokenmodel,
				"companyId"=>$companyId,
				"setmodels"=>$setmodels
			));
	}
	public function actionDpbd(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$this->render('dpbd',array(
			'companyId'=>$companyId,
			));
	}
	public function actionJcbd(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$epoiid = "type=1 and ePoiId=".$companyId." and delete_flag=0";
		$tokenmodel = MeituanToken::model()->find($epoiid);
		$this->render('jcbd',array(
			'tokenmodel' =>$tokenmodel
			));
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
}
?>