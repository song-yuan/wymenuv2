<?php
class CopyinstructController extends BackendController
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
		$criteria = new CDbCriteria;
		$criteria->condition =  'dpid='.$this->companyId.' and delete_flag=0';
		$models = Instruction::model()->findAll($criteria);
		
		$dpids = WxCompany::getCompanyChildren($this->companyId);
		$this->render('index',array(
				'models'=>$models,
				'dpids'=>$dpids,
		));
	}

	public function actionStoreInstruct(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
		$pshscode = Yii::app()->request->getParam('tghscode');
		$dpid = Yii::app()->request->getParam('dpids');
		
		$pshscodes = array();
		$pshscodes = explode(',',$pshscode);
		$dpids = array();
		$dpids = explode(',',$dpid);
        if((!empty($dpids))&&(Yii::app()->user->role < User::SHOPKEEPER)){
        	$transaction = Yii::app()->db->beginTransaction();
        	try{
        		$sql = 'select * from nb_instruction where dpid='.$this->companyId.' and phs_code in ('. $pshscode .') and delete_flag=0';
        		$instructs = Yii::app()->db->createCommand($sql)->queryAll();
        		foreach ($instructs as $instruct){
        			$phsCode = $instruct['phs_code'];
        			$phsName = $instruct['instruct_name'];
        			$source = 1;
        			$hasdetail = false;
        			$hasproins = false;
        			$sqlInstruct = 'insert into nb_instruction (lid,dpid,create_at,phs_code,source,instruct_name) values ';
        			$sqlIndetail = 'insert into nb_instruction_detail (lid,dpid,create_at,instruction_id,number,instruct_name,time,instruct,sort) values ';
        			$sqlproInstruct = 'insert into nb_product_instruction (lid,dpid,create_at,instruction_id,product_id,is_taste) values ';
        			
        			$sql = 'select * from nb_instruction_detail where dpid='.$instruct['dpid'].' and instruction_id='.$instruct['lid'].' and delete_flag=0';
        			$instructDetail = Yii::app()->db->createCommand($sql)->queryAll();
        			
        			$sql = 'select * from nb_product_instruction where dpid='.$instruct['dpid'].' and instruction_id='.$instruct['lid'].' and delete_flag=0';
        			$productInstruct = Yii::app()->db->createCommand($sql)->queryAll();
	        		foreach ($dpids as $dpid){
        				$sql = 'select * from nb_instruction where dpid='.$dpid.' and phs_code="'.$phsCode.'" and delete_flag=0';
        				$dpinstruct = Yii::app()->db->createCommand($sql)->queryRow();
        				if(!empty($dpinstruct)){
        					$sql = 'update nb_instruction set delete_flag=1 where lid='.$dpinstruct['lid'].' and dpid='.$dpid;
        					Yii::app()->db->createCommand($sql)->execute();
        					$sql = 'update nb_instruction_detail set delete_flag=1 where dpid='.$dpid.' and instruction_id='.$dpinstruct['lid'];
        					Yii::app()->db->createCommand($sql)->execute();
        				}
        				$createAt = date('Y-m-d H:i:s',time());
        				$se = new Sequence("instruction");
        				$lid = $se->nextval();
        				$sqlInstruct .= '('.$lid.','.$dpid.',"'.$createAt.'","'.$phsCode.'",'.$source.',"'.$phsName.'"),';
        				foreach ($instructDetail as $detail){
        					$hasdetail = true;
        					$se = new Sequence("instruction_detail");
        					$dlid = $se->nextval();
        					$sqlIndetail .= '('.$dlid.','.$dpid.',"'.$createAt.'",'.$lid.',"'.$detail['number'].'","'.$detail['instruct_name'].'","'.$detail['time'].'","'.$detail['instruct'].'","'.$detail['sort'].'"),';
        				}
        				foreach ($productInstruct as $proInstruct){
        					$sql = 'select phs_code from nb_product where lid='.$proInstruct['product_id'].' and dpid='.$instruct['dpid'];
        					$phscode = Yii::app()->db->createCommand($sql)->queryScalar();
        					$sql = 'select lid from nb_product where dpid='.$dpid.' and phs_code="'.$phscode.'" and delete_flag=0';
        					$productId = Yii::app()->db->createCommand($sql)->queryScalar();
        					if(!empty($productId)){
        						$hasproins = true;
        						$se = new Sequence("product_instruction");
        						$pid = $se->nextval();
        						$sqlproInstruct .= '('.$pid.','.$dpid.',"'.$createAt.'",'.$lid.','.$productId.',"'.$proInstruct['is_taste'].'"),';
        					}
        				}	
        			}
        			$sqlInstruct = rtrim($sqlInstruct,',');
        			Yii::app()->db->createCommand($sqlInstruct)->execute();
        			if($hasdetail){
        				$sqlIndetail = rtrim($sqlIndetail,',');
        				Yii::app()->db->createCommand($sqlIndetail)->execute();
        			}
        			if($hasproins){
        				$sqlproInstruct = rtrim($sqlproInstruct,',');
        				Yii::app()->db->createCommand($sqlproInstruct)->execute();
        			}
	        	}
        		$transaction->commit();
        		Yii::app()->user->setFlash('success' , yii::t('app','口味下发成功！！！'));
        		$this->redirect(array('copyinstruct/index' , 'companyId' => $companyId)) ;
        	}catch (Exception $e){
        		$transaction->rollback();
        		Yii::app()->user->setFlash('eror' , yii::t('app','口味下发失败！！！'));
        		$this->redirect(array('copyinstruct/index' , 'companyId' => $companyId)) ;
        	}
        }else{
        	Yii::app()->user->setFlash('error' , yii::t('app','无权限进行此项操作！！！'));
        	$this->redirect(array('copyinstruct/index' , 'companyId' => $companyId)) ;
        }        
	}
}