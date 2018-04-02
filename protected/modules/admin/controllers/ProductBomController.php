<?php
class ProductBomController extends BackendController
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
		//$pbId=Yii::app()->request->getParam('lid');
		$criteria = new CDbCriteria;
		
		$criteria->order='t.dpid desc';
		$criteria->condition =  't.dpid='.$this->companyId .' and t.delete_flag=0 ';
		if($categoryId){
			$criteria->condition.=' and t.category_id = '.$categoryId;
		}
		$pages = new CPagination(Product::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$criteria->with=array('productbom');
		$models = Product::model()->findAll($criteria);
		//var_dump($models);
		$categories = $this->getProductCategories();
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
				'categories'=>$categories,
				'categoryId'=>$categoryId
		));
	}

	public function actionCreate() {
		$this->layout = '/layouts/main_picture';
		$pid = Yii::app()->request->getParam('pid',0);
		$phscode = Yii::app()->request->getParam('phscode',0);
		$prodname = Yii::app()->request->getParam('prodname',0);
		
		$criteria = new CDbCriteria;
		$criteria->condition =  't.pid != 0 and t.delete_flag=0 and t.dpid='.$this->companyId ;
		$criteria->order = ' t.lid asc ';
		$models = MaterialCategory::model()->findAll($criteria);
		//查询原料分类
		
		$criteria = new CDbCriteria;
		$criteria->condition =  ' t.delete_flag=0 and t.dpid='.$this->companyId ;
		$criteria->order = ' t.lid asc ';
		$materials = ProductMaterial::model()->findAll($criteria);
		//查询原料信息
		
		$db = Yii::app()->db;
		$sql = 'select t.* from nb_taste t where t.taste_group_id in(select t1.taste_group_id from nb_product_taste t1 where t1.delete_flag = 0 and t1.product_id='.$pid.' and t1.dpid='.$this->companyId.') and t.delete_flag = 0 and t.dpid ='.$this->companyId ;
		$command1 = $db->createCommand($sql);
		$prodTastes = $command1->queryAll();
		//查询产品口味
		
		//var_dump($categories);exit;
		$this->render('create' , array(
				'models' => $models,
				'prodname' => $prodname,
				'pid' => $pid,
				'phscode' => $phscode,
				'materials' => $materials,
				'prodTastes' => $prodTastes,
				'action' => $this->createUrl('productBom/create' , array('companyId'=>$this->companyId))
		));
	}
	public function actionDetailIndex(){
		$pblid = Yii::app()->request->getParam('pblid');
		$prodname = Yii::app()->request->getParam('prodname');
		$papage = Yii::app()->request->getParam('papage',1);
		//$criteria = new CDbCriteria;
		//$criteria->with = array('material','taste');
		//$criteria->condition =  't.dpid='.$this->companyId .' and t.product_id='.$pblid.' and t.delete_flag=0';
		$db = Yii::app()->db;
		$sql = 'select k.* from(select t2.material_name,t1.name,t.* from nb_product_bom t left join nb_taste t1 on(t.taste_id = t1.lid and t.dpid = t1.dpid) right join nb_product_material t2 on(t.material_id=t2.lid and t.dpid=t2.dpid and t2.delete_flag=0) where t.delete_flag = 0 and t.product_id='.$pblid.' and t.dpid ='.$this->companyId.' order by t.taste_id asc,t.lid asc) k ';
		$recharge = Yii::app()->db->createCommand($sql)->queryRow();
		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();
		
		//$pages = new CPagination(ProductBom::model()->count($criteria));
		//	    $pages->setPageSize(1);
		//$pages->applyLimit($criteria);
		//$models = ProductBom::model()->findAll($criteria); //var_dump($models);exit;
		$this->render('detailindex',array(
				'models'=>$models,
				'pages'=>$pages,
				'pblid'=>$pblid,
				'prodname'=>$prodname,
				'papage'=>$papage
		));
	}
	public function actionDetailCreate(){
		$categoryId = Yii::app()->request->getParam('cid',0);
		$pblid = Yii::app()->request->getParam('lid');
		$papage = Yii::app()->request->getParam('papage');
		//var_dump($pblid);exit;
		$model = new ProductBom();
		$model->dpid = $this->companyId ;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductBom');
			$model->sales_unit_id = Yii::app()->request->getPost('hidden1');
		if(Yii::app()->user->role > User::SHOPKEEPER) {
				Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
				$this->redirect(array('productbom/detailindex' , 'companyId' => $this->companyId,'pblid'=>$pblid)) ;
			}
			if($pblid&&$model->material_id&&$model->sales_unit_id){
				
				
				//var_dump($model);exit;
				$db = Yii::app()->db;
				$sql = 'select t.* from nb_product t where t.delete_flag = 0 and t.lid = '.$pblid;
				$command1 = $db->createCommand($sql)->queryRow();
				$productCode = $command1['phs_code'];
				
				$sql = 'select t.* from nb_product_material t where t.delete_flag = 0 and t.lid = '.$model->material_id;
				$command2 = $db->createCommand($sql)->queryRow();
				$materialId = $command2['mphs_code'];
				
				$sql = 'select t.* from nb_material_unit t where t.delete_flag = 0 and t.lid = '.$model->sales_unit_id;
				$command3 = $db->createCommand($sql)->queryRow();
				$salesUnitId = $command3['muhs_code'];
				//var_dump($productCode);var_dump($materialId);var_dump($salesUnitId);exit;
				if($productCode&&$materialId&&$salesUnitId){
					$se=new Sequence("product_bom");
					$model->lid = $se->nextval();
		            $model->product_id = $pblid;
					$model->create_at = date('Y-m-d H:i:s',time());
					$model->delete_flag = '0';
					$model->mphs_code = $materialId;
					$model->phs_code = $productCode;
					$model->mushs_code = $salesUnitId;
					$model->source = 0;
		            //var_dump($model);exit;
					if($model->save()) {
						Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
						$this->redirect(array('productBom/detailindex','companyId' => $this->companyId,'pblid'=>$pblid));
					}else{
						Yii::app()->user->setFlash('error' ,yii::t('app', '添加失败'));
						$this->redirect(array('productBom/detailindex','companyId' => $this->companyId,'pblid'=>$pblid));
					}
				}else{
					Yii::app()->user->setFlash('error' ,yii::t('app', '添加失败(包含无编码产品)'));
					$this->redirect(array('productBom/detailindex','companyId' => $this->companyId,'pblid'=>$pblid));
				}
			}else{
				Yii::app()->user->setFlash('error' ,yii::t('app', '请完善信息！再确定保存！'));
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
				'materials'=>$materialslist,
				'papage' => $papage,
		));
	}
	public function actionDetailUpdate(){
        $pblid = Yii::app()->request->getParam('pblid');
        $prodname = Yii::app()->request->getParam('prodname');
        $tastename = Yii::app()->request->getParam('tastename');
        $lid = Yii::app()->request->getParam('lid');
        $papage = Yii::app()->request->getParam('papage');
        
		$model = ProductBom::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));
		//Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			if(Yii::app()->user->role > User::SHOPKEEPER) {
				Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
				$this->redirect(array('productbom/detailindex' , 'companyId' => $this->companyId,'pblid'=>$pblid,'papage'=>$papage)) ;
			}
			$model->attributes = Yii::app()->request->getPost('ProductBom');
			
			$saleunit = Yii::app()->request->getPost('hidden1',0);
			if($saleunit){
				$model->sales_unit_id = $saleunit;
			}
			
			$model->update_at = date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('ProductBom/detailindex' , 'companyId' => $this->companyId,'pblid'=>$pblid,'papage'=>$papage));
			}
		}
        $categories = $this->getCategories();
		$categoryId=  $this->getCategoryId($lid);
        $materials = $this->getMaterials($categoryId);
        $materialslist=CHtml::listData($materials, 'lid', 'material_name');
		$this->render('detailupdate' , array(
            'model' => $model,
            'pblid' => $pblid,
            'categories' => $categories,
            'categoryId' => $categoryId,
            'materials' => $materialslist,
			'prodname' => $prodname,
			'tastename' => $tastename,
			'papage' => $papage,
		));
	}

	public function actionDetailDelete(){
        $companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
        $pblid = Yii::app()->request->getParam('pblid');
        $papage = Yii::app()->request->getParam('papage');
        if(Yii::app()->user->role > User::SHOPKEEPER) {
        	Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
        	$this->redirect(array('productbom/detailindex' , 'companyId' => $this->companyId,'pblid'=>$pblid,'papage'=>$papage)) ;
        }
        $ids = Yii::app()->request->getPost('ids');//var_dump($ids);exit;
        //Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
        if(!empty($ids)) {
            Yii::app()->db->createCommand('update nb_product_bom set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')->execute(array( ':companyId' => $this->companyId));
            $this->redirect(array('productBom/detailindex' , 'companyId' => $companyId,'pblid'=>$pblid,'papage'=>$papage)) ;
        } else {
            Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
            $this->redirect(array('productBom/detailindex' , 'companyId' => $companyId,'pblid'=>$pblid,'papage'=>$papage)) ;
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
	private function getProductCategories(){
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
		}
		foreach ($options as $k=>$v) {
			$model = MaterialCategory::model()->find('t.lid = :lid and dpid=:dpid',array(':lid'=>$k,':dpid'=>$this->companyId));
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
			$materials = ProductMaterial::model()->findAll('dpid=:companyId and delete_flag=0' , array(':companyId' => $this->companyId));
		}else{
			$materials = ProductMaterial::model()->findAll('dpid=:companyId and category_id=:categoryId and delete_flag=0' , array(':companyId' => $this->companyId,':categoryId'=>$categoryId)) ;
		}
		$materials = $materials ? $materials : array();
		return $materials;
	}

	public function actionGetChildren(){
		$categoryId = Yii::app()->request->getParam('pid',0);
		if(!$categoryId){
			Yii::app()->end(json_encode(array('data'=>array(),'delay'=>400)));
		}

		$treeDataSource = array('data'=>array(),'delay'=>400);
		$produts=  $this->getMaterials($categoryId);

		foreach($produts as $c){
			$tmp['name'] = $c['material_name'];
			$tmp['id'] = $c['lid'];
			$tmp['unit_id'] = $c['sales_unit_id'];
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
		if(!empty($material)){
			$treeDataSource['data'] = TRUE;
		}
		Yii::app()->end(json_encode($treeDataSource));
	}
	public function actionStorProductBom(){
		
		$is_sync = DataSync::getInitSync();
		//var_dump($companyId);exit;
		$ids = Yii::app()->request->getPost('ids');
		$matids = Yii::app()->request->getParam('matids');
		$prodid = Yii::app()->request->getParam('prodid');
		$prodcode = Yii::app()->request->getParam('prodcode');
		$tasteid = Yii::app()->request->getParam('tasteid');
		$dpid = $this->companyId;
		$materialnums = array();
		$materialnums = explode(';',$matids);
		
		$db = Yii::app()->db;
		//var_dump($dpids,$phscodes);exit;
		$transaction = $db->beginTransaction();
		try{
			//var_dump($materialnums);exit;
			foreach ($materialnums as $materialnum){
				$materials = array();
				$materials = explode(',',$materialnum);
				$mateid = $materials[0];
				$matenum = $materials[1];
				$prodmaterials = ProductMaterial::model()->find('lid=:lid and dpid=:companyId and delete_flag=0' , array(':lid'=>$mateid,':companyId'=>$this->companyId));
				
				if(!empty($prodmaterials)&&!empty($mateid)){
					$se = new Sequence("product_bom");
					$id = $se->nextval();
					//Yii::app()->end(json_encode(array('status'=>true,'msg'=>'成功','matids'=>$prodmaterials['material_name'],'prodid'=>$matenum,'tasteid'=>$tasteid)));
					$dataprodbom = array(
							'lid'=>$id,
							'dpid'=>$dpid,
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
							'product_id'=>$prodid,
							'taste_id'=>$tasteid,
							'material_id'=>$mateid,
							'number'=>$matenum,
							'sales_unit_id'=>$prodmaterials['sales_unit_id'],
							'mphs_code'=>$prodmaterials['mphs_code'],
							'phs_code'=>$prodcode,
							'mushs_code'=>$prodmaterials['mushs_code'],
							'delete_flag'=>'0',
							'is_sync'=>$is_sync,
					);
					$msg = $prodid.'@@'.$mateid.'@@'.$prodmaterials['sales_unit_id'].'@@'.$prodmaterials['mphs_code'].'@@'.$prodcode.'@@'.$prodmaterials['mushs_code'];
					//var_dump($dataprod);exit;
					$command = $db->createCommand()->insert('nb_product_bom',$dataprodbom);
					
				}
				
			}
			//Yii::app()->end(json_encode(array('status'=>true,'msg'=>$msg)));
			$transaction->commit(); //提交事务会真正的执行数据库操作
			Yii::app()->end(json_encode(array('status'=>true,'msg'=>$msg)));
			
		} catch (Exception $e) {
				$transaction->rollback(); //如果操作失败, 数据回滚
				Yii::app()->end(json_encode(array('status'=>false,'msg'=>'保存失败',)));
			}  
		
		
	
	}
}