<?php
class ProductImgController extends BackendController
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
	public function actionindex(){
		
		$criteria = new CDbCriteria;
		//$criteria->with = array('productImg');
		$criteria->addCondition('t.dpid=:dpid and t.is_show=1 and t.delete_flag=0');
		
		$criteria->order = ' t.lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		
		$pages = new CPagination(Product::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = Product::model()->findAll($criteria);
		$this->render('productImg',array(
				'models'=>$models,
				'pages' => $pages,
		));

	}
	public function actionUpdate(){
		$pictures = array();
		$lid = Yii::app()->request->getParam('lid');
		$papage = Yii::app()->request->getParam('papage');
        //Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		$criteria = new CDbCriteria;
		$criteria->with='productImg';
		$criteria->addCondition('t.lid=:lid and t.dpid=:dpid and t.delete_flag=0 ');
		$criteria->order = ' t.lid desc ';
		$criteria->params[':lid']=$lid;
		$criteria->params[':dpid']=$this->companyId;
		$model = Product::model()->find($criteria);
                
		if(Yii::app()->request->isPostRequest) {
			$postData = Yii::app()->request->getPost('productImg');
			if(ProductPicture::saveImg($this->companyId,$lid,$postData)){
				Yii::app()->user->setFlash('success' , yii::t('app','修改成功'));
				$this->redirect(array('productImg/index' , 'companyId' => $this->companyId, 'page'=>$papage));
			}
		}
		if(!empty($model->productImg)){
			foreach($model->productImg as $pic){
				array_push($pictures,$pic->pic_path);
			}
		}
		
		$this->render('updateProductImg' , array(
			'model'=>$model,
			'pictures'=>$pictures,
			'papage'=>$papage,
		));
	}
}