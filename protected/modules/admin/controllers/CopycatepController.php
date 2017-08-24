<?php
class CopycatepController extends BackendController
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

		$db = Yii::app()->db;
		$sql = 'select t.dpid,t.type,t.company_name,t.contact_name,t.mobile from nb_company t where t.delete_flag = 0 and type=1 and t.comp_dpid = '.$this->companyId;
		$command = $db->createCommand($sql);
		$dpids = $command->queryAll();
		// p($dpids);exit;
		$categories = $this->getCategories();
		$this->render('index',array(
				'dpids'=>$dpids,
		));
	}


	public function actionCatep(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$is_sync = DataSync::getInitSync();
		$dpids = Yii::app()->request->getPost('ids');
		// p($dpids);
		//****查询公司的产品分类。。。****
		$db = Yii::app()->db;
		$sql = 'select t.* from nb_product_category t where t.delete_flag = 0 and t.pid = 0 and t.dpid = '.$this->companyId;
		$command = $db->createCommand($sql);
		$catep1 = $command->queryAll();
		$db = Yii::app()->db;
		$sql = 'select t.* from nb_product_category t where t.delete_flag = 0 and t.pid != 0 and t.dpid = '.$this->companyId;
		$command = $db->createCommand($sql);
		$catep2 = $command->queryAll();
		$db = Yii::app()->db;

		//var_dump($catep1,$catep2,$products);exit;
        //Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
        $transaction = $db->beginTransaction();
         try{
			if((!empty($dpids))&&(Yii::app()->user->role < User::SHOPKEEPER)){
	        	foreach ($dpids as $dpid){
	        		if(!empty($catep1)){
	        			foreach($catep1 as $category){
	        				$catep = ProductCategory::model()->find('chs_code=:ccode and dpid=:companyId and delete_flag =0' , array(':ccode'=>$category['chs_code'],':companyId'=>$dpid));
	        				if($catep){
	        					$catep->update_at = date('Y-m-d H:i:s',time());
	        					$catep->category_name = $category['category_name'];
	        					$catep->type = $category['type'];
	        					$catep->cate_type = $category['cate_type'];
	        					$catep->show_type = $category['show_type'];
	        					$catep->chs_code = $category['chs_code'];
	        					$catep->main_picture = $category['main_picture'];
	        					$catep->order_num = $category['order_num'];
	        					$catep->update();
	        				}else{
		                        $se = new Sequence("product_category");
		                        $id = $se->nextval();
		                        $data = array(
		                        		'lid'=>$id,
		                        		'dpid'=>$dpid,
		                        		'create_at'=>date('Y-m-d H:i:s',time()),
		                        		'update_at'=>date('Y-m-d H:i:s',time()),
		                        		'category_name'=>$category['category_name'],
		                        		'pid'=>"0",
		                        		'type'=>$category['type'],
		                        		'cate_type'=>$category['cate_type'],
	                        			'show_type'=>$category['show_type'],
		                        		'chs_code'=> $category['chs_code'],
		                        		'main_picture'=>$category['main_picture'],
		                        		'order_num'=>$category['order_num'],
		                        		'delete_flag'=>'0',
		                        		'is_sync'=>$is_sync,
		                        );
		                        $command = $db->createCommand()->insert('nb_product_category',$data);

		                        	//var_dump(mysql_query($command));exit;
		                        	//var_dump($model);exit;
	 	                        	$self = ProductCategory::model()->find('lid=:pid and dpid=:dpid and delete_flag=0' , array(':pid'=>$id,':dpid'=>$dpid ));
	 	                        	//var_dump($self);exit;
		                        	if($self->pid!='0'){
		                        		$parent = ProductCategory::model()->find('lid=:pid and dpid=:dpid and delete_flag=0' , array(':pid'=>$model->pid,':dpid'=> $dpid));
		                        		$self->tree = $parent->tree.','.$self->lid;
		                        	} else {
		                        		$self->tree = '0,'.$self->lid;
		                        	}
		                        	$self->update();
	        				}
	        			}
		    			if(!empty($catep2)){
		    				foreach ($catep2 as $category){
		    					$sql = 'select t.chs_code,t.tree from nb_product_category t where t.delete_flag =0 and t.lid='.$category['pid'].' and t.dpid='.$this->companyId;
		    					$command = $db->createCommand($sql);
		    					$sqltree = $command->queryRow();
		    					$chscode = $sqltree['chs_code'];
		    					//$chscodetree = $sqltree['tree'];
		    					$catep = ProductCategory::model()->find('chs_code=:ccode and dpid=:companyId and delete_flag=0' , array(':ccode'=>$category['chs_code'],':companyId'=>$dpid));
		    					$cateptree = ProductCategory::model()->find('chs_code=:ccode and dpid=:companyId and delete_flag=0' , array(':ccode'=>$chscode,':companyId'=>$dpid));
		    					//var_dump($cateptree,$sqltree);exit;
		    					if($catep){
		    						$catep->update_at = date('Y-m-d H:i:s',time());
		    						$catep->category_name = $category['category_name'];
		    						$catep->type = $category['type'];
		    						$catep->cate_type = $category['cate_type'];
		    						$catep->chs_code = $category['chs_code'];
		    						$catep->main_picture = $category['main_picture'];
		    						$catep->order_num = $category['order_num'];
		    						$catep->update();
		    					}else{//var_dump($catep);exit;

		    						$se = new Sequence("product_category");
		    						$id = $se->nextval();
		    						$datacate = array(
		    								'lid'=>$id,
		    								'dpid'=>$dpid,
		    								'create_at'=>date('Y-m-d H:i:s',time()),
		    								'update_at'=>date('Y-m-d H:i:s',time()),
		    								'category_name'=>$category['category_name'],
		    								'pid'=>$cateptree['lid'],
		    								'type'=>$category['type'],
		    								'cate_type'=>$category['cate_type'],
		    								'chs_code'=> $category['chs_code'],
		    								'main_picture'=>$category['main_picture'],
		    								'order_num'=>$category['order_num'],
		    								'delete_flag'=>'0',
		    								'is_sync'=>$is_sync,
		    						);
		    						$command = $db->createCommand()->insert('nb_product_category',$datacate);

		    						$self = ProductCategory::model()->find('lid=:pid and dpid=:dpid and delete_flag=0' , array(':pid'=>$id,':dpid'=>$dpid ));
		    						if($self->pid!='0'){
		    							$self->tree = $cateptree['tree'].','.$self->lid;
		    						} else {
		    							$self->tree = '0,'.$self->lid;
		    						}
		    						$self->update();
		    					}
		    				}
		    			}
	        		}
				}
			}
            $transaction->commit();
            Yii::app()->user->setFlash('success' , yii::t('app','分类下发成功！！！'));
            $this->redirect(array('copycatep/index' , 'companyId' => $this->  companyId,)) ;
        }catch (Exception $e){
            $transaction->rollback();
            Yii::app()->user->setFlash('error' , yii::t('app','分类下发失败！！！'));
            $this->redirect(array('copycatep/index' , 'companyId' => $this->  companyId,)) ;
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
?>