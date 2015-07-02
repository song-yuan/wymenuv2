<?php
class CreateOrder
{
	public $siteId = 0;
	public $companyId = 0;
	public $product = array();
	public function __construct($dpid = 0,$siteNoId = 0,$product = array()){
		$this->siteNo = SiteNo::model()->find('lid=:lid and dpid=:dpid',array(':lid'=>$siteNoId,':dpid'=>$dpid));
		$this->companyId = $this->siteNo->dpid;
		$this->siteId = $this->siteNo->site_id;
		$this->product = $product;
		$this->db = Yii::app()->db;
	}
	public function createOrder(){
		$time = date('Y-m-d H:i:s',time());
		$transaction = $this->db->beginTransaction();
		try {
			$criteria = new CDbCriteria;
			$criteria->addCondition('dpid=:dpid and site_id=:siteId and is_temp=:isTemp');
			$criteria->params = array(':dpid'=>$this->siteNo->dpid,':siteId'=>$this->siteNo->site_id,':isTemp'=>$this->siteNo->is_temp); 
			$criteria->order =  'lid desc';
			$order = Order::model()->find($criteria);
			if(!$order){
				$order = new Order;
				$data = array(
							'lid'=>$this->getMaxOrderId(),
							'dpid'=>$this->companyId,
							'site_id'=>$this->siteId,
							'create_at'=>$time,
							'is_temp'=>$this->siteNo->is_temp,
							'number'=>$this->siteNo->number,
							'update_at'=>$time,
							'remark'=>yii::t('app','无'),
							'taste_memo'=>yii::t('app','无'),
							);
				$order->attributes = $data;
				$order->save();
			}
			$setId = 0;
			if($this->product['type']){
				$setId = $this->product['lid'];
				$orderProduct = OrderProduct::model()->find('order_id=:orderId and dpid=:dpid and set_id=:setId and product_order_status=0',array(':orderId'=>$order->lid,'dpid'=>$this->companyId,':setId'=>$setId));
			}else{
				$orderProduct = OrderProduct::model()->find('order_id=:orderId and dpid=:dpid and product_id=:productId and product_order_status=0',array(':orderId'=>$order->lid,'dpid'=>$this->companyId,':productId'=>$this->product['lid']));
			}
			
			if($orderProduct){
				$orderProduct->price = $this->getProductPrice($this->companyId,$this->product['lid'],$this->product['type']);
				$orderProduct->delete_flag = 0;
				$orderProduct->update();
			}else{
				$orderProduct = new OrderProduct;
				$orderProductData = array(
										'lid'=>$this->getMaxOrderProductId(),
										'dpid'=>$this->companyId,
										'create_at'=>$time,
										'order_id'=>$order->lid,
										'set_id'=>$setId,
										'product_id'=>$this->product['lid'],
										'price'=>$this->getProductPrice($this->companyId,$this->product['lid'],$this->product['type']),
										'update_at'=>$time,
										'amount'=>1,
										'taste_memo'=>yii::t('app','无'),
										);
				$orderProduct->attributes = $orderProductData;
				$orderProduct->save();
			}
			$transaction->commit(); //提交事务会真正的执行数据库操作
			return true;
		} catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			return false;
		}
	}
	public function getMaxOrderId(){
		$maxOrderId = 1;
		$sql = 'SELECT NEXTVAL("order") AS id';
		$order = $this->db->createCommand($sql)->queryRow();
		if($order){
			$maxOrderId = $order['id']?$order['id']:1;
		}
		return $maxOrderId;
	}
	public function getMaxOrderProductId(){
		$maxOrderId = 1;
		$sql = 'SELECT NEXTVAL("order_product") AS id';
		$order = $this->db->createCommand($sql)->queryRow();
		if($order){
			$maxOrderId = $order['id']?$order['id']:1;
		}
		return $maxOrderId;
	}
	public static function getProductPrice($dpid = 0,$productId = 0,$type = 0){
		$price = 0;
		$time = date('Y-m-d H:i:s',time());
		$db = Yii::app()->db;
		if(!$type){
			// 非套餐
			$sql = 'select * from nb_product where lid=:lid and dpid=:dpid';
			$connect = $db->createCommand($sql);
			$connect->bindValue(':lid',$productId);
			$connect->bindValue(':dpid',$dpid);
			$product = $connect->queryRow();
			
			$price = $product['original_price'];
			if($product['is_temp_price']){
				$sql = 'select * from nb_product_tempprice where product_id=:productId and dpid=:dpid and begin_time < :time and end_time > :time';
				$connect = $db->createCommand($sql);
				$connect->bindValue(':productId',$product['lid']);
				$connect->bindValue(':dpid',$dpid);
				$connect->bindValue(':time',$time);
				$productTempPrice = $connect->queryRow();
				if($productTempPrice){
					$price = $productTempPrice['price'];
				}
			}
			if($product['is_special']){
				$sql = 'select * from nb_product_special where product_id=:productId and dpid=:dpid and begin_time < :time and end_time > :time';
				$connect = $db->createCommand($sql);
				$connect->bindValue(':productId',$product['lid']);
				$connect->bindValue(':dpid',$dpid);
				$connect->bindValue(':time',$time);
				$productTempPrice = $connect->queryRow();
				if($productTempPrice){
					$price = $productTempPrice['price'];
				}
			}
			if($product['is_discount']){
				$sql = 'select * from nb_product_discount where product_id=:productId and dpid=:dpid';
				$connect = $db->createCommand($sql);
				$connect->bindValue(':productId',$product['lid']);
				$connect->bindValue(':dpid',$dpid);
				$productDiscount = $connect->queryRow();
				if($productDiscount){
					if($productDiscount['is_discount']){
						$price = $product['original_price']*$productDiscount['price_discount']/100;
					}else{
						$price = $product['original_price'] - $productDiscount['price_discount'];
					}
				}
			}
		}else{
			//套餐
			$sql = 'select sum(price) as price from nb_product_set_detail where set_id=:setId and dpid=:dpid and delete_flag=0 and is_select=1';
			$connect = $db->createCommand($sql);
			$connect->bindValue(':setId',$productId);
			$connect->bindValue(':dpid',$dpid);
			$product = $connect->queryRow();
			$price = $product['price'];
		}
		
		return $price?$price:0;
	}
	public function hasOrderProduct(){
		$criteria = new CDbCriteria;
		$criteria->addCondition('dpid=:dpid and site_id=:siteId and is_temp=:isTemp');
		$criteria->params = array(':dpid'=>$this->siteNo->dpid,':siteId'=>$this->siteNo->site_id,':isTemp'=>$this->siteNo->is_temp); 
		$criteria->order =  'lid desc';
		$order = Order::model()->find($criteria);
		$setId = 0;
		if($order){
			if($this->product['type']){
				$setId = $this->product['lid'];
				$orderProduct = OrderProduct::model()->find('order_id=:orderId and dpid=:dpid and set_id=:setId and product_order_status=0',array(':orderId'=>$order->lid,'dpid'=>$this->companyId,':setId'=>$setId));
			}else{
				$orderProduct = OrderProduct::model()->find('order_id=:orderId and dpid=:dpid and product_id=:productId and product_order_status=0',array(':orderId'=>$order->lid,'dpid'=>$this->companyId,':productId'=>$this->product['lid']));
			}
			if($orderProduct){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
		
	}
	public function deleteOrderProduct(){
		$criteria = new CDbCriteria;
		$criteria->addCondition('dpid=:dpid and site_id=:siteId and is_temp=:isTemp');
		$criteria->params = array(':dpid'=>$this->siteNo->dpid,':siteId'=>$this->siteNo->site_id,':isTemp'=>$this->siteNo->is_temp); 
		$criteria->order =  'lid desc';
		$order = Order::model()->find($criteria);
		
		if($this->product['type']){
			$orderProduct = OrderProduct::model()->updateAll(array('delete_flag'=>1),'order_id=:orderId and dpid=:dpid and set_id=:productId and product_order_status=0',array(':orderId'=>$order->lid,':dpid'=>$this->companyId,':productId'=>$this->product['lid']));
			if($orderProduct){
				return true;
			}else{
				return false;
			}
		}else{
			$orderProduct = OrderProduct::model()->find('order_id=:orderId and dpid=:dpid  and product_id=:productId and product_order_status=0',array(':orderId'=>$order->lid,':dpid'=>$this->companyId,':productId'=>$this->product['lid']));
			$orderProduct->delete_flag = 1;
			if($orderProduct->update()){
				return true;
			}else{
				return false;
			}
		}
	}
	/**
	 * 
	 * 大pad下单并打印
	 * 
	 */
	public static function createPadOrder($dpid,$goodsIds,$padId){
		$sellOff = array();
		$time = date('Y-m-d H:i:s',time());
		$db = Yii::app()->db;
        $transaction = $db->beginTransaction();
 		try {
 			 $se=new Sequence("site_no");
             $lid = $se->nextval();
             $se=new Sequence("temp_site");
             $site_id = $se->nextval();
             
             $code = SiteClass::getCode($dpid);
            $data = array(
                'lid'=>$lid,
                'dpid'=>$dpid,
                'create_at'=>date('Y-m-d H:i:s',time()),
                'is_temp'=>1,
                'site_id'=>$site_id,
                'status'=>'1',
                'code'=>$code,
                'number'=>1,
                'delete_flag'=>'0'
            );                            
            $db->createCommand()->insert('nb_site_no',$data);
            
            $sef=new Sequence("order_feedback");
            $lidf = $sef->nextval();
            $dataf = array(
                'lid'=>$lidf,
                'dpid'=>$dpid,
                'create_at'=>date('Y-m-d H:i:s',time()),
                'is_temp'=>1,
                'site_id'=>$site_id,
                'is_deal'=>'0',
                'feedback_id'=>0,
                'order_id'=>0,
                'is_order'=>'1',
                'feedback_memo'=>'开台',
                'delete_flag'=>'0'
            );
            $db->createCommand()->insert('nb_order_feedback',$dataf);  
            //生成订单
            $se=new Sequence("order");
            $orderId = $se->nextval();
            $data = array(
						'lid'=>$orderId,
						'dpid'=>$dpid,
						'site_id'=>$site_id,
						'create_at'=>$time,
						'is_temp'=>1,
						'order_status'=>2,
						'number'=>1,
						'update_at'=>$time,
						'remark'=>yii::t('app','无'),
						'taste_memo'=>"",
						);
			$db->createCommand()->insert('nb_order',$data);  
			//订单产品 $goodsIds = array('goods_id'=>goods_num,'set_id,1'=>set_num);
			$orderPrice = 0;
			
			foreach($goodsIds as $key=>$num){
				 $se=new Sequence("order_product");
	             $orderProductId = $se->nextval();
	             $goodsArr = explode(',', $key);
	             if(count($goodsArr) > 1){
	             	// 套餐
	             	$sql = 'select * from nb_product_set where dpid='.$dpid.' and lid='.$goodsArr[0];
	             	$result = $db->createCommand()->queryRow();
	             	if($result){
	             		if($result['store_number'] > 0&&$result['store_number'] < $num){
	             			throw new Exception(json_encode( array('status'=>false,'dpid'=>$dpid,'jobid'=>"0",'type'=>'local','msg'=>yii::t('app',$result['set_name'].'库存不足！')),JSON_UNESCAPED_UNICODE));
	             		}
	             	}else{
	             		throw new Exception(json_encode( array('status'=>false,'dpid'=>$dpid,'jobid'=>"0",'type'=>'local','msg'=>yii::t('app','没有找到该产品请清空后重新下单！')),JSON_UNESCAPED_UNICODE));
	             	}
	             	$productSets = self::getSetProductIds($dpid,$goodsArr[0]);
	             	foreach($productSets as $productSet){
	             		$orderProductData = array(
										'lid'=>$orderProductId,
										'dpid'=>$dpid,
										'create_at'=>$time,
										'order_id'=>$orderId,
										'set_id'=>$goodsArr[0],
										'product_id'=>$productSet['product_id'],
										'price'=>$productSet['price'],
										'update_at'=>$time,
										'amount'=>$num,
										'taste_memo'=>"",
										'product_order_status'=>1,
										);
					   $db->createCommand()->insert('nb_order_product',$orderProductData);
					   $orderPrice +=$productSet['price']*$num;
	             	}
	             	if($result['store_number'] > 0){
	             		$sql = 'update nb_product_set set store_number=store_number-'.$num.' where dpid='.$dpid.' and lid='.$goodsArr[0];
	             		 $db->createCommand($sql)->execute();
	             		 array_push($sellOff,array("product_id"=>$goodsArr[0],"type"=>"set","num"=>$result['store_number']-$num));
	             	}
 	             }else{
	             	//单品
	             	$sql = 'select * from nb_product where dpid='.$dpid.' and lid='.$goodsArr[0];
	             	$result = $db->createCommand()->queryRow();
	             	if($result){
	             		if($result['store_number'] > 0&&$result['store_number'] < $num){
	             			throw new Exception(json_encode( array('status'=>false,'dpid'=>$dpid,'jobid'=>"0",'type'=>'local','msg'=>yii::t('app',$result['product_name'].'库存不足！')),JSON_UNESCAPED_UNICODE));
	             		}
	             	}else{
	             		throw new Exception(json_encode( array('status'=>false,'dpid'=>$dpid,'jobid'=>"0",'type'=>'local','msg'=>yii::t('app','没有找到该产品请清空后重新下单！')),JSON_UNESCAPED_UNICODE));
	             	}
	             	$productPrice = self::getProductPrice($dpid,$key,0);
	             	 $orderProductData = array(
										'lid'=>$orderProductId,
										'dpid'=>$dpid,
										'create_at'=>$time,
										'order_id'=>$orderId,
										'set_id'=>0,
										'product_id'=>$goodsArr[0],
										'price'=>$productPrice,
										'update_at'=>$time,
										'amount'=>$num,
										'taste_memo'=>"",
										'product_order_status'=>1,
										);
					 $db->createCommand()->insert('nb_order_product',$orderProductData);
					 $orderPrice +=$productPrice*$num;
					 if($result['store_number'] > 0){
	             		$sql = 'update nb_product set store_number=store_number-'.$num.' where dpid='.$dpid.' and lid='.$goodsArr[0];
	             		 $db->createCommand($sql)->execute();
	             		  array_push($sellOff,array("product_id"=>$goodsArr[0],"type"=>"product","num"=>$result['store_number']-$num));
	             	 }
	             }
			}	
			$sql = 'update nb_order set should_total='.$orderPrice.' where lid='.$orderId.' and dpid='.$dpid;
			$db->createCommand($sql)->execute();
			$order = Order::model()->with('company')->find('t.lid=:id and t.dpid=:dpid' , array(':id'=>$orderId,':dpid'=>$dpid));
            $pad=Pad::model()->with('printer')->find('t.dpid=:dpid and t.lid=:lid',array(':dpid'=>$order->dpid,'lid'=>$padId));
           	if(!$pad){
           		throw new Exception(json_encode( array('status'=>false,'dpid'=>$order->dpid,'jobid'=>"0",'type'=>'local','msg'=>yii::t('app','没有找到该pad！')),JSON_UNESCAPED_UNICODE));
           	}
            //要判断打印机类型错误，必须是local。
//            if($pad->printer->printer_type!='1')
//            {
//                throw new Exception(json_encode( array('status'=>false,$order->dpid,'jobid'=>"0",'type'=>'local','msg'=>yii::t('app','必须是本地打印机！')),JSON_UNESCAPED_UNICODE));
//            }else{
                //前面加 barcode
                $precode="1D77021D6B04".strtoupper(implode('',unpack('H*', 'A'.$order->lid)))."001D2100".strtoupper(implode('',unpack('H*', 'A'.$order->lid)))."0A";
                $printserver="0";
                $printList = Helper::printList($order , $pad,$precode,$printserver);
                if(!$printList['status']){
                	throw new Exception(json_encode($printList,JSON_UNESCAPED_UNICODE));
                }
                //$printList2=array_merge($printList,array('sitenoid'=> $lid));
//            }	

			//估清产品通知
			if(!empty($sellOff)){
				Gateway::getOnlineStatus();
                $store = Store::instance('wymenu');
                $pads=Pad::model()->findAll(" dpid = :dpid and delete_flag='0' and pad_type in ('1','2')",array(":dpid"=>$dpid));
                //var_dump($pads);exit;
                $sendjsondata=json_encode(array("company_id"=>$dpid,
                    "do_id"=>"sell_off",
                    "do_data"=>$sellOff));
                //var_dump($sendjsondata);exit;
                foreach($pads as $pad)
                {
                    $clientId=$store->get("padclient_".$dpid.$pad->lid);
                    //var_dump($clientId,$print_data);exit;
                    if(!empty($clientId))
                    {                            
                        Gateway::sendToClient($clientId,$sendjsondata);
                    }
                } 
			}				
 			$transaction->commit();	
 			return json_encode($printList,JSON_UNESCAPED_UNICODE);
		 } catch (Exception $e) {
                $transaction->rollback(); //如果操作失败, 数据回滚
                throw new Exception(json_encode( array('status'=>false,'dpid'=>$dpid,'jobid'=>"0",'type'=>'local','msg'=>$e->getMessage()),JSON_UNESCAPED_UNICODE));
                //return $e->getMessage();
            } 
	}
	//获取套餐里选中单品的id
	public static function getSetProductIds($dpid,$setId){
		$sql = 'select product_id,price from nb_product_set_detail where dpid='.$dpid.' and set_id='.$setId.' and is_select=1 and delete_flag=0';
		$results = Yii::app()->createCommand($sql)->queryAll();
		return $results;
	}
}