<?php
class OrderList
{
	/**
	 * 
	 * $orderList = array{$key=>array(array(),array())} $key 为商品分类的lid
	 * 
	 */
	public $siteNoId = 0;
	public $dpid = 0;
	public $orderId = 0;
	public $orderStatus = 0;
	public $orderLockStatus = 0;
	public $orderList = array();
	
	public function __construct($dpid = 0,$siteNoId = 0){
		$this->dpid = $dpid; 
		$this->siteNoId = $siteNoId;
		$this->db = Yii::app()->db;
		$this->SiteNo();
		$this->Order();
		$this->OrderStatus();
	}
	public function SiteNo(){
		$sql = 'select * from nb_site_no where lid=:lid and dpid=:dpid';
		$conn = $this->db->createCommand($sql);
		$conn->bindValue(':lid',$this->siteNoId);
		$conn->bindValue(':dpid',$this->dpid);
		$this->siteNo = $conn->queryRow();
	}
	//获取订单信息
	public function Order(){
		$sql = 'select * from nb_order where dpid=:dpid and site_id=:siteId and is_temp=:isTemp order by lid desc';
		$conn = $this->db->createCommand($sql);
		$conn->bindValue(':dpid',$this->siteNo['dpid']);
		$conn->bindValue(':siteId',$this->siteNo['site_id']);
		$conn->bindValue(':isTemp',$this->siteNo['is_temp']);
		$this->order = $conn->queryRow();
	}
	
	//订单商品类别 type 是否下单 0 未下单 1 已下单
	public function OrderProductList($orderId,$type,$groupby = 0,$isOrder = 0){
			$result = array();
			//category_id = 0时是套餐
			if($groupby){
				$sql = 'select t.*, t1.category_id, t1.product_name, t1.main_picture, t1.original_price, t1.product_unit, t1.weight_unit, t1.is_weight_confirm, t1.printer_way_id from nb_order_product t,nb_product t1  where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and order_id=:orderId and t.delete_flag=0 and main_id=0 and set_id=0 and product_order_status='.$type.
				   ' union select t.lid,t.dpid,t.create_at,t.update_at,t.order_id,t.set_id,t.main_id,t.product_id,t.is_retreat,sum(t.price) as price,t.amount,t.zhiamount,t.is_waiting,t.weight,t.taste_memo,t.is_giving,t.is_print,t.delete_flag,t.product_order_status, 0 as category_id, t1.set_name as product_name, t1.main_picture,t2.product_name as original_price,0 as product_unit, 0 as weight_unit, 0 as is_weight_confirm, 0 as printer_way_id  from nb_order_product t left join nb_product_set t1 on t.set_id=t1.lid and t.dpid=t1.dpid left join nb_product t2 on t.product_id=t2.lid and t.dpid=t2.dpid where t.dpid=:dpid and order_id=:orderId and t.delete_flag=0 and main_id=0 and t.set_id > 0 and t.product_order_status='.$type;
				$sql .= ' group by t.set_id';
			}else{
				$sql = 'select t.*, t1.category_id, t1.product_name, t1.main_picture, t1.original_price, t1.product_unit, t1.weight_unit, t1.is_weight_confirm, t1.printer_way_id from nb_order_product t,nb_product t1  where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and order_id=:orderId and t.delete_flag=0 and main_id=0 and set_id=0 and product_order_status='.$type.
				   ' union select t.*, 0 as category_id, t1.set_name as product_name, t1.main_picture,t2.product_name as original_price,0 as product_unit, 0 as weight_unit, 0 as is_weight_confirm, 0 as printer_way_id  from nb_order_product t left join nb_product_set t1 on t.set_id=t1.lid and t.dpid=t1.dpid left join nb_product t2 on t.product_id=t2.lid and t.dpid=t2.dpid where t.dpid=:dpid and order_id=:orderId and t.delete_flag=0 and main_id=0 and t.set_id > 0 and t.product_order_status='.$type;
			}
			$conn = $this->db->createCommand($sql);
			$conn->bindValue(':dpid',$this->dpid);
			$conn->bindValue(':orderId',$orderId);
			$orderlist = $conn->queryAll();
			foreach($orderlist as $key=>$val){
				if($isOrder){
					$val['addition'] = self::GetOrderAddProduct($val['dpid'],$orderId,$val['product_id'],$type);
				}else{
					$val['hasAddition'] = self::GetOrderAddProduct($val['dpid'],$orderId,$val['product_id'],$type);
					$val['addition'] = self::GetAddProduct($val['dpid'],$val['product_id']);
				}
				$result[$val['category_id']][] = $val;
			}
			return $result;
	}
	//该产品加菜
	public static function GetAddProduct($dpid,$productId){
		$sql = 'select t.*,t1.main_picture,t1.product_name from nb_product_addition t,nb_product t1 where t.sproduct_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t.mproduct_id=:productId';
		$conn = Yii::app()->db->createCommand($sql);
		$conn->bindValue(':dpid',$dpid);
		$conn->bindValue(':productId',$productId);
		$addProducts = $conn->queryAll();
		return $addProducts;
	}
	//订单中加菜
	public static function GetOrderAddProduct($dpid,$orderId,$mainId,$type){
		$sql = 'select t.*,t1.main_picture,t1.product_name,t1.product_unit from nb_order_product t,nb_product t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t.order_id=:orderId and t.main_id=:mainId and t.product_order_status=:status';
		$conn = Yii::app()->db->createCommand($sql);
		$conn->bindValue(':dpid',$dpid);
		$conn->bindValue(':orderId',$orderId);
		$conn->bindValue(':mainId',$mainId);
		$conn->bindValue(':status',$type);
		$addProducts = $conn->queryAll();
		return $addProducts;
	}
	//获取该套餐的产品 array(1=>array(,),2=>array(,)) 1,2表示group_no
	public function GetSetProduct($setId){
		$result = array();
		$sql = 'select t.*, t1.product_name, t1.main_picture from nb_product_set_detail t left join nb_product t1 on t.product_id=t1.lid and t.dpid=t1.dpid where t.set_id=:setId and t.dpid=:dpid and t.delete_flag=0 and t1.delete_flag=0 order by is_select desc';
		$conn = $this->db->createCommand($sql);
		$conn->bindValue(':setId',$setId);
		$conn->bindValue(':dpid',$this->siteNo['dpid']);
		$results = $conn->queryAll();
		foreach($results as $key=>$val){
			$result[$val['group_no']][] = $val;
		}
		return $result;
	}
	//订单状态
	public function OrderStatus(){
		if($this->order){
			$this->orderStatus = $this->order['order_status'];
			$this->orderLockStatus = $this->order['lock_status'];
		}
	}
	//订单总额和总数量 type 已下单和为下单 isOrder 是否已加菜
	public function OrderPrice($type,$groupby = 0,$isOrder = 0){
		$price = 0;
		$num = 0;
		if($this->order){
			$products = $this->OrderProductList($this->order['lid'],$type,$groupby,$isOrder);
			foreach($products as $product){
				foreach($product as $val){
					if($isOrder&&!empty($val['addition'])){
						foreach($val['addition'] as $v){
							$price += $v['price']*$v['amount'];
							$num += $v['amount'];
						}
					}elseif(!$isOrder&&!empty($val['hasAddition'])){
						foreach($val['hasAddition'] as $v){
							$price += $v['price']*$v['amount'];
							$num += $v['amount'];
						}
					}
					$price += $val['price']*$val['amount'];
					$num += $val['amount'];
				}
			}
		}
		return $price.':'.$num;
	}
	//下单更新数量 锁定订单 $goodsIds = array('goods_id'=>'num','goods_id'=>'num','set_id,goods_id1-goods_id2-goods_id3'=>num) 如 array('102'=>2) goods_id =102 num = 2
	public static function UpdateOrder($dpid,$orderId,$goodsIds){
		if($goodsIds){
		$transaction = Yii::app()->db->beginTransaction();
		try{
			foreach($goodsIds as $key=>$val){
					$goodsArr = explode(',',$key);//如果数组元素个数是2 证明书套餐
					if(count($goodsArr)==2){
						$setId = $goodsArr[0];
						//$sql = 'delete from nb_order_product where order_id=:orderId and set_id=:setId and dpid=:dpid';
                                                $sql = 'update nb_order_product set delete_flag="1" where order_id=:orderId and set_id=:setId and dpid=:dpid';
						$connect = Yii::app()->db->createCommand($sql);
						$connect->bindValue(':setId',$setId);
						$connect->bindValue(':orderId',$orderId);
                        $connect->bindValue(':dpid',$dpid);
						$connect->execute();
						
						$goodsData = explode('-',$goodsArr[1]);
						foreach($goodsData as $goods){
							$se=new Sequence("order_product");
                        	$lid = $se->nextval();
							$insertData = array(
												'lid'=>$lid,
												'dpid'=>$dpid,
												'create_at'=>date('Y-m-d H:i:s',time()),
												'order_id'=>$orderId,
												'set_id'=>$setId,
												'product_id'=>$goods,
												'price'=>ProductSetClass::GetProductSetPrice($dpid,$setId,$goods),
												'update_at'=>date('Y-m-d H:i:s',time()),
												'amount'=>$val,
												'taste_memo'=>yii::t('app','无'),
												);
							Yii::app()->db->createCommand()->insert('nb_order_product',$insertData);
						}
							
					}else{
						$sql = 'update nb_order_product set amount = :amount where order_id = :orderId and product_id = :productId and dpid=:dpid';
						$conn = Yii::app()->db->createCommand($sql);
						$conn->bindValue(':amount',$val);
						$conn->bindValue(':orderId',$orderId);
						$conn->bindValue(':productId',$key);
                        $conn->bindValue(':dpid',$dpid);
						$conn->execute();
					}
				}
				$sql = 'update nb_order set lock_status=1 where lid = :orderId and dpid=:dpid';
				$conn = Yii::app()->db->createCommand($sql);
				$conn->bindValue(':orderId',$orderId);
                $conn->bindValue(':dpid',$dpid);
				$conn->execute();
			$transaction->commit();
			return true;
		  }catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			return false;
		  }
		}else{
			return false;
		}
	}
	
	//pad下单 并打印
	//下单更新数量 锁定订单 $goodsIds = array('goods_id'=>'num','goods_id'=>'num','set_id,goods_id1-goods_id2-goods_id3'=>num) 如 array('102'=>2) goods_id =102 num = 2
	public static function UpdatePadOrder($dpid,$orderId,$goodsIds){
		if($goodsIds){
		$transaction = Yii::app()->db->beginTransaction();
		try{
			foreach($goodsIds as $key=>$val){
				if(!strpos($key,'group')){//去除套餐中的 checkbox
					$goodsArr = explode(',',$key);//如果数组元素个数是2 证明书套餐
					if(count($goodsArr)==2){
						$setId = $goodsArr[0];
						//$sql = 'delete from nb_order_product where order_id=:orderId and set_id=:setId and dpid=:dpid';
                                                $sql = 'update nb_order_product set delete_flag="1" where order_id=:orderId and set_id=:setId and dpid=:dpid';
						$connect = Yii::app()->db->createCommand($sql);
						$connect->bindValue(':setId',$setId);
						$connect->bindValue(':orderId',$orderId);
                        $conn->bindValue(':dpid',$dpid);
						$connect->execute();
						
						$goodsData = explode('-',$goodsArr[1]);
						foreach($goodsData as $goods){
							$se=new Sequence("order_product");
                            $lid = $se->nextval();
							$insertData = array(
                                            'lid'=>$lid,
                                            'dpid'=>$dpid,
                                            'create_at'=>date('Y-m-d H:i:s',time()),
                                            'order_id'=>$orderId,
                                            'set_id'=>$setId,
                                            'product_id'=>$goods,
                                            'price'=>ProductSetClass::GetProductSetPrice($dpid,$setId,$goods),
                                            'update_at'=>date('Y-m-d H:i:s',time()),
                                            'amount'=>$val,
                                            'taste_memo'=>yii::t('app','无'),
                                            );
							Yii::app()->db->createCommand()->insert('nb_order_product',$insertData);
						}
							
					}else{
						$sql = 'update nb_order_product set amount = :amount where order_id = :orderId and product_id = :productId and dpid=:dpid';
						$conn = Yii::app()->db->createCommand($sql);
						$conn->bindValue(':amount',$val);
						$conn->bindValue(':orderId',$orderId);
						$conn->bindValue(':productId',$key);
                        $conn->bindValue(':dpid',$dpid);
						$conn->execute();
					}
				}
			}
			
			$transaction->commit();
			return true;
		  }catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			return false;
		  }
		}else{
			return false;
		}
	}
	//下单更新数量 解除锁定订单 $goodsIds = array('goods_id'=>'num','goods_id'=>'num') 如 array('102'=>2) goods_id =102 num = 2
	public function ConfirmOrder($orderId,$goodsIds){
		if($goodsIds){
			foreach($goodsIds as $key=>$val){
				$goodsArr = explode('-',$key);//如果数组元素个数是2 证明书套餐
				if(count($goodsArr)==2){
					$sql = 'update nb_order_product set amount = :amount , product_order_status=1 where order_id = :orderId and set_id=:setId and product_id = :productId and dpid=:dpid';
					$conn = Yii::app()->db->createCommand($sql);
					$conn->bindValue(':amount',$val);
					$conn->bindValue(':orderId',$orderId);
					$conn->bindValue(':setId',$goodsArr[0]);
					$conn->bindValue(':productId',$goodsArr[1]);
                                        $conn->bindValue(':dpid',$this->dpid);
					$conn->execute();
				}else{
					$sql = 'update nb_order_product set amount = :amount , product_order_status=1 where order_id = :orderId and product_id = :productId and dpid=:dpid';
					$conn = Yii::app()->db->createCommand($sql);
					$conn->bindValue(':amount',$val);
					$conn->bindValue(':orderId',$orderId);
					$conn->bindValue(':productId',$key);
                                        $conn->bindValue(':dpid',$this->dpid);
					$conn->execute();
				}
			}
			$sql = 'update nb_order set lock_status=0, order_status=2 where lid = :orderId  and dpid=:dpid';
			$conn = Yii::app()->db->createCommand($sql);
			$conn->bindValue(':orderId',$orderId);
                        $conn->bindValue(':dpid',$this->dpid);
			$conn->execute();
			//siteNo 表
			$sql = 'update nb_site_no set status=2 where lid = :siteNoId and dpid=:dpid';
			$conn = Yii::app()->db->createCommand($sql);
			$conn->bindValue(':siteNoId',$this->siteNo['lid']);
			$conn->bindValue(':dpid',$this->dpid);
			$conn->execute();
			//site 表
			$sql = 'update nb_site set status=2 where lid = :siteId and dpid=:dpid';
			$conn = Yii::app()->db->createCommand($sql);
			$conn->bindValue(':siteId',$this->siteNo['site_id']);
			$conn->bindValue(':dpid',$this->dpid);
			$conn->execute();
			return true;
		}else{
			return false;
		}
	}
	//获取种类的名称
	public static function GetCatoryName($catoryId,$dpid){
		$sql = 'select category_name from  nb_product_category where lid = :lid and dpid=:dpid';
		$conn = Yii::app()->db->createCommand($sql);
		$conn->bindValue(':lid',$catoryId);
        $conn->bindValue(':dpid',$dpid);
		$catoryName = $conn->queryScalar();
		return $catoryName;
	}
	
	//订单商品类别 type 是否下单 0 未下单 1 已下单
	public static function WxPayOrderList($dpid,$orderId,$type,$groupby = 0,$isOrder = 0){
			$result = array();
			//category_id = 0时是套餐
			if($groupby){
				$sql = 'select t.*, t1.category_id, t1.product_name, t1.main_picture, t1.original_price, t1.product_unit, t1.weight_unit, t1.is_weight_confirm, t1.printer_way_id from nb_order_product t,nb_product t1  where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and order_id=:orderId and t.delete_flag=0 and main_id=0 and set_id=0 and product_order_status='.$type.
				   ' union select t.lid,t.dpid,t.create_at,t.update_at,t.order_id,t.set_id,t.main_id,t.product_id,t.is_retreat,sum(t.price) as price,t.amount,t.zhiamount,t.is_waiting,t.weight,t.taste_memo,t.is_giving,t.is_print,t.delete_flag,t.product_order_status, 0 as category_id, t1.set_name as product_name, t1.main_picture,t2.product_name as original_price,0 as product_unit, 0 as weight_unit, 0 as is_weight_confirm, 0 as printer_way_id  from nb_order_product t left join nb_product_set t1 on t.set_id=t1.lid and t.dpid=t1.dpid left join nb_product t2 on t.product_id=t2.lid and t.dpid=t2.dpid where t.dpid=:dpid and order_id=:orderId and t.delete_flag=0 and main_id=0 and t.set_id > 0 and t.product_order_status='.$type;
				$sql .= ' group by t.set_id';
			}else{
				$sql = 'select t.*, t1.category_id, t1.product_name, t1.main_picture, t1.original_price, t1.product_unit, t1.weight_unit, t1.is_weight_confirm, t1.printer_way_id from nb_order_product t,nb_product t1  where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and order_id=:orderId and t.delete_flag=0 and main_id=0 and set_id=0 and product_order_status='.$type.
				   ' union select t.*, 0 as category_id, t1.set_name as product_name, t1.main_picture,t2.product_name as original_price,0 as product_unit, 0 as weight_unit, 0 as is_weight_confirm, 0 as printer_way_id  from nb_order_product t left join nb_product_set t1 on t.set_id=t1.lid and t.dpid=t1.dpid left join nb_product t2 on t.product_id=t2.lid and t.dpid=t2.dpid where t.dpid=:dpid and order_id=:orderId and t.delete_flag=0 and main_id=0 and t.set_id > 0 and t.product_order_status='.$type;
			}
			$conn = Yii::app()->db->createCommand($sql);
			$conn->bindValue(':dpid',$dpid);
			$conn->bindValue(':orderId',$orderId);
			$orderlist = $conn->queryAll();
			foreach($orderlist as $key=>$val){
				if($isOrder){
					$val['addition'] = self::GetOrderAddProduct($val['dpid'],$orderId,$val['product_id'],$type);
				}else{
					$val['hasAddition'] = self::GetOrderAddProduct($val['dpid'],$orderId,$val['product_id'],$type);
					$val['addition'] = self::GetAddProduct($val['dpid'],$val['product_id']);
				}
				$result[$val['category_id']][] = $val;
			}
			return $result;
	}
	public static function WxPayOrderPrice($products){
		$price = 0;
		$num = 0;
		foreach($products as $product){
			foreach($product as $val){
				if(!empty($val['addition'])){
					foreach($val['addition'] as $v){
						$price += $v['price']*$v['amount'];
						$num += $v['amount'];
					}
				}
				$price += $val['price']*$val['amount'];
				$num += $val['amount'];
			}
		}
		return $price.':'.$num;
	}
        
        public static function createOrder($companyId,$orderId,$orderList,$orderStatus,$productList,$orderTasteIds,$orderTasteMemo,$callId,Order $order,Site $site,SiteNo $siteNo)
        {
            $sellOff = array();                
            //////////////
            //return json_encode(array('status'=>false,'msg'=>"test1"));
            /////////////
            $time=date('Y-m-d H:i:s',time());
            $db=Yii::app()->db;
            $transaction = $db->beginTransaction();           
                 
            try {
                $se=new Sequence("order_product");
                $setaste=new Sequence("order_taste");
                $orderProductStatus=0;
                if($orderStatus>1)
                {
                    $orderProductStatus=1;
                }
                ///先删除所有为下单的临时菜品，后插入
                //不能删除，否则无法同步
                $sql = 'delete from nb_order_product where dpid='.$companyId.' and product_order_status=0 and order_id in ('.$orderList.")";
                //$sql='update nb_order_product set delete_flag="1" where dpid='.$companyId.' and product_order_status=0 and order_id ='.$orderId;
                $result = $db->createCommand($sql)->execute();                            
                    //return array('status'=>false,'msg'=>"test11");
                //插入订单单品
                if(!empty($productList))
                {
                    $productListArr=explode(";",$productList);
                        
                    foreach($productListArr as $tvalue)
                    {   
                        //更新库存//失败则返回
                        $productDetailArr=explode(",",$tvalue);
                        $productdata=Product::model()->find('lid=:lid and dpid=:dpid' , array(':lid'=>$productDetailArr[3],':dpid'=>$companyId));
                        //return json_encode(array('status'=>true,'msg'=>$productdata->store_number.$productDetailArr[3].$productDetailArr[4]));
                        if($productdata->store_number==0 || ($productdata->store_number >0 && $productdata->store_number< $productDetailArr[5] ))
                        {
                            $transaction->rollback();
                            return array('status'=>false,'msg'=>$productdata->product_name."数量不足");
                        }
                        ////////套餐数量判断//////////////////////////
                        
                        //不是临时挂单就更新库存，更新下单数和点赞数，发送更新库存消息
                        if($orderStatus>1)
                        {
                            $productdata->order_number=$productdata->order_number+$productDetailArr[5];
                            $productdata->favourite_number=$productdata->favourite_number+$productDetailArr[5];                            
                            if($productdata->store_number>0)
                            {
                                $productdata->store_number=$productdata->store_number-$productDetailArr[5];                            
                                array_push($sellOff,array("product_id"=>sprintf("%010d",$productDetailArr[3]),"type"=>"product","num"=>$productdata->store_number));
                            }
                            //确保和云端的数量同步
                            if(Yii::app()->params['cloud_local']=='l')
                            {
                                DataSync::cloudFirt($companyId, "update nb_product set order_number=order_number+".$productDetailArr[5].
                                        ",favourite_number=favourite_number+".$productDetailArr[5].
                                        ",store_number=store_number-".$productDetailArr[5].
                                        " where dpid=".$companyId." and lid=".$productdata->lid);
                            }
                            ///套餐数量减////////////
                        }
                        $productdata->save();
                        //return array('status'=>false,'msg'=>"test111333");
                        if($productDetailArr[4]=="0")
                        {
                            //插入
                            $orderProductId="";
                            if($productDetailArr[0]=="0000000000")
                            {
                                $orderProductId = $se->nextval();
                            }else{
                                $orderProductId = $productDetailArr[0];
                            }
                            //orderid
                            $orderTempId=$productDetailArr[1];
                            if($orderTempId)
                            {
                                $orderTempId=$orderId;
                            }
                            //插入一条
                            $orderProductData = array(
                                                'lid'=>$orderProductId,
                                                'dpid'=>$companyId,
                                                'create_at'=>$time,
                                                'order_id'=>$orderTempId,
                                                'set_id'=>$productDetailArr[2],
                                                'product_id'=>$productDetailArr[3],
                                                'offprice'=>$productDetailArr[6],
                                                'original_price'=>$productDetailArr[12],
                                                'price'=>$productDetailArr[7],
                                                'update_at'=>$time,
                                                'amount'=>$productDetailArr[5],
                                                'is_giving'=>$productDetailArr[8],
                                                'product_status'=>$productDetailArr[8],//添加cf
                                                'taste_memo'=>$productDetailArr[11],
                                                'product_order_status'=>$orderProductStatus,
                                                );
                            //return array('status'=>false,'msg'=>"test14444".implode("..",$orderProductData));
                            $db->createCommand()->insert('nb_order_product',$orderProductData);                                
                        }
                        //修改为先删除后插入，防止以后一个菜品被分开点多分。
                        ////else{
//                                //更新
//                                $orderProductData=  OrderProduct::model()->find('lid=:lid and dpid=:dpid' , array(':lid'=>$productDetailArr[0],':dpid'=>$companyId));
//                                $orderProductData->price=$productDetailArr[3];
//                                $orderProductData->amount=$productDetailArr[2];
//                                $orderProductData->is_giving=$productDetailArr[4];
//                                $orderProductData->taste_memo=$productDetailArr[6];
//                                $orderProductData->save();
//                            }
                        //insert taste//delete and insert taste
                        //return array('status'=>false,'msg'=>"nbproductinsert after");
                        $orderProductTasteIds=str_replace("|",",",$productDetailArr[10]);
                        //return array('status'=>false,'msg'=>$orderProductTasteIds);
                        if(!empty($orderProductTasteIds))
                        {
                            $orderProductTasteIds=substr($orderProductTasteIds, 0,strlen($orderProductTasteIds)-1);
                            $orderProductTasteArr=explode(",",$orderProductTasteIds);
                            $modelids=$db->createCommand("select taste_id,lid from nb_order_taste where dpid=".$companyId." and is_order=0 and order_id = ".$productDetailArr[0])->queryAll();
                            $modelids1=array();
                            foreach ($modelids as $id)
                            {
                                //array_push($modelids1, $id["lid"]);
                                $modelids1[$id["taste_id"]]=$id["lid"];
                            }
                            $sql2 = 'delete from nb_order_taste where dpid='.$companyId.' and is_order=0 and order_id = '.$productDetailArr[0];
                            //$sql2 = 'update nb_order_taste set delete_flag="1" where dpid='.$companyId.' and is_order=0 and order_id = '.$productDetailArr[0];
                            //return json_encode(array('status'=>true,'msg'=>$orderProductTasteIds));
                            $result = $db->createCommand($sql2)->execute();
                            //重新插入  
                            if(!empty($orderProductTasteArr))
                            {
                                foreach($orderProductTasteArr as $tvalue)
                                {    
                                    $orderProductTasteId="";
                                    if(!empty($modelids1[$tvalue]))
                                    {
                                        $orderProductTasteId=$modelids1[$tvalue];
                                    }else{
                                        $orderProductTasteId = $setaste->nextval();
                                    }
                                    //return json_encode(array('status'=>false,'msg'=>$productDetailArr[0]."|".$tvalue."|".$orderTasteId));                                

                                    $orderProductTasteAll = array(
                                                            'lid'=>$orderProductTasteId,
                                                            'dpid'=>$companyId,
                                                            'create_at'=> $time,
                                                            'update_at'=> $time,
                                                            'order_id'=>$orderProductId,
                                                            'taste_id'=>$tvalue,
                                                            'is_order'=>"0",
                                                            'delete_flag'=>"0",
                                                            );
                                    $db->createCommand()->insert('nb_order_taste',$orderProductTasteAll);
                                }
                            }
                        }
                        //return array('status'=>false,'msg'=>"after taste insert");
                    }
                }

                if(!empty($site))
                {
                    if($site->status<$orderStatus)
                    {
                        $site->status=$orderStatus;
                        $site->update_at= $time;
                        $site->save();
                    }
                }
                if(!empty($siteNo))
                {
                    if($siteNo->status<$orderStatus)
                    {
                        $siteNo->status=$orderStatus;
                        $siteNo->update_at= $time;
                        $siteNo->save();
                    }
                }
                if($order->order_status<$orderStatus)
                {
                    $order->order_status=$orderStatus;
                    $order->update_at= $time;
                }
                $order->taste_memo=$orderTasteMemo;
                $order->callno=$callId;
                $order->save();
                
                //删除全单口味
                $orderTasteIds=str_replace("|",",",$orderTasteIds);
                //return array('status'=>false,'msg'=>$orderTasteIds);
                if(!empty($orderTasteIds))
                {
                    //return json_encode(array('status'=>false,'msg'=>$orderTasteIds));
                    $orderTasteIds=substr($orderTasteIds, 0,strlen($orderTasteIds)-1);
                    $orderTasteArr=explode(",",$orderTasteIds);
                    $modelids=$db->createCommand("select taste_id,lid from nb_order_taste where dpid=".$companyId." and is_order=1 and order_id =".$orderId)->queryAll();
                    $modelids1=array();
                    foreach ($modelids as $id)
                    {
                        //array_push($modelids1, $id["lid"]);
                        $modelids1[$id["taste_id"]]=$id["lid"];
                    }
                    $sql = 'delete from nb_order_taste where dpid='.$companyId.' and is_order=1 and order_id ='.$orderId;
                    //$sql = 'update nb_order_taste set delete_flag="1" where dpid='.$companyId.' and is_order=1 and order_id ='.$orderId;
                    $result = $db->createCommand($sql)->execute();
                    //重新插入                        
                    //return json_encode(array('status'=>false,'msg'=>"test3"));
                    //$se=new Sequence("order_taste");
                    if(!empty($orderTasteArr))
                    {
                        foreach($orderTasteArr as $tvalue)
                        {
                            $orderTasteId="";
                            if(!empty($modelids1[$tvalue]))
                            {
                                $orderTasteId=$modelids1[$tvalue];
                            }else{
                                $orderTasteId = $setaste->nextval();
                            }
                            $orderTasteAll = array(
                                                    'lid'=>$orderTasteId,
                                                    'dpid'=>$companyId,
                                                    'create_at'=> $time,
                                                    'update_at'=> $time,
                                                    'order_id'=>$orderId,
                                                    'taste_id'=>$tvalue,
                                                    'is_order'=>"1",
                                                    'delete_flag'=>"0",
                                                    );
                            $db->createCommand()->insert('nb_order_taste',$orderTasteAll);
                        }
                    }
                }
                //return array('status'=>false,'msg'=>"after order taste save");
//                if(!$savejson["status"])
//                {
//                    $ret=json_encode($savejson);
//                }else{
                if($orderStatus>1)
                {
                    //return array('status'=>false,'msg'=>"before printkitchen all".$orderList);
                    $ret=  Helper::printKitchenAll3($order,$orderList,$site,$siteNo,false);
                    //return array('status'=>false,'msg'=>"after printkitchen all");
                    if(!$ret['status'])
                    {
                        $transaction->rollback();
                    }else{
                        $transaction->commit();
                    }
                }else{
                    $ret = array('status'=>true,'msg'=>"保存成功",'jobs'=>array());
                    $transaction->commit();
                }       
                //估清产品通知
//                if(!empty($sellOff)){
//                    //return array('status'=>false,'msg'=>"沽清：".$sellOff);
//                    Gateway::getOnlineStatus();
//                    $store = Store::instance('wymenu');
//                    $pads=Pad::model()->findAll(" dpid = :dpid and delete_flag='0' and pad_type in ('0','1','2')",array(":dpid"=>$dpid));
//                    //var_dump($pads);exit;
//                    $sendjsondata=json_encode(array("company_id"=>$dpid,
//                        "do_id"=>"sell_off",
//                        "do_data"=>$sellOff));
//                    //var_dump($sendjsondata);exit;
//                    foreach($pads as $pad)
//                    {
//                        $clientId=$store->get("padclient_".$dpid.$pad->lid);
//                        //var_dump($clientId,$print_data);exit;
//                        if(!empty($clientId))
//                        {                            
//                            Gateway::sendToClient($clientId,$sendjsondata);
//                        }
//                    } 
//                }
                //return array('status'=>true,'msg'=>"保存成功",'jobs'=>array());
                return $ret;
            } catch (Exception $ex) {
                $transaction->rollback();
                return array('status'=>false,'msg'=>$e->getMessage(),'jobs'=>array());
            }                
        }
        
        
}