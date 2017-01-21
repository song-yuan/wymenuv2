<?php
class SynchronousController extends BackendController
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
	public function actionIndex(){
		$dpid = Yii::app()->request->getParam('companyId');
		$type = Yii::app()->request->getParam('type','manul');
                $action = Yii::app()->request->getParam('action','0');
                $dt=Yii::app()->request->getParam('dt','2015-08-15 19:00:00');
                $result="";
                /////////////////////////////////
                if($action==1)
                {
                    $isnow=true;//是否立刻同步
                    //db
                    $dbcloud;
                    $dblocal;
                    try
                    {
                        $dbcloud=Yii::app()->dbcloud;
                        $dblocal=Yii::app()->dblocal;            
                    } catch (Exception $ex) {
                        echo $ex->getMessage();
                        return;
                    }
                    if($type='manul')
                    {
                        DataSync::FlagSync($dpid,DataSync::$synctalbe,$isnow);
                    }else{
                        DataSync::timeSync($dpid, $dt, $isnow);  
                    }

                    //删除同步时间之前的所有的打印记录//删除1天谴的消息记录
                    //$cloudtime=date()
                    $tempnow = new DateTime(date('Y-m-d H:i:s',time()));
                    $tempnow->modify("-1 day");
                    $localtime=$tempnow->format('Y-m-d H:i:s');

                    $sqldeleteprintjobs = "delete from nb_order_printjobs where dpid=".$dpid." and create_at <= '".$localtime."'";  
                    $dblocal->createCommand($sqldeleteprintjobs)->execute(); 
                    $dbcloud->createCommand($sqldeleteprintjobs)->execute(); 
                    $sqldeleteorderfeed = "delete from nb_order_feedback where dpid=".$dpid." and create_at <= '".$localtime."'";  
                    $dblocal->createCommand($sqldeleteorderfeed)->execute(); 
                    $dbcloud->createCommand($sqldeleteorderfeed)->execute(); 

                    //下载图片
                    DataSync::clientDownImg($dpid);
                }
                /////////////////////////////////
		
		$this->render('index',array(				
				'type'=>$type,
                                'dt'=>$dt,
                                'result'=>$result,
		));
	}
	public function actionCreate(){
		if(Yii::app()->user->role != User::POWER_ADMIN) {
			$this->redirect(array('company/index','companyId'=>  $this->companyId));
		}
		$model = new Company();
		$model->create_at = date('Y-m-d H:i:s');
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Company');
                        $model->create_at=date('Y-m-d H:i:s',time());
                        $model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','创建成功'));
				$this->redirect(array('company/index','companyId'=> $this->companyId));
			} else {
				Yii::app()->user->setFlash('error',yii::t('app','创建失败'));
			}
		}
		$printers = $this->getPrinterList();
		return $this->render('create',array(
				'model' => $model,
				'printers'=>$printers,
                                'companyId'=>  $this->companyId
		));
	}
	public function actionUpdate(){
		$dpid = Helper::getCompanyId(Yii::app()->request->getParam('dpid'));
		$model = Company::model()->find('dpid=:companyId' , array(':companyId' => $dpid)) ;
		if(Yii::app()->request->isPostRequest) {
         //Until::isUpdateValid(array(0),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
			$model->attributes = Yii::app()->request->getPost('Company');
                        $model->update_at=date('Y-m-d H:i:s',time());
			
			//var_dump($model->attributes);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功'));
				$this->redirect(array('company/index','companyId'=>$this->companyId));
			} else {
				Yii::app()->user->setFlash('error',yii::t('app','修改失败'));
			}
		}
		$printers = $this->getPrinterList();
		return $this->render('update',array(
				'model'=>$model,
				'printers'=>$printers,
                                'companyId'=>$this->companyId
		));
	}
	public function actionDelete(){
		$ids = Yii::app()->request->getPost('companyIds');
        //Until::isUpdateValid(array(0),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_company set delete_flag=1,update_at="'.date('Y-m-d H:i:s',time()).'" where dpid in ('.implode(',' , $ids).')')
			->execute();
			
		}
		$this->redirect(array('company/index','companyId'=>$this->companyId));
	}
	private function getPrinterList(){
		$printers = Printer::model()->findAll('dpid=:dpid',array(':dpid'=>$this->companyId)) ;
		return CHtml::listData($printers, 'printer_id', 'name');
	}
	
}