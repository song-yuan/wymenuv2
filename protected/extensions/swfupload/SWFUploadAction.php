<?php
/**
 * 支持onBeforUpload和onAfterUpload事件
 * 同SWFUpload Widget一起使用
 * 
 * @author luochong <luochong1987@gmail.com>
 * @version 1.0.2  2010.10.19 14:08 
 */

class SWFUploadAction extends CAction
{
    public $filepath='';//文件路径 c:/wamp/www/a.EXT 
    protected  $callbackJS = '';
    protected $companyId = 0;
    protected $folder = '';
    protected $thumbWidth =  300;
    protected $thumbHeight = 300;
    
    public function run()
    {
    	//var_dump($_POST);exit;
         $this->init();
         //modify by osy for map fileupload
         	$filepath = $this->upload();
	     exit();
    }

    public function onAfterUpload($event)
    {
        $this->raiseEvent('onAfterUpload',$event);
    }
    
    public function onBeforeUpload($event)
    {
        $this->raiseEvent('onBeforeUpload',$event);
    }
    
   protected function init()
   {
        if(!isset($_POST['SWFUpload']))
        {
            Yii::app()->getRequest()->redirect(Yii::app ()->homeUrl);
            return ;
        }
        //var_dump($_POST);exit;
        $this->callbackJS = isset($_POST['callbackJS'])?$_POST['callbackJS']:'';
        $this->companyId =  isset($_POST['companyId'])?$_POST['companyId']:'0';
        $this->folder =  isset($_POST['folder'])?$_POST['folder']:'';
        $this->thumbWidth =  isset($_POST['thumbWidth'])?$_POST['thumbWidth']:Yii::app()->params['image_width'];
        $this->thumbHeight =  isset($_POST['thumbHeight'])?$_POST['thumbHeight']:Yii::app()->params['image_height'];
        if($this->filepath ==='')
	    {
	         throw new Exception('文件路径没有指定');
	    }
	    $this->filepath = $this->genDir().'/'.$this->filepath;
//	    var_dump($this->filepath);exit;
	    //删除上一个临时文件
	    /*if(isset($_SESSION['temp_file'])&&is_file($_SESSION['temp_file'])&&(intval($_POST['fileQuenueLimit']) == 1))
        {
            unlink($_SESSION['temp_file']);                     //删除swfupload 的临时文件
        }  */
   }
   public function genDir(){
   		$path = Yii::app()->basePath.'/../uploads';
   		if($this->companyId){
   			$path .= '/company_'.$this->companyId;
   			if(!is_dir($path)){
   				mkdir($path, 0777,true);
   			}
   			if($this->folder){
   				$path .= '/'.$this->folder;
	   			if(!is_dir($path)){
	   				mkdir($path, 0777,true);
	   			}
   			}
   		}
   		return $path;
   }
   protected function upload()
   {
         $file = CUploadedFile::getInstanceByName('Filedata'); 
	     
         $this->onBeforeUpload(new CEvent(array('uploadedFile'=>&$file)));
	     $this->filepath = str_replace('.EXT','.'.$file->extensionName,$this->filepath);
	     $filename = substr(strrchr($this->filepath,'/'),1);
	     $this->filepath = str_replace('\\','/',$this->filepath);
	     $filedir = str_replace(array("/$filename",Yii::app()->params['uploadDir']),'',$this->filepath);
	     
	     if(!is_dir(Yii::app()->params['uploadDir'].$filedir))
	     {
	           mkdir(Yii::app()->params['uploadDir'].$filedir, 0777,true); 
	     }
	     $relativePath = Yii::app()->request->baseUrl.'/'.substr($filedir,strpos($filedir,'../')+1).'/'.$filename;
	     
	     $file->saveAs($this->filepath);
	     
	     $image = Yii::app()->image->load($this->filepath);
	     $image->resize($this->thumbWidth, $this->thumbHeight , Image::WIDTH)->quality(75)->sharpen(20);
	     $image->save(); // or $image->save('images/small.jpg');
	     
	     $_SESSION['temp_file'] = $this->filepath;
	     echo 'JS:('.$this->callbackJS.")('$relativePath','$filedir','{$file->getName()}');"; 
	     $this->onAfterUpload(new CEvent(array('uploadedFile'=>&$file,'name'=>$filename,'path'=>$filedir)));
	     return $this->filepath;
   }
}
