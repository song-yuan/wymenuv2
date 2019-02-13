<?php
class WxrechargeController extends BackendController
{
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' ,yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex() {
		$criteria = new CDbCriteria;
		$criteria->addCondition('t.dpid=:dpid and t.delete_flag=0');
		$criteria->order = ' t.lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		
		$pages = new CPagination(WeixinRecharge::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = WeixinRecharge::model()->findAll($criteria);
		
		$this->render('index',array(
				'models'=>$models,
				'pages' => $pages
		));
	}
	public function actionCreate() {
		$redpids = array();
		$recashcards = array();
		$model = new WeixinRecharge;
		$model->dpid = $this->companyId;
		$companys = $this->getDp($this->comptype);
		$cashcards = $this->getCashCard($this->companyId);
		if(Yii::app()->request->isPostRequest) {
			$postData = Yii::app()->request->getPost('WeixinRecharge');
			$se = new Sequence("weixin_recharge");
			$model->lid = $se->nextval();
            $model->attributes = $postData;
            $model->create_at = date('Y-m-d H:i:s',time());
            $model->update_at = date('Y-m-d H:i:s',time());
			
            if(isset($postData['recharge_cashcard'])){
            	$model->recharge_cashcard = 1;
            	foreach ($postData['recharge_cashcard'] as $rcash){
            		$rechargeModel = new WeixinRechargeCashcard();
            		$se = new Sequence("weixin_recharge_cashcard");
            		$rechargeModel->lid = $se->nextval();
            		$rechargeModel->dpid = $this->companyId ;
            		$rechargeModel->create_at = date('Y-m-d H:i:s',time());
            		$rechargeModel->update_at = date('Y-m-d H:i:s',time());
            		$rechargeModel->weixin_recharge_id = $model->lid;
            		$rechargeModel->cashcard_id = $rcash;
            		$rechargeModel->save();
            	}
            }else{
            	$model->recharge_cashcard = 0;
            }
            
			if(isset($postData['recharge_dpid'])){
				$model->recharge_dpid = 1;
				foreach ($postData['recharge_dpid'] as $rdpid){
					$rechargeModel = new WeixinRechargeDpid();
					$se = new Sequence("weixin_recharge_dpid");
					$rechargeModel->lid = $se->nextval();
					$rechargeModel->dpid = $this->companyId ;
					$rechargeModel->create_at = date('Y-m-d H:i:s',time());
					$rechargeModel->update_at = date('Y-m-d H:i:s',time());
					$rechargeModel->weixin_recharge_id = $model->lid;
					$rechargeModel->recharge_dpid = $rdpid;
					$rechargeModel->save();
				}
			}else{
				$model->recharge_dpid = 0;
			}
			if($model->save()) {
				Yii::app()->user->setFlash('success' , yii::t('app','添加成功'));
				$this->redirect(array('wxrecharge/index' , 'companyId' => $this->companyId));
			}
		}
		$this->render('create' , array(
				'model' => $model,
				'companys' => $companys,
				'cashcards' =>$cashcards,
				'redpids'=>$redpids,
				'recashcards'=>$recashcards
		));
	}
	public function actionUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		$model = WeixinRecharge::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=>  $this->companyId));
		$companys = $this->getDp($this->comptype);
		$redpids = $this->getRechargeDpid($lid);
		$cashcards = $this->getCashCard($this->companyId);
		$recashcards = $this->getRechargeCashcard($lid);
		if(Yii::app()->request->isPostRequest) {
			$postData = Yii::app()->request->getPost('WeixinRecharge');
			$model->attributes = $postData;
			$model->update_at = date('Y-m-d H:i:s',time());
			
			if(isset($postData['recharge_cashcard'])){
				$model->recharge_cashcard = 1;
				if($postData['recharge_cashcard']!=$recashcards){
					WeixinRechargeCashcard::model()->updateAll(array('delete_flag'=>1),'weixin_recharge_id=:rid',array(':rid'=>$lid));
					foreach ($postData['recharge_cashcard'] as $rcash){
						$rechargeModel = new WeixinRechargeCashcard();
						$se = new Sequence("weixin_recharge_cashcard");
						$rechargeModel->lid = $se->nextval();
						$rechargeModel->dpid = $this->companyId ;
						$rechargeModel->create_at = date('Y-m-d H:i:s',time());
						$rechargeModel->update_at = date('Y-m-d H:i:s',time());;
						$rechargeModel->weixin_recharge_id = $lid;
						$rechargeModel->cashcard_id = $rcash;
						$rechargeModel->save();
					}
				}
			}else{
				$model->recharge_cashcard = 0;
			}
			
			if(isset($postData['recharge_dpid'])){
				$model->recharge_dpid = 1;
				if($postData['recharge_dpid']!=$redpids){
					WeixinRechargeDpid::model()->updateAll(array('delete_flag'=>1),'weixin_recharge_id=:rid',array(':rid'=>$lid));
					foreach ($postData['recharge_dpid'] as $rdpid){
						$rechargeModel = new WeixinRechargeDpid();
						$se = new Sequence("weixin_recharge_dpid");
						$rechargeModel->lid = $se->nextval();
						$rechargeModel->dpid = $this->companyId ;
						$rechargeModel->create_at = date('Y-m-d H:i:s',time());
						$rechargeModel->update_at = date('Y-m-d H:i:s',time());;
						$rechargeModel->weixin_recharge_id = $lid;
						$rechargeModel->recharge_dpid = $rdpid;
						$rechargeModel->save();
					}
				}
			}else{
				$model->recharge_dpid = 0;
			}
			if($model->save()){
				Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
				$this->redirect(array('wxrecharge/index' , 'companyId' => $this->companyId));
			}
		}
		$this->render('update' , array(
			'model'=>$model,
			'companys' => $companys,
			'cashcards' =>$cashcards,
			'redpids'=>$redpids,
			'recashcards'=>$recashcards
		));
	}
	public function actionDelete(){
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('wxrecharge/index' , 'companyId' => $this->companyId)) ;
		}
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('lid');
        if(!empty($ids)) {
			foreach ($ids as $id) {
				$model = WeixinRecharge::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id , ':companyId' => $companyId)) ;
				if($model) {
					$model->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
				}
			}
			$this->redirect(array('wxrecharge/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('wxrecharge/index' , 'companyId' => $companyId)) ;
		}
	}
	private function getRechargeDpid($rechargeId){
		$sql = 'select recharge_dpid from nb_weixin_recharge_dpid where weixin_recharge_id='.$rechargeId.' and delete_flag=0';
		$rdpids = Yii::app()->db->createCommand($sql)->queryColumn();
		return $rdpids;
	}
	private function getDp($type = 0){
		if($type==0){
			$sql = 'select dpid,company_name from nb_company where type=1 and comp_dpid='.$this->companyId.' and delete_flag=0';
			$companys = Yii::app()->db->createCommand($sql)->queryAll();
		}else{
			//门店
			$companys = array();
			$sql = 'select dpid,company_name from nb_company where dpid='.$this->companyId.' and delete_flag=0';
			$company = Yii::app()->db->createCommand($sql)->queryRow();
			if($company){
				array_push($companys, $company);
			}
		}
		return $companys;
	}
	private function getRechargeCashcard($rechargeId){
		$sql = 'select cashcard_id from nb_weixin_recharge_cashcard where weixin_recharge_id='.$rechargeId.' and delete_flag=0';
		$rdpids = Yii::app()->db->createCommand($sql)->queryColumn();
		return $rdpids;
	}
	private function getCashCard($dpid = 0){
		$sql = 'select lid,sole_code,cupon_title from nb_cupon where dpid='.$dpid.' and delete_flag=0';
		$cashcards = Yii::app()->db->createCommand($sql)->queryAll();
		return $cashcards;
	}
}