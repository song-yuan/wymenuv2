<?php
class CfceshiController extends BackendController
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
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionList(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$type = Yii::app()->request->getParam('type');
		$this->render('list',array('type'=>$type));
	}
	
	public function actionSqbactivate(){
		$compros = CompanyProperty::model()->find('dpid=:companyId and delete_flag=0' , array(':companyId'=>$this->companyId));
		if(!empty($compros)){
			$appId = $compros['appId'];
			$code = $compros['code'];
		}else{
			Yii::app()->end(json_encode(array("status"=>"ERROR",'msg'=>'尚未开通')));
			exit;
		}
		//var_dump($_POST);exit;
		//$result = SqbPay::activate($_POST);
		$device_id = $_POST['device_id'];
		$result = SqbPay::activate(array('device_id'=>$device_id,'appId'=>$appId,'code'=>$code));
		$obj = json_decode($result,true);
		$devicemodel = SqbPossetting::model()->find('device_id=:deviceid and dpid=:dpid',array(':dpid'=>$this->companyId,':deviceid'=>$device_id));
		//var_dump($obj);exit;
		if($obj['result_code']=='400'){
			Yii::app()->end(json_encode(array("status"=>"error",'msg'=>'不能激活！！！')));
		}else{
			if(!empty($devicemodel)){
				Yii::app()->db->createCommand('update nb_sqb_posseting set terminal_key='.$obj['biz_response']['terminal_key'].' where device_id ='.$device_id.' and dpid ='.$this->companyId)
				->execute();
			}else{
				
				//$obj = json_decode($result,true);
				$comset=new SqbPossetting();
				$se=new Sequence("sqb_possetting");
				$comset->lid = $se->nextval();
				$comset->dpid=$this->companyId;
				$comset->create_at = date('Y-m-d H:i:s',time());
				$comset->update_at = date('Y-m-d H:i:s',time());
				$comset->device_id = $device_id;
				$comset->terminal_sn = $obj['biz_response']['terminal_sn'];
				$comset->terminal_key = $obj['biz_response']['terminal_key'];
				$comset->key_validtime = date('Ymd',time());
				$comset->save();
				
				Yii::app()->db->createCommand('update nb_pad_setting set pay_activate=2 where pad_code ='.$device_id.' and dpid ='.$this->companyId)
				->execute();
			}
			Yii::app()->end(json_encode(array("status"=>"success",'msg'=>'成功')));
		}
	}	
	public function actionSqbpay(){
		
		//$result = SqbPay::pay($dpid,$_POST);
		//$obj = json_decode($result,true);
		
		$device_id = $_POST['device_id'];
		$totalAmount = $_POST['totalAmount'];
		
		$pay_code = $obj['result_code'];
		$result_code = $obj['biz_response']['result_code'];
		var_dump($obj);
		$order=new Order();
		$se=new Sequence("order");
		$order->lid = $se->nextval();
		$order->dpid=$dpid;
		$order->username=Yii::app()->user->name;
		$order->create_at = date('Y-m-d H:i:s',time());
		$order->lock_status = '0';
		$order->order_status = '1';
		$order->site_id = '0';
		$order->number = '1';
		$order->is_temp = '1';
		$order->account_no = $obj['biz_response']['data']['sn'];
		//var_dump($order);exit;
		$order->save();
		
		if($pay_code == '200'){
			if($result_code == 'PAY_SUCCESS'){
				Yii::app()->end(json_encode(array('type'=>'1',"status"=>"success",'msg'=>'支付成功！','data'=>$obj['biz_response']['data'])));
			}elseif($result_code == 'PAY_IN_PROGRESS'){
				Yii::app()->end(json_encode(array('type'=>'2',"status"=>"success",'msg'=>'交易进行中...','data'=>$obj['biz_response']['data'])));
			}else{
				Yii::app()->end(json_encode(array('type'=>'3',"status"=>"success",'msg'=>'支付失败，原因：'.$obj['biz_response']['error_message'],'data'=>$obj['biz_response']['data'])));
			}
		}else{
			Yii::app()->end(json_encode(array('type'=>'4',"status"=>"success",'msg'=>'金额有误...')));
		}
		exit;
	}
	public function actionSqbcheck(){
		$dpid = $this->companyId;
		
		$result = SqbPay::checkin($_POST);
		var_dump($result);exit;
	}
	public function actionSqbrefund(){
	
		$result = SqbPay::refund($_POST);
		var_dump($result);exit;
		$obj = json_decode($result,true);
		$result_code = $obj['biz_response']['result_code'];
		if($result_code == 'REFUND_SUCCESS'){
			Yii::app()->end(json_encode(array("status"=>"success",'msg'=>'退款成功！')));
		}else{
			Yii::app()->end(json_encode(array("status"=>"eror",'msg'=>'退款失败,原因：'.$obj['biz_response']['error_message'])));
		}
		exit;
	}
	public function actionSqbquery(){
		$terminal_sn = Yii::app()->request->getParam('terminal_sn');
		$terminal_key = Yii::app()->request->getParam('terminal_key');
		$clientSn = Yii::app()->request->getParam('clientSn');
		$sn = Yii::app()->request->getParam('sn');
		//var_dump($clientSn);
		$resultstatus = SqbPay::query(array(
    						'terminal_sn'=>$terminal_sn,
    						'terminal_key'=>$terminal_key,
    						'sn'=>$sn,
    						'client_sn'=>$clientSn,
    				));
    	$rsts = json_decode($resultstatus,true);
    				
		var_dump($rsts);exit;
		$obj = json_decode($result,true);
		$result_code = $obj['biz_response']['result_code'];
		if($result_code == 'REFUND_SUCCESS'){
			Yii::app()->end(json_encode(array("status"=>"success",'msg'=>'退款成功！')));
		}else{
			Yii::app()->end(json_encode(array("status"=>"eror",'msg'=>'退款失败,原因：'.$obj['biz_response']['error_message'])));
		}
		exit;
	}
	public function actionSqbprecreate(){
		$dpid = $this->companyId;
		$site_id = '0000';
		$is_temp = 1;
		$orderid = '0000026834';
		
		$result = SqbPay::preOrder(array(
				'dpid'=>$dpid,
				'client_sn'=>Order::getAccountNo($dpid,$site_id,$is_temp,$orderid),
				'total_amount'=>'0.01',
				'payway'=>'3',
				'subject'=>'wymenu',
				'operator'=>'admin',
				'notify_url'=>'http://menu.wymenu.com/wymenuv2/sqbpay/wappayresult',
				'return_url'=>'http://menu.wymenu.com/wymenuv2/sqbpay/wappayresult',
		));
		var_dump($result);exit;
	}
	
	public function actionSqbaddordpay(){
		$dpid = Yii::app()->request->getParam('dpid');
		$sql = 'select * from nb_notify_wxwap where dpid ='.$dpid;
		$orders = Yii::app()->db->createCommand($sql)
		->queryAll();
		if(!empty($orders)){
			foreach ($orders as $order){
				$client_sn = $order['client_sn'];
				$account_nos = explode('-',$client_sn);
				$orderid = $account_nos[0];
				$orderdpid = $account_nos[1];
				$sql = 'select * from nb_order where dpid ='.$dpid.' and lid ='.$orderid;
				$orderdatas = Yii::app()->db->createCommand($sql)
				->queryRow();
				if(!empty($orderdatas)){
					$total_amount = $orderdatas['total_amount'];
					if($orderdatas['order_type'] == '1' || $orderdatas['order_type'] == '6' || $orderdatas['order_type'] == '3' ){
						$pay_type = '12';
					}elseif($orderdatas['order_type' == '2']){
						$pay_type = '13';
					}else{
						$pay_type = '1';
					}
					$se = new Sequence ( "order_pay" );
					$orderpayId = $se->nextval ();
					$orderpayData = array (
							'lid' => $orderpayId,
							'dpid' => $orderdpid,
							'create_at' => $orderdatas['create_at'],
							'update_at' => $orderdatas['update_at'],
							'order_id' => $orderid,
							'account_no' => $orderdatas['account_no'],
							'pay_amount' => number_format($total_amount/100,2),
							'paytype' => $pay_type,
							'remark' => '收钱吧公众号支付',
					);
					$result = Yii::app ()->db->createCommand ()->insert ( 'nb_order_pay', $orderpayData );
				}
			}
		}
	}
	
}