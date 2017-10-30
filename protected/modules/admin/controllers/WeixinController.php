<?php
class WeixinController extends BackendController
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
        $model = WeixinServiceAccount::model()->find('dpid=:dpid',array(':dpid'=>$this->companyId));
        if(!$model){
        	$model = new WeixinServiceAccount;
        }
        if(Yii::app()->request->isPostRequest){
        	$postData = Yii::app()->request->getPost('WeixinServiceAccount');
        	$se=new Sequence("weixin_service_account");
            $postData['lid'] = $se->nextval();
            $postData['dpid'] = $this->companyId;
            $postData['create_at'] = date('Y-m-d H:i:s',time());
            $postData['update_at'] = date('Y-m-d H:i:s',time());
        	$model->attributes = $postData;
        	if($model->save()){
        		Yii::app()->user->setFlash('success' ,yii::t('app', '设置成功'));
        	}else{
        		$this->redirect(array('/admin/weixin/index','companyId'=>$this->companyId));
        	}
        }
		$this->render('index',array(
				'model'=>$model,
		));
	}
	public function actionMenu() {
		$modelExt = WeixinServiceAccount::model()->find('dpid=:brandId',array(':brandId'=>$this->companyId));
		if(!$modelExt||($modelExt->token=="")){
			 Yii::app()->user->setFlash('error','请先填写微信信息！');
			 $this->redirect(array('/admin/weixin/index','companyId'=>$this->companyId));
		}
		$menuList = Menu::getMenuList($this->companyId);
		if(Yii::app()->request->isPostRequest){
			$menus = Yii::app()->request->getPost('menu');
			$del_sql = "delete from nb_menu where dpid = ".$this->companyId;
			$res_del = Yii::app()->db->createCommand($del_sql)->execute();
			$now = time();
			$sql = "insert into nb_menu values";
			foreach($menus as $menu){
				$se=new Sequence("menu");
	            $lid = $se->nextval();
	            $dpid = $this->companyId;
	            $create_at = date('Y-m-d H:i:s',time());
	            $update_at = date('Y-m-d H:i:s',time());
				$sql = $sql."(".$lid.",".$dpid.",'".$create_at."','".$update_at."',".$menu['h'].",".$menu['v'].",'".$menu['name']."',".$menu['type'].",'".$menu['value']."','111'),";	
			}
			$insert_sql = rtrim($sql,',');
			$res_in = Yii::app()->db->createCommand($insert_sql)->execute();
			
			$menujson = Menu::getMenuJson($this->companyId);
			$wxSdk = new WxSdk($this->companyId);
			$result = $wxSdk->createMenu($menujson);
			
			if($result['errmsg']=="ok"){
				Yii::app()->user->setFlash('success','菜单发布成功');
			}else{
				Yii::app()->user->setFlash('error','菜单发布失败'.$result['errmsg']);
			}	
			$this->redirect(array('/admin/weixin/menu','companyId'=>$this->companyId));
		}
		$this->render('menu',array(
			'menuList'=>$menuList,
		));
	}
        
        public function actionWxmember(){
                $db=Yii::app()->db;
		$companyId = Yii::app()->request->getParam('companyId',"0000000000");
                $o = Yii::app()->request->getParam('o',"0");
                $s = Yii::app()->request->getParam('s',"0");
                $more = Yii::app()->request->getPost('more',"0");
                $findsex = Yii::app()->request->getPost('findsex',"%");
                $agefrom = Yii::app()->request->getPost('agefrom',"0");
                $ageto = Yii::app()->request->getPost('ageto',"100");
                $birthfrom = Yii::app()->request->getPost('birthfrom',"01-01");
                $birthto = Yii::app()->request->getPost('birthto',"12-31");                
                $finduserlevel=Yii::app()->request->getPost('finduserlevel',"0000000000");
                $findweixingroup=Yii::app()->request->getPost('findweixingroup',"0000000000");
                $findcountry=Yii::app()->request->getPost('findcountry',"%");
                $findprovince=Yii::app()->request->getPost('findprovince',"%");
                $findcity=Yii::app()->request->getPost('findcity',"%");
                $pointfrom = Yii::app()->request->getPost('pointfrom',"0");
                if($pointfrom==0)
                {
                    $pointfrom=-999999;
                }
                $pointto = Yii::app()->request->getPost('pointto',"9999999999");
                $remainfrom = Yii::app()->request->getPost('remainfrom',"0");
                if($remainfrom==0)
                {
                    $remainfrom=-999999;
                }
                $remainto = Yii::app()->request->getPost('remainto',"9999999999");
                $datefrom = Yii::app()->request->getPost('datefrom',"2015-01-01");
                $dateto = Yii::app()->request->getPost('dateto',date('Y-m-d',time()));
                $consumetotalfrom = Yii::app()->request->getPost('consumetotalfrom',"0");
                if($consumetotalfrom==0)
                {
                    $consumetotalfrom=-999999;
                }
                $consumetotalto = Yii::app()->request->getPost('consumetotalto',"9999999999");
                $timesfrom = Yii::app()->request->getPost('timesfrom',"0");
                $timesto = Yii::app()->request->getPost('timesto',"999999");
                $cardmobile = Yii::app()->request->getPost('cardmobile',"%");
                if(empty($cardmobile))
                {
                    $cardmobile="%";
                }
                
                //用sql语句查询出所有会员及消费总额、历史积分、余额、
                $sql="select t.lid,t.dpid,t.card_id,t.user_name,t.nickname,t.sex,t.user_birthday,tl.level_name,t.weixin_group,t.country "
                        .",t.province,t.city,t.mobile_num,ifnull(tct.consumetotal,0) as consumetotal,"
                        . "ifnull(tct.consumetimes,0) as consumetimes,ifnull(tpt.pointvalidtotal,0) as pointvalidtotal"
                        . ",ifnull(trt.rechargetotal,0)+ifnull(tcbt.cashbacktotal,0)-ifnull(twxp.wxpay,0) as remaintotal"
                        . " from nb_brand_user t "
                        . " LEFT JOIN (select dpid,user_id,sum(reality_total) as consumetotal,count(*) as consumetimes from nb_order"
                        . " where order_type in ('1','2') and order_status in ('3','4','8') and update_at>='$datefrom 00:00:00' and update_at <='$dateto 23:59:59'"
                        . " group by dpid,user_id) tct on t.dpid=tct.dpid and t.lid=tct.user_id "
                        . " LEFT JOIN (select dpid, brand_user_lid as user_id,sum(point_num) as pointvalidtotal from nb_point_record"
                        . " where end_time >  CURRENT_TIMESTAMP group by dpid,user_id) tpt on tpt.user_id=t.lid and tpt.dpid=t.dpid"
                        . " LEFT JOIN (select dpid, brand_user_lid as user_id,sum(recharge_money) as rechargetotal"
                        . " from nb_recharge_record group by dpid,user_id) trt on t.dpid=trt.dpid and t.lid=trt.user_id"
                        . " LEFT JOIN (select dpid, brand_user_lid as user_id,sum(cashback_num) as cashbacktotal"
                        . " from nb_cashback_record group by dpid,user_id) tcbt on tcbt.dpid=t.dpid and tcbt.user_id=t.lid"
                        . " LEFT JOIN (select to1.dpid, to1.user_id, sum(top.pay_amount) as wxpay"
                        . " from nb_order to1 LEFT JOIN nb_order_pay top ON top.dpid=to1.dpid and top.order_id=to1.lid and top.paytype='10'"
                        . " group by dpid,user_id) twxp on twxp.dpid=t.dpid and twxp.user_id=t.lid"
                        . " LEFT JOIN nb_brand_user_level tl on tl.dpid=t.dpid and tl.lid=t.user_level_lid "
                        . " where t.dpid=".$companyId
                        . " and t.sex like '".$findsex."'"
                        . " and t.country like '".$findcountry."'"
                        . " and t.province like '".$findprovince."'"
                        . " and t.city like '".$findcity."'"
                        . " and (t.card_id like '%".$cardmobile."%' or t.mobile_num like '%".$cardmobile."%')";
                        if($finduserlevel!="0000000000")
                        {
                            $sql.= " and tl.lid = ".$finduserlevel;
                        }
                        if($findweixingroup!="0000000000")
                        {
                            $sql.= " and t.weixin_group = ".$findweixingroup;
                        }
                        $yearnow=date('Y',time());
                        $yearbegin=$yearnow-$ageto;
                        $yearend=$yearnow-$agefrom;
                        $sql.= " and substring(ifnull(t.user_birthday,'1919-06-26'),1,4) >= '".$yearbegin."' and substring(ifnull(t.user_birthday,'1919-06-26'),1,4) <= '".$yearend."'";
                        $sql.= " and substring(ifnull(t.user_birthday,'1919-06-26'),6,5) >= '".$birthfrom."' and substring(ifnull(t.user_birthday,'1919-06-26'),6,5) <= '".$birthto."'";
                        $sql.=" and ifnull(tpt.pointvalidtotal,0) >= ".$pointfrom." and ifnull(tpt.pointvalidtotal,0)<=".$pointto;
                        $sql.=" and ifnull(trt.rechargetotal,0)+ifnull(tcbt.cashbacktotal,0)-ifnull(twxp.wxpay,0) >= "
                                .$remainfrom." and ifnull(trt.rechargetotal,0)+ifnull(tcbt.cashbacktotal,0)-ifnull(twxp.wxpay,0) <=".$remainto;
                        $sql.=" and ifnull(tct.consumetotal,0) >= ".$consumetotalfrom." and ifnull(tct.consumetotal,0)<=".$consumetotalto;
                        $sql.=" and ifnull(tct.consumetimes,0) >= ".$timesfrom." and ifnull(tct.consumetimes,0)<=".$timesto;
                        
                $sort=" ASC";
                if($s=="1")
                {
                    $sort=" DESC";
                }
                $order=" consumetotal";
                if($o=="1")
                {
                    $order=" pointvalidtotal";
                }else{
                    $order=" remaintotal";
                }
                $sql=$sql." order by ".$order.$sort;
                //echo $sql;exit;
                //$models=$db->createCommand($sql)->queryAll();
//		$criteria = new CDbCriteria;
//		$criteria->condition =  ' t.dpid='.$companyId;
                $pages = new CPagination($db->createCommand("select count(*) from (".$sql.") a")->queryScalar());
                $pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();
                //echo $sql;exit;
//		$pages->applyLimit($criteria);                
//                $criteria->with =  array("level");                
//		$models = BrandUser::model()->findAll($criteria);
                
                //检索条件会员等级
                $criteriauserlevel = new CDbCriteria;
		$criteriauserlevel->condition =  ' t.delete_flag=0 and t.dpid='.$companyId;
		$userlevels = BrandUserLevel::model()->findAll($criteriauserlevel);
                
                //一下为测试，要调用微信接口，去具体的数据
                ////////////////////////////调用接口，从数据库取出微信分组数据，对应相应的名称，整合成数组。cf
                $weixingroups=array(array("id"=>"100","name"=>"分组1"),array("id"=>"200","name"=>"分组2"));
                
                //获取国家、省、市
                $sqlcountry="select distinct country from nb_brand_user where dpid=".$companyId;
                $modelcountrys=$db->createCommand($sqlcountry)->queryAll();
                //$findcountry="中国";
                
                $sqlprovince="select distinct country,province from nb_brand_user where dpid=".$companyId;
                $modelprovinces=$db->createCommand($sqlprovince)->queryAll();
                //$findprovince="上海市";
                
                $sqlcity="select distinct country,province,city from nb_brand_user where dpid=".$companyId;
                $modelcitys=$db->createCommand($sqlcity)->queryAll(); 
                //$findcity="杨浦区";
		
		//var_dump($models);exit;
		$this->render('wxmember',array(
				'models'=>$models,
                                'findsex'=>$findsex,
                                'agefrom'=>$agefrom,
                                'ageto'=>$ageto,
                                'birthfrom'=>$birthfrom,
                                'birthto'=>$birthto,
                                'userlevels'=>$userlevels,
                                'finduserlevel'=>$finduserlevel,
                                'weixingroups'=>$weixingroups,
                                'findweixingroup'=>$findweixingroup,
                                'modelcountrys'=>$modelcountrys,
                                'modelprovinces'=>$modelprovinces,
                                'modelcitys'=>$modelcitys,
                                'findcountry'=>$findcountry,
                                'findprovince'=>$findprovince,
                                'findcity'=>$findcity,
                                'pointfrom'=>$pointfrom,
                                'pointto'=>$pointto,
                                'remainfrom'=>$remainfrom,
                                'remainto'=>$remainto,
                                'datefrom'=>$datefrom,
                                'dateto'=>$dateto,
                                'consumetotalfrom'=>$consumetotalfrom,
                                'consumetotalto'=>$consumetotalto,
                                'timesfrom'=>$timesfrom,
                                'timesto'=>$timesto,
                                'cardmobile'=>$cardmobile,
                                'more'=>$more,
                                'order'=>$o,
                                'sort'=>$s,
				'pages'=>$pages
		));
	}
	
        public function actionConsumelist(){
                $db=Yii::app()->db;
		$companyId = Yii::app()->request->getParam('companyId',"0000000000");
                $lid = Yii::app()->request->getParam('lid',"0000000000");
                $criteria = new CDbCriteria;
                $criteria->condition =  't.dpid='.$companyId.' and t.user_id='.$lid.' and order_status in ("3","4","8") and order_type in ("1","2")';
                $criteria->order = ' t.update_at desc '; 
                $pages = new CPagination(Order::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
                $models = Order::model()->findAll($criteria);
                $this->renderPartial('consumelist' , array(
				'models' => $models,
                                'pages'=>$pages
		));
        }
        
        public function actionPointlist(){
                $db=Yii::app()->db;
		$companyId = Yii::app()->request->getParam('companyId',"0000000000");
                $lid = Yii::app()->request->getParam('lid',"0000000000");
                $criteria = new CDbCriteria;
                $criteria->condition =  't.dpid='.$companyId.' and t.brand_user_lid='.$lid.' and delete_flag=0';
                $criteria->order = ' t.end_time desc '; 
                $pages = new CPagination(PointRecord::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
                $models = PointRecord::model()->findAll($criteria);
                $this->renderPartial('pointlist' , array(
				'models' => $models,
                                'pages'=>$pages
		));
        }
        
        public function actionCashbacklist(){
                $db=Yii::app()->db;
		//$companyId = Yii::app()->request->getParam('companyId',"0000000000");
                $lid = Yii::app()->request->getParam('lid',"0000000000");
                $sql = 'select k.* from '
                        . ' ((select t1.dpid,t1.update_at,t2.brand_user_lid,t1.reality_total as totalmoney,t2.point_type, t2.cashback_num from'
                        . ' nb_order t1,nb_cashback_record t2 where t1.dpid=t2.dpid and t2.point_type=0 and t2.type_lid=t1.lid)'
                        . ' union'
                        . ' (select t3.dpid,t3.update_at,t3.brand_user_lid,t3.recharge_money as totalmoney,"1" as point_type,t3.cashback_num from'
                        . ' nb_recharge_record t3 ))'
                        . ' k where k.brand_user_lid='.$lid
                        .' order by k.update_at desc';
                //echo $sql;exit;
                $count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();
                $this->renderPartial('cashbacklist' , array(
				'models' => $models,
                                'pages'=>$pages
		));
        }
        
        public function actionBranduserDetail(){
                $db=Yii::app()->db;
		$companyId = Yii::app()->request->getParam('companyId',"0000000000");
                $lid = Yii::app()->request->getParam('lid',"0000000000");
                $wg = Yii::app()->request->getParam('wg',"0");
                $ul = Yii::app()->request->getParam('ul',"");
                $criteria = new CDbCriteria;
                $criteria->condition =  't.dpid='.$companyId.' and t.lid='.$lid;
                //$criteria->order = ' t.end_time desc '; 
                $pages = new CPagination(BrandUser::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
                $model = BrandUser::model()->find($criteria);
                //var_dump($model);exit;
                $this->renderPartial('branduserdetail' , array(
				'model' => $model,
                                'wg'=>$wg,
                                'ul'=>$ul
		));
        }
}