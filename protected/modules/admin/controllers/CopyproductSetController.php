<?php
class CopyproductSetController extends BackendController
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
		$categoryId = Yii::app()->request->getParam('cid',0);
		$criteria = new CDbCriteria;
		//$criteria->with = array('company','category');
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId;
		
		$models = ProductSet::model()->findAll($criteria);
		
		$db = Yii::app()->db;
		$sql = 'select t.dpid,t.company_name from nb_company t where t.delete_flag = 0 and t.comp_dpid = '.$this->companyId;
		$command = $db->createCommand($sql);
		$dpids = $command->queryAll();
		//var_dump($dpids);exit;
		$this->render('index',array(
				'models'=>$models,
				'dpids'=>$dpids,
		));
	}

	public function actionStorProductset(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$is_sync = DataSync::getInitSync();
		//var_dump($companyId);exit;
		$ids = Yii::app()->request->getPost('ids');
		$pshscode = Yii::app()->request->getParam('pshscode');
		$dpid = Yii::app()->request->getParam('dpids');
		
		$pshscodes = array();
		$pshscodes = explode(',',$pshscode);
		$dpids = array();
		$dpids = explode(',',$dpid);
		$msgnull = '下列产品暂无套餐，请添加后再进行下发操作：';
		$msgprod = '下列产品尚未下发至选择店铺，请先下发产品再下发配方：';
		//var_dump($dpids,$pshscodes);exit;
		
		//****查询公司的产品分类。。。****
		
		$db = Yii::app()->db;
		$sql = 'select t.* from nb_product t where t.delete_flag = 0 and t.dpid = '.$this->companyId;
		$command = $db->createCommand($sql);
		$products = $command->queryAll();
		//var_dump($catep1,$catep2,$products);exit;
        //        Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
        if((!empty($dpids))&&(Yii::app()->user->role < User::WAITER)){
        	$transaction = $db->beginTransaction();
        	try{
	        	foreach ($dpids as $dpid){
	
	        			foreach ($pshscodes as $prodsethscode){
	        				$prodsets = ProductSet::model()->find('pshs_code=:pscode and dpid=:companyId and delete_flag=0' , array(':pscode'=>$prodsethscode,':companyId'=>$this->companyId));
	        				$prodsetso = ProductSet::model()->find('pshs_code=:pscode and dpid=:companyId and delete_flag=0' , array(':pscode'=>$prodsethscode,':companyId'=>$dpid));
	        				if(!empty($prodsetso)){
	        					$prodsetso->delete_flag = 1;
	        					$prodsetso->update();
	        					Yii::app()->db->createCommand('update nb_product_set_detail set delete_flag=1 where set_id =:setid and dpid = :companyId')
	        					->execute(array(':setid'=> $prodsetso->lid, ':companyId' => $dpid));
	        					//Yii::app()->db->createCommand()->update('nb_product_set_detail',array('set_id=:setid' ,'dpid=:dpid'), array(':setid' =>$prodsetso->lid , ':dpid'=>$dpid));
	        				}
	        				//var_dump($prodsetso);exit;
	        				if(!empty($prodsets)){
	        					$se = new Sequence("porduct_set");
	        					$pslid = $se->nextval();
	        					$dataprodset = array(
	        							'lid'=>$pslid,
	        							'dpid'=>$dpid,
	        							'create_at'=>date('Y-m-d H:i:s',time()),
	        							'update_at'=>date('Y-m-d H:i:s',time()),
	        							'pshs_code'=>$prodsethscode,
	        							'set_name'=>$prodsets->set_name,
	        							'source'=>1,
	        							'type'=>$prodsets->type,
	        							'simple_code'=>$prodsets->simple_code,
	        							'main_picture'=>$prodsets->main_picture,
	        							'set_price'=>$prodsets->set_price,
	        							'member_price'=>$prodsets->member_price,
	        							'description'=>$prodsets->description,
	        							'rank'=>$prodsets->rank,
	        							'is_member_discount'=>$prodsets->is_member_discount,
	        							'is_special'=>$prodsets->is_special,
	        							'is_discount'=>$prodsets->is_discount,
	        							'status'=>$prodsets->status,
	        							'delete_flag'=>'0',
	        							'is_sync'=>$is_sync,
	        					);
	        					//var_dump($dataprodset);exit;
	        					$command = $db->createCommand()->insert('nb_product_set',$dataprodset);
	        					
	        					$prodsetdetails = ProductSetDetail::model()->findAll('set_id=:lid and dpid=:companyId and delete_flag=0' , array(':lid'=>$prodsets->lid,':companyId'=>$this->companyId));
	        					foreach ($prodsetdetails as $prodsetdetail){
	        						$producto = Product::model()->find('lid=:lid and dpid=:companyId and delete_flag=0' , array(':lid'=>$prodsetdetail->product_id,':companyId'=>$this->companyId));
	        						$product = Product::model()->find('phs_code=:pcode and dpid=:companyId and delete_flag=0' , array(':pcode'=>$producto->phs_code,'companyId'=>$dpid));
	        						//var_dump($product);exit;
	        						if(!empty($product)){
		        						$se = new Sequence("porduct_set_detail");
		        						$psdlid = $se->nextval();
		        						$dataprodsetdetail = array(
		        								'lid'=>$psdlid,
		        								'dpid'=>$dpid,
		        								'create_at'=>date('Y-m-d H:i:s',time()),
		        								'update_at'=>date('Y-m-d H:i:s',time()),
		        								'set_id'=>$pslid,
		        								'product_id'=>$product->lid,
		        								'price'=>$prodsetdetail->price,
		        								'group_no'=>$prodsetdetail->group_no,
		        								'number'=>$prodsetdetail->number,
		        								'is_select'=>$prodsetdetail->is_select,
		        								'delete_flag'=>'0',
		        								'is_sync'=>$is_sync,
		        						);
		        						//var_dump($dataprodsetdetail);exit;
		        						$command = $db->createCommand()->insert('nb_product_set_detail',$dataprodsetdetail);
	        						}
	        					}
	        					//var_dump($prodsetdetails);exit;
	        				}
	        					
	        			}
	        	}
        		$transaction->commit();
        		//Yii::app()->user->setFlash('success' , $msgmate);
        		Yii::app()->user->setFlash('success' , yii::t('app','套餐下发成功！！！'));
        		$this->redirect(array('copyproductset/index' , 'companyId' => $companyId)) ;
        		//echo 'true';exit;
        	}catch (Exception $e){
        		$transaction->rollback();
        		//echo 'false';exit;
        		Yii::app()->user->setFlash('eror' , yii::t('app','套餐下发失败！！！'));
        		$this->redirect(array('copyproductset/index' , 'companyId' => $companyId)) ;
        	}
        	
        }else{
        	Yii::app()->user->setFlash('error' , yii::t('app','无权限进行此项操作！！！'));
        	$this->redirect(array('copyproductset/index' , 'companyId' => $companyId)) ;
        }        

	}
	public function actionStatus(){
		$id = Yii::app()->request->getParam('id');
		$product = Product::model()->find('lid=:id and dpid=:companyId' , array(':id'=>$id,':companyId'=>$this->companyId));
		//var_dump($product->status);
		if($product){
			$product->saveAttributes(array('status'=>$product->status?0:1,'update_at'=>date('Y-m-d H:i:s',time())));
		}
		exit;
	}
	public function actionRecommend(){
		$id = Yii::app()->request->getParam('id');
		$product = Product::model()->find('lid=:id and dpid=:companyId' , array(':id'=>$id,':companyId'=>$this->companyId));
		
		if($product){
			$product->saveAttributes(array('recommend'=>$product->recommend==0?1:0,'update_at'=>date('Y-m-d H:i:s',time())));
		}
		exit;
	}
	private function getCategoryList(){
		$categories = ProductCategory::model()->findAll('delete_flag=0 and dpid=:companyId' , array(':companyId' => $this->companyId)) ;
		//var_dump($categories);exit;
		return CHtml::listData($categories, 'lid', 'category_name');
	}
	public function actionGetChildren(){
		$pid = Yii::app()->request->getParam('pid',0);
		if(!$pid){
			Yii::app()->end(json_encode(array('data'=>array(),'delay'=>400)));
		}
		$treeDataSource = array('data'=>array(),'delay'=>400);
		$categories = Helper::getCategories($this->companyId,$pid);
	
		foreach($categories as $c){
			$tmp['name'] = $c['category_name'];
			$tmp['id'] = $c['lid'];
			$treeDataSource['data'][] = $tmp;
		}
		Yii::app()->end(json_encode($treeDataSource));
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
	private function getDepartments(){
		$departments = Department::model()->findAll('company_id=:companyId',array(':companyId'=>$this->companyId)) ;
		return CHtml::listData($departments, 'department_id', 'name');
	}
	
}