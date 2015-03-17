<?php
class ProductCleanController extends BackendController
{
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId && $this->getAction()->getId() != 'upload') {
			Yii::app()->user->setFlash('error' , '请选择公司');
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex(){
		$sc = Yii::app()->request->getPost('csinquery');
                //var_dump($sc);exit;
		$db = Yii::app()->db;
                if(empty($sc))
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
		));
	}
	public function actionStatus(){
		$id = Yii::app()->request->getParam('id');
                $isset = Yii::app()->request->getParam('isset');
                $db = Yii::app()->db;
                $sql='';
                if($isset==0)
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
	
	
}