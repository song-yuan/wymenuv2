<?php
class CommitController extends BackendController
{
	public function actionIndex(){
		$id=0;
        $date=0;
		$criteria = new CDbCriteria;
		$criteria->addCondition('dpid=:dpid and delete_flag=0');
		if(Yii::app()->request->isPostRequest){
			$id = Yii::app()->request->getPost('id',0);
			if($id){
				$criteria->addSearchCondition('commit_account_no',$id);
			}
            $date = Yii::app()->request->getPost('date',0);
            if($date){
                $criteria->addSearchCondition('commit_date',$date);
            }
		}
		$criteria->order = ' lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		$pages = new CPagination(Commit::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = Commit::model()->findAll($criteria);
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
				'id'=>$id,
                'date'=>$date,
		));
	}
	public function actionCreate(){
		$model = new Commit();
		$model->dpid = $this->companyId ;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Commit');
			//var_dump($model);exit;
			$se=new Sequence("commit");
			$model->lid = $se->nextval();
			//$model->callout_id = '000000000';
			$model->create_at = date('Y-m-d H:i:s',time());
			$model->update_at = date('Y-m-d H:i:s',time());
			$model->commit_account_no = date('YmdHis',time()).substr('0000000000'.$model->lid,-4);
			$model->delete_flag = '0';
			//var_dump($model);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('Commit/index' , 'companyId' => $this->companyId ));
			}
		}
		$this->render('create' , array(
			'model' => $model ,
		));
	}
	
	public function actionUpdate(){
		$id = Yii::app()->request->getParam('lid');
		$model = Commit::model()->find('lid=:commitId and dpid=:dpid' , array(':commitId' => $id,':dpid'=>  $this->companyId));
		$model->dpid = $this->companyId;
		//Until::isUpdateValid(array($id),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Commit');
			$model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
				$this->redirect(array('Commit/index' , 'companyId' => $this->companyId ));
			}
		}
		$this->render('update' , array(
				'model' => $model ,
		));
	}
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
        //Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_commit set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('Commit/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('Commit/index' , 'companyId' => $companyId)) ;
		}
	}
	public function actionDetailIndex(){
		$categoryId = Yii::app()->request->getParam('cid',0);
        $clid = Yii::app()->request->getParam('lid');
        $status = Yii::app()->request->getParam('status');
        $commit = Commit::model()->find('lid=:id and dpid=:dpid',array(':id'=>$clid,':dpid'=>$this->companyId));
		$criteria = new CDbCriteria;
		$criteria->condition =  't.delete_flag = 0 and t.dpid='.$this->companyId .' and t.commit_id='.$clid;
		$pages = new CPagination(CommitDetail::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = CommitDetail::model()->findAll($criteria);
		//var_dump($categoryId);exit;
		$this->render('detailindex',array(
				'models'=>$models,
				'commit'=>$commit,
				'pages'=>$pages,
				'categoryId'=>$categoryId,
                'clid'=>$clid,
				'status'=>$status,
		));
	}
	public function actionSetMealList() {

	}
	public function actionDetailCreate(){
		$model = new CommitDetail();
		$model->dpid = $this->companyId ;
        $clid = Yii::app()->request->getParam('lid');
		$model->commit_id=$clid;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('CommitDetail');
			$se=new Sequence("commit_detail");
			$model->lid = $se->nextval();
			$model->create_at = date('Y-m-d H:i:s',time());
			$model->update_at = date('Y-m-d H:i:s',time());
			//  $model->delete_flag = '0';

			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('Commit/detailindex' , 'companyId' => $this->companyId,'lid'=>$model->commit_id ));
			}
		}
		$categories = $this->getCategories();
		$categoryId=0;
		$materials = $this->getMaterials($categoryId);
		$materialslist=CHtml::listData($materials, 'lid', 'material_name');
		$this->render('detailcreate' , array(
				'model' => $model ,
				'categories'=>$categories,
				'categoryId'=>$categoryId,
				'materials'=>$materialslist
		));
	}

	public function actionDetailUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		$model = CommitDetail::model()->find('lid=:materialId and dpid=:dpid' , array(':materialId' => $lid,':dpid'=>  $this->companyId));
		$model->dpid = $this->companyId;
		//Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('CommitDetail');
			$model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
				$this->redirect(array('Commit/detailindex' , 'companyId' => $this->companyId,'lid'=>$model->commit_id ));
			}
		}
		$categories = $this->getCategories();
		$categoryId=  $this->getCategoryId($lid);
		$materials = $this->getMaterials($categoryId);
		$materialslist=CHtml::listData($materials, 'lid', 'material_name');
		//var_dump($materialslist);exit;
		$this->render('detailupdate' , array(
				'model' => $model ,
				'categories'=>$categories,
				'categoryId'=>$categoryId,
				'materials'=>$materialslist
		));
	}
	public function actionDetailDelete(){
		$clid = Yii::app()->request->getParam('clid');
		$status = Yii::app()->request->getParam('status');
	
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
	
		Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_commit_detail set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('Commit/detailindex' , 'companyId' => $companyId,'lid'=>$clid,'status'=>$status, )) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('Commit/detailindex' , 'companyId' => $companyId,'lid'=>$clid,'status'=>$status, )) ;
		}
	}
	public function actionCommitVerify(){
		$pid = Yii::app()->request->getParam('pid');
		$type = Yii::app()->request->getParam('type');
		$commit = Commit::model()->find('lid=:id and dpid=:dpid and delete_flag=0',array(':id'=>$pid,':dpid'=>$this->companyId));
		$commit->status = $type;
		if($commit->update()){
			echo 'true';
		}else{
			echo 'false';
		}
		exit;
	}
	public function actionCommitOutlid(){
		$pid = Yii::app()->request->getParam('pid');
		$type = Yii::app()->request->getParam('type');
		$commit = Commit::model()->find('lid=:id and dpid=:dpid and delete_flag=0',array(':id'=>$pid,':dpid'=>$this->companyId));
		$callout_id = $commit->callout_id ;
		if($callout_id > 0){
			echo 'true';
		}else{
			echo 'false';
		}
		exit;
	}
	public function actionStorageOrder(){
		$pid = Yii::app()->request->getParam('pid');
		$commit = Commit::model()->find('lid=:id and dpid=:dpid and delete_flag=0',array(':id'=>$pid,':dpid'=>$this->companyId));
		if($commit){
			$transaction = Yii::app()->db->beginTransaction();
			try{
				$commit->status = 4;
				$commit->update();
				$model = new StorageOrder();
				$model->dpid = $this->companyId ;
					
				$se=new Sequence("storage_order");
				$model->lid = $se->nextval();
				$model->create_at = date('Y-m-d H:i:s',time());
				$model->update_at = date('Y-m-d H:i:s',time());
				$model->manufacturer_id = $commit->callout_id;
				$model->organization_id = $commit->callin_id;
				$model->admin_id = $commit->admin_id;
				$model->storage_account_no = date('YmdHis',time()).substr($model->lid,-4);
				$model->purchase_account_no = $commit->commit_account_no;
				$model->storage_date = $commit->commit_date;
				$model->remark = $commit->remark;
				$model->status = 1;
				// 入库订单
				$model->save();
				
				$commitDetails = CommitDetail::model()->findAll('dpid=:dpid and commit_id=:pid',array(':dpid'=>$this->companyId,':pid'=>$pid));

				foreach ($commitDetails as $detail){
					$modeldetail = new StorageOrderDetail();
					$modeldetail->dpid = $this->companyId ;
					$se=new Sequence("storage_order_detail");
					$modeldetail->lid = $se->nextval();
					$modeldetail->create_at = date('Y-m-d H:i:s',time());
					$modeldetail->update_at = date('Y-m-d H:i:s',time());
					$modeldetail->storage_id = $model->lid;
					$modeldetail->material_id = $detail->material_id;
					$modeldetail->stock = $detail->stock;
					$modeldetail->save();
					
				}
				$transaction->commit();
				echo 'true';
			}catch (Exception $e){
				$transaction->rollback();
				echo 'false';
			}
		}
		exit;
	}
	private function getCategories(){
		$criteria = new CDbCriteria;
		$criteria->with = 'company';
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId ;
		$criteria->order = ' t.lid asc ';

		$models = MaterialCategory::model()->findAll($criteria);

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
			$model = MaterialCategory::model()->find('t.lid = :lid and dpid=:dpid',array(':lid'=>$k,':dpid'=>  $this->companyId));
			$optionsReturn[$model->category_name] = $v;
		}
		return $optionsReturn;
	}
	private function getMaterials($categoryId){
		if($categoryId==0)
		{
			//var_dump ('2',$categoryId);exit;
			$materials = ProductMaterial::model()->findAll('dpid=:companyId and delete_flag=0' , array(':companyId' => $this->companyId));
			//var_dump($materials);exit;
		}else{
			//var_dump ('3',$categoryId);exit;
			$materials = ProductMaterial::model()->findAll('dpid=:companyId and category_id=:categoryId and delete_flag=0' , array(':companyId' => $this->companyId,':categoryId'=>$categoryId)) ;
		}
		$materials = $materials ? $materials : array();
		//var_dump($products);exit;
		return $materials;
		//return CHtml::listData($products, 'lid', 'product_name');
	}

	public function actionGetChildren(){
		$categoryId = Yii::app()->request->getParam('pid',0);
		//$productSetId = Yii::app()->request->getParam('$productSetId',0);
		if(!$categoryId){
			Yii::app()->end(json_encode(array('data'=>array(),'delay'=>400)));
		}

		$treeDataSource = array('data'=>array(),'delay'=>400);
		$produts=  $this->getMaterials($categoryId);

		foreach($produts as $c){
			$tmp['name'] = $c['material_name'];
			$tmp['id'] = $c['lid'];
			$treeDataSource['data'][] = $tmp;
		}
		Yii::app()->end(json_encode($treeDataSource));
	}
	private function getCategoryId($lid){
		$db = Yii::app()->db;
		$sql = "SELECT category_id from nb_commit_detail cd,nb_product_material pm where cd.dpid=pm.dpid and cd.material_id=pm.lid and cd.lid=:lid";
		$command=$db->createCommand($sql);
		$command->bindValue(":lid" , $lid);
		return $command->queryScalar();
	}
}









