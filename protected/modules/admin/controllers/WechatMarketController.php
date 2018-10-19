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
        $more = Yii::app()->request->getPost('more',"0");
        $o = Yii::app()->request->getPost('o',"0");
        $s = Yii::app()->request->getPost('s',"0");
        //性别
        $findsex = Yii::app()->request->getPost('findsex',"%");
        //年龄范围
        $agefrom = Yii::app()->request->getPost('agefrom',"0");
        $ageto = Yii::app()->request->getPost('ageto',"150");
        //生日范围
        $birthfrom = Yii::app()->request->getPost('birthfrom',"01-01");
        $birthto = Yii::app()->request->getPost('birthto',"12-31");
        //会员等级
        $finduserlevel=Yii::app()->request->getPost('finduserlevel',"0");
        //未消费时长
        $noordertime=Yii::app()->request->getPost('noordertime',"%");

        //省 市 地区
        $findprovince=Yii::app()->request->getPost('province',"请选择..");
        $findcity=Yii::app()->request->getPost('city',"请选择..");
        
        //来源门店名称
        $source = Yii::app()->request->getPost('source',"");
        //关注时间
        $foucsfrom = Yii::app()->request->getPost('foucsfrom',"");
        $foucsto = Yii::app()->request->getPost('foucsto',"");

        //下订单时间范围
        $datefrom = Yii::app()->request->getPost('datefrom',"2014-01-01");
        $dateto = Yii::app()->request->getPost('dateto',date('Y-m-d',time()));

        //总消费额范围
        $consumetotalfrom = Yii::app()->request->getPost('consumetotalfrom',"0");
        $consumetotalto = Yii::app()->request->getPost('consumetotalto',"9999999999");

        //消费次数
        $timesfrom = Yii::app()->request->getPost('timesfrom',"0");
        $timesto = Yii::app()->request->getPost('timesto',"999999");
        
        //会员卡号  手机号
        $cardmobile = Yii::app()->request->getPost('cardmobile',"%");

        $sql = 'select t.lid,t.dpid,t.card_id,t.create_at,t.user_name,t.nickname,t.sex,t.user_birthday,tl.level_name,t.weixin_group,unsubscribe,t.country,t.province,t.city,t.mobile_num,(t.remain_money+t.remain_back_money) as all_money,com.dpid as companyid,com.company_name';
        $sql .=' from nb_brand_user t LEFT JOIN  nb_company com on com.dpid = t.weixin_group ';
        $sql .=' LEFT JOIN nb_brand_user_level tl on tl.dpid = t.dpid and tl.lid = t.user_level_lid ';
		if($this->comptype==0){
        	$companyDpid = $this->companyId;
        	$companyId = WxCompany::getAllDpids($companyDpid);
        	$sql .=' where t.dpid='.$this->companyId.' and tl.level_type = 1 and tl.delete_flag = 0';
        }else{
        	$companyDpid = $this->company_dpid;
        	$companyId = $this->companyId;
        	$sql .=' where t.dpid='.$this->company_dpid.' and weixin_group='.$this->companyId.' and tl.level_type = 1 and tl.delete_flag = 0';
        }
    	// 卡号或手机号
    	if($cardmobile!="%"&&$cardmobile!="")
        {
            $sql.= ' and (t.card_id like "%'.$cardmobile.'%" or t.mobile_num like "%'.$cardmobile.'%")';
        }
        // 性别
        if($findsex!="%")
        {
        	$sql.= ' and t.sex = "'.$findsex.'"';
        }
        // 年龄
        if($agefrom!=0){
        	 $sql .= ' and t.user_birthday<="'.date('Y-01-01',strtotime('-'.$agefrom.' year')).'"';
        }
        if($ageto!=150){
        	$sql .= ' and t.user_birthday>="'.date('Y-01-01',strtotime('-'.$agefrom.' year')).'"';
        }
        //生日
        if($birthfrom!='01-01'){
        	$sql .=' and DATE_FORMAT(t.user_birthday,"%m-%d")>="'.$birthfrom.'"';
        }
        if($birthto!='12-31'){
        	$sql .=' and DATE_FORMAT(t.user_birthday,"%m-%d")<="'.$birthto.'"';
        }
        //等级
        if($finduserlevel!="0")
        {
        	$sql.= ' and t.user_level_lid = '.$finduserlevel;
        }
        
        // 省市
        if($findprovince!="请选择..")
        {
        	$findprovincenew = str_replace(array('省','市'),'', $findprovince);
        	$sql .= ' and t.province like "%'.$findprovincenew.'%"';
        }
        if($findcity!="请选择..")
        {
        	$findcitynew = str_replace(array('市','区','县'),'', $findcity);
        	$sql .= ' and t.city like "'.$findcitynew.'"';
        }
        // 来源店铺
        if($source){
        	$sql.= ' and com.company_name like "%'.$source.'%"';
        }
        
        //关注时间数据处理
        if($foucsfrom){
        	$sql .= ' and t.create_at >="'.$foucsfrom.' 00:00:00"';
        }
        if($foucsto){
        	$sql .= ' and t.create_at <="'.$foucsto.' 23:59:59"';
        }
        // 订单信息条件
        if($datefrom!='2014-01-01'||
        	$dateto!=date('Y-m-d',time())||
        	$noordertime!='%'||
        	$consumetotalfrom!='0'||
        	$consumetotalto!='9999999999'||
        	$timesfrom!='0'||
        	$timesto!='999999'){
        	
        	$where = '';
        	$having = '';
        	$osql = 'select user_id from nb_order where dpid in('.$companyId.')';
        	
        	if($noordertime!='%'){
        		$noorderdate = date('Y-m-d',strtotime('-'.$noordertime.' month'));
        		$where .= ' and create_at<="'.$noorderdate.' 00:00:00"';
        	}else{
        		//下单时间
        		if($datefrom!='2014-01-01'){
        			$where .= ' and create_at>="'.$datefrom.' 00:00:00"';
        		}
        		if($dateto!=date('Y-m-d',time())){
        			$where .= ' and create_at<="'.$dateto.' 23:59:59"';
        		}	
        	}
        	$osql .=$where.' group by user_id';
        	if($consumetotalfrom!='0'){
        		$having .=' and sum(should_total)>='.$consumetotalfrom;
        	}
        	if($consumetotalto!='9999999999'){
        		$having .=' and sum(should_total)<='.$consumetotalto;
        	}
        	if($timesfrom!='0'){
        		$having .=' and count(lid)>='.$timesfrom;
        	}
        	if($timesto!='999999'){
        		$having .=' and count(lid)<='.$timesto;
        	}
        	if($having!=''){
        		$osql .=' having by '.$having;
        	}
        	$userIds = $db->createCommand($osql)->queryColumn();
        	$userStr = join($userIds, ',');
	        if($userStr!=''){
	        	$sql .= ' and t.user_id in('.$userStr.')';
	        }
        }
        $usersql = 'select lid from ('.$sql.')m where m.unsubscribe=0';
        $sql = 'select m.* from ('.$sql.')m';
        
        $usersArr = $db->createCommand($usersql)->queryColumn();
        $alluserid = join(',', $usersArr);
        
        $count = $db->createCommand(str_replace('m.*','count(*)',$sql))->queryScalar();
        $pages = new CPagination($count);
        $pages->pageSize = 100;
        $pdata =$db->createCommand($sql." LIMIT :offset,:limit");
        $pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
        $pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
        $models = $pdata->queryAll();

        //检索条件会员等级
        $sql = 'select lid,level_name from nb_brand_user_level where dpid='.$companyDpid.' and level_type=1 and delete_flag=0';
        $userlevels = $db->createCommand($sql)->queryAll();

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
			'noordertime'=>$noordertime,
			'province'=>$findprovince,
			'city'=>$findcity,
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

		public function actionGetCupon() {
			$this->layout = '/layouts/main_picture';
			$users = Yii::app()->request->getParam('users',0);
			$all = Yii::app()->request->getParam('all',0);

			$sql = 'select * from nb_cupon where dpid='.$this->companyId.' and is_available = 0 and delete_flag=0 order by lid desc';
			$models = Yii::app()->db->createCommand($sql)->queryAll();
			
			$this->render('addprod' , array(
				'all' => $all,
				'models' => $models,
				'users' => $users
			));
		}

		public function actionStorsentwxcard(){
			$plids = Yii::app()->request->getParam('plids');
			$users = Yii::app()->request->getParam('users');
			$dpid = $this->companyId;
			$materialnums = array();
			$materialnums = explode(';',$plids);

			$userarrays = array();
			$userarrays = explode(',',$users);
			$msg = '';
			
			$sqlArrs = array();
			$db = Yii::app()->db;
			foreach ($materialnums as $materialnum){
				$materials = array();
				$materials = explode(',',$materialnum);
				$plid = $materials[0];
				$pcode = $materials[1];
				
				$sql = 'select * from nb_cupon where lid='.$plid.' and dpid='.$this->companyId.' and delete_flag=0';
				$cupons = $db->createCommand($sql)->queryRow();
				if(!empty($cupons)&&!empty($plid)){
					$nowDate = date('Y-m-d H:i:s',time());
					$timetype = $cupons['time_type'];
					if($timetype=='1'){
						$validay = $cupons['begin_time'];
						$colseday = $cupons['end_time'];
					}else{
						$validay = date('Y-m-d H:i:s',strtotime('+'.$cupons['day_begin'].' day'));
						$colseday = date('Y-m-d H:i:s',strtotime($validay.'+'.$cupons['day'].' day'));
					}
					
					$sql = 'select brand_user_lid from nb_cupon_branduser where cupon_id='.$plid.' and brand_user_lid in ('.$users.')';
					$cbuserIds = $db->createCommand($sql)->queryColumn();
					
					$leftUserIds = array_diff($userarrays, $cbuserIds);
					if(!empty($leftUserIds)){
						$sql = 'insert into nb_cupon_branduser (lid,dpid,create_at,update_at,cupon_id,cupon_source,to_group,brand_user_lid,valid_day,close_day,is_used) VALUES';
						foreach ($leftUserIds as $userId){
							$se = new Sequence("cupon_branduser");
							$id = $se->nextval();
							$sql .= '('.$id.','.$dpid.',"'.$nowDate.'","'.$nowDate.'",'.$plid.',"2","3",'.$userId.',"'.$validay.'","'.$colseday.'","1"),';
						}
						$sql = rtrim($sql,',');
						$db->createCommand($sql)->execute();
						
						// 查询 要发送消息的会员的openId
						$leftuidStr = join(',', $leftUserIds);
						$sql = 'select openid from nb_brand_user where lid in('.$leftuidStr.')';
						$openids = $db->createCommand($sql)->queryColumn();
						$data = array();
						foreach ($openids as $openid){
							$dataTemp = array(
									'touser'=>$openId,
									'url'=>Yii::app()->createAbsoluteUrl('/user/ticket',array('companyId'=>$dpid)),
									'first'=>'现金券已发放到账户',
									'keyword1'=>$cupons['cupon_money'].'元现金券一张',
									'keyword2'=>'商家赠送',
									'keyword3'=>$colseday,
									'keyword4'=>'点餐下单时,选择现金券即可使用',
									'remark'=>'如果有任何疑问,请到店里咨询'
							);
							array_push($data, $dataTemp);
						}
						new WxMessageTpl($dpid, 1, $data);
					}
				}
			}
			$msg = json_encode(array('status'=>true,'msg'=>''));
			Yii::app()->end($msg);
	}
}