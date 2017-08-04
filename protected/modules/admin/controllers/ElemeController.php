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
		$this->render('dpsq',array(
				'companyId'=>$companyId,
				'url'=>$url,
				'clientId'=>$clientId,
				'sqUrl'=>$sqUrl,
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
		if($eleme){
			$phs_code = $eleme['phs_code'];
			$itemid = $eleme['elemeId'];
			$item = Elm::getItem($companyId,$itemid);
			$ite = json_decode($item);
			$categoryid = $ite->result->categoryId;
			$name = $ite->result->name;
			$specs = $ite->result->specs;
			foreach ($specs as $spec) {
				$original_price = $spec->price;
			}
			$sql = "elemeID=".$itemid." and delete_flag=0";
			$elememodel = ElemeCpdy::model()->find($sql);
			if(empty($elememodel)){
				$rest = Elm::updateItem($itemid,$dpid,$categoryid,$name,$original_price,$phs_code);
				$obj = json_decode($rest);
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
								'phs_code'=>$phs_code
					);
					$res = Yii::app()->db->createCommand()->insert('nb_eleme_cpdy',$inserData);
				}
			}else{
				$rest = Elm::updateItem($itemid,$dpid,$categoryid,$name,$original_price,$phs_code);
				$obj = json_decode($rest);
				if(!empty($obj->result)){
					$sql = "update nb_eleme_cpdy set phs_code='".$phs_code."' where dpid=".$companyId." and elemeID='".$itemid."' and delete_flag=0";
					$res = Yii::app()->db->createCommand($sql)->execute();
				}
			}
		}
		$sql = 'select elemeID from nb_eleme_cpdy where dpid='.$companyId.' and delete_flag=0';
		$items = Yii::app()->db->createCommand($sql)->queryColumn();
		$category_id = json_decode($category);
		$this->render('cpdy',array(
			'companyId'=>$companyId,
			'category_id'=>$category_id,
			'items'=>$items
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
		$id = Helper::getCompanyId(Yii::app()->request->getParam('id'));
		$item = Elm::getItem($companyId,$id);
		$ite = json_decode($item);
		$name = $ite->result->name;
		$elemeId = $ite->result->id;
		$sql = "dpid=$companyId and delete_flag=0";
		$models = Product::model()->findAll($sql);
		$modelsets = ProductSet::model()->findAll($sql);
		$this->renderPartial('glcp',array(
			'models'=>$models,
			'modelsets'=>$modelsets,
			'action'=>$this->createUrl('eleme/cpdy',array('companyId'=>$this->companyId)),
			'name'=>$name,
			'elemeId'=>$elemeId
			));
	}
}
?>