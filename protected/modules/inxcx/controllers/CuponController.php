<?php
/**
 * 现金券接口
 */
class CuponController extends Controller
{
	public $companyId;
	public function init()
	{
		$companyId = Yii::app()->request->getParam('companyId');
		$this->companyId = $companyId;
	}
	public function actionGetUserCupon()
	{
		var_dump($_POST);
		$userId = Yii::app()->request->getParam('userId');
		$proCodeArr = Yii::app()->request->getParam('proCodeArr');
		$total = Yii::app()->request->getParam('total');
		$type = Yii::app()->request->getParam('type');
		$dpid = $this->companyId;
		var_dump($proCodeArr);
		var_dump($total);
		var_dump($userId);
		var_dump($dpid);
		$cupon = WxCupon::getUserAvaliableCupon($proCodeArr, $total, $userId, $dpid, $type);
		var_dump($cupon);
		echo json_encode(array('cupon'=>$cupon));
	}
}