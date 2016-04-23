<?php
class ProductBomController extends BackendController
{
	public function actionIndex(){
		//$pbId=Yii::app()->request->getParam('lid');
		$criteria = new CDbCriteria;
		//$criteria->with=array('product');
		$criteria->order='t.dpid desc';
		$criteria->condition =  't.dpid='.$this->companyId .' and t.delete_flag=0 ';
		$pages = new CPagination(Product::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);

		$models = Product::model()->findAll($criteria);
		//var_dump($models);
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages
		));
	}

	public function actionDetailIndex(){
		$pblid = Yii::app()->request->getParam('pblid');
		$criteria = new CDbCriteria;
		$criteria->condition =  't.dpid='.$this->companyId .' and t.product_id='.$pblid.' and t.delete_flag=0';
		$pages = new CPagination(ProductBom::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = ProductBom::model()->findAll($criteria); //var_dump($models);exit;
		$this->render('detailindex',array(
				'models'=>$models,
				'pages'=>$pages,
				'pblid'=>$pblid,
		));
	}
	public function actionDetailCreate(){
		$categoryId = Yii::app()->request->getParam('cid',0);
		$pblid = Yii::app()->request->getParam('lid');
		//var_dump($pblid);exit;
		$model = new ProductBom();
		$model->dpid = $this->companyId ;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductBom');
			$se=new Sequence("product_bom");
			$model->lid = $se->nextval();
            $model->product_id = $pblid;
			$model->create_at = date('Y-m-d H:i:s',time());
			$model->delete_flag = '0';
            //var_dump($model);exit;
			if($model->save()) {
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('productBom/detailindex','companyId' => $this->companyId,'pblid'=>$pblid));
			}
		}
		$categories = $this->getCategories();
		$categoryId=0;
		$materials = $this->getMaterials($categoryId);
		$materialslist=CHtml::listData($materials, 'lid', 'material_name');
		$this->render('detailcreate' , array(
				'model' => $model,
				'pblid'=>$pblid,
				'categories'=>$categories,
				'categoryId'=>$categoryId,
				'materials'=>$materialslist
		));
	}
	public function actionDetailUpdate(){
        $pblid = Yii::app()->request->getParam('pblid');
        $lid = Yii::app()->request->getParam('lid');
      // var_dump($pblid);exit;
		$model = ProductBom::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));
		//var_dump($model);exit;
		Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductBom');
			$model->update_at = date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('ProductBom/detailindex' , 'companyId' => $this->companyId,'pblid'=>$pblid));
			}
		}
        $categories = $this->getCategories();
		$categoryId=  $this->getCategoryId($lid);
        $materials = $this->getMaterials($categoryId);
        $materialslist=CHtml::listData($materials, 'lid', 'material_name');
		$this->render('detailupdate' , array(
            'model' => $model,
            'pblid'=>$pblid,
            'categories'=>$categories,
            'categoryId'=>$categoryId,
            'materials'=>$materialslist
		));
	}

	public function actionDetailDelete(){
        $companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
        $pblid = Yii::app()->request->getParam('pblid');

        $ids = Yii::app()->request->getPost('ids');//var_dump($ids);exit;
        Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
        if(!empty($ids)) {
            Yii::app()->db->createCommand('update nb_product_bom set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')->execute(array( ':companyId' => $this->companyId));
            $this->redirect(array('productBom/detailindex' , 'companyId' => $companyId,'pblid'=>$pblid)) ;
        } else {
            Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
            $this->redirect(array('productBom/detailindex' , 'companyId' => $companyId,'pblid'=>$pblid)) ;
        }
	}
	public function actionGetChildren2(){
		$pid = Yii::app()->request->getParam('pid',0);
		if(!$pid){
			Yii::app()->end(json_encode(array('data'=>array(),'delay'=>400)));
		}
		$treeDataSource = array('data'=>array(),'delay'=>400);
		$categories = Helper::getCategory($this->companyId,$pid);

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
	private function getCategoryId($lid){
		$db = Yii::app()->db;
		$sql = "SELECT category_id from nb_product_bom pb,nb_product_material pm where pb.dpid=pm.dpid and pb.material_id=pm.lid and pb.lid=:lid";
		$command=$db->createCommand($sql);
		$command->bindValue(":lid" , $lid);
		return $command->queryScalar();
	}
	private function getMaterials($categoryId){
		if($categoryId==0)
		{
			//var_dump ('2',$categoryId);exit;
			$materials = ProductMaterial::model()->findAll('dpid=:companyId and delete_flag=0' , array(':companyId' => $this->companyId));
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
	public function actionIsDoubleBomDetail(){
		$materialId = Yii::app()->request->getParam('materialid',0);
		$productBomId = Yii::app()->request->getParam('productBomId',0);
		$companyId = Yii::app()->request->getParam('companyId',0);
		$treeDataSource = array('data'=>FALSE,'delay'=>400);
		$material= ProductBom::model()->find('t.dpid = :dpid and t.material_id = :materialid and t.delete_flag=0',array(':dpid'=>$companyId,':setid'=>$productBomId,':productid'=>$materialId));
		//var_dump($productId,$productSetId,$companyId,$product);exit;
		if(!empty($material)){
			$treeDataSource['data'] = TRUE;
		}
		Yii::app()->end(json_encode($treeDataSource));
	}
}