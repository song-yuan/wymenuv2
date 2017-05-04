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
//              print_r($_POST);exit;
		$this->layout = '/layouts/main_picture';
		$pid = Yii::app()->request->getParam('pid',0);
		$catetype = Yii::app()->request->getParam('catetype');
		$model = new ProductCategory() ;
		$model->dpid = $this->companyId ;
		if($pid) {
			$model->pid = $pid;
		}
		if($catetype) {
			$model->cate_type = $catetype;
		}
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductCategory');

//                        print_r($model->attributes);exit;
			if(empty($model->category_name)){
				Yii::app()->user->setFlash('error' ,yii::t('app', '类别名不能为空'));
				$this->redirect(array('productCategory/index' , 'companyId' => $this->companyId));
			}else{
			$category = ProductCategory::model()->find('dpid=:dpid and category_name=:name and delete_flag=0' , array(':dpid'=>  $this->companyId,':name'=>$model->category_name));
			//var_dump($category);var_dump('####');
			if($category){
				//var_dump(123);exit;
				Yii::app()->user->setFlash('error' ,yii::t('app', '该类别已添加'));
				$this->redirect(array('productCategory/index' , 'id'=>$category->lid,'companyId' => $this->companyId));
			}
			else{
				//Yii::app()->db->createCommand()->setText("lock tables {product_category} WRITE")->execute();
				$se=new Sequence("product_category");
				$lid = $se->nextval();
				$code=new Sequence("chs_code");
				$chs_code = $code->nextval();
				$model->lid = $lid;
				$model->chs_code = ProductCategory::getChscode($this->companyId,$lid, $chs_code);
				$model->create_at = date('Y-m-d H:i:s',time());
				$model->delete_flag = '0';
				$model->update_at = date('Y-m-d H:i:s',time());
				//$model->save();
				//Yii::app()->db->createCommand()->setText("unlock tables")->execute();
				//var_dump($model);var_dump('&&&&');
//				exit;
				if($model->save()){
					$self = ProductCategory::model()->find('lid=:pid and dpid=:dpid' , array(':pid'=>$model->lid,':dpid'=>  $this->companyId));
                                        if($self->pid!='0'){
						$parent = ProductCategory::model()->find('lid=:pid and dpid=:dpid' , array(':pid'=>$model->pid,':dpid'=>  $this->companyId));
						$self->tree = $parent->tree.','.$self->lid;
					} else {
						$self->tree = '0,'.$self->lid;
					}
                                      if(Yii::app()->request->getPost('ProductCategory2')){
                                            $category2 = Yii::app()->request->getPost('ProductCategory2');
                                            $category3 = Yii::app()->request->getPost('ProductCategory3');
//                                            print_r($category2);exit;
                                            $i=0;
                                            foreach($category2 as $cate2){
         
                                                $order = $category3[$i];
//                                                var_dump( $oreder);exit;
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
                                                if(empty($model1->category_name)){
                                                        Yii::app()->user->setFlash('error' ,yii::t('app', '类别名不能为空'));
                                                        $this->redirect(array('productCategory/index' , 'companyId' => $this->companyId));
                                                }else{
                                                    $category = ProductCategory::model()->find('dpid=:dpid and category_name=:name and delete_flag=0' , array(':dpid'=>  $this->companyId,':name'=>$model1->category_name));
                                                    //var_dump($category);var_dump('####');
                                                    if($category){
                                                            //var_dump(123);exit;
                                                            Yii::app()->user->setFlash('error' ,yii::t('app', '该类别已添加'));
                                                            $this->redirect(array('productCategory/index' , 'id'=>$category->lid,'companyId' => $this->companyId));
                                                    }
                                                }
                                                $model1->order_num = $order;
                                                $model1->tree = '0,'.$model1->pid.','. $model1->lid;
                                                $model1->save();
                                            }  
                                      }   
                                    $self->update();
                                    Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
                                    $this->redirect(array('productCategory/index' , 'id'=>$self->lid,'companyId' => $this->companyId));
				}	
			}
		}}
//                $se=new Sequence("product_category");
//                $lid = $se->nextval();
		$this->render('_form1' , array(
				'model' => $model,
				'action' => $this->createUrl('productCategory/create' , array('companyId'=>$this->companyId,'catetype' => $catetype,)),
		));
	}
	public function actionUpdate() {
		$this->layout = '/layouts/main_picture';
		$id = Yii::app()->request->getParam('id');
		//var_dump($id);exit;
		$model = ProductCategory::model()->find('lid=:id and dpid=:dpid', array(':id' => $id,':dpid'=>  $this->companyId));
        //Until::isUpdateValid(array($id),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
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
		$this->render('_form1' , array(
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
		$model = ProductCategory::model()->find('lid=:id and dpid=:companyId' , array(':id'=>$id,':companyId'=>$this->companyId));
		//var_dump($id,  $this->companyId,$model);exit;
		if($model&&$model->checkCategory()) {
			$model->deleteCategory();
			Yii::app()->user->setFlash('success',yii::t('app','删除成功！'));
		}else{
			Yii::app()->user->setFlash('error',yii::t('app','请先删除该分类下的产品！'));
		}
		
		$this->redirect(array('productCategory/index','companyId'=>$this->companyId,'id'=>$model->pid));
	}
	
	
	
}