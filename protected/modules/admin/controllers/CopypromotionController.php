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
		$categoryId = Yii::app()->request->getParam('cid',0);
		$criteria = new CDbCriteria;
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId;

		$criteria->addCondition('end_time>="'.date('Y-m-d H:i:s',time()).'"');

		$models = NormalPromotion::model()->findAll($criteria);

		$db = Yii::app()->db;
		$sql = 'select t.dpid,t.company_name from nb_company t where t.delete_flag = 0 and t.comp_dpid = '.$this->companyId;
		$command = $db->createCommand($sql);
		$dpids = $command->queryAll();
		//var_dump($dpids);exit;
		$categories = $this->getCategories();
//                var_dump($categories);exit;
		$this->render('copynormalpromotion',array(
				'models'=>$models,
				'dpids'=>$dpids,
				'categories'=>$categories,
				'categoryId'=>$categoryId
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
//         	$transaction = $db->beginTransaction();
//         	try{
	        	foreach ($dpids as $dpid){//遍历需要下发活动的店铺

        			foreach ($normalcodes as $normalcode){//遍历需要下发的活动

        				//查询店铺是否已经由此活动
        				$promotionself = NormalPromotion::model()->find('normal_code =:code and dpid=:dpid and delete_flag=0' , array(':code'=>$normalcode , ':dpid'=>$dpid));

        				//查询总公司是否有此活动
        				$promotioncomp = NormalPromotion::model()->find('normal_code =:code and dpid=:dpid and delete_flag=0' , array(':code'=>$normalcode , ':dpid'=>$this->companyId));

        				//查询此活动的详情
        				$sqlnpd = 'select t.* from nb_normal_promotion_detail t where t.delete_flag = 0 and t.normal_promotion_id ='.$promotioncomp->lid.' and t.dpid ='.$this->companyId;
        				$promotioncompdetails = $db->createCommand($sqlnpd)->queryAll();

        				//查询此活动的试用范围,及那些会员可以享有
        				$sqlnpb = 'select t.* from nb_normal_branduser t where t.delete_flag = 0 and t.normal_promotion_id ='.$promotioncomp->lid.' and t.dpid ='.$this->companyId;
        				$promotionbrandusers = $db->createCommand($sqlnpb)->queryAll();
	        			// p($promotioncompdetails);

        				if(!empty($promotioncompdetails)){//总部详情如果有就下一步判断店铺,没有就提示添加菜品详情
	        				if(!empty($promotionself)){//判断店铺活动是否存在,有就更新,没有就插入
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

	        					if($promotionself->save()){//店铺活动更新成功,查询店铺活动详情

	        						$sqlnpd = 'select t.* from nb_normal_promotion_detail t where t.delete_flag = 0 and t.normal_promotion_id ='.$falid.' and t.dpid ='.$dpid;
	        						$promotionselfdetail = $db->createCommand($sqlnpd)->queryAll();
	        						// p($promotionselfdetail);

	        						if ($promotionselfdetail) {//详情里边如果存在就删除再插入
        								$sql = "UPDATE `nb_normal_promotion_detail` SET `delete_flag`='1' WHERE (`normal_promotion_id`='".$falid."') AND (`dpid`='".$dpid."')";
	        							$command = $db->createCommand($sql)->execute();
	        						}
        							foreach ($promotioncompdetails as $promotioncompdetail) {
        								$sqlprodcomp = 'select t.* from nb_product t where t.delete_flag = 0 and t.phs_code ="'.$promotioncompdetail['pro_code'].'" and t.dpid ='.$this->companyId;
	        							$prodcomp = $db->createCommand($sqlprodcomp)->queryAll();
        								$sqlproddpid = 'select t.* from nb_product t where t.delete_flag = 0 and t.phs_code ="'.$promotioncompdetail['pro_code'].'" and t.dpid ='.$dpid;
        								$proddpid = $db->createCommand($sqlproddpid)->queryRow();
        								if(!empty($prodcomp)&&!empty($proddpid)){
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
		        									'product_id'=>$proddpid['lid'],//单品或套餐的id
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
		        							$db->createCommand()->insert('nb_normal_promotion_detail',$data);
		        						}
        							}
	        					}
	        				}else{
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

		        				foreach ($promotioncompdetails as $promotioncompdetail){
		        					$sqlprodcomp = 'select t.* from nb_product t where t.delete_flag = 0 and t.phs_code ="'.$promotioncompdetail['pro_code'].'" and t.dpid ='.$this->companyId;
	        						$prodcomp = $db->createCommand($sqlprodcomp)->queryAll();
	        						$sqlproddpid = 'select t.* from nb_product t where t.delete_flag = 0 and t.phs_code ="'.$promotioncompdetail['pro_code'].'" and t.dpid ='.$dpid;
	        						$proddpid = $db->createCommand($sqlproddpid)->queryRow();

	        						if(!empty($prodcomp)&&!empty($proddpid)){
	        							$se = new Sequence("normal_promotion_detail");
	        							$lid = $se->nextval();
	        							$datanorpromdetail = array(
	        									'lid'=>$lid,
	        									'dpid'=>$dpid,
	        									'create_at'=>date('Y-m-d H:i:s',time()),
	        									'update_at'=>date('Y-m-d H:i:s',time()),
	        									'normal_promotion_id'=> $falid,
	        									'normal_code_pa'=> $normalcode,
	        									'product_id'=> $proddpid['lid'],
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
// 	        	$transaction->commit();
// 	        	Yii::app()->user->setFlash('success' , yii::t('app','下发成功！！！'));
// 	        	$this->redirect(array('copypromotion/copynormalpromotion' , 'companyId' => $companyId , 'types'=>'1')) ;
//         	}catch (Exception $e){
//         		$transaction->rollback();
//         		Yii::app()->user->setFlash('error' , yii::t('app','下发失败！！！'));
//         		$this->redirect(array('copypromotion/copynormalpromotion' , 'companyId' => $companyId , 'types'=>'1')) ;
//         	}
        }else{
        	Yii::app()->user->setFlash('error' , yii::t('app','无权限进行此项操作！！！'));
        	$this->redirect(array('copypromotion/copynormalpromotion' , 'companyId' => $companyId , 'types'=>'1')) ;
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