<?php
class ProductCategoryController extends BackendController
{
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
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
	public function actionIndex(){
		//$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$criteria = new CDbCriteria;
		$criteria->with = 'company';
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId ;
		$criteria->order = ' order_num,tree,lid asc ';
		
		$models = ProductCategory::model()->findAll($criteria);
		
		$id = Yii::app()->request->getParam('id',0);
		$expandModel = ProductCategory::model()->find('lid=:id and dpid=:dpid and delete_flag=0',array(':id'=>$id,':dpid'=>  $this->companyId));
		
                $expandNode = $expandModel?explode(',',$expandModel->tree):array(0);
		//var_dump(substr('0000000000'.$expandNode[2],-10,10));exit;
		$this->render('index',array(
				'models'=>$models,
				'expandNode'=>$expandNode
		));
	}
	public function actionCreate() {
		$this->layout = '/layouts/main_picture';
		$msg = '';
		$pid = Yii::app()->request->getParam('pid',0);
		$catetype = Yii::app()->request->getParam('catetype');
		$model = new ProductCategory() ;
		$model->dpid = $this->companyId ;
        $companyId = Helper::genUsername(Yii::app()->request->getParam('companyId'));
        $db = Yii::app()->db;
		if($pid) {
			$model->pid = $pid;
		}
		if($catetype) {
			$model->cate_type = $catetype;
		}

		if(Yii::app()->request->isAjaxRequest){
			$hidden = Yii::app()->request->getParam('hidden');
				//echo $hidden;exit;
			if ($hidden==1) {
				$path = Yii::app()->basePath.'/../uploads/company_'.$this->companyId;
				$up = new CFileUpload();
				//设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
				$up -> set("path", $path);
				$up -> set("maxsize", 20*1024);
				$up -> set("allowtype", array("png", "jpg","jpeg"));
			
				if($up -> upload("file")) {
					$msg = '/wymenuv2/./uploads/company_'.$this->companyId.'/'.$up->getFileName();
					// $msg = '图片上传成功!!!';
				}else{
					$msg = $up->getErrorMsg();
				}
				echo $msg;exit;
			}
		}
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductCategory');
			$ctype = $model->cate_type;
			if(empty($model->category_name)){
				Yii::app()->user->setFlash('error' ,yii::t('app', '类别名不能为空'));
				$this->redirect(array('productCategory/index' , 'companyId' => $this->companyId));exit;
			}else{
			$category = ProductCategory::model()->find('dpid=:dpid and category_name=:name and delete_flag=0' , array(':dpid'=>  $this->companyId,':name'=>$model->category_name));
			//var_dump($category);var_dump('####');
			if($category){
				Yii::app()->user->setFlash('error' ,yii::t('app', '该类别已添加'));
				$this->redirect(array('productCategory/index' , 'id'=>$category->lid,'companyId' => $this->companyId));exit;
			}
			else{
// 				$transaction = $db->beginTransaction();
// 				try{

					$se=new Sequence("product_category");
					$lid = $se->nextval();
					$code=new Sequence("chs_code");
					$chs_code = $code->nextval();
					$model->lid = $lid;
					$model->chs_code = ProductCategory::getChscode($this->companyId,$lid, $chs_code);
					$model->create_at = date('Y-m-d H:i:s',time());
					$model->delete_flag = '0';
					$model->update_at = date('Y-m-d H:i:s',time());
					if($model->save()){
						$self = ProductCategory::model()->find('lid=:pid and dpid=:dpid' , array(':pid'=>$model->lid,':dpid'=>  $this->companyId));
	                    if($self->pid!='0'){
							$parent = ProductCategory::model()->find('lid=:pid and dpid=:dpid' , array(':pid'=>$model->pid,':dpid'=>  $this->companyId));
							$self->tree = $parent->tree.','.$self->lid;
						} else {
							$self->tree = '0,'.$self->lid;
						}
						$self->update();
						
                        	if(Yii::app()->request->getPost('ProductCategory2')){
	                        	$category2 = Yii::app()->request->getPost('ProductCategory2');
	                            $category3 = Yii::app()->request->getPost('ProductCategory3');
	                            $i=0;
	                            foreach($category2 as $cate2){
		         					$order = $category3[$i];
		                            $i++;
		                            $categoryName = $cate2['category_name'];
		                            $model1 = new ProductCategory() ;
		                            $model1->dpid = $this->companyId ;
		                            $se=new Sequence("product_category");          
		                            $lid1 = $se->nextval();
		                            $code=new Sequence("chs_code");
		                            $chs_code = $code->nextval();
		                            $model1->lid = $lid1;
		                            $model1->chs_code = ProductCategory::getChscode($this->companyId,$lid, $chs_code);
		                            $model1->create_at = date('Y-m-d H:i:s',time());
		                            $model1->delete_flag = '0';
		                            $model1->update_at = date('Y-m-d H:i:s',time());
		                            $model1->pid = $lid;
		                            $model1->category_name = $categoryName;
		                            $model1->cate_type = $ctype;
		                            if(empty($model1->category_name)){
			                            Yii::app()->user->setFlash('error' ,yii::t('app', '类别名不能为空'));
			                            $this->redirect(array('productCategory/index' , 'companyId' => $this->companyId));exit;
		                            }else{
		                            	$category = ProductCategory::model()->find('dpid=:dpid and category_name=:name and delete_flag=0' , array(':dpid'=>  $this->companyId,':name'=>$model1->category_name));
		                            	if($category){
				                            Yii::app()->user->setFlash('error' ,yii::t('app', '该类别已添加'));
				                            $this->redirect(array('productCategory/index' , 'id'=>$category->lid,'companyId' => $this->companyId));exit;
		                            	}
		                            }
		                            $model1->order_num = $order;
		                            $model1->tree = '0,'.$model1->pid.','. $model1->lid;
		                            $model1->save();
	                            }
                            }
                            Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
                            $this->redirect(array('productCategory/index' , 'id'=>$self->lid,'companyId' => $this->companyId));
					}
		
// 				$transaction->commit();
// 				}catch (Exception $e){
// 					$transaction->rollback();
// 					Yii::app()->user->setFlash('error' ,yii::t('app', '失败'));
// 					$dpidnames = ''.$dpid;
// 				}
			}
			
		}

	}

		$this->render('_form1' , array(
				'model' => $model,
                'user'  =>$companyId,
				'action' => $this->createUrl('productCategory/create' , array('companyId'=>$this->companyId,'catetype' => $catetype,)),
		));
	}
	public function actionUpdate() {
		$this->layout = '/layouts/main_picture';
		$msg = '';
		$id = Yii::app()->request->getParam('id');
		//var_dump($id);exit;
		$model = ProductCategory::model()->find('lid=:id and dpid=:dpid', array(':id' => $id,':dpid'=>  $this->companyId));
        //Until::isUpdateValid(array($id),$this->companyId,$this);//0,表示企业任何时候都在云端更新。

		if(Yii::app()->request->isAjaxRequest){
			$hidden = Yii::app()->request->getParam('hidden');
				//echo $hidden;exit;
			if ($hidden==1) {
				$path = Yii::app()->basePath.'/../uploads/company_'.$this->companyId;
				$up = new CFileUpload();
				//设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
				$up -> set("path", $path);
				$up -> set("maxsize", 20*1024);
				$up -> set("allowtype", array("png", "jpg","jpeg"));
			
				if($up -> upload("file")) {
					$msg = '/wymenuv2/./uploads/company_'.$this->companyId.'/'.$up->getFileName();
					// $msg = '图片上传成功!!!';
				}else{
					$msg = $up->getErrorMsg();
				}
				echo $msg;exit;
			}
		}

		if(Yii::app()->request->isPostRequest) {
			//var_dump($id);exit;
			$model->attributes = Yii::app()->request->getPost('ProductCategory');
                        $model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				//var_dump($model);exit;
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('productCategory/index' , 'id'=>$model->lid,'companyId' => $this->companyId));
			}
		}
		$this->render('_form2' , array(
				'model' => $model,
				'action' => $this->createUrl('productCategory/update' , array(
						'companyId'=>$this->companyId,
						'id'=>$model->lid
				))
		));
	}
	public function actionDelete(){
		$id = Yii::app()->request->getParam('id');
               // Until::isUpdateValid(array($id),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		$model = ProductCategory::model()->find('dpid=:companyId and (lid=:id or pid=:id)' , array(':id'=>$id,':companyId'=>$this->companyId));
		//var_dump($id,  $this->companyId,$model);exit;
		if($model&&$model->checkCategory()) {
			Yii::app()->db->createCommand('update nb_product_category set delete_flag=1 where dpid='.$this->companyId.' and (lid = '.$id.' or pid = '.$id.')')->execute();
			//$model->deleteCategory();
			Yii::app()->user->setFlash('success',yii::t('app','删除成功！'));
		}else{
			Yii::app()->user->setFlash('error',yii::t('app','请先删除该分类下的产品！'));
		}
		
		$this->redirect(array('productCategory/index','companyId'=>$this->companyId,'id'=>$model->pid));
	}
	
	
	
}