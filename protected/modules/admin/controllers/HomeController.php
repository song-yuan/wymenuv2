<?php
class HomeController extends BackendController
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
    public function beforeAction($action) {
    	parent::beforeAction($action);
    	if(!$this->companyId && $this->getAction()->getId() != 'upload') {
    		Yii::app()->user->setFlash('error' , '请选择公司˾');
    		$this->redirect(array('company/index'));
    	}
    	return true;
    }

    public function actionIndex(){
    	$dpid = $this->companyId;
    	$beginTime = date('Y-m-d 00:00:00');
    	$endTime = date('Y-m-d 23:59:59');
    	
    	$dpid = Yii::app()->request->getParam('dpid',0);
    	$companys = array();
    	if($this->comptype==0){
    		$companys = Helper::getCompanyChildren($this->companyId);
    		if($companys&&!$dpid){
    			$dpid = $companys[0]['dpid'];
    		}
    	}
    	
    	//  营业数据报表
    	$sql = 'select count(*) as all_order_num ,sum(should_total) as all_order_total from nb_order where dpid = '.$dpid.' and order_status in(3,4,8) and create_at >= "'.$beginTime.'" and create_at <= "'.$endTime.'"';
    	$order = Yii::app()->db->createCommand($sql)->queryRow();
    	
    	$sql = 'select sum(pay_amount) as pay_amount,paytype,payment_method_id,"" as payment_method_name from nb_order_pay where dpid = '.$dpid.' and paytype != 3 and create_at >= "'.$beginTime.'" and create_at <= "'.$endTime.'" group by paytype,payment_method_id';
    	$sql .= ' union select sum(t.pay_amount) as pay_amount,t.paytype,t.payment_method_id,t1.name as payment_method_name from nb_order_pay t left join nb_payment_method t1 on t.payment_method_id=t1.lid and t.dpid=t1.dpid where t.dpid = '.$dpid.' and t.paytype = 3 and t.create_at >= "'.$beginTime.'" and t.create_at <= "'.$endTime.'" group by t.paytype,t.payment_method_id';
    	$orderPay = Yii::app()->db->createCommand($sql)->queryAll();
    	
    	
    	$this->render('index',array(
    			'companys'=>$companys,
    			'order'=>$order,
    			'orderpay'=>$orderPay,
    			'dpid'=>$dpid
    	));
    }
}
