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
    	
		$db = Yii::app()->db;
        $sql = 'select t.* from nb_app_version t where t.delete_flag = 0 and t.lid =(select max(k.lid) from nb_app_version k where delete_flag = 0 and k.app_type = '.$appType.') and t.app_type ='.$appType;
        $command = $db->createCommand($sql);
        $appverifnos = $command->queryRow();
        if($appverifnos){	
	        $newverinfo = $appverifnos['app_version'];
	        $newapptype = $appverifnos['app_type'];
	        $newtype = $appverifnos['type'];
	        $content = $appverifnos['content'];
	        $url = $appverifnos['apk_url'];
	        //var_dump($url);exit;
	        if($newverinfo&&$newapptype&&$url){
		    	if($verinfo > $newverinfo){
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
//     	$verinfo = $data['versioninfo'];
//     	$type = $data['type'];
//     	$appType = $data['appType'];
//     	$newverinfo = '00.00.0510';
//     	$url = 'http://menu.wymenu.com/wymenuv2/downloadApk/menucharge.apk';
//     	//status = 0时，表示当前是最新版本;1时，表示云端有最新的版本可以进行更新;2时，表示终端app版本号比云端的版本号高;3时，表示未知状态.
//     	//type = 0时,表示自选更新;1时,表示强制更新.
//     	//appType = 1时,表示收银台APP,2时，表示后台APP。
//     	$msg = json_encode(array(
//     			'status' => 1,
//     			'verinfo' => $newverinfo,
//     			'type' => '0',
//     			'appType' => '1',
//     			'url' => $url,
//     	));
//         return $msg;
    }
    
    public static function  GrabImage($baseurl,$filename="") { 
        if($baseurl=="") return false; 
        if(file_exists($filename))
        {
            return "";
        }
        $basedir="";
        if($filename=="") { 
            $ext=strrchr($url,"."); 
            if($ext!=".gif" && $ext!=".jpg" && $ext!=".png") return false; 
            $filename=date("YmdHis").$ext; 
        } else{
            $basedir=substr($filename, 0,strrpos($filename,"/"));
        }

        try{
            if (!is_dir($basedir)){  		
                //第三个参数是“true”表示能创建多级目录，iconv防止中文目录乱码
                $res=mkdir(iconv("UTF-8", "GBK", $basedir),0777,true); 
                if (!$res){
                    return "";    
                }
            }
            ob_start(); 
            readfile($baseurl.$filename); 
            $img = ob_get_contents(); 
            ob_end_clean(); 
            $size = strlen($img); 

            $fp2=@fopen($filename, "a"); 
            fwrite($fp2,$img); 
            fclose($fp2); 
        }catch (Exception $e) {
            return "";
        }
        return $filename; 
    }
}