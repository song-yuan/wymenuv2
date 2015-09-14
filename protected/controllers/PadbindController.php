<?php

class PadbindController extends Controller
{
	public $layout = '/layouts/padmain';
        
        public function actionLogin()
	{
		$model = new LoginForm();
		// collect user input data
		if(isset($_POST['LoginForm']))
		{
                    $loginform=$_POST['LoginForm'];
                    $model->username=$loginform['username'];
                    $model->password=$loginform['password'];
                    $userexist=  User::model()->find(" username=:username and password_hash=:password and role=1 and delete_flag=0"
                            ,array(":username"=>$model->username,"password"=>Helper::genPassword($model->password)));
                    //var_dump($loginform);exit;
                    if($userexist)
                    {
                        if($loginform["pad_info"]=="00000000000000000000")
                        {
                            $modelpad=new Pad();
                            //var_dump($modelpad->lid);exit;//
                        }else{
                            $modelpad=Pad::model()->find(" dpid=:dpid and lid=:lid",array(":dpid"=>substr($loginform["pad_info"],0,10),":lid"=>substr($loginform["pad_info"],10,10)));
                        }   
                        //var_dump($modelpad);exit;
                        $this->render('dobind',array('model'=>$modelpad));
                        exit;
                    }
		}
                //var_dump($model);exit;
                $this->render('login' , array('model' => $model));				
	}
        
        public function actionDobind()
	{
				
	}
        
	/**
	 * 
	 * setting the companyId and padId
	 */
	public function actionIndex()
	{
		session_start();
		$companyId = Yii::app()->request->getParam('companyid',0);
                $padId = Yii::app()->request->getParam('padid',0);
                //var_dump($companyId,$padId);exit;
		if($companyId){
			$_SESSION['companyId'] = $companyId;
		}
                if($padId){
			$_SESSION['padId'] = $padId;
		}
                //var_dump($companyId,$padId);
                if($companyId!="0000000000" && $padId!="0000000000")
                {
                    $model=  Pad::model()->find(" dpid=:dpid and lid=:lid",array(":dpid"=>$companyId,":lid"=>$padId));
                    //var_dump($model);exit;
                    //收银pad
                    if(empty($model))
                    {
                        $this->redirect(array('login'));
                    }else{
                        if($model->pad_type=="0")//收银
                        {
                            //echo 'a';exit;
                            $this->redirect(array('admin/login'));
                        }else if($model->pad_type=="1" || $model->pad_type=="2")//点餐
                        {
                            //echo 'b';exit;
                            $this->redirect(array('product/index/companyid/'.$companyId.'/padid/'.$padId.'/padtype/'.$model->pad_type));
                        }else{
                            $this->redirect(array('login'));
                        }
                    //日本点餐pad
                    //detell whether this pad site is opensite!
                    //has to the site else opensite and go to the product page!
                    //中国点餐PAD
                    }
                }else{
                    $this->redirect(array('login'));
                }		
	}
        
        public function actionBind(){
		$companyid = Yii::app()->request->getParam('companyid',0);
                $padid = Yii::app()->request->getParam('padid',0);
                $ret=true;
                if(empty($companyid)||empty($padid))
                {
                    $ret=false;
                }
                $pad=Pad::model()->find(' dpid=:companyId and lid=:padid', array(':companyId'=>$companyid,':padid'=>$padid));
                $pad->is_bind='1';
                if($pad->save())
                {
                    $ret=true;
                }
                $retjson=json_encode(array("result"=>$ret));
                header('Content-type: application/Json');
                Yii::app()->end("{$_GET['jsoncallback']}({$retjson});");
	}
        
        public function actionDisbind(){
		$companyid = Yii::app()->request->getParam('companyid',0);
                $padid = Yii::app()->request->getParam('padid',0);
                $ret=true;
                if(empty($companyid)||empty($padid))
                {
                    $ret=false;
                }
                $pad=Pad::model()->find(' dpid=:companyId and lid=:padid', array(':companyId'=>$companyid,':padid'=>$padid));
                if(empty($pad))
                {
                    $ret=false;
                }
                $pad->is_bind='0';
                if($pad->save())
                {
                    $ret=true;
                }
                $retjson=json_encode(array("result"=>$ret));
                 header('Content-type: application/Json');
                Yii::app()->end("{$_GET['jsoncallback']}({$retjson});");
	}
        
        public function actionGetInfo(){
		$companyid = Yii::app()->request->getParam('companyid',0);
                $padid = Yii::app()->request->getParam('padid',0);
                if(empty($companyid)||empty($padid))
                {
                    echo "0";
                    exit;
                }
                $pad=Pad::model()->find(' dpid=:companyId and lid=:padid', array(':companyId'=>$companyid,':padid'=>$padid));
                if($pad)
                {
                    echo $pad->server_address;
                }else{
                    echo "0";
                }
	}
        
        public function actionGetPadPrinter(){
		$companyid = Yii::app()->request->getParam('companyid',0);
                $padid = Yii::app()->request->getParam('padid',0);
                if(empty($companyid)||empty($padid))
                {
                    echo "local";
                    exit;
                }
                $pad=Pad::model()->with("printer")->find(' t.dpid=:companyId and t.lid=:padid', array(':companyId'=>$companyid,':padid'=>$padid));
                if($pad)
                {
                    //echo $pad->printer->address;
                    //echo $pad->printer->printer_type;
                    if($pad->printer->printer_type=="1")
                    {
                        echo "local";
                    }else if(filter_var($pad->printer->address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)){
                        echo $pad->printer->address;
                    }else{
                        echo "local";
                    }
                }else{
                    echo "local";
                }
	}
	
        public function actionGetJob(){
		$companyid1 = Yii::app()->request->getParam('companyid',"0");
                $jobid1 = Yii::app()->request->getParam('jobid',"0");
                $companyid=  str_pad($companyid1, 10,"0",STR_PAD_LEFT);
                $jobid=str_pad($jobid1, 10,"0",STR_PAD_LEFT);
                //var_dump($jobid);exit;
                Gateway::getOnlineStatus();
                $store = Store::instance('wymenu');
                $printData = $store->get($companyid."_".$jobid);
                if(empty($printData))
                {
                    $criteria = new CDbCriteria;
                    $criteria->condition =  't.dpid='.$companyid.' and t.jobid='.$jobid;//.' and t.finish_flag=0';
                    //$criteria->order = ' t.lid desc ';                    
                    //$siteNo = SiteNo::model()->find($criteria);
                    $orderprintjobs=  OrderPrintjobs::model()->find($criteria);
                    if(!empty($orderprintjobs))
                    {
                        $printData=$orderprintjobs->content;
                    }else{
                        $printData="";
                    } 
                    $store->set($companyid."_".$jobid,$printData,0,30);
                }
//                echo $printData;
                  Yii::app()->end($printData);
	}
        
        public function actionGetPadList(){
		$companyid = Yii::app()->request->getParam('companyid',0);
                if(!$companyid){
			Yii::app()->end(json_encode(array('data'=>array(),'delay'=>400)));
		}
		
                $treeDataSource = array('data'=>array(),'delay'=>400);
		$pads = Pad::model()->findAll('dpid=:companyId and delete_flag=0 and is_bind=0' , array(':companyId' => $companyid));
                //var_dump($pads);exit;
		foreach($pads as $c){
			$tmp['name'] = $c['name'];
			$tmp['id'] = $c['lid'];
			$treeDataSource['data'][] = $tmp;
		}
		header('Content-type: application/Json');
                $endjson=json_encode($treeDataSource);
		Yii::app()->end("{$_GET['jsoncallback']}({$endjson});");
	}
        
        public function actionGetOnePad(){
		$companyid = Yii::app()->request->getParam('companyid',0);
                $padid = Yii::app()->request->getParam('padid',0);
                if(!$companyid){
			Yii::app()->end(json_encode(array('data'=>array(),'delay'=>400)));
		}
		
                $treeDataSource = array('data'=>array(),'delay'=>400);
		$pads = Pad::model()->findAll('dpid=:companyId and lid=:lid and delete_flag=0 ' , array(':companyId' => $companyid,':lid'=>$padid));
                //var_dump($pads);exit;
		foreach($pads as $c){
			$tmp['name'] = $c['name'];
			$tmp['id'] = $c['lid'];
			$treeDataSource['data'][] = $tmp;
		}
                header('Content-type: application/Json');
                $endjson=json_encode($treeDataSource);
		Yii::app()->end("{$_GET['jsoncallback']}({$endjson});");
	}
        
        public function actionDomain(){
		$companyid = Yii::app()->request->getParam('companyid',0);
                if(empty($companyid)||$companyid=="0000000000")
                {
                    echo "http://menu.wymenu.com/wymenuv2/";
                    exit;
                }
                $company=Company::model()->find(' dpid=:companyId', array(':companyId'=>$companyid));
                if($company)
                {
                    echo $company->domain;
                }else{
                    echo "http://menu.wymenu.com/wymenuv2/";
                }
	}
}