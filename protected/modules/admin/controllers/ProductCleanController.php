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
                $categoryId = Yii::app()->request->getParam('cid',"");
                $fromId = Yii::app()->request->getParam('from','sidebar');
                $csinquery=Yii::app()->request->getPost('csinquery',"");
                //var_dump($csinquery);exit;
                if($typeId=='product')
                {
                    
                    $criteria = new CDbCriteria;
                    $criteria->with = array('company','category');
                    $criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId ;
                    if(!empty($categoryId)){
                            $criteria->condition.=' and t.category_id = '.$categoryId;
                    }
                    
                    if(!empty($csinquery)){
                            $criteria->condition.=' and t.simple_code like "%'.strtoupper($csinquery).'%"';
                    }

                    $pages = new CPagination(Product::model()->count($criteria));
                    //	    $pages->setPageSize(1);
                    $pages->applyLimit($criteria);
                    $models = Product::model()->findAll($criteria);

                    $categories = $this->getCategories();
                    //var_dump($models);exit;
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
                    //var_dump($models);exit;
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
        
        public function actionStore(){
		$id = Yii::app()->request->getParam('id');
                $typeId = Yii::app()->request->getParam('typeId');
                $store_number = Yii::app()->request->getParam('storeNumber');
                $db = Yii::app()->db;
                
                $sql='';
                if($typeId=='product')
                {
                    $sql='update nb_product set store_number = '.$store_number.' where lid='.$id.' and dpid='.$this->companyId;
                }else{
                    $sql='update nb_product_set set store_number = '.$store_number.' where lid='.$id.' and dpid='.$this->companyId;
                }
                //var_dump($sql);exit;
                 
                    
		$command=$db->createCommand($sql);
                if($command->execute())
                {
                    Gateway::getOnlineStatus();
                    $store = Store::instance('wymenu');
                    $pads=Pad::model()->findAll(" dpid = :dpid and delete_flag='0' and pad_type in ('1','2')",array(":dpid"=>  $this->companyId));
                    //var_dump($pads);exit;
                    if(!empty($pads))
                    {
                        $sendjsondata=json_encode(array("company_id"=>  $this->companyId,
                            "do_id"=>"sell_off",
                            "do_data"=>array(array("product_id"=>$id,"type"=>$typeId,"num"=>$store_number)
                                //,array("product_id"=>$id,"type"=>$typeId,"num"=>$store_number)
                                )));
                        //var_dump($sendjsondata);exit;
                        foreach($pads as $pad)
                        {
                            $clientId=$store->get("padclient_".$this->companyId.$pad->lid);
                            //var_dump($clientId,$print_data);exit;
                            if(!empty($clientId))
                            {                            
                                Gateway::sendToClient($clientId,$sendjsondata);
                            }
                        }
                    }
                    Yii::app()->end(json_encode(array("status"=>"success")));
                }else{
                    Yii::app()->end(json_encode(array("status"=>"fail")));
                }
	}
        
        public function actionResetall(){
		$typeId = Yii::app()->request->getParam('typeId');
                $db = Yii::app()->db;
                
                $sql='';
                if($typeId=='product')
                {
                    $sql='update nb_product set store_number = -1 where dpid='.$this->companyId;
                }else{
                    $sql='update nb_product_set set store_number = -1 where dpid='.$this->companyId;
                }
                //var_dump($sql);exit;
                 
                    
		$command=$db->createCommand($sql);
                if($command->execute())
                {
                    Gateway::getOnlineStatus();
                    $store = Store::instance('wymenu');
                    $pads=Pad::model()->findAll(" dpid = :dpid and delete_flag='0' and pad_type in ('1','2')",array(":dpid"=>  $this->companyId));
                    //var_dump($pads);exit;
                    $sendjsondata=json_encode(array("company_id"=>  $this->companyId,
                        "do_id"=>"sell_off",
                        "do_data"=>array(array("product_id"=>$id,"type"=>$typeId,"num"=>$store_number)
                            //,array("product_id"=>$id,"type"=>$typeId,"num"=>$store_number)
                            )));
                    //var_dump($sendjsondata);exit;
                    foreach($pads as $pad)
                    {
                        $clientId=$store->get("padclient_".$this->companyId.$pad->lid);
                        //var_dump($clientId,$print_data);exit;
                        if(!empty($clientId))
                        {                            
                            Gateway::sendToClient($clientId,$sendjsondata);
                        }
                    }                                    
                    Yii::app()->end(json_encode(array("status"=>"success")));
                }else{
                    Yii::app()->end(json_encode(array("status"=>"fail")));
                }
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