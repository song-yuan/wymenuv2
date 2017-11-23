<?php
class Helper
{
	/**
	 * 转换字符集编码
	 * @param $data
	 * @param $targetCharset
	 * @return string
	 */
	static function characet($data) {
		if( !empty($data) ){
			$fileType = mb_detect_encoding($data , array('UTF-8','GBK','LATIN1','BIG5')) ;
			if( $fileType != 'UTF-8'){
				$data = mb_convert_encoding($data ,'utf-8' , $fileType);
			}
		}
		return $data;
	}
	static function writeLog($text) {
		$filePath = Yii::app()->basePath."/data/".date('Ymd',time())."-log.txt";
		file_put_contents ( $filePath, date ( "Y-m-d H:i:s" ) . "  " . $text . "\r\n", FILE_APPEND );
	}
	// 替换掉换行符等
	static function dealString($str) {
		$str = str_replace(array("\r\n", "\\r\n", "\n","\\n", "\r", "\\r", "'", "\'"), '', $str);
		return $str;
	}
	static public function genPassword($password)
	{
			return md5(md5($password).Yii::app()->params['salt']);
	}
	static public function getCompanyId($companyId) {
            if(Yii::app()->user->role <= '9')
            {
		return $companyId;
            }else{
                return Yii::app()->user->companyId ;
            }
	}
	static public function getCompanyIds($companyId) {
		if(Yii::app()->user->role=='5')
		{
			$models = Company::model()->findAll('t.dpid = '.$companyId);
			$companyIds = Company::model()->findAllBySql("select dpid from nb_company where comp_dpid=:dpid",array(':dpid'=>$companyId));
			//$companyIds = Company::model()->findAllByPk('dpid',"comp_dpid =:dpid",array(':dpid'=>$companyId));
			//$companyIds = Company::model()->findAllByPk(array('condition'=>'comp_dpid = '.$companyId,'index'=>'dpid'));
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
          
     static public function getCompanyName($companyId) {
            if($companyId)
            {
				$models = Company::model()->find('t.dpid = '.$companyId);
                //return Yii::app()->user->role == User::POWER_ADMIN ? $companyId : Yii::app()->user->companyId ;
               
            }else{
                $models = Company::model()->find('t.dpid = '.Yii::app()->user->companyId); 
            }
             return $models->company_name;
	}

	static public function getCompanyType($companyId) {
		if($companyId)
		{
			$models = Company::model()->find('t.dpid = '.$companyId);
		}else{
			$models = Company::model()->find('t.dpid = '.Yii::app()->user->companyId);
		}
		return $models->type;
	}
        
	static public function genCompanyOptions() {
		$companies = Company::model()->findAll('delete_flag=0') ;
		return CHtml::listData($companies, 'dpid', 'company_name');
	}
	static public function genProductMaterial() { // 品项名称
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$companies = ProductMaterial::model()->findAll('delete_flag=0 and dpid='.$companyId) ;
		return CHtml::listData($companies, 'lid', 'material_name');
	}
	static public function genStockUnit() { // 库存单位名称
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$companies = MaterialUnit::model()->findAll('unit_type=0 and delete_flag=0 and dpid='.$companyId) ;
		return CHtml::listData($companies, 'lid', 'unit_name');
	}
	static public function genSalesUnit() { // 零售单位名称
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$companies = MaterialUnit::model()->findAll('unit_type=1 and delete_flag=0 and dpid='.$companyId) ;
		return CHtml::listData($companies, 'lid', 'unit_name');
	}
	static public function getCardLevel() { // 传统卡等级名称
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$cardlevels = BrandUserLevel::model()->findAll('level_type=0 and delete_flag=0 and dpid='.$companyId) ;
		return CHtml::listData($cardlevels, 'lid', 'level_name');
	}
	static public function getCardLevels() { // 传统卡等级名称
		$db = Yii::app()->db;
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$sql = 'select CONVERT(t.lid,SIGNED) as lids,t.lid,t.level_name from nb_brand_user_level t where t.level_type = 0 and t.delete_flag =0 and t.dpid='.$companyId;
		$cardlevels = Yii::app()->db->createCommand($sql)->queryAll();
		//var_dump($cardlevels);exit;
		//$cardlevels = BrandUserLevel::model()->findAll('level_type=0 and delete_flag=0 and dpid='.$companyId) ;
		return CHtml::listData($cardlevels, 'lids', 'level_name');
	}
	static public function genOrgClass() { // 组织类型名称
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$companies = OrganizationClassification::model()->findAll('delete_flag=0 and dpid='.$companyId) ;
		return CHtml::listData($companies, 'lid', 'classification_name');
	}
	static public function genMfrClass() { //厂商类型名称
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$companies = ManufacturerClassification::model()->findAll('delete_flag=0 and dpid='.$companyId) ;
		return CHtml::listData($companies, 'lid', 'classification_name');
	}
	static public function genMfrInfoname() { //厂商名称
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$companies = ManufacturerInformation::model()->findAll('delete_flag=0 and dpid='.$companyId) ;
		//var_dump($companies);exit;
		return CHtml::listData($companies, 'lid', 'manufacturer_name');
	}
	static public function genOrgCompany($companyId) { //公司及组织名
		$company = Company::model()->find('delete_flag=0 and dpid='.$companyId) ;
		if($company->type==0){
			$company = Company::model()->findAll('dpid='.$companyId.' or comp_dpid='.$companyId.' and delete_flag=0') ;
		}else{
			$company = Company::model()->findAll('dpid='.$companyId.' and delete_flag=0') ;
		}
		return $company;
	}
	static public function genStoreCompany($companyId) { //公司所有仓库
		$company = Company::model()->find('delete_flag=0 and dpid='.$companyId) ;
		if($company->type==0){
			$company = Company::model()->findAll('dpid='.$companyId.' or comp_dpid='.$companyId.' and delete_flag=0') ;
			return $company;
		}else{
			$company = Company::model()->findAll('dpid='.$companyId.' or comp_dpid='.$company->comp_dpid.' and delete_flag=0 and type=2') ;
			return $company;
		}
		
	}
	static public function genUsername($companyId) {//管理员
		$companies = User::model()->findAll('delete_flag=0 and dpid='.$companyId.' and status=1 and role >='.Yii::app()->user->role) ;
		// var_dump($companies);exit;
		return CHtml::listData($companies, 'lid', 'username');
	}
	
	// 品项分类
	static public function getCategory($companyId,$pid=0){
		$command = Yii::app()->db->createCommand('select * from nb_material_category where dpid=:companyId and pid=:pid and delete_flag=0');
		$command->bindValue(':companyId',$companyId);
		$command->bindValue(':pid',$pid);
		return $command->queryAll();
	}
	//生成文件名字
	static public function genFileName(){
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
	 * @param unknown $companyId
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
	static public function getCategories($companyId,$pid=0){
		$command = Yii::app()->db->createCommand('select * from nb_product_category where dpid=:companyId and pid=:pid and delete_flag=0 and cate_type!=2');
		$command->bindValue(':companyId',$companyId);
		$command->bindValue(':pid',$pid);
		return $command->queryAll();
	}
	static public function getSetCategories($companyId,$pid=0){
		$command = Yii::app()->db->createCommand('select * from nb_product_category where dpid=:companyId and pid=:pid and delete_flag=0 and cate_type=2');
		$command->bindValue(':companyId',$companyId);
		$command->bindValue(':pid',$pid);
		return $command->queryAll();
	}
	//计算order的总价array('total'=>'总价','miniConsumeType'=>'最低消费类型','miniConsume'=>'最低消费','overTime'=>'超时时间','siteOverTime'=>'超时计算单位','buffer'=>'超时计算点','number'=>'人数')
	static public function calOrderConsume(Order $order, SiteNo $siteNo , $total){
		//$siteNo = SiteNo::model()->find('$order->site_no_id');
		$site = Site::model()->with('siteType')->find('t.lid=:siteid and t.dpid=:dpid',array('siteid'=>$siteNo->site_id,':dpid'=>$siteNo->dpid));
		$result = array('total'=>$total,'remark'=>$site->siteType->name.':'.$site->serial);
		if(!$site->has_minimum_consumption) {
			return $result;
		}
		if($site->minimum_consumption_type == 0) {
			//按时间收费
			$payTime = $order->pay_time ? $order->pay_time : time() ;
			$orderTime = $payTime - $order->create_time ;
			$overtime = $orderTime - $site->period*60 ;
			$overtimeTimes = 0 ;
			$buffer = $site->buffer*60 ;
			$siteOvertime = $site->overtime * 60 ;
			
			if($overtime < $buffer){
				$overtimeTimes = 0 ;
			}else {
				$mod = intval($overtime / $siteOvertime) ;
				$remainder = $overtime % $siteOvertime ;
				$overtimeTimes = $mod + ($remainder >= $buffer ? 1 : 0);
			}
                        $remark=yii::t('app','按时计费，最低消费{site->minimum_consumption}元/{site->period}分钟，超时每{site->overtime}分钟收费{site->overtime_fee}元，超出{site->buffer}分钟按{site->overtime}分钟计算。');
			str_replace('{site->minimum_consumption}',$site->minimum_consumption,$remark);
                        str_replace('{site->period}',$site->period,$remark);
                        str_replace('{site->overtime}',$site->overtime,$remark);
                        str_replace('{site->overtime_fee}',$site->overtime_fee,$remark);
                        str_replace('{site->buffer}',$site->buffer,$remark);
                         $result = array(
					'total' => $site->minimum_consumption + $site->overtime_fee * $overtimeTimes ,
					'remark'=>$remark,
			);
		}elseif($site->minimum_consumption_type == 1) {
			//按人头收费
                        $remark=yii::t('app','按人数计费，最低消费{site->minimum_consumption}元/人，每增加一人收取{site->minimum_consumption}元（实际总人数{order->number}）');
                        str_replace('{site->minimum_consumption}',$site->minimum_consumption,$remark);
                        str_replace('{order->number}',$order->number,$remark);
			$result = array(
					'total' => $site->minimum_consumption * $order->number,
					'remark'=>$remark,
			);
		}
		$result['total'] = $result['total'] > $total ? $result['total'] : $total ;
		return $result;
	}
	/**
	 * 
	 * 最低消费说明
	 * 
	 */
	static public function lowConsumeInfo($siteId){
		$site = Site::model()->findByPk($siteId);
		$result = array('total'=>0,'remark'=>'');
		if(!$site->has_minimum_consumption) {
			$result['remark'] = yii::t('app','无最低消费');
			return $result;
		}
		if($site->minimum_consumption_type == 0) {
			//按时间收费
                        $remark=yii::t('app','按时计费，最低消费{site->minimum_consumption}元/{site->period}分钟，超时每{site->overtime}分钟收费{site->overtime_fee}元，超出{site->buffer}分钟按{site->overtime}分钟计算。');
                        str_replace('{site->minimum_consumption}',$site->minimum_consumption,$remark);
                        str_replace('{site->period}',$site->period,$remark);
                        str_replace('{site->overtime}',$site->overtime,$remark);
                        str_replace('{site->overtime_fee}',$site->overtime_fee,$remark);
                        str_replace('{site->buffer}',$site->buffer,$remark);
			$result = array(
					'total' => 0,
					'remark'=>$remark,
			);
		}elseif($site->minimum_consumption_type == 1) {
			//按人头收费
                        $remark=yii::t('app','按人数计费，最低消费{site->minimum_consumption}元/人，每增加一人收取{site->minimum_consumption}元');
			str_replace('{site->minimum_consumption}',$site->minimum_consumption,$remark);
                        str_replace('{order->number}',$order->number,$remark);
                        $result = array(
					'total' => 0,
					'remark'=>$remark,
			);
		}
		return $result;
	}	
        //not in use
	static public function printOrderGoods(Order $order , $reprint = false){
		$orderProducts = OrderProduct::getOrderProducts($order->order_id);
		$siteNo = SiteNo::model()->findByPk($order->site_no_id);
		$site = Site::model()->findByPk($siteNo->site_id);
		$siteType = SiteType::model()->findByPk($site->type_id);
		
		$listData = array();
		foreach ($orderProducts as $product) {
			$key = $product['department_id'];
			if(!isset($listData[$key])) $listData[$key] = '';
			if(!$listData[$key]) {
				if($reprint) {
					$listData[$key].= str_pad('丢单重打' , 48,' ',STR_PAD_BOTH).'<br>';
				}
				$listData[$key].= str_pad('座号：'.$siteType->name.' '.$site->serial , 48,' ',STR_PAD_BOTH).'<br>';
				$listData[$key].= str_pad('时间：'.date('Y-m-d H:i:s',time()),30,' ').str_pad('人数：'.$order->number,10,' ').'<br>';
				$listData[$key].= str_pad('',48,'-').'<br>';
				$listData[$key].= str_pad('菜品',20,' ').str_pad('数量',20,' ').'<br>';
			}
			$listData[$key] .= str_pad($product['product_name'],20,' ').str_pad($product['amount'],20,' ').'<br>';
		}
		foreach ($listData as $departmentId=>$listString) {
			$department = Department::model()->findByPk($departmentId);
			if(!$department->printer_id) {
				if((Yii::app()->request->isAjaxRequest)) {
					echo Yii::app()->end(array('status'=>false,'msg'=>'请关联打印机'));
				} else {
					return array('status'=>false,'msg'=>'请关联打印机');
				}
			}
			$printer = Printer::model()->findByPk($department->printer_id);
			$listKey = $order->dpid.'_'.$printer->ip_address;
			$listString .=str_pad('打印机：'.$department->name,48,' ').'<br>';
			
			//$listString .=str_pad('点菜员：'.$);
			$list = new ARedisList($listKey);
			if($department->list_no) {
				for($i=0;$i<$department->list;$i++){
					if($reprint) {
						$list->add($listString);
					} else {
						$list->unshift($listString);
					}
					$channel = new ARedisChannel($order->dpid.'_PD');
					$channel->publish($listKey);
				}
			}
		}
		if((Yii::app()->request->isAjaxRequest)) {
			echo Yii::app()->end(json_encode(array('status'=>true,'msg'=>'')));
		} else {
			return array('status'=>true,'msg'=>'');
		}
	}
        //not in use
	static public function printCartGoods($companyId , $code,$reprint = false){
		$orderProducts = Cart::getCartProducts($companyId,$code);
		$siteNo = SiteNo::model()->find('dpid=:companyId and code=:code and delete_flag=0',array(':companyId'=>$companyId,':code'=>$code));
		$site = Site::model()->findByPk($siteNo->site_id);
		$siteType = SiteType::model()->findByPk($site->type_id);
		$listData = array();
		foreach ($orderProducts as $product) {
			$key = $product['department_id'];
			if(!isset($listData[$key])) $listData[$key] = '';
			if(!$listData[$key]) {
				if($reprint) {
					$listData[$key].= str_pad('丢单重打' , 48,' ',STR_PAD_BOTH).'<br>';
				}
				$listData[$key].= str_pad('座号：'.$siteType->name.' '.$site->serial , 48,' ',STR_PAD_BOTH).'<br>';
				$listData[$key].= str_pad('时间：'.date('Y-m-d H:i:s',time()),30,' ').str_pad('人数：'.$siteNo->number,10,' ').'<br>';
				$listData[$key].= str_pad('',48,'-').'<br>';
				$listData[$key].= str_pad('菜品',20,' ').str_pad('数量',20,' ').'<br>';
			}
			$listData[$key] .= str_pad($product['product_name'],20,' ').str_pad($product['product_num'],20,' ').'<br>';
		}
		foreach ($listData as $departmentId=>$listString) {
			$department = Department::model()->findByPk($departmentId);
			if(!$department->printer_id) {
				if((Yii::app()->request->isAjaxRequest)) {
					echo Yii::app()->end(array('status'=>false,'msg'=>'请关联打印机'));
				} else {
					return array('status'=>false,'msg'=>'请关联打印机');
				}
			}
			$printer = Printer::model()->findByPk($department->printer_id);
			$listKey = $companyId.'_'.$printer->ip_address;
			$listString .=str_pad('打印机：'.$department->name,48,' ').'<br>';
			
			//$listString .=str_pad('点菜员：'.$);
			$list = new ARedisList($listKey);
			if($department->list_no) {
				for($i=0;$i<$department->list_no;$i++){
					if($reprint) {
						$list->add($listString);
					} else {
						$list->unshift($listString);
					}
					$channel = new ARedisChannel($companyId.'_PD');
					$channel->publish($listKey);
				}
			}
		}
		$cart = Cart::model()->deleteAll('dpid=:companyId and code=:code',array(':companyId'=>$companyId,':code'=>$code));
		if((Yii::app()->request->isAjaxRequest)) {
			echo Yii::app()->end(json_encode(array('status'=>true,'msg'=>'')));
		} else {
			return array('status'=>true,'msg'=>'');
		}
	}
	 //----截取字符串为固定长度---
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
        
        static public function getPlaceholderLen($str,$len){
		$pl=(strlen($str) + mb_strlen($str,'UTF8'))/2;
                $appendstr=substr('                                            ',0,$len-$pl);
                return $str.$appendstr;
	}
        
        //
        static public function getPlaceholderLenBoth($str,$len){
		$pl=(strlen($str) + mb_strlen($str,'UTF8'))/2;
                $intpl=$pl/$len;
                $rempl=$pl%$len;
                $leftlen=($len-$rempl)/2;
                $leftappendstr=substr('                                            ',0,$leftlen);
                //$rightappendstr=substr('                                            ',0,$len-$rempl-$leftappendstr);
                //return mb_substr($str,0,$len*$intpl,'UTF8').$leftappendstr.substr($str,$len*$intpl,$rempl).$rightappendstr;//乱码，以后再修正
                if($intpl>0)
                {
                    return $str;
                }else{
                    return $leftappendstr.$str;
                }
	}
        
        /**
         * 
         * @param type $str title
         * @param type $len 每行8个字符
         * @param type $code SJIS ＧＢＫ
         * @return string
         */
        static public function setPrinterTitle($str,$len){
		$pl=mb_strlen($str,"UTF-8");
                //$intpl=$pl/$len;
                $rempl=$pl%$len;
                $retstr=mb_substr($str,0,$pl-$rempl,"UTF-8");
                if($rempl>0)
                {
                        $leftlen=($len-$rempl)/2;
                        $leftappendstr=mb_substr('                                            ',0,$leftlen*2,"UTF-8");
                        $retstr=$retstr.$leftappendstr;
                        $retstr=$retstr.mb_substr($str,$pl-$rempl,$rempl,"UTF-8");
                }
                return $retstr;
	}
        
        /**
         * 
         * @param type $str 产品名称
         * @param type $linelen 一行12个字符
         * @param type $len 8个汉字字符
         * @param type $code SJIS GBK
         * @return string
         */
        static public function setProductName($str,$linelen,$len){
		$pl=mb_strlen($str,"UTF-8");
                $intpl=$pl/$len;
                $rempl=$pl%$len;
                $retstr="";
				//var_dump($intpl);exit;
                if($intpl < 1)
                {
                    return $str;
                }
                $retstr=mb_substr($str,0,$len,"UTF-8");
                for($tempi=1;$tempi<$intpl;$tempi++)
                {
                    $retstr=$retstr.mb_substr('                                            ',0,($linelen-$len)*2,"UTF-8");
                    $retstr=$retstr.mb_substr($str,$len*$tempi,$len,"UTF-8");                    
                }
                if($rempl>0)
                {
                        $retstr=$retstr.mb_substr('                                            ',0,($linelen-$len)*2,"UTF-8");
                        $retstr=$retstr.mb_substr($str,$len*$intpl,$rempl,"UTF-8"); 
                }                
                return $retstr;
	}
        
        /**
         * 截取中文和英文无乱码现象
         * @param type $string
         * @param type $start
         * @param type $length
         * @return type
         */
        function GBsubstr($string, $start, $length) {  
            if(strlen($string)>$length){  
                $str=null;  
                $len=$start+$length;  
                for($i=$start;$i<$len;$i++){  
                    if(ord(substr($string,$i,1))>0xa0){  
                        $str.=substr($string,$i,2);  
                        $i++;  
                    }else{  
                        $str.=substr($string,$i,1);  
                    }  
                }  
                return $str;  
            }else{  
                return $string;  
            }  
        }
        
        //根据订单数据和padid开始开源清单
        //包括条码，总价，产品清单
        //send by workerman encode by GBK or shift-JIS
	static public function printPadList(Order $order , $padid){
		$pad=Pad::model()->find(' dpid=:dpid and lid=:lid',array(':dpid'=>$order->dpid,'lid'=>$padid));
                $printer = Printer::model()->find('lid=:printerId and dpid=:dpid',  array(':printerId'=>$pad->printer_id,':dpid'=>$order->dpid));
		if(empty($printer)) {
                        return array('status'=>false,'dpid'=>$order->dpid,'jobid'=>"0",'type'=>'none','msg'=>yii::t('app','PAD还没有设置默认打印机'));		
		}
		$hasData=false;
                $orderProducts = OrderProduct::getOrderProducts($order->lid,$order->dpid);
                ///site error because tempsite and reserve**************
                if($order->is_temp==0)
                {
                    $site = Site::model()->find('lid=:lid and dpid=:dpid',  array(':lid'=>$order->site_id,':dpid'=>$order->dpid));
                    $siteType = SiteType::model()->find('lid=:lid and dpid=:dpid',  array(':lid'=>$site->type_id,':dpid'=>$order->dpid));
                    $strSite=Helper::getPlaceholderLen(yii::t('app','座号：').$siteType->name.' '.$site->serial , 48);
                }else{
                    $strSite=Helper::getPlaceholderLen(yii::t('app','座号：临时座').$order->site_id%1000 , 48);
                }
		
		///////$listKey = $order->dpid.'_'.$printer->ip_address;                		
		//var_dump($list);exit;
                //$listData = array(Helper::getPlaceholderLenBoth($order->company->company_name, 48));
                $listData = array("22". Helper::setPrinterTitle($order->company->company_name,8));
		array_push($listData,$strSite);                
		array_push($listData,str_pad('',48,'-'));
		
		foreach ($orderProducts as $product) {
                    //var_dump($product);exit;
                    $hasData=true;
                    if(Yii::app()->language=='jp')
                    {
                        array_push($listData,Helper::getPlaceholderLen($product['product_name'],36).Helper::getPlaceholderLen($product['amount']." X ".number_format($product['price'],0),12));	
                    }else{
                        array_push($listData,Helper::getPlaceholderLen($product['product_name'],24).Helper::getPlaceholderLen($product['amount'].$product['product_unit'],12).Helper::getPlaceholderLen(number_format($product['price'],2) , 12));	
                    }
		}
		array_push($listData,str_pad('',48,'-'));
		array_push($listData,str_pad(yii::t('app','合计：').$order->reality_total , 24,' ').str_pad(yii::t('app','时间：').time(),24,' '));
                array_push($listData,str_pad(yii::t('app','服务员：').$order->username , 48,' '));
		//前面加 barcode
                $precode="1D6B450B".  strtoupper(implode('',unpack('H*', 'A'.$order->lid)))."0A".  strtoupper(implode('',unpack('H*', 'A'.$order->lid)))."0A";
                //后面加切纸
                $sufcode="0A0A0A0A0A0A1D5601";
		if($hasData){
                    return Helper::printConetent($printer,$listData,$precode,$sufcode);
		}else{
                    return array('status'=>false,'dpid'=>$printer->dpid,'jobid'=>"0",'type'=>'none','msg'=>yii::t('app','没有要打印的菜品'));
                }                
	}
        
        //收银台打印清单写入到redis
        //send by workerman encode by GBK or shift-JIS
	static public function printList(Order $order,$orderProducts , Pad $pad, $cprecode,$printserver,$memo,$cardtotal){
                $printer = Printer::model()->find('lid=:printerId and dpid=:dpid',  array(':printerId'=>$pad->printer_id,':dpid'=>$order->dpid));
		if(empty($printer)) {
                        return array('status'=>false,'dpid'=>$order->dpid,'jobid'=>"0",'type'=>'none','msg'=>yii::t('app','PAD还没有设置默认打印机'));		
		}
		$hasData=false;
		//$db = Yii::app()->db;
		$sql="select sum(t.number) as all_num from nb_order t where t.account_no =".$order->account_no;
		$Allnumber = Yii::app()->db->createCommand($sql)->queryRow();
		//$orderProducts = OrderProduct::getOrderProducts($order->lid,$order->dpid);
                ///site error because tempsite and reserve**************
                //$listData = array("22".Helper::getPlaceholderLenBoth($order->company->company_name, 16));//
                $listData = array("22".Helper::setPrinterTitle($order->company->company_name.$memo,8));
                array_push($listData,"00");
                array_push($listData,"br");
                array_push($listData,"br");
                
//                $listData = array("22".  Helper::setPrinterTitle($order->company->company_name,8));
//                if(!empty($memo))
//                {
//                    array_push($listData,"br");
//                    array_push($listData,"11".$memo);                    
//                }
//                array_push($listData,"00");
//                array_push($listData,"br");
//                $strSite="";
                if($order->is_temp==0)
                {
                    $site = Site::model()->find('lid=:lid and dpid=:dpid',  array(':lid'=>$order->site_id,':dpid'=>$order->dpid));
                    $siteType = SiteType::model()->find('lid=:lid and dpid=:dpid',  array(':lid'=>$site->type_id,':dpid'=>$order->dpid));
                    //$strSite=str_pad(yii::t('app','座号：').$siteType->name.' '.$site->serial , 24,' ').str_pad(yii::t('app','人数：').$order->number,12,' ');
                    array_push($listData,"10".yii::t('app','座  号：'));
                    array_push($listData,"11".$siteType->name.' '.$site->serial);
                    //array_push($listData,"00"."   ".yii::t('app','人数：').$order->number);
                }else{
                    //$strSite=str_pad(yii::t('app','座号：临时座').$order->site_id%10000 , 24,' ').str_pad(yii::t('app','人数：').$order->number,12,' ');
                    array_push($listData,"10".yii::t('app','座号：临时座'));
                    array_push($listData,"11".$order->site_id%10000);
                    //array_push($listData,"00"."   ".yii::t('app','人数：').$order->number);
                }
//                array_push($listData,"00"."   ".yii::t('app','人数：').$order->number);
//		if(!empty($order->callno))
//                {
//                    //$strSite=$strSite.str_pad(yii::t('app','呼叫号：').$order->callno,12,' ');
//                    //array_push($listData,$strcall);
//                    array_push($listData,"00"."   ".yii::t('app','呼叫号：'));
//                    array_push($listData,"11".$order->callno);
//                }
//		//$listKey = $order->dpid.'_'.$printer->ip_address;                	
//		array_push($listData,"br");
//		//array_push($listData,"00".$strSite);                
//		array_push($listData,"00".str_pad('',48,'-'));                
		
                array_push($listData,"br");
                array_push($listData,"10".yii::t('app','人  数：').$Allnumber['all_num']);
                array_push($listData,"br");
                array_push($listData,"10"."账单号：");
                array_push($listData,"00".$order->account_no);
                array_push($listData,"br");
                array_push($listData,"10"."下单时间：");
                array_push($listData,"00".$order->create_at);
                array_push($listData,"br");
                //return array('status'=>true,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试1");
                array_push($listData,"00".str_pad('',48,'-'));
                array_push($listData,"10".str_pad('品名',12,' ').str_pad('数量 ',6,' ').str_pad('单价/金额',5,' '));
                array_push($listData,"br");
                array_push($listData,"00".str_pad('',48,'-'));
                $productnum=0;
                $productmoneyall=0;
		foreach ($orderProducts as $product) {
                    //var_dump($product);exit;
                    $productnum++;
                    $productmoneyall=$productmoneyall+$product['price']*$product['amount'];
                    $isgiving="";
                    $isretreat="";                   
                    if($product['amount']<1)
                    {
                        continue;
                    }
                    if($product['is_giving']=='1')
                    {
                        $isgiving="(赠)";
                    }
                    if($product['is_retreat']=='1')
                    {
                        $isretreat="-";
                    }
                    $hasData=true;
//                    if(Yii::app()->language=='jp')
//                    {
//                        //array_push($listData,Helper::getPlaceholderLen($product['product_name'],36).Helper::getPlaceholderLen($product['amount']." X ".number_format($product['price'],0),12));	
//                        array_push($listData,"11".str_pad($product['amount']." X ".number_format($product['price'],0),10,' ')." ".Helper::setProductName($product['product_name'],12,6));
//                    }else{
                        //array_push($listData,Helper::getPlaceholderLen($product['product_name'],24).Helper::getPlaceholderLen($product['amount']." X ".$product['product_unit'],12).Helper::getPlaceholderLen(number_format($product['price'],2) , 12));	
                        //array_push($listData,"00".str_pad($product['amount']." X ".number_format($product['price'],2),13,' ')." ".Helper::setProductName($product['product_name'],24,16));
                        //array_push($listData,"11".str_pad($product['amount']." X ".number_format($product['price'],2),10,' ')." ".Helper::setProductName($product['product_name'],12,6));
                        if($product['product_type']=="0"){
                    		$productname=$product['product_name'].$isgiving;
                        }elseif($product['product_type']=="1"){
                        	$productname="餐位费";
                        }elseif($product['product_type']=="2"){
                        	$productname="送餐费";
                        }elseif($product['product_type']=="3"){
                        	$productname="打包费";
                        }else{
                        	$productname=$product['product_name'].$isgiving;
                        }
                        $printlen=(strlen($productname) + mb_strlen($productname,'UTF8')) / 2;
                        $charactorlen=  mb_strlen($productname,'UTF8');
                        if($printlen>22)
                        {
                            array_push($listData, "01".$productnum."."
                                    .mb_substr($productname,0,$charactorlen/2,'UTF8'));
                            array_push($listData,"br");
                            $lenstrleft=mb_substr($productname,$charactorlen/2,$charactorlen-($charactorlen/2),'UTF8');
                            $printlenstrleft=(strlen($lenstrleft) + mb_strlen($lenstrleft,'UTF8')) / 2;
                            //return array('status'=>false,'orderid'=>$order->lid, 'dpid'=>$printer->dpid,'jobid'=>"0",'type'=>'none','msg'=>$lenstrleft);
                            array_push($listData,
                                      "01"."  ".$lenstrleft
                                    .str_pad("",24-$printlenstrleft," ")
                                    .$isretreat.str_pad($product['amount'],6," ")//加2
                                    .number_format($product['price'],0)."/".number_format($product['price']*$product['amount'],2));	
                        }else{
                            array_push($listData,"01".$productnum."."
                                    .$productname
                                    .str_pad("",24-$printlen," ")
                                    .$isretreat.str_pad($product['amount'],6," ")//加2
                                    .number_format($product['price'],0)."/".number_format($product['price']*$product['amount'],2));	
                        }                
//                    }
                    array_push($listData,"br");
		}
		array_push($listData,"00".str_pad('',48,'-'));
                if(Yii::app()->language=='jp')
                {
                    //array_push($listData,str_pad(yii::t('app','应付：').number_format($order->should_total,0) , 26,' ').str_pad(date('Y-m-d H:i:s',time()),20,' '));
                    //array_push($listData,str_pad(yii::t('app','订餐电话：').$order->company->telephone,44,' '));
                    array_push($listData,"10".yii::t('app','应付').str_pad('',12,' ').number_format($order->should_total,0)//加2
                        .yii::t('app','实付：').number_format($order->reality_total,0));                    
                }else{
                    //array_push($listData,str_pad(yii::t('app','应付：').$order->should_total , 40,' '));
                    //array_push($listData,str_pad(yii::t('app','操作员：').Yii::app()->user->name,24,' ')
                    //        .str_pad(yii::t('app','时间：').date('Y-m-d H:i:s',time()),24,' '));
                    //array_push($listData,str_pad(yii::t('app','订餐电话：').$order->company->telephone,44,' ')); 
                    if($order->should_total>0)
                    {
                        array_push($listData,"10".yii::t('app','原价').str_pad('',12,' ').number_format($order->should_total,2));//加2
                        array_push($listData,"br");
                    }
                    //单品菜折扣优惠部分
                    $promotionarr=OrderProduct::getPromotion($order->account_no,$order->dpid);
                    foreach ($promotionarr as $dt)
                    {
                        $printlen=(strlen($dt["promotion_title"]) + mb_strlen($dt["promotion_title"],'UTF8')) / 2;
                        array_push($listData,"10".$dt["promotion_title"].str_pad("",16-$printlen," ")."-".number_format($dt["subprice"],2));//加2
                        array_push($listData,"br"); 
                    }
                    //整单折扣优惠部分
                    if(!empty($order->notpaydetail))
                    {
                        $notpayarr=explode("|",$order->notpaydetail);
                        if($notpayarr[2]>0)
                        {
                            //取折扣名称
                            $discountname=Yii::app()->db->createCommand("select discount_name from nb_discount where dpid=".$order->dpid." and lid=".$notpayarr[0])->queryScalar();
                            $tempprintname=$discountname."(".($notpayarr[1]*10)."折)";
                            $printlen=(strlen($tempprintname) + mb_strlen($tempprintname,'UTF8')) / 2;
                            array_push($listData,"10".$tempprintname.str_pad("",16-$printlen," ")."-".number_format($notpayarr[2],2));//加2
                            array_push($listData,"br"); 
                        }
                        if($notpayarr[3]>0)
                        {
                            array_push($listData,"10"."后台手动减价".str_pad("",4," ")."-".number_format($notpayarr[3],2));
                            array_push($listData,"br"); 
                        }
                        if($notpayarr[4]>0)
                        {
                            array_push($listData,"10"."抹零".str_pad("",12," ")."-".number_format($notpayarr[4],2));//加2
                            array_push($listData,"br"); 
                        }
                    }
                    if($order->pay_total>0)
                    {
                        array_push($listData,"10".yii::t('app','已付').str_pad("",12," ")."-".number_format($order->pay_total,2));//加2
                        array_push($listData,"br");
                    }
//                    if($order->reality_total>0)
//                    {
                        array_push($listData,"10".yii::t('app','应付').str_pad("",12," ").number_format($order->reality_total,2));//加2
                        array_push($listData,"br");
//                    }                    
                    array_push($listData,"br");
                    //echo $sqlorderproductpromotion;exit;
                    
                    array_push($listData,"br");
                    //支付方式
//                     if($order->account_cash>0)
//                     {
//                         array_push($listData,"10".yii::t('app','现金支付').str_pad("",8," ").number_format($order->account_cash,2));//加2
//                         array_push($listData,"br");                        
//                     }
                    
                    //支付方式CF
                    if($order->paycashaccountori>0)
                    {
                    	array_push($listData,"10".yii::t('app','现金支付').str_pad("",8," ").number_format($order->paycashaccountori,2));//加2
                    	array_push($listData,"br");
                    	array_push($listData,"10".yii::t('app','找零').str_pad("",12," ").number_format($order->paychangeaccount,2));//加2
                    	array_push($listData,"br");
                    }
                    
                    if($order->account_union>0)
                    {
                        array_push($listData,"10".yii::t('app','银联支付').str_pad("",8," ").number_format($order->account_union,2));//加2
                        array_push($listData,"br");                        
                    }
                    if(!empty($order->account_otherdetail))
                    {
                        $otherpaykv=array();
                        $otherpayrow=Yii::app()->db->createCommand("select lid,name from nb_payment_method where dpid=".$order->dpid)->queryAll();
                        foreach ($otherpayrow as $kv)
                        {
                            $otherpaykv[$kv["lid"]]=$kv["name"];
                        }
                        $otherpayarr=explode("|",$order->account_otherdetail);
                        foreach ($otherpayarr as $pd)
                        {
                            $pdarr=explode(",",$pd);
                            if($pdarr[1]>0)
                            {
                                $printlen=(strlen($otherpaykv[$pdarr[0]]) + mb_strlen($otherpaykv[$pdarr[0]],'UTF8')) / 2;
                                array_push($listData,"10".$otherpaykv[$pdarr[0]].str_pad("",16-$printlen," ").number_format($pdarr[1],2));//加2
                                array_push($listData,"br");  
                            }
                        }
                    }
                }
                if(!empty($order->account_membercard))
                    {
                        $membercardarr=explode("|",$order->account_membercard);
                        if($membercardarr[1]>0)
                        {
                            array_push($listData,"10".yii::t('app','会员卡支付').str_pad("",6," ").number_format($membercardarr[1],2));//加2
                            array_push($listData,"br");
                            array_push($listData,"10".yii::t('app','会员卡号').str_pad("",8," ").$membercardarr[0]);//加2
                            array_push($listData,"br");
                            array_push($listData,"10".yii::t('app','会员卡余额').str_pad("",6," ").number_format($membercardarr[2],2));//加2
                            array_push($listData,"br");
                        }
                    }
                array_push($listData,"br");
                if(!empty($order->username))
                {
                    array_push($listData,"10"."点单员：".$order->username);//."  "
                }else{
                    array_push($listData,"10"."客人自助下单");//."  "
                }
                array_push($listData,"br");
                array_push($listData,"10"."点单时间：");
                array_push($listData,"00".date('Y-m-d H:i:s',time()));
                array_push($listData,"br");
                array_push($listData,"10"."订餐电话：");
                array_push($listData,"00".$order->company->telephone);
//                array_push($listData,"00".$order->username);
//                array_push($listData,"00"."   ".date('Y-m-d H:i:s',time()));
//                array_push($listData,"br");
//                array_push($listData,"00".yii::t('app','订餐电话：').$order->company->telephone);
                $precode=$cprecode;
                //后面加切纸
                $sufcode="0A0A0A0A0A0A1D5601";                        
                //var_dump($listData);exit;
                $printret=array();
		if($hasData){
                    //$printserver='0';
                    $retcontent= Helper::printConetent($printer,$listData,$precode,$sufcode,$printserver,$order->lid);
                    $retcontent['orderid']=$order->lid;
                    return $retcontent;
		}else{
                    return array('status'=>false,'orderid'=>$order->lid, 'dpid'=>$printer->dpid,'jobid'=>"0",'type'=>'none','msg'=>yii::t('app','没有要打印的菜品！'));
                }
                /*$listData.= str_pad('',48,'-').'<br>';
		$listData.= str_pad('消费合计：'.$order->reality_total , 20,' ').'<br>';
		$listData.= str_pad('收银员：'.Yii::app()->user->name,20,' ').'<br>';
		$listData.= str_pad('应收金额：'.$order->reality_total,48,' ').'<br>';
		$listData.= str_pad('',48,'-').str_pad('打印时间：'.time(),20,' ').'<br>'
						.str_pad('订餐电话：'.$order->company->telephone,20,' ').'<br>';
		//echo Yii::app()->end(json_encode(array('status'=>true,'msg'=>$listData)));exit;
		if(!empty($listData)){
                    if(Yii::app()->params->has_cache){
                        $list = new ARedisList($listKey);
			if($reprint) {
				$listData = str_pad('丢单重打', 40 , ' ',STR_PAD_BOTH).'<br>'.$listData;
				$list->add($listData);
			} else {
				$list->unshift($listData);
			}
                    }
		}
		//echo Yii::app()->end(json_encode(array('status'=>true,'msg'=>$listData)));exit;
		//$channel = new ARedisChannel($order->dpid.'_PD');
		//$channel->publish($listKey);
		if((Yii::app()->request->isAjaxRequest)) {
			echo Yii::app()->end(json_encode(array('status'=>true,'msg'=>'')));
		} else {
			return array('status'=>true,'msg'=>'');
		}*/
	}
        
        //收银台打印清单写入到redis
        //send by workerman encode by GBK or shift-JIS
	static public function printPauseList(Order $order,$orderProducts, Pad $pad, $cprecode,$printserver,$memo,$cardtotal){
                $printer = Printer::model()->find('lid=:printerId and dpid=:dpid',  array(':printerId'=>$pad->printer_id,':dpid'=>$order->dpid));
		if(empty($printer)) {
                        return array('status'=>false,'dpid'=>$order->dpid,'jobid'=>"0",'type'=>'none','msg'=>yii::t('app','PAD还没有设置默认打印机'));		
		}
		$hasData=false;
		//$orderProducts = OrderProduct::getOrderProducts($order->lid,$order->dpid);
                ///site error because tempsite and reserve**************
                //$listData = array("22".Helper::getPlaceholderLenBoth($order->company->company_name, 16));//
                $listData = array("22".  Helper::setPrinterTitle($order->company->company_name,8));
                if(!empty($memo))
                {
                    array_push($listData,"br");
                    array_push($listData,"11".$memo);                    
                }
                array_push($listData,"00");
                array_push($listData,"br");
                $strSite="";
                if($order->is_temp==0)
                {
                    $site = Site::model()->find('lid=:lid and dpid=:dpid',  array(':lid'=>$order->site_id,':dpid'=>$order->dpid));
                    $siteType = SiteType::model()->find('lid=:lid and dpid=:dpid',  array(':lid'=>$site->type_id,':dpid'=>$order->dpid));
                    //$strSite=str_pad(yii::t('app','座号：').$siteType->name.' '.$site->serial , 24,' ').str_pad(yii::t('app','人数：').$order->number,12,' ');
                    array_push($listData,"00".yii::t('app','座号：'));
                    array_push($listData,"11".$siteType->name.' '.$site->serial);
                    //array_push($listData,"00"."   ".yii::t('app','人数：').$order->number);
                }else{
                    //$strSite=str_pad(yii::t('app','座号：临时座').$order->site_id%10000 , 24,' ').str_pad(yii::t('app','人数：').$order->number,12,' ');
                    array_push($listData,"00".yii::t('app','座号：临时座'));
                    array_push($listData,"11".$order->site_id%10000);
                    //array_push($listData,"00"."   ".yii::t('app','人数：').$order->number);
                }
                array_push($listData,"00"."   ".yii::t('app','人数：').$order->number);
		if(!empty($order->callno))
                {
                    //$strSite=$strSite.str_pad(yii::t('app','呼叫号：').$order->callno,12,' ');
                    //array_push($listData,$strcall);
                    array_push($listData,"00"."   ".yii::t('app','呼叫号：'));
                    array_push($listData,"11".$order->callno);
                }
		//$listKey = $order->dpid.'_'.$printer->ip_address;                	
		array_push($listData,"br");
		//array_push($listData,"00".$strSite);                
		array_push($listData,"00".str_pad('',48,'-'));                
		
		foreach ($orderProducts as $product) {
                    //var_dump($product);exit;
                    if($product['amount']<1)
                    {
                        continue;
                    }
                    $hasData=true;
                    if(Yii::app()->language=='jp')
                    {
                        //array_push($listData,Helper::getPlaceholderLen($product['product_name'],36).Helper::getPlaceholderLen($product['amount']." X ".number_format($product['price'],0),12));	
                        array_push($listData,"11".str_pad($product['amount']." X ".number_format($product['price'],0),10,' ')." ".Helper::setProductName($product['product_name'],12,6));
                    }else{
                        //array_push($listData,Helper::getPlaceholderLen($product['product_name'],24).Helper::getPlaceholderLen($product['amount']." X ".$product['product_unit'],12).Helper::getPlaceholderLen(number_format($product['price'],2) , 12));	
                        //array_push($listData,"00".str_pad($product['amount']." X ".number_format($product['price'],2),13,' ')." ".Helper::setProductName($product['product_name'],24,16));
                        array_push($listData,"11".str_pad($product['amount']." X ".number_format($product['price'],2),10,' ')." ".Helper::setProductName($product['product_name'],12,6));
                    }
                    array_push($listData,"br");
		}
		array_push($listData,"00".str_pad('',48,'-'));
                //var_dump($listData);exit;
                if(Yii::app()->language=='jp')
                {
                    array_push($listData,"11".yii::t('app','原价：').number_format($order->should_total,0)
                        .yii::t('app','现价：').number_format($order->reality_total,0));                    
                }else{
                    if($order->should_total>0)
                    {
                        array_push($listData,"11".yii::t('app','原价：').number_format($order->should_total,2));
                        array_push($listData,"br");
                    }
                    if($order->reality_total>0)
                    {
                        array_push($listData,"11".yii::t('app','现价：').number_format($order->reality_total,2));
                    }                    
                }
                
                array_push($listData,"br");
                array_push($listData,"00".$order->username);
                array_push($listData,"00"."   ".date('Y-m-d H:i:s',time()));
                array_push($listData,"br");
                array_push($listData,"00".yii::t('app','订餐电话：').$order->company->telephone);
                $precode=$cprecode;
                //后面加切纸
                $sufcode="0A0A0A0A0A0A1D5601";                        
                //var_dump($listData);exit;
                $printret=array();
		if($hasData){
                    //$printserver='0';
                    $retcontent= Helper::printPauseConetent($printer,$listData,$precode,$sufcode,$printserver,$order->lid);
                    $retcontent['orderid']=$order->lid;
                    return $retcontent;
		}else{
                    return array('status'=>false,'orderid'=>$order->lid, 'dpid'=>$printer->dpid,'jobid'=>"0",'type'=>'none','msg'=>yii::t('app','没有要打印的菜品！'));
                }                
	}
        
        static public function printPayList(Order $order,$orderProducts, Pad $pad, $cprecode,$printserver,$memo,$cardtotal){
                $printer = Printer::model()->find('lid=:printerId and dpid=:dpid',  array(':printerId'=>$pad->printer_id,':dpid'=>$order->dpid));
		if(empty($printer)) {
                        return array('status'=>false,'dpid'=>$order->dpid,'jobid'=>"0",'type'=>'none','msg'=>yii::t('app','PAD还没有设置默认打印机'));		
		}
		$hasData=false;
		//$orderProducts = OrderProduct::getOrderProducts($order->lid,$order->dpid);
                ///site error because tempsite and reserve**************
                //$listData = array("22".Helper::getPlaceholderLenBoth($order->company->company_name, 16));//
//                $listData = array("22".  Helper::setPrinterTitle($order->company->company_name,8));
//                if(!empty($memo))
//                {
//                    array_push($listData,"br");
//                    array_push($listData,"11".$memo);                    
//                }
//                array_push($listData,"00");
//                array_push($listData,"br");
                $listData = array("22".Helper::setPrinterTitle($order->company->company_name,8));
                array_push($listData,"br");
                array_push($listData,"br");
                //array_push($listData,"22"."+++总单+++"); 
                //array_push($listData,"22"."<".$printerway->name.">");
                array_push($listData,"10".Helper::setPrinterTitle($memo,12));
                array_push($listData,"00");
                array_push($listData,"br");
                array_push($listData,"br");
                $strSite="";
                if($order->is_temp==0)
                {
                    $site = Site::model()->find('lid=:lid and dpid=:dpid',  array(':lid'=>$order->site_id,':dpid'=>$order->dpid));
                    $siteType = SiteType::model()->find('lid=:lid and dpid=:dpid',  array(':lid'=>$site->type_id,':dpid'=>$order->dpid));
                    //$strSite=str_pad(yii::t('app','座号：').$siteType->name.' '.$site->serial , 24,' ').str_pad(yii::t('app','人数：').$order->number,12,' ');
                    array_push($listData,"10".yii::t('app','座号：'));
                    array_push($listData,"11".$siteType->name.' '.$site->serial);
                    //array_push($listData,"00"."   ".yii::t('app','人数：').$order->number);
                }else{
                    //$strSite=str_pad(yii::t('app','座号：临时座').$order->site_id%10000 , 24,' ').str_pad(yii::t('app','人数：').$order->number,12,' ');
                	
                		if($order->order_type=="2"){
                			array_push($listData,"10".yii::t('app','微信外卖：'));
                			array_push($listData,"11".$order->site_id%1000);
                		}elseif($order->order_type=="3"){
                			array_push($listData,"10".yii::t('app','预约自提：'));
                			array_push($listData,"11".$order->site_id%1000);
                		}else{
                			array_push($listData,"10".yii::t('app','临时座：'));
                			array_push($listData,"11".$order->site_id%1000);
                		}
//                 	array_push($listData,"10".yii::t('app','座号：临时座'));
//                     array_push($listData,"11".$order->site_id%1000);
                    //array_push($listData,"00"."   ".yii::t('app','人数：').$order->number);
                }
                array_push($listData,"br");
                array_push($listData,"10".yii::t('app','人数：').$order->number);
                array_push($listData,"br");
                array_push($listData,"10"."下单时间：");
                array_push($listData,"00".$order->create_at);
                array_push($listData,"br");
                array_push($listData,"10"."账单号：");
                array_push($listData,"00".$order->account_no);
                array_push($listData,"br");
                if($order->order_type=="2"){
                	$orderAddress = OrderAddress::model()->find('order_lid=:lid and dpid=:dpid',  array(':lid'=>$order->lid,':dpid'=>$order->dpid));
                	array_push($listData,"10".yii::t('app','姓名：'));
                	array_push($listData,"11".$orderAddress->consignee);
                	array_push($listData,"br");
                	array_push($listData,"10".yii::t('app','电话：'));
                	array_push($listData,"11".$orderAddress->mobile);
                	array_push($listData,"br");
                	array_push($listData,"10".yii::t('app','地址：'));
                	array_push($listData,"11".$orderAddress->province."".$orderAddress->city."".$orderAddress->area."-".$orderAddress->street);
                }
                if($order->order_type=="3"){
                	$orderAddress = OrderAddress::model()->find('order_lid=:lid and dpid=:dpid',  array(':lid'=>$order->lid,':dpid'=>$order->dpid));
                	array_push($listData,"10".yii::t('app','姓名：'));
                	array_push($listData,"11".$orderAddress->consignee);
                	array_push($listData,"br");
                	array_push($listData,"10".yii::t('app','电话：'));
                	array_push($listData,"11".$orderAddress->mobile);
                	array_push($listData,"br");
                	array_push($listData,"10".yii::t('app','预约时间：'));
                	array_push($listData,"br");
                	array_push($listData,"11".$order->appointment_time);
                }
//		if(!empty($order->callno))
//                {
//                    //$strSite=$strSite.str_pad(yii::t('app','呼叫号：').$order->callno,12,' ');
//                    //array_push($listData,$strcall);
//                    array_push($listData,"00"."   ".yii::t('app','呼叫号：'));
//                    array_push($listData,"11".$order->callno);
//                }
		//$listKey = $order->dpid.'_'.$printer->ip_address;                	
		array_push($listData,"br");
		//array_push($listData,"00".$strSite);                
		array_push($listData,"00".str_pad('',48,'-'));                
		array_push($listData,"10".str_pad('品名',12,' ').str_pad('数量 ',6,' ').str_pad('单价/金额',5,' '));
                array_push($listData,"br");
                array_push($listData,"00".str_pad('',48,'-'));
                $productnum=0;
                $productmoneyall=0;
		foreach ($orderProducts as $product) {
                    //var_dump($product);exit;
                    $productnum++;
                    $productmoneyall=$productmoneyall+$product['price']*$product['amount'];
                    $isgiving="";
                    $isretreat="";                   
                    if($product['amount']<1)
                    {
                        continue;
                    }
                    if($product['is_giving']=='1')
                    {
                        $isgiving="(赠)";
                    }
                    if($product['is_retreat']=='1')
                    {
                        $isretreat="-";
                    }
                    $hasData=true;
//                    if(Yii::app()->language=='jp')
//                    {
//                        //array_push($listData,Helper::getPlaceholderLen($product['product_name'],36).Helper::getPlaceholderLen($product['amount']." X ".number_format($product['price'],0),12));	
//                        array_push($listData,"11".str_pad($product['amount']." X ".number_format($product['price'],0),10,' ')." ".Helper::setProductName($product['product_name'],12,6));
//                    }else{
                        //array_push($listData,Helper::getPlaceholderLen($product['product_name'],24).Helper::getPlaceholderLen($product['amount']." X ".$product['product_unit'],12).Helper::getPlaceholderLen(number_format($product['price'],2) , 12));	
                        //array_push($listData,"00".str_pad($product['amount']." X ".number_format($product['price'],2),13,' ')." ".Helper::setProductName($product['product_name'],24,16));
//                        array_push($listData,"11".str_pad($product['amount']." X ".number_format($product['price'],2),10,' ')." ".Helper::setProductName($product['product_name'],12,6));
		 				if($product['product_type']=="0"){
                    		$productname=$product['product_name'].$isgiving;
                        }elseif($product['product_type']=="1"){
                        	$productname="餐位费";
                        }elseif($product['product_type']=="2"){
                        	$productname="送餐费";
                        }elseif($product['product_type']=="3"){
                        	$productname="打包费";
                        }else{
                        	$productname=$product['product_name_p'].$isgiving;
                        }//CF
                    	
                        $printlen=(strlen($productname) + mb_strlen($productname,'UTF8')) / 2;
                        $charactorlen=  mb_strlen($productname,'UTF8');
                        if($printlen>22)
                        {
                            array_push($listData, "01".$productnum."."
                                    .mb_substr($productname,0,$charactorlen/2,'UTF8'));
                            array_push($listData,"br");
                            $lenstrleft=mb_substr($productname,$charactorlen/2,$charactorlen-($charactorlen/2),'UTF8');
                            $printlenstrleft=(strlen($lenstrleft) + mb_strlen($lenstrleft,'UTF8')) / 2;
                            //return array('status'=>false,'orderid'=>$order->lid, 'dpid'=>$printer->dpid,'jobid'=>"0",'type'=>'none','msg'=>$lenstrleft);
                            array_push($listData,
                                      "01"."  ".$lenstrleft
                                    .str_pad("",24-$printlenstrleft," ")
                                    .$isretreat.str_pad($product['amount'],4," ")
                                    .number_format($product['original_price'],0)."/".number_format($product['price']*$product['amount'],2));	
                        }else{
                            array_push($listData,"01".$productnum."."
                                    .$productname
                                    .str_pad("",24-$printlen," ")
                                    .$isretreat.str_pad($product['amount'],4," ")
                                    .number_format($product['original_price'],0)."/".number_format($product['price']*$product['amount'],2));	
                        }
//                    }
                    array_push($listData,"br");
		}
		array_push($listData,"00".str_pad('',48,'-'));
                //var_dump($listData);exit;
                if(Yii::app()->language=='jp')
                {
                    array_push($listData,"10".yii::t('app','原价').str_pad("",10," ").number_format($order->should_total,0)
                        .yii::t('app','现价：').number_format($order->reality_total,0));                    
                }else{
                    if($order->should_total>0)
                    {
                        array_push($listData,"10".yii::t('app','原价').str_pad("",10," ").number_format($order->should_total,2));
                        array_push($listData,"br");
                    }
                    //单品菜折扣优惠部分
                    //$promotionarr=OrderProduct::getPromotion($order->account_no,$order->dpid);
                    $promotionarr=OrderProduct::getPromotionByOrderId($order->lid,$order->dpid);
                    foreach ($promotionarr as $dt)
                    {
                        $printlen=(strlen($dt["promotion_title"]) + mb_strlen($dt["promotion_title"],'UTF8')) / 2;
                        array_push($listData,"10".$dt["promotion_title"].str_pad("",14-$printlen," ")."-".number_format($dt["subprice"],2));
                        array_push($listData,"br"); 
                    }
                    if($order->reality_total>0)
                    {
                        array_push($listData,"10".yii::t('app','现价').str_pad("",10," ").number_format($order->reality_total,2));
                    }
                    
                    $modeloderpay=OrderPay::model()->findAll( "dpid=:dpid and order_id=:orderid",array(":dpid"=>$order->dpid,":orderid"=>$order->lid));
                    if(!empty($modeloderpay))
                    {
                        array_push($listData,"br"); 
                        array_push($listData,"00".str_pad('',48,'-'));
                        foreach ($modeloderpay as $op)
                        {
                            $payname="";
                            switch ($op->paytype)
                            {
                                case 1:
                                    $payname="微信支付";
                                    break;
                                case 2:
                                    $payname="支付宝支付";
                                    break;
                                case 9:
                                    $payname="微信代金券";
                                    break;
                                case 10:
                                    $payname="微信会员余额";
                                    break;
                            }
                            $printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
                            array_push($listData,"10".$payname.str_pad("",14-$printlen," ").number_format($op->pay_amount,2));
                            array_push($listData,"br"); 
                        }
                    }
                }                
                array_push($listData,"br");
                if(!empty($order->username))
                {
                    array_push($listData,"10"."点单员：".$order->username);//."  "
                }else{
                    array_push($listData,"10"."客人自助下单");//."  "
                }
                array_push($listData,"br");
                array_push($listData,"10"."点单时间：");
                array_push($listData,"00".date('Y-m-d H:i:s',time()));
                array_push($listData,"br");
                array_push($listData,"10"."订餐电话：");
                array_push($listData,"00".$order->company->telephone);
                $precode=$cprecode;
                //后面加切纸
                $sufcode="0A0A0A0A0A0A1D5601";                        
                //var_dump($listData);exit;
                $printret=array();
		if($hasData){
                    //$printserver='0';
                    //付款单打印二张，一张留存，一张给客户
                    $retcontent= Helper::printPayConetent($printer,$listData,$precode,$sufcode,$printserver,$order->lid);
                    //$retcontent= Helper::printPauseConetent($printer,$listData,$precode,$sufcode,$printserver,$order->lid);
                    $retcontent['orderid']=$order->lid;
                    return $retcontent;
		}else{
                    return array('status'=>false,'orderid'=>$order->lid, 'dpid'=>$printer->dpid,'jobid'=>"0",'type'=>'none','msg'=>yii::t('app','没有要打印的菜品！'));
                }                
	}
        
        //开台时的打印
        //打印开台号和人数，以后有WiFi的密码等。
	static public function printCloseAccount($dpid, $retreatdetails, $tableareas,$allmoney, $orderdetails, $products, $rll, $payments, $models ,$incomes,  $begin_time, $end_time, $modeldata, $money, $moneydata, $recharge,Pad $pad, $cprecode,$printserver){
		               //return array('status'=>false,'msg'=>"123");//添加$money
		               //var_dump($money);exit;
                $printer = Printer::model()->find('lid=:printerId and dpid=:dpid',  array(':printerId'=>$pad->printer_id,':dpid'=>$dpid));
		if(empty($printer)) {
                        return array('status'=>0,'dpid'=>$siteno->dpid,'jobid'=>"0",'type'=>'none','msg'=>yii::t('app','PAD还没有设置默认打印机'));		
		}
		
		if(count($models)==0){
			$sumall=0;
			$memo="日结对账单";
			//return array('status'=>false,'msg'=>"123");
			$listData = array("22".  Helper::setPrinterTitle(Company::getCompanyName($dpid)." ".$memo,8));
			//                if(!empty($memo))
				//                {
				//                    array_push($listData,"br");
				//                    array_push($listData,"10".$memo);
				//                }
			array_push($listData,"00");
			array_push($listData,"br");
			array_push($listData,"00".str_pad('',48,'-'));
			array_push($listData,"00".yii::t('app','没有日结数据！！！'));
			array_push($listData,"br");
			array_push($listData,"00".str_pad('',48,'-'));
			array_push($listData,"00".Yii::app()->user->name."    ".date('Y-m-d H:i:s',time()));
			array_push($listData,"br");
			//array_push($listData,"00"."   ".yii::t('app','订餐电话：').$order->company->telephone);return array('status'=>false,'msg'=>"123");
			
			$precode=$cprecode;
			//后面加切纸
			$sufcode="0A0A0A0A0A0A";
		}else{
                $sumall=0;
                $memo="日结对账单";
                //return array('status'=>false,'msg'=>"123");
                $listData = array("22".  Helper::setPrinterTitle(Company::getCompanyName($dpid)." ".$memo,8));
//                if(!empty($memo))
//                {
//                    array_push($listData,"br");
//                    array_push($listData,"10".$memo);                    
//                }
                array_push($listData,"00");
                array_push($listData,"br");
                array_push($listData,"00".str_pad('',48,'-'));
                foreach ($models as $model)
                {
                    $payname="";
                    switch ($model->paytype)
                    {
                        case 0:
                            $payname="现金支付";
                            break;
                        case 1:
                            $payname="微信支付";
                            break;
                        case 2:
                            $payname="支付宝";
                            break;
                        case 3:
                            if ($model->payment_method_id){$payname = $model->paymentMethod->name;}else $payname="其他代金券";
                            break;
                        case 4:
                            $payname="会员卡支付";
                            break;
                        case 5:
                            $payname="银联卡支付";
                            break;
                        case 9:
                            $payname="微信代金券";
                            break;
                        case 10:
                            $payname="微信会员余额支付";
                            break;                        
                    }
                    $printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;    
                    array_push($listData,"01".$payname.str_pad("", 25-$printlen," ").$model->should_all);
                    array_push($listData,"br");
                    $sumall=$sumall+$model->should_all;
                }
                	//$payname="充值金额";
                	
               // foreach ($money as $moneys){
               // 	$payname="";
                	if(!empty($money)){
	                	$payname = "传统卡充值/赠送:";//}
                                $printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
	                	array_push($listData,"01".$payname.str_pad("", 25-$printlen," ").$money['all_money']."/".$money['all_give']);
	                	array_push($listData,"br");
	                	$sumall=$sumall+$money['all_money'];
                	}
                        if(!empty($recharge)){
	                	$payname = "微信充值/赠送:";//}
                                $printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
	                	array_push($listData,"01".$payname.str_pad("", 25-$printlen," ").$recharge['all_recharge']."/".$recharge['all_cashback']);
	                	array_push($listData,"br");
	                	$sumall=$sumall+$recharge['all_recharge'];
                	}
               // }//添加
				array_push($listData,"00".str_pad('',48,'-')); 
                array_push($listData,"10".str_pad("合计：",7).$sumall);
                array_push($listData,"br");
                array_push($listData,"00".str_pad('',48,'-'));   
				array_push($listData,"00".Yii::app()->user->name."    ".date('Y-m-d H:i:s',time())); 
				array_push($listData,"br");
                //array_push($listData,"00"."   ".yii::t('app','订餐电话：').$order->company->telephone);return array('status'=>false,'msg'=>"123");
                
                $precode=$cprecode;
                //后面加切纸
                $sufcode="0A0A0A0A0A0A"; 
                
		}
		
		//return array('status'=>false,'msg'=>$rll);
		if(in_array('businessdata',$rll)){
			//return array('status'=>false,'msg'=>"456");
		//营业数据报表
                array_push($listData,"00");
                array_push($listData,"br");
                array_push($listData,"00");
                array_push($listData,"br");
                array_push($listData,"00");
                array_push($listData,"br");
                //return array('status'=>false,'msg'=>"123");
				//添加
                //$sumall=0;
                if(!empty($modeldata)&&!empty($moneydata)){
                	//return array('status'=>false,'msg'=>"123");
                $memo="营业数据报表";
                //var_dump($modeldata);exit;
                array_push($listData,"22".  Helper::setPrinterTitle(Company::getCompanyName($dpid)." ".$memo,8));//return array('status'=>false,'msg'=>"123");
                //                if(!empty($memo))
                	//                {
                	//                    array_push($listData,"br");
                	//                    array_push($listData,"10".$memo);
                	//                }
                array_push($listData,"00");
                array_push($listData,"br");
                array_push($listData,"00".str_pad('',48,'-'));
                $payname="查询时间段：";
                $printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
                array_push($listData,"01".$payname.str_pad("", 15-$printlen," ").$begin_time." 至 ".$end_time);
                array_push($listData,"br");
                array_push($listData,"00".str_pad('',48,'-'));
                $payname="客流";
                $printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;//return array('status'=>false,'msg'=>$modeldata['all_number']);
                array_push($listData,"01".$payname.str_pad("", 25-$printlen," ").$modeldata['all_number']);
                array_push($listData,"br");
                $payname="单数";
                //return array('status'=>false,'msg'=>"123");
                $printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
                array_push($listData,"01".$payname.str_pad("", 25-$printlen," ").$modeldata['all_account']);
                array_push($listData,"br");
                $payname="销售额";
                $printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
                array_push($listData,"01".$payname.str_pad("", 25-$printlen," ").sprintf("%.2f",$moneydata['all_originalprice']));
                array_push($listData,"br");
                $payname="实收";
                $printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
                array_push($listData,"01".$payname.str_pad("", 25-$printlen," ").sprintf("%.2f",$modeldata['all_realprice']));
                array_push($listData,"br");
                $payname="优惠";
                $printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
                array_push($listData,"01".$payname.str_pad("", 25-$printlen," ").sprintf("%.2f",$moneydata['all_originalprice']-$modeldata['all_realprice']));
                array_push($listData,"br");
                $payname="人均";
                $printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
                array_push($listData,"01".$payname.str_pad("", 25-$printlen," ").sprintf("%.2f",$modeldata['all_realprice']/$modeldata['all_number']));
                array_push($listData,"br");
                $payname="单均";
                $printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
                array_push($listData,"01".$payname.str_pad("", 25-$printlen," ").sprintf("%.2f",$modeldata['all_realprice']/$modeldata['all_account']));
                array_push($listData,"br");
                array_push($listData,"00".str_pad('',48,'-'));
                array_push($listData,"00".Yii::app()->user->name."    ".date('Y-m-d H:i:s',time()));
                array_push($listData,"br");
                $precode=$cprecode;
                //后面加切纸
                $sufcode="0A0A0A0A0A0A";
                }
		}     
           if(in_array('income',$rll)){     
                //营业收入（产品类型）
                array_push($listData,"00");
                array_push($listData,"br");
                array_push($listData,"00");
                array_push($listData,"br");
                array_push($listData,"00");
                array_push($listData,"br");
                $memo="营业收入（产品类型）";
                //return array('status'=>false,'msg'=>"123");
                array_push($listData,"22".  Helper::setPrinterTitle(Company::getCompanyName($dpid)." ".$memo,8));//return array('status'=>false,'msg'=>"123");
                //                if(!empty($memo))
                	//                {
                	//                    array_push($listData,"br");
                	//                    array_push($listData,"10".$memo);
                	//                }
                array_push($listData,"00");
                array_push($listData,"br");
                array_push($listData,"00".str_pad('',48,'-'));
                $payname="查询时间段：";
                $printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
                array_push($listData,"00".$payname.str_pad("", 15-$printlen," ").$begin_time." 至 ".$end_time);
                array_push($listData,"br");
                array_push($listData,"00".str_pad('',48,'-'));
                $payname="产品类型";
                $nummoney="数量/金额";
                $printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
                array_push($listData,"00".$payname.str_pad("", 25-$printlen," ").$nummoney);
                array_push($listData,"br");
                foreach ($incomes as $model)
                {
                	$payname=$model['category_name'];
                	$printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
                	array_push($listData,"00".$payname.str_pad("", 25-$printlen," ").$model['all_num']."/".sprintf("%.2f",$model['all_price']));
                	array_push($listData,"br");
//                 	$payname="数量/金额";
//                 	$printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
//                 	array_push($listData,"00".$payname.str_pad("", 20-$printlen," ").$model['all_num']."/".sprintf("%.2f",$model['all_price']));
//                 	array_push($listData,"br");
                	
                }
                array_push($listData,"00".str_pad('',48,'-'));
                array_push($listData,"00".Yii::app()->user->name."    ".date('Y-m-d H:i:s',time()));
                array_push($listData,"br");
           }  
           if(in_array('payall',$rll)){
                //收款统计（支付方式）
                array_push($listData,"00");
                array_push($listData,"br");
                array_push($listData,"00");
                array_push($listData,"br");
                array_push($listData,"00");
                array_push($listData,"br");
                
                $sumall=0;
                $memo="收款统计（支付方式）";
                //return array('status'=>false,'msg'=>"123");
                array_push($listData,"22".  Helper::setPrinterTitle(Company::getCompanyName($dpid)." ".$memo,8));//return array('status'=>false,'msg'=>"123");
               
                array_push($listData,"00");
                array_push($listData,"br");
                array_push($listData,"00".str_pad('',48,'-'));
                $payname="支付方式";
                $printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
                array_push($listData,"01".$payname.str_pad("", 25-$printlen," ")."单数/金额");
                array_push($listData,"br");
                array_push($listData,"00".str_pad('',48,'-'));
                foreach ($payments as $payment)
                {
                	$payname="";
                	switch ($payment->paytype)
                	{
                		case 0:
                			$payname="现金支付";
                			break;
                		case 1:
                			$payname="微信支付";
                			break;
                		case 2:
                			$payname="支付宝";
                			break;
                		case 3:
                			if ($payment->payment_method_id){$payname = $payment->paymentMethod->name;}else $payname="其他代金券";
                			break;
                		case 4:
                			$payname="会员卡支付";
                			break;
                		case 5:
                			$payname="银联卡支付";
                			break;
                		case 9:
                			$payname="微信代金券";
                			break;
                		case 10:
                			$payname="微信会员余额支付";
                			break;
                	}
                	$printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
                	array_push($listData,"01".$payname.str_pad("", 25-$printlen," ").$payment->all_num."/".$payment->all_reality);
                	array_push($listData,"br");
                	$sumall=$sumall+$payment->all_reality;
                }
              
                // }//添加
                array_push($listData,"00".str_pad('',48,'-'));
                array_push($listData,"10".str_pad("合计：",7).$sumall);
                array_push($listData,"br");
                array_push($listData,"00".str_pad('',48,'-'));
                array_push($listData,"00".Yii::app()->user->name."    ".date('Y-m-d H:i:s',time()));
                array_push($listData,"br");
                //array_push($listData,"00"."   ".yii::t('app','订餐电话：').$order->company->telephone);return array('status'=>false,'msg'=>"123");
                
                $precode=$cprecode;
                //后面加切纸
                $sufcode="0A0A0A0A0A0A";
                
           }
           if(in_array('product',$rll)){
           	//产品销售
           	array_push($listData,"00");
           	array_push($listData,"br");
           	array_push($listData,"00");
           	array_push($listData,"br");
           	array_push($listData,"00");
           	array_push($listData,"br");
           	$memo="产品销售";
           	//return array('status'=>false,'msg'=>"123");
           	array_push($listData,"22".  Helper::setPrinterTitle(Company::getCompanyName($dpid)." ".$memo,8));//return array('status'=>false,'msg'=>"123");
           	//                if(!empty($memo))
           	//                {
           	//                    array_push($listData,"br");
           	//                    array_push($listData,"10".$memo);
           	//                }
           	array_push($listData,"00");
           	array_push($listData,"br");
           	array_push($listData,"00".str_pad('',48,'-'));
           	$payname="查询时间段：";
           	$printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
           	array_push($listData,"00".$payname.str_pad("", 15-$printlen," ").$begin_time." 至 ".$end_time);
           	array_push($listData,"br");
           	array_push($listData,"00".str_pad('',48,'-'));
           	$payname="产品";
            $ranking="排名";
           	$nummoney="数量/金额/实收（折后）";
           	//$realitymoney="实收（折后）";
           	$printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
           	$printlenc=(strlen($ranking) + mb_strlen($ranking,'UTF8')) / 2;
           	array_push($listData,"00".$payname.str_pad("", 20-$printlen," ").$ranking.str_pad("", 5-$printlenc," ").$nummoney);
           	array_push($listData,"br");
           	$a=1;//return array('status'=>false,'msg'=>$products);
           	foreach ($products as $model)
           	{
           		$payname=$model->product->product_name;
           		$ranking=$a;
           		$printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
           		$printlenc=(strlen($ranking) + mb_strlen($ranking,'UTF8')) / 2;
           		$a++;
           		array_push($listData,"00".$payname.str_pad("", 20-$printlen," ").$ranking.str_pad("", 5-$printlenc," ").$model->all_total."/".sprintf("%.2f",$model->all_jiage)."/".sprintf("%.2f",$model->all_price));
           		array_push($listData,"br");
           		//                 	$payname="数量/金额";
           		//                 	$printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
           		//                 	array_push($listData,"00".$payname.str_pad("", 20-$printlen," ").$model['all_num']."/".sprintf("%.2f",$model['all_price']));
           		//                 	array_push($listData,"br");
           		 
           	}
           	array_push($listData,"00".str_pad('',48,'-'));
           	array_push($listData,"00".Yii::app()->user->name."    ".date('Y-m-d H:i:s',time()));
           	array_push($listData,"br");
           	$precode=$cprecode;
           	$sufcode="0A0A0A0A0A0A";
           }
           //账单详情
           if(in_array('orderdetail',$rll)){
           	//产品销售
           	array_push($listData,"00");
           	array_push($listData,"br");
           	array_push($listData,"00");
           	array_push($listData,"br");
           	array_push($listData,"00");
           	array_push($listData,"br");
           	$memo="账单详情";
           	//return array('status'=>false,'msg'=>"123");
           	array_push($listData,"22".  Helper::setPrinterTitle(Company::getCompanyName($dpid)." ".$memo,8));//return array('status'=>false,'msg'=>"123");
           	//                if(!empty($memo))
           	//                {
           	//                    array_push($listData,"br");
           	//                    array_push($listData,"10".$memo);
           	//                }
           	array_push($listData,"00");
           	array_push($listData,"br");
           	array_push($listData,"00".str_pad('',48,'-'));
           	$payname="查询时间段：";
           	$printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
           	array_push($listData,"00".$payname.str_pad("", 15-$printlen," ").$begin_time." 至 ".$end_time);
           	array_push($listData,"br");
           	array_push($listData,"00".str_pad('',48,'-'));
           	$payname="账单号";
           	$time="时间";
           	$sitenum="座位号";
           	$number="人数";
           	$price="原价";
           	$reaprice="实价";
           	//$realitymoney="实收（折后）";
           	$printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
           	$printlent=(strlen($time) + mb_strlen($time,'UTF8')) / 2;
           	$printlens=(strlen($sitenum) + mb_strlen($sitenum,'UTF8')) / 2;
           	$printlenn=(strlen($number) + mb_strlen($number,'UTF8')) / 2;
           	$printlenp=(strlen($price) + mb_strlen($price,'UTF8')) / 2;
           	$printlenr=(strlen($reaprice) + mb_strlen($reaprice,'UTF8')) / 2;
           	array_push($listData,"00".$payname.str_pad("", 13-$printlen," ").$time.str_pad("", 8-$printlent," ").$sitenum.str_pad("", 7-$printlens," ").$number.str_pad("", 5-$printlenn," ").$price.str_pad("", 6-$printlenp," ").$reaprice);
           	array_push($listData,"br");
           	array_push($listData,"00".str_pad('',48,'-'));
           	$a=1;//return array('status'=>false,'msg'=>$products);
           	foreach ($orderdetails as $model)
           	{
           		$payname=$model->account_no;
           		//$time=date('m-d H:i',$model->update_at);
           		$date=$model->update_at; // 数据库读取出来的时间
           		$time = strtotime($date);
           		$sitenum = Helper::getSiteName($model->lid);
           		$number = $model->all_number;
           		$price = sprintf("%.2f",Helper::getOriginalMoney($model->account_no));
           		$reaprice = sprintf("%.2f",Helper::getAccountMoney($model->account_no));
           		$printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
           		$printlent=(strlen($time) + mb_strlen($time,'UTF8')) / 2;
           		$printlens=(strlen($sitenum) + mb_strlen($sitenum,'UTF8')) / 2;
           		$printlenn=(strlen($number) + mb_strlen($number,'UTF8')) / 2;
           		$printlenp=(strlen($price) + mb_strlen($price,'UTF8')) / 2;
           		$printlenr=(strlen($reaprice) + mb_strlen($reaprice,'UTF8')) / 2;
           		$a++;
           		array_push($listData,"00".$payname.str_pad("", 13-$printlen," ").date("d"."日"."H:i",$time).str_pad("", 12-$printlent," ").$sitenum.str_pad("", 6-$printlens," ").$number.str_pad("", 3-$printlenn," ").$price.str_pad("", 8-$printlenp," ").$reaprice);
           		array_push($listData,"br");
           		//                 	$payname="数量/金额";
           		//                 	$printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
           		//                 	array_push($listData,"00".$payname.str_pad("", 20-$printlen," ").$model['all_num']."/".sprintf("%.2f",$model['all_price']));
           		//                 	array_push($listData,"br");
           
           	}
           	array_push($listData,"00".str_pad('',48,'-'));
           	array_push($listData,"00".Yii::app()->user->name."    ".date('Y-m-d H:i:s',time()));
           	array_push($listData,"br");
           	$precode=$cprecode;
           	$sufcode="0A0A0A0A0A0A";
           }
           //台桌区域
           if(in_array('table',$rll)){
           	//台桌区域
           	array_push($listData,"00");
           	array_push($listData,"br");
           	array_push($listData,"00");
           	array_push($listData,"br");
           	array_push($listData,"00");
           	array_push($listData,"br");
           	$memo="台桌区域";
           	//return array('status'=>false,'msg'=>"123");
           	array_push($listData,"22".  Helper::setPrinterTitle(Company::getCompanyName($dpid)." ".$memo,8));//return array('status'=>false,'msg'=>"123");
           	//                if(!empty($memo))
           	//                {
           	//                    array_push($listData,"br");
           	//                    array_push($listData,"10".$memo);
           	//                }
           	array_push($listData,"00");
           	array_push($listData,"br");
           	array_push($listData,"00".str_pad('',48,'-'));
           	$payname="查询时间段：";
           	$printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
           	array_push($listData,"00".$payname.str_pad("", 15-$printlen," ").$begin_time." 至 ".$end_time);
           	array_push($listData,"br");
           	array_push($listData,"00".str_pad('',48,'-'));
           	$payname="台桌区域";
           	$number="客流";
           	$allaccount="单数";
           	$reamoney="金额";
            $proportion="占比";
           	$printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
           	$printlenn=(strlen($number) + mb_strlen($number,'UTF8')) / 2;
           	$printlena=(strlen($allaccount) + mb_strlen($allaccount,'UTF8')) / 2;
           	$printlenr=(strlen($reamoney) + mb_strlen($reamoney,'UTF8')) / 2;
           	$printlenp=(strlen($proportion) + mb_strlen($proportion,'UTF8')) / 2;
           	array_push($listData,"00".$payname.str_pad("", 15-$printlen," ").$number.str_pad("", 8-$printlenn," ").$allaccount.str_pad("", 8-$printlena," ").$reamoney.str_pad("", 10-$printlenr," ").$proportion);
           	array_push($listData,"br");
           	array_push($listData,"00".str_pad('',48,'-'));
           	$a=1;//return array('status'=>false,'msg'=>$products);
           	foreach ($tableareas as $model)
           	{
           		$payname=$model['name'];
           		$number=$model['all_number'];
           		$allaccount=$model['all_account'];
           		$reamoney=$model['all_paymoney'];
           		if($allmoney['all_money']){
           		$proportion=sprintf("%.2f",$model['all_paymoney']*100/$allmoney['all_money'])."%";
           		}else{
           			$proportion="0";
           		}
           		//$ranking=$a;
           		$printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
           		$printlenn=(strlen($number) + mb_strlen($number,'UTF8')) / 2;
           		$printlena=(strlen($allaccount) + mb_strlen($allaccount,'UTF8')) / 2;
           		$printlenr=(strlen($reamoney) + mb_strlen($reamoney,'UTF8')) / 2;
           		$printlenp=(strlen($proportion) + mb_strlen($proportion,'UTF8')) / 2;
           		$a++;
           		array_push($listData,"00".$payname.str_pad("", 15-$printlen," ").$number.str_pad("", 8-$printlenn," ").$allaccount.str_pad("", 8-$printlena," ").$reamoney.str_pad("", 10-$printlenr," ").$proportion);
           		array_push($listData,"br");
           		//                 	$payname="数量/金额";
           		//                 	$printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
           		//                 	array_push($listData,"00".$payname.str_pad("", 20-$printlen," ").$model['all_num']."/".sprintf("%.2f",$model['all_price']));
           		//                 	array_push($listData,"br");
           
           	}
           	array_push($listData,"00".str_pad('',48,'-'));
           	array_push($listData,"00".Yii::app()->user->name."    ".date('Y-m-d H:i:s',time()));
           	array_push($listData,"br");
           	$precode=$cprecode;
           	$sufcode="0A0A0A0A0A0A";
           }
           
           //退菜明细
           if(in_array('retreatdetail',$rll)){
           	//退菜明细
           	array_push($listData,"00");
           	array_push($listData,"br");
           	array_push($listData,"00");
           	array_push($listData,"br");
           	array_push($listData,"00");
           	array_push($listData,"br");
           	$memo="退菜明细";
           	//return array('status'=>false,'msg'=>"123");
           	array_push($listData,"22".  Helper::setPrinterTitle(Company::getCompanyName($dpid)." ".$memo,8));//return array('status'=>false,'msg'=>"123");
           	//                if(!empty($memo))
           	//                {
           	//                    array_push($listData,"br");
           	//                    array_push($listData,"10".$memo);
           	//                }
           	array_push($listData,"00");
           	array_push($listData,"br");
           	array_push($listData,"00".str_pad('',48,'-'));
           	$payname="查询时间段：";
           	$printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
           	array_push($listData,"00".$payname.str_pad("", 15-$printlen," ").$begin_time." 至 ".$end_time);
           	array_push($listData,"br");
           	array_push($listData,"00".str_pad('',48,'-'));
           	$payname="账单号";
           	$productname="菜品";
           	$number="数量";
           	$price="价格";
           	$time="时间";
           	$reason="原因";
           	//$realitymoney="实收（折后）";
           	$printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
           	$printlent=(strlen($time) + mb_strlen($time,'UTF8')) / 2;
           	$printlens=(strlen($productname) + mb_strlen($productname,'UTF8')) / 2;
           	$printlenn=(strlen($number) + mb_strlen($number,'UTF8')) / 2;
           	$printlenp=(strlen($price) + mb_strlen($price,'UTF8')) / 2;
           	$printlenr=(strlen($reason) + mb_strlen($reason,'UTF8')) / 2;
           	array_push($listData,"00".$payname.str_pad("", 13-$printlen," ").$time.str_pad("", 8-$printlent," ").$productname.str_pad("", 7-$printlens," ").$number.str_pad("", 5-$printlenn," ").$price.str_pad("", 6-$printlenp," ").$reason);
           	array_push($listData,"br");//return array('status'=>false,'msg'=>"123");
           	array_push($listData,"00".str_pad('',48,'-'));
           	$a=1;//return array('status'=>false,'msg'=>$products);
           	
           	foreach ($retreatdetails as $model)
           	{
           		$payname = $model['account_no'];
           		//$time = date('m-d H:i',$model['update_at']);
           		$date = $model['update_at']; // 数据库读取出来的时间
           		$time = strtotime($date);
           		$productname = $model['product_name'];
           		//$sitenum = Helper::getSiteName($model->lid);
           		$number = $model['amount'];
           		$price = sprintf("%.2f",$model['price']);
           		$reason = $model['name'].'('. $model['retreat_memo'].')';
           		$printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
           		$printlent=(strlen($time) + mb_strlen($time,'UTF8')) / 2;
           		$printlens=(strlen($productname) + mb_strlen($productname,'UTF8')) / 2;
           		$printlenn=(strlen($number) + mb_strlen($number,'UTF8')) / 2;
           		$printlenp=(strlen($price) + mb_strlen($price,'UTF8')) / 2;
           		$printlenr=(strlen($reason) + mb_strlen($reason,'UTF8')) / 2;
           		$a++;
           		array_push($listData,"00".$payname.str_pad("", 13-$printlen," ").date("d"."日"."H:i",$time).str_pad("", 12-$printlent," ").$productname.str_pad("", 7-$printlens," ").$number.str_pad("", 5-$printlenn," ").$price.str_pad("", 6-$printlenp," ").$reason);
           		array_push($listData,"br");
           		//                 	$payname="数量/金额";
           		//                 	$printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
           		//                 	array_push($listData,"00".$payname.str_pad("", 20-$printlen," ").$model['all_num']."/".sprintf("%.2f",$model['all_price']));
           		//                 	array_push($listData,"br");
           		 
           	}
           	array_push($listData,"00".str_pad('',48,'-'));
           	array_push($listData,"00".Yii::app()->user->name."    ".date('Y-m-d H:i:s',time()));
           	array_push($listData,"br");
           	$precode=$cprecode;
           	$sufcode="0A0A0A0A0A0A";
           }
           
           //充值记录表
           if(in_array('recharge',$rll)){
                //充值记录报表）
                array_push($listData,"00");
                array_push($listData,"br");
                array_push($listData,"00");
                array_push($listData,"br");
                array_push($listData,"00");
                array_push($listData,"br");
                $memo="充值记录报表";
                //return array('status'=>false,'msg'=>"123");
                array_push($listData,"22".  Helper::setPrinterTitle(Company::getCompanyName($dpid)." ".$memo,8));//return array('status'=>false,'msg'=>"123");
                //                if(!empty($memo))
                //                {
                //                    array_push($listData,"br");
                //                    array_push($listData,"10".$memo);
                //                }
                array_push($listData,"00");
                array_push($listData,"br");
                array_push($listData,"00".str_pad('',48,'-'));
                $payname="查询时间段：";
                $printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
                array_push($listData,"00".$payname.str_pad("", 15-$printlen," ").$begin_time." 至 ".$end_time);
                array_push($listData,"br");
                array_push($listData,"00".str_pad('',48,'-'));
                
				if(!empty($money)){
	                	$payname = "传统卡充值/赠送:";//}
                        $printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
	                	array_push($listData,"01".$payname.str_pad("", 25-$printlen," ").$money['all_money']."/".$money['all_give']);
	                	array_push($listData,"br");
	                	$sumall=$sumall+$money['all_money'];
                	}
                        if(!empty($recharge)){
	                	$payname = "微信充值/赠送:";//}
                        $printlen=(strlen($payname) + mb_strlen($payname,'UTF8')) / 2;
	                	array_push($listData,"01".$payname.str_pad("", 25-$printlen," ").$recharge['all_recharge']."/".$recharge['all_cashback']);
	                	array_push($listData,"br");
	                	$sumall=$sumall+$recharge['all_recharge'];
                	}
                array_push($listData,"00".str_pad('',48,'-'));
                array_push($listData,"00".Yii::app()->user->name."    ".date('Y-m-d H:i:s',time()));
                array_push($listData,"br");
                //array_push($listData,"00"."   ".yii::t('app','订餐电话：').$order->company->telephone);return array('status'=>false,'msg'=>"123");
                $precode=$cprecode;
                $sufcode="0A0A0A0A0A0A";
           
           } 
                $precode=$cprecode;
                $sufcode="0A0A0A0A0A0A1D5601";
                //结束添加
                $retcontent=array();//return array('status'=>false,'msg'=>"123");
                $orderid="0000000000";//打印日结单时
		$retcontent= Helper::printConetent($printer,$listData,$precode,$sufcode,$printserver,$orderid);	
                //$retcontent['orderid']=$order->lid;
                return $retcontent;
	}
	public function getAccountMoney($account_no){
		$accountMoney = '';
		if($account_no){
			$sql = 'select sum(t.pay_amount) as all_zhifu,t.* from nb_order_pay t where t.paytype not in(9,10) and t.order_id in(select t1.lid from nb_order t1 where t1.account_no = '.$account_no.')';
			$connect = Yii::app()->db->createCommand($sql);
			$money = $connect->queryRow();
			$accountMoney = $money['all_zhifu'];
		}
		//$accountMoney = '';
		//$sql = 'update nb_order_product set original_price=(select t.original_price from nb_product t where t.delete_flag=0 and t.lid =nb_order_product.product_id ) where 1';
		return $accountMoney;
	}
	public function getOriginalMoney($account_no){
		$originalMoney = '';
		if($account_no){
			$sql = 'select sum(t.original_price*t.amount) as all_original from nb_order_product t  where t.is_retreat = 0 and t.product_order_status in(1,2) and t.order_id in(select t1.lid from nb_order t1 where t1.account_no = '.$account_no.')';
			$connect = Yii::app()->db->createCommand($sql);
			$money = $connect->queryRow();
			//var_dump($sql);exit;
			$originalMoney = $money['all_original'];
		}
		//$accountMoney = '';
		//$sql = 'update nb_order_product set original_price=(select t.original_price from nb_product t where t.delete_flag=0 and t.lid =nb_order_product.product_id ) where 1';
		return $originalMoney;
	}
	//获取座位信息
public function getSiteName($orderId){
		$sitename="";
		$sitetype="";
	
		$sql = 'select t.site_id, t.dpid, t1.site_level, t1.type_id, t1.serial, t2.name,t2.simplecode from nb_order t, nb_site t1, nb_site_type t2 where t.site_id = t1.lid and t.dpid = t1.dpid and t1.type_id = t2.lid and t.dpid = t2.dpid and t.lid ='. $orderId;
		//$conn = Yii::app()->db->createCommand($sql);
		//$result = $conn->queryRow();
		//$siteId = $result['lid'];
		$connect = Yii::app()->db->createCommand($sql);
		//	$connect->bindValue(':site_id',$siteId);
		//	$connect->bindValue(':dpid',$dpid);
		$site = $connect->queryRow();
		$retsite="";
		if($site['site_id'] && $site['dpid'] ){
			//	echo 'ABC';
			$sitelevel = $site['site_level'];
			$sitename = $site['simplecode'];
			$sitetype = $site['serial'];
			$retsite=$sitename.$sitetype;
		}
		//if($siteId && $dpid){
		//$sql = 'select order.site_id, order.dpid,site.type_id, site.serial, site_type.name from nb_order, nb_site, nb_site_type where order.site_id = site.lid and order.dpid = site.dpid';
		//$conn = Yii::app()->db->createCommand($sql);
	
		//}
		return $retsite;
	}
        //开台时的打印
        //打印开台号和人数，以后有WiFi的密码等。
	static public function printSite(SiteNo $siteno,Site $site,Pad $pad, $cprecode,$printserver,$memo){
		               
                $printer = Printer::model()->find('lid=:printerId and dpid=:dpid',  array(':printerId'=>$pad->printer_id,':dpid'=>$siteno->dpid));
		if(empty($printer)) {
                        return array('status'=>0,'dpid'=>$siteno->dpid,'jobid'=>"0",'type'=>'none','msg'=>yii::t('app','PAD还没有设置默认打印机'));		
		}		
                $listData = array("22".  Helper::setPrinterTitle(Company::getCompanyName($siteno->dpid),8));
                if(!empty($memo))
                {
                    array_push($listData,"br");
                    array_push($listData,"11".$memo);                    
                }
                array_push($listData,"00");
                array_push($listData,"br");
                $strSite="";
                if($siteno->is_temp==0)
                {
                    array_push($listData,"00".yii::t('app','座号：'));
                    array_push($listData,"11".$site->siteType->name.' '.$site->serial);
                }else{
                    array_push($listData,"00".yii::t('app','座号：临时座'));
                    array_push($listData,"11".$siteno->site_id%10000);                    
                }
                array_push($listData,"00"."   ".yii::t('app','人数：').$siteno->number);
		array_push($listData,"br");
		array_push($listData,"00".str_pad('',48,'-'));                
		
		array_push($listData,"00".$order->username."    ".date('Y-m-d H:i:s',time()));                    
                //array_push($listData,"00"."   ".yii::t('app','订餐电话：').$order->company->telephone);
                 
                $precode=$cprecode;
                //后面加切纸
                $sufcode="0A0A0A0A0A0A1D5601";                        
                $printret=array();
                $orderid="0000000000";
		$retccontent= Helper::printConetent($printer,$listData,$precode,$sufcode,$printserver,$orderid);	
                if($retccontent['status'])
                {
                    return array('status'=>1,'msg'=>yii::t('app','打印成功'));
                }else{
                    return array('status'=>0,'msg'=>yii::t('app','发送打印内容失败'));
                }
	}
        
        //开台时的打印
        //打印开台号和人数，以后有WiFi的密码等。
	static public function printQueue(Pad $pad, $cprecode,$printserver,$queueno,$waitingno,$mobileno,$sitename,$minpersons,$maxpersons){
		               
                $printer = Printer::model()->find('lid=:printerId and dpid=:dpid',  array(':printerId'=>$pad->printer_id,':dpid'=>$pad->dpid));
		if(empty($printer)) {
                        return array('status'=>0,'dpid'=>$siteno->dpid,'jobid'=>"0",'type'=>'none','msg'=>yii::t('app','PAD还没有设置默认打印机'));		
		}		
                $listData = array("22".  Helper::setPrinterTitle(Company::getCompanyName($pad->dpid),8));
                
                array_push($listData,"br");
                array_push($listData,"11"."      等位排号单");            
                array_push($listData,"00");
                array_push($listData,"br");
                array_push($listData,"00".str_pad('',48,'-'));  
                array_push($listData,"00"."手机号码:".$mobileno);
                array_push($listData,"br");
                array_push($listData,"00"."就餐人数:".$minpersons."-".$maxpersons);
                array_push($listData,"br");
                array_push($listData,"00"."领号时间:".date('Y-m-d H:i:s',time()));
                array_push($listData,"br");
                array_push($listData,"00".str_pad('',48,'-'));
                array_push($listData,"22"."  ".$sitename);
                array_push($listData,"br");
                array_push($listData,"22"."    ".$queueno);
                array_push($listData,"br");
                array_push($listData,"00"."您之前还有".$waitingno."桌客人在等待！");
                array_push($listData,"br");
                array_push($listData,"00".str_pad('',48,'-'));
                $queuememo=Company::getQueueMemo($pad->dpid);
                if(!empty($queuememo))
                {
                    array_push($listData,"00"."*注意，".$queuememo);
                    array_push($listData,"br");
                }
                array_push($listData,"00"."*留下手机或微信号，到号时，会有短信或微信通知！");
                array_push($listData,"br");
                array_push($listData,"00"."*最终解释权归本店所有！");
                array_push($listData,"br");
                $precode=$cprecode;
                //后面加切纸
                $sufcode="0A0A0A0A0A0A1D5601";                        
                $printret=array();
                $orderid="0000000000";
		$retccontent= Helper::printConetent($printer,$listData,$precode,$sufcode,$printserver,$orderid);                
                return $retccontent;
	}
        
        //打印机测试
	static public function printCheck(Pad $pad){		
                $printer = Printer::model()->find('lid=:printerId and dpid=:dpid',  array(':printerId'=>$pad->printer_id,':dpid'=>$pad->dpid));
		if(empty($printer)) {
                        return array('status'=>false,'dpid'=>$order->dpid,'jobid'=>"0",'type'=>'none','msg'=>yii::t('app','PAD还没有设置默认打印机'));		
		}
                
		$listData = array(Helper::getPlaceholderLenBoth(yii::t('app','打印机校正成功！'), 48));
		array_push($listData,str_pad('',48,'-'));
                //var_dump($listData);exit;
                //后面加切纸
                $sufcode="0A0A0A0A0A0A1D5601";     
		return Helper::printConetent($printer,$listData,"",$sufcode);		
	}
        
        //单品厨打 口味 全单口味
        //套餐和加菜一起厨打 口味 全单口味
        //send by workerman encode by GBK or shift-JIS
        //传入呼叫器号码要打印出来
	static public function printKitchen(Order $order,OrderProduct $orderProduct,Site $site,  SiteNo $siteNo , $reprint){		
                //$order = Order::model()->find('lid=:orderid and dpid=:dpid',  array(':orderid'=>$orderProduct->order_id,':dpid'=>$orderProduct->dpid));
		//var_dump($order);
                $orderTastes=  OrderTaste::model()->with('taste')->findAll('t.order_id=:orderid and t.dpid=:dpid and t.is_order=1',  array(':orderid'=>$orderProduct->order_id,':dpid'=>$orderProduct->dpid));
                $orderTasteEx = $order->taste_memo;
                $orderProductTastes = OrderTaste::model()->with('taste')->findAll('t.order_id=:orderid and t.dpid=:dpid and t.is_order=0',  array(':orderid'=>$orderProduct->lid,':dpid'=>$orderProduct->dpid));
                $orderProductTasteEx = $orderProduct->taste_memo;
                //var_dump($orderProductTasteEx);exit;
                //$site = Site::model()->find('lid=:lid and dpid=:dpid',  array(':lid'=>$order->site_id,':dpid'=>$order->dpid));
		//$siteType = SiteType::model()->find('lid=:lid and dpid=:dpid',  array(':lid'=>$site->type_id,':dpid'=>$order->dpid));
		//var_dump($site->floor_id,$orderProduct->product->printer_way_id);exit;
                $floor_id='0';
                if($order->is_temp=='0')
                {
                    $floor_id=$site->floor_id;
                }
                $printwaydetails = PrinterWayDetail::model()->findAll('floor_id=:floorid and print_way_id=:pwi and dpid=:dpid and delete_flag=0',array(':floorid'=>$floor_id,':pwi'=>$orderProduct->product->printer_way_id,':dpid'=>$orderProduct->dpid));
                //var_dump($printwaydetails);exit;	
		foreach ($printwaydetails as $printway) {
                        $printer = Printer::model()->find('lid=:printerId and dpid=:dpid',  array(':printerId'=>$printway->printer_id,':dpid'=>$order->dpid));
                        if(empty($printer)) {
                                return array('status'=>false,'dpid'=>$printer->dpid,'jobid'=>"0",'type'=>'none','msg'=>yii::t('app','没有设置厨房打印机'));		
                        }
                        if($printer->printer_type!='0') {
                                return array('status'=>false,'dpid'=>$printer->dpid,'jobid'=>"0",'type'=>'none','msg'=>yii::t('app','厨打打印机必须是网络打印机'));		
                        }
                        //$listKey = $order->dpid.'_'.$printer->ip_address;  
                        //    dfddddddddddddddd  d 
                        /////////**********判断打印机是否存  在  ******//////////////////
                        //$list = new ARedisList($listKey);
                        //var_dump($list);exit;
                        //$listData = array("22".Helper::getPlaceholderLenBoth($orderProduct->company->company_name, 16));//
                        $listData = array("22".  Helper::setPrinterTitle("催菜",8));
                        array_push($listData,"00");
                        array_push($listData,"br");
                        if($reprint)
                        {
                            $strreprint=yii::t('app',"*****重复厨打，请留意！！！");
                            array_push($listData,"11".$strreprint);
                        }
                        array_push($listData,"br");
                        $strSite="";
                        if($order->is_temp=='1')
                        {
                            //$strSite.= str_pad(yii::t('app','临时座：').$siteNo->site_id%1000 , 24,' ').str_pad(yii::t('app','人数：').$order->number,12,' ');
                            array_push($listData,"00".yii::t('app','临时座：'));
                            array_push($listData,"11".$siteNo->site_id%1000);
                        }else{
                            //$strSite.= str_pad(yii::t('app','座号：').$site->siteType->name.' '.$site->serial , 24,' ').str_pad(yii::t('app','人数：').$order->number,12,' ');
                            array_push($listData,"00".yii::t('app','座号：'));
                            array_push($listData,"11".$site->siteType->name.' '.$site->serial);
                        }
                        array_push($listData,"00"."   ".yii::t('app','人数：').$order->number);
                        //$strreprint="";
                        //var_dump($strSite);exit;
                        //$listData = array(Helper::getPlaceholderLenBoth($orderProduct->company->company_name, 48));
                        //var_dump($listData);exit;
                        
                        if(!empty($order->callno))
                        {
                            //$strSite=$strSite.str_pad(yii::t('app','呼叫号：').$order->callno,12,' ');
                            //array_push($listData,$strcall);
                            array_push($listData,"00"."  ".yii::t('app','呼叫号：'));
                            array_push($listData,"11".$order->callno);
                        }
                        //var_dump($listData);exit;
                        //array_push($listData,$strSite);
                        array_push($listData,"br");                        
                        array_push($listData,"00".str_pad('',48,'-'));
                        //array_push($listData,Helper::getPlaceholderLen($orderProduct->product->product_name,34).Helper::getPlaceholderLen($orderProduct->amount." X ".$orderProduct->product->product_unit,14));	
                        array_push($listData,"11".str_pad($orderProduct->amount." X ",8,' ').Helper::setProductName($orderProduct->product->product_name,12,8));
                        array_push($listData,"br");
                        $strTaste= yii::t('app',"单品口味：").$orderProductTasteEx;
                        $existTaste=0;
                        $productStatus="";
                        if($orderProduct->product_status=="1"){
                        	$productStatus="等叫！！！";
                        }elseif ($orderProduct->product_status=="2"){
                        	$productStatus="加急！！！";
                        }
                        $strStatus=yii::t('app',"状态：").$productStatus;
                        if(!empty($productStatus)){
                        	array_push($listData,"11".$strStatus);
                        	array_push($listData,"br");
                        }
                        if(!empty($orderProductTasteEx))
                        {
                            $existTaste=1;
                        }
                        foreach($orderProductTastes as $orderProductTaste){
                            $strTaste.= '/'.$orderProductTaste->taste->name;
                            $existTaste=1;
                        }
                        if($existTaste==1)
                        {
                            array_push($listData,"11".$strTaste);
                            array_push($listData,"br");
                        }
                        array_push($listData,"00".str_pad('',48,'-'));
                        $existTaste=0;
                        if(!empty($orderTasteEx))
                        {
                            $existTaste=1;
                        }
                        $strAllTaste= yii::t('app',"全单口味：").$orderTasteEx;
                        foreach($orderTastes as $orderTaste){
                            $strAllTaste.= '/'.$orderTaste->taste->name;
                            $existTaste=1;
                        }
                        if($existTaste==1)
                        {
                            array_push($listData,"11".$strAllTaste);
                            array_push($listData,"br");
                            array_push($listData,"00".str_pad('',48,'-'));
                        }
                        
                        array_push($listData,"00".yii::t('app','操作员：').$order->username //Yii::app()->user->name
                                .date('Y-m-d H:i:s',time()));
                        $precode="";
                        //后面加切纸
                        $sufcode="0A0A0A0A0A0A1D5601";                        
                        //var_dump($listData);exit;
                        $printret=array();
                        $printserver="1";
                        for($i=0;$i<$printway->list_no;$i++){                                        
                            $printret=Helper::printConetent($printer,$listData,$precode,$sufcode,$printserver);
                            if(!$printret['status'])
                            {
                                return $printret;
                            }
                        }
                        return $printret;
                        /*
                        if(!empty($listData)){
                            if(Yii::app()->params->has_cache)
                            {
                                if($printway->list_no) {
                                    for($i=0;$i<$printway->list_no;$i++){
                                        
                                        if($reprint) {
                                                $listData = str_pad('重新厨打', 40 , ' ',STR_PAD_BOTH).'<br>'.$listData;
                                                $list->add($listData);
                                        } else {
                                                $list->unshift($listData);
                                        }
                                    }
                                }
                            }
                        }*/
                }		
		//echo Yii::app()->end(json_encode(array('status'=>true,'msg'=>$listData)));exit;
		//$channel = new ARedisChannel($order->dpid.'_PD');
		//$channel->publish($listKey);		
	}
        
        //在同一个打印机厨打的菜品，不分开厨打，这个比较复杂，以后完善，
        //目前就是所有菜品一张厨打单子上出来。口味暂时也不打印
        //完善的参见printKitchenAll2
	static public function printKitchenAll(Order $order,Site $site,  SiteNo $siteNo , $reprint){		
                //$order = Order::model()->find('lid=:orderid and dpid=:dpid',  array(':orderid'=>$orderProduct->order_id,':dpid'=>$orderProduct->dpid));
		//var_dump($order);
                $orderTastes=  OrderTaste::model()->with('taste')->findAll('t.order_id=:orderid and t.dpid=:dpid and t.is_order=1',  array(':orderid'=>$order->lid,':dpid'=>$order->dpid));
                $orderTasteEx = $order->taste_memo;
                //$orderProductTastes = OrderTaste::model()->with('taste')->findAll('t.order_id=:orderid and t.dpid=:dpid and t.is_order=0',  array(':orderid'=>$orderProduct->lid,':dpid'=>$orderProduct->dpid));
                //$orderProductTasteEx = $orderProduct->taste_memo;
                //var_dump($orderProductTasteEx);exit;
                //$site = Site::model()->find('lid=:lid and dpid=:dpid',  array(':lid'=>$order->site_id,':dpid'=>$order->dpid));
		//$siteType = SiteType::model()->find('lid=:lid and dpid=:dpid',  array(':lid'=>$site->type_id,':dpid'=>$order->dpid));
		//var_dump($site->floor_id,$orderProduct->product->printer_way_id);exit;
                $floor_id='0';
                if($order->is_temp=='0')
                {
                    $floor_id=$site->floor_id;
                }
                $orderProducts = OrderProduct::model()->with('product')->findAll('t.order_id=:id and t.dpid=:dpid and t.is_print=0 and t.delete_flag=0' , array(':id'=>$order->lid,':dpid'=>$order->dpid));
                //var_dump($orderProducts);exit;
                if(empty($orderProducts)) 
                {
                    return array('status'=>false,'dpid'=>$order->dpid,'jobid'=>"0",'type'=>'none','msg'=>yii::t('app','没有要打印的菜品！'));
                }
                //var_dump($orderProducts);exit;
                $printwaydetails = PrinterWayDetail::model()->findAll('floor_id=:floorid and print_way_id=:pwi and dpid=:dpid and delete_flag=0',array(':floorid'=>$floor_id,':pwi'=>$orderProducts[0]->product->printer_way_id,':dpid'=>$order->dpid));
                if(empty($printwaydetails))
                {
                    return array('status'=>false,'dpid'=>$order->dpid,'jobid'=>"0",'type'=>'none','msg'=>yii::t('app','下单区域没有设定打印方案！'));
                }
                //var_dump($printwaydetails);exit;	
		foreach ($printwaydetails as $printway) {
                        $printer = Printer::model()->find('lid=:printerId and dpid=:dpid',  array(':printerId'=>$printway->printer_id,':dpid'=>$order->dpid));
                        if(empty($printer)) {
                                return array('status'=>false,'dpid'=>$printer->dpid,'jobid'=>"0",'type'=>'none','msg'=>yii::t('app','没有设置厨房打印机'));		
                        }
                        if($printer->printer_type!='0') {
                                return array('status'=>false,'dpid'=>$printer->dpid,'jobid'=>"0",'type'=>'none','msg'=>yii::t('app','厨打打印机必须是网络打印机'));		
                        }
                        //$listData = array(Helper::getPlaceholderLenBoth($order->company->company_name, 48));
                        //$listData = array("22".Helper::getPlaceholderLenBoth($order->company->company_name, 16));//
                        $listData = array("22".  Helper::setPrinterTitle($order->company->company_name,8));
                        array_push($listData,"00");
                        array_push($listData,"br");
                        if($reprint)
                        {
                            $strreprint=yii::t('app',"*****重复厨打，请留意！！！");
                            array_push($listData,"11".$strreprint);
                        }
                        array_push($listData,"br");
                        $strSite="";
                        if($order->is_temp=='1')
                        {
                            //$strSite.= str_pad(yii::t('app','临时座：').$siteNo->site_id%1000 , 24,' ').str_pad(yii::t('app','人数：').$order->number,12,' ');
                            array_push($listData,"00".yii::t('app','临时座：'));
                            array_push($listData,"11".$siteNo->site_id%1000);
                        }else{
                            //$strSite.= str_pad(yii::t('app','座号：').$site->siteType->name.' '.$site->serial , 24,' ').str_pad(yii::t('app','人数：').$order->number,12,' ');
                            array_push($listData,"00".yii::t('app','座号：'));
                            array_push($listData,"11".$site->siteType->name.' '.$site->serial);
                        }
                        //$strreprint="";
                        //var_dump($strSite);exit;
                        array_push($listData,"00".yii::t('app','人数：').$order->number);
                        //var_dump($listData);exit;
                        
                        if(!empty($order->callno))
                        {
                            //$strSite=$strSite.str_pad(yii::t('app','呼叫号：').$order->callno,12,' ');
                            //array_push($listData,$strcall);
                            array_push($listData,"00"."  ".yii::t('app','呼叫号：'));
                            array_push($listData,"11".$order->callno);
                        }
                        //var_dump($listData);exit;
                       // array_push($listData,$strSite);    
                        array_push($listData,"br");
                        array_push($listData,"00".str_pad('',48,'-'));
                        foreach($orderProducts as $orderProduct)
                        {
                            //array_push($listData,Helper::getPlaceholderLen($orderProduct->product->product_name,38).Helper::getPlaceholderLen($orderProduct->amount." X ".$orderProduct->product->product_unit,10));	
                            array_push($listData,"11".str_pad($orderProduct->amount." X ",8,' ').Helper::setProductName($orderProduct->product->product_name,12,8));
                            array_push($listData,"br");
                        }
                        
                        //$strTaste= yii::t('app',"单品口味：").$orderProductTasteEx;
                        //foreach($orderProductTastes as $orderProductTaste){
                        //    $strTaste.= '/'.$orderProductTaste->taste->name;
                        //}
                        //array_push($listData,$strTaste);
                        //array_push($listData,str_pad('',48,'-'));
                       // $strAllTaste= yii::t('app',"全单口味：").$orderTasteEx;
                       // foreach($orderTastes as $orderTaste){
                       //     $strAllTaste.= '/'.$orderTaste->taste->name;
                       // }
                       // array_push($listData,$strAllTaste);
                        array_push($listData,"00".str_pad('',48,'-'));
                        array_push($listData,"00".yii::t('app','操作员：').$order->username
                                ."  ".date('Y-m-d H:i:s',time()));
                        $precode="";
                        //后面加切纸
                        $sufcode="0A0A0A0A0A0A1D5601";                        
                        //var_dump($listData);exit;
                        $printret=array();
                        for($i=0;$i<$printway->list_no;$i++){
                            $printserver="1";
                            $printret=Helper::printConetent($printer,$listData,$precode,$sufcode,$printserver);
                            if(!$printret['status'])
                            {
                                return $printret;
                            }
                        }
                        return $printret;
                }			
	}
        
        //这个是最新的，临时的在printKitchenAll中
        //2015/8/5更新
        //遍历所有打印方案，通常有传菜打印放啊、厨房整单打印方案、厨房分开打印方案
	static public function printKitchenAll2(Order $order,Site $site,  SiteNo $siteNo , $reprint){		
                $printers_a=array();
                $orderproducts_a=array();
                $printer2orderproducts_a=array();
                $jobids=array();
                //return array('status'=>true,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"dddd");        
                //$orderProductTastes = OrderTaste::model()->with('taste')->findAll('t.order_id=:orderid and t.dpid=:dpid and t.is_order=0',  array(':orderid'=>$orderProduct->lid,':dpid'=>$orderProduct->dpid));
                //$orderProductTasteEx = $orderProduct->taste_memo;
                //var_dump($orderProductTasteEx);exit;
                //$site = Site::model()->find('lid=:lid and dpid=:dpid',  array(':lid'=>$order->site_id,':dpid'=>$order->dpid));
		//$siteType = SiteType::model()->find('lid=:lid and dpid=:dpid',  array(':lid'=>$site->type_id,':dpid'=>$order->dpid));
		//var_dump($site->floor_id,$orderProduct->product->printer_way_id);exit;
                $floor_id='0';
                if($order->is_temp=='0')
                {
                    $floor_id=$site->floor_id;
                }
                $orderProducts = OrderProduct::model()->with('product')->findAll('t.order_id=:id and t.dpid=:dpid and t.is_print=0 and t.delete_flag=0' , array(':id'=>$order->lid,':dpid'=>$order->dpid));
                if(empty($orderProducts)) 
                {
                    return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>yii::t('app','没有要打印的菜品！'));
                }
                //foreach printer_way //传菜厨打、整单厨打、配菜和制作厨打
                $printerways= PrinterWay::model()->findAll(" dpid = :dpid and delete_flag=0",array(':dpid'=>$order->dpid));
                if(empty($printerways))
                {
                    return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"没有打印方案");
                }
                //var_dump($printerways);exit;
                foreach($printerways as $printerway)
                {
                    $printer2orderproducts_a=array();
                        foreach($orderProducts as $orderProduct)
                        {
                            $orderproducts_a[$orderProduct->lid]=$orderProduct;
                            
                            $productprinterwaynow=  ProductPrinterway::model()->find("dpid=:dpid and printer_way_id=:pwi and product_id=:pid",array(':dpid'=>$order->dpid,':pwi'=>$printerway->lid,':pid'=>$orderProduct->product_id));
                            //var_dump($printerway->lid,$productprinterwaynow);exit;
                            if(!empty($productprinterwaynow))
                            {
                                //不是每个产品都对应所有打印方案
//                                return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"部分产品没有设置打印方案");
//                            }else{
                                $printwaydetails = PrinterWayDetail::model()->findAll('floor_id=:floorid and print_way_id=:pwi and dpid=:dpid and delete_flag=0',array(':floorid'=>$floor_id,':pwi'=>$printerway->lid,':dpid'=>$order->dpid));
                                foreach ($printwaydetails as $printway) {
                                    $printer = Printer::model()->find('lid=:printerId and dpid=:dpid',  array(':printerId'=>$printway->printer_id,':dpid'=>$order->dpid));
                                    if(empty($printer)) {
                                            return array('status'=>false,'dpid'=>$printer->dpid,'allnum'=>"0",'type'=>'none','msg'=>yii::t('app','打印方案没有设置厨房打印机'));		
                                    }
                                    if(!array_key_exists($printer->lid, $printers_a))
                                    {
                                        $printers_a[$printer->lid]=$printer; //add isonpaper listno
                                    }
                                    if(array_key_exists($printer->lid, $printer2orderproducts_a))
                                    {
                                        array_push($printer2orderproducts_a[$printer->lid],$orderProduct->lid);
                                    }else{
                                        $printer2orderproducts_a[$printer->lid]=array($orderProduct->lid);
                                    }
                                    if($printer->printer_type!='0') {
                                            return array('status'=>false,'dpid'=>$printer->dpid,'allnum'=>"0",'type'=>'none','msg'=>yii::t('app','厨打打印机必须是网络打印机'));		
                                    }
                                }
                            }
                        }                        
                        //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试3");
                        //如果是整体，
//                        if(empty($printer2orderproducts_a))
//                        {
//                            return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"没有找到打印机和产品关系");
//                        }
                        if($printerway->is_onepaper=="1")
                        {
                            foreach ($printer2orderproducts_a as $key=>$values) {
                                
                                    $printer = $printers_a[$key];
                                    $productids="";
                                    //$listData = array("22".Helper::getPlaceholderLenBoth($order->company->company_name, 16));//
                                    $listData = array("22".Helper::setPrinterTitle($order->company->company_name,8));
                                    array_push($listData,"br");
                                    //array_push($listData,"22"."+++总单+++"); 
                                    array_push($listData,"22"."<".$printerway->name.">");
                                    array_push($listData,"00");
                                    array_push($listData,"br");
                                    if($reprint)
                                    {
                                        $strreprint=yii::t('app',"*****重复厨打，请留意！！！");
                                        array_push($listData,"11".$strreprint);
                                    }
                                    array_push($listData,"br");
                                    $strSite="";
                                    if($order->is_temp=='1')
                                    {
                                        array_push($listData,"00".yii::t('app','临时座：'));
                                        array_push($listData,"11".$siteNo->site_id%1000);
                                    }else{
                                        array_push($listData,"00".yii::t('app','座号：'));
                                        array_push($listData,"11".$site->siteType->name.' '.$site->serial);
                                    }
                                    array_push($listData,"00".yii::t('app','人数：').$order->number);
                                    //return array('status'=>true,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试1");
                                    if(!empty($order->callno))
                                    {
                                        array_push($listData,"00"."  ".yii::t('app','呼叫号：'));
                                        array_push($listData,"11".$order->callno);
                                    }
                                    array_push($listData,"br");
                                    array_push($listData,"00".str_pad('',48,'-'));
                                    $productids="";
                                    foreach($values as $value)
                                    {
                                        if(empty($productids))
                                        {
                                            $productids.=$value;
                                        }else{
                                            $productids.=",".$value;
                                        }
                                        
                                        $orderProduct=$orderproducts_a[$value];
                                        if($orderProduct->amount<1)
                                        {
                                            continue;
                                        }
                                        //array_push($listData,Helper::getPlaceholderLen($value->product->product_name,38).Helper::getPlaceholderLen($orderProduct->amount." X ".$value->product->product_unit,10));	
                                        array_push($listData,"11".str_pad($orderProduct->amount."X".$orderProduct->product->product_unit,8," ").  Helper::setProductName($orderProduct->product->product_name,12,8));	
                                        array_push($listData,"br");

                                        $orderProductTastes = OrderTaste::model()->with('taste')->findAll('t.order_id=:orderid and t.dpid=:dpid and t.is_order=0',  array(':orderid'=>$orderProduct->lid,':dpid'=>$orderProduct->dpid));
                                        $orderProductTasteEx = $orderProduct->taste_memo;                
                                        $strTaste= yii::t('app',"单品口味：").$orderProductTasteEx;
                                        $existTaste=0;
				                        $productStatus="";
				                        if($orderProduct->product_status=="1"){
				                        	$productStatus="等叫！！！";
				                        }elseif ($orderProduct->product_status=="2"){
				                        	$productStatus="加急！！！";
				                        }
				                        $strStatus=yii::t('app',"状态：").$productStatus;
				                        if(!empty($productStatus)){
				                        	array_push($listData,"11".$strStatus);
				                        	array_push($listData,"br");
				                        }
                                        if(!empty($orderProductTasteEx))
                                        {
                                            $existTaste=1;
                                        }
                                        foreach($orderProductTastes as $orderProductTaste){
                                            $strTaste.= '/'.$orderProductTaste->taste->name;
                                            $existTaste=1;
                                        }
                                        if($existTaste==1)
                                        {
                                            array_push($listData,"11".$strTaste);
                                            array_push($listData,"br");
                                        }
                                        array_push($listData,"00".str_pad('',48,'-'));
                                    }
                                    $orderTastes=  OrderTaste::model()->with('taste')->findAll('t.order_id=:orderid and t.dpid=:dpid and t.is_order=1',  array(':orderid'=>$order->lid,':dpid'=>$order->dpid));
                                    $orderTasteEx = $order->taste_memo;                
                                    array_push($listData,"00".str_pad('',48,'-'));
                                    $strAllTaste= yii::t('app',"全单口味：").$orderTasteEx;
                                    $existTaste=0;
                                    if(!empty($orderTasteEx))
                                    {
                                        $existTaste=1;
                                    }
                                    foreach($orderTastes as $orderTaste){
                                       $strAllTaste.= '/'.$orderTaste->taste->name;
                                       $existTaste=1;
                                    }
                                    if($existTaste==1)
                                    {
                                        array_push($listData,"11".$strAllTaste);
                                        array_push($listData,"br");
                                        array_push($listData,"00".str_pad('',48,'-'));
                                    }

                                    array_push($listData,"00".yii::t('app','操作员：').$order->username."  "//Yii::app()->user->name."  "
                                            .date('Y-m-d H:i:s',time()));
                                    $precode="";
                                    //后面加切纸
                                    $sufcode="0A0A0A0A0A0A1D5601";                        
                                    //var_dump($listData);exit;
                                    $printret=array();
                                    $printserver="0";//0通过自己同步打印，1通过打印服务器打印
                                    
                                    //份数循环                                    
                                    for($i=0;$i<$printerway->list_no;$i++){             //////////////                           
                                        $printret=Helper::printConetent($printer,$listData,$precode,$sufcode,$printserver);
                                        //array_push($jobids,$printret['jobid']."_".$order->lid);//将所有单品的id链接上去，便于更新下单状态，打印成功后下单状态和打印状态变更，数量加1
                                        array_push($jobids,$printret['jobid']."_".$printret['address']."_".$productids);
                                        $productids="";
                                        if(!$printret['status'])
                                        {
                                            return array('status'=>false,'allnum'=>count($jobids),'msg'=>$printret['msg']);
                                        }
                                    }                                    
                                    //return $printret;
                            }
                        }else{ ////如果不是整体，分开打印    //////////////
                            foreach ($printer2orderproducts_a as $key=>$values) {
                                
                                    $printer = $printers_a[$key];
                                    $productids="";
                                    //$listData = array("22".Helper::getPlaceholderLenBoth($order->company->company_name, 16));//
                                    //组装头
                                    $listDataHeader = array("22".Helper::setPrinterTitle($order->company->company_name,8));
                                    array_push($listData,"br");
                                    //array_push($listData,"22"."---分菜单---"); 
                                    array_push($listData,"22"."<".$printerway->name.">");
                                    array_push($listDataHeader,"00");
                                    array_push($listDataHeader,"br");

                                    if($reprint)
                                    {
                                        $strreprint=yii::t('app',"*****重复厨打，请留意！！！");
                                        array_push($listDataHeader,"11".$strreprint);
                                    }
                                    array_push($listDataHeader,"br");
                                    $strSite="";
                                    if($order->is_temp=='1')
                                    {
                                        array_push($listDataHeader,"00".yii::t('app','临时座：'));
                                        array_push($listDataHeader,"11".$siteNo->site_id%1000);
                                    }else{
                                        array_push($listDataHeader,"00".yii::t('app','座号：'));
                                        array_push($listDataHeader,"11".$site->siteType->name.' '.$site->serial);
                                    }
                                    array_push($listDataHeader,"00".yii::t('app','人数：').$order->number);

                                    if(!empty($order->callno))
                                    {
                                        array_push($listDataHeader,"00"."  ".yii::t('app','呼叫号：'));
                                        array_push($listDataHeader,"11".$order->callno);
                                    }
                                    array_push($listDataHeader,"br");
                                    array_push($listDataHeader,"00".str_pad('',48,'-'));
                                    //组装尾部
                                    $orderTastes=  OrderTaste::model()->with('taste')->findAll('t.order_id=:orderid and t.dpid=:dpid and t.is_order=1',  array(':orderid'=>$order->lid,':dpid'=>$order->dpid));
                                    $orderTasteEx = $order->taste_memo; 
                                    $listDataTail =array("00".str_pad('',48,'-')); 
                                    //array_push($listData,"00".str_pad('',48,'-'));
                                    $strAllTaste= yii::t('app',"全单口味：").$orderTasteEx;
                                    $existTaste=0;
                                    if(!empty($orderTasteEx))
                                    {
                                        $existTaste=1;
                                    }
                                    foreach($orderTastes as $orderTaste){
                                       $strAllTaste.= '/'.$orderTaste->taste->name;
                                       $existTaste=1;
                                    }
                                    if($existTaste==1)
                                    {
                                        array_push($listDataTail,"11".$strAllTaste);
                                        array_push($listDataTail,"br");
                                        array_push($listDataTail,"00".str_pad('',48,'-'));
                                    }

                                    array_push($listDataTail,"00".yii::t('app','操作员：').$order->username."  "//Yii::app()->user->name."  "
                                            .date('Y-m-d H:i:s',time()));
                                    //生成body并打印
                                    $productids="";
                                    foreach($values as $value)
                                    {
                                        $listDataBody= array();
                                        //组装身体
                                        //$productids="";
                                        if(empty($productids))
                                        {
                                            $productids.=$value;
                                        }else{
                                            $productids.=",".$value;
                                        }
                                        $orderProduct=$orderproducts_a[$value];
                                        if($orderProduct->amount<1)
                                        {
                                            continue;
                                        }
                                        //array_push($listData,Helper::getPlaceholderLen($value->product->product_name,38).Helper::getPlaceholderLen($orderProduct->amount." X ".$value->product->product_unit,10));	
                                        array_push($listDataBody,"11".str_pad($orderProduct->amount."X".$orderProduct->product->product_unit,8," ").  Helper::setProductName($orderProduct->product->product_name,12,8));	
                                        array_push($listDataBody,"br");

                                        $orderProductTastes = OrderTaste::model()->with('taste')->findAll('t.order_id=:orderid and t.dpid=:dpid and t.is_order=0',  array(':orderid'=>$orderProduct->lid,':dpid'=>$orderProduct->dpid));
                                        $orderProductTasteEx = $orderProduct->taste_memo;                
                                        $strTaste= yii::t('app',"单品口味：").$orderProductTasteEx;
                                        $existTaste=0;
                                        $productStatus="";
                                        if($orderProduct->product_status=="1"){
                                        	$productStatus="等叫！！！";
                                        }elseif ($orderProduct->product_status=="2"){
                                        	$productStatus="加急！！！";
                                        }
                                        $strStatus=yii::t('app',"状态：").$productStatus;
                                        if(!empty($productStatus)){
                                        	array_push($listDataBody,"11".$strStatus);
                                        	array_push($listDataBody,"br");
                                        }
                                        if(!empty($orderProductTasteEx))
                                        {
                                            $existTaste=1;
                                        }
                                        foreach($orderProductTastes as $orderProductTaste){
                                            $strTaste.= '/'.$orderProductTaste->taste->name;
                                            $existTaste=1;
                                        }
                                        if($existTaste==1)
                                        {
                                            array_push($listDataBody,"11".$strTaste);
                                            array_push($listDataBody,"br");
                                        }
                                        $listData=  array_merge($listDataHeader,$listDataBody,$listDataTail);                                        
                                        $precode="";
                                        //后面加切纸
                                        $sufcode="0A0A0A0A0A0A1D5601";                        
                                        //var_dump($listData);exit;
                                        $printret=array();
                                        $printserver="0";  ///自己去轮询
                                        //份数循环
                                        for($i=0;$i<$printerway->list_no;$i++){             //////////////                           
                                            $printret=Helper::printConetent($printer,$listData,$precode,$sufcode,$printserver);
                                            //array_push($jobids,$printret['jobid']."_".$order->lid);//将所有单品的id链接上去，便于更新下单状态，打印成功后下单状态和打印状态变更，数量加1
                                            array_push($jobids,$printret['jobid']."_".$printret['address']."_".$productids);
                                            $productids="";
                                            if(!$printret['status'])
                                            {
                                                return array('status'=>false,'allnum'=>count($jobids),'msg'=>$printret['msg']);
                                            }
                                        }  
                                    }                               
                                    //return array('status'=>true,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试n");
                            }
                        }
                } 
                //return array('status'=>true,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试13");                        
                //var_dump(json_encode($jobids));exit;
//                Gateway::getOnlineStatus();
//                $store = Store::instance('wymenu');
                $store=new Memcache;
                $store->connect(Yii::app()->params['memcache']['server'],Yii::app()->params['memcache']['port']);
                $store->set("kitchenjobs_".$order->dpid."_".$order->lid,json_encode($jobids),0,300);
                $store->close();
                $ret=array('status'=>true,'orderid'=>$order->lid,'dpid'=>$order->dpid,'allnum'=>count($jobids),'msg'=>'打印任务正常发布',"jobs"=>$jobids);
                //return array('status'=>true,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试14");
                //更新菜品状态为已打印
                $sqlorderproduct="update nb_order_product set is_print='1' where dpid=".$order->dpid." and order_id =".$order->lid;
                $commandorderproduct=Yii::app()->db->createCommand($sqlorderproduct);
                $commandorderproduct->execute();
                
                return $ret;
	}
        
        //2015/9/4更新
        //在2的基础上将同一个打印机的任务一次输出，减少打印机的连接请求
	static public function printKitchenAll3(Order $order,$orderList,Site $site,  SiteNo $siteNo , $reprint){		
                $printers_a=array();
                $orderproducts_a=array();
                $printer2orderproducts_a=array();
                $jobids=array();
                $printercontent_a=array();
                //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"dddd");        
                //$orderProductTastes = OrderTaste::model()->with('taste')->findAll('t.order_id=:orderid and t.dpid=:dpid and t.is_order=0',  array(':orderid'=>$orderProduct->lid,':dpid'=>$orderProduct->dpid));
                //$orderProductTasteEx = $orderProduct->taste_memo;
                //var_dump($orderProductTasteEx);exit;
                //$site = Site::model()->find('lid=:lid and dpid=:dpid',  array(':lid'=>$order->site_id,':dpid'=>$order->dpid));
		//$siteType = SiteType::model()->find('lid=:lid and dpid=:dpid',  array(':lid'=>$site->type_id,':dpid'=>$order->dpid));
		//var_dump($site->floor_id,$orderProduct->product->printer_way_id);exit;
                $floor_id='0';
                if($order->is_temp=='0')
                {
                    $floor_id=$site->floor_id;
                }
                $orderProducts = OrderProduct::model()->with('product')->findAll(' t.order_id in ('.$orderList.') and t.dpid='.$order->dpid.' and t.is_print=0 and t.delete_flag=0 ');//CF
                if(empty($orderProducts)) 
                {
                    return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"noorderproduct");//yii::t('app','没有要打印的菜品！')
                }
                //foreach printer_way //传菜厨打、整单厨打、配菜和制作厨打
                $printerways= PrinterWay::model()->findAll(" dpid = :dpid and delete_flag=0",array(':dpid'=>$order->dpid));
                if(empty($printerways))
                {
                    return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"没有打印方案");
                }
                //var_dump($printerways);exit;
                foreach($printerways as $printerway)
                {
                    $printer2orderproducts_a=array();
                        foreach($orderProducts as $orderProduct)
                        {
                            $orderproducts_a[$orderProduct->lid]=$orderProduct;
                            
                            $productprinterwaynow=  ProductPrinterway::model()->find("dpid=:dpid and printer_way_id=:pwi and product_id=:pid",array(':dpid'=>$order->dpid,':pwi'=>$printerway->lid,':pid'=>$orderProduct->product_id));
                            //var_dump($printerway->lid,$productprinterwaynow);exit;
                            if(!empty($productprinterwaynow))
                            {
                                //不是每个产品都对应所有打印方案
//                                return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"部分产品没有设置打印方案");
//                            }else{
                                $printwaydetails = PrinterWayDetail::model()->findAll('floor_id=:floorid and print_way_id=:pwi and dpid=:dpid and delete_flag=0',array(':floorid'=>$floor_id,':pwi'=>$printerway->lid,':dpid'=>$order->dpid));
                                foreach ($printwaydetails as $printway) {
                                    $printer = Printer::model()->find('lid=:printerId and dpid=:dpid',  array(':printerId'=>$printway->printer_id,':dpid'=>$order->dpid));
                                    if(empty($printer)) {
                                            return array('status'=>false,'dpid'=>$printer->dpid,'allnum'=>"0",'type'=>'none','msg'=>yii::t('app','打印方案没有设置厨房打印机'));		
                                    }
                                    if(!array_key_exists($printer->lid, $printers_a))
                                    {
                                        $printers_a[$printer->lid]=$printer; //add isonpaper listno
                                    }
                                    if(array_key_exists($printer->lid, $printer2orderproducts_a))
                                    {
                                        array_push($printer2orderproducts_a[$printer->lid],$orderProduct->lid);
                                    }else{
                                        $printer2orderproducts_a[$printer->lid]=array($orderProduct->lid);
                                    }
                                    if($printer->printer_type!='0') {
                                            return array('status'=>false,'dpid'=>$printer->dpid,'allnum'=>"0",'type'=>'none','msg'=>yii::t('app','厨打打印机必须是网络打印机'));		
                                    }
                                }
                            }
                        }                        
                        //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试3");
                        //如果是整体，
//                        if(empty($printer2orderproducts_a))
//                        {
//                            return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"没有找到打印机和产品关系");
//                        }
                        if($printerway->is_onepaper=="1")
                        {
                            foreach ($printer2orderproducts_a as $key=>$values) {
                                
                                    $printer = $printers_a[$key];
                                    $productids="";
                                    //$listData = array("22".Helper::getPlaceholderLenBoth($order->company->company_name, 16));//
                                    $listData = array("22".Helper::setPrinterTitle($order->company->company_name,8));
                                    array_push($listData,"br");
                                    array_push($listData,"br");
                                    //array_push($listData,"22"."+++总单+++"); 
                                    //array_push($listData,"22"."<".$printerway->name.">");
                                    array_push($listData,"10".Helper::setPrinterTitle($printerway->name,12));
                                    array_push($listData,"00");
                                    array_push($listData,"br");
                                    array_push($listData,"br");
                                    if($reprint)
                                    {
                                        $strreprint=yii::t('app',"*****重复厨打，请留意！！！");
                                        array_push($listData,"10".$strreprint);
                                        array_push($listData,"br");
                                    }
                                    
                                    $strSite="";
//                                    if($order->is_temp=='1')
//                                    {
//                                        array_push($listData,"00".yii::t('app','临时座：'));
//                                        array_push($listData,"11".$siteNo->site_id%1000);
//                                    }else{
//                                        array_push($listData,"00".yii::t('app','座号：'));
//                                        array_push($listData,"11".$site->siteType->name.' '.$site->serial);
//                                    }
//                                    array_push($listData,"00".yii::t('app','人数：').$order->number);
//                                    //return array('status'=>true,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试1");
//                                    if(!empty($order->callno))
//                                    {
//                                        array_push($listData,"00"."  ".yii::t('app','呼叫号：'));
//                                        array_push($listData,"11".$order->callno);
//                                    }
//                                    array_push($listData,"br");
//                                    array_push($listData,"00".str_pad('',48,'-'));
                                    if($order->is_temp=='1')
                                    {
                                        array_push($listData,"10".yii::t('app','临时座：'));
                                        array_push($listData,"11".$siteNo->site_id%1000);
                                    }else{
                                        array_push($listData,"10".yii::t('app','座号：'));
                                        array_push($listData,"11".$site->siteType->name.' '.$site->serial);
                                    }
                                    array_push($listData,"br");
                                    array_push($listData,"10".yii::t('app','人数：').$order->number);
                                    array_push($listData,"br");
                                    array_push($listData,"10"."下单时间：");
                                    array_push($listData,"00".$order->create_at);
                                    array_push($listData,"br");
                                    array_push($listData,"10"."账单号：");
                                    array_push($listData,"00".$order->account_no);
                                    array_push($listData,"br");
                                    //return array('status'=>true,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试1");
                                    array_push($listData,"00".str_pad('',48,'-'));
                                    array_push($listData,"10".str_pad('品名',16,' ').str_pad('数量 ',6,' ').str_pad('单位',4,' '));
                                    array_push($listData,"br");
                                    array_push($listData,"00".str_pad('',48,'-'));
                                    $productids="";
                                    $productnum=0;
                                    $productmoneyall=0;
                                    foreach($values as $value)
                                    {
                                        $productnum++;
                                        $productmoneyall=$productmoneyall+$orderProduct->price*$orderProduct->amount;
                                        if(empty($productids))
                                        {
                                            $productids.=$value;
                                        }else{
                                            $productids.=",".$value;
                                        }
                                        $orderProduct=$orderproducts_a[$value];
                                        if($orderProduct->amount<1)
                                        {
                                            continue;
                                        }
                                        $productStatus="";
                                        if($orderProduct->product_status=="1"){
                                        	$productStatus="[等]";
                                        }elseif ($orderProduct->product_status=="2"){
                                        	$productStatus="[急]";
                                        }
                                        //array_push($listData,Helper::getPlaceholderLen($value->product->product_name,38).Helper::getPlaceholderLen($orderProduct->amount." X ".$value->product->product_unit,10));	
                                        //array_push($listData,"11".str_pad($orderProduct->amount."X".$orderProduct->product->product_unit,8," ").  Helper::setProductName($orderProduct->product->product_name,12,8));	
                                        if($orderProduct->product_type=="0"){
	                                        $printlen=(strlen($orderProduct->product->product_name) + mb_strlen($orderProduct->product->product_name,'UTF8')) / 2;
	                                        if(!empty($productStatus)){
	                                        	array_push($listData,"01".$productnum.".".$productStatus.$orderProduct->product->product_name.str_pad("",28-$printlen," ").str_pad($orderProduct->amount,4," ").$orderProduct->product->product_unit);	
	                                        }else{
	                                        	array_push($listData,"01".$productnum.".".$productStatus.$orderProduct->product->product_name.str_pad("",32-$printlen," ").str_pad($orderProduct->amount,4," ").$orderProduct->product->product_unit);
	                                        }
                                        }elseif($orderProduct->product_type=="1"){
                                        	$printlen=(strlen("餐位费") + mb_strlen("餐位费",'UTF8')) / 2;
                                        	if(!empty($productStatus)){
                                        		array_push($listData,"01".$productnum.".".$productStatus."餐位费".str_pad("",28-$printlen," ").str_pad($orderProduct->amount,4," ").$orderProduct->product->product_unit);
                                        	}else{
                                        		array_push($listData,"01".$productnum.".".$productStatus."餐位费".str_pad("",32-$printlen," ").str_pad($orderProduct->amount,4," ").$orderProduct->product->product_unit);
                                        	}
                                        }elseif($orderProduct->product_type=="2"){
                                        	$printlen=(strlen("送餐费") + mb_strlen("送餐费",'UTF8')) / 2;
                                        	if(!empty($productStatus)){
                                        		array_push($listData,"01".$productnum.".".$productStatus."送餐费".str_pad("",28-$printlen," ").str_pad($orderProduct->amount,4," ").$orderProduct->product->product_unit);
                                        	}else{
                                        		array_push($listData,"01".$productnum.".".$productStatus."送餐费".str_pad("",32-$printlen," ").str_pad($orderProduct->amount,4," ").$orderProduct->product->product_unit);
                                        	}
                                        }elseif($orderProduct->product_type=="3"){
                                        	$printlen=(strlen("打包费") + mb_strlen("打包费",'UTF8')) / 2;
                                        	if(!empty($productStatus)){
                                        		array_push($listData,"01".$productnum.".".$productStatus."打包费".str_pad("",28-$printlen," ").str_pad($orderProduct->amount,4," ").$orderProduct->product->product_unit);
                                        	}else{
                                        		array_push($listData,"01".$productnum.".".$productStatus."打包费".str_pad("",32-$printlen," ").str_pad($orderProduct->amount,4," ").$orderProduct->product->product_unit);
                                        	}
                                        }else{
                                        $printlen=(strlen($orderProduct->product->product_name) + mb_strlen($orderProduct->product->product_name,'UTF8')) / 2;
	                                        if(!empty($productStatus)){
	                                        	array_push($listData,"01".$productnum.".".$productStatus.$orderProduct->product->product_name.str_pad("",28-$printlen," ").str_pad($orderProduct->amount,4," ").$orderProduct->product->product_unit);	
	                                        }else{
	                                        	array_push($listData,"01".$productnum.".".$productStatus.$orderProduct->product->product_name.str_pad("",32-$printlen," ").str_pad($orderProduct->amount,4," ").$orderProduct->product->product_unit);
	                                        }
                                        }
                                        array_push($listData,"br");
                                        $orderProductTastes = OrderTaste::model()->with('taste')->findAll('t.order_id=:orderid and t.dpid=:dpid and t.is_order=0',  array(':orderid'=>$orderProduct->lid,':dpid'=>$orderProduct->dpid));
                                        $orderProductTasteEx = $orderProduct->taste_memo;                
                                        $strTaste= yii::t('app',"备注：").$orderProductTasteEx;
                                        $existTaste=0;
                                        
//                                         $strStatus=yii::t('app',"状态：").$productStatus;
//                                         if(!empty($productStatus)){
//                                         	array_push($listData,"00".$strStatus);
//                                         	array_push($listData,"br");
//                                         }
                                        if(!empty($orderProductTasteEx))
                                        {
                                            $existTaste=1;
                                        }
                                        
                                        foreach($orderProductTastes as $orderProductTaste){
                                            $strTaste.= '/'.$orderProductTaste->taste->name;
                                            $existTaste=1;
                                        }
                                        if($existTaste==1)
                                        {
                                            array_push($listData,"00".$strTaste);
                                            array_push($listData,"br");
                                        }
                                        array_push($listData,"00".str_pad('',48,'-'));
                                    }
                                    //array_push($listData,"10"."金额合计：  ".number_format($productmoneyall,2));//."  "
                                    //array_push($listData,"br");
                                    //array_push($listData,"00".str_pad('',48,'-'));
                                    $orderTastes=  OrderTaste::model()->with('taste')->findAll('t.order_id=:orderid and t.dpid=:dpid and t.is_order=1',  array(':orderid'=>$order->lid,':dpid'=>$order->dpid));
                                    $orderTasteEx = $order->taste_memo;                
                                    //array_push($listData,"00".str_pad('',48,'-'));
                                    $strAllTaste= yii::t('app',"全单口味：").$orderTasteEx;
                                    $existTaste=0;
                                    if(!empty($orderTasteEx))
                                    {
                                        $existTaste=1;
                                    }
                                    foreach($orderTastes as $orderTaste){
                                       $strAllTaste.= '/'.$orderTaste->taste->name;
                                       $existTaste=1;
                                    }
                                    if($existTaste==1)
                                    {
                                        array_push($listData,"00".$strAllTaste);
                                        array_push($listData,"br");
                                        array_push($listData,"00".str_pad('',48,'-'));
                                    }
                                    if(!empty($order->username))
                                    {
                                        array_push($listData,"10"."点单员：".$order->username);//."  "
                                        
                                    }else{
                                        array_push($listData,"10"."客人自助下单");//."  "
                                        
                                    }
                                    array_push($listData,"br");
                                    array_push($listData,"10"."点单时间：");
                                    array_push($listData,"00".date('Y-m-d H:i:s',time()));

//                                    array_push($listData,"00".yii::t('app','操作员：').$order->username."  "//Yii::app()->user->name."  "
//                                            .date('Y-m-d H:i:s',time()));
//                                    $precode="";
//                                    //后面加切纸
//                                    $sufcode="0A0A0A0A0A0A1D5601";                        
//                                    //var_dump($listData);exit;
//                                    $printret=array();
//                                    $printserver="0";//0通过自己同步打印，1通过打印服务器打印
//                                    
//                                    //份数循环                                    
//                                    for($i=0;$i<$printerway->list_no;$i++){             //////////////                           
//                                        $printret=Helper::printConetent($printer,$listData,$precode,$sufcode,$printserver);
//                                        //array_push($jobids,$printret['jobid']."_".$order->lid);//将所有单品的id链接上去，便于更新下单状态，打印成功后下单状态和打印状态变更，数量加1
//                                        array_push($jobids,$printret['jobid']."_".$printret['address']."_".$productids);
//                                        $productids="";
//                                        if(!$printret['status'])
//                                        {
//                                            return array('status'=>false,'allnum'=>count($jobids),'msg'=>$printret['msg']);
//                                        }
//                                    }                                    
                                    //return $printret;
                                    /////尝试用整体打印$printercontent_a
                                    for($i=0;$i<$printerway->list_no;$i++){
                                        if(array_key_exists($key, $printercontent_a))
                                        {
                                            array_push($printercontent_a[$key],$listData);
                                        }else{
                                            $printercontent_a[$key]=array($listData);
                                        }                                        
                                    }                                    
                            }
                        }else{ ////如果不是整体，分开打印    //////////////
                            foreach ($printer2orderproducts_a as $key=>$values) {
                                    //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试1");
                                    
                                    $printer = $printers_a[$key];
                                    foreach($values as $value){
                                    $productids="";
                                    //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试2");
                                    //组装头
                                    if($orderProduct->product_status=="1"){
                                        	$productStatus="等叫";
                                        	$listDataHeader = array("22".Helper::setPrinterTitle($productStatus,8));
                                   
                                        }elseif ($orderProduct->product_status=="2"){
                                        	$productStatus="加急";
                                        	$listDataHeader = array("22".Helper::setPrinterTitle($productStatus,8));
                                   
                                        }else{
                                    $listDataHeader = array("22".Helper::setPrinterTitle($order->company->company_name,8));
                                        }
                                    //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试3");
                                    array_push($listDataHeader,"br");
                                    array_push($listDataHeader,"00"."<".$printerway->name.">");
                                    array_push($listDataHeader,"00");
                                    array_push($listDataHeader,"br");                                    
                                    
                                    if($reprint)
                                    {
                                        $strreprint=yii::t('app',"*****重复厨打，请留意！！！");
                                        array_push($listDataHeader,"11".$strreprint);
                                    }
                                    array_push($listDataHeader,"br");
                                    $strSite="";
                                    if($order->is_temp=='1')
                                    {
                                        array_push($listDataHeader,"00".yii::t('app','临时座：'));
                                        array_push($listDataHeader,"11".$siteNo->site_id%1000);
                                    }else{
                                        array_push($listDataHeader,"00".yii::t('app','座号：'));
                                        array_push($listDataHeader,"11".$site->siteType->name.' '.$site->serial);
                                    }
                                    array_push($listDataHeader,"00".yii::t('app','人数：').$order->number);

                                    if(!empty($order->callno))
                                    {
                                        array_push($listDataHeader,"00"."  ".yii::t('app','呼叫号：'));
                                        array_push($listDataHeader,"11".$order->callno);
                                    }
                                    array_push($listDataHeader,"br");
                                    array_push($listDataHeader,"00".str_pad('',48,'-'));
                                    //组装尾部
                                    $orderTastes=  OrderTaste::model()->with('taste')->findAll('t.order_id=:orderid and t.dpid=:dpid and t.is_order=1',  array(':orderid'=>$order->lid,':dpid'=>$order->dpid));
                                    $orderTasteEx = $order->taste_memo; 
                                    $listDataTail =array("00".str_pad('',48,'-')); 
                                    $strAllTaste= yii::t('app',"全单口味：").$orderTasteEx;
                                    $existTaste=0;
                                    if(!empty($orderTasteEx))
                                    {
                                        $existTaste=1;
                                    }
                                    foreach($orderTastes as $orderTaste){
                                       $strAllTaste.= '/'.$orderTaste->taste->name;
                                       $existTaste=1;
                                    }
                                    if($existTaste==1)
                                    {
                                        array_push($listDataTail,"11".$strAllTaste);
                                        array_push($listDataTail,"br");
                                        array_push($listDataTail,"00".str_pad('',48,'-'));
                                    }

                                    array_push($listDataTail,"00".yii::t('app','操作员：').$order->username."  "//Yii::app()->user->name."  "
                                            .date('Y-m-d H:i:s',time()));
                                    //生成body并打印
                                    $productids="";
                                    //foreach($values as $value)
                                    //{
                                        $listDataBody= array();
                                        //组装身体
                                        //$productids="";
                                        if(empty($productids))
                                        {
                                            $productids.=$value;
                                        }else{
                                            $productids.=",".$value;
                                        }
                                        $orderProduct=$orderproducts_a[$value];
                                        if($orderProduct->amount<1)
                                        {
                                            continue;
                                        }
                                        $productcf=preg_replace("/\s/",'',$orderProduct->product->product_name);
                                        $printlen=(strlen($productcf) + mb_strlen($productcf,'UTF8')) / 2;
                                        //array_push($listData,Helper::getPlaceholderLen($value->product->product_name,38).Helper::getPlaceholderLen($orderProduct->amount." X ".$value->product->product_unit,10));	
                                        //array_push($listDataBody,"11".str_pad($orderProduct->amount."X".$orderProduct->product->product_unit,8," ").  Helper::setProductName($orderProduct->product->product_name,12,8));	
                                        array_push($listDataBody,"11".trim(Helper::setProductName($orderProduct->product->product_name,12,8)).str_pad("",20-$printlen," ").$orderProduct->amount.$orderProduct->product->product_unit);
                                        //array_push($listData,"11".$orderProduct->product->product_name.str_pad("",32-$printlen," ").str_pad($orderProduct->amount,2," ").$orderProduct->product->product_unit);
                                         
                                        array_push($listDataBody,"br");

                                        $orderProductTastes = OrderTaste::model()->with('taste')->findAll('t.order_id=:orderid and t.dpid=:dpid and t.is_order=0',  array(':orderid'=>$orderProduct->lid,':dpid'=>$orderProduct->dpid));
                                        $orderProductTasteEx = $orderProduct->taste_memo;                
                                        $strTaste= yii::t('app',"单品口味：").$orderProductTasteEx;
                                        $existTaste=0;
//                                     	$productStatus="";
//                                         if($orderProduct->product_status=="1"){
//                                         	$productStatus="等叫！！！";
//                                         }elseif ($orderProduct->product_status=="2"){
//                                         	$productStatus="加急！！！";
//                                         }
//                                         $strStatus=yii::t('app',"状态：").$productStatus;
//                                         if(!empty($productStatus)){
//                                         	array_push($listDataBody,"11".$strStatus);
//                                         	array_push($listDataBody,"br");
//                                         }
                                        if(!empty($orderProductTasteEx))
                                        {
                                            $existTaste=1;
                                        }
                                        foreach($orderProductTastes as $orderProductTaste){
                                            $strTaste.= '/'.$orderProductTaste->taste->name;
                                            $existTaste=1;
                                        }
                                        if($existTaste==1)
                                        {
                                            array_push($listDataBody,"11".$strTaste);
                                            array_push($listDataBody,"br");
                                        }
                                        $listData=  array_merge($listDataHeader,$listDataBody,$listDataTail);                                        
//                                        $precode="";
                                        //后面加切纸
//                                        $sufcode="0A0A0A0A0A0A1D5601";                        
//                                        //var_dump($listData);exit;
//                                        $printret=array();
//                                        $printserver="0";  ///自己去轮询
//                                        //份数循环
//                                        for($i=0;$i<$printerway->list_no;$i++){             //////////////                           
//                                            $printret=Helper::printConetent($printer,$listData,$precode,$sufcode,$printserver);
//                                            //array_push($jobids,$printret['jobid']."_".$order->lid);//将所有单品的id链接上去，便于更新下单状态，打印成功后下单状态和打印状态变更，数量加1
//                                            array_push($jobids,$printret['jobid']."_".$printret['address']."_".$productids);
//                                            $productids="";
//                                            if(!$printret['status'])
//                                            {
//                                                return array('status'=>false,'allnum'=>count($jobids),'msg'=>$printret['msg']);
//                                            }
//                                        }  
                                        for($i=0;$i<$printerway->list_no;$i++){
                                            if(array_key_exists($key, $printercontent_a))
                                            {
                                                array_push($printercontent_a[$key],$listData);
                                            }else{
                                                $printercontent_a[$key]=array($listData);
                                            }                                        
                                        }
                                    }                               
                                    //return array('status'=>true,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试n");
                            }
                        }
                } 
                //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试13");
                //整体打印
                $jobids2=array();
                $precode="";
                $sufcode="0A0A0A0A0A0A1D5601";                        
                //var_dump($listData);exit;
                $printret=array();
                $printserver="0";  ///自己去轮询
                //份数循环
                foreach ($printercontent_a as $key=>$values) {             //////////////                           
                    //$printret=Helper::printConetent($printer,$listData,$precode,$sufcode,$printserver);
                    $printer2 = $printers_a[$key];
                    //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"before printConetent2");
                    $printret=Helper::printConetent2($printer2,$values,$precode,$sufcode,$printserver,$order->lid);
                    //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"after printConetent2");
                    //array_push($jobids,$printret['jobid']."_".$order->lid);//将所有单品的id链接上去，便于更新下单状态，打印成功后下单状态和打印状态变更，数量加1
                    if(!$printret['status'])
                    {
                        return array('status'=>false,'allnum'=>count($jobids),'msg'=>$printret['msg']);
                    }
                    array_push($jobids2,"0_".$printret['jobid']."_".$printret['address']);
                    
                }               
                //var_dump(json_encode($jobids));exit;
                //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>count($jobids2),'type'=>'none','msg'=>"测试14".count($jobids2));
//                Gateway::getOnlineStatus();
//                $store = Store::instance('wymenu');
                //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"memcache初始化失败");
                try{
                    $store=new Memcache;
                    $store->connect(Yii::app()->params['memcache']['server'],Yii::app()->params['memcache']['port']);                
                    $store->set("kitchenjobs_".$order->dpid."_".$order->lid,json_encode($jobids2),0,300);    
                    $store->close();
                }  catch (Exception $e)
                {
                    return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"memcache初始化失败444");        
                }
                $ret=array('status'=>true,'orderid'=>$order->lid,'dpid'=>$order->dpid,'allnum'=>count($jobids2),'msg'=>'打印任务正常发布',"jobs"=>$jobids2);
                //return array('status'=>true,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试14");
                //更新菜品状态为已打印
                $sqlorderproduct="update nb_order_product set is_print='1' where dpid=".$order->dpid." and order_id in (".$orderList.")";
                $commandorderproduct=Yii::app()->db->createCommand($sqlorderproduct);
                $commandorderproduct->execute();
                
                return $ret;
	}
	
	
	//2016/3/9更新
	//在3的基础上修改，打印从微信端下单的已经付款的订单，以实现客人自助付款单的菜品厨打。
	static public function printKitchenAll8(Order $order,$orderList,Site $site, $reprint){
		$printers_a=array();
		$orderproducts_a=array();
		$printer2orderproducts_a=array();
		$jobids=array();
		$printercontent_a=array();
		//return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"dddd");
		//$orderProductTastes = OrderTaste::model()->with('taste')->findAll('t.order_id=:orderid and t.dpid=:dpid and t.is_order=0',  array(':orderid'=>$orderProduct->lid,':dpid'=>$orderProduct->dpid));
		//$orderProductTasteEx = $orderProduct->taste_memo;
		//var_dump($orderProductTasteEx);exit;
		//$site = Site::model()->find('lid=:lid and dpid=:dpid',  array(':lid'=>$order->site_id,':dpid'=>$order->dpid));
		//$siteType = SiteType::model()->find('lid=:lid and dpid=:dpid',  array(':lid'=>$site->type_id,':dpid'=>$order->dpid));
		//var_dump($site->floor_id,$orderProduct->product->printer_way_id);exit;
		$floor_id='0';
		if($order->is_temp=='0')
		{
			$floor_id=$site->floor_id;
		}
		$orderProducts = OrderProduct::model()->with('product')->findAll(' t.order_id in ('.$orderList.') and t.dpid='.$order->dpid.' and t.product_order_status=8 and t.is_print=0 and t.delete_flag=0 ');//CF
		if(empty($orderProducts))
		{
			return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"noorderproduct");//yii::t('app','没有要打印的菜品！')
		}
		//foreach printer_way //传菜厨打、整单厨打、配菜和制作厨打
		$printerways= PrinterWay::model()->findAll(" dpid = :dpid and delete_flag=0",array(':dpid'=>$order->dpid));
		if(empty($printerways))
		{
			return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"没有打印方案");
		}
		//var_dump($printerways);exit;
		foreach($printerways as $printerway)
		{
			$a = 0;
			$printer2orderproducts_a=array();
			foreach($orderProducts as $orderProduct)
			{
				$orderproducts_a[$orderProduct->lid]=$orderProduct;
	
				$productprinterwaynow=  ProductPrinterway::model()->find("dpid=:dpid and printer_way_id=:pwi and product_id=:pid",array(':dpid'=>$order->dpid,':pwi'=>$printerway->lid,':pid'=>$orderProduct->product_id));
				//var_dump($printerway->lid,$productprinterwaynow);exit;
				if(!empty($productprinterwaynow))
				{
					//不是每个产品都对应所有打印方案
					//                                return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"部分产品没有设置打印方案");
					//                            }else{
					$printwaydetails = PrinterWayDetail::model()->findAll('floor_id=:floorid and print_way_id=:pwi and dpid=:dpid and delete_flag=0',array(':floorid'=>$floor_id,':pwi'=>$printerway->lid,':dpid'=>$order->dpid));
					//var_dump($printwaydetails);
					foreach ($printwaydetails as $printway) {
						//$a .= $printway->floor_id.'[]';
						$printer = Printer::model()->find('lid=:printerId and dpid=:dpid',  array(':printerId'=>$printway->printer_id,':dpid'=>$order->dpid));
						if(empty($printer)) {
							return array('status'=>false,'dpid'=>$printer->dpid,'allnum'=>"0",'type'=>'none','msg'=>yii::t('app','打印方案没有设置厨房打印机'));
						}
						if(!array_key_exists($printer->lid, $printers_a))
						{
							$printers_a[$printer->lid]=$printer; //add isonpaper listno
						}
						if(array_key_exists($printer->lid, $printer2orderproducts_a))
						{
							array_push($printer2orderproducts_a[$printer->lid],$orderProduct->lid);
						}else{
							$printer2orderproducts_a[$printer->lid]=array($orderProduct->lid);
						}
						if($printer->printer_type!='0') {
							return array('status'=>false,'dpid'=>$printer->dpid,'allnum'=>"0",'type'=>'none','msg'=>yii::t('app','厨打打印机必须是网络打印机'));
						}
					}
				}
			}
			//return $a;
			if($printerway->is_onepaper=="1")
			{
				foreach ($printer2orderproducts_a as $key=>$values) {
	
					$printer = $printers_a[$key];
					$productids="";
					//$listData = array("22".Helper::getPlaceholderLenBoth($order->company->company_name, 16));//
					$listData = array("22".Helper::setPrinterTitle($order->company->company_name,8));
					array_push($listData,"br");
					array_push($listData,"br");
					//array_push($listData,"22"."+++总单+++");
					//array_push($listData,"22"."<".$printerway->name.">");
					array_push($listData,"10".Helper::setPrinterTitle($printerway->name,12));
					array_push($listData,"00");
					array_push($listData,"br");
					array_push($listData,"br");
					if($reprint)
					{
						$strreprint=yii::t('app',"*****重复厨打，请留意！！！");
						array_push($listData,"10".$strreprint);
						array_push($listData,"br");
					}
	
					$strSite="";
					if($order->is_temp=='1')
					{
						if($order->order_type=="2"){
							array_push($listData,"10".yii::t('app','微信外卖：'));
							array_push($listData,"11".$order->site_id%1000);
						}elseif($order->order_type=="3"){
							array_push($listData,"10".yii::t('app','预约自提：'));
							array_push($listData,"11".$order->site_id%1000);
						}else{
							array_push($listData,"10".yii::t('app','临时座：'));
							array_push($listData,"11".$order->site_id%1000);
						}
// 						array_push($listData,"10".yii::t('app','临时座：'));
// 						array_push($listData,"11".$order->site_id%1000);
					}else{
						array_push($listData,"10".yii::t('app','座号：'));
						array_push($listData,"11".$site->siteType->name.' '.$site->serial);
					}
					array_push($listData,"br");
					array_push($listData,"10".yii::t('app','人数：').$order->number);
					array_push($listData,"br");
					array_push($listData,"10"."下单时间：");
					array_push($listData,"00".$order->create_at);
					array_push($listData,"br");
					array_push($listData,"10"."账单号：");
					array_push($listData,"00".$order->account_no);
					array_push($listData,"br");
					//return array('status'=>true,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试1");
					array_push($listData,"00".str_pad('',48,'-'));
					array_push($listData,"10".str_pad('品名',16,' ').str_pad('数量 ',6,' ').str_pad('单位',4,' '));
					array_push($listData,"br");
					array_push($listData,"00".str_pad('',48,'-'));
					$productids="";
					$productnum=0;
					$productmoneyall=0;
					foreach($values as $value)
					{
						$productnum++;
						$productmoneyall=$productmoneyall+$orderProduct->price*$orderProduct->amount;
						if(empty($productids))
						{
							$productids.=$value;
						}else{
							$productids.=",".$value;
						}
						$orderProduct=$orderproducts_a[$value];
						if($orderProduct->amount<1)
						{
							continue;
						}
						$productStatus="";
						if($orderProduct->product_status=="1"){
							$productStatus="[等]";
						}elseif ($orderProduct->product_status=="2"){
							$productStatus="[急]";
						}
						//array_push($listData,Helper::getPlaceholderLen($value->product->product_name,38).Helper::getPlaceholderLen($orderProduct->amount." X ".$value->product->product_unit,10));
						//array_push($listData,"11".str_pad($orderProduct->amount."X".$orderProduct->product->product_unit,8," ").  Helper::setProductName($orderProduct->product->product_name,12,8));
						if($orderProduct->product_type=="0"){
							$printlen=(strlen(trim($orderProduct->product->product_name)) + mb_strlen(trim($orderProduct->product->product_name),'UTF8')) / 2;
							if(!empty($productStatus)){
								array_push($listData,"01".$productnum.".".$productStatus.$orderProduct->product->product_name.str_pad("",28-$printlen," ").str_pad($orderProduct->amount,4," ").$orderProduct->product->product_unit);
							}else{
								array_push($listData,"01".$productnum.".".$productStatus.$orderProduct->product->product_name.str_pad("",32-$printlen," ").str_pad($orderProduct->amount,4," ").$orderProduct->product->product_unit);
							}
						}elseif($orderProduct->product_type=="1"){
							$printlen=(strlen("餐位费") + mb_strlen("餐位费",'UTF8')) / 2;
							if(!empty($productStatus)){
								array_push($listData,"01".$productnum.".".$productStatus."餐位费".str_pad("",28-$printlen," ").str_pad($orderProduct->amount,4," ").$orderProduct->product->product_unit);
							}else{
								array_push($listData,"01".$productnum.".".$productStatus."餐位费".str_pad("",32-$printlen," ").str_pad($orderProduct->amount,4," ").$orderProduct->product->product_unit);
							}
						}elseif($orderProduct->product_type=="2"){
							$printlen=(strlen("送餐费") + mb_strlen("送餐费",'UTF8')) / 2;
							if(!empty($productStatus)){
								array_push($listData,"01".$productnum.".".$productStatus."送餐费".str_pad("",28-$printlen," ").str_pad($orderProduct->amount,4," ").$orderProduct->product->product_unit);
							}else{
								array_push($listData,"01".$productnum.".".$productStatus."送餐费".str_pad("",32-$printlen," ").str_pad($orderProduct->amount,4," ").$orderProduct->product->product_unit);
							}
						}elseif($orderProduct->product_type=="3"){
							$printlen=(strlen("打包费") + mb_strlen("打包费",'UTF8')) / 2;
							if(!empty($productStatus)){
								array_push($listData,"01".$productnum.".".$productStatus."打包费".str_pad("",28-$printlen," ").str_pad($orderProduct->amount,4," ").$orderProduct->product->product_unit);
							}else{
								array_push($listData,"01".$productnum.".".$productStatus."打包费".str_pad("",32-$printlen," ").str_pad($orderProduct->amount,4," ").$orderProduct->product->product_unit);
							}
						}else{
							$printlen=(strlen($orderProduct->product->product_name) + mb_strlen($orderProduct->product->product_name,'UTF8')) / 2;
							if(!empty($productStatus)){
								array_push($listData,"01".$productnum.".".$productStatus.$orderProduct->product->product_name.str_pad("",28-$printlen," ").str_pad($orderProduct->amount,4," ").$orderProduct->product->product_unit);
							}else{
								array_push($listData,"01".$productnum.".".$productStatus.$orderProduct->product->product_name.str_pad("",32-$printlen," ").str_pad($orderProduct->amount,4," ").$orderProduct->product->product_unit);
							}
						}
						array_push($listData,"br");
						$orderProductTastes = OrderTaste::model()->with('taste')->findAll('t.order_id=:orderid and t.dpid=:dpid and t.is_order=0',  array(':orderid'=>$orderProduct->lid,':dpid'=>$orderProduct->dpid));
						$orderProductTasteEx = $orderProduct->taste_memo;
						$strTaste= yii::t('app',"备注：").$orderProductTasteEx;
						$existTaste=0;
	
						//                                         $strStatus=yii::t('app',"状态：").$productStatus;
						//                                         if(!empty($productStatus)){
						//                                         	array_push($listData,"00".$strStatus);
						//                                         	array_push($listData,"br");
						//                                         }
						if(!empty($orderProductTasteEx))
						{
							$existTaste=1;
						}
	
						foreach($orderProductTastes as $orderProductTaste){
							$strTaste.= '/'.$orderProductTaste->taste->name;
							$existTaste=1;
						}
						if($existTaste==1)
						{
							array_push($listData,"00".$strTaste);
							array_push($listData,"br");
						}
						array_push($listData,"00".str_pad('',48,'-'));
					}
					//array_push($listData,"10"."金额合计：  ".number_format($productmoneyall,2));//."  "
					//array_push($listData,"br");
					//array_push($listData,"00".str_pad('',48,'-'));
					$orderTastes=  OrderTaste::model()->with('taste')->findAll('t.order_id=:orderid and t.dpid=:dpid and t.is_order=1',  array(':orderid'=>$order->lid,':dpid'=>$order->dpid));
					$orderTasteEx = $order->taste_memo;
					//array_push($listData,"00".str_pad('',48,'-'));
					$strAllTaste= yii::t('app',"全单口味：").$orderTasteEx;
					$existTaste=0;
					if(!empty($orderTasteEx))
					{
						$existTaste=1;
					}
					foreach($orderTastes as $orderTaste){
						$strAllTaste.= '/'.$orderTaste->taste->name;
						$existTaste=1;
					}
					if($existTaste==1)
					{
						array_push($listData,"00".$strAllTaste);
						array_push($listData,"br");
						array_push($listData,"00".str_pad('',48,'-'));
					}
					if(!empty($order->username))
					{
						array_push($listData,"10"."点单员：".$order->username);//."  "
	
					}else{
						array_push($listData,"10"."客人自助下单");//."  "
	
					}
					array_push($listData,"br");
					array_push($listData,"10"."点单时间：");
					array_push($listData,"00".date('Y-m-d H:i:s',time()));
	
					//return $printret;
					/////尝试用整体打印$printercontent_a
					for($i=0;$i<$printerway->list_no;$i++){
					if(array_key_exists($key, $printercontent_a))
					{
					array_push($printercontent_a[$key],$listData);
				}else{
				$printercontent_a[$key]=array($listData);
				}
				}
	}
	}else{ ////如果不是整体，分开打印    //////////////
		foreach ($printer2orderproducts_a as $key=>$values) {
		//return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试1");
	
		$printer = $printers_a[$key];
		foreach($values as $value){
		$productids="";
		//return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试2");
		//组装头
		if($orderProduct->product_status=="1"){
		$productStatus="等叫";
		$listDataHeader = array("22".Helper::setPrinterTitle($productStatus,8));
		 
					}elseif ($orderProduct->product_status=="2"){
						$productStatus="加急";
							$listDataHeader = array("22".Helper::setPrinterTitle($productStatus,8));
							 
					}else{
					$listDataHeader = array("22".Helper::setPrinterTitle($order->company->company_name,8));
					}
					//return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试3");
			array_push($listDataHeader,"br");
			array_push($listDataHeader,"00"."<".$printerway->name.">");
			array_push($listDataHeader,"00");
			array_push($listDataHeader,"br");
	
			if($reprint)
			{
			$strreprint=yii::t('app',"*****重复厨打，请留意！！！");
			array_push($listDataHeader,"11".$strreprint);
			}
			array_push($listDataHeader,"br");
			
			$strSite="";
			if($order->is_temp=='1')
					{
						if($order->order_type=="2"){
							array_push($listDataHeader,"10".yii::t('app','微信外卖：'));
							array_push($listDataHeader,"11".$order->site_id%1000);
						}elseif($order->order_type=="3"){
							array_push($listDataHeader,"10".yii::t('app','预约自提：'));
							array_push($listDataHeader,"11".$order->site_id%1000);
						}else{
							array_push($listDataHeader,"10".yii::t('app','临时座：'));
							array_push($listDataHeader,"11".$order->site_id%1000);
						}
// 						array_push($listDataHeader,"10".yii::t('app','临时座：'));
// 						array_push($listDataHeader,"11".$order->site_id%1000);
					}else{
						array_push($listDataHeader,"10".yii::t('app','座号：'));
						array_push($listDataHeader,"11".$site->siteType->name.' '.$site->serial);
					}
			array_push($listDataHeader,"00".yii::t('app','人数：').$order->number);
	
			if(!empty($order->callno))
			{
			array_push($listDataHeader,"00"."  ".yii::t('app','呼叫号：'));
			array_push($listDataHeader,"11".$order->callno);
			}
			array_push($listDataHeader,"br");
			array_push($listDataHeader,"00".str_pad('',48,'-'));
			//组装尾部
			$orderTastes=  OrderTaste::model()->with('taste')->findAll('t.order_id=:orderid and t.dpid=:dpid and t.is_order=1',  array(':orderid'=>$order->lid,':dpid'=>$order->dpid));
			$orderTasteEx = $order->taste_memo;
			$listDataTail =array("00".str_pad('',48,'-'));
			$strAllTaste= yii::t('app',"全单口味：").$orderTasteEx;
			$existTaste=0;
			if(!empty($orderTasteEx))
			{
			$existTaste=1;
					}
					foreach($orderTastes as $orderTaste){
					$strAllTaste.= '/'.$orderTaste->taste->name;
					$existTaste=1;
					}
					if($existTaste==1)
					{
					array_push($listDataTail,"11".$strAllTaste);
					array_push($listDataTail,"br");
						array_push($listDataTail,"00".str_pad('',48,'-'));
					}
	
								array_push($listDataTail,"00".yii::t('app','操作员：').$order->username."  "//Yii::app()->user->name."  "
								.date('Y-m-d H:i:s',time()));
								//生成body并打印
								$productids="";
								//foreach($values as $value)
									//{
									$listDataBody= array();
									//组装身体
									//$productids="";
									if(empty($productids))
									{
										$productids.=$value;
									}else{
											$productids.=",".$value;
					}
					$orderProduct=$orderproducts_a[$value];
					if($orderProduct->amount<1)
					{
					continue;
					}
					 $printlen=(strlen(trim($orderProduct->product->product_name)) + mb_strlen(trim($orderProduct->product->product_name),'UTF8')) / 2;
                     //array_push($listData,Helper::getPlaceholderLen($value->product->product_name,38).Helper::getPlaceholderLen($orderProduct->amount." X ".$value->product->product_unit,10));	
                     //array_push($listDataBody,"11".str_pad($orderProduct->amount."X".$orderProduct->product->product_unit,8," ").  Helper::setProductName($orderProduct->product->product_name,12,8));	
                     array_push($listDataBody,"11".trim(Helper::setProductName($orderProduct->product->product_name,12,8)).str_pad("",20-$printlen," ").$orderProduct->amount.$orderProduct->product->product_unit);
                     //array_push($listData,"11".$orderProduct->product->product_name.str_pad("",32-$printlen," ").str_pad($orderProduct->amount,2," ").$orderProduct->product->product_unit);
                     array_push($listDataBody,"br");
	
					$orderProductTastes = OrderTaste::model()->with('taste')->findAll('t.order_id=:orderid and t.dpid=:dpid and t.is_order=0',  array(':orderid'=>$orderProduct->lid,':dpid'=>$orderProduct->dpid));
					$orderProductTasteEx = $orderProduct->taste_memo;
					$strTaste= yii::t('app',"单品口味：").$orderProductTasteEx;
					$existTaste=0;
						//                                         }
						if(!empty($orderProductTasteEx))
						{
						$existTaste=1;
						}
							foreach($orderProductTastes as $orderProductTaste){
							$strTaste.= '/'.$orderProductTaste->taste->name;
							$existTaste=1;
						}
						if($existTaste==1)
						{
						array_push($listDataBody,"11".$strTaste);
						array_push($listDataBody,"br");
						}
							$listData=  array_merge($listDataHeader,$listDataBody,$listDataTail);
									for($i=0;$i<$printerway->list_no;$i++){
									if(array_key_exists($key, $printercontent_a))
									{
									array_push($printercontent_a[$key],$listData);
						}else{
						$printercontent_a[$key]=array($listData);
						}
						}
						}
						//return array('status'=>true,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试n");
						}
						}
						}
						//return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试13");
						//整体打印
								$jobids2=array();
								$precode="";
								$sufcode="0A0A0A0A0A0A1D5601";
								//var_dump($listData);exit;
                $printret=array();
										$printserver="0";  ///自己去轮询
										//份数循环
												foreach ($printercontent_a as $key=>$values) {             //////////////
												//$printret=Helper::printConetent($printer,$listData,$precode,$sufcode,$printserver);
												$printer2 = $printers_a[$key];
												//return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"before printConetent2");
												$printret=Helper::printConetent8($printer2,$values,$precode,$sufcode,$printserver,$order->lid);
												//return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"after printConetent2");
												//array_push($jobids,$printret['jobid']."_".$order->lid);//将所有单品的id链接上去，便于更新下单状态，打印成功后下单状态和打印状态变更，数量加1
												if(!$printret['status'])
												{
												return array('status'=>false,'allnum'=>count($jobids),'msg'=>$printret['msg']);
						}
						array_push($jobids2,"0_".$printret['jobid']."_".$printret['address']);
	
						}
						//var_dump(json_encode($jobids));exit;
												//return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>count($jobids2),'type'=>'none','msg'=>"测试14".count($jobids2));
												//                Gateway::getOnlineStatus();
												//                $store = Store::instance('wymenu');
												//return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"memcache初始化失败");
												try{
												$store=new Memcache;
													$store->connect(Yii::app()->params['memcache']['server'],Yii::app()->params['memcache']['port']);
													$store->set("kitchenjobs_".$order->dpid."_".$order->lid,json_encode($jobids2),0,300);
													$store->close();
						}  catch (Exception $e)
						{
						return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"memcache初始化失败444");
						}
						$ret=array('status'=>true,'orderid'=>$order->lid,'dpid'=>$order->dpid,'allnum'=>count($jobids2),'msg'=>'打印任务正常发布',"jobs"=>$jobids2);
						//return array('status'=>true,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试14");
						//更新菜品状态为已打印
						$sqlorderproduct="update nb_order_product set is_print='1' where dpid=".$order->dpid." and order_id in (".$orderList.") and product_order_status = 8";
						$commandorderproduct=Yii::app()->db->createCommand($sqlorderproduct);
						$commandorderproduct->execute();
	
						return $ret;
	}
        
        //2015/9/4更新
        //在2的基础上将同一个打印机的任务一次输出，减少打印机的连接请求
	static public function printKitchenOther(Order $order, $orderProducts,Site $site,  SiteNo $siteNo , $reprint,$memo){		
                $printers_a=array();
                $orderproducts_a=array();
                $printer2orderproducts_a=array();
                $jobids=array();
                $printercontent_a=array();
                //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>yii::t('app','没有要打印的菜品！'));
                //return array('status'=>true,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"dddd");        
                //$orderProductTastes = OrderTaste::model()->with('taste')->findAll('t.order_id=:orderid and t.dpid=:dpid and t.is_order=0',  array(':orderid'=>$orderProduct->lid,':dpid'=>$orderProduct->dpid));
                //$orderProductTasteEx = $orderProduct->taste_memo;
                //var_dump($orderProductTasteEx);exit;
                //$site = Site::model()->find('lid=:lid and dpid=:dpid',  array(':lid'=>$order->site_id,':dpid'=>$order->dpid));
		//$siteType = SiteType::model()->find('lid=:lid and dpid=:dpid',  array(':lid'=>$site->type_id,':dpid'=>$order->dpid));
		//var_dump($site->floor_id,$orderProduct->product->printer_way_id);exit;
                $floor_id='0';
                if($order->is_temp=='0')
                {
                    $floor_id=$site->floor_id;
                }
                //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>yii::t('app','没有要打印的菜品！'));
//                $orderProducts = OrderProduct::model()->with('product')->findAll('t.order_id=:id and t.dpid=:dpid and t.is_print=0 and t.delete_flag=0' , array(':id'=>$order->lid,':dpid'=>$order->dpid));
                if(empty($orderProducts)) 
                {
                    return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>yii::t('app','没有要打印的菜品！'));
                }
                //foreach printer_way //传菜厨打、整单厨打、配菜和制作厨打
                $printerways= PrinterWay::model()->findAll(" dpid = :dpid and delete_flag=0",array(':dpid'=>$order->dpid));
                if(empty($printerways))
                {
                    return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"没有打印方案".$order->dpid);
                }
                //var_dump($printerways);exit;
                foreach($printerways as $printerway)
                {
                    $printer2orderproducts_a=array();
                        foreach($orderProducts as $orderProduct)
                        {
                            $orderproducts_a[$orderProduct->lid]=$orderProduct;
                            
                            $productprinterwaynow=  ProductPrinterway::model()->find("dpid=:dpid and printer_way_id=:pwi and product_id=:pid",array(':dpid'=>$order->dpid,':pwi'=>$printerway->lid,':pid'=>$orderProduct->product_id));
                            //var_dump($printerway->lid,$productprinterwaynow);exit;
                            if(!empty($productprinterwaynow))
                            {
                                //不是每个产品都对应所有打印方案
//                                return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"部分产品没有设置打印方案");
//                            }else{
                                $printwaydetails = PrinterWayDetail::model()->findAll('floor_id=:floorid and print_way_id=:pwi and dpid=:dpid and delete_flag=0',array(':floorid'=>$floor_id,':pwi'=>$printerway->lid,':dpid'=>$order->dpid));
                                foreach ($printwaydetails as $printway) {
                                    $printer = Printer::model()->find('lid=:printerId and dpid=:dpid',  array(':printerId'=>$printway->printer_id,':dpid'=>$order->dpid));
                                    if(empty($printer)) {
                                            return array('status'=>false,'dpid'=>$printer->dpid,'allnum'=>"0",'type'=>'none','msg'=>yii::t('app','打印方案没有设置厨房打印机'));		
                                    }
                                    if(!array_key_exists($printer->lid, $printers_a))
                                    {
                                        $printers_a[$printer->lid]=$printer; //add isonpaper listno
                                    }
                                    if(array_key_exists($printer->lid, $printer2orderproducts_a))
                                    {
                                        array_push($printer2orderproducts_a[$printer->lid],$orderProduct->lid);
                                    }else{
                                        $printer2orderproducts_a[$printer->lid]=array($orderProduct->lid);
                                    }
                                    if($printer->printer_type!='0') {
                                            return array('status'=>false,'dpid'=>$printer->dpid,'allnum'=>"0",'type'=>'none','msg'=>yii::t('app','厨打打印机必须是网络打印机'));		
                                    }
                                }
                            }
                        }                        
                        //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试3");
                        //如果是整体，
//                        if(empty($printer2orderproducts_a))
//                        {
//                            return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"没有找到打印机和产品关系");
//                        }
                        if($printerway->is_onepaper=="1")
                        {
                            foreach ($printer2orderproducts_a as $key=>$values) {
                                //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试5");
                                    $printer = $printers_a[$key];
                                    $productids="";
                                    //$listData = array("22".Helper::getPlaceholderLenBoth($order->company->company_name, 16));//
                                    $listData = array("22".Helper::setPrinterTitle($order->company->company_name."退菜单",8));
                                    array_push($listData,"br");
                                    array_push($listData,"br");
                                    //array_push($listData,"22"."+++总单+++"); 
                                    array_push($listData,"10".Helper::setPrinterTitle($printerway->name,12));
                                    array_push($listData,"00");
                                    array_push($listData,"br");
                                    array_push($listData,"br");
                                    //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"zheng-memo:".$memo);
                                    $strSite="";
                                    if($order->is_temp=='1')
                                    {
                                        array_push($listData,"10".yii::t('app','临时座：'));
                                        array_push($listData,"11".$siteNo->site_id%1000);
                                    }else{
                                        array_push($listData,"10".yii::t('app','座号：'));
                                        array_push($listData,"11".$site->siteType->name.' '.$site->serial);
                                    }
                                    array_push($listData,"br");
                                    array_push($listData,"10".yii::t('app','人数：').$order->number);
                                    array_push($listData,"br");
                                    array_push($listData,"10"."下单时间：");
                                    array_push($listData,"00".$order->create_at);
                                    array_push($listData,"br");
                                    array_push($listData,"10"."账单号：");
                                    array_push($listData,"00".$order->account_no);
                                    array_push($listData,"br");
                                    //return array('status'=>true,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试1");
                                    array_push($listData,"00".str_pad('',48,'-'));
                                    array_push($listData,"10".str_pad('品名',16,' ').str_pad('数量 ',6,' ').str_pad('单位',4,' '));
                                    array_push($listData,"br");
                                    array_push($listData,"00".str_pad('',48,'-'));
                                    $productids="";
                                    //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试3");
                                    foreach($values as $value)
                                    {
                                        //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试3");
                                        if(empty($productids))
                                        {
                                            $productids.=$value;
                                        }else{
                                            $productids.=",".$value;
                                        }
                                        //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试3");
                                        $orderProduct=$orderproducts_a[$value];
                                        if($orderProduct->amount<1)
                                        {
                                            continue;
                                        }
                                        //array_push($listData,Helper::getPlaceholderLen($value->product->product_name,38).Helper::getPlaceholderLen($orderProduct->amount." X ".$value->product->product_unit,10));	
                                        //array_push($listData,"01".str_pad("-".$orderProduct->amount.$orderProduct->product->product_unit,8," ").Helper::setProductName($orderProduct->product->product_name,12,8));	
                                        array_push($listData,"01".str_pad($orderProduct->product->product_name,34," ").str_pad("-".$orderProduct->amount,6," ").str_pad($orderProduct->product->product_unit,8," "));	
                                        array_push($listData,"br");
                                        array_push($listData,"br");
                                        array_push($listData,"10"."原因:".$memo);
                                        array_push($listData,"br");
                                        //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试3".$memo);
                                        array_push($listData,"00".str_pad('',48,'-'));
                                    }
                                    if(!empty($order->username))
                                    {
                                        array_push($listData,"10"."点单员：".$order->username);//."  "
                                        array_push($listData,"br");
                                        array_push($listData,"10"."退菜员：".Yii::app()->user->name);//."  "
                                    }else{
                                        array_push($listData,"10"."客人自助下单");//."  "
                                        array_push($listData,"br");
                                        array_push($listData,"10"."退菜员：".Yii::app()->user->name);//."  "
                                    }
                                    array_push($listData,"br");
                                    array_push($listData,"10"."退菜时间：");
                                    array_push($listData,"00".date('Y-m-d H:i:s',time()));
                                    
                                    /////尝试用整体打印$printercontent_a
                                    //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试3");
                                    for($i=0;$i<$printerway->list_no;$i++){
                                        if(array_key_exists($key, $printercontent_a))
                                        {
                                            array_push($printercontent_a[$key],$listData);
                                        }else{
                                            $printercontent_a[$key]=array($listData);
                                        }                                        
                                    }                                    
                            }
                        }else{ ////如果不是整体，分开打印    //////////////
                            foreach ($printer2orderproducts_a as $key=>$values) {
                                //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试6");
                                    $printer = $printers_a[$key];
                                    $productids="";
                                    //$listData = array("22".Helper::getPlaceholderLenBoth($order->company->company_name, 16));//
                                    //组装头
//                                    $listDataHeader = array("22".Helper::setPrinterTitle($order->company->company_name."退菜单",8));
//                                    array_push($listDataHeader,"br");
//                                    //array_push($listData,"22"."---分菜单---"); 
//                                    array_push($listData,"10".Helper::setPrinterTitle($printerway->name,12));
//                                    array_push($listDataHeader,"01");
//                                    array_push($listDataHeader,"br");
//                                    //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"fen-memo3:".$memo);
//                                    $strSite="";
//                                    if($order->is_temp=='1')
//                                    {
//                                        array_push($listDataHeader,"01".yii::t('app','临时座：'));
//                                        array_push($listDataHeader,"11".$siteNo->site_id%1000);
//                                    }else{
//                                        array_push($listDataHeader,"01".yii::t('app','座号：'));
//                                        array_push($listDataHeader,"11".$site->siteType->name.' '.$site->serial);
//                                    }
//                                    array_push($listDataHeader,"01"."   ".yii::t('app','人数：').$order->number);
//                                    array_push($listDataHeader,"br");
//                                    //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试4".$memo);                                        
//                                    array_push($listDataHeader,"00".str_pad('',48,'-'));
                                    $listDataHeader = array("22".Helper::setPrinterTitle($order->company->company_name."退菜单",8));
                                    array_push($listDataHeader,"br");
                                    array_push($listDataHeader,"br");
                                    //array_push($listData,"22"."+++总单+++"); 
                                    array_push($listDataHeader,"10".Helper::setPrinterTitle($printerway->name,12));
                                    array_push($listDataHeader,"00");
                                    array_push($listDataHeader,"br");
                                    array_push($listDataHeader,"br");
                                    //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"zheng-memo:".$memo);
                                    $strSite="";
                                    if($order->is_temp=='1')
                                    {
                                        array_push($listDataHeader,"10".yii::t('app','临时座：'));
                                        array_push($listDataHeader,"11".$siteNo->site_id%1000);
                                    }else{
                                        array_push($listDataHeader,"10".yii::t('app','座号：'));
                                        array_push($listDataHeader,"11".$site->siteType->name.' '.$site->serial);
                                    }
                                    array_push($listDataHeader,"br");
                                    array_push($listDataHeader,"10".yii::t('app','人数：').$order->number);
                                    array_push($listDataHeader,"br");
                                    array_push($listDataHeader,"10"."下单时间：");
                                    array_push($listDataHeader,"00".$order->create_at);
                                    array_push($listDataHeader,"br");
                                    array_push($listDataHeader,"10"."账单号：");
                                    array_push($listDataHeader,"00".$order->account_no);
                                    array_push($listDataHeader,"br");
                                    //return array('status'=>true,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试1");
                                    array_push($listDataHeader,"00".str_pad('',48,'-'));
                                    array_push($listDataHeader,"10".str_pad('品名',16,' ').str_pad('数量 ',6,' ').str_pad('单位',4,' '));
                                    array_push($listDataHeader,"br");
                                    array_push($listDataHeader,"00".str_pad('',48,'-'));
                                    
                                    
                                    //组装尾部                                    
                                    $listDataTail=array();// =array("00".str_pad('',48,'-'));
//                                    if(!empty($order->username))
//                                    {
//                                        array_push($listDataTail,"01".'点单员：'.$order->username."  退菜员：".Yii::app()->user->name);//."  "
//                                    }else{
//                                        array_push($listDataTail,"01"."客人自助下单  退菜员：".Yii::app()->user->name);//."  "
//                                    }
//                                    array_push($listDataTail,"br");
//                                    array_push($listDataTail,"10".date('Y-m-d H:i:s',time()));
                                    
//                                    array_push($listDataTail,"00".yii::t('app','操作员：').$order->username."  "//Yii::app()->user->name."  "
//                                            .date('Y-m-d H:i:s',time()));
                                    //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试4".$memo);                                        
                                    if(!empty($order->username))
                                    {
                                        array_push($listDataTail,"10"."点单员：".$order->username);//."  "
                                        array_push($listDataTail,"br");
                                        array_push($listDataTail,"10"."退菜员：".Yii::app()->user->name);//."  "
                                    }else{
                                        array_push($listDataTail,"10"."客人自助下单");//."  "
                                        array_push($listDataTail,"br");
                                        array_push($listDataTail,"10"."退菜员：".Yii::app()->user->name);//."  "
                                    }
                                    array_push($listDataTail,"br");
                                    array_push($listDataTail,"10"."退菜时间：");
                                    array_push($listDataTail,"00".date('Y-m-d H:i:s',time()));                                   
                                    
                                    //生成body并打印
                                    $productids="";
                                    foreach($values as $value)
                                    {
                                        $listDataBody= array();
                                        //组装身体
                                        //$productids="";
                                        if(empty($productids))
                                        {
                                            $productids.=$value;
                                        }else{
                                            $productids.=",".$value;
                                        }
                                        $orderProduct=$orderproducts_a[$value];
                                        if($orderProduct->amount<1)
                                        {
                                            continue;
                                        }
                                        //array_push($listData,Helper::getPlaceholderLen($value->product->product_name,38).Helper::getPlaceholderLen($orderProduct->amount." X ".$value->product->product_unit,10));	
//                                        array_push($listDataBody,"01".str_pad("-".$orderProduct->amount.$orderProduct->product->product_unit,8," ").Helper::setProductName($orderProduct->product->product_name,12,8));	
//                                        array_push($listDataBody,"br");
//                                        array_push($listDataBody,"10"."原因:".$memo);
//                                        array_push($listDataBody,"br");
                                        //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试4".$memo);                                        
                                        if($orderProduct->product_type=="0"){
                                        	array_push($listDataBody,"01".str_pad($orderProduct->product->product_name,34," ").str_pad("-".$orderProduct->amount,6," ").str_pad($orderProduct->product->product_unit,8," "));	
                                        }else{
                                        	array_push($listDataBody,"01".str_pad($orderProduct->product_name,34," ").str_pad("-".$orderProduct->amount,6," ").str_pad($orderProduct->product->product_unit,8," "));
                                        }
                                        array_push($listDataBody,"br");
                                        array_push($listDataBody,"br");
                                        array_push($listDataBody,"10"."原因:".$memo);
                                        array_push($listDataBody,"br");
                                        //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试3".$memo);
                                        array_push($listDataBody,"00".str_pad('',48,'-'));
                                        $listData=  array_merge($listDataHeader,$listDataBody,$listDataTail);                                        
//                                        $precode="";
                                        //后面加切纸
//                                        $sufcode="0A0A0A0A0A0A1D5601";                        
//                                        //var_dump($listData);exit;
//                                        $printret=array();
//                                        $printserver="0";  ///自己去轮询
//                                        //份数循环
//                                        for($i=0;$i<$printerway->list_no;$i++){             //////////////                           
//                                            $printret=Helper::printConetent($printer,$listData,$precode,$sufcode,$printserver);
//                                            //array_push($jobids,$printret['jobid']."_".$order->lid);//将所有单品的id链接上去，便于更新下单状态，打印成功后下单状态和打印状态变更，数量加1
//                                            array_push($jobids,$printret['jobid']."_".$printret['address']."_".$productids);
//                                            $productids="";
//                                            if(!$printret['status'])
//                                            {
//                                                return array('status'=>false,'allnum'=>count($jobids),'msg'=>$printret['msg']);
//                                            }
//                                        }  
                                        for($i=0;$i<$printerway->list_no;$i++){
                                            if(array_key_exists($key, $printercontent_a))
                                            {
                                                array_push($printercontent_a[$key],$listData);
                                            }else{
                                                $printercontent_a[$key]=array($listData);
                                            }                                        
                                        }
                                    }                               
                                    //return array('status'=>true,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试n");
                            }
                        }
                } 
                //return array('status'=>true,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"13");
                //整体打印
                $jobids2=array();
                $precode="";
                $sufcode="0A0A0A0A0A0A1D5601";                        
                //var_dump($listData);exit;
                $printret=array();
                $printserver="0";  ///自己去轮询
                //份数循环
                foreach ($printercontent_a as $key=>$values) {             //////////////                           
                    //$printret=Helper::printConetent($printer,$listData,$precode,$sufcode,$printserver);
                    $printer2 = $printers_a[$key];
                    $printret=Helper::printConetent2($printer2,$values,$precode,$sufcode,$printserver,$order->lid);
                    //array_push($jobids,$printret['jobid']."_".$order->lid);//将所有单品的id链接上去，便于更新下单状态，打印成功后下单状态和打印状态变更，数量加1
                    if(!$printret['status'])
                    {
                        return array('status'=>false,'allnum'=>count($jobids),'msg'=>$printret['msg']);
                    }
                    array_push($jobids2,"0_".$printret['jobid']."_".$printret['address']);
                    
                }               
                //var_dump(json_encode($jobids));exit;
//                Gateway::getOnlineStatus();
//                $store = Store::instance('wymenu');
                $store=new Memcache;
                $store->connect(Yii::app()->params['memcache']['server'],Yii::app()->params['memcache']['port']);                
                $store->set("kitchenjobs_".$order->dpid."_".$order->lid,json_encode($jobids2),0,300);       
                $store->close();
                $ret=array('status'=>true,'orderid'=>$order->lid,'dpid'=>$order->dpid,'allnum'=>count($jobids2),'msg'=>'打印任务正常发布',"jobs"=>$jobids2);
                //return array('status'=>true,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试14");
                //更新菜品状态为已打印
//                $sqlorderproduct="update nb_order_product set is_print='1' where dpid=".$order->dpid." and order_id =".$order->lid;
//                $commandorderproduct=Yii::app()->db->createCommand($sqlorderproduct);
//                $commandorderproduct->execute();
                
                return $ret;
	}
	
	//2016/1/14更新
	//催菜打印
	static public function printKitchenHurry(Order $order, $orderProducts,Site $site,  SiteNo $siteNo , $reprint,$memo){
		$printers_a=array();
		$orderproducts_a=array();
		$printer2orderproducts_a=array();
		$jobids=array();
		$printercontent_a=array();
		$floor_id='0';
		if($order->is_temp=='0')
		{
			$floor_id=$site->floor_id;
		}
		//return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>yii::t('app','没有要打印的菜品！'));
		//                $orderProducts = OrderProduct::model()->with('product')->findAll('t.order_id=:id and t.dpid=:dpid and t.is_print=0 and t.delete_flag=0' , array(':id'=>$order->lid,':dpid'=>$order->dpid));
		if(empty($orderProducts))
		{
			return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>yii::t('app','没有要打印的菜品！'));
		}
		//foreach printer_way //传菜厨打、整单厨打、配菜和制作厨打
		$printerways= PrinterWay::model()->findAll(" dpid = :dpid and delete_flag=0",array(':dpid'=>$order->dpid));
		if(empty($printerways))
		{
			return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"没有打印方案".$order->dpid);
		}
		//var_dump($printerways);exit;
		foreach($printerways as $printerway)
		{
			$printer2orderproducts_a=array();
			foreach($orderProducts as $orderProduct)
			{
				$orderproducts_a[$orderProduct->lid]=$orderProduct;
	
				$productprinterwaynow=  ProductPrinterway::model()->find("dpid=:dpid and printer_way_id=:pwi and product_id=:pid",array(':dpid'=>$order->dpid,':pwi'=>$printerway->lid,':pid'=>$orderProduct->product_id));
				//var_dump($printerway->lid,$productprinterwaynow);exit;
				if(!empty($productprinterwaynow))
				{
					//不是每个产品都对应所有打印方案
					//                                return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"部分产品没有设置打印方案");
					//                            }else{
					$printwaydetails = PrinterWayDetail::model()->findAll('floor_id=:floorid and print_way_id=:pwi and dpid=:dpid and delete_flag=0',array(':floorid'=>$floor_id,':pwi'=>$printerway->lid,':dpid'=>$order->dpid));
					foreach ($printwaydetails as $printway) {
						$printer = Printer::model()->find('lid=:printerId and dpid=:dpid',  array(':printerId'=>$printway->printer_id,':dpid'=>$order->dpid));
						if(empty($printer)) {
							return array('status'=>false,'dpid'=>$printer->dpid,'allnum'=>"0",'type'=>'none','msg'=>yii::t('app','打印方案没有设置厨房打印机'));
						}
						if(!array_key_exists($printer->lid, $printers_a))
						{
							$printers_a[$printer->lid]=$printer; //add isonpaper listno
						}
						if(array_key_exists($printer->lid, $printer2orderproducts_a))
						{
							array_push($printer2orderproducts_a[$printer->lid],$orderProduct->lid);
						}else{
							$printer2orderproducts_a[$printer->lid]=array($orderProduct->lid);
						}
						if($printer->printer_type!='0') {
							return array('status'=>false,'dpid'=>$printer->dpid,'allnum'=>"0",'type'=>'none','msg'=>yii::t('app','厨打打印机必须是网络打印机'));
						}
					}
				}
			}
			
			if($printerway->is_onepaper=="1")
			{
				foreach ($printer2orderproducts_a as $key=>$values) {
					//return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试5");
					$printer = $printers_a[$key];
					$productids="";
					//$listData = array("22".Helper::getPlaceholderLenBoth($order->company->company_name, 16));//
					$listData = array("22".Helper::setPrinterTitle("催菜单！！",8));
					array_push($listData,"br");
					array_push($listData,"br");
					//array_push($listData,"22"."+++总单+++");
					array_push($listData,"10".Helper::setPrinterTitle($printerway->name,12));
					array_push($listData,"00");
					array_push($listData,"br");
					array_push($listData,"br");
					//return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"zheng-memo:".$memo);
					$strSite="";
					if($order->is_temp=='1')
					{
						array_push($listData,"10".yii::t('app','临时座：'));
						array_push($listData,"11".$siteNo->site_id%1000);
					}else{
						array_push($listData,"10".yii::t('app','座号：'));
						array_push($listData,"11".$site->siteType->name.' '.$site->serial);
					}
					array_push($listData,"br");
					array_push($listData,"10".yii::t('app','人数：').$order->number);
					array_push($listData,"br");
					array_push($listData,"10"."下单时间：");
					array_push($listData,"00".$order->create_at);
					array_push($listData,"br");
					array_push($listData,"10"."账单号：");
					array_push($listData,"00".$order->account_no);
					array_push($listData,"br");
					//return array('status'=>true,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试1");
					array_push($listData,"00".str_pad('',48,'-'));
					array_push($listData,"10".str_pad('品名',16,' ').str_pad('数量 ',6,' ').str_pad('单位',4,' '));
					array_push($listData,"br");
					array_push($listData,"00".str_pad('',48,'-'));
					$productids="";
					//return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试3");
					foreach($values as $value)
					{
						//return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试3");
						if(empty($productids))
						{
							$productids.=$value;
						}else{
							$productids.=",".$value;
						}
						//return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试3");
						$orderProduct=$orderproducts_a[$value];
						if($orderProduct->amount<1)
						{
							continue;
						}
						//array_push($listData,Helper::getPlaceholderLen($value->product->product_name,38).Helper::getPlaceholderLen($orderProduct->amount." X ".$value->product->product_unit,10));
						//array_push($listData,"01".str_pad("-".$orderProduct->amount.$orderProduct->product->product_unit,8," ").Helper::setProductName($orderProduct->product->product_name,12,8));
						array_push($listData,"01".str_pad($orderProduct->product->product_name,37," ").str_pad($orderProduct->amount,6," ").str_pad($orderProduct->product->product_unit,8," "));
						array_push($listData,"br");
						array_push($listData,"br");
						array_push($listData,"10"."原因:".$memo);
						array_push($listData,"br");
						//return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试3".$memo);
						array_push($listData,"00".str_pad('',48,'-'));
					}
					if(!empty($order->username))
					{
						array_push($listData,"10"."点单员：".$order->username);//."  "
						array_push($listData,"br");
						array_push($listData,"10"."催菜员：".Yii::app()->user->name);//."  "
					}else{
						array_push($listData,"10"."客人自助下单");//."  "
						array_push($listData,"br");
						array_push($listData,"10"."催菜员：".Yii::app()->user->name);//."  "
					}
					array_push($listData,"br");
					array_push($listData,"10"."催菜时间：");
					array_push($listData,"00".date('Y-m-d H:i:s',time()));
	
					/////尝试用整体打印$printercontent_a
					//return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试3");
					for($i=0;$i<$printerway->list_no;$i++){
						if(array_key_exists($key, $printercontent_a))
						{
							array_push($printercontent_a[$key],$listData);
						}else{
							$printercontent_a[$key]=array($listData);
						}
					}
				}
			}else{ ////如果不是整体，分开打印    //////////////
				foreach ($printer2orderproducts_a as $key=>$values) {
					//return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试6");
					$printer = $printers_a[$key];
					$productids="";
					
					$listDataHeader = array("22".Helper::setPrinterTitle("催菜单！！",8));
					array_push($listDataHeader,"br");
					array_push($listDataHeader,"br");
					//array_push($listData,"22"."+++总单+++");
					array_push($listDataHeader,"10".Helper::setPrinterTitle($printerway->name,12));
					array_push($listDataHeader,"00");
					array_push($listDataHeader,"br");
					array_push($listDataHeader,"br");
					//return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"zheng-memo:".$memo);
					$strSite="";
					if($order->is_temp=='1')
					{
						array_push($listDataHeader,"10".yii::t('app','临时座：'));
						array_push($listDataHeader,"11".$siteNo->site_id%1000);
					}else{
						array_push($listDataHeader,"10".yii::t('app','座号：'));
						array_push($listDataHeader,"11".$site->siteType->name.' '.$site->serial);
					}
					array_push($listDataHeader,"br");
					array_push($listDataHeader,"10".yii::t('app','人数：').$order->number);
					array_push($listDataHeader,"br");
					array_push($listDataHeader,"10"."下单时间：");
					array_push($listDataHeader,"00".$order->create_at);
					array_push($listDataHeader,"br");
					array_push($listDataHeader,"10"."账单号：");
					array_push($listDataHeader,"00".$order->account_no);
					array_push($listDataHeader,"br");
					//return array('status'=>true,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试1");
					array_push($listDataHeader,"00".str_pad('',48,'-'));
					array_push($listDataHeader,"10".str_pad('品名',16,' ').str_pad('数量 ',6,' ').str_pad('单位',4,' '));
					array_push($listDataHeader,"br");
					array_push($listDataHeader,"00".str_pad('',48,'-'));
	
	
					//组装尾部
					$listDataTail=array();// =array("00".str_pad('',48,'-'));
					
					if(!empty($order->username))
					{
						array_push($listDataTail,"10"."点单员：".$order->username);//."  "
						array_push($listDataTail,"br");
						array_push($listDataTail,"10"."催菜员：".Yii::app()->user->name);//."  "
					}else{
						array_push($listDataTail,"10"."客人自助下单");//."  "
						array_push($listDataTail,"br");
						array_push($listDataTail,"10"."催菜员：".Yii::app()->user->name);//."  "
					}
					array_push($listDataTail,"br");
					array_push($listDataTail,"10"."催菜时间：");
					array_push($listDataTail,"00".date('Y-m-d H:i:s',time()));
	
					//生成body并打印
					$productids="";
					foreach($values as $value)
					{
						$listDataBody= array();
						//组装身体
						//$productids="";
						if(empty($productids))
						{
							$productids.=$value;
						}else{
							$productids.=",".$value;
						}
						$orderProduct=$orderproducts_a[$value];
						if($orderProduct->amount<1)
						{
							continue;
						}
					
						//return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试4".$memo);
						array_push($listDataBody,"01".str_pad($orderProduct->product->product_name,37," ").str_pad($orderProduct->amount,6," ").str_pad($orderProduct->product->product_unit,8," "));
						array_push($listDataBody,"br");
						array_push($listDataBody,"br");
						array_push($listDataBody,"10"."原因:".$memo);
						array_push($listDataBody,"br");
						//return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试3".$memo);
						array_push($listDataBody,"00".str_pad('',48,'-'));
						$listData=  array_merge($listDataHeader,$listDataBody,$listDataTail);
						
						for($i=0;$i<$printerway->list_no;$i++){
							if(array_key_exists($key, $printercontent_a))
							{
								array_push($printercontent_a[$key],$listData);
							}else{
								$printercontent_a[$key]=array($listData);
							}
						}
					}
					//return array('status'=>true,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试n");
				}
			}
		}
		//return array('status'=>true,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"13");
		//整体打印
		$jobids2=array();
		$precode="";
		$sufcode="0A0A0A0A0A0A1D5601";
		//var_dump($listData);exit;
		$printret=array();
		$printserver="0";  ///自己去轮询
		//份数循环
		foreach ($printercontent_a as $key=>$values) {             //////////////
			//$printret=Helper::printConetent($printer,$listData,$precode,$sufcode,$printserver);
			$printer2 = $printers_a[$key];
			$printret=Helper::printConetent2($printer2,$values,$precode,$sufcode,$printserver,$order->lid);
			//array_push($jobids,$printret['jobid']."_".$order->lid);//将所有单品的id链接上去，便于更新下单状态，打印成功后下单状态和打印状态变更，数量加1
			if(!$printret['status'])
			{
				return array('status'=>false,'allnum'=>count($jobids),'msg'=>$printret['msg']);
			}
			array_push($jobids2,"0_".$printret['jobid']."_".$printret['address']);
	
		}
		
		$store=new Memcache;
		$store->connect(Yii::app()->params['memcache']['server'],Yii::app()->params['memcache']['port']);
		$store->set("kitchenjobs_".$order->dpid."_".$order->lid,json_encode($jobids2),0,300);
		$store->close();
		$ret=array('status'=>true,'orderid'=>$order->lid,'dpid'=>$order->dpid,'allnum'=>count($jobids2),'msg'=>'打印任务正常发布',"jobs"=>$jobids2);
		
		return $ret;
	}
        
        /*
         * $printserver是否通过打印服务器打印，0表示不通过，数据存储在内存中，由程序通知pad自己去取数据并打印。
         * 1表示通过，指令发出去后，由打印服务器安排打印，程序只能读取打印服务器的返回结果，是异步的。
         */
        static public function printConetent(Printer $printer,$content,$precode,$sufcode,$printserver,$orderid)
        {
//                Gateway::getOnlineStatus();
//                $store = Store::instance('wymenu');
                $store=new Memcache;
                $store->connect(Yii::app()->params['memcache']['server'],Yii::app()->params['memcache']['port']);                
                $contentCode="";
                //内容编码
                if($printer->language=='1')//zh-cn GBK
                {
                    foreach($content as $line)
                    {
                        //$strcontent=mb_convert_encoding($line,"GBK","UTF-8");
                        //$contentCode.=strtoupper(implode('',unpack('H*', $strcontent)))."0A";
                        $strcontent=mb_convert_encoding(substr($line,2),"GBK","UTF-8");
                        $strfontsize=substr($line,0,2);
                        if($strfontsize=="br")
                        {
                            $contentCode.="0A";
                        }else{                            
                            $contentCode.="1D21".$strfontsize.strtoupper(implode('',unpack('H*', $strcontent)));
                        }
                    }
                }elseif($printer->language=='2')//日文 shift-jis
                {
                    $contentCode.="1C43011C26";//日文前导符号
                    foreach($content as $line)
                    {
                        //$strcontent=mb_convert_encoding($line,"SJIS","UTF-8");
                        //$contentCode.=strtoupper(implode('',unpack('H*', $strcontent)))."0A";
                        $strcontent=mb_convert_encoding(substr($line,2),"SJIS","UTF-8");
                        $strfontsize=substr($line,0,2);
                        if($strfontsize=="br")
                        {
                            $contentCode.="0A";
                        }else{                            
                            $contentCode.="1D21".$strfontsize.strtoupper(implode('',unpack('H*', $strcontent)));
                        }
                    }
                }else
                {
                    return array('status'=>false,'dpid'=>$printer->dpid,'jobid'=>'0','type'=>'none','msg'=>yii::t('app','无法确定打印机语言！'));
                }
                //加barcode和切纸
                $contentCode=$precode.$contentCode.$sufcode;
                //任务构建
                $se=new Sequence("printer_job_id");
                $jobid = $se->nextval();
                if($printserver=='1')//通过打印服务器打印
                {
                    if($printer->printer_type!='0')//not net
                    {
                        return array('status'=>false,'dpid'=>$printer->dpid,'jobid'=>'0','type'=>'net','msg'=>yii::t('app','网络打印的打印机必须是网络打印机！'));
                    }
//                    $print_data=array(
//                        "do_id"=>"ipPrintContent",
//                        "company_id"=>$printer->dpid,
//                        "job_id"=>$jobid,
//                        "printer"=>$printer->address,
//                        //"content"=>"BBB6D3ADCAB9D3C30A0A0A0A0A0A1D5601"
//                        "content"=>$contentCode
//                    );
//                    //$store = Store::instance('wymenu');
//                    //echo 'ss';exit;
//                    $clientId=$store->get("client_".$printer->dpid);
//                    //var_dump($clientId,$print_data);exit;
//                    if(!empty($clientId))
//                    {
//                        Gateway::sendToClient($clientId,json_encode($print_data));
//                        //Gateway::sendToAll(json_encode($print_data));
//                        return array('status'=>true,'dpid'=>$printer->dpid,'jobid'=>$jobid,'type'=>'net','msg'=>'');
//                    }else{
//                        return array('status'=>false,'dpid'=>$printer->dpid,'jobid'=>'0','type'=>'net','msg'=>yii::t('app','打印服务器找不到！'));
//                    }   
                    ///////////////////
                    ///打印任务不再发送，返回job编号，有pad自己去取                   
                    
                }else{//主动的同步打印 0
//                    if($printer->printer_type=='1')//local
//                    {                
//                        //$ret = $store->set($companyId."_".$jobid,'1C43011C2688A488A482AE82AF82B182F182C982BF82CD0A0A0A0A0A0A1D5601',0,60);
//                        $store->set($printer->dpid."_".$jobid,$contentCode,0,120);//should 120测试1200
//                        return array('status'=>true,'dpid'=>$printer->dpid,'jobid'=>$jobid,'type'=>'local','msg'=>'');
//                    }else{
                      $seorderprintjobs=new Sequence("order_printjobs");
                        $orderjobId = $seorderprintjobs->nextval();
                        $time=date('Y-m-d H:i:s',time());
                        //插入一条
                        $orderPrintJob = array(
                                            'lid'=>$orderjobId,
                                            'dpid'=>$printer->dpid,
                                            'create_at'=>$time,
                                            'orderid'=>$orderid,
                                            'jobid'=>$jobid,
                                            'update_at'=>$time,
                                            'address'=>$printer->address,
                                            'content'=>$contentCode,
                                            'printer_type'=>"0",
                                            'finish_flag'=>'1',
                                            'delete_flag'=>'0',
                                            );
                        Yii::app()->db->createCommand()->insert('nb_order_printjobs',$orderPrintJob);
                        $store->set($printer->dpid."_".$jobid,$contentCode,0,30);//should 120测试1200                        
                        return array('status'=>true,'dpid'=>$printer->dpid,'jobid'=>$jobid,'type'=>'net','address'=>$printer->address,'msg'=>'');
//                    }
                }
                $store->close();
        }
        
        static public function printPauseConetent(Printer $printer,$content,$precode,$sufcode,$printserver,$orderid)
        {
                $store=new Memcache;
                $store->connect(Yii::app()->params['memcache']['server'],Yii::app()->params['memcache']['port']);                
                $contentCode="";
                //内容编码
                if($printer->language=='1')//zh-cn GBK
                {
                    foreach($content as $line)
                    {
                        //$strcontent=mb_convert_encoding($line,"GBK","UTF-8");
                        //$contentCode.=strtoupper(implode('',unpack('H*', $strcontent)))."0A";
                        $strcontent=mb_convert_encoding(substr($line,2),"GBK","UTF-8");
                        $strfontsize=substr($line,0,2);
                        if($strfontsize=="br")
                        {
                            $contentCode.="0A";
                        }else{                            
                            $contentCode.="1D21".$strfontsize.strtoupper(implode('',unpack('H*', $strcontent)));
                        }
                    }
                }elseif($printer->language=='2')//日文 shift-jis
                {
                    $contentCode.="1C43011C26";//日文前导符号
                    foreach($content as $line)
                    {
                        //$strcontent=mb_convert_encoding($line,"SJIS","UTF-8");
                        //$contentCode.=strtoupper(implode('',unpack('H*', $strcontent)))."0A";
                        $strcontent=mb_convert_encoding(substr($line,2),"SJIS","UTF-8");
                        $strfontsize=substr($line,0,2);
                        if($strfontsize=="br")
                        {
                            $contentCode.="0A";
                        }else{                            
                            $contentCode.="1D21".$strfontsize.strtoupper(implode('',unpack('H*', $strcontent)));
                        }
                    }
                }else
                {
                    return array('status'=>false,'dpid'=>$printer->dpid,'jobid'=>'0','type'=>'none','msg'=>yii::t('app','无法确定打印机语言！'));
                }
                //加barcode和切纸
                $contentCode=$precode.$contentCode.$sufcode;
                //任务构建
                $se=new Sequence("printer_job_id");
                $jobid = $se->nextval();
                if($printserver=='1')//通过打印服务器打印
                {
                    if($printer->printer_type!='0')//not net
                    {
                        return array('status'=>false,'dpid'=>$printer->dpid,'jobid'=>'0','type'=>'net','msg'=>yii::t('app','网络打印的打印机必须是网络打印机！'));
                    }
                    
                }else{//主动的同步打印 0
                    $db=Yii::app()->db;
                    $transactionnow=$db->beginTransaction();
                    try{
                        $seorderprintjobs=new Sequence("order_printjobs");
                        $orderjobId = $seorderprintjobs->nextval();
                        $time=date('Y-m-d H:i:s',time());
                        //插入一条
                        $orderPrintJob = array(
                                            'lid'=>$orderjobId,
                                            'dpid'=>$printer->dpid,
                                            'create_at'=>$time,
                                            'orderid'=>$orderid,
                                            'jobid'=>$jobid,
                                            'update_at'=>$time,
                                            'address'=>$printer->address,
                                            'content'=>$contentCode,
                                            'printer_type'=>"0",
                                            'finish_flag'=>'1',
                                            'delete_flag'=>'0',
                                            'is_sync'=>'10000',
                                            );
                        $db->createCommand("delete from nb_order_printjobs where dpid=".$printer->dpid." and orderid=".$orderid." and is_sync='10000'")->execute();
                        $db->createCommand()->insert('nb_order_printjobs',$orderPrintJob);
                        $db->createCommand("update nb_order_product set product_order_status='0' where dpid=".$printer->dpid." and order_id=".$orderid." and product_order_status='9'")->execute();
                        $store->set($printer->dpid."_".$jobid,$contentCode,0,30);//should 120测试1200                        
                        $transactionnow->commit();
                        return array('status'=>true,'dpid'=>$printer->dpid,'jobid'=>$jobid,'type'=>'net','address'=>$printer->address,'msg'=>'');
                    }catch( Exception $ex){
                        $transactionnow->rollback();
                        return array('status'=>false,'dpid'=>$printer->dpid,'jobid'=>'0','type'=>'net','msg'=>yii::t('app','数据库更新异常！'));
                    }
                }
                $store->close();
        }
        
        static public function printPayConetent(Printer $printer,$content,$precode,$sufcode,$printserver,$orderid)
        {
                $store=new Memcache;
                $store->connect(Yii::app()->params['memcache']['server'],Yii::app()->params['memcache']['port']);                
                $contentCode="";
                //内容编码
                if($printer->language=='1')//zh-cn GBK
                {
                    foreach($content as $line)
                    {
                        //$strcontent=mb_convert_encoding($line,"GBK","UTF-8");
                        //$contentCode.=strtoupper(implode('',unpack('H*', $strcontent)))."0A";
                        $strcontent=mb_convert_encoding(substr($line,2),"GBK","UTF-8");
                        $strfontsize=substr($line,0,2);
                        if($strfontsize=="br")
                        {
                            $contentCode.="0A";
                        }else{                            
                            $contentCode.="1D21".$strfontsize.strtoupper(implode('',unpack('H*', $strcontent)));
                        }
                    }
                }elseif($printer->language=='2')//日文 shift-jis
                {
                    $contentCode.="1C43011C26";//日文前导符号
                    foreach($content as $line)
                    {
                        //$strcontent=mb_convert_encoding($line,"SJIS","UTF-8");
                        //$contentCode.=strtoupper(implode('',unpack('H*', $strcontent)))."0A";
                        $strcontent=mb_convert_encoding(substr($line,2),"SJIS","UTF-8");
                        $strfontsize=substr($line,0,2);
                        if($strfontsize=="br")
                        {
                            $contentCode.="0A";
                        }else{                            
                            $contentCode.="1D21".$strfontsize.strtoupper(implode('',unpack('H*', $strcontent)));
                        }
                    }
                }else
                {
                    return array('status'=>false,'dpid'=>$printer->dpid,'jobid'=>'0','type'=>'none','msg'=>yii::t('app','无法确定打印机语言！'));
                }
                //加barcode和切纸
                $contentCode=$precode.$contentCode.$sufcode;
                //任务构建
                $se=new Sequence("printer_job_id");
                $jobid = $se->nextval();
                if($printserver=='1')//通过打印服务器打印
                {
                    if($printer->printer_type!='0')//not net
                    {
                        return array('status'=>false,'dpid'=>$printer->dpid,'jobid'=>'0','type'=>'net','msg'=>yii::t('app','网络打印的打印机必须是网络打印机！'));
                    }
                    
                }else{//主动的同步打印 0
                    $db=Yii::app()->db;
                    $transactionnow=$db->beginTransaction();
                    try{
                        $seorderprintjobs=new Sequence("order_printjobs");
                        $orderjobId = $seorderprintjobs->nextval();
                        $time=date('Y-m-d H:i:s',time());
                        //插入一条
                        $orderPrintJob = array(
                                            'lid'=>$orderjobId,
                                            'dpid'=>$printer->dpid,
                                            'create_at'=>$time,
                                            'orderid'=>$orderid,
                                            'jobid'=>$jobid,
                                            'update_at'=>$time,
                                            'address'=>$printer->address,
                                            'content'=>$contentCode.$contentCode,
                                            'printer_type'=>"0",
                                            'finish_flag'=>'1',
                                            'delete_flag'=>'0',
                                            'is_sync'=>'01000',
                                            );
                        $db->createCommand("delete from nb_order_printjobs where dpid=".$printer->dpid." and orderid=".$orderid." and is_sync='01000'")->execute();
                        $db->createCommand()->insert('nb_order_printjobs',$orderPrintJob);
                        //$db->createCommand("update nb_order_product set product_order_status='0' where dpid=".$printer->dpid." and order_id=".$orderid." and product_order_status='9'")->execute();
                        $store->set($printer->dpid."_".$jobid,$contentCode,0,30);//should 120测试1200                        
                        $transactionnow->commit();
                        return array('status'=>true,'dpid'=>$printer->dpid,'jobid'=>$jobid,'type'=>'net','address'=>$printer->address,'msg'=>'');
                    }catch( Exception $ex){
                        $transactionnow->rollback();
                        return array('status'=>false,'dpid'=>$printer->dpid,'jobid'=>'0','type'=>'net','msg'=>yii::t('app','数据库更新异常！'));
                    }
                }
                $store->close();
        }
        
        /*
         * $printserver是否通过打印服务器打印，0表示不通过，数据存储在内存中，由程序通知pad自己去取数据并打印。
         * 1表示通过，指令发出去后，由打印服务器安排打印，程序只能读取打印服务器的返回结果，是异步的。
         */
        static public function printConetent2(Printer $printer,$contents,$precode,$sufcode,$printserver,$orderid)
        {
//                Gateway::getOnlineStatus();
//                $store = Store::instance('wymenu');
            //return array('status'=>false,'dpid'=>$printer->dpid,'jobid'=>'0','type'=>'none','msg'=>yii::t('app',Yii::app()->params['memcache']['server'].Yii::app()->params['memcache']['port']));
            try{
                $store=new Memcache;
                $store->connect(Yii::app()->params['memcache']['server'],Yii::app()->params['memcache']['port']);
                //$store=memcache_connect(Yii::app()->params['memcache']['server'],Yii::app()->params['memcache']['port']);
                //return array('status'=>false,'dpid'=>$printer->dpid,'jobid'=>'0','type'=>'none','msg'=>'memcache初始化失败');                
            }catch(Exception $e)
            {
                return array('status'=>false,'dpid'=>$printer->dpid,'jobid'=>'0','type'=>'none','msg'=>yii::t('app','memcache初始化失败22！'));
            }
                $contentCode="";
                $contentCodeAll="";
                
                foreach($contents as $content)
                {
                    //内容编码
                    if($printer->language=='1')//zh-cn GBK
                    {
                        foreach($content as $line)
                        {
                            //$strcontent=mb_convert_encoding($line,"GBK","UTF-8");
                            //$contentCode.=strtoupper(implode('',unpack('H*', $strcontent)))."0A";
                            $strcontent=mb_convert_encoding(substr($line,2),"GBK","UTF-8");
                            $strfontsize=substr($line,0,2);
                            if($strfontsize=="br")
                            {
                                $contentCode.="0A";
                            }else{                            
                                $contentCode.="1D21".$strfontsize.strtoupper(implode('',unpack('H*', $strcontent)));
                            }
                        }
                    }elseif($printer->language=='2')//日文 shift-jis
                    {
                        $contentCode.="1C43011C26";//日文前导符号
                        foreach($content as $line)
                        {
                            //$strcontent=mb_convert_encoding($line,"SJIS","UTF-8");
                            //$contentCode.=strtoupper(implode('',unpack('H*', $strcontent)))."0A";
                            $strcontent=mb_convert_encoding(substr($line,2),"SJIS","UTF-8");
                            $strfontsize=substr($line,0,2);
                            if($strfontsize=="br")
                            {
                                $contentCode.="0A";
                            }else{                            
                                $contentCode.="1D21".$strfontsize.strtoupper(implode('',unpack('H*', $strcontent)));
                            }
                        }
                    }else
                    {
                        return array('status'=>false,'dpid'=>$printer->dpid,'jobid'=>'0','type'=>'none','msg'=>yii::t('app','无法确定打印机语言！'));
                    }
                    //加barcode和切纸
                    $contentCode=$precode.$contentCode.$sufcode;
                    $contentCodeAll=$contentCodeAll.$contentCode;
                    $contentCode="";
                }
                    //任务构建
                $se=new Sequence("printer_job_id");
                $jobid = $se->nextval();
                if($printserver=='1')//通过打印服务器打印
                {
//                    if($printer->printer_type!='0')//not net
//                    {
//                        return array('status'=>false,'dpid'=>$printer->dpid,'jobid'=>'0','type'=>'net','msg'=>yii::t('app','网络打印的打印机必须是网络打印机！'));
//                    }
//                    $print_data=array(
//                        "do_id"=>"ipPrintContent",
//                        "company_id"=>$printer->dpid,
//                        "job_id"=>$jobid,
//                        "printer"=>$printer->address,
//                        //"content"=>"BBB6D3ADCAB9D3C30A0A0A0A0A0A1D5601"
//                        "content"=>$contentCode
//                    );
//                    //$store = Store::instance('wymenu');
//                    //echo 'ss';exit;
//                    $clientId=$store->get("client_".$printer->dpid);
//                    //var_dump($clientId,$print_data);exit;
//                    if(!empty($clientId))
//                    {
//                        Gateway::sendToClient($clientId,json_encode($print_data));
//                        //Gateway::sendToAll(json_encode($print_data));
//                        return array('status'=>true,'dpid'=>$printer->dpid,'jobid'=>$jobid,'type'=>'net','msg'=>'');
//                    }else{
//                        return array('status'=>false,'dpid'=>$printer->dpid,'jobid'=>'0','type'=>'net','msg'=>yii::t('app','打印服务器找不到！'));
//                    }   
                    ///////////////////
                    ///打印任务不再发送，返回job编号，有pad自己去取                   

                }else{//主动的同步打印 0
//                    if($printer->printer_type=='1')//local
//                    {                
//                        //$ret = $store->set($companyId."_".$jobid,'1C43011C2688A488A482AE82AF82B182F182C982BF82CD0A0A0A0A0A0A1D5601',0,60);
//                        $store->set($printer->dpid."_".$jobid,$contentCode,0,120);//should 120测试1200
//                        return array('status'=>true,'dpid'=>$printer->dpid,'jobid'=>$jobid,'type'=>'local','msg'=>'');
//                    }else{
                        
                        $seorderprintjobs=new Sequence("order_printjobs");
                        $orderjobId = $seorderprintjobs->nextval();
                        $time=date('Y-m-d H:i:s',time());
                        //插入一条
                        $orderPrintJob = array(
                                            'lid'=>$orderjobId,
                                            'dpid'=>$printer->dpid,
                                            'create_at'=>$time,
                                            'orderid'=>$orderid,
                                            'jobid'=>$jobid,
                                            'update_at'=>$time,
                                            'address'=>$printer->address,
                                            'content'=>$contentCodeAll,
                                            'printer_type'=>"0",
                                            'finish_flag'=>'0',//默认0不成功
                                            'delete_flag'=>'0',
                                            );
                        Yii::app()->db->createCommand()->insert('nb_order_printjobs',$orderPrintJob);
                        
                        $store->set($printer->dpid."_".$jobid,$contentCodeAll,0,30);//should 120测试1200
                        return array('status'=>true,'dpid'=>$printer->dpid,'jobid'=>$jobid,'type'=>'net','address'=>$printer->address,'msg'=>'');
//                    }
                }
                $store->close();
        }

        /*******
         * 
         * 
         * 
         * 为了打印微信支付端生成的自助付款单的厨打，生产打印任务根据printConetent2修改的的方法
         * 
         * 
         * 
         */
        static public function printConetent8(Printer $printer,$contents,$precode,$sufcode,$printserver,$orderid)
			{
//                Gateway::getOnlineStatus();
//                $store = Store::instance('wymenu');
            //return array('status'=>false,'dpid'=>$printer->dpid,'jobid'=>'0','type'=>'none','msg'=>yii::t('app',Yii::app()->params['memcache']['server'].Yii::app()->params['memcache']['port']));
            try{
                $store=new Memcache;
                $store->connect(Yii::app()->params['memcache']['server'],Yii::app()->params['memcache']['port']);
                //$store=memcache_connect(Yii::app()->params['memcache']['server'],Yii::app()->params['memcache']['port']);
                //return array('status'=>false,'dpid'=>$printer->dpid,'jobid'=>'0','type'=>'none','msg'=>'memcache初始化失败');                
            }catch(Exception $e)
            {
                return array('status'=>false,'dpid'=>$printer->dpid,'jobid'=>'0','type'=>'none','msg'=>yii::t('app','memcache初始化失败22！'));
            }
                $contentCode="";
                $contentCodeAll="";
                
                foreach($contents as $content)
                {
                    //内容编码
                    if($printer->language=='1')//zh-cn GBK
                    {
                        foreach($content as $line)
                        {
                            //$strcontent=mb_convert_encoding($line,"GBK","UTF-8");
                            //$contentCode.=strtoupper(implode('',unpack('H*', $strcontent)))."0A";
                            $strcontent=mb_convert_encoding(substr($line,2),"GBK","UTF-8");
                            $strfontsize=substr($line,0,2);
                            if($strfontsize=="br")
                            {
                                $contentCode.="0A";
                            }else{                            
                                $contentCode.="1D21".$strfontsize.strtoupper(implode('',unpack('H*', $strcontent)));
                            }
                        }
                    }elseif($printer->language=='2')//日文 shift-jis
                    {
                        $contentCode.="1C43011C26";//日文前导符号
                        foreach($content as $line)
                        {
                            //$strcontent=mb_convert_encoding($line,"SJIS","UTF-8");
                            //$contentCode.=strtoupper(implode('',unpack('H*', $strcontent)))."0A";
                            $strcontent=mb_convert_encoding(substr($line,2),"SJIS","UTF-8");
                            $strfontsize=substr($line,0,2);
                            if($strfontsize=="br")
                            {
                                $contentCode.="0A";
                            }else{                            
                                $contentCode.="1D21".$strfontsize.strtoupper(implode('',unpack('H*', $strcontent)));
                            }
                        }
                    }else
                    {
                        return array('status'=>false,'dpid'=>$printer->dpid,'jobid'=>'0','type'=>'none','msg'=>yii::t('app','无法确定打印机语言！'));
                    }
                    //加barcode和切纸
                    $contentCode=$precode.$contentCode.$sufcode;
                    $contentCodeAll=$contentCodeAll.$contentCode;
                    $contentCode="";
                }
                    //任务构建
                $se=new Sequence("printer_job_id");
                $jobid = $se->nextval();
                if($printserver=='1')//通过打印服务器打印
                {
  
                    ///////////////////
                    ///打印任务不再发送，返回job编号，有pad自己去取                   

                }else{//主动的同步打印 0

                        
                        $seorderprintjobs=new Sequence("order_printjobs");
                        $orderjobId = $seorderprintjobs->nextval();
                        $time=date('Y-m-d H:i:s',time());
                        //插入一条
                        $orderPrintJob = array(
                                            'lid'=>$orderjobId,
                                            'dpid'=>$printer->dpid,
                                            'create_at'=>$time,
                                            'orderid'=>$orderid,
                                            'jobid'=>$jobid,
                                            'update_at'=>$time,
                                            'address'=>$printer->address,
                                            'content'=>$contentCodeAll,
                                            'printer_type'=>"0",
                                            'finish_flag'=>'0',//默认0不成功
                                            'delete_flag'=>'0',
                        					'is_sync'=>'01001',
                                            );
                        Yii::app()->db->createCommand()->insert('nb_order_printjobs',$orderPrintJob);
                       //exit;
                        $store->set($printer->dpid."_".$jobid,$contentCodeAll,0,30);//should 120测试1200
                        return array('status'=>true,'dpid'=>$printer->dpid,'jobid'=>$jobid,'type'=>'net','address'=>$printer->address,'msg'=>'');
//                    }
                }
                $store->close();
        }
         
        
        
        static public function  GrabImage($baseurl,$filename="") { 
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