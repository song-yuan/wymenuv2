<?php
class ProductSetController extends BackendController
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
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex(){
		$categoryId = Yii::app()->request->getParam('cid',0);
		$pname = Yii::app()->request->getParam('pname',null);
		
		$criteria = new CDbCriteria;
		$criteria->condition =  't.dpid='.$this->companyId .' and delete_flag=0';
		if($categoryId){
			$criteria->condition.=' and t.category_id = '.$categoryId;
		}
		if($pname){
			$criteria->condition.=' and t.set_name like "%'.$pname.'%"';
		}
		$criteria->order = 't.sort asc';
		$pages = new CPagination(ProductSet::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);

		$models = ProductSet::model()->findAll($criteria);
		$categories = $this->getSCategories();
		$this->render('index',array(
			'models'=>$models,
			'categories'=>$categories,
			'categoryId'=>$categoryId,
			'pname'=>$pname,
			'pages'=>$pages
		));
	}
	public function actionCreate(){
		$istempp = Yii::app()->request->getParam('istempp',0);
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('productSet/index' , 'companyId' => $this->companyId)) ;
		}
		$msg = '';
		if(Yii::app()->request->isAjaxRequest){
			$path = Yii::app()->basePath.'/../uploads/company_'.$this->companyId;
			$up = new CFileUpload();
			//设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
			$up -> set("path", $path);
			$up -> set("maxsize", 2*1024*1024);
			$up -> set("allowtype", array("png", "jpg","jpeg"));

			if($up -> upload("file")) {
				$msg = '/wymenuv2/./uploads/company_'.$this->companyId.'/'.$up->getFileName();
			}else{
				$msg = $up->getErrorMsg();
			}
			echo $msg;exit;
		}
		$model = new ProductSet();
		$model->dpid = $this->companyId ;
		$status = '';
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductSet');

			$cateID = $model->category_id;
			if(!empty($cateID)){
				$db = Yii::app()->db;
				$sql = 'select t.* from nb_product_category t where t.delete_flag = 0 and t.lid = '.$cateID;
				$command = $db->createCommand($sql);
				$categoryId = $command->queryRow();

				$se=new Sequence("porduct_set");
				$model->lid = $lid = $se->nextval();
				$code=new Sequence("phs_code");
				$pshs_code = $code->nextval();

				if($model->member_price==''){
					$model->member_price = $model->set_price;
				}
				$model->create_at = date('Y-m-d H:i:s',time());
				$model->update_at = date('Y-m-d H:i:s',time());
				$model->pshs_code = ProductCategory::getChscode($this->companyId, $lid, $pshs_code);
				$model->chs_code = $categoryId['chs_code'];
				$model->source = 0;
				$model->delete_flag = '0';
				$py=new Pinyin();
				$model->simple_code = $py->py($model->set_name);
				//var_dump($model);exit;
				if($model->save()) {
					Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
					$this->redirect(array('productSet/detailindex','lid' => $model->lid , 'companyId' => $model->dpid , 'status' => ''));
				}
			}else{
				 $model->addError('category_id','必须添加二级分类');
			}
		}
		if(Yii::app()->user->role > 10){
			$model->is_show_wx = 2;
		}
		$categories = $this->getCategoryList();
		$this->render('create' , array(
				'model' => $model,
				'status'=> $status,
				'istempp' => $istempp,
		));
	}
	public function actionUpdate(){
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('productSet/index' , 'companyId' => $this->companyId));
		}
		$msg = '';
		if(Yii::app()->request->isAjaxRequest){
			$path = Yii::app()->basePath.'/../uploads/company_'.$this->companyId;
			$up = new CFileUpload();
			//设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
			$up -> set("path", $path);
			$up -> set("maxsize", 2*1024*1024);
			$up -> set("allowtype", array("png", "jpg","jpeg"));

			if($up -> upload("file")) {
				$msg = '/wymenuv2/./uploads/company_'.$this->companyId.'/'.$up->getFileName();
			}else{
				$msg = $up->getErrorMsg();
			}
			echo $msg;exit;
		}
		$lid = Yii::app()->request->getParam('lid');
		$status = Yii::app()->request->getParam('status');
		$papage = Yii::app()->request->getParam('papage');
		$istempp = Yii::app()->request->getParam('istempp');
		$islock = Yii::app()->request->getParam('islock');
                //echo 'ddd';
		$model = ProductSet::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductSet');
			if($model->category_id){
				$cateID = $model->category_id;
				$db = Yii::app()->db;
				$sql = 'select t.* from nb_product_category t where t.delete_flag = 0 and t.lid = '.$cateID;
				$command = $db->createCommand($sql);
				$categoryId = $command->queryRow();
				$model->chs_code = $categoryId['chs_code'];
			}
            $py=new Pinyin();
            $model->simple_code = $py->py($model->set_name);
            $model->update_at=date('Y-m-d H:i:s',time());

                        //var_dump($model->attributes);var_dump(Yii::app()->request->getPost('ProductSet'));exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('productSet/index' , 'companyId' => $this->companyId ,'page' => $papage));
			}
		}
		$categories = $this->getCategoryList();
		$this->render('update' , array(
				'model'=>$model,
				'categories' => $categories,
				'status'=>$status,
				'papage'=>$papage,
				'istempp' => $istempp,
				'islock' => $islock,
		));
	}

	public function actionDelete(){
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('productSet/index' , 'companyId' => $this->companyId)) ;
		}
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));

		$papage = Yii::app()->request->getParam('papage');
		//var_dump($papage);exit;
		$ids = Yii::app()->request->getPost('ids');
        //Until::isUpdateValid(array($ids),$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_product_set set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));

			Yii::app()->db->createCommand('update nb_product_set_detail set delete_flag=1 where set_id in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));

			/**
			* 删除套餐时,删除套餐内的产品组合
			*/

			Yii::app()->db->createCommand('update nb_product_set_group set delete_flag=1 where set_id in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			$this->redirect(array('productSet/index' , 'companyId' => $companyId, 'page' => $papage));
		} else {
			Yii::app()->user->setFlash('error' ,yii::t('app', '请选择要删除的项目'));
			$this->redirect(array('productSet/index' , 'companyId' => $companyId, 'page' => $papage));
		}
	}

        public function actionDetailIndex(){
		$pwlid = Yii::app()->request->getParam('lid');
		$status = Yii::app()->request->getParam('status');// var_dump($pwlid);exit;
		$papage = Yii::app()->request->getParam('papage');
		$dpid = Yii::app()->request->getParam('companyId');
		$islock = Yii::app()->request->getParam('islock');

		$criteria = new CDbCriteria;
        $criteria->with = array('product');
        $criteria->order =  't.group_no';
        //$criteria->with = 'printer';
		$criteria->condition =  't.dpid='.$dpid.' and t.set_id='.$pwlid.' and t.delete_flag=0 and product.delete_flag=0';


        $sql='select pg.lid as pglid,psg.lid as psgid,pgd.*,pg.*,psg.* from nb_product_group pg '
        	.' left join nb_product_set_group psg on(pg.dpid=psg.dpid and psg.delete_flag=0 and pg.lid=psg.prod_group_id)'
        	.' left join nb_product_group_detail pgd on(pg.dpid=pgd.dpid and pgd.delete_flag=0 and pg.lid=pgd.prod_group_id and pgd.is_select=1)'
        	.' where pg.dpid='.$dpid.' and psg.set_id='.$pwlid.' and psg.delete_flag=0';

        $db=Yii::app()->db;

        $infos = $db->createCommand($sql)->queryAll();
		// p($infos);
		$criteria2 = new CDbCriteria;
		$criteria2->condition =  't.dpid='.$dpid .' and t.lid='.$pwlid.' and t.delete_flag=0';

		$pages = new CPagination(ProductSetDetail::model()->count($criteria)+count($infos));
		// $pages->setPageSize(1);
		$pages->applyLimit($criteria);

		$models = ProductSetDetail::model()->findAll($criteria);
        // p($models);

		$psmodel = ProductSet::model()->find($criteria2);
        // var_dump($psmodel);exit;
		$this->render('detailindex',array(
			'infos'=>$infos,
			'pslid'=>$pwlid,
			'models'=>$models,
            'psmodel'=>$psmodel,
			'pages'=>$pages,
			'status'=>$status,
			'papage'=>$papage,
			'islock'=>$islock,
		));
	}

	public function actionDetailCreate(){

		$pslid = Yii::app()->request->getParam('psid');
		$type = Yii::app()->request->getParam('type');
		$kind = Yii::app()->request->getParam('kind');
		$groupid = Yii::app()->request->getPost('prod_group_id');
		$papage = Yii::app()->request->getParam('papage'); //var_dump($pslid);exit;
		$status = '';
		$maxgroupno=$this->getMaxGroupNo($pslid);
		$maxgroupno2=$this->getMaxGroupNo2($pslid);
		$categories = $this->getCategories();
		$categoryId=0;
		$products = $this->getProducts($categoryId);
		$productslist=CHtml::listData($products, 'lid', 'product_name');

		$groups = $this->getGroupnos($pslid);
		$groupslist=CHtml::listData($groups, 'group_no' , 'product_name');
		$maxgroupno=$maxgroupno>$maxgroupno2?$maxgroupno:$maxgroupno2;
		if ($kind==0) {
			$model = new ProductSetDetail();
			$model->dpid = $this->companyId ;
	        $model->set_id=$pslid;
	        if(Yii::app()->user->role > User::SHOPKEEPER) {
	        	Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
	        	$this->redirect(array('productSet/detailindex' , 'companyId' => $this->companyId,'lid' => $pslid , 'papage'=>$papage)) ;
	        }
			if(Yii::app()->request->isPostRequest) {
				$model->attributes = Yii::app()->request->getPost('ProductSetDetail');
				$groupno = Yii::app()->request->getParam('groupno');
				$isselect = Yii::app()->request->getParam('isselect');
				$number = Yii::app()->request->getParam('number');
				//var_dump($model);exit;
	            $se=new Sequence("porduct_set_detail");
	            $model->lid = $se->nextval();
	            $model->create_at = date('Y-m-d H:i:s',time());
	            $model->delete_flag = '0';
	            $model->group_no = $groupno;
	            $model->is_select = $isselect;
	            $model->number = $number;
	            //var_dump($model);exit;
	            $modelsp= Yii::app()->db->createCommand('select count(*) as num from nb_product_set_detail t where t.dpid='.$this->companyId.' and t.set_id='.$pslid.' and t.delete_flag=0 and group_no='.$model->group_no)->queryRow();
	            //var_dump($modelsp);exit;
	            if($model->is_select=="1")
	            {
	                $sqlgroup="update nb_product_set_detail set is_select=0 where group_no=".$model->group_no." and dpid=".$this->companyId." and set_id=".$model->set_id;
	                Yii::app()->db->createCommand($sqlgroup)->execute();
	            }
				if($model->save()) {

					Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
					$this->redirect(array('productSet/detailindex','companyId' => $this->companyId,'lid'=>$model->set_id,'papage'=>$papage));
				}
			}
				$this->render('detailcreate' , array(
					'model' => $model,
					'categories' => $categories,
					'categoryId' => $categoryId,
					'products' => $productslist,
					'maxgroupno'=>$maxgroupno,
					'groups' =>$groupslist,
					'type'=>$type,
					'psid'=>$pslid,
					'kind'=>$kind,
					'status'=>$status,
					'papage'=>$papage,
				));
		}elseif($kind==1){
			$model = ProductSetGroup::model();
			$pgroups = ProductGroup::model()->findAll('dpid=:dpid and delete_flag=0',array(':dpid'=>$this->companyId));
			// p($pgroups);
			if (Yii::app()->request->isPostRequest) {
				$info = $model->find('dpid=:dpid and set_id=:set_id and prod_group_id=:groupid',array(':dpid'=>$this->companyId,':groupid'=>$groupid,':set_id'=>$pslid));
				// p($info);
				if($info){
					$info->dpid=$this->companyId;
					$info->update_at=date('Y-m-d H:i:s',time());
					$info->delete_flag=0;
					if ($info->save()) {
						Yii::app()->user->setFlash('success',Yii::t('app','添加成功'));
						$this->redirect(array('productSet/detailindex' , 'companyId' => $this->companyId,'lid' => $pslid,'papage'=>$papage)) ;
					}else{
						Yii::app()->user->setFlash('error',Yii::t('app','添加失败'));
						$this->redirect(array('productSet/detailindex' , 'companyId' => $this->companyId,'lid' => $pslid,'papage'=>$papage)) ;
					}
				}else{
					$model = new ProductSetGroup();
					$se=new Sequence("product_set_group");
					$lid = $se->nextval();

					$model->lid=$lid;
					$model->dpid=$this->companyId;
					$model->create_at=date('Y-m-d H:i:s',time());
					$model->update_at=date('Y-m-d H:i:s',time());
					$model->group_no=$maxgroupno+1;
					$model->set_id=$pslid;
					$model->prod_group_id=$groupid;
					$model->delete_flag=0;
					// p($modeld);
					if ($model->save()) {
						Yii::app()->user->setFlash('success',Yii::t('app','添加成功'));
						$this->redirect(array('productSet/detailindex' , 'companyId' => $this->companyId,'lid' => $pslid,'papage'=>$papage)) ;
					}else{
						Yii::app()->user->setFlash('error',Yii::t('app','添加失败'));
						$this->redirect(array('productSet/detailindex' , 'companyId' => $this->companyId,'lid' => $pslid,'papage'=>$papage)) ;
					}
				}
			}
			$this->render('detailcreate' , array(
				'model' => $model,
				'pgroups' => $pgroups,
				'categories' => $categories,
				'categoryId' => $categoryId,
				'products' => $productslist,
				'maxgroupno'=>$maxgroupno,
				'groups' =>$groupslist,
				'type'=>$type,
				'psid'=>$pslid,
				'kind'=>$kind,
				'status'=>$status,
				'papage'=>$papage,
			));
		}
	}

	public function actionGroupdelete(){
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('productSet/index' , 'companyId' => $this->companyId)) ;
		}
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));

		$papage = Yii::app()->request->getParam('papage');
		//var_dump($papage);exit;
		$pslid = Yii::app()->request->getParam('pslid');
		$pglid = Yii::app()->request->getParam('pglid');
		$lid = Yii::app()->request->getParam('lid');
		// p($pslid);
        //Until::isUpdateValid(array($ids),$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($pglid)) {
			$info = Yii::app()->db->createCommand('update nb_product_set_group set delete_flag=1 where lid=:lid and prod_group_id=:prod_group_id and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId,':prod_group_id'=>$pglid,':lid'=>$lid));
			if ($info) {
				Yii::app()->user->setFlash('success' ,yii::t('app', '删除成功'));
				$this->redirect(array('productSet/detailindex' , 'companyId' => $companyId, 'lid' => $pslid));
			}else{
				Yii::app()->user->setFlash('error' ,yii::t('app', '删除失败'));
				$this->redirect(array('productSet/detailindex' , 'companyId' => $companyId, 'lid' => $pslid));
			}
		} else {
			Yii::app()->user->setFlash('error' ,yii::t('app', '请选择要删除的项目'));
			$this->redirect(array('productSet/detailindex' , 'companyId' => $companyId, 'lid' => $pslid));
		}
	}

	public function actionDetailUpdate(){

		$lid = Yii::app()->request->getParam('lid');
		$type = Yii::app()->request->getParam('type');
		$status = Yii::app()->request->getParam('status');
		$papage = Yii::app()->request->getParam('papage');

		$model = ProductSetDetail::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=> $this->companyId));
	if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('productSet/detailindex' , 'companyId' => $this->companyId,'lid' => $model->set_id)) ;
		}
        //Until::isUpdateValid(array($lid),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('ProductSetDetail');
			$groupno = Yii::app()->request->getParam('groupno');
			$isselect = Yii::app()->request->getParam('isselect');
			$number = Yii::app()->request->getParam('number');

			$model->update_at = date('Y-m-d H:i:s',time());
			$model->group_no = $groupno;
			$model->is_select = $isselect;
			$model->number = $number;
			//var_dump($model);exit;
			//只有一个时选中，如果第一个必须选中，后续的，判断是选中，必须取消其他选中
			$modelsp= Yii::app()->db->createCommand('select count(*) as num from nb_product_set_detail t where t.dpid='.$this->companyId.' and t.set_id='.$model->set_id.' and t.delete_flag=0 and group_no='.$model->group_no)->queryRow();
			//var_dump($modelsp);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('productSet/detailindex' , 'companyId' => $this->companyId,'lid' => $model->set_id ,'status'=>$status));
			}
		}
                $maxgroupno=$this->getMaxGroupNo($model->set_id);
                //$printers = $this->getPrinters();
                $categories = $this->getCategories();
                $categoryId=  $this->getCategoryId($lid);
                $products = $this->getProducts($categoryId);
                $productslist=CHtml::listData($products, 'lid', 'product_name');

                $groups = $this->getGroupnos($model->set_id);
                $groupslist=CHtml::listData($groups, 'group_no' , 'product_name');
		$this->render('detailupdate' , array(
				'model'=>$model,
                'categories' => $categories,
                'categoryId' => $categoryId,
                'products' => $productslist,
                'maxgroupno' => $maxgroupno,
				'groups'=>$groupslist,
				'type'=>$type,
				'status'=>$status,
				'papage'=>$papage,
		));
	}

        public function actionGroupdetail(){

		$pwlid = Yii::app()->request->getParam('lid');
		$dpid = Yii::app()->request->getParam('companyId');
		$status = Yii::app()->request->getParam('status',0);
		$papage = Yii::app()->request->getParam('papage');
		$pslid = Yii::app()->request->getParam('pslid');
		//var_dump($pwlid);exit;
		$criteria = new CDbCriteria;
        $criteria->with = array('product');
        $criteria->order = 't.prod_group_id';
        //var_dump($criteria);exit;
		$criteria->condition =  't.dpid='.$dpid .' and t.prod_group_id='.$pwlid.' and t.delete_flag=0 and product.delete_flag=0';
		$pages = new CPagination(ProductGroupDetail::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = ProductGroupDetail::model()->findAll($criteria);
                



		$criteria2 = new CDbCriteria;
		$criteria2->condition = 't.dpid='.$dpid .' and t.lid='.$pwlid.' and t.delete_flag=0'; 
		$psmodel = ProductGroup::model()->find($criteria2);
        // p($psmodel);
		$this->render('groupdetail',array(
			'models'=>$models,
			'pslid'=>$pslid,
            'psmodel'=>$psmodel,
			'pages'=>$pages,
			'status'=>$status,
			'papage'=>$papage,
		));
	}

	public function actionDetailDelete(){

		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
        $printset = Yii::app()->request->getParam('psid');
        $papage = Yii::app()->request->getParam('papage');
		$ids = Yii::app()->request->getPost('ids');
		$glids = Yii::app()->request->getPost('glids');
		// p($glids);
		if(Yii::app()->user->role > User::SHOPKEEPER) {
			Yii::app()->user->setFlash('error' , yii::t('app','你没有权限'));
			$this->redirect(array('productSet/detailindex' , 'companyId' => $this->companyId,'lid'=>$printset,'papage'=>$papage)) ;
		}
                //Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)||!empty($glids)) {
			if (!empty($ids)) {
				Yii::app()->db->createCommand('update nb_product_set_detail set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')->execute(array( ':companyId' => $this->companyId));
			}
			if (!empty($glids)){
				Yii::app()->db->createCommand('update nb_product_set_group set delete_flag=1 where lid in ('.implode(',' , $glids).') and dpid = :companyId')->execute(array( ':companyId' => $this->companyId));
			}
			$this->redirect(array('productSet/detailindex' , 'companyId' => $companyId,'lid'=>$printset,'papage'=>$papage)) ;
		} else {
			Yii::app()->user->setFlash('error' ,yii::t('app', '请选择要删除的项目'));
			$this->redirect(array('productSet/detailindex' , 'companyId' => $companyId,'lid'=>$printset,'papage'=>$papage)) ;
		}
	}

	public function actionGetSetChildren(){
		$pid = Yii::app()->request->getParam('pid',0);
		if(!$pid){
			Yii::app()->end(json_encode(array('data'=>array(),'delay'=>400)));
		}
		$treeDataSource = array('data'=>array(),'delay'=>400);
		$categories = Helper::getSetCategories($this->companyId,$pid);

		foreach($categories as $c){
			$tmp['name'] = $c['category_name'];
			$tmp['id'] = $c['lid'];
			$treeDataSource['data'][] = $tmp;
		}
		Yii::app()->end(json_encode($treeDataSource));
	}


	public function actionGetChildren(){
		$categoryId = Yii::app()->request->getParam('pid',0);
		$productSetId = Yii::app()->request->getParam('$productSetId',0);
		// var_dump($productSetId);exit;
		if(!$categoryId){
			Yii::app()->end(json_encode(array('data'=>array(),'delay'=>400)));
		}
		$treeDataSource = array('data'=>array(),'delay'=>400);
		$produts=  $this->getProducts($categoryId);
		//var_dump($produts);exit;
		foreach($produts as $c){
			$tmp['name'] = $c['product_name'];
			$tmp['id'] = $c['lid'];
			$treeDataSource['data'][] = $tmp;
		}
		Yii::app()->end(json_encode($treeDataSource));
	}

   public function actionIsDoubleSetDetail(){
		$productId = Yii::app()->request->getParam('productid',0);
        $productSetId = Yii::app()->request->getParam('productSetId',0);
        $companyId = Yii::app()->request->getParam('companyId',0);
        $treeDataSource = array('data'=>FALSE,'delay'=>400);
        $product= ProductSetDetail::model()->find('t.dpid = :dpid and t.set_id = :setid and t.product_id = :productid and t.delete_flag=0',array(':dpid'=>$companyId,':setid'=>$productSetId,':productid'=>$productId));
        //var_dump($productId,$productSetId,$companyId,$product);exit;
        if(!empty($product)){
            $treeDataSource['data'] = TRUE;
		}
		Yii::app()->end(json_encode($treeDataSource));
	}

	private function getProducts($categoryId){
                if($categoryId==0)
                {
                    //var_dump ('2',$categoryId);exit;
                    $products = Product::model()->findAll('dpid=:companyId and delete_flag=0' , array(':companyId' => $this->companyId));
                }else{
                    //var_dump ('3',$categoryId);exit;
                    $products = Product::model()->findAll('dpid=:companyId and category_id=:categoryId and delete_flag=0' , array(':companyId' => $this->companyId,':categoryId'=>$categoryId)) ;
                }
                $products = $products ? $products : array();
                //var_dump($products);exit;
                return $products;
		//return CHtml::listData($products, 'lid', 'product_name');
	}

        private function getSetProducts($categoryId,$productSetId){
                $db = Yii::app()->db;

                if($categoryId==0)
                {
                    $sql = "SELECT lid,product_name from nb_product where dpid=:companyId and delete_flag=0 and lid not in (select product_id from nb_product_set_detail where set_id=:productSetId and dpid=:dpid)";
                    $command=$db->createCommand($sql);
                    $command->bindValue(":companyId" , $this->companyId);
                    $command->bindValue(":dpid" , $this->companyId);
                    $command->bindValue(":productSetId" , $productSetId);
                }else{
                    $sql = "SELECT lid,product_name from nb_product where dpid=:companyId and category_id=:categoryId and delete_flag=0 and lid not in (select product_id from nb_product_set_detail where set_id=:productSetId and dpid=:dpid)";
                    $command=$db->createCommand($sql);
                    $command->bindValue(":companyId" , $this->companyId);
                    $command->bindValue(":dpid" , $this->companyId);
                    $command->bindValue(":productSetId" , $productSetId);
                    $command->bindValue(":categoryId" , $categoryId);
                }
                $products=$command->queryAll();
                $products = $products ? $products : array();
                //var_dump($sql);exit;
                return $products;
		//return CHtml::listData($products, 'lid', 'product_name');
	}

        private function getCategoryId($lid){
                $db = Yii::app()->db;
                $sql = "SELECT category_id from nb_product_set_detail sd,nb_product p where sd.dpid=p.dpid and sd.product_id=p.lid and sd.lid=:lid";
                $command=$db->createCommand($sql);
                $command->bindValue(":lid" , $lid);
                return $command->queryScalar();
	}

        private function getMaxGroupNo($psid){
                $db = Yii::app()->db;
                $sql = "SELECT max(group_no) from nb_product_set_detail where delete_flag = 0 and dpid=:dpid and set_id=:psid";
                $command=$db->createCommand($sql);
                $command->bindValue(":dpid" , $this->companyId);
                $command->bindValue(":psid" , $psid);
                return $command->queryScalar();
	}
        private function getMaxGroupNo2($psid){
                $db = Yii::app()->db;
                $sql = "SELECT max(group_no) from nb_product_set_group where delete_flag = 0 and dpid=:dpid and set_id=:psid";
                $command=$db->createCommand($sql);
                $command->bindValue(":dpid" , $this->companyId);
                $command->bindValue(":psid" , $psid);
                return $command->queryScalar();
	}

    private function getSCategories(){
		$criteria = new CDbCriteria;
		$criteria->with = 'company';
		$criteria->condition =  't.cate_type =2 and t.delete_flag=0 and t.dpid='.$this->companyId ;
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
		$criteria->condition =  't.cate_type !=2 and t.delete_flag=0 and t.dpid='.$this->companyId ;
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

   private function getCategoryList(){
		$criteria = new CDbCriteria;
		//$criteria->with = 'company';
		$criteria->condition =  't.cate_type !=2 and t.delete_flag=0 and t.dpid='.$this->companyId ;
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
	private function getGroupnos($setid){
		if($setid)
		{
			$sql = 'select t1.*,t.product_name from nb_product t left join nb_product_set_detail t1 on( t.dpid = t1.dpid and t1.delete_flag =0 and t.lid = t1.product_id and t1.set_id ='.$setid.' ) where t1.is_select = 1 and t1.lid is not null and t.dpid ='.$this->companyId.' and t.delete_flag = 0 group by t1.group_no' ;
			//$groupnos = ProductSetDetail::model()->findAll('left join nb_product t on(t.dpid = dpid and t.delete_flag = 0)dpid=:companyId and delete_flag=0 and set_id =:setId group by group_no' , array(':companyId' => $this->companyId,':setId'=>$setid));
			$command1 = Yii::app()->db->createCommand($sql);
			$groupnos = $command1->queryAll();
			//var_dump($sql);exit;
		}
		$groupnos = $groupnos ? $groupnos : array();
		return $groupnos;
	}
}