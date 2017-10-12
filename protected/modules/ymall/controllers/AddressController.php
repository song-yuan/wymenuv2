<?php

class AddressController extends BaseYmallController
{

	/**
	 * @Author    zhang
	 * @DateTime  2017-09-27T10:12:31+0800
	 * @copyright [copyright]
	 * @license   [license]
	 * @version   [version]
	 * @return    [type]         订单地址选择          [description]
	 */
	public function actionAddresslist()
	{

		$account_no = Yii::app()->request->getParam('account_no');
		$user_id = '88888888';
		$sql = 'select * from nb_goods_address  where  delete_flag = 0 and user_id='.$user_id.' and dpid='.$this->companyId.' order by default_address desc';
		$models=Yii::app()->db->createCommand($sql)->queryAll();
		// p($models);
		$this->render('addresslist',array(
			'account_no'=>$account_no,
			'models'=>$models,
			'companyId'=>$this->companyId,
		));
	}


	public function actionAddressmanage()
	{


		$success = Yii::app()->request->getParam('success',0);//修改添加的状态,0 正常 ,1 添加成功,2 编辑成功

		$user_id = '88888888';
		$sql = 'select * from nb_goods_address  where  delete_flag = 0 and user_id='.$user_id.' and dpid='.$this->companyId.' order by default_address desc';
		$models=Yii::app()->db->createCommand($sql)->queryAll();

		$this->render('addressmanage',array(
			'models'=>$models,
			'companyId'=>$this->companyId,
			'success'=>$success,
		));
	}


	public function actionAddaddress()
	{
		$name = Yii::app()->request->getParam('name');
		$mobile = Yii::app()->request->getParam('mobile');
		$pcc = Yii::app()->request->getParam('pcc');
		$street = Yii::app()->request->getParam('street');
		$default_address = Yii::app()->request->getParam('default_address',0);
		$error = Yii::app()->request->getParam('error',0);


		$user_id = '88888888';
		if (Yii::app()->request->isPostRequest) {
			// p($_POST);
			if ($default_address) {
				$sql = 'update nb_goods_address set default_address = "0" where default_address = "1" and delete_flag = 0 and user_id=:user_id and dpid=:dpid';
				$command=Yii::app()->db->createCommand($sql)->execute(array(':user_id'=>$user_id,':dpid'=>$this->companyId));
			}else{
				$info = GoodsAddress::model()->find('dpid=:dpid and user_id=:user_id and default_address = 1',array(':dpid'=>$this->companyId,':user_id'=>$user_id,));
				if (empty($info)) {
					$default_address = 1;
				}
			}
				$goods_address = new GoodsAddress();
				$se=new Sequence("goods_address");
				$lid = $se->nextval();
				$is_sync = DataSync::getInitSync();
				$goods_address->lid = $lid;
				$goods_address->dpid = $this->companyId;
				$goods_address->create_at = date('Y-m-d H:i:s',time());
				$goods_address->update_at = date('Y-m-d H:i:s',time());
				$goods_address->name = $name;
				$goods_address->user_id = $user_id;
				$goods_address->pcc = $pcc;
				$goods_address->mobile = $mobile;
				$goods_address->street = $street;
				$goods_address->default_address = $default_address;
				$goods_address->delete_flag=0;
				$goods_address->is_sync = $is_sync;
				if ($goods_address->insert()) {
                    $this->redirect(array('address/addressmanage','companyId'=> $this->companyId,'success'=>1));
				}else{
                    $this->redirect(array('address/addaddress','companyId'=> $this->companyId,'error'=>1));
				}
		}

		$this->render('addaddress',array(
			'error'=>$error,
		));
	}


	public function actionEditaddress()
	{
		$user_id = '88888888';
		$lid = Yii::app()->request->getParam('lid');
		$model = GoodsAddress::model()->find('lid=:lid and dpid=:dpid and user_id=:user_id and delete_flag=0',array(':lid'=>$lid,':dpid'=>$this->companyId,':user_id'=>$user_id));
		// p($model);
		$name = Yii::app()->request->getParam('name',$model['name']);
		$mobile = Yii::app()->request->getParam('mobile',$model['mobile']);
		$pcc = Yii::app()->request->getParam('pcc',$model['pcc']);
		$street = Yii::app()->request->getParam('street',$model['street']);
		$default_address = Yii::app()->request->getParam('default_address',$model['default_address']);
		$error = Yii::app()->request->getParam('error',0);



		if (Yii::app()->request->isPostRequest) {
			// p($default_address);
			if ($default_address) {
				$sql = 'update nb_goods_address set default_address = "0" where default_address = "1" and delete_flag = 0 and user_id=:user_id and dpid=:dpid';
				$command=Yii::app()->db->createCommand($sql)->execute(array(':user_id'=>$user_id,':dpid'=>$this->companyId));
			}
				$model->lid = $lid;
				$model->dpid = $this->companyId;
				$model->update_at = date('Y-m-d H:i:s',time());
				$model->name = $name;
				$model->pcc = $pcc;
				$model->mobile = $mobile;
				$model->street = $street;
				$model->default_address = $default_address;
				if ($model->update()) {
					// p(Yii::app()->request->isAjaxRequest);
					if (Yii::app()->request->isAjaxRequest) {
						echo json_encode(1);exit;//成功返回1
					}else{
                    	$this->redirect(array('address/addressmanage','companyId'=> $this->companyId,'success'=>2));
					}
				}else{
					if (Yii::app()->request->isAjaxRequest) {
						echo json_encode(0);exit;
					}else{
                    	$this->redirect(array('address/editress','companyId'=> $this->companyId,'error'=>2));
                    }
				}
		}

		$this->render('editaddress',array(
			'model'=>$model,
			'error'=>$error,
		));
	}
}