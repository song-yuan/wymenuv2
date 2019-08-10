<?php
class InstructController extends BackendController
{
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex() {
		$criteria = new CDbCriteria;
		$criteria->addCondition('dpid=:dpid and delete_flag=0');
		$criteria->order = 'lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		
		$pages = new CPagination(Instruction::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = Instruction::model()->findAll($criteria);
		
		$this->render('index',array(
				'models'=>$models,
				'pages' => $pages,
		));
	}
	public function actionCreate() {
		$model = new Instruction();
		$model->dpid = $this->companyId ;
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Instruction');
            $se=new Sequence("instruction");
            $lid = $se->nextval();

           	$code=new Sequence("phs_code");
            $tghs_code = $code->nextval();
                        
            $model->lid = $lid;
            $model->create_at = date('Y-m-d H:i:s',time());
            $model->update_at = date('Y-m-d H:i:s',time());
            $model->phs_code = ProductCategory::getChscode($this->companyId, $lid, $tghs_code);
			
			if($model->save()) {
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('instruct/index' , 'companyId' => $this->companyId));
			}
		}
		$this->render('create' , array(
				'model' => $model , 
		));
	}
	public function actionUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		$model = Instruction::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=>  $this->companyId));
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Instruction');
			$model->update_at = date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('instruct/index','companyId'=>$this->companyId));
			}
		}
		$this->render('update' , array(
			'model'=>$model,
		));
	}
	public function actionDelete(){
		$companyId = $this->companyId;
		$ids = Yii::app()->request->getPost('lid');
		if(!empty($ids)) {
			foreach ($ids as $id){
				$model = Instruction::model()->find('lid=:id and dpid=:companyId' , array(':id'=>$id,':companyId'=>$companyId));
				if($model) {
					$model->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
				}
			}
			$this->redirect(array('instruct/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('instruct/index' , 'companyId' => $companyId)) ;
		}
	}
    public function actionDetailIndex() {
		$groupid = Yii::app()->request->getParam('groupid',0);
		$criteria = new CDbCriteria;
		$criteria->addCondition('t.dpid=:dpid and t.instruction_id=:groupid and t.delete_flag=0');
		$criteria->order = 't.sort asc,t.lid desc';
		$criteria->params[':dpid']=$this->companyId;
		$criteria->params[':groupid']=$groupid; 
		
		$pages = new CPagination(InstructionDetail::model()->count($criteria));
		$pages->applyLimit($criteria);
		$models = InstructionDetail::model()->findAll($criteria);
		
		$instruction = Instruction::model()->find('lid=:lid and dpid=:dpid',array(':lid'=>$groupid,':dpid'=>$this->companyId));
		$this->render('detailIndex',array(
				'instruction'=>$instruction,
				'models'=>$models,
				'pages' => $pages,
                'groupid'=>$groupid,
		));
	}
	public function actionDetailCreate() {
		$groupid = Yii::app()->request->getParam('groupid',0);
		$model = new InstructionDetail();
		$model->dpid = $this->companyId ;		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('InstructionDetail');
			$se=new Sequence("instruction_detail");
			$model->lid = $se->nextval();
			$model->create_at = date('Y-m-d H:i:s',time());
			$model->instruction_id = $groupid;
			if($model->save()) {
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('instruct/detailIndex' , 'companyId' => $this->companyId,'groupid'=>$groupid));
			}
		}
		$instruction = Instruction::model()->find('lid=:lid and dpid=:dpid',array(':lid'=>$groupid,':dpid'=>$this->companyId));
		$this->render('detailCreate' , array(
			'instruction' => $instruction,
			'model' => $model , 
        	'groupid'=>$groupid,
		));
	}
	public function actionDetailUpdate(){
		$lid = Yii::app()->request->getParam('lid');
		$model = InstructionDetail::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=>  $this->companyId));
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('InstructionDetail');
			$model->update_at = date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('instruct/detailIndex', 'groupid'=>$model->instruction_id, 'companyId' => $this->companyId));
			}
		}
		$instruction = Instruction::model()->find('lid=:lid and dpid=:dpid',array(':lid'=>$model->instruction_id,':dpid'=>$this->companyId));
		$this->render('detailUpdate' , array(
			'instruction' => $instruction,
			'model'=>$model,
            'groupid'=>$model->instruction_id,
		));
	}
	public function actionDetailDelete(){
        $groupid = Yii::app()->request->getParam('groupid',0);
		$ids = Yii::app()->request->getPost('lid');
		if(!empty($ids)) {
			foreach ($ids as $id) {
				$model = InstructionDetail::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id , ':companyId' => $this->companyId)) ;
				if($model) {
					$model->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
				}
			}
			$this->redirect(array('instruct/detailIndex' , 'companyId' => $this->companyId,'groupid'=>$groupid)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('instruct/detailIndex' , 'companyId' => $this->companyId,'groupid'=>$groupid)) ;
		}
	}
	public function actionProductInstruct(){
        $categoryId = Yii::app()->request->getParam('cid',0);
		$criteria = new CDbCriteria;
		if($categoryId!=0)
		{
			$criteria->addCondition('t.dpid=:dpid and t.delete_flag=0 and t.category_id ='.$categoryId);
		}else{
			$criteria->addCondition('t.dpid=:dpid and t.delete_flag=0');
		}        
		$criteria->order = ' t.lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		$categories = $this->getCategories();
		$pages = new CPagination(Product::model()->count($criteria));
		$pages->applyLimit($criteria);
		$criteria->with = 'productInstruct';
		$models = Product::model()->findAll($criteria);
		$instruct = array();
		$instructions = Instruction::model()->findAll('dpid=:dpid and delete_flag=0',array(':dpid'=>$this->companyId));
		foreach ($instructions as $instruction){
			$instruct['lid-'.(int)$instruction['lid']] = $instruction;
		}
		$this->render('productInstruct',array(
				'instruct'=>$instruct,
				'models'=>$models,
                'categories'=>$categories,
                'categoryId'=>$categoryId,
				'pages' => $pages,
		));
	}
	public function actionUpdateProductInstruct(){
		$lid = Yii::app()->request->getParam('lid');
		$model = Product::model()->find('lid=:lid and dpid=:dpid', array(':lid'=>$lid,':dpid'=>$this->companyId));
		
		if(Yii::app()->request->isPostRequest) {
			$postData = Yii::app()->request->getPost('Instruct');
			if($this->saveProductInstruction($lid,$postData,0)){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('instruct/productInstruct' , 'companyId' => $this->companyId));
			}
		}
		$instructions = $this->getInstruction();
		$productInstructs = $this->getProductInstruction($lid);
		$this->render('updateProductInstruct' , array(
			'model'=>$model,
			'instructions'=>$instructions,
			'productInstructs'=>$productInstructs,
		));
	}
	public function actionTasteInstruct(){
		$criteria = new CDbCriteria;
		$criteria->addCondition('t.dpid=:dpid and t.delete_flag=0');
		$criteria->order = ' t.lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		$pages = new CPagination(Taste::model()->count($criteria));
		$pages->applyLimit($criteria);
		$criteria->with = 'productInstruct';
		$models = Taste::model()->findAll($criteria);
		$instruct = array();
		$instructions = Instruction::model()->findAll('dpid=:dpid and delete_flag=0',array(':dpid'=>$this->companyId));
		foreach ($instructions as $instruction){
			$instruct['lid-'.(int)$instruction['lid']] = $instruction;
		}
		$this->render('tasteInstruct',array(
				'instruct'=>$instruct,
				'models'=>$models,
				'pages' => $pages,
		));
	}
	// 该口味指令列表
	public function actionTasteInstructList(){
		$lid = Yii::app()->request->getParam('lid');
		$sql = 'select * from nb_product_instruction where dpid='.$this->companyId.' and product_id like "%'.$lid.'%" and is_taste=1 and delete_flag=0';
		$models = Yii::app()->db->createCommand($sql)->queryAll();
		
		$tastes = $this->getTaste();
		$instructions = $this->getInstruction();
		$instruct = array();
		foreach ($instructions as $instruction){
			$instruct['lid-'.(int)$instruction['lid']] = $instruction;
		}
		$taste = array();
		foreach ($tastes as $t){
			$taste['lid-'.(int)$t['lid']] = $t;
		}
		$data = array();
		foreach ($models as $model){
			$pid = $model['product_id'];
			$iid = $model['instruction_id'];
			$pidArr = explode(',', $pid);
			$tt = array();
			foreach ($pidArr as $p){
				if(isset($taste['lid-'.(int)$p])){
					array_push($tt, $taste['lid-'.(int)$p]);
				}
			}
			if(!isset($data[$pid])){
				$data[$pid] = array();
				$data[$pid]['instruct'] = array();
				$data[$pid]['taste'] = $tt;
			}
			if(isset($instruct['lid-'.(int)$iid])){
				array_push($data[$pid]['instruct'], $instruct['lid-'.(int)$iid]);
			}
			$data[$pid]['model'] = $model;
		}
		$this->render('tasteInstructList',array(
				'models' => $data,
		));
	}
	public function actionUpdateTasteInstruct(){
		$lid = Yii::app()->request->getParam('lid');
		$models = Taste::model()->findAll('lid in('.$lid.') and dpid='.$this->companyId);
		if(Yii::app()->request->isPostRequest) {
			$tastes = Yii::app()->request->getPost('Taste',0);
			$postData = Yii::app()->request->getPost('Instruct');
			if($tastes&&$tastes[0]){
				array_push($tastes, $lid);
				$lid = join(',', $tastes);
			}
			if($this->saveProductInstruction($lid,$postData,1)){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('instruct/tasteInstruct' , 'companyId' => $this->companyId));
			}
		}
		$tastes = $this->getTaste();
		$instructions = $this->getInstruction();
		$productInstructs = $this->getProductInstruction($lid);
		$this->render('updateTasteInstruct' , array(
				'models'=>$models,
				'tastes'=>$tastes,
				'instructions'=>$instructions,
				'productInstructs'=>$productInstructs,
		));
	}
	// 获取口味
	private function getTaste(){
		$sql = 'select * from nb_taste where dpid='.$this->companyId.' and allflae=0 and delete_flag=0';
		$tastes = Yii::app()->db->createCommand($sql)->queryAll();
		return $tastes;
	}
	// 获取指令
	private function getInstruction(){
		$sql = 'select * from nb_instruction where dpid='.$this->companyId.' and delete_flag=0';
		$instructs = Yii::app()->db->createCommand($sql)->queryAll();
		return $instructs;
	}
	// 获取产品指令
	private function getProductInstruction($productId){
		$sql = 'select t.instruction_id from nb_product_instruction t,nb_instruction t1 where t.instruction_id=t1.lid and t.dpid=t1.dpid and t.dpid='.$this->companyId.' and t.product_id="'.$productId.'" and t.delete_flag=0 and t1.delete_flag=0';
		$instructIds = Yii::app()->db->createCommand($sql)->queryColumn();
		return $instructIds;
	}
	// 对应指令保存
	private function saveProductInstruction($productId,$instructIds,$isTaste = 0){
		$sql = 'update nb_product_instruction set delete_flag=1 where dpid='.$this->companyId.' and product_id="'.$productId.'" and delete_flag=0';
		$res = Yii::app()->db->createCommand($sql)->execute();
		if(!empty($instructIds)){
			$sql = 'insert into nb_product_instruction (lid,dpid,create_at,instruction_id,product_id,is_taste) values ';
			foreach ($instructIds as $id){
				$se=new Sequence("product_instruction");
				$lid = $se->nextval();
				$createAt = date('Y-m-d H:i:s',time());
				$sql .= '('.$lid.','.$this->companyId.',"'.$createAt.'",'.$id.',"'.$productId.'",'.$isTaste.'),';
			}
			$sql = rtrim($sql,',');
			$res = Yii::app()->db->createCommand($sql)->execute();
		}
		return $res;
	}
    private function getCategories(){
		$criteria = new CDbCriteria;
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId ;
		$criteria->order = ' tree,t.lid asc ';
		
		$models = ProductCategory::model()->findAll($criteria);
                
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
			$model = ProductCategory::model()->find('t.lid = :lid and dpid=:dpid',array(':lid'=>$k,':dpid'=>  $this->companyId));
			$optionsReturn[$model->category_name] = $v;
		}
		return $optionsReturn;
	}
}