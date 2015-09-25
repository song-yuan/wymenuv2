<?php
class Helper
{
	static public function genPassword($password)
	{
			return md5(md5($password).Yii::app()->params['salt']);
	}
	static public function getCompanyId($companyId) {
            if(Yii::app()->user->role=='1' || Yii::app()->user->role=='2')
            {
		return $companyId;
            }else{
                return Yii::app()->user->companyId ;
            }
	}
        
        static public function getCompanyName($companyId) {
            if($companyId)
            {
		$models = Company::model()->find('t.dpid = '.$companyId);
                //var_dump($models);exit;
                //return Yii::app()->user->role == User::POWER_ADMIN ? $companyId : Yii::app()->user->companyId ;
               
            }else{
                $models = Company::model()->find('t.dpid = '.Yii::app()->user->companyId); 
            }
             return $models->company_name;
	}
        
	static public function genCompanyOptions() {
		$companies = Company::model()->findAll('delete_flag=0') ;
		return CHtml::listData($companies, 'dpid', 'company_name');
	}
	//生成文件名字
	static public function genFileName(){
		if (function_exists('com_create_guid') === true)
		{
			return trim(com_create_guid(), '{}');
		}
		return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
	}
	static public function getCategories($companyId,$pid=0){
		$command = Yii::app()->db->createCommand('select * from nb_product_category where dpid=:companyId and pid=:pid and delete_flag=0');
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
	static public function printList(Order $order,$orderProducts , Pad $pad, $cprecode,$printserver,$memo){
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
                if(Yii::app()->language=='jp')
                {
                    //array_push($listData,str_pad(yii::t('app','应付：').number_format($order->should_total,0) , 26,' ').str_pad(date('Y-m-d H:i:s',time()),20,' '));
                    //array_push($listData,str_pad(yii::t('app','订餐电话：').$order->company->telephone,44,' '));
                    array_push($listData,"11".yii::t('app','应付：').number_format($order->should_total,0)
                        .yii::t('app','实付：').number_format($order->reality_total,0));                    
                }else{
                    //array_push($listData,str_pad(yii::t('app','应付：').$order->should_total , 40,' '));
                    //array_push($listData,str_pad(yii::t('app','操作员：').Yii::app()->user->name,24,' ')
                    //        .str_pad(yii::t('app','时间：').date('Y-m-d H:i:s',time()),24,' '));
                    //array_push($listData,str_pad(yii::t('app','订餐电话：').$order->company->telephone,44,' '));
                    array_push($listData,"11".yii::t('app','原价：').number_format($order->should_total,2)
                            .yii::t('app','优惠价：').number_format($order->reality_total,2));                    
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
        
        //开台时的打印
        //打印开台号和人数，以后有WiFi的密码等。
	static public function printCloseAccount($dpid,$models,Pad $pad, $cprecode,$printserver,$memo){
		               
                $printer = Printer::model()->find('lid=:printerId and dpid=:dpid',  array(':printerId'=>$pad->printer_id,':dpid'=>$dpid));
		if(empty($printer)) {
                        return array('status'=>0,'dpid'=>$siteno->dpid,'jobid'=>"0",'type'=>'none','msg'=>yii::t('app','PAD还没有设置默认打印机'));		
		}
                $sumall=0;
                $listData = array("22".  Helper::setPrinterTitle(Company::getCompanyName($dpid),8));
                if(!empty($memo))
                {
                    array_push($listData,"br");
                    array_push($listData,"22"."<<".$memo);                    
                }
                array_push($listData,"00");
                array_push($listData,"br");
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
                            $payname="支付宝支付";
                            break;
                        case 3:
                            $payname="后台手动支付";
                            break;
                        case 4:
                            $payname="会员卡支付";
                            break;
                        case 5:
                            $payname="银联卡支付";
                            break;
                    }
                        
                    array_push($listData,"11".str_pad($payname,7).$model->should_all);
                    array_push($listData,"br");
                    $sumall=$sumall+$model->should_all;
                }
		array_push($listData,"00".str_pad('',48,'-')); 
                array_push($listData,"11".str_pad("合计：",7).$sumall);
                array_push($listData,"br");
                array_push($listData,"00".str_pad('',48,'-'));   
		array_push($listData,"00".Yii::app()->user->name."    ".date('Y-m-d H:i:s',time()));                    
                //array_push($listData,"00"."   ".yii::t('app','订餐电话：').$order->company->telephone);
                 
                $precode=$cprecode;
                //后面加切纸
                $sufcode="0A0A0A0A0A0A1D5601";                        
                $retcontent=array();
                $orderid="0000000000";//打印日结单时
		$retcontent= Helper::printConetent($printer,$listData,$precode,$sufcode,$printserver,$orderid);	
                //$retcontent['orderid']=$order->lid;
                return $retcontent;
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
	static public function printQueue(Pad $pad, $cprecode,$printserver,$memo){
		               
                $printer = Printer::model()->find('lid=:printerId and dpid=:dpid',  array(':printerId'=>$pad->printer_id,':dpid'=>$pad->dpid));
		if(empty($printer)) {
                        return array('status'=>0,'dpid'=>$siteno->dpid,'jobid'=>"0",'type'=>'none','msg'=>yii::t('app','PAD还没有设置默认打印机'));		
		}		
                $listData = array("22".  Helper::setPrinterTitle(Company::getCompanyName($pad->dpid),8));
                if(!empty($memo))
                {
                    array_push($listData,"br");
                    array_push($listData,"11".$memo);                    
                }
                array_push($listData,"00");
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
                Gateway::getOnlineStatus();
                $store = Store::instance('wymenu');
                $store->set("kitchenjobs_".$order->dpid."_".$order->lid,json_encode($jobids),0,300);                        
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
	static public function printKitchenAll3(Order $order,Site $site,  SiteNo $siteNo , $reprint){		
                $printers_a=array();
                $orderproducts_a=array();
                $printer2orderproducts_a=array();
                $jobids=array();
                $printercontent_a=array();
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
                //return array('status'=>true,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试13");
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
                    array_push($jobids2,"0_".$printret['jobid']."_".$printret['address']);
                    if(!$printret['status'])
                    {
                        return array('status'=>false,'allnum'=>count($jobids),'msg'=>$printret['msg']);
                    }
                }               
                //var_dump(json_encode($jobids));exit;
                Gateway::getOnlineStatus();
                $store = Store::instance('wymenu');
                $store->set("kitchenjobs_".$order->dpid."_".$order->lid,json_encode($jobids2),0,300);                        
                $ret=array('status'=>true,'orderid'=>$order->lid,'dpid'=>$order->dpid,'allnum'=>count($jobids2),'msg'=>'打印任务正常发布',"jobs"=>$jobids2);
                //return array('status'=>true,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试14");
                //更新菜品状态为已打印
                $sqlorderproduct="update nb_order_product set is_print='1' where dpid=".$order->dpid." and order_id =".$order->lid;
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
                                    $listData = array("22".Helper::setPrinterTitle($order->company->company_name,8));
                                    array_push($listData,"br");
                                    //array_push($listData,"22"."+++总单+++"); 
                                    array_push($listData,"22"."整单<".$printerway->name.">");
                                    array_push($listData,"br");
                                    array_push($listData,"22"."<".$memo);
                                    array_push($listData,"00");
                                    array_push($listData,"br");
                                    //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"zheng-memo:".$memo);
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
                                    array_push($listData,"br");
                                    //return array('status'=>true,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试1");
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
                                        array_push($listData,"11".str_pad($orderProduct->amount."X".$orderProduct->product->product_unit,8," ").  Helper::setProductName($orderProduct->product->product_name,12,8));	
                                        array_push($listData,"br");
                                        array_push($listData,$memo);
                                        array_push($listData,"br");
                                        //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试3".$memo);
                                        array_push($listData,"00".str_pad('',48,'-'));
                                    }
                                    
                                    array_push($listData,"00".yii::t('app','操作员：').$order->username."  "//Yii::app()->user->name."  "
                                            .date('Y-m-d H:i:s',time()));
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
                                    $listDataHeader = array("22".Helper::setPrinterTitle($order->company->company_name,8));
                                    array_push($listDataHeader,"br");
                                    //array_push($listData,"22"."---分菜单---"); 
                                    array_push($listDataHeader,"22"."分单<".$printerway->name.">");
                                    array_push($listDataHeader,"br");
                                    array_push($listDataHeader,"22"."<".$memo);
                                    array_push($listDataHeader,"00");
                                    array_push($listDataHeader,"br");
                                    //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"fen-memo3:".$memo);
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
                                    array_push($listDataHeader,"br");
                                    //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试4".$memo);                                        
                                    array_push($listDataHeader,"00".str_pad('',48,'-'));
                                    //组装尾部
                                    $listDataTail =array("00".str_pad('',48,'-'));
                                    array_push($listDataTail,"00".yii::t('app','操作员：').$order->username."  "//Yii::app()->user->name."  "
                                            .date('Y-m-d H:i:s',time()));
                                    //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试4".$memo);                                        
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
                                        array_push($listDataBody,$memo);
                                        array_push($listDataBody,"br");
                                        //return array('status'=>false,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试4".$memo);                                        
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
                    array_push($jobids2,"0_".$printret['jobid']."_".$printret['address']);
                    if(!$printret['status'])
                    {
                        return array('status'=>false,'allnum'=>count($jobids),'msg'=>$printret['msg']);
                    }
                }               
                //var_dump(json_encode($jobids));exit;
                Gateway::getOnlineStatus();
                $store = Store::instance('wymenu');
                $store->set("kitchenjobs_".$order->dpid."_".$order->lid,json_encode($jobids2),0,300);                        
                $ret=array('status'=>true,'orderid'=>$order->lid,'dpid'=>$order->dpid,'allnum'=>count($jobids2),'msg'=>'打印任务正常发布',"jobs"=>$jobids2);
                //return array('status'=>true,'dpid'=>$order->dpid,'allnum'=>"0",'type'=>'none','msg'=>"测试14");
                //更新菜品状态为已打印
//                $sqlorderproduct="update nb_order_product set is_print='1' where dpid=".$order->dpid." and order_id =".$order->lid;
//                $commandorderproduct=Yii::app()->db->createCommand($sqlorderproduct);
//                $commandorderproduct->execute();
                
                return $ret;
	}
        
        /*
         * $printserver是否通过打印服务器打印，0表示不通过，数据存储在内存中，由程序通知pad自己去取数据并打印。
         * 1表示通过，指令发出去后，由打印服务器安排打印，程序只能读取打印服务器的返回结果，是异步的。
         */
        static public function printConetent(Printer $printer,$content,$precode,$sufcode,$printserver,$orderid)
        {
                Gateway::getOnlineStatus();
                $store = Store::instance('wymenu');
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
                    $print_data=array(
                        "do_id"=>"ipPrintContent",
                        "company_id"=>$printer->dpid,
                        "job_id"=>$jobid,
                        "printer"=>$printer->address,
                        //"content"=>"BBB6D3ADCAB9D3C30A0A0A0A0A0A1D5601"
                        "content"=>$contentCode
                    );
                    //$store = Store::instance('wymenu');
                    //echo 'ss';exit;
                    $clientId=$store->get("client_".$printer->dpid);
                    //var_dump($clientId,$print_data);exit;
                    if(!empty($clientId))
                    {
                        Gateway::sendToClient($clientId,json_encode($print_data));
                        //Gateway::sendToAll(json_encode($print_data));
                        return array('status'=>true,'dpid'=>$printer->dpid,'jobid'=>$jobid,'type'=>'net','msg'=>'');
                    }else{
                        return array('status'=>false,'dpid'=>$printer->dpid,'jobid'=>'0','type'=>'net','msg'=>yii::t('app','打印服务器找不到！'));
                    }   
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
        }
        
        /*
         * $printserver是否通过打印服务器打印，0表示不通过，数据存储在内存中，由程序通知pad自己去取数据并打印。
         * 1表示通过，指令发出去后，由打印服务器安排打印，程序只能读取打印服务器的返回结果，是异步的。
         */
        static public function printConetent2(Printer $printer,$contents,$precode,$sufcode,$printserver,$orderid)
        {
                Gateway::getOnlineStatus();
                $store = Store::instance('wymenu');
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
                    if($printer->printer_type!='0')//not net
                    {
                        return array('status'=>false,'dpid'=>$printer->dpid,'jobid'=>'0','type'=>'net','msg'=>yii::t('app','网络打印的打印机必须是网络打印机！'));
                    }
                    $print_data=array(
                        "do_id"=>"ipPrintContent",
                        "company_id"=>$printer->dpid,
                        "job_id"=>$jobid,
                        "printer"=>$printer->address,
                        //"content"=>"BBB6D3ADCAB9D3C30A0A0A0A0A0A1D5601"
                        "content"=>$contentCode
                    );
                    //$store = Store::instance('wymenu');
                    //echo 'ss';exit;
                    $clientId=$store->get("client_".$printer->dpid);
                    //var_dump($clientId,$print_data);exit;
                    if(!empty($clientId))
                    {
                        Gateway::sendToClient($clientId,json_encode($print_data));
                        //Gateway::sendToAll(json_encode($print_data));
                        return array('status'=>true,'dpid'=>$printer->dpid,'jobid'=>$jobid,'type'=>'net','msg'=>'');
                    }else{
                        return array('status'=>false,'dpid'=>$printer->dpid,'jobid'=>'0','type'=>'net','msg'=>yii::t('app','打印服务器找不到！'));
                    }   
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