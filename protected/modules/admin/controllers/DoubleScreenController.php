<?php
class DoubleScreenController extends BackendController
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
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex() {
		$type = Yii::app()->request->getParam('type',0);
		$criteria = new CDbCriteria;
		$criteria->addCondition('dpid=:dpid and delete_flag=0');
		$criteria->order = ' lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		
		$pages = new CPagination(DoubleScreen::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = DoubleScreen::model()->findAll($criteria);
		
		$this->render('index',array(
				'models'=>$models,
				'pages' => $pages,
				'type'=>$type
		));
	}
	public function actionCreate() {
		$type = Yii::app()->request->getParam('type',0);
		$model = new DoubleScreen ;
		$model->dpid = $this->companyId ;
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('DoubleScreen');
                        $se=new Sequence("double_screen");
                       
                        $model->lid = $se->nextval();
                        $code=new Sequence("phs_code");
			$phs_code = $code->nextval();
                        
                        $model->create_at = date('Y-m-d H:i:s',time());
                        $model->update_at = date('Y-m-d H:i:s',time());
                        $model->phs_code = ProductCategory::getChscode($this->companyId, $model->lid, $phs_code);
			$model->source = '0';
                        $model->is_able = '1';
                        $model->delete_flag = '0';
                        
//                        var_dump($model);exit;
			if($model->save()) {
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('doubleScreen/index' , 'companyId' => $this->companyId,'type'=>$type));
			}
		}
		$this->render('create' , array(
				'model' => $model , 
				'type' => $type
		));
	}
	public function actionUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		$type = Yii::app()->request->getParam('type');
        //Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		$model = DoubleScreen::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=>  $this->companyId));
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('DoubleScreen');
                        $model->update_at = date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('doubleScreen/index' , 'type'=>$type, 'companyId' => $this->companyId));
			}
		}
		$this->render('update' , array(
			'model'=>$model,
			'type' => $type
		));
	}
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('lid');
		$type = Yii::app()->request->getParam('type',0);
        //Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			foreach ($ids as $id) {
				$model = DoubleScreen::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id , ':companyId' => $companyId)) ;
				if($model) {
					$model->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
				}
			}
			$this->redirect(array('doubleScreen/index' , 'companyId' => $companyId,'type'=>$type)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('doubleScreen/index' , 'companyId' => $companyId,'type'=>$type)) ;
		}
	}
    public function actionDetailIndex() {
		$groupid = Yii::app()->request->getParam('groupid',0);
        $groupname = Yii::app()->request->getParam('groupname',0);
        $type = Yii::app()->request->getParam('type',0);
		$criteria = new CDbCriteria;
		$criteria->addCondition('dpid=:dpid and double_screen_id=:groupid and delete_flag=0');
		$criteria->order = ' lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		$criteria->params[':groupid']=$groupid; 
		
		$pages = new CPagination(DoubleScreenDetail::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = DoubleScreenDetail::model()->findAll($criteria);
		
		$this->render('detailIndex',array(
				'models'=>$models,
				'pages' => $pages,
                'groupid'=>$groupid,
                'groupname'=>$groupname,
				'type'=>$type
		));
	}
	public function actionDetailCreate() {
		$groupid = Yii::app()->request->getParam('groupid',0);
        $groupname = Yii::app()->request->getParam('groupname',0);
        $type = Yii::app()->request->getParam('type','0');
		$model = new DoubleScreenDetail();
		$model->dpid = $this->companyId ;		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('DoubleScreenDetail');
						$url2 = Yii::app()->request->getParam('url2');
						$types = $model->type;
						if($types == 1){
							$model->url = $url2;
						}
						//var_dump($model);exit;
                        $se=new Sequence("double_screen_detail");
                        $model->lid = $se->nextval();
                        $model->double_screen_id = $groupid ;
                        //$model->lid = $se->nextval();                        
                        $model->create_at = date('Y-m-d H:i:s',time());
                        $model->delete_flag = '0';
                   //var_dump($model);exit;
			if($model->save()) {
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('doubleScreen/detailIndex' , 'companyId' => $this->companyId,'groupname'=>$groupname,'groupid'=>$groupid,'type'=>$type));
			}
		}
		$this->render('detailCreate' , array(
				'model' => $model , 
                'groupid'=>$groupid,
                'groupname'=>$groupname,
				'type' => $type
		));
	}
	public function actionDetailUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		$type = Yii::app()->request->getParam('type');
        $groupname = Yii::app()->request->getParam('groupname',0);
        //Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		$model = DoubleScreenDetail::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=>  $this->companyId));
		
		if(Yii::app()->request->isPostRequest) {
			//$otherprice = $_POST['other_price'];
			$model->attributes = Yii::app()->request->getPost('DoubleScreenDetail');
			$url2 = Yii::app()->request->getParam('url2');
			$types = $model->type;
			if($types == 1){
				$model->url = $url2;
			}
            $model->update_at=date('Y-m-d H:i:s',time());
            //var_dump($model);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('doubleScreen/detailIndex' , 'type'=>$type,'groupname'=>$groupname,'groupid'=>$model->double_screen_id, 'companyId' => $this->companyId));
			}
		}
		$this->render('detailUpdate' , array(
			'model'=>$model,
            'groupid'=>$model->double_screen_id,
            'groupname'=>$groupname,
			'type' => $type
		));
	}
	public function actionDetailDelete(){
		//$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
                $groupid = Yii::app()->request->getParam('groupid',0);
                $groupname = Yii::app()->request->getParam('groupname',0);
		$ids = Yii::app()->request->getPost('lid');
		$type = Yii::app()->request->getParam('type',0);
        //Until::isUpdateValid($ids,$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			foreach ($ids as $id) {
				$model = DoubleScreenDetail::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id , ':companyId' => $this->companyId)) ;
				if($model) {
					$model->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
				}
			}
			$this->redirect(array('doubleScreen/detailIndex' , 'companyId' => $this->companyId,'groupname'=>$groupname,'groupid'=>$groupid,'type'=>$type)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('doubleScreen/detailIndex' , 'companyId' => $this->companyId,'groupname'=>$groupname,'groupid'=>$groupid,'type'=>$type)) ;
		}
	}

}