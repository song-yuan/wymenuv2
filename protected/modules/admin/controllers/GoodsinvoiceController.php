<?php
class GoodsinvoiceController extends BackendController
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
		if(!$this->companyId && $this->getAction()->getId() != 'upload') {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionGoodsinvoice(){
		$gdid = Yii::app()->request->getParam('gdid');
		$db = Yii::app()->db;
		if($gdid){
			$sql = 'select k.* from (select c.company_name,t.* from nb_goods_invoice t left join nb_company c on(t.dpid = c.dpid) where t.dpid ='.$this->companyId.' and t.goods_delivery_id = '.$gdid.') k';
		}else{
			$sql = 'select k.* from (select c.company_name,t.* from nb_goods_invoice t left join nb_company c on(t.dpid = c.dpid) where t.dpid ='.$this->companyId.') k';
		}
		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();
	
		$this->render('goodsinvoice',array(
				'models'=>$models,
				'pages'=>$pages,
		));
		
	}
	public function actionDetailindex(){
		$goid = Yii::app()->request->getParam('lid');
		$name = Yii::app()->request->getParam('name');
		$papage = Yii::app()->request->getParam('papage');
		
		$db = Yii::app()->db;
		
		$sqls = 'select t.* from nb_goods_invoice t where t.lid ='.$goid;
		$model = $db->createCommand($sqls)->queryRow();
		
		$sqlstock = 'select t.* from nb_company t where t.type = 2 and t.comp_dpid ='.$this->companyId;
		$stocks = $db->createCommand($sqlstock)->queryAll();
		
		$sql = 'select k.* from (select c.goods_name,co.company_name as stock_name,t.* from nb_goods_invoice_details t left join nb_goods c on(t.goods_id = c.lid) left join nb_company co on(co.dpid = t.dpid ) where t.goods_invoice_id = '.$goid.' order by t.lid) k';
		//;
	
		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();
		//var_dump($models);exit;
	
		$this->render('detailindex',array(
				'models'=>$models,
				'model'=>$model,
				'stocks'=>$stocks,
				'pages'=>$pages,
				'papage'=>$papage,
				'name'=>$name
		));
	
	}

	public function actionStore(){
		$pid = Yii::app()->request->getParam('pid');
		//var_dump($pid);//exit;
		$dpid = $this->companyId;
		$db = Yii::app()->db;
		$transaction = $db->beginTransaction();
		try
		{
			$is_sync = DataSync::getInitSync();
			$db->createCommand('update nb_goods_invoice set status =1,update_at ="'.date('Y-m-d H:i:s',time()).'" where lid ='.$pid)
			->execute();
			
			$transaction->commit();
			Yii::app()->end(json_encode(array("status"=>"success",'msg'=>'成功')));
			//return true;
		}catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			Yii::app()->end(json_encode(array("status"=>"fail")));
			//return false;
		}
	}

	public function actionStorestock(){
		$name = Yii::app()->request->getParam('name');
		$nums = Yii::app()->request->getParam('nums');
		$gid = Yii::app()->request->getParam('gid');
		$type = Yii::app()->request->getParam('type');
		//var_dump($name);
		//var_dump($nums);
		//exit;
		$dpid = $this->companyId;
		$db = Yii::app()->db;
		$transaction = $db->beginTransaction();
		try
		{
			$is_sync = DataSync::getInitSync();
			$db->createCommand('update nb_goods_invoice set sent_type ='.$type.',sent_personnel="'.$name.'",mobile="'.$nums.'",update_at ="'.date('Y-m-d H:i:s',time()).'" where lid ='.$gid)
			->execute();
				
			$transaction->commit();
			Yii::app()->end(json_encode(array("status"=>"success",'msg'=>'成功')));
			//return true;
		}catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			Yii::app()->end(json_encode(array("status"=>"fail")));
			//return false;
		}
	}
	
	public function actionAddp(){
		$this->layout = '/layouts/main_picture';
		$gid = Yii::app()->request->getParam('gid',0);
		
		$db = Yii::app()->db;
		$sql ='select t.* from nb_goods_invoice t where t.lid ='.$gid.' and t.delete_flag =0 ';
		$models = $db->createCommand($sql)->queryAll();
	
		$sql2 = 'select t.* from nb_takeaway_member t where t.delete_flag =0 and t.dpid ='.$this->companyId.' or t.dpid in(select c.dpid from nb_company c where c.delete_flag =0 and c.comp_dpid ='.$this->companyId.')';
		$pers = $db->createCommand($sql2)->queryAll();
		
		$this->render('addp' , array(
				'models' => $models,
				'pers' => $pers,
				'gid'=>$gid,
				'action' => $this->createUrl('goodsinvoice/addp' , array('companyId'=>$this->companyId))
		));
	}
	
}