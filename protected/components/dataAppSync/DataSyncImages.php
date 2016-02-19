<?php
/**
 * appcan 用到图片缓存 图片离线缓存 icache zy_icache
 * 可以去百度，看看别人怎么弄的
 */
class DataSyncTables
{
    /**
     * 下载最新的图片文件
     * @param type $company_id
     */
    public static function clientDownImg($company_id){
        //$company_id = Yii::app()->request->getParam('companyId',0);
        //云端则不需要同步
        if(Yii::app()->params['cloud_local']=='c')
        {
            return;
        }
        try{           
            ob_start(); 
            readfile(Yii::app()->params['masterdomain'].'admin/datasync/serverImglist/companyId/'.$company_id); 
            $serverimgs = ob_get_contents(); 
            ob_end_clean(); 
            $fna=  json_decode($serverimgs);
            //var_dump($fna);exit;
            $filesnames2 = scandir("uploads/company_".$company_id);
            //var_dump($filesnames2);exit;                        
            $filesnames3=array_diff($fna, $filesnames2);
            foreach($filesnames3 as $akey=>$avalue)
            {
                DataSync::GrabImage(Yii::app()->params->masterdomain,"uploads/company_".$company_id."/".$avalue);
            }
        } catch (Exception $ex) {
            //osy//echo "下载文件异常";
        }
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