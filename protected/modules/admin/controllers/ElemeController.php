<?php
header("Content-type: text/html; charset=utf-8"); 
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
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$this->render('index',array('companyId'=>$companyId));
	}
	public function actionDpsq(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$url = Yii::app()->createAbsoluteUrl('/eleme/elemetoken');
		$url = urlencode($url);
		$clientId = ElmConfig::key;
		$sqUrl = ElmConfig::squrl;
		// var_dump($token);exit;
		$type = Yii::app()->request->getParam('type');
		// var_dump($type);exit;
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
		// var_dump($type);exit;
		$this->render('dpsq',array(
				'companyId'=>$companyId,
				'url'=>$url,
				'clientId'=>$clientId,
				'sqUrl'=>$sqUrl,
				'token'=>$token
			));
	}
	public function actionCpdy(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$dpid = $companyId;
		$resultid = Elm::elemeId($companyId);
		$obj = json_decode($resultid);
		$auth = $obj->result->authorizedShops;
		$shopid = $auth[0]->id;
		$category = Elm::getShopCategories($companyId,$shopid);
		$eleme = Yii::app()->request->getParam('eleme');
		$itemm =array();
		$error = '';
		if($eleme){
			$phs_code = $eleme['phs_code'];
			$itemid = $eleme['elemeId'];
			$sql = 'select product_name as name from nb_product where dpid='.$dpid.' and phs_code="'.$phs_code.'" and delete_flag=0 union select set_name as name from nb_product_set where dpid='.$dpid.' and pshs_code="'.$phs_code.'" and delete_flag=0';
			$names =Yii::app()->db->createCommand($sql)->queryRow();
			$productname = $names['name'];
			$item = Elm::getItem($companyId,$itemid);
			$ite = json_decode($item);
			// var_dump($item);exit;
			$categoryid = $ite->result->categoryId;
			$name = $ite->result->name;
			$description = $ite->result->description;
			$specs = $ite->result->specs;
			foreach ($specs as $spec) {
				$specId = $spec->specId;
				$original_price = $spec->price;
			}
			// var_dump($specId);exit;
			$attributes = $ite->result->attributes;
			// var_dump($attributes);exit;
			$attr['name'] ='';
			$attr['details']='';
			foreach ($attributes as $attribute) {
				$attributeName = $attribute->name;
				$details = $attribute->details;
				$attr['name']=$attributeName;
				$attr['details']=$details;
			}
			$attributes1 = array($attr);
			// var_dump($attributes1);exit;
			$sql = "select elemeID from nb_eleme_cpdy where elemeID=".$itemid." and delete_flag=0";
			$elememodel = Yii::app()->db->createCommand($sql)->queryRow();
			if(empty($elememodel['elemeID'])){
				if(empty($description) && empty($attr['name'])){
					$rest = Elm::updateItem($itemid,$dpid,$categoryid,$name,$original_price,$phs_code,$specId);
					// var_dump($rest);exit;
				}elseif(!empty($description) && empty($attr['name'])){
					$rest = Elm::updateItem2($itemid,$dpid,$categoryid,$name,$original_price,$phs_code,$specId,$description);
					// var_dump($rest);exit;
				}else{
					$rest = Elm::updateItem1($itemid,$dpid,$categoryid,$name,$original_price,$phs_code,$specId,$description,$attributes1);
					// var_dump($rest);exit;
				}
				$obj = json_decode($rest);
				// var_dump($rest);exit;
				if(!empty($obj->result)){
					$se = new Sequence("eleme_cpdy");
					$lid = $se->nextval();
					$creat_at = date("Y-m-d H:i:s");
					$update_at = date("Y-m-d H:i:s");
					$inserData = array(
								'lid'=>	$lid,
								'dpid'=> $dpid,
								'create_at'=>$creat_at,
								'update_at'=>$update_at,
								'elemeID'=>$itemid,
								'categoryId'=>$categoryid,
								'phs_code'=>$phs_code,
								'name'=>"$productname"
					);
					$res = Yii::app()->db->createCommand()->insert('nb_eleme_cpdy',$inserData);
				}else{
					$error = Yii::app()->user->setFlash('error' , $obj->error->message);
				}
			}else{
				if(empty($description) && empty($attr['name'])){
					$rest = Elm::updateItem($itemid,$dpid,$categoryid,$name,$original_price,$phs_code,$specId);
					// var_dump($rest);exit;
				}elseif(!empty($description) && empty($attr['name'])){
					$rest = Elm::updateItem2($itemid,$dpid,$categoryid,$name,$original_price,$phs_code,$specId,$description);
					// var_dump($rest);exit;
				}else{
					$rest = Elm::updateItem1($itemid,$dpid,$categoryid,$name,$original_price,$phs_code,$specId,$description,$attributes1);
					// var_dump($rest);exit;
				}
				$obj = json_decode($rest);
				// var_dump($rest);exit();
				if(!empty($obj->result)){
					$sql = "update nb_eleme_cpdy set phs_code=".$phs_code.",name='".$productname."' where dpid=".$companyId." and elemeID=".$itemid." and delete_flag=0";
					$res = Yii::app()->db->createCommand($sql)->execute();
				}else{
					$error = Yii::app()->user->setFlash('error' , $obj->error->message);
				}
			}
			
		}
		$sql = 'select elemeID,name from nb_eleme_cpdy where dpid='.$companyId.' and delete_flag=0';
		$items = Yii::app()->db->createCommand($sql)->queryAll();
		$sql = 'select elemeID from nb_eleme_cpdy where dpid='.$companyId.' and delete_flag=0';
			$itemm = Yii::app()->db->createCommand($sql)->queryColumn();
		// var_dump($items);exit;
		$category_id = json_decode($category);
		$this->render('cpdy',array(
			'companyId'=>$companyId,
			'category_id'=>$category_id,
			'items'=>$items,
			'itemm'=>$itemm,
			'error'=>$error
			));
	}
	public function actionDpdy(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$resultid = Elm::ElemeId($companyId);
		$obj = json_decode($resultid);
		$auth = $obj->result->authorizedShops;
		$shopid = $auth[0]->id;
		$result = Elm::elemeUpdateId($companyId,$shopid);
		$this->render('dpdy',array(
			'result' =>$result,
			'shopid'=>$shopid
			));
	}
	public function actionGlcp(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$id = Yii::app()->request->getParam('id');
		$item = Elm::getItem($companyId,$id);
		$ite = json_decode($item);
		$name = $ite->result->name;
		$elemeId = $ite->result->id;
		$modelCategory = ProductCategory::model()->findAll("dpid=".$companyId." and pid!=0 and delete_flag=0");
		// var_dump($modelCategory);exit;
		$this->renderPartial('glcp',array(
			'action'=>$this->createUrl('eleme/cpdy',array('companyId'=>$this->companyId)),
			'name'=>$name,
			'elemeId'=>$elemeId,
			'modelCategory'=>$modelCategory,
			'companyId'=>$companyId
			));
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
}
?>