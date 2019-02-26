<?php
class Helper
{
	/**
	 * 转换字符集编码
	 * @param $data
	 * @param $targetCharset
	 * @return string
	 */
	public static function characet($data) {
		if( !empty($data) ){
			$fileType = mb_detect_encoding($data , array('UTF-8','GBK','LATIN1','BIG5')) ;
			if( $fileType != 'UTF-8'){
				$data = mb_convert_encoding($data ,'utf-8' , $fileType);
			}
		}
		return $data;
	}
	/**
	 * 写日志
	 */
	public static function writeLog($text) {
		$filePath = Yii::app()->basePath."/data/".date('Ymd',time())."-log.txt";
		file_put_contents ( $filePath, date ( "Y-m-d H:i:s" ) . "  " . $text . "\r\n", FILE_APPEND );
	}
	/**
	 * 获取毫秒时间戳
	 */
	public static function getMillisecond()  
    {  
    	$microtime = explode (' ', microtime());
    	$time = $microtime[1] . ($microtime[0] * 1000);
    	$time = explode ( '.', $time );
    	$time = $time[0];
    	return $time;
    }
	// 替换掉换行符等
	public static function dealString($str) {
		$replace = array(
					"\r\n","\\r\n",
					"\n","\\n", 
					"\r","\\r",
					"'","\'",
					"\t","\\t",
				);
		$str = str_replace($replace, '', $str);
		$replace = '/./u';
		$str = preg_replace_callback(
					$replace,
					function (array $match) {
						return strlen($match[0]) >= 4 ? '' : $match[0];
					},
					$str
				);
		return $str;
	}
	public static function genPassword($password)
	{
		return md5(md5($password).Yii::app()->params['salt']);
	}
	public static function getCompanyId($companyId) {
        if(Yii::app()->user->role <= '10')
        {
        	// 返回所选择的店
			return $companyId;
        }else{
        	// 只能操作 管理员所在店
            return Yii::app()->user->companyId ;
        }
	}
	public static function getCompanyChildren($companyId,$type=1) {
		$sql = 'select * from nb_company where comp_dpid='.$companyId.' and type='.$type;
		$company = Yii::app()->db->createCommand($sql)->queryAll();
		return $company;
	}
	public static function getCompanyIds($companyId) {
		if(Yii::app()->user->role<='10')
		{
			$models = Company::model()->findAll('t.dpid = '.$companyId);
			$companyIds = Company::model()->findAllBySql("select dpid from nb_company where comp_dpid=:dpid",array(':dpid'=>$companyId));
			$dpids = $companyId;
			if($models){
				foreach ($companyIds as $dpid){
					$dpids =$dpids.','.$dpid->dpid;
				}
				$gropids = array();
				$gropids = explode(',',$dpids);
				//$companyIds = $dpids;
			}
			return $gropids;
		}
	}
          
     public static function getCompanyName($companyId) {
         if($companyId)
         {
			$models = Company::model()->find('t.dpid = '.$companyId);
         }else{
            $models = Company::model()->find('t.dpid = '.Yii::app()->user->companyId); 
         }
         return $models->company_name;
	}

	public static function getCompanyType($companyId) {
		if($companyId)
		{
			$models = Company::model()->find('t.dpid = '.$companyId);
		}else{
			$models = Company::model()->find('t.dpid = '.Yii::app()->user->companyId);
		}
		return $models->type;
	}
        
	public static function genCompanyOptions() {
		$companies = Company::model()->findAll('delete_flag=0') ;
		return CHtml::listData($companies, 'dpid', 'company_name');
	}
	public static function genProductMaterial() { // 品项名称
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$companies = ProductMaterial::model()->findAll('delete_flag=0 and dpid='.$companyId) ;
		return CHtml::listData($companies, 'lid', 'material_name');
	}
	public static function genStockUnit() { // 库存单位名称
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$companies = MaterialUnit::model()->findAll('unit_type=0 and delete_flag=0 and dpid='.$companyId) ;
		return CHtml::listData($companies, 'lid', 'unit_name');
	}
	public static function genSalesUnit() { // 零售单位名称
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$companies = MaterialUnit::model()->findAll('unit_type=1 and delete_flag=0 and dpid='.$companyId) ;
		return CHtml::listData($companies, 'lid', 'unit_name');
	}
	public static function getCardLevel() { // 传统卡等级名称
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$cardlevels = BrandUserLevel::model()->findAll('level_type=0 and delete_flag=0 and dpid='.$companyId) ;
		return CHtml::listData($cardlevels, 'lid', 'level_name');
	}
	public static function getCardLevels() { // 传统卡等级名称
		$db = Yii::app()->db;
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$sql = 'select lid as lids,lid,level_name from nb_brand_user_level  where level_type = 0 and delete_flag =0 and dpid='.$companyId;
		$cardlevels = Yii::app()->db->createCommand($sql)->queryAll();
		return CHtml::listData($cardlevels, 'lids', 'level_name');
	}
	public static function genOrgClass() { // 组织类型名称
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$companies = OrganizationClassification::model()->findAll('delete_flag=0 and dpid='.$companyId) ;
		return CHtml::listData($companies, 'lid', 'classification_name');
	}
	public static function genMfrClass() { //厂商类型名称
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$companies = ManufacturerClassification::model()->findAll('delete_flag=0 and dpid='.$companyId) ;
		return CHtml::listData($companies, 'lid', 'classification_name');
	}
	public static function genMfrInfoname() { //厂商名称
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$companies = ManufacturerInformation::model()->findAll('delete_flag=0 and dpid='.$companyId) ;
		//var_dump($companies);exit;
		return CHtml::listData($companies, 'lid', 'manufacturer_name');
	}
	public static function genOrgCompany($companyId) { //公司及组织名
		$company = Company::model()->find('delete_flag=0 and dpid='.$companyId) ;
		if($company->type==0){
			$company = Company::model()->findAll('dpid='.$companyId.' or comp_dpid='.$companyId.' and delete_flag=0') ;
		}else{
			$company = Company::model()->findAll('dpid='.$companyId.' and delete_flag=0') ;
		}
		return $company;
	}
	public static function genStoreCompany($companyId) { //公司所有仓库
		$company = Company::model()->find('delete_flag=0 and dpid='.$companyId) ;
		if($company->type==0){
			$company = Company::model()->findAll('dpid='.$companyId.' or comp_dpid='.$companyId.' and delete_flag=0') ;
			return $company;
		}else{
			$company = Company::model()->findAll('dpid='.$companyId.' or comp_dpid='.$company->comp_dpid.' and delete_flag=0 and type=2') ;
			return $company;
		}
		
	}
	public static function genUsername($companyId,$s=0) {//管理员
		if($s){
			$companies = User::model()->findAll('delete_flag=0 and username = "'.yii::app()->user->username.'" and status=1 and role >='.Yii::app()->user->role) ;
		}else{
			$companies = User::model()->findAll('delete_flag=0 and dpid='.$companyId.' and status=1 and role >='.Yii::app()->user->role) ;
		}// var_dump($companies);exit;
		return CHtml::listData($companies, 'lid', 'username');
	}
	public static function getOpretion($companyId) {//管理员
		
		$companies = User::model()->findAll('delete_flag=0 and dpid='.$companyId.' and status=1 and role >='.Yii::app()->user->role) ;
		return CHtml::listData($companies, 'username', 'username');
	}
	public static function genRetreats($dpid) {//盘损原因
		
		$companies = Retreat::model()->findAll('delete_flag=0 and dpid='.$dpid.' and type=2 ') ;
		
		return CHtml::listData($companies, 'lid', 'name');
	}
	// 品项分类
	public static function getCategory($companyId,$pid=0){
		$command = Yii::app()->db->createCommand('select * from nb_material_category where dpid=:companyId and pid=:pid and delete_flag=0');
		$command->bindValue(':companyId',$companyId);
		$command->bindValue(':pid',$pid);
		return $command->queryAll();
	}
	//生成文件名字
	public static function genFileName(){
		if (function_exists('com_create_guid') === true)
		{
			return trim(com_create_guid(), '{}');
		}
		return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
	}
	/**
	 * 生成随机数
	 */
	public static function randNum($len){
		$rand = '';
		for ($i=0;$i<$len;$i++){
			$rand .= mt_rand(0, 9);
		}
		return $rand;
	}
	/**
	 * 判断是不是微信浏览器
	 */
	public static function isMicroMessenger() {
		return stripos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ? true : false;
	}
	/**
	 * 
	 * @param
	 * @param number $pid
	 * 计算折扣后产品价格
	 * $price 产品价格 $total 产品价格总和  $pay 收入总价
	 * 
	 * 如果套餐  $price 套餐内单品价格  $total 套餐内计算原价 $pay 套餐价格
	 * 
	 */
	public static function dealProductPrice($price,$total,$pay) {
		if($total == 0){
			$result = 0;
		}else{
			if($total > $pay){
				$discount = $total - $pay;
				$result = number_format(($price - $price/$total*$discount),4);
			}else{
				$over = $pay - $total ;
				$result = number_format(($price + $price/$total*$over),4);
			}
		}
		return $result;
	}
	public static function getCategories($companyId,$pid=0){
		$command = Yii::app()->db->createCommand('select * from nb_product_category where dpid=:companyId and pid=:pid and delete_flag=0 and cate_type!=2');
		$command->bindValue(':companyId',$companyId);
		$command->bindValue(':pid',$pid);
		return $command->queryAll();
	}
	public static function getSetCategories($companyId,$pid=0){
		$command = Yii::app()->db->createCommand('select * from nb_product_category where dpid=:companyId and pid=:pid and delete_flag=0 and cate_type=2');
		$command->bindValue(':companyId',$companyId);
		$command->bindValue(':pid',$pid);
		return $command->queryAll();
	}
	/**
	 * 导出excel表格（适合没有单元格合并的情况）
	 * @param array $data 二维数组
	 * @param array $table_head 表头（即excel工作表的第一行标题）
	 * @param string $file_name 文件名
	 * @param string $sheet_name 工作表名
	 */
	public static function exportExcel($table_head = array(), $data = array(), $file_name='excel', $sheet_name='sheet')
	{
		// 创建PHPExcel对象
		$objPHPExcel = new PHPExcel();  
		
		// 设置excel文件的属性，在excel文件->属性->详细信息，可以看到这些值
		$objPHPExcel->getProperties()  //获得文件属性对象，给下文提供设置资源
					->setCreator("admin")     //设置文件的创建者
					->setLastModifiedBy("admin")    //最后修改者
					->setTitle("Office 2007 XLSX Record Document")    //标题
					->setSubject("Office 2007 XLSX Record Document")  //主题
					->setDescription("Record document for Office 2007 XLSX, generated using PHP classes.") //描述
					->setKeywords("office 2007 openxml php")    //关键字
					->setCategory("export file");               //类别
	
		// 设置Excel文档的第一张sheet（工作表）为活动表，即当前操作的表。
		$objPHPExcel->setActiveSheetIndex(0);
	
		// 获取当前操作的工作表
		$activeSheet = $objPHPExcel->getActiveSheet();
	
		// 设置工作表的名称
		$activeSheet->setTitle($sheet_name);
		
		//设置第1行的行高 第2行的行高
		$activeSheet->getRowDimension('1')->setRowHeight(30);
		$activeSheet->getRowDimension('2')->setRowHeight(24);
		
		$objPHPExcel->getDefaultStyle()->getFont()->setName('宋体');
		$objPHPExcel->getDefaultStyle()->getFont()->setSize(16);
		
		// 设置工作表的表头
		foreach ($table_head as $row=>$v) {
			// 居中 加粗
			$column = PHPExcel_Cell::stringFromColumnIndex($row);
			$activeSheet->getStyle($column."2")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$activeSheet->getStyle($column."2")->getFont()->setBold(true);
			// 设置单元格的值
			$activeSheet->setCellValue($column."2", $v);
			
			if($row == count($table_head)-1){
				//设置excel表格的 标题 合并单元格 设置居中
				$activeSheet->setCellValue('A1', $sheet_name);
				$activeSheet->getStyle('A1')->getFont()->setSize(20);
				$activeSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$activeSheet->mergeCells('A1:'.$column.'1');
			}
		}
		
		// 将$data中的数据填充到单元格中
		foreach ($data as $key=>$col) {
			foreach ($col as $row=>$v ) {
				// 字体大小
				$column = PHPExcel_Cell::stringFromColumnIndex($row);
				$activeSheet->getStyle($column.($key+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$activeSheet->setCellValue($column.($key+3), $v);
				if($key==0){
					$activeSheet->getColumnDimension($column)->setWidth(strlen($v)+5);
				}
			}
			//设置每列宽度
		}
	
		// 导出Excel表格
		$file_name .='('. date('m.d'). ')';   // 文件名
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$file_name.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
		$objWriter->save('php://output');
	}
	 /**
	  * ----截取字符串为固定长度---
	  */
	public static function truncate_utf8_string($string, $length, $etc = '')
	{
		$result = '';
		$string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
		$strlen = strlen($string);
		for ($i = 0; (($i < $strlen) && ($length > 0)); $i++)
		{
			if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0'))
			{
				if ($length < 1.0)
				{
					break;
				}
				$result .= substr($string, $i, $number);
				$length -= 1.0;
				$i += $number - 1;
			}
			else
			{
				$result .= substr($string, $i, 1);
				$length -= 0.5;
			}
		}
		$result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
		if ($i < $strlen)
		{
			$result .= $etc;
		}
		return $result;
	}       
        
	public function getAccountMoney($account_no){
		$accountMoney = '';
		if($account_no){
			$sql = 'select sum(t.pay_amount) as all_zhifu,t.* from nb_order_pay t where t.paytype not in(9,10) and t.order_id in(select t1.lid from nb_order t1 where t1.account_no = '.$account_no.')';
			$connect = Yii::app()->db->createCommand($sql);
			$money = $connect->queryRow();
			$accountMoney = $money['all_zhifu'];
		}
		return $accountMoney;
	}
	public function getOriginalMoney($account_no){
		$originalMoney = '';
		if($account_no){
			$sql = 'select sum(t.original_price*t.amount) as all_original from nb_order_product t  where t.is_retreat = 0 and t.product_order_status in(1,2) and t.order_id in(select t1.lid from nb_order t1 where t1.account_no = '.$account_no.')';
			$connect = Yii::app()->db->createCommand($sql);
			$money = $connect->queryRow();
			$originalMoney = $money['all_original'];
		}
		return $originalMoney;
	}
	//获取座位信息
	public function getSiteName($orderId){
		$sitename="";
		$sitetype="";
	
		$sql = 'select t.site_id, t.dpid, t1.site_level, t1.type_id, t1.serial, t2.name,t2.simplecode from nb_order t, nb_site t1, nb_site_type t2 where t.site_id = t1.lid and t.dpid = t1.dpid and t1.type_id = t2.lid and t.dpid = t2.dpid and t.lid ='. $orderId;
		$connect = Yii::app()->db->createCommand($sql);
		$site = $connect->queryRow();
		$retsite = "";
		if($site['site_id'] && $site['dpid'] ){
			$sitelevel = $site['site_level'];
			$sitename = $site['simplecode'];
			$sitetype = $site['serial'];
			$retsite = $sitename.$sitetype;
		}
		return $retsite;
	}
}