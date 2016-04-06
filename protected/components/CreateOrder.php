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
							'create_at'=>date('Y-m-d H:i:s',time()),
							'is_temp'=>$this->siteNo->is_temp,
							'number'=>$this->siteNo->number,
							'update_at'=>date('Y-m-d H:i:s',time()),
							'remark'=>yii::t('app','无'),
							'taste_memo'=>"",
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
                                $orderProduct->update_at = date('Y-m-d H:i:s',time());
				$orderProduct->update();
			}else{
				$orderProduct = new OrderProduct;
				$orderProductData = array(
										'lid'=>$this->getMaxOrderProductId(),
										'dpid'=>$this->companyId,
										'create_at'=>date('Y-m-d H:i:s',time()),
										'order_id'=>$order->lid,
										'set_id'=>$setId,
										'product_id'=>$this->product['lid'],
										'price'=>$this->getProductPrice($this->companyId,$this->product['lid'],$this->product['type']),
										'update_at'=>date('Y-m-d H:i:s',time()),
										'amount'=>1,
										'taste_memo'=>"",
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
			$orderProduct = OrderProduct::model()->updateAll(array('delete_flag'=>1,'update_at'=>date('Y-m-d H:i:s',time())),'order_id=:orderId and dpid=:dpid and set_id=:productId and product_order_status=0',array(':orderId'=>$order->lid,':dpid'=>$this->companyId,':productId'=>$this->product['lid']));
			if($orderProduct){
				return true;
			}else{
				return false;
			}
		}else{
			$orderProduct = OrderProduct::model()->find('order_id=:orderId and dpid=:dpid  and product_id=:productId and product_order_status=0',array(':orderId'=>$order->lid,':dpid'=>$this->companyId,':productId'=>$this->product['lid']));
			$orderProduct->delete_flag = 1;
                        $orderProduct->update_at = date('Y-m-d H:i:s',time());
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
		$isTemp = $goodsIds['client_is_temp'];
		$site_id = $goodsIds['client_site_id'];
		$siteName = $goodsIds['client_site_name'];
                $reprint = $goodsIds['client_reprint'];
                $waitorname = $goodsIds['client_waitor_name'];
                
                //订单的状态，临时做下单时挂单状态，非临时做下单直接厨打
                $orderStatus="2";
                $orderPorductStatus="1";
                if($isTemp=="1")
                {
                    $orderStatus="1";
                    $orderPorductStatus="0";
                }
		unset($goodsIds['client_is_temp']);
		unset($goodsIds['client_site_id']);
		unset($goodsIds['client_site_name']);
                unset($goodsIds['client_reprint']);
                unset($goodsIds['client_waitor_name']);
                
        $siteStatus = self::getSiteStatus($site_id,$dpid,$isTemp);
        if(empty($siteStatus)){
        	throw new Exception(json_encode( array('status'=>false,'dpid'=>$dpid,'jobid'=>"0",'type'=>'local','msg'=>yii::t('app','请先开台后下单！'))));
        }
		$sellOff = array();
		$printOrderProducts = array();
		$time = date('Y-m-d H:i:s',time());
		$db = Yii::app()->db;
                //return json_encode(array('status'=>false,'msg'=>$waitorname));
        $transaction = $db->beginTransaction();
 		try {
 			if($site_id==0){
 				//未开台的临时台
 				$se=new Sequence("site_no");
                                $lid = $se->nextval();
                                $code = rand(1000,9999);
                                $se=new Sequence("temp_site");
                                $site_id = $se->nextval(); 
                                
                                $data = array(
                                    'lid'=>$lid,
                                    'dpid'=>$dpid,
                                    'create_at'=>date('Y-m-d H:i:s',time()),
                                    'update_at'=>date('Y-m-d H:i:s',time()),
                                    'is_temp'=>$isTemp,
                                    'site_id'=>$site_id,
                                    'status'=>$orderStatus,
                                    'code'=>$code,
                                    'number'=>1,
                                    'delete_flag'=>'0'
                                );                            
                                $db->createCommand()->insert('nb_site_no',$data);

                                $se=new Sequence("order");
                                $orderId = $se->nextval();
                                
                                $accountNo = self::getPadAccountNo($dpid,$site_id,0,$orderId);
                                $data = array(
										'lid'=>$orderId,
										'dpid'=>$dpid,
										'site_id'=>$site_id,
										'account_no'=>$accountNo,
										'create_at'=>$time,
			                            'username'=>$waitorname,
										'is_temp'=>$isTemp,
										'order_status'=>$orderStatus,
										'number'=>1,
										'update_at'=>$time,
										'remark'=>yii::t('app','无'),
										'taste_memo'=>"",
										);
							$db->createCommand()->insert('nb_order',$data);
 			}else{
 				//已经开台的固定台或临时台，
 				//$feedback_memo = yii::t('app','点单');
// 				//查找site表
// 				$sql = 'select * from nb_site_no where site_id='.$site_id.' and dpid='.$dpid.' order by lid desc';
// 				$siteModel = $db->createCommand($sql)->queryRow();
// 				if(!$siteModel){
// 					throw new Exception(json_encode( array('status'=>false,'dpid'=>$dpid,'msg'=>yii::t('app','存在该座次号,请重新选座次下单!')),JSON_UNESCAPED_UNICODE));
// 				}
// 				 //如果该座位 状态开台未下单
        //	            if(0 < $siteModel['status'] && $siteModel['status'] < 4){
        //	            	//先查找是否已经存在订单
        //	            	$sql = 'select * from nb_order where site_id='.$site_id.' and dpid='.$dpid.' and is_temp='.$isTemp.' order by lid desc';
                                $criteria = new CDbCriteria;
                                $criteria->condition =  ' t.order_status in ("1","2","3") and  t.dpid='.$dpid.' and t.site_id='.$site_id.' and t.is_temp='.$isTemp ;
                                $criteria->order = ' t.lid desc ';
                                $orderModel = Order::model()->find($criteria);
                                $criteria->condition =  ' t.status in ("1","2","3") and  t.dpid='.$dpid.' and t.site_id='.$site_id.' and t.is_temp='.$isTemp ;
                                $criteria->order = ' t.lid desc ';
                                $siteNo = SiteNo::model()->find($criteria);
                                
                                $siteNo->status=$orderStatus;
                                $siteNo->update_at=date('Y-m-d H:i:s',time());
                                $siteNo->save();
                                
                                if($isTemp=="0")
                                {
                                    $site=  Site::model()->find(" t.dpid=:dpid and t.lid=:siteid",array(':dpid'=>$siteNo->dpid,':siteid'=>$siteNo->site_id));
                                    $site->status=$orderStatus;
                                    $site->update_at=date('Y-m-d H:i:s',time());
                                    $site->save();
                                }
                                
                                if($orderModel){
                                        $orderId = $orderModel['lid'];
                                }else{
				            		 //生成订单
						            $se=new Sequence("order");
						            $orderId = $se->nextval();
						            $accountNo = self::getPadAccountNo($dpid,$site_id,0,$orderId);
						            $data = array(
			                                    'lid'=>$orderId,
			                                    'dpid'=>$dpid,
			                                    'account_no'=>$accountNo,
			                                    'site_id'=>$site_id,
			                                    'create_at'=>$time,
			                                    'username'=>$waitorname,
			                                    'is_temp'=>$isTemp,
			                                    'order_status'=>$orderStatus,
			                                    'number'=>$siteNo->number,
			                                    'update_at'=>$time,
			                                    'remark'=>yii::t('app','无'),
			                                    'taste_memo'=>"",
			                                    );
									$db->createCommand()->insert('nb_order',$data);						 
									 //更新site表状态
			//						$sql = 'update nb_site set status=1 where lid='.$site_id.' and dpid='.$dpid.' order by lid desc';
			//					    $db->createCommand($sql)->execute();
			//					    //更新site_no表状态
			//					    $sql = 'update nb_site_no set status=1 where site_id='.$site_id.' and dpid='.$dpid.' and is_temp='.$isTemp.' order by lid desc';
			//					    $db->createCommand($sql)->execute();
                                }
                            }
 			
            
//            return json_encode(array('status'=>false,'msg'=>"test"));
            
           
            
			//订单产品 $goodsIds = array('goods_id'=>goods_num,'set_id,1'=>set_num);
			$orderPrice = 0;
			
				foreach($goodsIds as $key=>$num){
				 	$se=new Sequence("order_product");
                    $orderProductId = $se->nextval();
                    $goodsArr = explode(',', $key);
                    if(count($goodsArr) > 1){
                       // 套餐
                       $sql = 'select * from nb_product_set where dpid='.$dpid.' and lid='.$goodsArr[0];
                       $result = $db->createCommand($sql)->queryRow();
                       if($result){
                               if($result['store_number']==0 ||($result['store_number'] > 0&&$result['store_number'] < 1)){
                                       throw new Exception(json_encode( array('status'=>false,'dpid'=>$dpid,'jobid'=>"0",'type'=>'local','msg'=>yii::t('app',$result['set_name'].'库存不足！仅剩'.$result['store_number'].'份！'))));
                               }
                       }else{
                               throw new Exception(json_encode( array('status'=>false,'dpid'=>$dpid,'jobid'=>"0",'type'=>'local','msg'=>yii::t('app','没有找到该产品请清空后重新下单！'))));
                       }
                       //添加选择的套餐明细
                   		foreach($num as $setDetail){
	                       	$detailId = key($setDetail);
	                       	$productSet = self::getSetProductId($dpid,$detailId);
		             		$orderProductData = array(
											'lid'=>$orderProductId,
											'dpid'=>$dpid,
											'create_at'=>$time,
											'order_id'=>$orderId,
											'set_id'=>$goodsArr[0],
											'product_id'=>$productSet['product_id'],
											'product_name'=>$productSet['product_name'],
											'product_pic'=>$productSet['main_picture'],
											'price'=>$productSet['price'],
											'update_at'=>$time,
											'amount'=>$productSet['number'],
											'taste_memo'=>"",
											'product_order_status'=>$orderPorductStatus,
											);
						  $db->createCommand()->insert('nb_order_product',$orderProductData);
						   
						   $se=new Sequence("order_product");
	                       $orderProductId = $se->nextval();
						   
						   $orderPrice +=$productSet['price']*$productSet['number'];
						   array_push($printOrderProducts,array('amount'=>$productSet['number'],'price'=>$productSet['price'],'product_name'=>ProductClass::getProductName($productSet['product_id'],$dpid)));
		             	}
                       
	             	if($result['store_number'] > 0){
	             		$sql = 'update nb_product_set set store_number=store_number-1 where dpid='.$dpid.' and lid='.$goodsArr[0];
	             		 $db->createCommand($sql)->execute();
	             		 array_push($sellOff,array("product_id"=>sprintf("%010d",$goodsArr[0]),"type"=>"set","num"=>$result['store_number']-1));
	             	}
 	             }else{
 	             	//单品 如果有口味  num-eq =>array('taste_id1','taste_id2') num 是数量 eq是序号 $goodsArr[0] 产品id
 	             	if($goodsArr[0]=='quandan'){
 	             		//全单口味
 	             		foreach($num as $tasteId=>$taste){
 	             			$se=new Sequence("order_taste");
                    		$orderTasteId = $se->nextval();
                    		$orderTasteData = array(
										'lid'=>$orderTasteId,
										'dpid'=>$dpid,
										'create_at'=>$time,
										'order_id'=>$orderId,
										'taste_id'=>$tasteId,
										'is_order'=>1,
										'update_at'=>$time,
										);
					  		 $db->createCommand()->insert('nb_order_taste',$orderTasteData);
 	             		}
 	             		
 	             	}else{
		             	$sql = 'select * from nb_product where dpid='.$dpid.' and lid='.$goodsArr[0];
		             	$result = $db->createCommand($sql)->queryRow();
		             	
		             	$productPrice = self::getProductPrice($dpid,$key,0);
		             	
		             	if(is_array($num)){
	                	//有口味$num = num-eq 格式
	                	foreach($num as $k=>$v){
	                		$numEq = explode('-', $k);
	                		$amount = $numEq[0];
	                		if($result){
			             		if($result['store_number']==0 || ($result['store_number'] > 0 && $result['store_number'] < $amount)){
			             			throw new Exception(json_encode( array('status'=>false,'dpid'=>$dpid,'jobid'=>"0",'type'=>'local','msg'=>yii::t('app',$result['product_name'].'库存不足！仅剩'.$result['store_number'].'份！'))));
			             		}
			             	}else{
			             		throw new Exception(json_encode( array('status'=>false,'dpid'=>$dpid,'jobid'=>"0",'type'=>'local','msg'=>yii::t('app','没有找到该产品请清空后重新下单！'))));
			             	}
			             	//每一个eq 生成一个订单
			             	$orderProductData = array(
											'lid'=>$orderProductId,
											'dpid'=>$dpid,
											'create_at'=>$time,
											'order_id'=>$orderId,
											'set_id'=>0,
											'product_id'=>$goodsArr[0],
											'product_name'=>$result['product_name'],
											'product_pic'=>$result['main_picture'],
											'price'=>$productPrice,
											'original_price'=>$result['original_price'],
											'update_at'=>$time,
											'amount'=>$amount,
											'taste_memo'=>"",
											'product_order_status'=>$orderPorductStatus,
											);
							 $db->createCommand()->insert('nb_order_product',$orderProductData);
							 $orderPrice +=$productPrice*$amount;
							 
							 array_push($printOrderProducts,array('amount'=>$amount,'price'=>$productPrice,'product_name'=>ProductClass::getProductName($goodsArr[0],$dpid)));
							 //该订单对应的多个口味
	                		 foreach($v as $tasteId=>$val){
							   if($tasteId){
								   $orderTastSe = new Sequence("order_taste");
			            		   $orderTasteId = $orderTastSe->nextval();
								   $orderTasteData = array(
								   						'lid'=>$orderTasteId,
								   						'dpid'=>$dpid,
								   						'create_at'=>$time,
                                                                                                                'update_at'=>$time,
								   						'taste_id'=>$tasteId,
								   						'order_id'=>$orderProductId,
								   						'is_order'=>0
								   						);
								   $db->createCommand()->insert('nb_order_taste',$orderTasteData);
	                	 	   }
	                	     }
		                	   $se=new Sequence("order_product");
			            	   $orderProductId = $se->nextval();
			            	   
			            	   if($result['store_number'] > 0){
			             		  $sql = 'update nb_product set store_number=store_number-'.$amount.' where dpid='.$dpid.' and lid='.$goodsArr[0];
			             		  $db->createCommand($sql)->execute();
			             		  array_push($sellOff,array("product_id"=>sprintf("%010d",$goodsArr[0]),"type"=>"product","num"=>$result['store_number']-$amount));
			             	   }
                                           $sqladd = 'update nb_product set order_number=order_number+'.$amount.',favourite_number=favourite_number+'.$amount.' where dpid='.$dpid.' and lid='.$goodsArr[0];
                                           $db->createCommand($sqladd)->execute();
	                	}
	             	}else{ 
	             		if($result){
		             		if($result['store_number']==0 || ($result['store_number'] > 0&&$result['store_number'] < $num)){
		             			throw new Exception(json_encode( array('status'=>false,'dpid'=>$dpid,'jobid'=>"0",'type'=>'local','msg'=>yii::t('app',$result['product_name'].'库存不足！仅剩'.$result['store_number'].'份！'))));
		             		}
		             	}else{
		             		throw new Exception(json_encode( array('status'=>false,'dpid'=>$dpid,'jobid'=>"0",'type'=>'local','msg'=>yii::t('app','没有找到该产品请清空后重新下单！'))));
		             	}
	             		 $orderProductData = array(
										'lid'=>$orderProductId,
										'dpid'=>$dpid,
										'create_at'=>$time,
										'order_id'=>$orderId,
										'set_id'=>0,
										'product_id'=>$goodsArr[0],
										'product_name'=>$result['product_name'],
										'product_pic'=>$result['main_picture'],
										'price'=>$productPrice,
										'original_price'=>$result['original_price'],
										'update_at'=>$time,
										'amount'=>$num,
										'taste_memo'=>"",
										'product_order_status'=>$orderPorductStatus,
										);
						 $db->createCommand()->insert('nb_order_product',$orderProductData);
						 $orderPrice +=$productPrice*$num;
						 
						 array_push($printOrderProducts,array('amount'=>$num,'price'=>$productPrice,'product_name'=>ProductClass::getProductName($goodsArr[0],$dpid)));
						 
						 if($result['store_number'] > 0){
		             		$sql = 'update nb_product set store_number=store_number-'.$num.' where dpid='.$dpid.' and lid='.$goodsArr[0];
		             		$db->createCommand($sql)->execute();
		             		array_push($sellOff,array("product_id"=>sprintf("%010d",$goodsArr[0]),"type"=>"product","num"=>$result['store_number']-$num));
		             	 }
                                 $sqladd = 'update nb_product set order_number=order_number+'.$num.',favourite_number=favourite_number+'.$num.' where dpid='.$dpid.' and lid='.$goodsArr[0];
                                           $db->createCommand($sqladd)->execute();
	             	}
	             }
               }
			}	
			$sql = 'update nb_order set should_total='.$orderPrice.' where lid='.$orderId.' and dpid='.$dpid;
			$db->createCommand($sql)->execute();
                        
//                        $sql = 'update nb_site_no set status='.$orderPrice.' where lid='.$orderId.' and dpid='.$dpid;
//			$db->createCommand($sql)->execute();
//			return json_encode(array('status'=>false,'msg'=>"test22"));
			//厨打
            if($orderId !='0')
            {
                $order = Order::model()->with('company')->find(' t.lid=:lid and t.dpid=:dpid and t.order_status in(1,2,3)' , array(':lid'=>$orderId,':dpid'=>$dpid));
                //Yii::app()->end(json_encode(array('status'=>false,'msg'=>"234")));                    
                if(empty($order))
                {
                    return json_encode(array('status'=>false,'msg'=>"该订单不存在"));
                }
            }
 //           return json_encode(array('status'=>false,'msg'=>"test2236"));
            if($order->is_temp=="0"){
 //               return json_encode(array('status'=>false,'msg'=>"test2235"));
            	$criteria = new CDbCriteria;
                $criteria->condition =  't.dpid='.$dpid.' and t.site_id='.$order->site_id.' and t.is_temp='.$order->is_temp ;
                $criteria->order = ' t.lid desc ';                    
                $siteNo = SiteNo::model()->find($criteria);
                //order site 和 siteno都需要更新状态 所以要取出来
                $criteria2 = new CDbCriteria;
                $criteria2->condition =  't.dpid='.$dpid.' and t.lid='.$order->site_id ;
                $criteria2->order = ' t.lid desc ';                    
                $site = Site::model()->with("siteType")->find($criteria2);
//            return json_encode(array('status'=>false,'msg'=>"test8"));
                $orderlist="0000000000,".$order->lid;
            	$printList = Helper::printKitchenAll3($order,$orderlist,$site,$siteNo,false);
            }else{
//                return json_encode(array('status'=>false,'msg'=>"test223"));
            	$pad=Pad::model()->with('printer')->find(' t.dpid=:dpid and t.lid=:lid',array(':dpid'=>$order->dpid,'lid'=>$padId));
            	 //前面加 barcode
                $precode="1D6B450B".strtoupper(implode('',unpack('H*', 'A'.$order->lid)))."0A".strtoupper(implode('',unpack('H*', 'A'.$order->lid)))."0A";
                $orderProducts = OrderProduct::getOrderProducts($order->lid,$order->dpid);
                //var_dump($orderProducts);exit;
                //return json_encode(array('status'=>false,'msg'=>"test2234"));
                $memo="清单";
                $printList = Helper::printList($order,$orderProducts , $pad,$precode,"0",$memo);
                               
            }
            if(!$printList['status']){
            	throw new Exception(json_encode($printList));
            }
                //$printList2=array_merge($printList,array('sitenoid'=> $lid));
//            }								
 			$transaction->commit();	
                        //估清产品通知
//			if(!empty($sellOff)){
//				Gateway::getOnlineStatus();
//                $store = Store::instance('wymenu');
//                $store=new Memcache;
//                $store->connect(Yii::app()->params['memcache']['server'],Yii::app()->params['memcache']['port']);                
                
//                $pads=Pad::model()->findAll(" dpid = :dpid and delete_flag='0' and pad_type in ('0','1','2')",array(":dpid"=>$dpid));
//                //var_dump($pads);exit;
//                $sendjsondata=json_encode(array("company_id"=>$dpid,
//                    "do_id"=>"sell_off",
//                    "do_data"=>$sellOff));
//                //var_dump($sendjsondata);exit;
//                foreach($pads as $pad)
//                {
//                    $clientId=$store->get("padclient_".$dpid.$pad->lid);
//                    //var_dump($clientId,$print_data);exit;
//                    if(!empty($clientId))
//                    {                            
//                        Gateway::sendToClient($clientId,$sendjsondata);
//                    }
//                } 
//			}
 			return json_encode($printList);
		 } catch (Exception $e) {
                $transaction->rollback(); //如果操作失败, 数据回滚
                throw new Exception($e->getMessage());
                //return $e->getMessage();
            } 
	}
	public static function getSiteStatus($siteId,$dpid,$istemp){
			if($istemp==0){
                    $criteria1 = new CDbCriteria;
                    $criteria1->condition =  ' t.dpid='.$dpid.' and t.lid='.$siteId ;
                    $site = Site::model()->find($criteria1);
                    if(!empty($site))
                    {
                    	$status=$site->status;
                    }else{
                        $status=0;
                    }
                }else{
                    $criteria2 = new CDbCriteria;
                    $criteria2->condition =  't.status in ("1","2","3") and t.dpid='.$dpid.' and t.site_id='.$siteId.' and t.is_temp='.$istemp ;
                    //$criteria2->condition =  't.status in ("9") and t.dpid='.$companyId.' and t.site_id='.$sid.' and t.is_temp='.$istemp ;
                    $criteria2->order = ' t.lid desc ';
                    $siteNo = SiteNo::model()->find($criteria2);
                    if(!empty($siteNo))
                    {
                        $status=$siteNo->status;
                    }else{
                        $status=0;
                    }
                }
            return $status;
	}
	//获取套餐里选中单品的id
	public static function getSetProductIds($dpid,$setId){
		$sql = 'select product_id,price,number from nb_product_set_detail where dpid='.$dpid.' and set_id='.$setId.' and is_select=1 and delete_flag=0';
		$results = Yii::app()->db->createCommand($sql)->queryAll();
		return $results;
	}
	//获取套餐明细
	public static function getSetProductId($dpid,$lid){
		$sql = 'select t.product_id,t.price,t.number,t1.product_name,t1.main_picture,t1.original_price from nb_product_set_detail t,nb_product t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid='.$dpid.' and t.lid='.$lid;
		$result = Yii::app()->db->createCommand($sql)->queryRow();
		return $result;
	}
	 /**
	  * 
	  * 订单流水单号
	  * 
	  */
	  public static function getPadAccountNo($dpid,$siteId,$isTemp,$orderId){
            $sql="select ifnull(min(account_no),'000000000000') as account_no from nb_order where dpid="
                    .$dpid." and site_id=".$siteId." and is_temp=".$isTemp
                    ." and order_status in ('1','2','3')";
            $ret=Yii::app()->db->createCommand($sql)->queryScalar();      
            if(empty($ret) || $ret=="0000000000")
            {
                $ret=substr(date('Ymd',time()),-6).substr("0000000000".$orderId, -6);
            }
            return $ret;
        }
}