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
			 Yii::app()->admin->setFlash('error','请先填写微信信息！');
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
				$sql = $sql."(".$lid.",".$dpid.",'".$create_at."','".$update_at."',".$menu['h'].",".$menu['v'].",'".$menu['name']."',".$menu['type'].",'".$menu['value']."'),";	
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
                $order = Yii::app()->request->getParam('o',"0");
                $sort = Yii::app()->request->getParam('s',"0");
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
                $consumetotalfrom = Yii::app()->request->getPost('consumetotalfrom',"0");
                $consumetotalto = Yii::app()->request->getPost('consumetotalto',"9999999999");
                $pointfrom = Yii::app()->request->getPost('pointfrom',"0");
                $pointto = Yii::app()->request->getPost('pointto',"9999999999");
                $remainfrom = Yii::app()->request->getPost('remainfrom',"0");
                $remainto = Yii::app()->request->getPost('remainto',"9999999999");
                
                //用sql语句查询出所有会员及消费总额、历史积分、余额、
                $sql="select t.card_id,t.user_name,t.sex,t.user_birthday,tl.level_name,t.weixin_group,t.country "
                        .",t.province,t.city,t.mobile_num,";
                
		$criteria = new CDbCriteria;
		$criteria->condition =  ' t.dpid='.$companyId;		
		$pages = new CPagination(BrandUser::model()->count($criteria));
		$pages->applyLimit($criteria);                
                $criteria->with =  array("level");                
		$models = BrandUser::model()->findAll($criteria);
                
                //检索条件会员等级
                $criteriauserlevel = new CDbCriteria;
		$criteriauserlevel->condition =  ' t.delete_flag=0 and t.dpid='.$companyId;
		$userlevels = BrandUserLevel::model()->findAll($criteriauserlevel);                
                
                //一下为测试，要调用微信接口，去具体的数据
                $weixingroups=array(array("id"=>"100","name"=>"普通"),array("id"=>"300","name"=>"测试"));
                
                //获取国家、省、市
                $sqlcountry="select distinct country from nb_brand_user where dpid=".$companyId;
                $modelcountrys=$db->createCommand($sqlcountry)->queryAll();				
                
                $sqlprovince="select distinct country,province from nb_brand_user where dpid=".$companyId;
                $modelprovinces=$db->createCommand($sqlprovince)->queryAll();				
                
                $sqlcity="select distinct country,province,city from nb_brand_user where dpid=".$companyId;
                $modelcitys=$db->createCommand($sqlcity)->queryAll();                
		
		//var_dump($modelcountrys);exit;
		$this->render('wxmember',array(
				'models'=>$models,
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
                                'more'=>$more,
                                'order'=>$order,
                                'sort'=>$sort,
				'pages'=>$pages
		));
	}
	
}