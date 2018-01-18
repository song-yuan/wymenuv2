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
		//var_dump($dpid);exit;
		$sql = 'select * from nb_notify_wxwap where dpid ='.$dpid;
		$orders = Yii::app()->db->createCommand($sql)
		->queryAll();
		//var_dump($orders);exit;
		if(!empty($orders)){
			foreach ($orders as $order){
				$total_amount = $order['total_amount'];
				$client_sn = $order['client_sn'];
				$account_nos = explode('-',$client_sn);
				$orderid = $account_nos[0];
				$orderdpid = $account_nos[1];
				$sql = 'select * from nb_order where dpid ='.$dpid.' and lid ='.$orderid;
				$orderdatas = Yii::app()->db->createCommand($sql)
				->queryRow();
				//var_dump($orderdatas);
				if(!empty($orderdatas)){
					
					if($orderdatas['order_type'] == '1' || $orderdatas['order_type'] == '6' || $orderdatas['order_type'] == '3'){
						$pay_type = '12';
					}elseif($orderdatas['order_type'] == '2'){
						$pay_type = '13';
					}else{
						$pay_type = '1';
					}
					$sql = 'select * from nb_order_pay where dpid ='.$dpid.' and order_id ='.$orderid.' and account_no ="'.$orderdatas['account_no'].'" and paytype ='.$pay_type;
					$orderpays = Yii::app()->db->createCommand($sql)
					->queryRow();
					if(!empty($orderpays)){
						
					}else{
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
			Yii::app()->end(json_encode(array("status"=>"success",'msg'=>'成功！')));
		}else{
			Yii::app()->end(json_encode(array("status"=>"error",'msg'=>'失败')));
		}
	}
	

	public function actionSelfrjs(){
		
		$btime = '2017-10-11 00:00:00';
		$etime = '2017-10-12 00:00:00';
		$db = Yii::app()->db;
		$sql = 'select * from nb_company where type =1 and delete_flag =0';
		$coms = $db->createCommand($sql)->queryAll();
		if($coms){
			foreach ($coms as $c){
				$dpid = $c['dpid'];
				//var_dump($dpid);
				$sqlor = 'select DATE_FORMAT(t.create_at,"%Y-%m-%d") as times, t.* from nb_order t where t.dpid ='.$dpid.' and t.order_status in(3,4,8) and t.create_at <= "'.$etime.'" and t.create_at >= "'.$btime.'" group by DATE_FORMAT(t.create_at,"%Y-%m-%d")';
				$orders = $db->createCommand($sqlor)->queryAll();
	
				$sqlpos = 'select t.* from nb_pad_setting t where t.dpid ='.$dpid.' and t.delete_flag =0';
				$pos = $db->createCommand($sqlpos)->queryRow();
	
				if($orders && $pos){
					foreach ($orders as $order){
						$times = str_replace('-','',$order['times']);
						$rjcode = substr("0000".$dpid,-4).$times.'01';
							
						$rj = $db->createCommand('select * from nb_rijie_code where dpid ='.$dpid.' and begin_time ="2017-10-11 00:00:00"')->queryAll();
						if(!empty($rj)){
	
						}else{
							$lid = new Sequence("rijie_code");
							$id = $lid->nextval();
							$data = array(
									'lid'=>$id,
									'dpid'=>$dpid,
									'create_at'=>$order['times'].' 00:00:00',
									'update_at'=>date('Y-m-d H:i:s',time()),
									'pos_code'=>$pos['pad_code'],
									'begin_time'=>$order['times'].' 00:00:00',
									'end_time'=>$order['times'].' 23:59:59',
									'rijie_num'=>1,
									'rijie_code'=>$rjcode,
									'is_rijie'=>'0',
									'delete_flag'=>'0',
									'is_sync'=>'11111',
							);
							$command = $db->createCommand()->insert('nb_rijie_code',$data);
						}
					}
				}
			}
			//exit;
			Yii::app()->end(json_encode(array("status"=>"true",'msg'=>'成功')));
		}
	}
	
	public function actionCeshijk(){
		$soap=new SoapClient('http://58.213.118.119:8127/Ajax/TradeChange.asmx?wsdl');
		$soap->soap_defencoding = 'utf-8';
		$soap->decode_utf8 = false;
		$soap->xml_encoding = 'utf-8';
		
		$valiKEY = '6EA576539AEB4E878946911DA4E0C6BD';
		$stationname = '合肥南';
		$stationid = '5933e969-2773-42dc-a17e-725e1ad386fd';
		$branch = '合肥分公司';
		$shopname = "巴百克牛肉堡";
		$shopid = "e61b636e-245c-4bd9-b235-85e7bef376c5";
		
		$ParamData = array(
				'STATIONNAME'=>$stationname,
		    	'STATIONID'=>$stationid,
		    	'SHOPNAME'=>$shopname,
		    	'SHOPNO'=>$shopid,
		    	'BILLTYPE'=>"",
		    	'BILLNO'=>'12345678765432',
		    	'BILLALLPRICES'=>'1.22',
		    	'BILLTIME'=>'2017-11-28 10:35:10',
		    	'PAYMENT'=>'xj',
		    	'TRANSTYPE'=>'xs',
		    	'SOURCETYPE'=>'pos',
		    	'SOURCENO'=>"",
		    	'BRANCH'=>$branch
		);
		$data = array(
				'tradeChange'=>$ParamData,
				'valiKEY'=>$valiKEY
		);
		$param["tradeChange"]='[{"STATIONNAME":"上海站","STATIONID":"6464ef51-b72c-4aeb-ac28-320fc904703e","SHOPNAME":"老城隍庙","SHOPNO":"a6db5488-7273-40f0-be57-04ff95b6641b","BILLTYPE":"","BILLNO":"10120161017224622","BARCODE":"","BILLALLPRICES":6.0,"BILLTIME":"2017-10-18 09:12:00","TRANSTYPE":"销售","PAYMENT":"xj","SOURCETYPE":"POS机","SOURCENO":"101","BRANCH":"hf"}]';
		$param["valiKey"]=$valiKEY;
		
		
		print_r($soap->__getFunctions());
		var_dump($soap->__getTypes());
		//exit;
		$result = $soap->__Call('Save',array($param));
		//$arr = $soap->Save();
		//$arr = $soap->ServiceMethod($ParamData);
		var_dump($result) ;
		exit;
	}
	public function actionMtpay(){
		
		$pay_price = Yii::app()->request->getParam('pay_price');
		$auth_code = Yii::app()->request->getParam('auth_code');
		$dpid = Yii::app()->request->getParam('dpid');
		//$result = SqbPay::pay($dpid,$_POST);
		//$obj = json_decode($result,true);
		$data = array(
				'dpid' => $dpid,
				'channel' => 'wx_barcode_pay',
				'outTradeNo' => $pay_price,
				'authCode' => $auth_code,
				'totalFee' => '1',
				'subject' => '壹点吃',
				'body' => '壹点吃测试单',
				'expireMinutes' => '5',
				'random'=>'2131231',
		);
		$result = MtpPay::pay($data);
		var_dump($result);exit;
	}
	public function actionMtwappay(){
	
		//$result = SqbPay::pay($dpid,$_POST);
		//$obj = json_decode($result,true);
    	$data = array(
    			'outTradeNo'=>'20180118001',
    			'dpid'=>'27',
    			'totalFee'=>'1',
    			'subject'=>'壹点吃',
    			'body'=>'壹点吃支付测试',
    			'channel'=>'wx_scan_pay',
    			'expireMinutes'=>'3',
    			'tradeType'=>'NATIVE',
    			'notifyUrl'=>'http://www.wymenu.com/wymenuv2/Cfceshi/Mtwappayresult',
    			'merchantId'=>'4282256',
    			'appId'=>'31140',
    			'random'=>'1234565432',
    	);
		$result = MtpPay::preOrder($data);
		var_dump($result);exit;
	}
	
}