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
		$userId = Yii::app()->request->getParam('userId');
		$proCode = Yii::app()->request->getParam('proCode');
		$total = Yii::app()->request->getParam('total');
		$type = Yii::app()->request->getParam('type');
		$dpid = $this->companyId;
		$proCodeArr = explode(',', $proCode);
		var_dump($proCodeArr);
		var_dump($total);
		var_dump($userId);
		var_dump($dpid);
		$cupon = WxCupon::getUserAvaliableCupon($proCodeArr, $total, $userId, $dpid, $type);
		var_dump($cupon);
		echo json_encode(array('cupon'=>$cupon));
	}
}