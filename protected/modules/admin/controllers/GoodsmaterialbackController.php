<?php
/**
* 原料采购退货单(运输损耗表)
*/
class GoodsmaterialbackController extends BackendController
{
	
	public function actionIndex()
	{
		$company_info = Company::model()->find('dpid='.$this->companyId .' and delete_flag=0');
		$db = Yii::app()->db;
		if ($company_info->type == 0) { //公司
			$sql = 'select co.company_name as depot_name,gst.goods_order_accountno,gst.lid,gst.invoice_accountno,gst.price,gst.num,gst.status,c.company_name as store_name,go.username,g.goods_name,g.main_picture from nb_goods_stock_taking gst '
					.'left join nb_goods_order go on(gst.goods_order_accountno=go.account_no) '
					.'left join nb_goods g on(gst.goods_id=g.lid and gst.goods_code=g.goods_code) '
					.'left join nb_company co on(co.dpid=gst.dpid) '
					.'left join nb_company c on(c.dpid=go.dpid) '
					.'where gst.dpid in(select dpid from nb_company where comp_dpid ='.$this->companyId.' and type = 2) order by gst.create_at desc';
			$models = $db->createCommand($sql)->queryAll();
		}else if($company_info->type == 2){ //仓库
			$sql = 'select co.company_name as depot_name,gst.lid,gst.goods_order_accountno,gst.invoice_accountno,gst.price,gst.num,gst.status,c.company_name as store_name,go.username,g.goods_name,g.main_picture from nb_goods_stock_taking gst '
					.'left join nb_goods_order go on(gst.goods_order_accountno=go.account_no) '
					.'left join nb_goods g on(gst.goods_id=g.lid and gst.goods_code=g.goods_code) '
					.'left join nb_company co on(co.dpid=gst.dpid) '
					.'left join nb_company c on(c.dpid=go.dpid) '
					.'where gst.dpid='.$this->companyId.' order by gst.create_at desc';
			$models = $db->createCommand($sql)->queryAll();
		}
			// p($models);
		$this->render('index',array(
			'models'=>$models,
		));
	}


		public function actionChangestatus()
	{
		$lid = Yii::app()->request->getParam('lid');
		$status = Yii::app()->request->getParam('status');
		$db = Yii::app()->db;
		if(Yii::app()->request->isAjaxRequest) {
			if ($status == 1) {
				$status = 0;
			}else{
				$status = 1;
			}
			$sql = 'update nb_goods_stock_taking set status ='.$status.' where lid ='.$lid;
			$command=$db->createCommand($sql)->execute();

			if($command){
				echo json_encode(1);exit;
			}else{
				echo json_encode(0);exit;
			}
		}
	}
}

?>