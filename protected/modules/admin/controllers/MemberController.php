<?php
class MemberController extends BackendController
{
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex() {
		$id = 0;
		$criteria = new CDbCriteria;
		$criteria->addCondition('dpid=:dpid and delete_flag=0');
		if(Yii::app()->request->isPostRequest){
			$id = Yii::app()->request->getPost('id',0);
			if($id){
				$criteria->addSearchCondition('selfcode',$id);
			}
		}
		
		$criteria->order = ' lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		
		$pages = new CPagination(MemberCard::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = MemberCard::model()->findAll($criteria);
                //var_dump($models);exit;
		$this->render('index',array(
				'models'=>$models,
				'pages' => $pages,
				'id'=>$id,
		));
	}
	public function actionCreate() {
		$model = new MemberCard ;
		$model->dpid = $this->companyId ;
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('MemberCard');
			if($model->haspassword){
				$model->password_hash = MD5($model->password_hash);
			}
            $se=new Sequence("member_card");
            $model->lid = $se->nextval();
            $model->create_at = date('Y-m-d H:i:s',time());
            $model->update_at=date('Y-m-d H:i:s',time());
            $model->delete_flag = '0';
			if($model->save()) {
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('member/index' , 'companyId' => $this->companyId));
			}
		}
		$this->render('create' , array(
				'model' => $model , 
		));
	}
	public function actionUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		$model = MemberCard::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=>  $this->companyId));
		Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('MemberCard');
                        $model->update_at=date('Y-m-d H:i:s',time());
			if($model->haspassword){
				$model->password_hash = MD5($model->password_hash);
			}else{
				$model->password_hash = '';
			}
			if($model->save()){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('member/index' , 'companyId' => $this->companyId));
			}
		}
		$this->render('update' , array(
			'model'=>$model,
		));
	}
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$id = Yii::app()->request->getParam('id');
                Until::isUpdateValid(array($id),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($id)) {
				$model = MemberCard::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id , ':companyId' => $companyId)) ;
				if($model) {
					$model->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
				}
			$this->redirect(array('member/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('member/index' , 'companyId' => $companyId)) ;
		}
	}
	public function actionChargeRecord() {
		$id = Yii::app()->request->getParam('lid');
		$member = MemberCard::model()->find('lid=:lid and dpid=:dpid',array(':lid'=>$id,':dpid'=>$this->companyId));
		
		$criteria = new CDbCriteria;
		$criteria->addCondition('dpid=:dpid and delete_flag=0');
		$criteria->addCondition('member_card_id=:memberCardId');
		$criteria->order = ' lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		$criteria->params[':memberCardId']=$member->selfcode;
		
		$pages = new CPagination(MemberRecharge::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = MemberRecharge::model()->findAll($criteria);
		$this->render('chargerecord',array(
				'models'=>$models,
				'pages' => $pages,
		));
	}
	public function actionConsumerRecord() {
		$id = Yii::app()->request->getParam('lid');
		$member = MemberCard::model()->find('lid=:lid and dpid=:dpid',array(':lid'=>$id,':dpid'=>$this->companyId));
		
		$criteria = new CDbCriteria;
		$criteria->addCondition('dpid=:dpid and delete_flag=0');
		$criteria->addCondition('member_card_id=:memberCardId');
		$criteria->order = ' lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		$criteria->params[':memberCardId']=$member->selfcode;
		
		$pages = new CPagination(MemberConsumer::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = MemberConsumer::model()->findAll($criteria);
		$this->render('consumerrecord',array(
				'models'=>$models,
				'pages' => $pages,
		));
	}
	public function actionCharge() {
		$model = new MemberRecharge;
		$model->dpid = $this->companyId;
		//Until::validOperate($model->dpid, $this);
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('MemberRecharge');
			$rfid = Yii::app()->request->getPost('rfid');
			$transaction=Yii::app()->db->beginTransaction();
			try{
				$member = MemberCard::model()->find('rfid=:rfid and selfcode=:selfcode and dpid=:dpid',array(':rfid'=>$rfid,':selfcode'=>$model->member_card_id,':dpid'=>$this->companyId));
                                Until::validOperate($member->lid, $this);
                                //var_dump($member);exit;
                                $member->all_money = $member->all_money + $model->reality_money + $model->give_money;
	          
	            $se = new Sequence("member_recharge");
	            $model->lid = $se->nextval();
	            $model->update_at = date('Y-m-d H:i:s',time());
	            $model->create_at = date('Y-m-d H:i:s',time());
	            $model->delete_flag = '0';
	           if($model->save()&&$member->update()) {
	           		$transaction->commit();
					Yii::app()->user->setFlash('success',yii::t('app', '充值成功'));
				}else{
					$transaction->rollback();
					Yii::app()->user->setFlash('error',yii::t('app', '充值失败'));
				}
			}catch(Exception $e){
				Yii::app()->user->setFlash('error' ,yii::t('app', '充值失败'));
				$transaction->rollback();
			}
			$this->redirect(array('member/index','companyId'=>$this->companyId));
		}
		$this->renderPartial('charge' , array(
				'model' => $model , 
		));
	}
	public function actionGetMember() {
		$card = Yii::app()->request->getParam('card',0);
		$criteria = new CDbCriteria;
		$criteria->addCondition('dpid=:dpid and delete_flag=0');
		if($card){
			$criteria->addCondition('rfid=:card');
			$criteria->addCondition('selfcode=:card','OR');
			$criteria->addCondition('name=:card','OR');
			$criteria->addCondition('mobile=:card','OR');
			$criteria->params[':card']=$card;
		}
		
		$criteria->order = ' lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		
		$model = MemberCard::model()->find($criteria);
		if($model){
			$res = array('rfid'=>$model->rfid,'selfcode'=>$model->selfcode,'all_money'=>$model->all_money,'name'=>$model->name,'mobile'=>$model->mobile,'email'=>$model->email);
			Yii::app()->end(json_encode(array('status'=>true,'msg'=>$res)));
		}else{
			Yii::app()->end(json_encode(array('status'=>false,'msg'=>'没有查询到该会员信息')));
		}
		
	}
}