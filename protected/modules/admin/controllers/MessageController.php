<?php
class MessageController extends BackendController
{

	public function actionIndex() {

		$db=Yii::app()->db;
		$sql='select s.lid,s.downdate,s.dpid,s.all_message_no,s.send_message_no,s.money from nb_message_set s  where  s.delete_flag=0 and dpid='.$this->companyId;

		$models = $db->createCommand($sql)->queryALL();
		// p($models);
		$sql2 = 'select * from nb_message where delete_flag=0 and dpid='.$this->companyId.' and unix_timestamp(downdate_at) >'.time();
		$infos = $db->createCommand($sql2)->queryALL();
		$this->render('index',array(
				'models'=>$models,
				'infos'=>$infos,
		));
	}



	public function actionSetindex() {

		$db=Yii::app()->db;
		$sql='select c.company_name,s.lid,s.downdate,s.dpid,s.all_message_no,s.send_message_no,s.money from nb_message_set s left join nb_company c on(c.dpid=s.dpid and c.delete_flag=0 and c.type=0) where  s.delete_flag=0 order by c.company_name asc';//s.dpid='.$dpid.' and


		$models = $db->createCommand($sql)->queryALL();
		$count = count($models);

		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());
		$models = $pdata->queryAll();
		// p($models);
		$this->render('setindex',array(
				'models'=>$models,
				'pages' => $pages,
		));
	}




	public function actionSetcreate() {
		$model = new MessageSet ;
		$db=Yii::app()->db;
		$sql='select c.company_name,c.dpid from nb_company c where c.delete_flag=0 and c.type=0 ';
		$dpids = $db->createCommand($sql)->queryALL();
		$companyId = Yii::app()->request->getParam('companyId');
		if(Yii::app()->request->isPostRequest) {
			$dpid = Yii::app()->request->getParam('dpid');
			if ($dpid=='') {
				Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
				$this->redirect(array('message/setcreate' , 'companyId' => $companyId, )) ;
			}
			$model->attributes = Yii::app()->request->getPost('MessageSet');
            $se=new Sequence("message_set");
            $model->lid = $se->nextval();
            $model->dpid = $dpid;
            $model->create_at = date('Y-m-d H:i:s',time());
            $model->update_at=date('Y-m-d H:i:s',time());
            $model->delete_flag = '0';
// p($model);
			if($model->save()) {
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('message/setindex' , 'companyId' => $this->companyId));
			}
		}
		$this->render('setcreate' , array(
				'model' => $model ,
				'dpids' => $dpids ,
		));
	}


	public function actionSetupdate(){
		$model = MessageSet::model();
		$lid = Yii::app()->request->getParam('id');
		$model = $model->find('lid=:lid and delete_flag=0',array(':lid'=>$lid));
		$db=Yii::app()->db;
		$sql='select c.company_name,c.dpid from nb_company c where c.delete_flag=0 and c.type=0 ';
		$dpids = $db->createCommand($sql)->queryALL();

		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('MessageSet');
			$dpid = Yii::app()->request->getParam('dpid');
            $se=new Sequence("message_set");
            $model->lid = $se->nextval();
            $model->dpid = $dpid;
            $model->create_at = date('Y-m-d H:i:s',time());
            $model->update_at=date('Y-m-d H:i:s',time());
            $model->delete_flag = '0';
			if($model->save()) {
				Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
				$this->redirect(array('message/setindex' , 'companyId' => $this->companyId));
			}
		}
		$this->render('setupdate' , array(
			'model'=>$model,
			'dpids' => $dpids ,
		));
	}


	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$lids = Yii::app()->request->getParam('ids');
		// p($lids);
		$papage = Yii::app()->request->getParam('papage');
		if(!empty($lids)) {
			foreach ($lids as $key => $lid) {
				$model = MessageSet::model()->find('lid=:lid and delete_flag=0' , array(':lid' => $lid )) ;
				if($model) {
					$model->saveAttributes(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())));
				}
			}
			$this->redirect(array('message/setindex' , 'companyId' => $companyId, 'page'=>$papage)) ;
		} else {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
			$this->redirect(array('message/setindex' , 'companyId' => $companyId, 'page'=>$papage)) ;
		}
	}
	public function actionCreateOrder(){
		$dpid = $_POST['dpid'];
		$msid = $_POST['msid'];
		$username = $_POST['username'];
		$paytype = $_POST['paytype'];
		//$notifyurl = $_POST['notifyurl'];
		$db = Yii::app()->db;
		if((!empty($dpid))&&(!empty($msid))){
			$sql = 'select downdate,all_message_no,send_message_no,money from nb_message_set where lid ='.$msid;
			$mss = $db->createCommand($sql)->queryRow();
			if(!empty($mss)){
				$se = new Sequence("message_order");
				$id = $se->nextval();
				$data = array(
					'lid' => $id,
					'dpid' => $dpid,
					'create_at' => date('Y-m-d H:i:s',time()),
					'update_at' => date('Y-m-d H:i:s',time()),
					'username' => $username,
					'message_set_id' => $msid,
					'accountno' =>$accountno = Common::getMsOrder($dpid, $id, $msid),
					'pay_status' => '0',
				);
				$result = $db->createCommand()->insert('nb_message_order',$data);
			}
			if($result){
				
				$total_amount = (int)($mss['money']*100);
				/*以分为单位,不超过10位纯数字字符串,超过1亿元的收款请使用银行转账*/
				$payway = $paytype;
				$subject = '短信套餐';
				/*本次交易的简要介绍*/
				$operator = $username;
				/*发起本次交易的操作员*/
				 
				$devicemodel = WxCompany::getSqbPayinfo('27');
				if(!empty($devicemodel)){
					$terminal_sn = $devicemodel['terminal_sn'];
					$terminal_key = $devicemodel['terminal_key'];
				}else{
					$result = array('status'=>false, 'result'=>false);
					return $result;
				}
				//$notifyurl = 'http://menu.wymenu.com/wymenuv2/admin/message/createOrderresult';
				$notifyurl = 'http://119.23.61.6';
				$url = SqbConfig::SQB_DOMAIN.'/upay/v2/precreate';
				$data = array(
						'terminal_sn'=>$terminal_sn,
						'client_sn'=>$accountno,
						'total_amount'=>''.$total_amount,
						'payway'=>$payway,
						'subject'=>$subject,
						'operator'=>$operator,
						'notify_url'=> $notifyurl,
				);
				var_dump($data);exit;
				$body = json_encode($data);
				$results = SqbCurl::httpPost($url, $body, $terminal_sn , $terminal_key);
				$obj = json_decode($results,true);
				$result_code = $obj['result_code'];
				Helper::writeLog('预下单返回参数：'.$results);
				
				if($result_code == '200'){
					$result_codes = $obj['biz_response']['result_code'];
					if($result_codes == 'PRECREATE_SUCCESS'){
						$imgurl = $obj['biz_response']['data']['qr_code_image_url'];
						Yii::app()->end(json_encode(array("status"=>"success","msg"=>$imgurl,)));
					}else{
						$error_message = $obj['biz_response']['error_message'];
						Yii::app()->end(json_encode(array("status"=>"error","msg"=>$error_message,)));
					}
				}else{
					$error_message = $obj['error_message'];
					Yii::app()->end(json_encode(array("status"=>"error","msg"=>$error_message,)));
				}
				
			}
			
		} else {
			Yii::app()->end(json_encode(array("status"=>"error","msg"=>'创建短信订单失败！',)));
		}
	}
	
	public function actionCreateOrderresult(){
		Helper::writeLog('预下单回调通知参数：');
	}

}