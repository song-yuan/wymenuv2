<?php
/**
 * 美团外卖 开放平台
 * 接口
 */
class MeituanController extends BackendController
{
	public function actionIndex(){
		$timestamp = time();
		$dpid = $this->companyId;
		if($this->comptype==1){
			$dpid = $this->company_dpid;
			$mtconfig = MtOpenUnit::getMtConfig($dpid);
			$appid = $mtconfig['app_id'];
			$appSerect = $mtconfig['app_secret'];
			$url = MtOpenUnit::MTURL.'poi/mget';
			$data = array(
					'app_id'=>$appid,
					'timestamp'=>$timestamp,
					'app_poi_codes'=>$this->companyId,
			);
			$url = MtOpenUnit::getUrlStr($url, $data, $appSerect);
			$result = Curl::https($url);
			$obj = json_decode($result,true);
			$model = $obj['data'];
		}else{
			$model = MeituanSetting::model()->find('dpid='.$dpid.' and delete_flag=0');
			if(Yii::app()->request->isPostRequest){
				if(empty($model)){
					$model = new MeituanSetting();
					$se = new Sequence("meituan_setting");
					$lid = $se->nextval();
					$model->lid = $lid;
					$model->dpid = $dpid;
					$model->create_at = date('Y-m-d H:i:s',time());
				}
				$model->attributes = Yii::app()->request->getPost('MeituanSetting');
				if($model->save()){
					Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
				}
				$this->redirect(array('meituan/index' , 'companyId' => $this->companyId));
			}
			if(empty($model)){
				$model = new MeituanSetting();
			}
		}
		$this->render('index',array(
				'companyId'=>$this->companyId,
				'model'=>$model
			));
	}
	/**
	 * 菜品对应
	 */
	public function actionProductDy(){
		$models = array();
		$timestamp = time();
		$dpid = $this->companyId;
		if($this->comptype==1){
			$dpid = $this->company_dpid;
		}
		$mtconfig = MtOpenUnit::getMtConfig($dpid);
		$appid = $mtconfig['app_id'];
		$appSerect = $mtconfig['app_secret'];
		$url = MtOpenUnit::MTURL.'food/list';
		$data = array(
				'app_id'=>$appid,
				'timestamp'=>$timestamp,
				'app_poi_code'=>$this->companyId,
				'offset'=>'0',
				'limit'=>'200'
		);
		$url = MtOpenUnit::getUrlStr($url, $data, $appSerect);
		$result = Curl::https($url);
		
		$obj = json_decode($result,true);
		$data = $obj['data'];
		if(!empty($data)){
			foreach ($data as $v){
				$cateName = $v['category_name'];
				if(!isset($models[$cateName])){
					$models[$cateName] = array('length'=>0,'data'=>array());
				}
				$skus = json_decode($v['skus'],true);
				$models[$cateName]['length'] = $models[$cateName]['length'] + count($skus);
				array_push($models[$cateName]['data'], $v);
			}
		}
		$category = $this->getCategory($this->companyId);
		$product = $this->getProduct($this->companyId); 
		$this->render('productdy',array(
				'companyId'=>$this->companyId,
				'models'=>$models,
				'categorys'=>$category,
				'products'=>$product,
		));
	}
	/**
	 * 提交产品更新
	 */
	public function actionAjaxProductDy(){
		$timestamp = time();
		$dpid = $this->companyId;
		if($this->comptype==1){
			$dpid = $this->company_dpid;
		}
		$appFoodCode = Yii::app()->request->getPost('appcode');
		$name = Yii::app()->request->getPost('mt_name');
		$cateName = Yii::app()->request->getPost('mt_category_name');
		$skuid = Yii::app()->request->getPost('mt_skuid');
		$spec = Yii::app()->request->getPost('mt_skuspec');
		$mtconfig = MtOpenUnit::getMtConfig($dpid);
		$appid = $mtconfig['app_id'];
		$appSerect = $mtconfig['app_secret'];
		$url = MtOpenUnit::MTURL.'food/updateAppFoodCodeByNameAndSpec';
		
		$data = array(
				'app_id'=>$appid,
				'timestamp'=>$timestamp,
				'app_poi_code'=>$this->companyId,
				'name'=>$name,
				'category_name'=>$cateName,
				'app_food_code'=>$appFoodCode,
				
		);
		if($skuid!=''){
			$data['sku_id'] = $appFoodCode;
			$data['spec'] = $spec;
		}
		$data = MtOpenUnit::getPostStr($url, $data, $appSerect);
		$result = Curl::postHttps($url,$data);
		$obj = json_decode($result,true);
		$res = $obj['data'];
		echo $res;
		exit;
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