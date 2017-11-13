<?php
class MaterialAdController extends BackendController
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
		if(!$this->companyId && $this->getAction()->getId() != 'upload') {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}

	public function actionIndex(){
		$criteria = new CDbCriteria;
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId;
		$criteria->order = 't.sort asc,t.lid asc';
		$pages = new CPagination(MaterialAd::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = MaterialAd::model()->findAll($criteria);
		// var_dump($models);exit;
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
		));
	}

	public function actionCreate(){
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('materialAd/index' , 'companyId' => $this->companyId)) ;
		}
		$msg = '';
		$model = new MaterialAd();
		$model->dpid = $this->companyId;

		if(Yii::app()->request->isAjaxRequest){
			$path = Yii::app()->basePath.'/../uploads/company_'.$this->companyId;
			$up = new CFileUpload();
			//设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
			$up -> set("path", $path);
			$up -> set("maxsize", 40*1024);
			$up -> set("allowtype", array("png", "jpg","jpeg"));

			if($up -> upload("file")) {
				$msg = '/wymenuv2/./uploads/company_'.$this->companyId.'/'.$up->getFileName();
				// $msg = '图片上传成功!!!';
			}else{
				$msg = $up->getErrorMsg();
			}
			echo $msg;exit;
		}
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('MaterialAd');
			$materialAd = Yii::app()->request->getPost('MaterialAd');

			if(!empty($materialAd['name'])){
				$se=new Sequence("material_ad");
				$lid = $se->nextval();
				$model->lid = $lid;
				$model->create_at = date('Y-m-d H:i:s',time());
				$model->update_at = date('Y-m-d H:i:s',time());
				$model->delete_flag = '0';
				//var_dump($model);exit;
				// p($model);
				if($model->insert()){
				// if($model->save()){
					Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
					$this->redirect(array('materialAd/index' , 'companyId' => $this->companyId ));
				}
			}else{
				Yii::app()->user->setFlash('error',yii::t('app','添加数据有误,请重新添加！'));
				$this->redirect(array('materialAd/create' , 'companyId' => $this->companyId ));
			}
		}

		$this->render('create' , array(
			'model' => $model ,
		));
	}

	public function actionUpdate(){
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('materialAd/index' , 'companyId' => $this->companyId)) ;
		}
		$msg = '';
		if(Yii::app()->request->isAjaxRequest){
			$path = Yii::app()->basePath.'/../uploads/company_'.$this->companyId;
			$up = new CFileUpload();
			//设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
			$up -> set("path", $path);
			$up -> set("maxsize", 40*1024);
			$up -> set("allowtype", array("png", "jpg","jpeg"));

			if($up -> upload("file")) {
				$msg = '/wymenuv2/./uploads/company_'.$this->companyId.'/'.$up->getFileName();
			}else{
				$msg = $up->getErrorMsg();
			}
			echo $msg;exit;
		}
		$id = Yii::app()->request->getParam('id');
		$model = MaterialAd::model()->find('lid=:lid and dpid=:dpid' , array(':lid' => $id,':dpid'=>  $this->companyId));
		// p($model);
		$model->dpid = $this->companyId;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('MaterialAd');
			$materialAd = Yii::app()->request->getPost('MaterialAd');
			$model->update_at=date('Y-m-d H:i:s',time());
			if($model->update()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('materialAd/index' , 'companyId' => $this->companyId ));
			}
		}
		$this->render('update' , array(
			'model' => $model ,
		));
	}

	public function actionDelete(){
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('materialAd/index' , 'companyId' => $this->companyId)) ;
		}
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
		// p($ids);
		if(!empty($ids)) {
			$info = Yii::app()->db->createCommand('update nb_material_ad set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			if ($info) {
				Yii::app()->user->setFlash('success' , yii::t('app','删除成功'));
				$this->redirect(array('materialAd/index' , 'companyId' => $companyId));
			}
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('materialAd/index' , 'companyId' => $companyId));
		}
	}


	public function actionShow(){
		$lid = Yii::app()->request->getParam('lid');//原料广告表的lid编号
		$is_show = Yii::app()->request->getParam('is_show');

		if(Yii::app()->request->isAjaxRequest){
			$model = MaterialAd::model()->find('lid=:lid and dpid=:dpid' , array(':lid' => $lid,':dpid'=>  $this->companyId));
			$model->is_show =$is_show;
			$model->update_at=date('Y-m-d H:i:s',time());
			if($model->update()){
				echo json_encode(1);exit;
			}else{
				echo json_encode(0);exit;
			}
			// P($model);
		}
	}



}