<?php
class CopypromotionController extends BackendController
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
	public function actionCopynormalpromotion(){
		$criteria = new CDbCriteria;
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId;
		$criteria->addCondition('end_time>="'.date('Y-m-d H:i:s',time()).'"');
		$criteria->order = 't.lid desc';
		$models = NormalPromotion::model()->findAll($criteria);

		$db = Yii::app()->db;
		$sql = 'select t.dpid,t.type,t.company_name,t1.is_rest from nb_company t left join nb_company_property t1 on(t1.dpid = t.dpid) where t.delete_flag = 0 and t.type = 1 and t.comp_dpid = '.$this->companyId.' group by t.dpid';
		$command = $db->createCommand($sql);
		$dpids = $command->queryAll();
		$this->render('copynormalpromotion',array(
				'models'=>$models,
				'dpids'=>$dpids,
		));
	}

	public function actionClearnormalpromotion(){
		$criteria = new CDbCriteria;
		$criteria->condition = 't.dpid='.$this->companyId;
	
		$criteria->order =  't.lid desc';
		$models = NormalPromotion::model()->findAll($criteria);
	
		$db = Yii::app()->db;
		$sql = 'select t.dpid,t.type,t.company_name,t1.is_rest from nb_company t left join nb_company_property t1 on(t1.dpid = t.dpid) where t.delete_flag = 0 and t.type = 1 and t.comp_dpid = '.$this->companyId.' group by t.dpid';
		$command = $db->createCommand($sql);
		$dpids = $command->queryAll();
		$this->render('clearnormalpromotion',array(
				'models'=>$models,
				'dpids'=>$dpids,
		));
	}
	public function actionCopyfullsentpromotion(){
		$criteria = new CDbCriteria;
		$criteria->condition =  't.full_type=0 and t.delete_flag=0 and t.dpid='.$this->companyId;
		$criteria->addCondition('end_time>="'.date('Y-m-d H:i:s',time()).'"');
		$criteria->order = 't.lid desc';
		$models = FullSent::model()->findAll($criteria);
	
		$db = Yii::app()->db;
		$sql = 'select t.dpid,t.type,t.company_name,t1.is_rest from nb_company t left join nb_company_property t1 on(t1.dpid = t.dpid) where t.delete_flag = 0 and t.type = 1 and t.comp_dpid = '.$this->companyId.' group by t.dpid';
		$command = $db->createCommand($sql);
		$dpids = $command->queryAll();
		$this->render('copyfullsentpromotion',array(
				'models'=>$models,
				'dpids'=>$dpids,
		));
	}
	public function actionClearfullsentpromotion(){
		$criteria = new CDbCriteria;
		$criteria->condition = 't.full_type=0 and t.dpid='.$this->companyId;
	
		$criteria->order =  't.lid desc';
		$models = FullSent::model()->findAll($criteria);
	
		$db = Yii::app()->db;
		$sql = 'select t.dpid,t.type,t.company_name,t1.is_rest from nb_company t left join nb_company_property t1 on(t1.dpid = t.dpid) where t.delete_flag = 0 and t.type = 1 and t.comp_dpid = '.$this->companyId.' group by t.dpid';
		$command = $db->createCommand($sql);
		$dpids = $command->queryAll();
		$this->render('clearfullsentpromotion',array(
				'models'=>$models,
				'dpids'=>$dpids,
		));
	}
	public function actionCopyfullminuspromotion(){
		$criteria = new CDbCriteria;
		$criteria->condition =  't.full_type=1 and t.delete_flag=0 and t.dpid='.$this->companyId;
		$criteria->addCondition('end_time>="'.date('Y-m-d H:i:s',time()).'"');
		$criteria->order = 't.lid desc';
		$models = FullSent::model()->findAll($criteria);
	
		$db = Yii::app()->db;
		$sql = 'select t.dpid,t.type,t.company_name,t1.is_rest from nb_company t left join nb_company_property t1 on(t1.dpid = t.dpid) where t.delete_flag = 0 and t.type = 1 and t.comp_dpid = '.$this->companyId.' group by t.dpid';
		$command = $db->createCommand($sql);
		$dpids = $command->queryAll();
		$this->render('copyfullminuspromotion',array(
				'models'=>$models,
				'dpids'=>$dpids,
		));
	}
	public function actionClearfullminuspromotion(){
		$criteria = new CDbCriteria;
		$criteria->condition = 't.full_type=1 and t.dpid='.$this->companyId;
	
		$criteria->order =  't.lid desc';
		$models = FullSent::model()->findAll($criteria);
	
		$db = Yii::app()->db;
		$sql = 'select t.dpid,t.type,t.company_name,t1.is_rest from nb_company t left join nb_company_property t1 on(t1.dpid = t.dpid) where t.delete_flag = 0 and t.type = 1 and t.comp_dpid = '.$this->companyId.' group by t.dpid';
		$command = $db->createCommand($sql);
		$dpids = $command->queryAll();
		$this->render('clearfullminuspromotion',array(
				'models'=>$models,
				'dpids'=>$dpids,
		));
	}
	public function actionCopybuysentpromotion(){
		$criteria = new CDbCriteria;
		$criteria->condition =  't.dpid='.$this->companyId.' and t.delete_flag=0';
		$criteria->addCondition('end_time>="'.date('Y-m-d H:i:s',time()).'"');
		$criteria->order = 't.lid desc';
		$models = BuysentPromotion::model()->findAll($criteria);
	
		$db = Yii::app()->db;
		$sql = 'select t.dpid,t.type,t.company_name,t1.is_rest from nb_company t left join nb_company_property t1 on(t1.dpid = t.dpid) where t.delete_flag = 0 and t.type = 1 and t.comp_dpid = '.$this->companyId.' group by t.dpid';
		$command = $db->createCommand($sql);
		$dpids = $command->queryAll();
		$this->render('copybuysentpromotion',array(
				'models'=>$models,
				'dpids'=>$dpids,
		));
	}
	public function actionClearbuysentpromotion(){
		$criteria = new CDbCriteria;
		$criteria->condition = 't.dpid='.$this->companyId;
	
		$criteria->order =  't.lid desc';
		$models = BuysentPromotion::model()->findAll($criteria);
	
		$db = Yii::app()->db;
		$sql = 'select t.dpid,t.type,t.company_name,t1.is_rest from nb_company t left join nb_company_property t1 on(t1.dpid = t.dpid) where t.delete_flag = 0 and t.type = 1 and t.comp_dpid = '.$this->companyId.' group by t.dpid';
		$command = $db->createCommand($sql);
		$dpids = $command->queryAll();
		$this->render('clearbuysentpromotion',array(
				'models'=>$models,
				'dpids'=>$dpids,
		));
	}
	//下发普通活动
	public function actionStorProduct(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$is_sync = DataSync::getInitSync();
		//var_dump($companyId);exit;
		$ids = Yii::app()->request->getPost('ids');
		$codes = Yii::app()->request->getParam('code');//接收活动编码,总部唯一
		$dpid = Yii::app()->request->getParam('dpids');//接收店铺的dpid
		$ckc = Yii::app()->request->getParam('ckc');//判断是否清除以前的活动
		$normalcodes = array();
		$normalcodes = explode(',',$codes);//接收活动编码,总部唯一
		$dpids = array();
		$dpids = explode(',',$dpid);//接收店铺的dpid
		// var_dump($dpids,$normalcodes);exit;

		//****查询公司的产品分类。。。****

		$db = Yii::app()->db;

		//var_dump($catep1,$catep2,$products);exit;
        //Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
        if((!empty($dpids))&&(Yii::app()->user->role < User::SHOPKEEPER)){
        	$transaction = $db->beginTransaction();
        	try{
	        	foreach ($dpids as $dpid){//遍历需要下发活动的店铺

	        		if($ckc==1){
	        			$sqlclear = 'update nb_normal_promotion set delete_flag =1,update_at="'.date('Y-m-d H:i:s',time()).'" where dpid ='.$dpid.' and source = 1';
	        			$db->createCommand($sqlclear)->execute();
	        			$sqlclears = 'update nb_normal_promotion_detail set delete_flag = 1,update_at ="'.date('Y-m-d h:i:s',time()).'" where dpid ='.$dpid.' and normal_promotion_id in(select lid from nb_normal_promotion where delete_flag =0 and source =1 and dpid='.$dpid.')';
	        			$db->createCommand($sqlclears)->execute();
	        		}
	        		
        			foreach ($normalcodes as $normalcode){//遍历需要下发的活动
        				
        				//查询店铺是否已经由此活动
        				$promotionself = NormalPromotion::model()->find('normal_code =:code and dpid=:dpid' , array(':code'=>$normalcode , ':dpid'=>$dpid));

        				//查询总公司是否有此活动
        				$promotioncomp = NormalPromotion::model()->find('normal_code =:code and dpid=:dpid and delete_flag=0' , array(':code'=>$normalcode , ':dpid'=>$this->companyId));

        				//查询此活动的详情
        				$sqlnpd = 'select t.* from nb_normal_promotion_detail t where t.delete_flag = 0 and t.normal_promotion_id ='.$promotioncomp->lid.' and t.dpid ='.$this->companyId;
        				$promotioncompdetails = $db->createCommand($sqlnpd)->queryAll();

        				// p($promotioncompdetails);

        				//查询此活动的试用范围,及那些会员可以享有
        				$sqlnpb = 'select t.* from nb_normal_branduser t where t.delete_flag = 0 and t.normal_promotion_id ='.$promotioncomp->lid.' and t.dpid ='.$this->companyId;
        				$promotionbrandusers = $db->createCommand($sqlnpb)->queryAll();
	        			// p($promotioncompdetails);

        				if(!empty($promotioncompdetails)){//总部详情如果有就下一步判断店铺,没有就提示添加菜品详情
	        				if(!empty($promotionself)){//判断店铺活动是否存在,有就更新,没有就插入活动及详情
	        					$falid = $promotionself->lid;

	        					$promotionself->promotion_title = $promotioncomp->promotion_title;
	        					$promotionself->main_picture = $promotioncomp->main_picture;
	        					$promotionself->promotion_abstract = $promotioncomp->promotion_abstract;
	        					$promotionself->promotion_memo = $promotioncomp->promotion_memo;
	        					$promotionself->promotion_type = $promotioncomp->promotion_type;
	        					$promotionself->can_cupon = $promotioncomp->can_cupon;
	        					$promotionself->begin_time = $promotioncomp->begin_time;
	        					$promotionself->end_time = $promotioncomp->end_time;
	        					$promotionself->weekday = $promotioncomp->weekday;
	        					$promotionself->day_begin = $promotioncomp->day_begin;
	        					$promotionself->day_end = $promotioncomp->day_end;
	        					$promotionself->to_group = $promotioncomp->to_group;
	        					$promotionself->group_id = $promotioncomp->group_id;
	        					$promotionself->order_num = $promotioncomp->order_num;
	        					$promotionself->is_available = $promotioncomp->is_available;
	        					$promotionself->delete_flag = 0;

	        					if($promotionself->save()){//店铺活动更新成功,查询店铺活动详情

	        						$sqlnpd = 'select t.* from nb_normal_promotion_detail t where t.delete_flag = 0 and t.normal_promotion_id ='.$falid.' and t.dpid ='.$dpid;
	        						$promotionselfdetail = $db->createCommand($sqlnpd)->queryAll();
	        						// p($promotionselfdetail);

	        						if ($promotionselfdetail) {//详情里边如果存在就删除再插入
        								$sql = "UPDATE `nb_normal_promotion_detail` SET `delete_flag`='1' WHERE (`normal_promotion_id`='".$falid."') AND (`dpid`='".$dpid."')";
	        							$command = $db->createCommand($sql)->execute();
	        						}
        							foreach ($promotioncompdetails as $promotioncompdetail) {
        								//总部与店铺的产品查询
        								$sqlprodcomp = 'select t.* from nb_product t where t.delete_flag = 0 and t.phs_code ="'.$promotioncompdetail['pro_code'].'" and t.dpid ='.$this->companyId;
	        							$prodcomp = $db->createCommand($sqlprodcomp)->queryAll();

        								$sqlproddpid = 'select t.* from nb_product t where t.delete_flag = 0 and t.phs_code ="'.$promotioncompdetail['pro_code'].'" and t.dpid ='.$dpid;
        								$proddpid = $db->createCommand($sqlproddpid)->queryRow();

        								//总部与店铺的套餐查询
	        							$sqlprodsetcomp = 'select t.* from nb_product_set t where t.delete_flag = 0 and t.pshs_code ="'.$promotioncompdetail['pro_code'].'" and t.dpid ='.$this->companyId;
	        							$prodsetcomp = $db->createCommand($sqlprodsetcomp)->queryAll();

        								$sqlprodsetdpid = 'select t.* from nb_product_set t where t.delete_flag = 0 and t.pshs_code ="'.$promotioncompdetail['pro_code'].'" and t.dpid ='.$dpid;
        								$prodsetdpid = $db->createCommand($sqlprodsetdpid)->queryRow();


        								if((!empty($prodcomp)&&!empty($proddpid)) || (!empty($prodsetcomp)&&!empty($prodsetdpid)) ){//把套餐过滤掉了 , 修改
		        							$se = new Sequence("normal_promotion_detail");
		        							$selid = $se->nextval();
		        							// print_r($proddpid);die;
		        							$data = array(
		        									'lid'=>$selid,
		        									'dpid'=>$dpid,
		        									'create_at'=>date('Y-m-d H:i:s',time()),
		        									'update_at'=>date('Y-m-d H:i:s',time()),
		        									'normal_promotion_id'=>$promotionself['lid'],
		        									'normal_code_pa'=>$promotioncompdetail['normal_code_pa'],//父级活动的编码
		        									'product_id'=>$proddpid['lid']?$proddpid['lid']:$prodsetdpid['lid'],//单品或套餐的id
		        									'pro_code'=>$promotioncompdetail['pro_code'],
		        									'is_set'=>$promotioncompdetail['is_set'],
		        									'is_discount'=>$promotioncompdetail['is_discount'],
		        									'promotion_money'=>$promotioncompdetail['promotion_money'],
		        									'promotion_discount'=>$promotioncompdetail['promotion_discount'],
		        									'order_num'=>$promotioncompdetail['order_num'],
		        									'is_show'=>$promotioncompdetail['is_show'],
		        									'delete_flag'=>'0',
		        									'is_sync'=>$promotioncompdetail['is_sync'],
		        							);
	        							// p($data);
	        							$db->createCommand()->insert('nb_normal_promotion_detail',$data);
		        						}
        							}
	        					}
	        				}else{
	        					//店铺插入活动
	        					$se = new Sequence("normal_promotion");
	        					$falid = $se->nextval();
	        					$datanormalpromotion = array(
	        							'lid'=>$falid,
	        							'dpid'=>$dpid,
	        							'create_at'=>date('Y-m-d H:i:s',time()),
	        							'update_at'=>date('Y-m-d H:i:s',time()),
	        							'normal_code'=> $normalcode,
	        							'source'=> '1',
	        							'promotion_title'=> $promotioncomp->promotion_title,
			        					'main_picture' => $promotioncomp->main_picture,
			        					'promotion_abstract' => $promotioncomp->promotion_abstract,
			        					'promotion_memo' => $promotioncomp->promotion_memo,
			        					'promotion_type' => $promotioncomp->promotion_type,
			        					'can_cupon' => $promotioncomp->can_cupon,
			        					'begin_time' => $promotioncomp->begin_time,
			        					'end_time' => $promotioncomp->end_time,
			        					'weekday' => $promotioncomp->weekday,
			        					'day_begin' => $promotioncomp->day_begin,
			        					'day_end' => $promotioncomp->day_end,
			        					'to_group' => $promotioncomp->to_group,
			        					'group_id' => $promotioncomp->group_id,
			        					'order_num' => $promotioncomp->order_num,
			        					'is_available' => $promotioncomp->is_available,
	        					);
	        					// var_dump($datanormalpromotion);exit;
	        					$command = $db->createCommand()->insert('nb_normal_promotion',$datanormalpromotion);
	        					//店铺插入详情
		        				foreach ($promotioncompdetails as $promotioncompdetail){
		        					$sqlprodcomp = 'select t.* from nb_product t where t.delete_flag = 0 and t.phs_code ="'.$promotioncompdetail['pro_code'].'" and t.dpid ='.$this->companyId;
	        						$prodcomp = $db->createCommand($sqlprodcomp)->queryAll();
	        						$sqlproddpid = 'select t.* from nb_product t where t.delete_flag = 0 and t.phs_code ="'.$promotioncompdetail['pro_code'].'" and t.dpid ='.$dpid;
	        						$proddpid = $db->createCommand($sqlproddpid)->queryRow();

    								//总部与店铺的套餐查询
        							$sqlprodsetcomp = 'select t.* from nb_product_set t where t.delete_flag = 0 and t.pshs_code ="'.$promotioncompdetail['pro_code'].'" and t.dpid ='.$this->companyId;
        							$prodsetcomp = $db->createCommand($sqlprodsetcomp)->queryAll();

    								$sqlprodsetdpid = 'select t.* from nb_product_set t where t.delete_flag = 0 and t.pshs_code ="'.$promotioncompdetail['pro_code'].'" and t.dpid ='.$dpid;
    								$prodsetdpid = $db->createCommand($sqlprodsetdpid)->queryRow();



	        						if((!empty($prodcomp)&&!empty($proddpid)) || (!empty($prodsetcomp)&&!empty($prodsetdpid)) ){
	        							$se = new Sequence("normal_promotion_detail");
	        							$lid = $se->nextval();
	        							$datanorpromdetail = array(
	        									'lid'=>$lid,
	        									'dpid'=>$dpid,
	        									'create_at'=>date('Y-m-d H:i:s',time()),
	        									'update_at'=>date('Y-m-d H:i:s',time()),
	        									'normal_promotion_id'=> $falid,
	        									'normal_code_pa'=> $normalcode,
	        									'product_id'=> $proddpid['lid']?$proddpid['lid']:$prodsetdpid['lid'],
	        									'pro_code' => $promotioncompdetail['pro_code'],
	        									'is_set' => $promotioncompdetail['is_set'],
	        									'is_discount' => $promotioncompdetail['is_discount'],
	        									'promotion_money' => $promotioncompdetail['promotion_money'],
	        									'promotion_discount' => $promotioncompdetail['promotion_discount'],
	        									'order_num' => $promotioncompdetail['order_num'],
	        									'is_show' => $promotioncompdetail['is_show'],
	        									'delete_flag' => '0',
	        							);
	        							//var_dump($datanorpromdetail);exit;
	        							$command = $db->createCommand()->insert('nb_normal_promotion_detail',$datanorpromdetail);
	        						}
		        				}
	        				}
	        				if(!empty($promotionbrandusers)){
	        					foreach ($promotionbrandusers as $promotionbranduser){
	        						$se = new Sequence("normal_branduser");
	        						$lid = $se->nextval();
	        						$datanorprombrands = array(
	        								'lid'=>$lid,
	        								'dpid'=>$dpid,
	        								'create_at'=>date('Y-m-d H:i:s',time()),
	        								'update_at'=>date('Y-m-d H:i:s',time()),
	        								'normal_promotion_id'=> $falid,
	        								'to_group'=> $promotionbranduser['to_group'],
	        								'brand_user_lid'=> $promotionbranduser['brand_user_lid'],
	        								'delete_flag' => '0',
	        						);
	        						$command = $db->createCommand()->insert('nb_normal_branduser',$datanorprombrands);
	        					}
	        				}
        				}else{
        					Yii::app()->user->setFlash('error' , yii::t('app','请设置活动优惠产品！！！'));
        					$this->redirect(array('copypromotion/copynormalpromotion' , 'companyId' => $companyId , 'types'=>'1')) ;
        				}
        			}
	        	}
	        	$transaction->commit();
	        	Yii::app()->user->setFlash('success' , yii::t('app','下发成功！！！'));
	        	$this->redirect(array('copypromotion/copynormalpromotion' , 'companyId' => $companyId , 'types'=>'1')) ;
        	}catch (Exception $e){
        		$transaction->rollback();
        		Yii::app()->user->setFlash('error' , yii::t('app','下发失败！！！'));
        		$this->redirect(array('copypromotion/copynormalpromotion' , 'companyId' => $companyId , 'types'=>'1')) ;
        	}
        }else{
        	Yii::app()->user->setFlash('error' , yii::t('app','无权限进行此项操作！！！'));
        	$this->redirect(array('copypromotion/copynormalpromotion' , 'companyId' => $companyId , 'types'=>'1')) ;
        }

	}

	public function actionClearstorProduct(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
		$codes = Yii::app()->request->getParam('code');//接收活动编码,总部唯一
		$dpid = Yii::app()->request->getParam('dpids');//接收店铺的dpid
		$normalcodes = array();
		$normalcodes = explode(',',$codes);//接收活动编码,总部唯一
		$dpids = array();
		$dpids = explode(',',$dpid);//接收店铺的dpid
	
		$db = Yii::app()->db;
	
		if((!empty($dpids))&&(Yii::app()->user->role < User::SHOPKEEPER)){
			$transaction = $db->beginTransaction();
			try{
				foreach ($dpids as $dpid){//遍历需要下发活动的店铺
					foreach ($normalcodes as $normalcode){//遍历需要下发的活动
						//查询店铺是否已经由此活动
						$sql = 'update nb_normal_promotion set delete_flag=1 where normal_code='.$normalcode.' and dpid='.$dpid;
						$db->createCommand($sql)->execute();
						$sql = 'update nb_normal_promotion_detail set delete_flag=1 where normal_code_pa='.$normalcode.' and dpid ='.$dpid;
						$db->createCommand($sql)->execute();
					}
				}
				$transaction->commit();
				Yii::app()->user->setFlash('success' , yii::t('app','清除成功！！！'));
				$this->redirect(array('copypromotion/clearnormalpromotion' , 'companyId' => $companyId)) ;
			}catch (Exception $e){
				$transaction->rollback();
				Yii::app()->user->setFlash('error' , yii::t('app','清除失败！！！'));
				$this->redirect(array('copypromotion/clearnormalpromotion' , 'companyId' => $companyId)) ;
			}
		}else{
			Yii::app()->user->setFlash('error' , yii::t('app','无权限进行此项操作！！！'));
			$this->redirect(array('copypromotion/clearnormalpromotion' , 'companyId' => $companyId)) ;
		}
	
	}
	//下发满送活动
	public function actionStorfullsent(){
		$companyId = $this->companyId;
		$ids = Yii::app()->request->getPost('ids');
		$codes = Yii::app()->request->getParam('code');//接收活动编码,总部唯一
		$dpid = Yii::app()->request->getParam('dpids');//接收店铺的dpid
		$ckc = Yii::app()->request->getParam('ckc');//判断是否清除以前的活动
		$fullcodes = explode(',',$codes);//接收活动编码,总部唯一
		$dpids = explode(',',$dpid);//接收店铺的dpid
		
		$msg = '';
		$db = Yii::app()->db;
		foreach ($fullcodes as $fullcode){
			$sql = 'select * from nb_full_sent where dpid='.$companyId.' and sole_code="'.$fullcode.'" and delete_flag=0';
			$fullsent = $db->createCommand($sql)->queryRow();
			if($fullsent){
				$sql = 'select * from nb_full_sent_detail where dpid='.$companyId.' and full_sent_id='.$fullsent['lid'].' and delete_flag=0';
				$fullsentDetails = $db->createCommand($sql)->queryAll();
				$fullsql = 'INSERT INTO nb_full_sent (lid,dpid,create_at,update_at,sole_code,title,infor,begin_time,end_time,full_type,full_cost,extra_cost,sent_number,is_available,source) VALUES ';
				$fulldetailsql = 'INSERT INTO nb_full_sent_detail (lid,dpid,create_at,update_at,full_sent_id,product_id,phs_code,is_discount,promotion_money,promotion_discount,number) VALUES ';
				$createAt = $fullsent['create_at'];
				$updateAt = $fullsent['update_at'];
				$soleCode = $fullsent['sole_code'];
				$title = $fullsent['title'];
				$infor = $fullsent['infor'];
				$beginTime = $fullsent['begin_time'];
				$endTime = $fullsent['end_time'];
				$fullType = $fullsent['full_type'];
				$fullCost = $fullsent['full_cost'];
				$extraCost = $fullsent['extra_cost'];
				$sentNumber = $fullsent['sent_number'];
				$isAvailable = $fullsent['is_available'];
				$source = 1;
				foreach ($dpids as $dpid){
					$sql = 'select lid from nb_full_sent where dpid='.$dpid.' and sole_code="'.$soleCode.'" and source=1 and delete_flag=0';
					$hasfullsent = $db->createCommand($sql)->queryRow();
					if($hasfullsent){
						continue;
					}
					
					$se = new Sequence("full_sent");
					$lid = $se->nextval();
					$fullsql .= '('.$lid.','.$dpid.',"'.$createAt.'","'.$updateAt.'","'.$soleCode.'","'.$title.'","'.$infor.'","'.$beginTime.'","'.$endTime.'","'.$fullType.'","'.$fullCost.'","'.$extraCost.'","'.$sentNumber.'","'.$isAvailable.'",'.$source.'),';
					foreach ($fullsentDetails as $detail){
						$pcode = $detail['phs_code'];
						$sql = 'select lid from nb_product where dpid='.$dpid.' and phs_code="'.$pcode.'" and delete_flag=0';
						$product = $db->createCommand($sql)->queryRow();
						if(!$product){
							continue;
						}
						$se = new Sequence("full_sent_detail");
						$lid = $se->nextval();
						$createAt = $detail['create_at'];
						$updateAt = $detail['update_at'];
						$fullsentId = $detail['full_sent_id'];
						$fproId = $product['lid'];
						$isdiscount = $detail['is_discount'];
						$promoney = $detail['promotion_money'];
						$prodiscount = $detail['promotion_discount'];
						$number = $detail['number'];
						$fulldetailsql .= '('.$lid.','.$dpid.',"'.$createAt.'","'.$updateAt.'",'.$fullsentId.','.$fproId.','.$pcode.','.$isdiscount.','.$promoney.','.$prodiscount.','.$number.'),';
					}
				}
				$fullsql = rtrim($fullsql,',');
				$fulldetailsql = rtrim($fulldetailsql,',');
				
				$transaction = $db->beginTransaction();
				try{
					$db->createCommand($fullsql)->execute();
					$db->createCommand($fulldetailsql)->execute();
					$transaction->commit();
				}catch(Exception $e){
					$transaction->rollback();
					$$msg .= $title.':失败  ';
				}
			}
		}
		if($msg==''){
			Yii::app()->user->setFlash('success' , yii::t('app','下发成功！！！'));
		}else{
			Yii::app()->user->setFlash('error' , yii::t('app',$msg));
		}
		$this->redirect(array('copypromotion/copyfullsentpromotion' , 'companyId' => $companyId)) ;
	}
	public function actionClearstorfullsent(){
		$companyId = $this->companyId;
		$codes = Yii::app()->request->getParam('code');//接收活动编码,总部唯一
		$dpid = Yii::app()->request->getParam('dpids');//接收店铺的dpid
		$fullsentcodes = explode(',',$codes);//接收活动编码,总部唯一
		$dpids = explode(',',$dpid);//接收店铺的dpid
	
		$db = Yii::app()->db;
	
		if((!empty($dpids))&&(Yii::app()->user->role < User::SHOPKEEPER)){
			$sqlArr = array();
			foreach ($fullsentcodes as $fullsentcode){//遍历需要下发的活动
				foreach ($dpids as $dpid){//遍历需要下发活动的店铺
					//查询店铺是否已经由此活动
					$sql = 'select lid from nb_full_sent where dpid='.$dpid.' and sole_code="'.$fullsentcode.'"';
					$fullsent = $db->createCommand($sql)->queryRow();
					if($fullsent){
						$sql = 'update nb_full_sent set delete_flag=1 where lid='.$fullsent['lid'].' and dpid='.$dpid;
						array_push($sqlArr, $sql);
						$sql = 'update nb_full_sent_detail set delete_flag=1 where dpid ='.$dpid.' and full_sent_id='.$fullsent['lid'];
						array_push($sqlArr, $sql);
					}
				}
			}
			if($sqlArr){
				$transaction = $db->beginTransaction();
				try{
					foreach ($sqlArr as $sql){
						$db->createCommand($sql)->execute();
					}
					$transaction->commit();
					Yii::app()->user->setFlash('success' , yii::t('app','清除成功！！！'));
				}catch (Exception $e){
					$transaction->rollback();
					Yii::app()->user->setFlash('error' , yii::t('app','清除失败！！！'));
				}
			}
			$this->redirect(array('copypromotion/clearfullsentpromotion' , 'companyId' => $companyId)) ;
		}else{
			Yii::app()->user->setFlash('error' , yii::t('app','无权限进行此项操作！！！'));
			$this->redirect(array('copypromotion/clearfullsentpromotion' , 'companyId' => $companyId)) ;
		}
	
	}
	//下发满减活动
	public function actionStorfullminus(){
		$companyId = $this->companyId;
		$ids = Yii::app()->request->getPost('ids');
		$codes = Yii::app()->request->getParam('code');//接收活动编码,总部唯一
		$dpid = Yii::app()->request->getParam('dpids');//接收店铺的dpid
		$ckc = Yii::app()->request->getParam('ckc');//判断是否清除以前的活动
		$fullcodes = explode(',',$codes);//接收活动编码,总部唯一
		$dpids = explode(',',$dpid);//接收店铺的dpid
	
		$msg = '';
		$db = Yii::app()->db;
		foreach ($fullcodes as $fullcode){
			$sql = 'select * from nb_full_sent where dpid='.$companyId.' and sole_code="'.$fullcode.'" and delete_flag=0';
			$fullsent = $db->createCommand($sql)->queryRow();
			if($fullsent){
				$fullsql = 'INSERT INTO nb_full_sent (lid,dpid,create_at,update_at,sole_code,title,infor,begin_time,end_time,full_type,full_cost,extra_cost,sent_number,is_available,source) VALUES ';
				$createAt = $fullsent['create_at'];
				$updateAt = $fullsent['update_at'];
				$soleCode = $fullsent['sole_code'];
				$title = $fullsent['title'];
				$infor = $fullsent['infor'];
				$beginTime = $fullsent['begin_time'];
				$endTime = $fullsent['end_time'];
				$fullType = $fullsent['full_type'];
				$fullCost = $fullsent['full_cost'];
				$extraCost = $fullsent['extra_cost'];
				$sentNumber = $fullsent['sent_number'];
				$isAvailable = $fullsent['is_available'];
				$source = 1;
				foreach ($dpids as $dpid){
					$sql = 'select lid from nb_full_sent where sole_code="'.$soleCode.'" and source=1 and delete_flag=0';
					$hasfullsent = $db->createCommand($sql)->queryRow();
					if($hasfullsent){
						continue;
					}
					$se = new Sequence("full_sent");
					$lid = $se->nextval();
					$fullsql .= '('.$lid.','.$dpid.',"'.$createAt.'","'.$updateAt.'","'.$soleCode.'","'.$title.'","'.$infor.'","'.$beginTime.'","'.$endTime.'","'.$fullType.'","'.$fullCost.'","'.$extraCost.'","'.$sentNumber.'","'.$isAvailable.'",'.$source.'),';
				}
				$fullsql = rtrim($fullsql,',');
				$res = $db->createCommand($fullsql)->execute();
				if(!$res){
					$msg .= $title.':失败 ;';
				}
			}
		}
		if($msg==''){
			Yii::app()->user->setFlash('success' , yii::t('app','下发成功！！！'));
		}else{
			Yii::app()->user->setFlash('error' , yii::t('app',$msg));
		}
		$this->redirect(array('copypromotion/copyfullminuspromotion' , 'companyId' => $companyId)) ;
	}
	public function actionClearstorfullminus(){
		$companyId = $this->companyId;
		$codes = Yii::app()->request->getParam('code');//接收活动编码,总部唯一
		$dpid = Yii::app()->request->getParam('dpids');//接收店铺的dpid
		$fullsentcodes = explode(',',$codes);//接收活动编码,总部唯一
		$dpids = explode(',',$dpid);//接收店铺的dpid
	
		$db = Yii::app()->db;
	
		if((!empty($dpids))&&(Yii::app()->user->role < User::SHOPKEEPER)){
			$sqlArr = array();
			foreach ($fullsentcodes as $fullsentcode){//遍历需要下发的活动
				foreach ($dpids as $dpid){//遍历需要下发活动的店铺
					//查询店铺是否已经由此活动
					$sql = 'select lid from nb_full_sent where dpid='.$dpid.' and sole_code="'.$fullsentcode.'"';
					$fullsent = $db->createCommand($sql)->queryRow();
					if($fullsent){
						$sql = 'update nb_full_sent set delete_flag=1 where lid='.$fullsent['lid'].' and dpid='.$dpid;
						array_push($sqlArr, $sql);
					}
				}
			}
			if($sqlArr){
				$transaction = $db->beginTransaction();
				try{
					foreach ($sqlArr as $sql){
						$db->createCommand($sql)->execute();
					}
					$transaction->commit();
					Yii::app()->user->setFlash('success' , yii::t('app','清除成功！！！'));
				}catch (Exception $e){
					$transaction->rollback();
					Yii::app()->user->setFlash('error' , yii::t('app','清除失败！！！'));
				}
			}
			$this->redirect(array('copypromotion/clearfullsentpromotion' , 'companyId' => $companyId)) ;
		}else{
			Yii::app()->user->setFlash('error' , yii::t('app','无权限进行此项操作！！！'));
			$this->redirect(array('copypromotion/clearfullsentpromotion' , 'companyId' => $companyId)) ;
		}
	
	}
	//下发买送活动
	public function actionStorbuysent(){
		$companyId = $this->companyId;
		$ids = Yii::app()->request->getPost('ids');
		$codes = Yii::app()->request->getParam('code');//接收活动编码,总部唯一
		$dpid = Yii::app()->request->getParam('dpids');//接收店铺的dpid
		$ckc = Yii::app()->request->getParam('ckc');//判断是否清除以前的活动
		$buycodes = explode(',',$codes);//接收活动编码,总部唯一
		$dpids = explode(',',$dpid);//接收店铺的dpid
		$msg = '';
		$db = Yii::app()->db;
		foreach ($buycodes as $buycode){
			$sql = 'select * from nb_buysent_promotion where dpid='.$companyId.' and sole_code="'.$buycode.'" and delete_flag=0';
			$buysent = $db->createCommand($sql)->queryRow();
			if($buysent){
				$sql = 'select * from nb_buysent_promotion_detail where dpid='.$companyId.' and buysent_pro_id='.$buysent['lid'].' and delete_flag=0';
				$fullsentDetails = $db->createCommand($sql)->queryAll();
				$buysql = 'INSERT INTO nb_buysent_promotion (lid,dpid,create_at,update_at,sole_code,promotion_title,main_picture,promotion_abstract,promotion_memo,promotion_type,can_cupon,begin_time,end_time,weekday,day_begin,day_end,to_group,group_id,order_num,is_available,source) VALUES ';
				$buydetailsql = 'INSERT INTO nb_buysent_promotion_detail (lid,dpid,create_at,update_at,sole_code,buysent_pro_id,fa_sole_code,is_set,product_id,phs_code,buy_num,s_product_id,s_phs_code,sent_num,limit_num,group_no,is_available,source) VALUES ';
				$createAt = $buysent['create_at'];
				$updateAt = $buysent['update_at'];
				$soleCode = $buysent['sole_code'];
				$title = $buysent['promotion_title'];
				$mainpicture = $buysent['main_picture'];
				$proabstract = $buysent['promotion_abstract'];
				$promotionmemo = $buysent['promotion_memo'];
				$promotiontype = $buysent['promotion_type'];
				$cancupon = $buysent['can_cupon'];
				$beginTime = $buysent['begin_time'];
				$endTime = $buysent['end_time'];
				$weekday = $buysent['weekday'];
				$daybegin = $buysent['day_begin'];
				$dayend = $buysent['day_end'];
				$togroup = $buysent['to_group'];
				$groupid = $buysent['group_id'];
				$ordernum = $buysent['order_num'];
				$isAvailable = $buysent['is_available'];
				$source = 1;
				foreach ($dpids as $dpid){
					$sql = 'select lid from nb_buysent_promotion where dpid='.$dpid.' and sole_code="'.$soleCode.'" and source=1 and delete_flag=0';
					$hasbuysent = $db->createCommand($sql)->queryRow();
					if($hasbuysent){
						continue;
					}
					$se = new Sequence("buysent_promotion");
					$buysentId = $se->nextval();
					$buysql .= '('.$buysentId.','.$dpid.',"'.$createAt.'","'.$updateAt.'","'.$soleCode.'","'.$title.'","'.$mainpicture.'","'.$proabstract.'","'.$promotionmemo.'","'.$promotiontype.'","'.$cancupon.'","'.$beginTime.'","'.$endTime.'","'.$weekday.'","'.$daybegin.'","'.$dayend.'","'.$togroup.'","'.$groupid.'","'.$ordernum.'","'.$isAvailable.'",'.$source.'),';
					foreach ($fullsentDetails as $detail){
						$pcode = $detail['phs_code'];
						$spcode = $detail['s_phs_code'];
						if($pcode==$spcode){
							$sql = 'select lid from nb_product where dpid='.$dpid.' and phs_code="'.$pcode.'" and delete_flag=0';
							$product = $db->createCommand($sql)->queryRow();
							if(!$product){
								continue;
							}
							$fproId = $product['lid'];
							$sfproId = $product['lid'];
						}else{
							$sql = 'select lid,phs_code from nb_product where dpid='.$dpid.' and phs_code in("'.$pcode.'","'.$spcode.'") and delete_flag=0';
							$products = $db->createCommand($sql)->queryAll();
							if(count($products)!=2){
								continue;
							}
							foreach ($products as $product){
								if($pcode==$product['phs_code']){
									$fproId = $product['lid'];
								}
								if($spcode==$product['phs_code']){
									$sfproId = $product['lid'];
								}
							}
						}
						
						$se = new Sequence("buysent_promotion_detail");
						$buysentDetailId = $se->nextval();
						$createAt = $detail['create_at'];
						$updateAt = $detail['update_at'];
						$dsoleCode = $detail['sole_code'];
						$fasolecode = $detail['fa_sole_code'];
						$isset = $detail['is_set'];
						$buynum = $detail['buy_num'];
						$sentnum = $detail['sent_num'];
						$limitnum = $detail['limit_num'];
						$groupno = $detail['group_no'];
						$isavailable = $detail['is_available'];
						$source = 1;
						$buydetailsql .= '('.$buysentDetailId.','.$dpid.',"'.$createAt.'","'.$updateAt.'","'.$dsoleCode.'",'.$buysentId.',"'.$fasolecode.'",'.$isset.','.$fproId.',"'.$pcode.'",'.$buynum.','.$sfproId.',"'.$spcode.'",'.$sentnum.','.$limitnum.','.$groupno.','.$isavailable.','.$source.'),';
					}
				}
				$buysql = rtrim($buysql,',');
				$buydetailsql = rtrim($buydetailsql,',');
				
				$transaction = $db->beginTransaction();
				try{
					$db->createCommand($buysql)->execute();
					$db->createCommand($buydetailsql)->execute();
					$transaction->commit();
				}catch(Exception $e){
					$transaction->rollback();
					$msg .= $title.':失败  ';
				}
			}
		}
		if($msg==''){
			Yii::app()->user->setFlash('success' , yii::t('app','下发成功！！！'));
		}else{
			Yii::app()->user->setFlash('error' , yii::t('app',$msg));
		}
		$this->redirect(array('copypromotion/copybuysentpromotion' , 'companyId' => $companyId)) ;
	}
	public function actionClearstorbuysent(){
		$companyId = $this->companyId;
		$codes = Yii::app()->request->getParam('code');//接收活动编码,总部唯一
		$dpid = Yii::app()->request->getParam('dpids');//接收店铺的dpid
		$buysentcodes = explode(',',$codes);//接收活动编码,总部唯一
		$dpids = explode(',',$dpid);//接收店铺的dpid
	
		$db = Yii::app()->db;
	
		if((!empty($dpids))&&(Yii::app()->user->role < User::SHOPKEEPER)){
			$sqlArr = array();
			foreach ($buysentcodes as $buysentcode){//遍历需要下发的活动
				foreach ($dpids as $dpid){//遍历需要下发活动的店铺
					//查询店铺是否已经由此活动
					$sql = 'select lid from nb_buysent_promotion where dpid='.$dpid.' and sole_code="'.$buysentcode.'"';
					$buysent = $db->createCommand($sql)->queryRow();
					if($buysent){
						$sql = 'update nb_buysent_promotion set delete_flag=1 where lid='.$buysent['lid'].' and dpid='.$dpid;
						array_push($sqlArr, $sql);
						$sql = 'update nb_buysent_promotion_detail set delete_flag=1 where dpid ='.$dpid.' and buysent_pro_id='.$buysent['lid'];
						array_push($sqlArr, $sql);
					}
				}
			}
			if($sqlArr){
				$transaction = $db->beginTransaction();
				try{
					foreach ($sqlArr as $sql){
						$db->createCommand($sql)->execute();
					}
					$transaction->commit();
					Yii::app()->user->setFlash('success' , yii::t('app','清除成功！！！'));
				}catch (Exception $e){
					$transaction->rollback();
					Yii::app()->user->setFlash('error' , yii::t('app','清除失败！！！'));
				}
			}
			$this->redirect(array('copypromotion/clearbuysentpromotion' , 'companyId' => $companyId)) ;
		}else{
			Yii::app()->user->setFlash('error' , yii::t('app','无权限进行此项操作！！！'));
			$this->redirect(array('copypromotion/clearbuysentpromotion' , 'companyId' => $companyId)) ;
		}
	
	}
	public function actionStatus(){
		$id = Yii::app()->request->getParam('id');
		$product = Product::model()->find('lid=:id and dpid=:companyId' , array(':id'=>$id,':companyId'=>$this->companyId));
		//var_dump($product->status);
		if($product){
			$product->saveAttributes(array('status'=>$product->status?0:1,'update_at'=>date('Y-m-d H:i:s',time())));
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
		$categories = ProductCategory::model()->findAll('delete_flag=0 and dpid=:companyId' , array(':companyId' => $this->companyId)) ;
		//var_dump($categories);exit;
		return CHtml::listData($categories, 'lid', 'category_name');
	}
	public function actionGetChildren(){
		$pid = Yii::app()->request->getParam('pid',0);
		if(!$pid){
			Yii::app()->end(json_encode(array('data'=>array(),'delay'=>400)));
		}
		$treeDataSource = array('data'=>array(),'delay'=>400);
		$categories = Helper::getCategories($this->companyId,$pid);

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
	private function getDepartments(){
		$departments = Department::model()->findAll('company_id=:companyId',array(':companyId'=>$this->companyId)) ;
		return CHtml::listData($departments, 'department_id', 'name');
	}

}