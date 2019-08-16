<?php
class ElemeController extends BackendController
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
	public function actionIndex(){
		$companyId = Yii::app()->request->getParam('companyId');
		$models = ElemeToken::model()->findAll('dpid=:dpid and delete_flag=0',array(':dpid'=>$companyId));
		$sql = "select * from nb_eleme_dpdy where dpid=".$this->companyId." and delete_flag=0";
	    $dp = Yii::app()->db->createCommand($sql)->queryRow();
		$this->render('index',array('companyId'=>$companyId,'models'=>$models,'dp'=>$dp));
	}
	public function actionDpsq(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$url = Yii::app()->createAbsoluteUrl('/eleme/elemetoken');
		$url = urlencode($url);
		$clientId = ElmConfig::key;
		$sqUrl = ElmConfig::squrl;
		$type = Yii::app()->request->getParam('type');
		if(!empty($type)){
			$sql = "update nb_eleme_token set delete_flag=1 where dpid=".$companyId." and delete_flag=0";
			Yii::app()->db->createCommand($sql)->execute();
			$this->render('dpsq',array(
				'companyId'=>$companyId,
				'url'=>$url,
				'clientId'=>$clientId,
				'sqUrl'=>$sqUrl
			));
		}else{
			$sql = 'select * from nb_eleme_token where dpid='.$companyId.' and delete_flag=0';
			$token = Yii::app()->db->createCommand($sql)->queryRow();
		}
		$this->render('dpsq',array(
				'companyId'=>$companyId,
				'url'=>$url,
				'clientId'=>$clientId,
				'sqUrl'=>$sqUrl,
				'token'=>$token
			));
	}
	public function actionDpdy(){
		$companyId = Yii::app()->request->getParam('companyId');
		$resultid = Elm::ElemeId($companyId);
		$obj = json_decode($resultid);
		$auth = $obj->result->authorizedShops;
		$shopid = $auth[0]->id;
		$result = Elm::elemeUpdateId($companyId,$shopid);
		$obj = json_decode($result);
		if(!empty($obj->result)){
			$se=new Sequence("eleme_dpdy");
			$lid = $se->nextval();
			$creat_at = date("Y-m-d H:i:s");
			$update_at = date("Y-m-d H:i:s");
			$shopid = $obj->result->id;
			$inserData = array(
					'lid'=>	$lid,
					'dpid'=>$this->companyId,
					'create_at'=>$creat_at,
					'update_at'=>$update_at,
					'shopId'=>$shopid
			);
			$res = Yii::app()->db->createCommand()->insert('nb_eleme_dpdy',$inserData);
			Yii::app()->user->setFlash('success',yii::t('app','店铺对应成功！'));
			$this->redirect(array('eleme/index' ,'companyId' => $this->companyId));
		}
	}
	public function actionCpdy(){
		$companyId = Yii::app()->request->getParam('companyId');
		$dpid = $companyId;
		
		$sql = "select shopId from nb_eleme_dpdy where dpid=".$this->companyId." and delete_flag=0";
	    $shopid = Yii::app()->db->createCommand($sql)->queryScalar();
		$ecateobj = Elm::getShopCategories($companyId,$shopid);
		$eporobj = Elm::getShopItems($companyId,$shopid);
		$ecategory = json_decode($ecateobj,true);//产品分类
		$ecategorys = $ecategory['result'];
		$eproduct = json_decode($eporobj,true);//产品列表
		$eproducts = $eproduct['result'];
		
		$eproduct = array();
		foreach ($eproducts as $p){
			$categoryId = 'lid-'.$p['categoryId'];
			$specs = $p['specs'];
			if(!isset($eproduct[$categoryId])){
				$eproduct[$categoryId] = array('length'=>0,'data'=>array());
			}
			$eproduct[$categoryId]['length'] = $eproduct[$categoryId]['length'] + count($specs);
			array_push($eproduct[$categoryId]['data'], $p);
		}
		$category = $this->getCategory($dpid);
		$product = $this->getProduct($dpid);
		$this->render('cpdy',array(
			'companyId'=>$companyId,
			'ecategorys'=>$ecategorys,
			'eproducts'=>$eproduct,
			'categorys'=>$category,
			'products'=>$product,
		));
	}
	public function actionAjaxProductDy(){
		$dpid = $this->companyId;
		$extendcode = Yii::app()->request->getPost('extendcode');
		$eid = Yii::app()->request->getPost('e_id');
		$name = Yii::app()->request->getPost('e_name');
		$ecateid = Yii::app()->request->getPost('e_cateid');
		$especid = Yii::app()->request->getPost('e_specid');
		$espec = Yii::app()->request->getPost('e_spec');
		$ematerial = Yii::app()->request->getPost('e_materials');
		$especs = json_decode(urldecode($espec),true);
		$ematerials = json_decode(urldecode($ematerial),true);
		foreach ($especs as $k=>$es){
			$spid = $es['specId'];
			if($especid==$spid){
				$especs[$k]['extendCode'] = $extendcode;
			}
		}
		$res = Elm::updateItem($eid, $dpid, $ecateid, $name, $especs, $ematerials);
		$obj = json_decode($res,true);
		if(!empty($obj['error'])){
			$msg = array('status'=>false,'msg'=>$obj['error']['message']);
		}else {
			$msg = array('status'=>true,'data'=>urlencode(json_encode($especs)));
		}
		echo json_encode($msg);
		exit;
	}
	public function actionCanzhi(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$categoryId = Yii::app()->request->getParam('cid');
		if(isset($categoryId)){
			$sql = "dpid=".$companyId." and category_id=".$categoryId." and delete_flag=0";
			$models = Product::model()->findAll($sql);
			if(empty($models)){
				$models = ProductSet::model()->findAll($sql);
			}
			foreach($models as $c){
			$tmp['name'] = isset($c['product_name'])?$c['product_name']:$c['set_name'];
			$tmp['id'] = isset($c['phs_code'])?$c['phs_code']:$c['pshs_code'];
			$treeDataSource['data'][] = $tmp;
			}
		}
		 Yii::app()->end(json_encode($treeDataSource));
	}
	public function actionDpjb(){
		$dpid = Yii::app()->request->getParam('dpid');
		$sql = "update nb_eleme_token set delete_flag=1 where dpid=".$dpid;
		$token = Yii::app()->db->createCommand($sql)->execute();
		$sqls = "update nb_eleme_dpdy set delete_flag=1 where dpid=".$dpid;
		$dpdy = Yii::app()->db->createCommand($sqls)->execute();
		if($token){
			Yii::app()->end(json_encode(array("status"=>"success",'msg'=>'成功')));
		}else{
			Yii::app()->end(json_encode(array("status"=>"fail")));
			return false;
		}
	}
	public function getCategory($dpid){
		$category = array();
		$sql = 'select lid,pid,category_name from nb_product_category where dpid='.$dpid.' and delete_flag=0';
		$categorys = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($categorys as $c){
			$pid = $c['pid'];
			if(!isset($category[$pid])){
				$category[$pid] = array();
			}
			array_push($category[$pid], $c);
		}
		return $category;
	}
	public function getProduct($dpid){
		$product = array();
		$sql = 'select category_id,phs_code,product_name from nb_product where dpid='.$dpid.' and delete_flag=0';
		$sql .= ' union select category_id,pshs_code as phs_code,set_name as product_name from nb_product_set where dpid='.$dpid.' and delete_flag=0';
		$products = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($products as $p){
			$product[$p['phs_code']] = $p;
		}
		return $product;
	}
}
?>