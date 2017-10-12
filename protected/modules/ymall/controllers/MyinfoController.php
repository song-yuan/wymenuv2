<?php

class MyinfoController extends BaseYmallController
{

	//我的信息  订单的四大类型
	public function actionIndex()
	{

		//查询提交者信息
		$user_id = 88888888;
		$user_name = 'admin';
		$db = Yii::app()->db;
		$sql = 'select god.*,go.*,g.description,g.goods_unit,g.store_number,g.main_picture,c.company_name from nb_goods_order_detail god '
		.' left join nb_goods_order go on(go.account_no=god.account_no) '
		.' left join nb_company c on(c.dpid=god.stock_dpid) '
		.' left join nb_goods g on (g.lid=god.goods_id and g.goods_code=god.goods_code )'
		.' where god.dpid='.$this->companyId
		.' and go.order_type=1'
		.' and go.user_id='.$user_id
		.' and god.delete_flag=0'
		.' order by god.stock_dpid';
		$products_nopay = $db->createCommand($sql)->queryAll();
		$materials_nopay =array();
		foreach ($products_nopay as $key => $product) {
			if(!isset($materials_nopay[$product['account_no']])){
				$materials_nopay[$product['account_no']] = array();
			}
			array_push($materials_nopay[$product['account_no']], $product);
		}
// p($materials_nopay);
		$this->render('myinfo',array(
			'materials_nopay'=>$materials_nopay,
		));
	}
	public function actionNormalsetting()
	{
		$this->render('normalsetting',array(
		));
	}

	public function actionDelete_nopay()
	{
		$account_no = Yii::app()->request->getParam('account_no');
		// p($account_no);
		$info = GoodsOrder::model()->deleteAll('dpid=:dpid and account_no=:account_no',array(':dpid'=>$this->companyId,':account_no'=>$account_no));
		if($info){
			$infod = GoodsOrderDetail::model()->deleteAll('dpid=:dpid and account_no=:account_no',array(':dpid'=>$this->companyId,':account_no'=>$account_no));
			if ($infod) {
				echo json_encode(1);exit;
			}
		}else{
			echo json_encode(2);exit;
		}
	}
}