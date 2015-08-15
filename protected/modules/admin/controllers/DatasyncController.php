<?php
class DatasyncController extends Controller
{	
        //图片上传只让他们从云端上传
        //本地下载图片
	public function actionIndex(){
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
        
        public function actionServerImglist(){
            $company_id = Yii::app()->request->getParam('companyId',0);
            $filesnames1 = scandir("uploads/company_".$company_id);
            $fnj=  json_encode($filesnames1);
            Yii::app()->end($fnj);
        }
        
        private function clientDownImg(){
            $company_id = Yii::app()->request->getParam('companyId',0);
            ob_start(); 
            readfile(Yii::app()->params->masterdomain.'/admin/datasync/companyId/'.$company_id); 
            $serverimgs = ob_get_contents(); 
            ob_end_clean(); 
            $fna=  json_decode($serverimgs);
            $filesnames2 = scandir("uploads/company_".$company_id);
            $filesnames3=array_diff($fna, $filesnames2);
            foreach($filesnames3 as $akey=>$avalue)
            {
                Helper::GrabImage(Yii::app()->params->masterdomain,"uploads/company_".$company_id."/".$avalue);
            }
        }
        
        /*
         * 5分钟执行一次，最多送500条，执行100条
         */
        public function actionExecSeverSql(){
            //send server sql 
            //exec client sql
        }
        
        /*
         * 5分钟执行一次，最多送500条，执行100条
         */
        public function actionExecClientSql(){
            //send client sql
            //exec server sql
            //downimagefile
            $this->clientDownImg();
        }
	
}