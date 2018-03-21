<?php
/**
 * appcan 版本更新。。。
 */
class DataSyncAppVersion
{
    /**
     * 查询最新的版本信息
     */
    public static function checkVersion($data){
        //查询是否是最新版本的安装包
    	$verinfo = $data['versioninfo'];
    	$type = $data['type'];
    	$appType = $data['appType'];
    	$newverinfo = '00.00.0000';
    	$newapptype = 1;
    	$newtype = 0;
    	$content = '';
    	$url = '';

    	$urlhead = Yii::app()->request->getHostInfo().'/wymenuv2/downloadApk/';
    	
		$db = Yii::app()->db;
        $sql = 'select * from nb_app_version where app_type ='.$appType.' and delete_flag = 0 order by lid desc limit 1';
        $command = $db->createCommand($sql);
        $appverifnos = $command->queryRow();
        if($appverifnos){	
	        $newverinfo = $appverifnos['app_version'];
	        $newapptype = $appverifnos['app_type'];
	        $newtype = $appverifnos['type'];
	        $content = $appverifnos['content'];
	        $urlend = $appverifnos['apk_url'];
	        
	        $url = $urlhead.$urlend;
	        if($newverinfo&&$newapptype&&$url){
		    	if($verinfo >= $newverinfo){
		    		$status = 0;
		    	}else{
		    		$status = 1;
		    	}
	        }else{
	        	$status = 0;
	        }
	    	
        }else{
        	$status = 0;
        }
        $msg = json_encode(array(
        		'status' => $status,
        		'verinfo' => $newverinfo,
        		'type' => $newtype,
        		'appType' => $newapptype,
        		'url' => $url,
        		'content' => $content,
        ));
        return $msg;
    }
    public static function getConnectUsInfo($data){
    	//查询是否是最新版本的安装包
    	
    	$contents = '';
    	$db = Yii::app()->db;
    	$sql = 'select t.* from nb_connect_us t where t.delete_flag = 0 order by t.type';
    	$command = $db->createCommand($sql);
    	$connectinfos = $command->queryAll();
    	if($connectinfos){
    		$status = 1;
    		$type0 = 1;
    		$type1 = 1;
    		$type2 = 1;
    		$type3 = 1;
    		$type4 = 1;
    		foreach ($connectinfos as $connectinfo){
    			$connect_type = $connectinfo['type'];
    			if($connect_type == 0){
    				$connect_name = 'QQ'.$type0.'：';
    				$type0++;
    			}elseif($connect_type == 1){
    				$connect_name = '手机'.$type1.'：';
    				$type1++;
    			}
    			elseif($connect_type == 2){
    				$connect_name = 'email'.$type1.'：';
    				$type1++;
    			}
    			elseif($connect_type == 3){
    				$connect_name = '固话'.$type1.'：';
    				$type1++;
    			}elseif($connect_type == 4){
    				$connect_name = '微信'.$type1.'：';
    				$type1++;
    			}
    			$content = '<div style="width: 94%;height: 26px;line-height: 26px;">
    						<div style="width: 20%;float: left;text-align: right;">
			    			<span id="updatever_title" >'.$connect_name.'</span>
			    			</div>
			    			<div style="width: 50%;float: left;margin-left: 2%;">
			    			<span id="print_success_num" >'.$connectinfo['content'].'</span>
			    			</div></div>';
    			$contents = $contents.$content;
    		}
    		
    	}else{
    		$status = 0;
    	}
    	$msg = json_encode(array(
    			'status' => $status,
    			'contents' => $contents,
    	));
    	return $msg;
    }
    

}