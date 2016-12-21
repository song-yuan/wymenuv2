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
    	
    	$versions = array();
    	$versions = explode(',',$version);
    	
    	$msg = json_encode ( array (
    			'status' => true,
    			'verinfo' => '00.00.0003',
    			'type' => '1',
    			'appType' => '1'
    	) );
        return $msg;
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