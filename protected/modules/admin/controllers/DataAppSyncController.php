<?php
/**
 * 数据同步几大规则
 * 1：app的初始化，重要的事情提示三遍，初始化时，先检查有无数据还没有同步到云端，
 * 如果有就先同步，然后删除本地的所有的表结构及数据，最后从云端下载表结构及数据。
 * 2：下载数据同步，只同步基础数据，表结构不变化，先删除所有基础数据，然后下载
 * 3：日常数据同步，默认是自动打开的，也可以关闭。只要打开，就检查本地的所有的
 * 
 */
class DataAppSyncController extends Controller
{	
    
        /**
         * 测试用的
         * @return type
         */
	public function actionIndex(){
            //echo strtotime("2015-11-11 12:00:00");
//            $store=new Memcache;
//            $store->connect(Yii::app()->params['memcache']['server'],Yii::app()->params['memcache']['port']); 
//            $store->set("kitchenjobs_","234234",0,300); 
//            echo $store->get("kitchenjobs_");
            
//            $tempnow = new DateTime(date('Y-m-d H:i:s',time()));
//            echo $tempnow->format('Y-m-d H:i:s');
//            $tempnow->modify("-1 day");
//            echo $tempnow->format('Y-m-d H:i:s');
           
            echo substr(date('Ymd',time()),-6).substr("0000000000"."111", -6);exit;
            echo implode("",array("11"=>'aa','22'=>'bb'));exit;
            try
            {
                $dbcloud=Yii::app()->dbcloud;
                $dblocal=Yii::app()->dblocal;            
            } catch (Exception $ex) {
                echo $ex->getMessage();
                return;
            }
            
//            $se=new Sequence("sqlcmd_sync");
//            var_dump($se->nextval());exit;
                        
//            $filesnames1 = scandir("uploads/company_0000000001");
//            $fnj=  json_encode($filesnames1);
//            var_dump($fnj);
//            $fna=  json_decode($fnj);
//            var_dump($fna);
//            //exit;
//            $filesnames2 = scandir("uploads/company_0000000011");
//            $filesnames3=array_diff($fna, $filesnames2);
//            var_dump($filesnames3);exit;
//            
//            //图片同步，主要是uploads/company_nnnnn的文件夹下，其他的不用，产品详细的文本框将来删除           
//            $imgfile="uploads/company_0000000007/DBAED346-6C35-46CF-8632-DDBE81C2352E.jpg";
//            
//            $img=Helper::GrabImage(Yii::app()->params->masterdomain,$imgfile); 
//            if($img){ 
//                echo '<img src="'.$img.'">'; 
//            }else{ 
//                echo "false"; 
//            } 
	}
        /**
         * 
         * 测试
         * 
         */
         public function actionTest(){
         	$id = Yii::app()->request->getParam('id');
         	if($id==1){
         		echo 11;
         	}else{
         		echo 22;
         	}
         	exit;
         }
        /**
         * 获取服务器端图片列表
         */
        public function actionServerImglist(){
            $company_id = Yii::app()->request->getParam('companyId',0);
            $filesnames1 = scandir("uploads/company_".$company_id);
            $fnj=  json_encode($filesnames1);
            Yii::app()->end($fnj);
        }
        
        /**
         * 初始化
         */
        public function actionLocalInit(){
            
        }
        
        /**
         * 同步基础数据
         */
        public function actionSyncBaseData(){
            
        }
        
        /**
         * 获取表结构
         */
        public function actionGetTableStructure(){
            
        }
        
        public function actionOperation()
        {
            
        }
}