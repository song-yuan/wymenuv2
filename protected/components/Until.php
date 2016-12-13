<?php
class Until {	
 	/**
 	 * 导出excel表格
 	 */
 	static public function exportFile($data,$type='xml',$fileName='excel-export'){
 		if($type == 'xml'){
			
 			$xls = new Excel('UTF-8', false);
 			$xls->addArray($data);
 			$xls->generateXML($fileName);
 		}
 		if($type == 'txt'){
 			$txt = new Txt($data,$fileName);
 			$txt->create();
 		}
 		return; 		
 	}
        
        /**
 	 * 数据更新和删除时，要做一下判断...............
         * 如果返回的不是“1”，则不能更新或删除。
         * 
         * 判断每一个更新是否合法
         * 如果是服务器端模式Yii::app()->params['cloud_local']=='c'
         * 判断dpid的is2_cloud，是0，返回"1"，
         * 是1，如果lid是单数则返回：“云端不能更新本地数据”，
         * 否则返回“1”
         * 
         * 如果是服务器端模式Yii::app()->params['cloud_local']=='l'
         * 判断lid是否是双数，如果是则返回：“本地不能更新云端数据”，
         * 否则返回“1”
 	 */
 	static public function isUpdateValid(array $lids,$dpid,$parent){
            $db = Yii::app()->db;
//             if(Yii::app()->params['cloud_local']=='c')//云端服务器
//             {
//                 $sql = "select is2_cloud from nb_company where dpid=".$dpid;
//                 $command=$db->createCommand($sql);
//                 $nowval= $command->queryScalar();
//                 if($nowval=="1")
//                 {
//                    foreach ($lids as $lid)
//                     {
//                         if($lid%2==1)
//                         {
//                             // return yii::t('app',"云端不能更新本地数据");
//                              $title=yii::t('app',"云端不能更新本地数据");
//                              $parent->redirect(array('default/error2',
//                                      'companyId'=>$dpid,
//                                      'title' => $title                               
//                              ));
//                         }
//                     }
//                 }else{
//                     return "1";
//                 }                    
//             }else{//本地服务器
//                 foreach ($lids as $lid)
//                 {
//                     if($lid%2==0)
//                     {
//                         //return yii::t('app',"本地不能更新云端数据");
//                         $title=yii::t('app',"本地不能更新云端数据");
//                             $parent->redirect(array('default/error2',
//                                     'companyId'=>$dpid,
//                                     'title' => $title                               
//                             ));
//                     }
//                 }
//             } 
            return "1";
 	}
        
        /*
         * 判断是否使用本地服务器，
         * 如果是,
         * 云端开台，点单等功能不能操作，只能查看，否则冲突。
         * 一下controller：product default
         * before action 中调用
         * ？？因为如果是本地操作的话，
         * ？？点餐PAD的数据插入和更新是不可能在云端的，
         * ？？且点餐PAD也连接不到云端，
         * ？？所以点餐PAD部分好像不需要做isOperateValid 及 isUpdateValid
         * ？？后台收银部分呢？PAD和云端共存，所以要区分判断，isOperateValid 及 isUpdateValid
         * ？？后台其他部分也是只需要判断isUpdateValid
         */
        static public function isOperateValid($controller,$action,$dpid,$parent){
            $db = Yii::app()->db;
            //非法的controller->action数组
            $validOperate=array(
                //前台
                "product->confirmPadOrder",
                "product->opensite",
                //后台
                //"company->index",
                "default->readfeedback",
                "",
            );
//             if(Yii::app()->params['cloud_local']=='c')//云端服务器
//             {
//                 $sql = "select is2_cloud from nb_company where delete_flag = 0 and dpid=".$dpid;
//                 //var_dump($sql);
//                 $command=$db->createCommand($sql);
//                 $nowval= $command->queryScalar();
//                 //var_dump($sql,$nowval,$controller."->".$action);
//                 if($nowval=="1")
//                 {
//                     if(in_array($controller."->".$action,$validOperate))
//                     {
//                         $title=yii::t('app',"云端不能进行此项操作");
//                         $parent->redirect(array('default/error2',
//                                 'companyId'=>$dpid,
// 				'title' => $title                               
//                         ));
//                         return false;
//                     }
//                 }
//             }
            return true;
        }
        
        /*
         * 方法内判断某个操作是否合法
         */
        static public function validOperate($dpid,$parent){
            $db = Yii::app()->db;
            //非法的controller->action数组
            
            if(Yii::app()->params['cloud_local']=='c')//云端服务器
            {
                $sql = "select is2_cloud from nb_company where delete_flag = 0 and dpid=".$dpid;
                //var_dump($sql);
                $command=$db->createCommand($sql);
                $nowval= $command->queryScalar();
                //var_dump($sql,$nowval,$controller."->".$action);
                if($nowval=="1")
                {
                        $title=yii::t('app',"云端不能进行此项操作");
                        $parent->redirect(array('default/error2',
                                'companyId'=>$dpid,
				'title' => $title                               
                        ));
                        return false;
                }
            }
            return true;
        }
        
        static public function validOperateJson($dpid,$parent){
            $db = Yii::app()->db;
            //非法的controller->action数组
            
            if(Yii::app()->params['cloud_local']=='c')//云端服务器
            {
                $sql = "select is2_cloud from nb_company where delete_flag = 0 and dpid=".$dpid;
                //var_dump($sql);
                $command=$db->createCommand($sql);
                $nowval= $command->queryScalar();
                //var_dump($sql,$nowval,$controller."->".$action);
                if($nowval=="1")
                {
                       
                        return false;
                }
            }
            return true;
        }
}
?>
