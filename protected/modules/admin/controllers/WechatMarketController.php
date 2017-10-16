<?php

class WechatMarketController extends BackendController {

	public function actionList() {

		$companyId = $this->companyId;
		$iscom = Yii::app()->db->createCommand('select type from nb_company where delete_flag =0 and dpid ='.$companyId)->queryRow();
		$isrest = Yii::app()->db->createCommand('select is_rest from nb_company_property where delete_flag =0 and dpid ='.$companyId)->queryRow();
    	//var_dump($iscom);exit;

		if($isrest){
			$isrest = $isrest['is_rest'];
		}else{
			$isrest = 0;
		}
		$this->render('list',array(
			'isrest' => $isrest,
			'iscom' => $iscom,
			));
	}

	public function actionCube() {
		$this->render('cube');
	}

	public function actionWxmembercube(){
		$db=Yii::app()->db;
		$companyId = Yii::app()->request->getParam('companyId',"0000000000");
		$o = Yii::app()->request->getParam('o',"0");
		$s = Yii::app()->request->getParam('s',"0");
		$more = Yii::app()->request->getPost('more',"0");
		$findsex = Yii::app()->request->getPost('findsex',"%");//性别
		$agefrom = Yii::app()->request->getPost('agefrom',"0");//起始年龄
		$ageto = Yii::app()->request->getPost('ageto',"100");//终止年龄
		$birthfrom = Yii::app()->request->getPost('birthfrom',"01-01");//起始生日
		$birthto = Yii::app()->request->getPost('birthto',"12-31");//终止生日
		$finduserlevel=Yii::app()->request->getPost('finduserlevel',"0000000000");//会员等级
		$findweixingroup=Yii::app()->request->getPost('findweixingroup',"0000000000");//会员来源店铺
		$noordertime=Yii::app()->request->getPost('noordertime',"%");//未消费时长

		//省 市 地区
		$findprovince=Yii::app()->request->getPost('province',"%");
		$findcity=Yii::app()->request->getPost('city',"%");
		$findarea=Yii::app()->request->getPost('area',"%");

		$pointfrom = Yii::app()->request->getPost('pointfrom',"0");
		$source = Yii::app()->request->getPost('source',"");//来源
		$foucsfrom = Yii::app()->request->getPost('foucsfrom',"");//关注开始时间
		$foucsto = Yii::app()->request->getPost('foucsto',"");//关注结束时间时间

		if($pointfrom==0)
		{
			$pointfrom=-999999;
		}
		$pointto = Yii::app()->request->getPost('pointto',"9999999999");
		$remainfrom = Yii::app()->request->getPost('remainfrom',"0");
		// if($remainfrom==0)
		// {
		// 	$remainfrom=-999999;
		// }
		$remainto = Yii::app()->request->getPost('remainto',"9999999999");

		//时间范围
		$datefrom = Yii::app()->request->getPost('datefrom',"2015-01-01");
		$dateto = Yii::app()->request->getPost('dateto',date('Y-m-d',time()));

		//总消费额范围
		$consumetotalfrom = Yii::app()->request->getPost('consumetotalfrom',"0");
		// if($consumetotalfrom==0)
		// {
		// 	$consumetotalfrom=-999999;
		// }
		$consumetotalto = Yii::app()->request->getPost('consumetotalto',"9999999999");

		//消费次数
		$timesfrom = Yii::app()->request->getPost('timesfrom',"0");
		$timesto = Yii::app()->request->getPost('timesto',"999999");

		$cardmobile = Yii::app()->request->getPost('cardmobile',"%");//会员卡号  手机号
		if(empty($cardmobile))
		{
			$cardmobile="%";
		}

		//未消费时长数据处理
		if($noordertime!="%"){
			$begintime = date('Y-m-d',strtotime("-".$noordertime." month"));
			$endtime = date('Y-m-d',time());
			$sql = 'select ifnull(k.user_id,0000000000) as user_id from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$companyId.' and k.create_at >="'.$begintime.' 00:00:00" and k.create_at <="'.$endtime.' 23:59:59" group by k.user_id';
			$orders = $db->createCommand($sql)->queryAll();
			$users ='0000000000';
			foreach ($orders as $order){
				$users = $users .','.$order['user_id'];
			}
		}else{
			$users = '0000000000';
		}
		//var_dump($sql);exit;
		//用sql语句查询出所有会员及消费总额、历史积分、余额、

		//来源店铺条件 省 市 地区
		if($findprovince!="请选择..")
		{
			$sqlp= " and com.province like '".$findprovince."'";
		}else{
			$sqlp='';
		}
		if($findcity!="请选择..")
		{
			$sqlc= " and com.city like '".$findcity."'";
		}else{
			$sqlc='';
		}
		if($findarea!="请选择..")
		{
			$sqla= " and com.county_area like '".$findarea."'";
		}else{
			$sqla='';
		}

		$sql="select t.lid,t.dpid,t.create_at,t.card_id,t.user_name,t.nickname,t.sex,t.user_birthday,tl.level_name,t.weixin_group "
		.",com.province,com.city,com.county_area,com.company_name,t.mobile_num,t.remain_money,ifnull(tct.consumetotal,0) as consumetotal,"
		. "ifnull(tct.consumetimes,0) as consumetimes". " from nb_brand_user t "
		. " LEFT JOIN nb_company com on (com.dpid = t.weixin_group )"
		. " LEFT JOIN (select dpid,user_id,sum(reality_total) as consumetotal,count(*) as consumetimes from nb_order"
			. " where order_type in ('1','2','6') and order_status in ('3','4','8') and update_at>='$datefrom 00:00:00' and update_at <='$dateto 23:59:59'"
				. " group by dpid,user_id) tct on (t.weixin_group=tct.dpid and t.lid=tct.user_id) "
		. " LEFT JOIN nb_brand_user_level tl on tl.dpid=t.dpid and tl.lid=t.user_level_lid and tl.delete_flag=0 and tl.level_type=1 "
		. " where t.lid not in(".$users.") and (t.dpid=".$companyId." or t.weixin_group =".$companyId.")".$sqlp.$sqlc.$sqla;
	        //echo $sql;exit;

		if($finduserlevel!="0000000000")
		{
			$sql.= " and tl.lid = ".$finduserlevel;
		}
		if($findsex!="%")
		{
			$sql.= "and t.sex like '".$findsex."'";
		}
		if($cardmobile!="%")
		{
			$sql.= " and (t.card_id like '%".$cardmobile."%' or t.mobile_num like '%".$cardmobile."%')";
		}
		if($findweixingroup!="0000000000")
		{
			$sql.= " and t.weixin_group = ".$findweixingroup;
		}
		if($source){
			$sql.= " and com.company_name like '%".$source."%'";
		}

		//关注时间数据处理
		if($foucsfrom){
			$sql .= " and t.create_at >='".$foucsfrom."'";
		}
		if($foucsto){
			$sql .= " and t.create_at <='".$foucsto."'";
		}


		$yearnow=date('Y',time());
		$yearbegin=$yearnow-$ageto;
		$yearend=$yearnow-$agefrom;
		$sql.= " and substring(ifnull(t.user_birthday,'1917-01-01'),1,4) >= '".$yearbegin."' and substring(ifnull(t.user_birthday,'1917-01-01'),1,4) <= '".$yearend."'";
		$sql.= " and substring(ifnull(t.user_birthday,'1917-01-01'),6,5) >= '".$birthfrom."' and substring(ifnull(t.user_birthday,'1917-01-01'),6,5) <= '".$birthto."'";
		//$sql.=" and ifnull(tpt.pointvalidtotal,0) >= ".$pointfrom." and ifnull(tpt.pointvalidtotal,0)<=".$pointto;
		//$sql.=" and ifnull(trt.rechargetotal,0)+ifnull(tcbt.cashbacktotal,0)-ifnull(twxp.wxpay,0) >= "
		//	.$remainfrom." and ifnull(trt.rechargetotal,0)+ifnull(tcbt.cashbacktotal,0)-ifnull(twxp.wxpay,0) <=".$remainto;
		$sql.=" and ifnull(tct.consumetotal,0) >= ".$consumetotalfrom." and ifnull(tct.consumetotal,0)<=".$consumetotalto;
		$sql.=" and ifnull(tct.consumetimes,0) >= ".$timesfrom." and ifnull(tct.consumetimes,0)<=".$timesto;
		//echo $sql;exit;
		$sort=" ASC";
		if($s=="1")
		{
			$sort=" DESC";
		}
		$order=" consumetotal";

		$sql=$sql." order by ".$order.$sort;
		$allmodels=$db->createCommand($sql)->queryAll();
		$alluserid = array();
		foreach ($allmodels as $value) {
			array_push($alluserid,$value['lid']);
		}
		$alluserid=implode(',',$alluserid);
		// echo $alluserid;exit;
		$pages = new CPagination($db->createCommand("select count(*) from (".$sql.") a")->queryScalar());
		$pages -> pageSize = 100;
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata ->queryAll();

		//检索条件会员等级
		$criteriauserlevel = new CDbCriteria;
		$criteriauserlevel->condition =  ' t.delete_flag=0 and t.dpid='.$companyId;
		$userlevels = BrandUserLevel::model()->findAll($criteriauserlevel);

		//一下为测试，要调用微信接口，去具体的数据
		//调用接口，从数据库取出微信分组数据，对应相应的名称，整合成数组。cf
		$weixingroups=array(array("id"=>"100","name"=>"分组1"),array("id"=>"200","name"=>"分组2"));


	//var_dump($models);exit;
		$this->render('wxmembercube',array(
			'models'=>$models,
			'alluserid'=>$alluserid,
			'findsex'=>$findsex,
			'agefrom'=>$agefrom,
			'ageto'=>$ageto,
			'birthfrom'=>$birthfrom,
			'birthto'=>$birthto,
			'userlevels'=>$userlevels,
			'finduserlevel'=>$finduserlevel,
			'weixingroups'=>$weixingroups,
			'findweixingroup'=>$findweixingroup,
			'noordertime'=>$noordertime,
			'province'=>$findprovince,
			'city'=>$findcity,
			'area'=>$findarea,
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
			'pages'=>$pages,
			'source'=>$source,
			'foucsfrom'=>$foucsfrom,
			'foucsto'=>$foucsto,
			));
		}

		public function actionAddprod() {
			$this->layout = '/layouts/main_picture';
			$users = Yii::app()->request->getParam('users',0);
			$all = Yii::app()->request->getParam('all',0);

			$criteria = new CDbCriteria;
			$criteria->condition =  't.is_available = 0 and t.delete_flag=0 and t.dpid='.$this->companyId;
			$criteria->order = ' t.lid asc ';
			$models = Cupon::model()->findAll($criteria);

			// p($models);
			$this->render('addprod' , array(
				'all' => $all,
				'models' => $models,
				'users' => $users,
				'action' => $this->createUrl('wechatMarket/addprod' , array('companyId'=>$this->companyId))
				));
		}

		public function actionStorsentwxcard(){

			$is_sync = DataSync::getInitSync();
			$plids = Yii::app()->request->getParam('plids');
			$users = Yii::app()->request->getParam('users');
			$dpid = $this->companyId;
			$materialnums = array();
			$materialnums = explode(';',$plids);

			$userarrays = array();
			$userarrays = explode(',',$users);
			$msg = '';
			$db = Yii::app()->db;
		
			foreach ($userarrays as $userarray){
				$sql = 'select * from nb_brand_user where lid='.$userarray;
				$openId = $db->createCommand($sql)->queryRow();
			//var_dump($userarray);exit;
				foreach ($materialnums as $materialnum){
					$materials = array();
					$materials = explode(',',$materialnum);
					$plid = $materials[0];
					$pcode = $materials[1];
				//var_dump($plid.'@'.$pcode);exit;
					$sql = 'select * from nb_brand_user where lid='.$plid.' and dpid='.$this->companyId.' and delete_flag=0';
					$cupons = $db->createCommand($sql)->queryRow();
				//var_dump($buysentprodetail);exit;
					if(!empty($cupons)&&!empty($plid)){
						$timetype = $cupons['time_type'];
						if($timetype=='1'){
							$validay = $cupons['begin_time'];
							$colseday = $cupons['end_time'];
						}else{
							$validay = date('Y-m-d H:i:s',strtotime('+'.$cupons['day_begin'].' day'));
							$colseday = date('Y-m-d H:i:s',strtotime($validay.'+'.$cupons['day'].' day'));
						}

						$se = new Sequence("cupon_branduser");
						$id = $se->nextval();
					//$code=new Sequence("sole_code");
					//$sole_code = $code->nextval();
					//Yii::app()->end(json_encode(array('status'=>true,'msg'=>'成功','matids'=>$prodmaterials['material_name'],'prodid'=>$matenum,'tasteid'=>$tasteid)));
						$data = array(
							'lid'=>$id,
							'dpid'=>$dpid,
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
							'cupon_id'=>$plid,
							'cupon_source'=>'2',
							'source_id'=>'0000000000',
							'to_group'=>'3',
							'brand_user_lid'=>$userarray,
							'valid_day'=>$validay,
							'close_day'=>$colseday,
							'is_used'=>'1',
							'used_time'=>'0000-00-00 00:00:00',
							'delete_flag'=>'0',
							'is_sync'=>$is_sync,
							);

					//$msg = $prodid.'@@'.$mateid.'@@'.$prodmaterials['product_name'].'@@'.$prodmaterials['phs_code'].'@@'.$prodcode;
					//var_dump($data);exit;
						$command = $db->createCommand()->insert('nb_cupon_branduser',$data);
					//exit;
						Cupon::sentCupon($dpid, $userarray, $cupons['cupon_money'], $cupons['cupon_abstract'], $colseday, 0, $openId['openid']);
					}

				}
			}
			Yii::app()->end(json_encode(array('status'=>true,'msg'=>'')));

		
	}



}