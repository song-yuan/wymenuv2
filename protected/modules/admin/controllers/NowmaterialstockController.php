<?php
class NowmaterialstockController extends BackendController{
	
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
		$criteria->with = array('category');
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId;
		if($categoryId){
			$criteria->condition.=' and t.category_id = '.$categoryId;
		}
	    //$criteria->condition.=' and t.lid = '.$categoryId;
		$pages = new CPagination(ProductMaterial::model()->count($criteria));
		$pages->applyLimit($criteria);
		$models = ProductMaterial::model()->findAll($criteria);
		$categories = $this->getCategories();
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
				'categories'=>$categories,
				'categoryId'=>$categoryId

		));
	}


	public function actionDetailindex(){
		$materialId = Yii::app()->request->getParam('id',0);
		$criteria = new CDbCriteria;
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId.' and t.material_id ='.$materialId;
		//$criteria->condition.=' and t.lid = '.$categoryId;
		$pages = new CPagination(ProductMaterialStock::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = ProductMaterialStock::model()->findAll($criteria);
		$this->render('detailindex',array(
				'models'=>$models,
				'pages'=>$pages,
	
		));
	}


	private function getCategoryList(){
		$categories = MaterialCategory::model()->findAll('delete_flag=0 and dpid=:companyId' , array(':companyId' => $this->companyId)) ;
		//var_dump($categories);exit;
		return CHtml::listData($categories, 'lid', 'category_name');
	}


	public function actionGetChildren(){
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



    //库存预警
    public function actionCaution(){
        $criteria = new CDbCriteria;
        $criteria->with = array('category');
        $criteria->condition = 't.delete_flag=0 and t.dpid='.$this->companyId;
        $models = ProductMaterial::model()->findAll($criteria);
        //var_dump($models);exit;
        $this->renderPartial('caution',array(
            'models'=>$models
        ));
    }

    /*public function actionCaution(){
        $db = Yii::app()->db;
        $sql = 'select k.* from (select p.material_identifier,p.material_name,m.category_name,sum(pr.stock) as stock_all,p.sales_unit_id
                from nb_product_material p
                left join nb_material_category m on(m.lid=p.category_id)
                left join nb_product_material_stock pr on(p.lid = pr.material_id and pr.dpid=p.dpid and pr.delete_flag=0)
                where p.delete_flag=0 and p.dpid='.$this->companyId.' group by p.lid) k';
        $models = $db->createCommand($sql)->queryAll();
        //var_dump($models);exit;
        $this->renderPartial('caution',array(
            'models'=>$models
        ));
    }*/


}
