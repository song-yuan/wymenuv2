<?php
class CopyproductSetController extends BackendController
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
		$arr_dpid = Yii::app()->request->getParam('arr_dpid','');
		$criteria = new CDbCriteria;
		//$criteria->with = array('company','category');
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId;

		$models = ProductSet::model()->findAll($criteria);

		$db = Yii::app()->db;
		$sql = 'select t.dpid,t.type,t.company_name,t1.is_rest from nb_company t left join nb_company_property t1 on(t1.dpid = t.dpid) where t.delete_flag = 0 and t.type = 1 and t.comp_dpid = '.$this->companyId.' group by t.dpid';
		$command = $db->createCommand($sql);
		$dpids = $command->queryAll();
		$sql2 = 'select * from nb_price_group where dpid = '.$this->companyId. ' and delete_flag=0';
        $groups = $db->createCommand($sql2)->queryALL();
		//var_dump($dpids);exit;
		$this->render('index',array(
				'models'=>$models,
				'dpids'=>$dpids,
				'groups'=>$groups,
				'arr_dpid'=>$arr_dpid,
		));
	}

	public function actionStorProductset(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$is_sync = DataSync::getInitSync();
		//var_dump($companyId);exit;
		$ids = Yii::app()->request->getPost('ids');
		$pshscode = Yii::app()->request->getParam('pshscode');
		$groups = Yii::app()->request->getParam('groups');
		$copydpids = Yii::app()->request->getParam('dpids');
		$ctp = Yii::app()->request->getParam('ctp');
		// p($groups);
		$pshscodes = array();
		$pshscodes = explode(',',$pshscode);
		$dpids = array();
		$dpids = explode(',',$copydpids);
		$msgnull = '下列产品暂无套餐，请添加后再进行下发操作：';
		$msgprod = '下列产品尚未下发至选择店铺，请先下发产品再下发配方：';
		$dpidnames = '';
		//var_dump($dpids,$pshscodes);exit;
		// p($dpids);
		//****查询公司的产品分类。。。****

		$db = Yii::app()->db;
		$sql = 'select t.* from nb_product_category t where t.delete_flag = 0 and t.pid = 0 and t.dpid = '.$this->companyId;
		$command = $db->createCommand($sql);
		$catep1 = $command->queryAll();
		$db = Yii::app()->db;
		$sql = 'select t.* from nb_product_category t where t.delete_flag = 0 and t.pid != 0 and t.dpid = '.$this->companyId;
		$command = $db->createCommand($sql);
		$catep2 = $command->queryAll();
		$sql = 'select t.* from nb_product t where t.delete_flag = 0 and t.dpid = '.$this->companyId;
		$command = $db->createCommand($sql);
		$products = $command->queryAll();
        if((!empty($dpids))&&(Yii::app()->user->role < User::SHOPKEEPER)){

        	foreach ($dpids as $dpid){
        		// $transaction = $db->beginTransaction();
        		// try{
	        		if(!empty($catep1)){
	        			foreach($catep1 as $category){
	        				$catep = ProductCategory::model()->find('chs_code=:ccode and dpid=:companyId and delete_flag =0' , array(':ccode'=>$category['chs_code'],':companyId'=>$dpid));
	        				if($catep){
	        					$catep->update_at = date('Y-m-d H:i:s',time());
	        					$catep->category_name = $category['category_name'];
	        					$catep->type = $category['type'];
	        					$catep->cate_type = $category['cate_type'];
	        					$catep->chs_code = $category['chs_code'];
	        					$catep->main_picture = $category['main_picture'];
	        					$catep->order_num = $category['order_num'];
	        					$catep->show_type = $category['show_type'];
	        					$rows = $catep->update();
	        					if (!$rows) {
	        						$dpidnames .= $dpid.',';
	        					}
	        				}else{//var_dump($catep);exit;
	        					$se = new Sequence("product_category");
	        					$id = $se->nextval();
	        					$data = array(
	        							'lid'=>$id,
	        							'dpid'=>$dpid,
	        							'create_at'=>date('Y-m-d H:i:s',time()),
	        							'update_at'=>date('Y-m-d H:i:s',time()),
	        							'category_name'=>$category['category_name'],
	        							'pid'=>"0",
	        							'type'=>$category['type'],
	        							'cate_type'=>$category['cate_type'],
	        							'chs_code'=> $category['chs_code'],
	        							'main_picture'=>$category['main_picture'],
	        							'order_num'=>$category['order_num'],
	        							'show_type'=>$category['show_type'],
	        							'delete_flag'=>'0',
	        							'is_sync'=>$is_sync,
	        					);
	        					$command = $db->createCommand()->insert('nb_product_category',$data);
	        					if (!$command) {
	        						$dpidnames .= $dpid.',';
	        					}
	        					//var_dump(mysql_query($command));exit;
	        					//var_dump($model);exit;
	        					$self = ProductCategory::model()->find('lid=:pid and dpid=:dpid and delete_flag=0' , array(':pid'=>$id,':dpid'=>$dpid ));
	        					//var_dump($self);exit;
	        					if($self->pid!='0'){
	        						$parent = ProductCategory::model()->find('lid=:pid and dpid=:dpid and delete_flag=0' , array(':pid'=>$model->pid,':dpid'=> $dpid));
	        						$self->tree = $parent->tree.','.$self->lid;
	        					} else {
	        						$self->tree = '0,'.$self->lid;
	        					}
	        					$rows = $self->update();
	        					if (!$rows) {
	        						$dpidnames .= $dpid.',';
	        					}
	        				}
	        			}
	        			if($catep2){
	        				foreach ($catep2 as $category){
	        					$sql = 'select t.chs_code,t.tree from nb_product_category t where t.delete_flag =0 and t.lid='.$category['pid'].' and t.dpid='.$this->companyId;
	        					$command = $db->createCommand($sql);
	        					$sqltree = $command->queryRow();
	        					$chscode = $sqltree['chs_code'];
	        					//$chscodetree = $sqltree['tree'];
	        					$catep = ProductCategory::model()->find('chs_code=:ccode and dpid=:companyId and delete_flag=0' , array(':ccode'=>$category['chs_code'],':companyId'=>$dpid));
	        					$cateptree = ProductCategory::model()->find('chs_code=:ccode and dpid=:companyId and delete_flag=0' , array(':ccode'=>$chscode,':companyId'=>$dpid));
	        					//var_dump($cateptree,$sqltree);exit;
	        					if($catep){
	        						$catep->update_at = date('Y-m-d H:i:s',time());
	        						$catep->category_name = $category['category_name'];
	        						$catep->type = $category['type'];
	        						$catep->cate_type = $category['cate_type'];
	        						$catep->show_type = $category['show_type'];
	        						$catep->chs_code = $category['chs_code'];
	        						$catep->main_picture = $category['main_picture'];
	        						$catep->order_num = $category['order_num'];
	        						$rows = $catep->update();
	        						if (!$rows) {
		        						$dpidnames .= $dpid.',';
		        					}
	        					}else{//var_dump($catep);exit;

	        						$se = new Sequence("product_category");
	        						$id = $se->nextval();
	        						$datacate = array(
	        								'lid'=>$id,
	        								'dpid'=>$dpid,
	        								'create_at'=>date('Y-m-d H:i:s',time()),
	        								'update_at'=>date('Y-m-d H:i:s',time()),
	        								'category_name'=>$category['category_name'],
	        								'pid'=>$cateptree['lid'],
	        								'type'=>$category['type'],
	        								'cate_type'=>$category['cate_type'],
	        								'show_type'=>$category['show_type'],
	        								'chs_code'=> $category['chs_code'],
	        								'main_picture'=>$category['main_picture'],
	        								'order_num'=>$category['order_num'],
	        								'delete_flag'=>'0',
	        								'is_sync'=>$is_sync,
	        						);
	        						$command = $db->createCommand()->insert('nb_product_category',$datacate);
	        						if (!$command) {
		        						$dpidnames .= $dpid.',';
		        					}
	        						//var_dump(mysql_query($command));exit;
	        						//var_dump($model);exit;
	        						$self = ProductCategory::model()->find('lid=:pid and dpid=:dpid and delete_flag=0' , array(':pid'=>$id,':dpid'=>$dpid ));
	        						//var_dump($self);exit;
	        						if($self->pid!='0'){
	        							//$parent = ProductCategory::model()->find('lid=:pid and dpid=:dpid' , array(':pid'=>$model->pid,':dpid'=> $dpid));
	        							$self->tree = $cateptree['tree'].','.$self->lid;
	        						} else {
	        							$self->tree = '0,'.$self->lid;
	        						}
	        						$rows = $self->update();
	        						if (!$rows) {
		        						$dpidnames .= $dpid.',';
		        					}
	        					}
	        				}
	        			}

	        		}
	        		if($ctp==2){
	        			$sql ='delete from nb_product_set where source=1 and dpid ='.$dpid;
	        			$re = Yii::app()->db->createCommand($sql)->execute();
	        		}
        			foreach ($pshscodes as $prodsethscode){
        				$prodsets = ProductSet::model()->find('pshs_code=:pscode and dpid=:companyId and delete_flag=0' , array(':pscode'=>$prodsethscode,':companyId'=>$this->companyId));
        				$prodsetso = ProductSet::model()->find('pshs_code=:pscode and dpid=:companyId and delete_flag=0' , array(':pscode'=>$prodsethscode,':companyId'=>$dpid));
        				$categoryId = ProductCategory::model()->find('chs_code=:ccode and dpid=:companyId and delete_flag=0' , array(':ccode'=>$prodsets->chs_code,':companyId'=>$dpid));
        				if(!empty($prodsetso)){
        					$prodsetso->delete_flag = 1;
        					$rows = $prodsetso->update();
        					if (!$rows) {
        						$dpidnames .= $dpid.',';
        					}
                            $rows = Yii::app()->db->createCommand('update nb_product_set_detail set delete_flag=1 where set_id =:setid and dpid = :companyId')
                            ->execute(array(':setid'=> $prodsetso->lid, ':companyId' => $dpid));
                            if (!$rows) {
        						$dpidnames .= $dpid.',';
        					}
                            }
			            if(!empty($prodsets)){
    				            /*
    	        					判断分组,
    	        						如果为0就查询是否已设置分组
    	        							(店铺dpid,)
    	        							如果没有就默认总部,,,
    	        							如果有就查询,


    									如果不为0就查询分组价格,并更新公司属性表里的分组id
    										总部dpid,    $this->companyId
    										分组lid,    $groups
    										菜品id     $product->lid
    	        				*/
    	        				if ($groups==0) {
    	        					$group = CompanyProperty::model()->find('dpid=:dpid and delete_flag=0',array(':dpid'=>$dpid))->price_group_id;
    	        					// p($group);
    	        					if($group){
    	        						$sql = 'select * from nb_price_group_detail where dpid='.$this->companyId.' and price_group_id='.$group.' and product_id='.$prodsets->lid.' and delete_flag=0';
    		        					$gp_info = $db->createCommand($sql)->queryAll();
    		        					// p($gp_info);
    		        					if ($gp_info==null) {
    		        						$price=$prodsets->set_price;
    	        							$mb_price=$prodsets->member_price;
    		        					}else{
    		        						$price=$gp_info[0]['price'];
    		        						$mb_price=$gp_info[0]['mb_price'];
    		        					}
    	        					}else{
    	        						$price=$prodsets->set_price;
    	        						$mb_price=$prodsets->member_price;
    	        					}
    	        				}else{
    	        					$sql = 'select * from nb_price_group_detail where dpid='.$this->companyId.' and price_group_id='.$groups.' and product_id='.$prodsets->lid.' and delete_flag=0';
    	        					$gp_info = $db->createCommand($sql)->queryAll();
    	        					$price=$gp_info[0]['price'];
    	        					$mb_price=$gp_info[0]['mb_price'];

    	        					$model = CompanyProperty::model()->find('dpid=:dpid and delete_flag=0',array(':dpid'=>$dpid));
    				                // p($model);
    				                if ($model) {
    				                    $rows = $model->saveAttributes(array('price_group_id'=>$groups,'update_at'=>date('Y-m-d H:i:s',time())));
    				                    if (!$rows) {
			        						$dpidnames .= $dpid.',';
			        					}
    				                }else{
    				                    $se=new Sequence("company_property");
    				                    $lid = $se->nextval();
    				                    // p($lid);
    				                    $data = array(
    				                            'lid'=>$lid,
    				                            'dpid'=>$dpid,
    				                            'update_at'=>date('Y-m-d H:i:s',time()),
    				                            'price_group_id'=>$groups,
    				                            'delete_flag'=>'0',
    				                    );
    				                    $command = $db->createCommand()->insert('nb_company_property',$data);
    				                    if (!$command) {
			        						$dpidnames .= $dpid.',';
			        					}
    				                }
    	        				}

			                    $se = new Sequence("porduct_set");
			                    $pslid = $se->nextval();
			                    $dataprodset = array(
			                        'lid'=>$pslid,
			                        'dpid'=>$dpid,
			                        'create_at'=>date('Y-m-d H:i:s',time()),
			                        'update_at'=>date('Y-m-d H:i:s',time()),
			        				'category_id'=>$categoryId['lid'],
			                        'pshs_code'=>$prodsethscode,
			        				'chs_code'=>$prodsets->chs_code,
			                        'set_name'=>$prodsets->set_name,
			                        'source'=>1,
			                        'type'=>$prodsets->type,
			                        'simple_code'=>$prodsets->simple_code,
			                        'main_picture'=>$prodsets->main_picture,
			                        'set_price'=>$price,
			                        'member_price'=>$mb_price,
			                        'description'=>$prodsets->description,
			                        'rank'=>$prodsets->rank,
			                    	'sort'=>$prodsets->sort,
			                        'is_member_discount'=>$prodsets->is_member_discount,
			                        'is_special'=>$prodsets->is_special,
			                        'is_discount'=>$prodsets->is_discount,
			                        'status'=>$prodsets->status,
			        				'is_show'=>$prodsets->is_show,
			        				'is_show_wx'=>$prodsets->is_show_wx,
			        				'is_lock'=>$prodsets->is_lock,
			                        'delete_flag'=>'0',
			                        'is_sync'=>$is_sync,
			                    );
			                    // p($dataprodset);exit;
			                    $command = $db->createCommand()->insert('nb_product_set',$dataprodset);
			                    if (!$command) {
	        						$dpidnames .= $dpid.',';
	        					}
			                    $prodsetdetails = ProductSetDetail::model()->findAll('set_id=:lid and dpid=:companyId and delete_flag=0' , array(':lid'=>$prodsets->lid,':companyId'=>$this->companyId));
			                    foreach ($prodsetdetails as $prodsetdetail){
		                            $producto = Product::model()->find('lid=:lid and dpid=:companyId and delete_flag=0' , array(':lid'=>$prodsetdetail->product_id,':companyId'=>$this->companyId));
		                            if(!empty($producto)){
		                                    $product = Product::model()->find('phs_code=:pcode and dpid=:companyId and delete_flag=0' , array(':pcode'=>$producto->phs_code,'companyId'=>$dpid));
		                                    //var_dump($product);exit;
		                                    if(!empty($product)){
		                                            $se = new Sequence("porduct_set_detail");
		                                            $psdlid = $se->nextval();
		                                            $dataprodsetdetail = array(
                                                            'lid'=>$psdlid,
                                                            'dpid'=>$dpid,
                                                            'create_at'=>date('Y-m-d H:i:s',time()),
                                                        	'update_at'=>date('Y-m-d H:i:s',time()),
                                                    		'set_id'=>$pslid,
															'product_id'=>$product->lid,
					        								'price'=>$prodsetdetail->price,
					        								'group_no'=>$prodsetdetail->group_no,
					        								'number'=>$prodsetdetail->number,
					        								'is_select'=>$prodsetdetail->is_select,
					        								'delete_flag'=>'0',
					        								'is_sync'=>$is_sync,
													);
	        						//var_dump($dataprodsetdetail);exit;
	        						$command = $db->createCommand()->insert('nb_product_set_detail',$dataprodsetdetail);
	        						if (!$command) {
		        						$dpidnames .= $dpid.',';
		        					}
	        						}
        						}
        					}
        					//var_dump($prodsetdetails);exit;
        				}

        			}
        		// $transaction->commit();
    			// }catch (Exception $e){
    			// 	$transaction->rollback();
    			// 	//echo 'false';exit;
    			// }
        	}
        	if ($dpidnames != '') {
	        	$arr_dpids = explode(',',$dpidnames);
	        	$arr_dpid = array_unique($arr_dpids);
        	}else{
        		$arr_dpid = '';
    			Yii::app()->user->setFlash('success' , yii::t('app','套餐下发成功！！！'));
        	}
    		$this->redirect(array('copyproductSet/index' , 'companyId' => $companyId,'arr_dpid' => $arr_dpid)) ;

        }else{
        	Yii::app()->user->setFlash('error' , yii::t('app','无权限进行此项操作！！！'));
        	$this->redirect(array('copyproductSet/index' , 'companyId' => $companyId)) ;
        }

	}


}