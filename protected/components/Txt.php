<?php
/**
 * 
 * 生成txt文档
 * 
 */
class Txt
{
	public function __construct($data,$filename){
		$this->filename = $filename;
		$this->data = $data;
	}
	public function create(){
		$filename = $this->filename;  
		$encoded_filename = urlencode($filename);  
		$encoded_filename = str_replace("+", "%20", $encoded_filename);  
		
		header("Content-Type: application/octet-stream");  
		if (preg_match("/MSIE/", $_SERVER['HTTP_USER_AGENT']) ) {  
		    header('Content-Disposition:  attachment; filename="' . $encoded_filename . '"');  
		} elseif (preg_match("/Firefox/", $_SERVER['HTTP_USER_AGENT'])) {  
		    header('Content-Disposition: attachment; filename*="utf8' .  $filename . '"');  
		} else {  
		    header('Content-Disposition: attachment; filename="' .  $filename . '"');  
		} 
		echo $this->data;
	}
}