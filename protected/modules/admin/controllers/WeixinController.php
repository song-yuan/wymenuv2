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
			$result = $wxSdk ->create_menu($menujson);
		
			if($result['errmsg']=="ok"){
				Yii::app()->admin->setFlash('success','菜单发布成功');
			}else{
				Yii::app()->admin->setFlash('error','菜单发布失败');
			}	
			$this->redirect(array('/admin/weixin/menu','companyId'=>$this->companyId));
		}
		$this->render('menu',array(
			'menuList'=>$menuList,
		));
	}
	
}