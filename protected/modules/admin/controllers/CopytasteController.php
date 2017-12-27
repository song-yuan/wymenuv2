<?php
class CopytasteController extends BackendController
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
		
		$models = TasteGroup::model()->findAll($criteria);
		
		$db = Yii::app()->db;
		//$sql = 'select t.dpid,t.company_name from nb_company t where t.delete_flag = 0 ';
		$sql = 'select t.dpid,t.type,t.company_name,t1.is_rest from nb_company t left join nb_company_property t1 on(t1.dpid = t.dpid) where t.delete_flag = 0 and t.type = 1 and t.comp_dpid = '.$this->companyId.' group by t.dpid';
		$command = $db->createCommand($sql);
		$dpids = $command->queryAll();
		//var_dump($dpids);exit;
		$this->render('index',array(
				'models'=>$models,
				'dpids'=>$dpids,
		));
	}

	public function actionStorTaste(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$is_sync = DataSync::getInitSync();
		//var_dump($companyId);exit;
		$ids = Yii::app()->request->getPost('ids');
		$pshscode = Yii::app()->request->getParam('tghscode');
		$dpid = Yii::app()->request->getParam('dpids');
		
		$pshscodes = array();
		$pshscodes = explode(',',$pshscode);
		$dpids = array();
		$dpids = explode(',',$dpid);
		//var_dump($dpids,$pshscodes);exit;
		
		//****查询公司的产品分类。。。****
		
		$db = Yii::app()->db;
		$sql = 'select t.* from nb_product t where t.delete_flag = 0 and t.dpid = '.$this->companyId;
		$command = $db->createCommand($sql);
		$products = $command->queryAll();
		
        if((!empty($dpids))&&(Yii::app()->user->role < User::SHOPKEEPER)){
        	$transaction = $db->beginTransaction();
        	try{
	        	foreach ($dpids as $dpid){
	
	        			foreach ($pshscodes as $prodsethscode){
	        				$tastegs = TasteGroup::model()->find('tghs_code=:tgcode and dpid=:companyId and delete_flag=0' , array(':tgcode'=>$prodsethscode,':companyId'=>$this->companyId));
	        				$tastegso = TasteGroup::model()->find('tghs_code=:tgcode and dpid=:companyId and delete_flag=0' , array(':tgcode'=>$prodsethscode,':companyId'=>$dpid));
	        				//var_dump($dpids,$pshscodes,$tastegs,$tastegso);exit;
	        				if(!empty($tastegso)){
	        					$tastegso->delete_flag = 1;
	        					$tastegso->update();
	        					Yii::app()->db->createCommand('update nb_taste set delete_flag=1 where taste_group_id =:tgid and dpid = :companyId')
	        					->execute(array(':tgid'=> $tastegso->lid, ':companyId' => $dpid));
	        					Yii::app()->db->createCommand('update nb_product_taste set delete_flag=1 where taste_group_id =:tgid and dpid = :companyId')
	        					->execute(array(':tgid'=> $tastegso->lid, ':companyId' => $dpid));
	        					//Yii::app()->db->createCommand()->update('nb_product_set_detail',array('set_id=:setid' ,'dpid=:dpid'), array(':setid' =>$prodsetso->lid , ':dpid'=>$dpid));
	        				}
	        				//var_dump($prodsetso);exit;
	        				if(!empty($tastegs)){
	        					$se = new Sequence("taste_group");
	        					$tglid = $se->nextval();
	        					$tgdata = array(
	        							'lid'=>$tglid,
	        							'dpid'=>$dpid,
	        							'create_at'=>date('Y-m-d H:i:s',time()),
	        							'update_at'=>date('Y-m-d H:i:s',time()),
	        							'name'=>$tastegs->name,
	        							'tghs_code'=>$prodsethscode,
	        							'source'=>1,
	        							'allflae'=>$tastegs->allflae,
	        							'delete_flag'=>'0',
	        							'is_sync'=>$is_sync,
	        					);
	        					//var_dump($dataprodset);exit;
	        					$command = $db->createCommand()->insert('nb_taste_group',$tgdata);
	        					
	        					//查询该口味分组下对应的单品，然后遍历插入数据库
	        					$prodtastes = ProductTaste::model()->findAll('taste_group_id=:lid and dpid=:companyId and delete_flag=0' , array(':lid'=>$tastegs->lid,':companyId'=>$this->companyId));
	        					foreach ($prodtastes as $prodtaste){
	        						$sql = 'select t.* from nb_product t where t.delete_flag = 0 and t.dpid ='.$dpid.' and t.phs_code = (select t1.phs_code from nb_product t1 where t1.dpid='.$this->companyId.' and t1.lid ='.$prodtaste->product_id.' and t1.delete_flag = 0) ';
	        						$command = $db->createCommand($sql);
	        						$prod = $command->queryRow();
	        						if(!empty($prod)){
	        							$se = new Sequence("product_taste");
	        							$ptlid = $se->nextval();
	        							$ptdata = array(
	        									'lid'=>$ptlid,
	        									'dpid'=>$dpid,
	        									'create_at'=>date('Y-m-d H:i:s',time()),
	        									'update_at'=>date('Y-m-d H:i:s',time()),
	        									'taste_group_id'=>$tglid,
	        									'product_id'=>$prod['lid'],
	        									'delete_flag'=>'0',
	        									'is_sync'=>$is_sync,
	        							);
	        							//var_dump($ptdata);exit;
	        							$command = $db->createCommand()->insert('nb_product_taste',$ptdata);
	        							
	        						}
	        						//var_dump($prod);exit;
	        					}
	        					//以上代码下发口味产品对应关系
	        					//var_dump($prodtastes);exit;
	        					$tastes = Taste::model()->findAll('taste_group_id=:lid and dpid=:companyId and delete_flag=0' , array(':lid'=>$tastegs->lid,':companyId'=>$this->companyId));
	        					
	        					foreach ($tastes as $taste){
	        						//$tasteori = Taste::model()->find('lid=:lid and dpid=:companyId and delete_flag=0' , array(':lid'=>$prodsetdetail->product_id,':companyId'=>$this->companyId));
	        						//$tastenew = Taste::model()->find('taste_group_id=:pcode and dpid=:companyId and delete_flag=0' , array(':pcode'=>$producto->phs_code,'companyId'=>$dpid));
	        						//var_dump($product);exit;
        						
	        						$se = new Sequence("taste");
	        						$tastelid = $se->nextval();
	        						$tastedata = array(
	        								'lid'=>$tastelid,
	        								'dpid'=>$dpid,
	        								'create_at'=>date('Y-m-d H:i:s',time()),
	        								'update_at'=>date('Y-m-d H:i:s',time()),
	        								'taste_group_id'=>$tglid,
	        								'name'=>$taste->name,
	        								'allflae'=>$taste->allflae,
	        								'price'=>$taste->price,
	        								'delete_flag'=>'0',
	        								'is_sync'=>$is_sync,
	        						);
	        						//var_dump($dataprodsetdetail);exit;
	        						$command = $db->createCommand()->insert('nb_taste',$tastedata);
	        					}
	        					//var_dump($prodsetdetails);exit;
	        				}	
	        			}
	        	}
        		$transaction->commit();
        		//Yii::app()->user->setFlash('success' , $msgmate);
        		Yii::app()->user->setFlash('success' , yii::t('app','口味下发成功！！！'));
        		$this->redirect(array('copytaste/index' , 'companyId' => $companyId)) ;
        		//echo 'true';exit;
        	}catch (Exception $e){
        		$transaction->rollback();
        		//echo 'false';exit;
        		Yii::app()->user->setFlash('eror' , yii::t('app','口味下发失败！！！'));
        		$this->redirect(array('copytaste/index' , 'companyId' => $companyId)) ;
        	}
        	
        }else{
        	Yii::app()->user->setFlash('error' , yii::t('app','无权限进行此项操作！！！'));
        	$this->redirect(array('copytaste/index' , 'companyId' => $companyId)) ;
        }        

	}

	
}