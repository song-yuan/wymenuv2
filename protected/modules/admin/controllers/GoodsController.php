<?php
class GoodsController extends BackendController
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

	public function actionIndex(){
		$categoryId = Yii::app()->request->getParam('cid',0);
		if($categoryId){
			$cates = ' and category_id ='.$categoryId;
		}else{
			$cates = '';
		}
		$db = Yii::app()->db;
		$sql = 'select k.* from (select mc.category_name, t.* from nb_goods t left join nb_material_category mc on(t.category_id = mc.lid and mc.delete_flag =0 ) where t.dpid ='.$this->companyId.' and t.delete_flag =0 '.$cates.') k';
		
		//$models = $db->createCommand($sql)->queryAll();
		//var_dump($sql);exit;
		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();
		$categories = $this->getCategories();
//                var_dump($categories);exit;
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
				'categories'=>$categories,
				'categoryId'=>$categoryId,
				//'comtype'=>$comtype,
		));
	}

	public function actionCreate(){
		$msg = '';
		$model = new Goods();
		//var_dump($model);exit;
		$istempp = Yii::app()->request->getParam('istempp',0);
		$model->dpid = $this->companyId ;
		
		$comps = Yii::app()->db->createCommand('select comp_dpid from nb_company where delete_flag = 0 and dpid ='.$this->companyId)->queryRow();
		//var_dump($compid);exit;
		if(!empty($comps)){
			$compid = $comps['comp_dpid'];
		}else{
			Yii::app()->user->setFlash('error' , yii::t('app','读取总部信息失败！'));
			$this->redirect(array('goods/index' , 'companyId' => $this->companyId)) ;
		}
		//$model->create_time = time();
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('goods/index' , 'companyId' => $this->companyId)) ;
		}
		
		if(Yii::app()->request->isAjaxRequest){
			$path = Yii::app()->basePath.'/../uploads/goodscompany_'.$this->companyId;
			$up = new CFileUpload();
			//设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
			$up -> set("path", $path);
			$up -> set("maxsize", 2*1024*1024);
			$up -> set("allowtype", array("png", "jpg","jpeg"));
		
			if($up -> upload("file")) {
				$msg = '/wymenuv2/./uploads/goodscompany_'.$this->companyId.'/'.$up->getFileName();
			}else{
				$msg = $up->getErrorMsg();
			}
			echo $msg;exit;
		}
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Goods');
			//var_dump($model);exit;
			$cateID = $model->category_id;
			if(!empty($cateID)){
				$db = Yii::app()->db;
				$sql = 'select t.* from nb_material_category t where t.delete_flag = 0 and t.lid = '.$cateID;
				$command = $db->createCommand($sql);
				$categoryId = $command->queryRow();
				//var_dump($categoryId);exit;
				if(empty($model->member_price)){
					$model->member_price = $model->original_price;
				}
				if(empty($categoryId)){
					$model->addError('category_id','分类信息读取失败！');
					$this->render('create' , array(
							'model' => $model ,
							'istempp' => $istempp,));
				}else{
					if($categoryId['mchs_code']==''){
					$model->addError('category_id','分类信息读取失败！');
					$this->render('create' , array(
							'model' => $model ,
							'istempp' => $istempp,));
					}
				}
				$se=new Sequence("goods");
				$lid = $se->nextval();
				$model->lid = $lid;
				$code=new Sequence("goods_code");
				$phs_code = $code->nextval();
				
				$model->create_at = date('Y-m-d H:i:s',time());
				$model->update_at = date('Y-m-d H:i:s',time());
				$model->cate_code = $categoryId['mchs_code'];
				$model->goods_code = ProductCategory::getChscode($this->companyId, $lid, $phs_code);
				$model->delete_flag = '0';
				$py=new Pinyin();
				$model->simple_code = $py->py($model->goods_name);
				//var_dump($model);exit;
				if($model->save()){
					Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
					$this->redirect(array('goods/index' , 'companyId' => $this->companyId ));
				}
			}else{
				 $model->addError('category_id','必须添加二级分类');
			}
			
		}
		//$categories = $this->getCategoryList();
		//$departments = $this->getDepartments();
                //echo 'ss';exit;
		$this->render('create' , array(
			'model' => $model ,
			'compid' => $compid,
			//'categories' => $categories,
			'istempp' => $istempp,
		));
	}
	
	public function actionUpdate(){
		$msg = '';
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('goods/index' , 'companyId' => $this->companyId)) ;
		}
		
		$comps = Yii::app()->db->createCommand('select comp_dpid from nb_company where delete_flag = 0 and dpid ='.$this->companyId)->queryRow();
		//var_dump($compid);exit;
		if(!empty($comps)){
			$compid = $comps['comp_dpid'];
		}else{
			Yii::app()->user->setFlash('error' , yii::t('app','读取总部信息失败！'));
			$this->redirect(array('goods/index' , 'companyId' => $this->companyId)) ;
		}
		if(Yii::app()->request->isAjaxRequest){
			$path = Yii::app()->basePath.'/../uploads/goodscompany_'.$this->companyId;
			$up = new CFileUpload();
			//设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
			$up -> set("path", $path);
			$up -> set("maxsize", 2*1024*1024);
			$up -> set("allowtype", array("png", "jpg","jpeg"));
		
			if($up -> upload("file")) {
				$msg = '/wymenuv2/./uploads/goodscompany_'.$this->companyId.'/'.$up->getFileName();
			}else{
				$msg = $up->getErrorMsg();
			}
			echo $msg;exit;
		}
		$id = Yii::app()->request->getParam('id');
		$istempp = Yii::app()->request->getParam('istempp');
		$papage = Yii::app()->request->getParam('papage');
		$islock = Yii::app()->request->getParam('islock');
		//var_dump($istempp);exit;
		$model = Goods::model()->find('lid=:goodsId and dpid=:dpid' , array(':goodsId' => $id,':dpid'=>  $this->companyId));
		//var_dump($model);exit;
		$model->dpid = $this->companyId;
		//Until::isUpdateValid(array($id),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Goods');
			if($model->category_id){
				$categoryId = MaterialCategory::model()->find('lid=:lid and delete_flag=0' , array(':lid'=>$model->category_id));
				$model->cate_code = $categoryId['mchs_code'];
			}
                $py=new Pinyin();
                $model->simple_code = $py->py($model->goods_name);
			$model->update_at=date('Y-m-d H:i:s',time());
			//$model->is_lock = '0';
			//var_dump($model);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'.$msg));
				$this->redirect(array('goods/index' , 'companyId' => $this->companyId ,'page' => $papage));
			}
		}
		$categories = $this->getCategoryList();
		//$departments = $this->getDepartments();
		
		$this->render('update' , array(
				'model' => $model ,
				'compid' => $compid,
				'categories' => $categories,
				'istempp' => $istempp,
				'papage' => $papage,
				'islock' => $islock,
		));
	}
	public function actionDelete(){
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('goods/index' , 'companyId' => $this->companyId)) ;
		}
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
        //Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_goods set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			
			$deleteids = implode(',' , $ids);
			$se=new Sequence("b_login");
			$lid = $se->nextval();
			$userid = Yii::app()->user->userId;
			$username = Yii::app()->user->username;
			$data = array(
					'lid'=>$lid,
					'dpid'=>$this->companyId,
					'create_at'=>date('Y-m-d H:i:s',time()),
					'update_at'=>date('Y-m-d H:i:s',time()),
					'user_id'=>$userid,
					'do_what'=>$username.':delete('.$deleteids.')',
					'out_time'=>"0000-00-00 00:00:00"
			);
			Yii::app()->db->createCommand()->insert('nb_b_login',$data);
			
			Yii::app()->user->setFlash('success' , yii::t('app','删除成功'));
			$this->redirect(array('goods/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('goods/index' , 'companyId' => $companyId)) ;
		}
	}
	public function actionStatus(){
		$id = Yii::app()->request->getParam('id');
		$goods = Goods::model()->find('lid=:id and dpid=:companyId' , array(':id'=>$id,':companyId'=>$this->companyId));
		//var_dump($product->status);
		if($goods){
			$goods->saveAttributes(array('status'=>$goods->status?0:1,'update_at'=>date('Y-m-d H:i:s',time())));
		}
		exit;
	}
	public function actionRecommend(){
		$id = Yii::app()->request->getParam('id');
		$product = Product::model()->find('lid=:id and dpid=:companyId' , array(':id'=>$id,':companyId'=>$this->companyId));
		
		if($product){
			$product->saveAttributes(array('recommend'=>$product->recommend==0?1:0,'update_at'=>date('Y-m-d H:i:s',time())));
		}
		exit;
	}
	private function getCategoryList(){
		$sql ='select t.* from nb_material_category t where t.delete_flag =0 and t.dpid =('
				.' select comp_dpid from nb_company where type = 0 and dpid ='.$this->companyId.' )';
		$categories = Yii::app()->db->createCommand($sql)->queryAll();
		return CHtml::listData($categories, 'lid', 'category_name');
	}
	public function actionGetChildren(){
		$pid = Yii::app()->request->getParam('pid',0);
		$compid = Yii::app()->request->getParam('companyId',$this->companyId);
		if(!$pid){
			Yii::app()->end(json_encode(array('data'=>array(),'delay'=>400)));
		}
		$treeDataSource = array('data'=>array(),'delay'=>400);
		$categories = Helper::getCategory($compid,$pid);
	
		foreach($categories as $c){
			$tmp['name'] = $c['category_name'];
			$tmp['id'] = $c['lid'];
			$treeDataSource['data'][] = $tmp;
		}
		Yii::app()->end(json_encode($treeDataSource));
	}
	private function getCategories(){
		
		$comps = Yii::app()->db->createCommand('select comp_dpid from nb_company where delete_flag = 0 and dpid ='.$this->companyId)->queryRow();
		//var_dump($compid);exit;
		if(!empty($comps)){
			$compid = $comps['comp_dpid'];
		}else{
			Yii::app()->user->setFlash('error' , yii::t('app','读取总部信息失败！'));
			$this->redirect(array('goods/index' , 'companyId' => $this->companyId)) ;
		}
		
		$criteria = new CDbCriteria;
		$criteria->with = 'company';
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$compid ;
		$criteria->order = ' tree,t.lid asc ';
		
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
                    //var_dump($k,$v);exit;
			$model = MaterialCategory::model()->find('t.lid = :lid and dpid=:dpid',array(':lid'=>$k,':dpid'=> $compid));
			$optionsReturn[$model->category_name] = $v;
		}
		return $optionsReturn;
	}
	private function getDepartments(){
		$departments = Department::model()->findAll('company_id=:companyId',array(':companyId'=>$this->companyId)) ;
		return CHtml::listData($departments, 'department_id', 'name');
	}
	public function actionStore(){
		$pid = Yii::app()->request->getParam('pid');//菜品lid编号
		$showtype = Yii::app()->request->getParam('showtype');//下架类型，0表示自上下架，1表示统一上下架。
		$shownum = Yii::app()->request->getParam('shownum');//表示下架后菜品is_show字段的数值，0表示单品不显示，1表示都显示，6表示公司统一下架，7表示自下架。
		$pcode = Yii::app()->request->getParam('pcode');//菜品在公司内的唯一编码.
		$dpid = $this->companyId;
		$db = Yii::app()->db;
		$transaction = $db->beginTransaction();
		//$msg = $pid.'@@'.$shownum.'##'.$showtype.'$$'.$pcode.'%%'.$dpid;
		try
		{
			$is_sync = DataSync::getInitSync();
			//盘点日志
			//盘点日志
			if($showtype==0){
				Yii::app()->db->createCommand('update nb_goods set is_show = '.$shownum.' where lid in ('.$pid.') and dpid = :companyId')
				->execute(array( ':companyId' => $this->companyId));
				//Yii::app()->user->setFlash('success' , yii::t('app','删除成功'));
				//$this->redirect(array('product/index' , 'companyId' => $companyId)) ;
				$transaction->commit();
				Yii::app()->end(json_encode(array("status"=>"success",'msg'=>'成功')));
			}else{
				$dpids = '000';
				$companys = Company::model()->findAll('dpid=:companyId or comp_dpid=:companyId and delete_flag=0' , array(':companyId'=>$this->companyId));
				foreach ($companys as $company){
					$dpids = $dpids .','.$company->dpid;
				}
				Yii::app()->db->createCommand('update nb_goods set is_show = '.$shownum.' where goods_code in ('.$pcode.') and dpid in ('.$dpids.')')
				->execute();
				$transaction->commit();
				Yii::app()->end(json_encode(array("status"=>"success",'msg'=>'成功')));
			}
			return true;
		}catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			Yii::app()->end(json_encode(array("status"=>"fail")));
			return false;
		}
	}
	

	public function actionStorewx(){
		$pid = Yii::app()->request->getParam('pid');
		$shownum = Yii::app()->request->getParam('shownum');
		$pcode = Yii::app()->request->getParam('pcode');
		$dpid = $this->companyId;
		$db = Yii::app()->db;
		$transaction = $db->beginTransaction();
		try
		{
			Yii::app()->db->createCommand('update nb_product set is_show_wx = '.$shownum.' where lid in ('.$pid.') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$transaction->commit();
			Yii::app()->end(json_encode(array("status"=>"success",'msg'=>'成功')));
			
		}catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			Yii::app()->end(json_encode(array("status"=>"fail")));
		}
	}
	
}