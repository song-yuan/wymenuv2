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
 		if($type == 'pdf'){
 			
 		}
 		return; 		
 	}
}
?>
