<?php
class ProductCleanController extends BackendController
{
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId && $this->getAction()->getId() != 'upload') {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex(){
		//$sc = Yii::app()->request->getPost('csinquery');
                $typeId = Yii::app()->request->getParam('typeId');
                $categoryId = Yii::app()->request->getParam('cid',0);
                $fromId = Yii::app()->request->getParam('from','sidebar');
                if($typeId=='product')
                {
                    
                    $criteria = new CDbCriteria;
                    $criteria->with = array('company','category');
                    $criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId ;
                    if($categoryId){
                            $criteria->condition.=' and t.category_id = '.$categoryId;
                    }

                    $pages = new CPagination(Product::model()->count($criteria));
                    //	    $pages->setPageSize(1);
                    $pages->applyLimit($criteria);
                    $models = Product::model()->findAll($criteria);

                    $categories = $this->getCategories();

                    $this->render('index',array(
                                    'models'=>$models,
                                    'pages'=>$pages,
                                    'categories'=>$categories,
                                    'categoryId'=>$categoryId,
                                    'typeId' => $typeId
                    ));
                }else{
                    $criteria = new CDbCriteria;
                    $criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId ;
                    $pages = new CPagination(ProductSet::model()->count($criteria));
                    $pages->applyLimit($criteria);
                    $models = ProductSet::model()->findAll($criteria);
                    //var_dump($typeId);exit;
                    $this->render('index',array(
                                    'models'=>$models,
                                    'pages'=>$pages,
                                    'typeId' => $typeId
                    ));
                }
                 
                //var_dump($sc);exit;
		//$db = Yii::app()->db;
                /*if(empty($sc))
                {
                    $sql = "SELECT 0 as isset,lid,dpid,product_name as name,simple_code as cs,main_picture as pic , status from nb_product where delete_flag=0 and is_show=1 and dpid=".$this->companyId
                            . " union ".
                            "SELECT 1 as isset,lid,dpid,set_name as name,simple_code as cs,main_picture as pic ,status from nb_product_set where delete_flag=0 and dpid=".$this->companyId
                           ;
                }else{
                    $sql = "SELECT 0 as isset,lid,dpid,product_name as name,simple_code as cs,main_picture as pic , status from nb_product where delete_flag=0 and is_show=1 and dpid=".$this->companyId." and simple_code like '%".$sc."%'"
                            . " union ".
                            "SELECT 1 as isset,lid,dpid,set_name as name,simple_code as cs,main_picture as pic ,status from nb_product_set where delete_flag=0 and dpid=".$this->companyId." and simple_code like '%".$sc."%'"
                           ;
                }
                $command=$db->createCommand($sql);
                //$command->bindValue(":table" , $this->table);
                $models= $command->queryAll();
		//var_dump($models);exit;
                $criteria = new CDbCriteria;
		$pages = new CPagination(count($models));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages
		));*/
	}
	public function actionStatus(){
		$id = Yii::app()->request->getParam('id');
                $typeId = Yii::app()->request->getParam('typeId');
                $db = Yii::app()->db;
                $sql='';
                if($typeId=='product')
                {
                    $sql='update nb_product set status = not status where lid='.$id.' and dpid='.$this->companyId;
                }else{
                    $sql='update nb_product_set set status = not status where lid='.$id.' and dpid='.$this->companyId;
                }
                //var_dump($sql);exit;
		$command=$db->createCommand($sql);
                $command->execute();
                //save to product_out
		exit;
	}
	
	private function getCategories(){
		$criteria = new CDbCriteria;
		$criteria->with = 'company';
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId ;
		$criteria->order = ' tree,t.lid asc ';
		
		$models = ProductCategory::model()->findAll($criteria);
                
		//return CHtml::listData($models, 'lid', 'category_name','pid');
		$options = array();
		$optionsReturn = array(yii::t('app','--请选择分类--'));
		if($models) {
			foreach ($models as $model) {
				if($model->pid == '0') {
					$options[$model->lid] = array();
				} else {
					$options[$model->pid][$model->lid] = $model->category_name;
				}
			}
                        //var_dump($options);exit;
		}
		foreach ($options as $k=>$v) {
                    //var_dump($k,$v);exit;
			$model = ProductCategory::model()->find('t.lid = :lid and dpid=:dpid',array(':lid'=>$k,':dpid'=>  $this->companyId));
			$optionsReturn[$model->category_name] = $v;
		}
		return $optionsReturn;
	}
}