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
		$type = 5;
		$dpid = $this->companyId;
		$cupon = WxCupon::getUserAvaliableCupon($proCodeArr, $total, $userId, $dpid, $type);
	}
}