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
		$userId = Yii::app()->request->getPost('userId');
		$proCodeArr = Yii::app()->request->getPost('proCodeArr');
		$total = Yii::app()->request->getPost('total');
		$type = Yii::app()->request->getPost('type');
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