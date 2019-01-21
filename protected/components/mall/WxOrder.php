<?php 
/**
 * 
 * 
 * 微信端订单类
 * //堂吃必须有siteId
 *$type 0 临时座 1 堂吃 2 外卖 3 预约
 *$normalPromotionIds 菜品普通优惠id
 *
 * 
 */
class WxOrder
{
	public $dpid;
	public $userId;
	public $user;
	public $siteId;
	public $siteNoId;
	public $type;
	public $number;
	public $cartNumber = 0;// 购物车产品数量
	public $isTemp = 0;
	public $levelDiscount = 1;//会员等级折扣
	public $seatingFee = 0;
	public $packingFee = 0;
	public $freightFee = 0;
	public $hasfullsent = false;
	public $cupon = false;
	public $fullsent = '0-0-0';//满送满减信息
	public $fullMinus = 0; //满减金额
	public $fullSentProduct = array(); //满送产品
	public $cart = array();
	public $normalPromotionIds = array();
	public $tastes = array();//原始产品口味
	public $others = array();//其他参数
	public $productTastes = array();//处理后的产品口味
	public $setDetail = array();  // 套餐详情 set_id - product_id - number - price
	public $productSetDetail = array();// 处理套餐详情 array(product_id=>array(set_id,product_id,number,price))
	public $siteNo = false;
	public $order = false;
	public $orderSuccess = false;
	
	public function __construct($dpid,$user,$siteId = null,$type = 1,$number = 1,$productSet = array(),$tastes = array(),$others){
		$this->dpid = $dpid;
		$this->userId = $user['lid'];
		$this->user = $user;
		$this->siteId = $siteId;
		$this->type = $type;
		$this->number = $number;
		$this->tastes = $tastes;
		$this->others = $others;
		$this->setDetail = $productSet;
		$this->isTemp = 1;
		$this->getLevelDiscount();
		$this->getCupon();
		$this->getFullsent();
		$this->getCart();
		$this->dealTastes();
		$this->dealProductSet();
		if($this->type==1){
			$this->isTemp = 0;
			$this->getSite();
			$this->getSeatingFee();
		}elseif(in_array($this->type, array(2,7,8))){
			$this->orderOpenSite();
			$this->getPackingFee();
			$this->getFreightFee();
		}elseif($this->type==3){
			$this->orderOpenSite();
			$this->getPackingFee();
		}else{
			$this->orderOpenSite();
		}
	}
	/**
	 *获取会员等级折扣 
	 */
	public function getLevelDiscount(){
		$this->levelDiscount = WxBrandUser::getUserDiscount($this->user,$this->type);
	}
	/**
	 *获取优惠券信息
	 */
	public function getCupon(){
		$cupoinId = $this->others['cuponId'];
		if($cupoinId){
			$now = date('Y-m-d H:i:s',time());
			$cbArr = explode('-', $cupoinId);
			$cbLid = $cbArr[0];
			$cbDpid = $cbArr[1];
			$sql = 'select t.lid,t.dpid,t.cupon_id,t1.cupon_money,t1.min_consumer from nb_cupon_branduser t,nb_cupon t1 where t.cupon_id=t1.lid and t.dpid=t1.dpid and  t.lid='.$cbLid.
			' and t.dpid='.$cbDpid.' and t.valid_day <= "'.$now.'" and "'.$now.'" <= t.close_day and t1.delete_flag=0 and t1.is_available=0';
			$this->cupon = Yii::app()->db->createCommand($sql)->queryRow();
		}
	}
	/**
	 *处理满减满送活动
	 */
	public function getFullsent(){
		if($this->others['fullsent']!='0-0-0'){
			$now = date('Y-m-d H:i:s',time());
			$this->hasfullsent = true;
			$fullsentArr = explode('-', $this->others['fullsent']);
			$fullType = $fullsentArr[0];
			$fullsentId = $fullsentArr[1];
			$fullsentdetailId = $fullsentArr[2];
			$fullsentObj = WxFullSent::checkFullsent($fullsentId,$this->dpid);
			if(!$fullsentObj){
				throw new Exception('满减满送活动不存在');
			}
			$this->fullsent = $fullsentObj;
			if($now < $fullsentObj['begin_time']){
				throw new Exception('满减满送活动未开始');
			}
			if($now > $fullsentObj['end_time']){
				throw new Exception('满减满送活动已结束');
			}
			if($fullType==0){
				$fullsentdetail = WxFullSent::checkFullsentproduct($fullsentdetailId,$fullsentId,$this->dpid);
				if(!$fullsentdetail){
					throw new Exception('该增送产品已下架');
				}
				$this->fullSentProduct = $fullsentdetail;
			}else{
				$this->fullMinus = $fullsentObj['extra_cost'];
			}
		}
	}
	/**
	 * 获取购物车产品信息
	 */
	public function getCart(){
		if($this->type==2){
			$hideCate = WxCategory::getHideCate($this->dpid, 2);
			if(empty($hideCate)){
				$sql = 'select t.lid,t.dpid,t.product_id,t.is_set,t.num,t.promotion_type,t.promotion_id,t.to_group,t1.product_name,t1.main_picture,t1.is_member_discount,t1.member_price,t1.original_price from nb_cart t,nb_product t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t.user_id=:userId and t.is_set=0 and t1.is_show=1 and t1.is_show_wx=1 and t1.delete_flag=0';
			}else{
				$categoryStr = join(',', $hideCate);
				$sql = 'select t.lid,t.dpid,t.product_id,t.is_set,t.num,t.promotion_type,t.promotion_id,t.to_group,t1.product_name,t1.main_picture,t1.is_member_discount,t1.member_price,t1.original_price from nb_cart t,nb_product t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t1.category_id not in ('.$categoryStr.') and t.user_id=:userId and t.is_set=0 and t1.is_show=1 and t1.is_show_wx=1 and t1.delete_flag=0';
			}
		}elseif($this->type==6){
			$hideCate = WxCategory::getHideCate($this->dpid, 3);
			if(empty($hideCate)){
				$sql = 'select t.lid,t.dpid,t.product_id,t.is_set,t.num,t.promotion_type,t.promotion_id,t.to_group,t1.product_name,t1.main_picture,t1.is_member_discount,t1.member_price,t1.original_price from nb_cart t,nb_product t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t.user_id=:userId and t.is_set=0 and t1.is_show=1 and t1.is_show_wx=1 and t1.delete_flag=0';
			}else{
				$categoryStr = join(',', $hideCate);
				$sql = 'select t.lid,t.dpid,t.product_id,t.is_set,t.num,t.promotion_type,t.promotion_id,t.to_group,t1.product_name,t1.main_picture,t1.is_member_discount,t1.member_price,t1.original_price from nb_cart t,nb_product t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t1.category_id not in ('.$categoryStr.') and t.user_id=:userId and t.is_set=0 and t1.is_show=1 and t1.is_show_wx=1 and t1.delete_flag=0';
			}
		}else{
			$hideCate = WxCategory::getHideCate($this->dpid, 4);
			if(empty($hideCate)){
				$sql = 'select t.lid,t.dpid,t.product_id,t.is_set,t.num,t.promotion_type,t.promotion_id,t.to_group,t1.product_name,t1.main_picture,t1.is_member_discount,t1.member_price,t1.original_price from nb_cart t,nb_product t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t.user_id=:userId and t.is_set=0 and t1.is_show=1 and t1.is_show_wx=1 and t1.delete_flag=0';
			}else{
				$categoryStr = join(',', $hideCate);
				$sql = 'select t.lid,t.dpid,t.product_id,t.is_set,t.num,t.promotion_type,t.promotion_id,t.to_group,t1.product_name,t1.main_picture,t1.is_member_discount,t1.member_price,t1.original_price from nb_cart t,nb_product t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t1.category_id not in ('.$categoryStr.') and t.user_id=:userId and t.is_set=0 and t1.is_show=1 and t1.is_show_wx=1 and t1.delete_flag=0';
			}
		}
		$sql .= ' union select t.lid,t.dpid,t.product_id,t.is_set,t.num,t.promotion_type,t.promotion_id,t.to_group,t1.set_name as product_name,t1.main_picture,t1.is_member_discount,t1.member_price,t1.set_price as original_price from nb_cart t,nb_product_set t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t.user_id=:userId and t.is_set=1 and t1.is_show=1 and t1.is_show_wx=1 and t1.delete_flag=0';
		$results = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$this->dpid)
				  ->bindValue(':userId',$this->userId)
				  ->queryAll();
		foreach($results as $k=>$result){
			$store = $this->checkStoreNumber($this->dpid,$result['product_id'],$result['is_set'],$result['num']);
			if(!$store['status']){
				throw new Exception($store['msg']);
			}
			$results[$k]['store_number'] = $store['msg'];
			if($result['promotion_id'] > 0){
				$promotionType = $result['promotion_type'];
				$productPromotion = WxPromotion::getProductPromotion($this->dpid,$promotionType,$result['promotion_id'],$result['product_id'],$result['is_set']);
				if(!$productPromotion){
					throw new Exception('该产品已无优惠活动');
				}
				$promotion = WxPromotion::isPromotionValid($this->dpid,$promotionType,$result['promotion_id'],$this->type);
				if(!$promotion){
					throw new Exception('优惠活动已结束');
				}
				if($result['to_group']==2){
					// 会员等级活动
					$promotionUser = WxPromotion::getPromotionUser($this->dpid, $this->user['user_level_lid'], $result['promotion_id']);
					if(empty($promotionUser)){
						throw new Exception('会员不是该等级,不能享受优惠');
					}
				}
				if($promotionType=='promotion'){
					$productPrice = WxPromotion::getPromotionPrice($result['dpid'],$this->userId,$result['product_id'],$result['is_set'],$result['promotion_id'],$result['to_group']);
					$results[$k]['price'] = $productPrice['price'];
					$results[$k]['promotion'] = $productPrice;
				}elseif($promotionType=='sent'){
					$results[$k]['price'] = '0.00';
					$results[$k]['promotion'] = array('promotion_type'=>0,'price'=>0,'promotion_info'=>array());
				}else{
					$results[$k]['price'] = $results[$k]['original_price'];
					$results[$k]['promotion'] = array('promotion_type'=>0,'price'=>0,'promotion_info'=>array());
				}
			}else{
				if($this->type==2){
					$result['member_price'] = $result['original_price'];
					$results[$k]['member_price'] = $results[$k]['original_price'];
				}
				$results[$k]['price'] = $result['member_price'];
				$results[$k]['promotion'] = array('promotion_type'=>0,'price'=>0,'promotion_info'=>array());
			}
			$this->cartNumber +=$result['num'];
		}
		$this->cart = $results;
	}
	//判断产品库存
	public function checkStoreNumber($dpid,$productId,$isSet,$num){
		if($isSet){
			$sql = 'select * from nb_product_set where lid=:productId and dpid=:dpid and delete_flag=0';
			$product = Yii::app()->db->createCommand($sql)
					->bindValue(':dpid',$dpid)
					->bindValue(':productId',$productId)
					->queryRow();
			if($product['store_number']==0){
				return array('status'=>false,'msg'=>$product['set_name'].'该产品已售罄!');
			}
		}else{
			$sql = 'select * from nb_product where lid=:productId and dpid=:dpid and delete_flag=0';
			$product = Yii::app()->db->createCommand($sql)
						->bindValue(':dpid',$dpid)
						->bindValue(':productId',$productId)
						->queryRow();
			if($product['store_number']==0){
				return array('status'=>false,'msg'=>$product['product_name'].'该产品已售罄!');
			}
		}
		
		if($product['store_number'] > 0){
			if($num > $product['store_number']){
				return array('status'=>false,'msg'=>'超出库存,库存剩余'.$product['store_number'].'!');
			}
		}
		return array('status'=>true,'msg'=>$product['store_number']);
	}
	//处理订单口味
	public function dealTastes(){
		if(!empty($this->tastes)){
			foreach($this->tastes as $taste){
				$tasteArr = explode('-',$taste);
				if(count($tasteArr)>1){
					$cartId = (int)$tasteArr[0];
					$this->productTastes[$cartId][] = $tasteArr;
				}
			}
		}
	}
	//处理订单口味
	public function dealProductSet(){
		if(!empty($this->setDetail)){
			foreach($this->setDetail as $detail){
				$detailArr = explode('-',$detail);
				if(count($detailArr) > 1){
					$cartId = $detailArr[0];
					$this->productSetDetail[$cartId][] = $detailArr;
				}
			}
			// 套餐内单品
			foreach ($this->productSetDetail as $k=>$setdetail){
				$totalOriginPrice = 0;
				$setId = $setdetail[0][1];
				$productSet = WxProduct::getProductSet($setId, $this->dpid);
				foreach ($setdetail as $key=>$val){
					$productId = $val[2];
					$num = $val[3];
					$setProduct = WxProduct::getProduct($productId, $this->dpid);
					$totalOriginPrice += $setProduct['original_price']*$num;
					$this->productSetDetail[$k][$key]['product_name'] = $setProduct['product_name'];
					$this->productSetDetail[$k][$key]['main_picture'] = $setProduct['main_picture'];
					$this->productSetDetail[$k][$key]['original_price'] = $setProduct['original_price'];
				}
				$this->productSetDetail[$k]['set_name'] = $productSet['set_name'];
				$this->productSetDetail[$k]['total_original_price'] = $totalOriginPrice;
			}
		}
	}
	//获取座位状态
	public function getSite(){
		$this->siteNo = WxSite::getSiteNo($this->siteId,$this->dpid);
		if(!$this->siteNo){
			throw new Exception('请联系服务员,开台后下单');
		}
		if(!in_array($this->siteNo['status'],array(1,2))){
			throw new Exception('请联系服务员,开台后下单');
		}else{
			$this->siteNoId = $this->siteNo['lid'];
		}
	}
	//获取餐位费
	public function getSeatingFee(){
		$isSeatingFee = WxCompanyFee::get(1,$this->dpid);
		if($isSeatingFee){
			$this->seatingFee = $isSeatingFee['fee_price'];
		}else{
			$this->seatingFee = 0;
		}
	}
	//获取打包费
	public function getPackingFee(){
		$isPackingFee = WxCompanyFee::get(2,$this->dpid);
		if($isPackingFee){
			$this->packingFee = $isPackingFee['fee_price'];
		}else{
			$this->packingFee = 0;
		}
	}
	//获取运费
	public function getFreightFee(){
		$isFreightFee = WxCompanyFee::get(3,$this->dpid);
		if($isFreightFee){
			$this->freightFee = $isFreightFee['fee_price'];
		}else{
			$this->freightFee = 0;
		}
	}
	//座位开台
	public function orderOpenSite(){
		$result = SiteClass::openSite($this->dpid,$this->number,$this->isTemp,$this->siteId,'0','0');
		if($this->isTemp==1){
			$this->siteId = $result['siteid'];
		}
	}
	//生成订单
	public function createOrder(){
		$orderArr = array();
		$time = time();
		$orderPrice = 0;
		$realityPrice = 0;
		$memdiscount = 0;
	    $orderProductStatus = 9;
	    $orderTime = $this->others['orderTime'];
	    $appointmentTime = date('Y-m-d H:i:s',strtotime('+'. $orderTime*60 .' seconds'));
	    $remark = $this->others['remark'];
	    if(!empty($remark)){
	    	$remark = Helper::dealString($remark);
	    }
	    if($this->type==1){
	    	// 餐桌
	    	$orderProductStatus = 1;
    		$se = new Sequence("order");
    		$orderId = $se->nextval();
    		$accountNo = self::getAccountNo($this->dpid,$this->siteId,0,$orderId);
    		$insertOrderArr = array(
    				'lid'=>$orderId,
    				'dpid'=>$this->dpid,
    				'create_at'=>date('Y-m-d H:i:s',$time),
    				'update_at'=>date('Y-m-d H:i:s',$time),
    				'account_no'=>$accountNo,
    				'user_id'=>$this->userId,
    				'site_id'=>$this->siteNoId,
    				'is_temp'=>$this->isTemp,
    				'number'=>$this->number,
    				'order_status'=>2,
    				'callno'=>'',
    				'order_type'=>$this->type,
    				'takeout_typeid'=>$this->others['takeout'],
    				'appointment_time'=>$appointmentTime,
    				'remark'=>$remark,
    				'taste_memo'=>''
    		);
    		$result = Yii::app()->db->createCommand()->insert('nb_order', $insertOrderArr);
    		$orderArr = $insertOrderArr;
	    }else{
			// 不带餐桌
			$se = new Sequence("order");
			$orderId = $se->nextval();
			$accountNo = self::getAccountNo($this->dpid,$this->siteId,0,$orderId);
			$insertOrderArr = array(
					'lid'=>$orderId,
					'dpid'=>$this->dpid,
					'create_at'=>date('Y-m-d H:i:s',$time),
					'update_at'=>date('Y-m-d H:i:s',$time),
					'account_no'=>$accountNo,
					'user_id'=>$this->userId,
					'site_id'=>$this->siteId,
					'is_temp'=>$this->isTemp,
					'number'=>$this->number,
					'callno'=>'',
					'order_status'=>2,
					'order_type'=>$this->type,
					'takeout_typeid'=>$this->others['takeout'],
					'appointment_time'=>$appointmentTime,
					'remark'=>$remark,
					'taste_memo'=>''
			);
			$result = Yii::app()->db->createCommand()->insert('nb_order', $insertOrderArr);
			$orderArr = $insertOrderArr;
			//外卖订单地址
			if(in_array($this->type,array(2,3,7,8))){
				$address = WxAddress::getDefault($this->userId,$this->user['dpid']);
				if($address){
					WxOrderAddress::addOrderAddress($orderId,$this->dpid,$address);
				}
			}
		}
		//整单口味
		$orderArr['taste'] = array();
		if(isset($this->productTastes[0]) && !empty($this->productTastes[0])){
			$otArr = array();
			foreach($this->productTastes[0] as $ordertaste){
				if($ordertaste[2] > 0){
					$orderPrice +=$ordertaste[2];
					$realityPrice +=$ordertaste[2];
				}
				$se = new Sequence("order_taste");
				$orderTasteId = $se->nextval();
				$orderTasteData = array(
						'lid'=>$orderTasteId,
						'dpid'=>$this->dpid,
						'create_at'=>date('Y-m-d H:i:s',$time),
						'update_at'=>date('Y-m-d H:i:s',$time),
						'taste_name'=>$ordertaste[3],
						'taste_id'=>$ordertaste[1],
						'order_id'=>$orderId,
						'is_order'=>1,
				);
				$result = Yii::app()->db->createCommand()->insert('nb_order_taste',$orderTasteData);
				array_push($otArr, $orderTasteData);
			}
			$orderArr['taste'] = $otArr;
		}
		$levelDiscount = $this->levelDiscount;
		// mainId 用于区分不同明细的套餐
		$mainId = 1;
		foreach($this->cart as $cart){
			$isSent = false; // 是赠送产品
			$isPromotion = false;// 是否普通优惠活动
			$ortherPrice = 0;// 产品加价
			$oortherPrice = 0;// 产品原价加价
			$prodiscount = 1; //活动折扣
			$pptype = $cart['promotion_type'];
			if($pptype=='sent'){
				$isSent = true;
			}
			if($cart['promotion_id'] > 0){
				$isPromotion = true;
				$proinfo = $cart['promotion']['promotion_info'];
				if(!empty($proinfo)){
					$protype = $proinfo['is_discount'];
					if($protype > 0){
						$prodiscount = $proinfo['promotion_discount'];
					}
				}
			}
			
			if(!$isPromotion&&$cart['is_member_discount']){
				$cart['price'] = $cart['price']*$levelDiscoun;
			}
			$cartPrice = $cart['price'];
			$orderPrice += $cartPrice*$cartNum;
			if($cart['is_set'] > 0){
				$setPrice = $cartPrice;
				// 套餐 插入套餐明细  计算单个套餐数量  $detail = array(cart_id,set_id,product_id,num,price); price 套餐内加价
				$setName = $this->productSetDetail[$cart['lid']]['set_name'];
				$totalProductPrice = $this->productSetDetail[$cart['lid']]['total_original_price'];
				unset($this->productSetDetail[$cart['lid']]['set_name']);
				unset($this->productSetDetail[$cart['lid']]['total_original_price']);
				foreach ($this->productSetDetail[$cart['lid']] as $i=>$detail){
					$dprice = $detail[4]*$prodiscount;
					if(!$isPromotion&&$cart['is_member_discount']){
						$memdiscount += number_format($dprice*(1-$levelDiscount)*$cartNum,2);
						$dprice = number_format($dprice*$levelDiscount,2);
					}
					if(!$isSent){
						$orderPrice += $dprice*$cartNum;
					}
					$oortherPrice += $detail[4];
					$itemPrice = Helper::dealProductPrice($detail['original_price'], $totalProductPrice, $setPrice);
						
					$se = new Sequence("order_product");
					$orderProductId = $se->nextval();
					$orderProductData = array(
							'lid'=>$orderProductId,
							'dpid'=>$this->dpid,
							'create_at'=>date('Y-m-d H:i:s',$time),
							'update_at'=>date('Y-m-d H:i:s',$time),
							'order_id'=>$orderId,
							'set_id'=>$cart['product_id'],
							'private_promotion_lid'=>$cart['promotion_id'],
							'main_id'=>$mainId,
							'product_id'=>$detail[2],
							'product_name'=>$detail['product_name'],
							'product_pic'=>$detail['main_picture'],
							'price'=>$itemPrice+$dprice,
							'original_price'=>$detail['original_price']+$detail[4],
							'amount'=>$detail[3]*$cartNum,
							'zhiamount'=>$cartNum,
							'product_order_status'=>$orderProductStatus,
							'taste_memo'=>$setName,
					);
					Yii::app()->db->createCommand()->insert('nb_order_product',$orderProductData);
				}
				if($cart['store_number'] > 0){
					$sql = 'update nb_product_set set store_number =  store_number-'.$cartNum.' where lid='.$cart['product_id'].' and dpid='.$this->dpid.' and delete_flag=0';
					Yii::app()->db->createCommand($sql)->execute();
				}
				$mainId++;
			}else{
				$se = new Sequence("order_product");
				$orderProductId = $se->nextval();
				//单品 插入产品口味 cartid-produtId-tasteId-tasteprice-tastename
				if(isset($this->productTastes[$cart['lid']]) && !empty($this->productTastes[$cart['lid']])){
					foreach($this->productTastes[$cart['lid']] as $taste){
						if($taste[3] > 0){
							$dprice = $taste[3]*$prodiscount;
							if(!$isPromotion&&$cart['is_member_discount']){
								$memdiscount += number_format($dprice*(1-$levelDiscount)*$cartNum,2);
								$dprice = number_format($dprice*$levelDiscount,2);
							}
							if(!$isSent){
								$orderPrice += $dprice*$cartNum;
							}
							$ortherPrice += $dprice;
							$oortherPrice += $taste[3];
						}
						$se = new Sequence("order_taste");
						$orderTasteId = $se->nextval();
						$orderTasteData = array(
								'lid'=>$orderTasteId,
								'dpid'=>$this->dpid,
								'create_at'=>date('Y-m-d H:i:s',$time),
								'update_at'=>date('Y-m-d H:i:s',$time),
								'taste_name'=>$taste[4],
								'taste_id'=>$taste[2],
								'order_id'=>$orderProductId,
								'is_order'=>0,
						);
						Yii::app()->db->createCommand()->insert('nb_order_taste',$orderTasteData);
					}
				}
				
				$orderProductData = array(
						'lid'=>$orderProductId,
						'dpid'=>$this->dpid,
						'create_at'=>date('Y-m-d H:i:s',$time),
						'update_at'=>date('Y-m-d H:i:s',$time),
						'order_id'=>$orderId,
						'set_id'=>0,
						'private_promotion_lid'=>$cart['promotion_id'],
						'product_id'=>$cart['product_id'],
						'product_name'=>$cart['product_name'],
						'product_pic'=>$cart['main_picture'],
						'price'=>$cart['price']+$ortherPrice,
						'original_price'=>$cart['original_price']+$oortherPrice,
						'amount'=>$cart['num'],
						'product_order_status'=>$orderProductStatus,
				);
				Yii::app()->db->createCommand()->insert('nb_order_product',$orderProductData);
		
				if($cart['store_number'] > 0){
					$sql = 'update nb_product set store_number =  store_number-'.$cart['num'].' where lid='.$cart['product_id'].' and dpid='.$this->dpid.' and delete_flag=0';
					Yii::app()->db->createCommand($sql)->execute();
				}
			}
			
			//插入订单优惠
			if($isPromotion){
				$promotion = $cart['promotion']['promotion_info'];
				if(!empty($promotion)){
					$se = new Sequence("order_product_promotion");
					$orderproductpromotionId = $se->nextval();
					$orderProductPromotionData =array(
							'lid'=>$orderproductpromotionId,
							'dpid'=>$this->dpid,
							'create_at'=>date('Y-m-d H:i:s',$time),
							'update_at'=>date('Y-m-d H:i:s',$time),
							'order_id'=>$orderId,
							'order_product_id'=>$orderProductId,
							'account_no'=>$accountNo,
							'promotion_type'=>$cart['promotion']['promotion_type'],
							'promotion_id'=>$promotion['poromtion_id'],
							'promotion_money'=>$promotion['promotion_money'],
							'can_cupon'=>$promotion['can_cupon'],
							'delete_flag'=>0,
					);
					Yii::app()->db->createCommand()->insert('nb_order_product_promotion',$orderProductPromotionData);
				}
			}
			$realityPrice +=($cart['original_price']+$oortherPrice)*$cartNum;
		}
		if($this->type!=1){
			// 满送产品
			if(!empty($this->fullSentProduct)){
				$se = new Sequence("order_product");
				$orderProductId = $se->nextval();
				
				$orderProductData = array(
						'lid'=>$orderProductId,
						'dpid'=>$this->dpid,
						'create_at'=>date('Y-m-d H:i:s',$time),
						'update_at'=>date('Y-m-d H:i:s',$time),
						'order_id'=>$orderId,
						'set_id'=>0,
						'product_id'=>$this->fullSentProduct['product_id'],
						'product_name'=>$this->fullSentProduct['product_name'],
						'product_pic'=>$this->fullSentProduct['main_picture'],
						'price'=>$this->fullSentProduct['price'],
						'original_price'=>$this->fullSentProduct['original_price'],
						'amount'=>1,
						'product_order_status'=>$orderProductStatus,
				);
				Yii::app()->db->createCommand()->insert('nb_order_product',$orderProductData);
			}
			if(($this->type==3) && $this->seatingFee > 0){
				$se = new Sequence("order_product");
				$orderProductId = $se->nextval();
				$orderProductData = array(
						'lid'=>$orderProductId,
						'dpid'=>$this->dpid,
						'create_at'=>date('Y-m-d H:i:s',$time),
						'update_at'=>date('Y-m-d H:i:s',$time),
						'order_id'=>$orderId,
						'set_id'=>0,
						'product_id'=>0,
						'product_name'=>'餐位费',
						'product_pic'=>'',
						'product_type'=>1,
						'price'=>$this->seatingFee,
						'original_price'=>$this->seatingFee,
						'amount'=>$this->number,
						'product_order_status'=>9,
				);
				Yii::app()->db->createCommand()->insert('nb_order_product',$orderProductData);
				$orderPrice +=  $this->seatingFee*$this->number;
				$realityPrice += $this->seatingFee*$this->number;
			}elseif(in_array($this->type, array(2,7,8))){
				if($this->packingFee > 0){
					$se = new Sequence("order_product");
					$orderProductId = $se->nextval();
					$orderProductData = array(
							'lid'=>$orderProductId,
							'dpid'=>$this->dpid,
							'create_at'=>date('Y-m-d H:i:s',$time),
							'update_at'=>date('Y-m-d H:i:s',$time),
							'order_id'=>$orderId,
							'set_id'=>0,
							'product_id'=>0,
							'product_name'=>'包装费',
							'product_pic'=>'',
							'product_type'=>2,
							'price'=>$this->packingFee,
							'original_price'=>$this->packingFee,
							'amount'=>$this->cartNumber,
							'product_order_status'=>9,
					);
					Yii::app()->db->createCommand()->insert('nb_order_product',$orderProductData);
					$orderPrice +=  $this->packingFee*$this->cartNumber;
					$realityPrice += $this->packingFee*$this->cartNumber;
				}
				if($this->freightFee > 0){
					$se = new Sequence("order_product");
					$orderProductId = $se->nextval();
					$orderProductData = array(
							'lid'=>$orderProductId,
							'dpid'=>$this->dpid,
							'create_at'=>date('Y-m-d H:i:s',$time),
							'update_at'=>date('Y-m-d H:i:s',$time),
							'order_id'=>$orderId,
							'set_id'=>0,
							'product_id'=>0,
							'product_name'=>'配送费',
							'product_pic'=>'',
							'product_type'=>3,
							'price'=>$this->freightFee,
							'original_price'=>$this->freightFee,
							'amount'=>1,
							'product_order_status'=>9,
					);
					Yii::app()->db->createCommand()->insert('nb_order_product',$orderProductData);
					$orderPrice +=  $this->freightFee;
					$realityPrice += $this->freightFee;
				}
			}
			// 会员折扣
			if($memdiscount > 0){
				$se = new Sequence("order_account_discount");
				$orderAccountId = $se->nextval();
				$orderAccountData = array(
						'lid'=>$orderAccountId,
						'dpid'=>$this->dpid,
						'create_at'=>date('Y-m-d H:i:s',$time),
						'update_at'=>date('Y-m-d H:i:s',$time),
						'order_id'=>$orderId,
						'account_no'=>$accountNo,
						'discount_title'=>'会员折扣',
						'discount_id'=>0,
						'discount_money'=>$memdiscount,
				);
				Yii::app()->db->createCommand()->insert('nb_order_account_discount',$orderAccountData);
			}
			// 满减优惠
			if($this->fullMinus > 0){
				$se = new Sequence("order_account_discount");
				$orderAccountId = $se->nextval();
				$orderAccountData = array(
						'lid'=>$orderAccountId,
						'dpid'=>$this->dpid,
						'create_at'=>date('Y-m-d H:i:s',$time),
						'update_at'=>date('Y-m-d H:i:s',$time),
						'order_id'=>$orderId,
						'account_no'=>$accountNo,
						'discount_title'=>$this->fullsent['title'],
						'discount_id'=>0,
						'discount_money'=>$this->fullMinus,
				);
				Yii::app()->db->createCommand()->insert('nb_order_account_discount',$orderAccountData);
				$orderPrice = $orderPrice - $this->fullMinus;
				if($orderPrice < 0){
					$orderPrice = 0;
				}
			}
			
			$sql = 'update nb_site_no set status=2 where site_id='.$this->siteId.' and dpid='.$this->dpid;
			Yii::app()->db->createCommand($sql)->execute();
		}else{
			$sql = 'update nb_site set status=2 where lid='.$this->siteId.' and dpid='.$this->dpid;
			Yii::app()->db->createCommand($sql)->execute();
			$sql = 'update nb_site_no set status=2 where lid='.$this->siteNoId.' and dpid='.$this->dpid;
			Yii::app()->db->createCommand($sql)->execute();
		}
		$orderArr['reality_total'] = $realityPrice;
		$orderArr['should_total'] = $orderPrice;
		$payPrice = $orderPrice;
		// 现金券
		if($this->cupon && $payPrice>0){
			$order = $orderArr;
			$payMoney = self::updateOrderCupon($this->cupon, $order, $payPrice, $this->user['card_id']);
			$payPrice -= $payMoney;
		}
		
		// 使用储值
		if($this->others['yue'] && $payPrice>0){
			$remainMoney = WxBrandUser::getYue($this->user);
			if($remainMoney > 0){
				$order = $orderArr;
				$payMoney = self::reduceYue($this->user,$order,$payPrice);
				$payPrice -= $payMoney;
			}
		}
		$sql = 'update nb_order set should_total='.$orderPrice.',reality_total='.$realityPrice.' where lid='.$orderId.' and dpid='.$this->dpid;
		Yii::app()->db->createCommand($sql)->execute();
		//清空购物车
		$sql = 'delete from nb_cart where user_id='.$this->userId.' and dpid='.$this->dpid;
		Yii::app()->db->createCommand($sql)->execute();
		if($payPrice <= 0){
			$this->orderSuccess = true;
		}
		$this->order = $orderArr;
		return $orderId;
	}
	public static function getOrder($orderId,$dpid){
		$sql = 'select * from nb_order where lid=:lid and dpid=:dpid';
		$order = Yii::app()->db->createCommand($sql)
				  ->bindValue(':lid',$orderId)
				  ->bindValue(':dpid',$dpid)
				  ->queryRow();
		if($order){
			$order['taste'] = self::getOrderTaste($orderId, $dpid, 1);
		}
	    return $order;
	}
	/**
	 * 
	 * 获取未付款的 订单产品
	 * 
	 */
	 public static function getNoPayOrderProduct($orderId,$dpid){
		$sql = 'select * from nb_order_product where order_id=:lid and dpid=:dpid and delete_flag=0 and product_order_status=9';
		$order = Yii::app()->db->createCommand($sql)
				  ->bindValue(':lid',$orderId)
				  ->bindValue(':dpid',$dpid)
				  ->queryAll();
	    return $order;
	}
	/**
	 * 
	 * 通过siteid获取所有未支付订单
	 * 
	 */
	public static function getOrderBySiteId($siteId,$dpid){
		$sql = 'select * from nb_order where site_id=:siteId and dpid=:dpid and order_status=2 and is_temp=0 and order_type=1 order by lid desc';
		$orders = Yii::app()->db->createCommand($sql)
				  ->bindValue(':siteId',$siteId)
				  ->bindValue(':dpid',$dpid)
				  ->queryAll();
		foreach ($orders as $key=>$order){
			$orders[$key]['taste'] = self::getOrderTaste($order['lid'], $dpid, 1);
			$orders[$key]['product_list'] = self::getOrderProduct($order['lid'],$dpid);
		}
	    return $orders;
	}
	// 获取订单产品 
	public static function getOrderProduct($orderId,$dpid){
		$sql = 'select lid,order_id,private_promotion_lid,main_id,set_id,price,amount,zhiamount,is_retreat,product_id,product_name,product_pic,original_price from nb_order_product  where order_id = :orderId and dpid = :dpid and product_type=0 and delete_flag=0 and set_id=0';
		$sql .=' union select t.lid,t.order_id,t.private_promotion_lid,t.main_id,t.set_id,sum(t.price*t.amount) as price,t.amount,t.zhiamount,t.is_retreat,t.product_id,t1.set_name as product_name,t.product_pic,t.original_price from nb_order_product t,nb_product_set t1  where t.set_id=t1.lid and t.dpid=t1.dpid and t.order_id = :orderId and t.dpid = :dpid and t.product_type=0 and t.delete_flag=0 and t.set_id>0 group by t.set_id,t.main_id';
		$orderProduct = Yii::app()->db->createCommand($sql)
					    ->bindValue(':orderId',$orderId)
					    ->bindValue(':dpid',$dpid)
					    ->queryAll();
		foreach ($orderProduct as $k=>$product){
			if($product['set_id']>0){
				$oProduct = WxProduct::getProductSet($product['set_id'], $dpid);
				$productSet = self::getOrderProductSetDetail($product['order_id'],$dpid,$product['set_id'],$product['main_id']);
				$orderProduct[$k]['phs_code'] = $oProduct['pshs_code'];
				$orderProduct[$k]['is_member_discount'] = $oProduct['is_member_discount'];
				$orderProduct[$k]['detail'] = $productSet;
			}else{
				$oProduct = WxProduct::getProduct($product['product_id'], $dpid);
				$productTaste = self::getOrderTaste($product['lid'],$dpid,0);
				$orderProduct[$k]['phs_code'] = $oProduct['phs_code'];
				$orderProduct[$k]['is_member_discount'] = $oProduct['is_member_discount'];
				$orderProduct[$k]['taste'] = $productTaste;
			}
		}
	    return $orderProduct;
	}
	// 获取订单产品数据(放入缓存的订单产品数据)
	public static function getOrderProductData($orderId,$dpid){
		$sql = 'select *,"" as set_name,sum(price*amount/zhiamount) as set_price from nb_order_product where order_id=' . $orderId . ' and dpid='.$dpid.' and set_id > 0 and delete_flag=0 group by set_id ,main_id'.
				' union select *,"" as set_name,"0.00" as set_price from nb_order_product where order_id=' . $orderId . ' and dpid='.$dpid.' and set_id = 0 and delete_flag=0';
		$orderProduct = Yii::app ()->db->createCommand ( $sql )->queryAll ();
		foreach ( $orderProduct as $k => $product ) {
			$sql = 'select create_at,taste_id,order_id,is_order,taste_name as name from nb_order_taste where order_id=' . $product ['lid'] . ' and dpid='.$dpid.' and is_order=0 and delete_flag=0';
			$orderProductTaste = Yii::app ()->db->createCommand ( $sql )->queryAll ();
			$orderProduct [$k] ['product_taste'] = $orderProductTaste;
			$sql = 'select promotion_title,promotion_type,promotion_id,promotion_money,can_cupon from nb_order_product_promotion where order_id=' . $product ['lid'] . ' and dpid='.$dpid.' and delete_flag=0';
			$orderProductPromotion = Yii::app ()->db->createCommand ( $sql )->queryAll ();
			$orderProduct [$k] ['product_promotion'] = $orderProductPromotion;
			if($product['set_id'] > 0){
				$sql = 'select t.*,t1.set_name,t1.set_price from nb_order_product t,nb_product_set t1 where t.set_id=t1.lid and t.dpid=t1.dpid and t.dpid='.$dpid.' and t.order_id=' . $product ['order_id'] . ' and t.set_id='.$product['set_id'].' and main_id='.$product['main_id'];
				$productSet = Yii::app ()->db->createCommand ( $sql )->queryAll ();
				if(!empty($productSet)){
					$orderProduct[$k]['amount'] = $product['zhiamount'];
					$orderProduct[$k]['set_name'] = $productSet[0]['set_name'];
					$orderProduct[$k]['set_price'] = $product['set_price'];
					$orderProduct[$k]['set_detail'] = $productSet;
				}
			}
			$orderProduct[$k]['product_name'] = $product['product_name'];
		}
		return $orderProduct;
	}
	public static function getOrderProductSetDetail($orderId,$dpid,$setId,$mainId){
		$sql = 'select * from nb_order_product where order_id=:orderId and set_id=:setId and dpid=:dpid and main_id=:mainId and product_type=0 and delete_flag=0';
		$orderProductSet = Yii::app()->db->createCommand($sql)
					->bindValue(':orderId',$orderId)
					->bindValue(':dpid',$dpid)
					->bindValue(':setId',$setId)
					->bindValue(':mainId',$mainId)
					->queryAll();
		foreach ($orderProductSet as $k=>$orderSet){
			$productTaste = self::getOrderTaste($orderSet['lid'],$dpid,0);
			$orderProductSet[$k]['taste'] = $productTaste;
		}
		return $orderProductSet;
	}
	public static function getOrderTaste($orderId,$dpid,$isOrder){
		$sql = 'select t.*,t1.name,t1.price from nb_order_taste t,nb_taste t1 where t.taste_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t.is_order=:isOrder and t.order_id=:orderId';
		$orderTaste = Yii::app()->db->createCommand($sql)
						->bindValue(':orderId',$orderId)
						->bindValue(':dpid',$dpid)
						->bindValue(':isOrder',$isOrder)
						->queryAll();
		return $orderTaste;
	}
	public static function getOrderProductByType($orderId,$dpid,$type){
		$sql = 'select t.price,t.amount,t.is_retreat from nb_order_product t where t.order_id = :orderId and t.dpid = :dpid and t.product_type=:type and t.delete_flag=0';
		$orderProduct = Yii::app()->db->createCommand($sql)
				  ->bindValue(':orderId',$orderId)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':type',$type)
				  ->queryAll();
	    return $orderProduct;
	}
	public static function getUserOrderList($userId,$cardId,$type,$page){
		if($type==1){
			$sql = 'select * from nb_order where user_id='.$userId.' and order_type in (1,2,3,6) and order_status in ("1","2") order by lid desc';
		}elseif($type==2){
			$sql = 'select * from nb_order where user_id='.$userId.' and order_type in (1,2,3,6) and order_status in ("3","4","8") order by lid desc';
		}else{
			$sql = 'select * from nb_order where user_id='.$userId.' and order_type in (1,2,3,6) and order_status in ("1","2","3","4","8") order by lid desc';
		}
		$sql .= '  limit '. ($page-1)*10 .',10';
		$orderList = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($orderList as $key=>$list){
			$sql = 'select company_name,logo from nb_company where dpid='.$list['dpid'];
			$company = Yii::app()->db->createCommand($sql)->queryRow();
			$orderList[$key]['company_name'] = $company['company_name'];
			$orderList[$key]['logo'] = $company['logo'];
		}
	    return $orderList;
	}
	// 订单地址
	public static function getOrderAddress($orderId,$dpid){
		$sql = 'select * from nb_order_address where order_lid=:orderId and dpid=:dpid and delete_flag=0';
		$address = Yii::app()->db->createCommand($sql)
				  ->bindValue(':orderId',$orderId)
				  ->bindValue(':dpid',$dpid)
				  ->queryRow();
	    return $address;
	}
	// 订单折扣优惠
	public static function getOrderAccountDiscount($orderId,$dpid){
		$sql = 'select * from nb_order_account_discount where dpid=:dpid and order_id=:orderId and delete_flag=0';
		$orderDiscount = Yii::app()->db->createCommand($sql)
				->bindValue(':dpid',$dpid)
				->bindValue(':orderId',$orderId)
				->queryAll ();
		return $orderDiscount;
	}
	// 订单产品优惠活动
	public static function getOrderProductPromotion($orderProductId,$dpid){
		$sql = 'select * from nb_order_product_promotion where order_id=:orderProductId and dpid=:dpid and delete_flag=0';
		$promotion = Yii::app()->db->createCommand($sql)
				->bindValue(':orderProductId',$orderProductId)
				->bindValue(':dpid',$dpid)
				->queryRow();
		return $promotion;
	}
	/**
	 * 
	 * 获取当天改会员使用现金券支付的订单
	 * 
	 */
	 public static function getOrderUseCupon($userId,$dpid){
	 	$now = date('Y-m-d',time());
	 	$sql = 'select * from nb_order where dpid=:dpid and user_id=:userId and cupon_branduser_lid > 0 and order_status in (1,2,3,4,8) and create_at >= :now';
		$order = Yii::app()->db->createCommand($sql)
				  ->bindValue(':userId',$userId)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':now',$now)
				  ->queryAll();
		return $order;
	}
	public static function updateOrderStatus($orderId,$dpid){
		$now = date('Y-m-d H:i:s',time());
		$sql = 'update nb_order set order_status=3,paytype=1,pay_time="'.$now.'" where lid='.$orderId.' and dpid='.$dpid;
		Yii::app()->db->createCommand($sql)->execute();
	}
	/**
	 * 
	 * 更改订单产品表状态
	 * 
	 */
	public static function updateOrderProductStatus($orderId,$dpid){
		$sql = 'update nb_order_product set product_order_status=8 where order_id='.$orderId.' and dpid='.$dpid.' and delete_flag=0';
		Yii::app()->db->createCommand($sql)->execute();
	}
	/**
	 * 
	 * 更改订单支付方式
	 * 
	 * 
	 */
	 public static function updatePayType($orderId,$dpid,$paytype = 1){
		$sql = 'update nb_order set paytype='.$paytype.' where lid='.$orderId.' and dpid='.$dpid;
		Yii::app()->db->createCommand($sql)->execute();
	}
	/**
	 * 
	 * 插入订单代金券表
	 * 并减少订单相应的金额
	 * @$userCupon 会员领取代金券信息
	 * $order lid dpid account_no pay_price
	 * should_total 是需要支付的金额
	 * 
	 */
	public static function updateOrderCupon($userCupon,$order,$payPrice,$cardId){
		$money = 0;
		if($payPrice >= $userCupon['min_consumer']){
			$now = date('Y-m-d H:i:s',time());
			$orderPrice = $payPrice;
			$cuponPrice = $userCupon['cupon_money'];
			if($orderPrice <= $cuponPrice){
				$cuponPrice = $orderPrice;
			}
			$money = $cuponPrice;
			
			self::insertOrderPay($order,9,$money,$userCupon['lid'],$cardId);
			WxCupon::dealCupon($userCupon['dpid'], $userCupon['lid'], 2, $order['dpid']);
		}
		return $money;
	}
	/**
	 * 
	 * 更改订单信息
	 * 
	 * 
	 */
	 public static function update($orderId,$dpid,$contion){
		$sql = 'update nb_order set '.$contion.' where lid='.$orderId.' and dpid='.$dpid;
		Yii::app()->db->createCommand($sql)->execute();
	}
	/**
	 * 
	 * 取消订单
	 * 
	 */
	 public static function cancelOrder($orderId,$dpid){
	 	$order = self::getOrder($orderId,$dpid);
	 	if(!$order){
	 		throw new Exception('订单不存在!');
	 	}
	 	if(in_array($order['order_status'], array(3,4,8))){
	 		throw new Exception('该订单已支付,不能取消!');
	 	}
	 	if($order['order_status']==7){
	 		throw new Exception('订单已经被取消!');
	 	}
	 	$sql = 'select * from nb_order_product where order_id=:orderId and dpid=:dpid';
	 	$resluts = Yii::app()->db->createCommand($sql)
	 							 ->bindValue(':orderId',$orderId)
	 							 ->bindValue(':dpid',$dpid)
	 							 ->queryAll();
	 	if(empty($resluts)){
	 		throw new Exception('订单处理有问题,请联系服务员!');
	 	}else{
	 		foreach($resluts as $orderProduct){
	 			$sql = 'select * from nb_product where lid=:productId and dpid=:dpid and delete_flag=0';
				$product = Yii::app()->db->createCommand($sql)
							  ->bindValue(':dpid',$dpid)
							  ->bindValue(':productId',$orderProduct['product_id'])
							  ->queryRow();
				if($product['store_number'] >= 0){
					$sql = 'update nb_product set store_number =  store_number+'.$orderProduct['amount'].' where lid='.$orderProduct['product_id'].' and dpid='.$dpid.' and delete_flag=0';
			 		Yii::app()->db->createCommand($sql)->execute();
				}
	 		}
			$sql = 'update nb_order set order_status=7 where lid='.$orderId.' and dpid='.$dpid;
			$result = Yii::app()->db->createCommand($sql)->execute();
			if(!$result){
				throw new Exception('订单取消失败!');
			}
			$orderPays = WxOrderPay::get($dpid,$orderId);
			foreach ($orderPays as $orderpay){
				if($orderpay['paytype']==9){
					$user = WxBrandUser::getFromCardId($dpid, $orderpay['remark']);
					WxCupon::refundCupon($orderpay['paytype_id'],$user['lid']);
					WxOrderPay::refundOrderPay($orderpay);
				}else if($orderpay['paytype']==7){
					$user = WxBrandUser::getFromCardId($dpid, $orderpay['remark']);
					WxBrandUser::refundYue($orderpay['pay_amount'], $user, $dpid, 1);
					WxOrderPay::refundOrderPay($orderpay);
				}else if($orderpay['paytype']==10){
					$user = WxBrandUser::getFromCardId($dpid, $orderpay['remark']);
					WxBrandUser::refundYue($orderpay['pay_amount'],$user,$dpid);
					WxOrderPay::refundOrderPay($orderpay);
				}
			}
	 	}
	}
	/**
	 * 
	 * 微信支付 通知时 使用该方法
	 * order——pay表记录支付数据
	 * // 微信支付
	 */
	 public static function insertOrderPay($order,$paytype = 1,$payPrice = 0,$payTypeId = 0,$out_trade_no = ''){
 		$time = time();
 		$se = new Sequence("order_pay");
	    $orderPayId = $se->nextval();
	    $insertOrderPayArr = array(
	        	'lid'=>$orderPayId,
	        	'dpid'=>$order['dpid'],
	        	'create_at'=>$order['create_at'],
	        	'update_at'=>date('Y-m-d H:i:s',$time), 
	        	'order_id'=>$order['lid'],
	        	'account_no'=>$order['account_no'],
	        	'pay_amount'=>$payPrice,
	        	'paytype'=>$paytype,
	    		'paytype_id'=>$payTypeId,
	    		'remark'=>$out_trade_no,
	        );
		$result = Yii::app()->db->createCommand()->insert('nb_order_pay', $insertOrderPayArr);
	 }
	/**
	 * 
	 * 扣除会员余额
	 * $order : lid dpid account_no should_total 
	 * should_total 就是需要支付的金额
	 * $paymoney = array('charge'=>'','back'=>'')
	 */
	 public static function reduceYue($user,$order,$payPrice){
	 	$payMoney = 0;
	 	$userId = $user['lid'];
	 	$userDpId = $user['dpid'];
	 	$orderId = $order['lid'];
	 	$dpid = $order['dpid'];
		$total = $payPrice;
		
		$paymoney = array('charge'=>0, 'back'=>0);
		$payYue = WxBrandUser::reduceYue($user, $dpid, $total, $paymoney);	
		
		if($paymoney['charge']){
			self::insertOrderPay($order, 7, $paymoney['charge'],0,$user['card_id']);
		}
		
		if($paymoney['back']){
			self::insertOrderPay($order, 10, $paymoney['back'],0,$user['card_id']);
		}
	 	return $payYue;
	 }
	 /**
	  * 
	  * 订单支付成功处理
	  * 
	  */ 
	 public static function dealOrder($user,$order){
	 	$orderId = $order['lid'];
	 	$dpid = $order['dpid'];
	 	WxBrandUser::isUserFirstOrder($user,$dpid);
	 	//修改订单状态
	 	self::updateOrderStatus($orderId,$dpid);
	 	//修改订单产品状态
	 	self::updateOrderProductStatus($orderId,$dpid);
	 	//修改座位状态
	 	if($order['order_type']==1){
	 		WxSite::updateSiteStatus($order['site_id'],$dpid,4);
	 		WxScanLog::invalidScene($dpid, $order['site_id']);
	 	}else{
	 		WxSite::updateTempSiteStatus($order['site_id'],$dpid,4);
	 	}
	 	self::dealMaterialStock($order);
	 }
	 /**
	  * 处理产品库存
	  */
	 public static function dealMaterialStock($order){
	 	// 获取订单中产品 减少库存
	 	$orderId = $order['lid'];
	 	$dpid = $order['dpid'];
	 	$orderProducts = self::getOrderProductData($orderId, $dpid);
	 	foreach ($orderProducts as $product){
	 		if($product['set_id'] > 0){
	 			$setDetails = $product['set_detail'];
	 			foreach($setDetails as $detail){
	 				$productTasteArr = array();
	 				if(isset($detail['product_taste'])&&!empty($detail['product_taste'])){
	 					foreach ($detail['product_taste'] as $taste){
	 						array_push($productTasteArr, $taste['taste_id']);
	 					}
	 				}
	 				$productBoms = DataSyncOperation::getBom($dpid, $detail['product_id'], $productTasteArr);
	 				if(!empty($productBoms)){
	 					foreach ($productBoms as $bom){
	 						$stock = $bom['number']*$product['amount'];
	 						DataSyncOperation::updateMaterialStock($dpid,$order['create_at'],$bom['material_id'],$stock,$detail['lid'],1);
	 					}
	 				}
	 			}
	 		}else{
	 			$productTasteArr = array();
	 			if(isset($product['product_taste'])&&!empty($product['product_taste'])){
	 				foreach ($product['product_taste'] as $taste){
	 					array_push($productTasteArr, $taste['taste_id']);
	 				}
	 			}
	 			$productBoms = DataSyncOperation::getBom($dpid, $product['product_id'], $productTasteArr);
	 			if(!empty($productBoms)){
	 				foreach ($productBoms as $bom){
	 					$stock = $bom['number']*$product['amount'];
	 					DataSyncOperation::updateMaterialStock($dpid,$order['create_at'],$bom['material_id'],$stock,$product['lid'],1);
	 				}
	 			}
	 		}
	 	}
	 }
	 /**
	  * 订单完成 订单放入redis 
	  * 扣减库存
	  */
	 public static function orderSuccess($order){
	 	$order['order_status'] = 3;
	 	self::pushOrderToRedis($order);
	 	self::dealMaterialStock($order);
	 }
	 /**
	  * 已支付的订单
	  * 放入缓存
	  */
	 public static function pushOrderToRedis($order){
	 	$orderId = $order['lid'];
	 	$orderDpid = $order['dpid'];
	 	$orderArr = array();
	 	$orderArr['nb_site_no'] = array();
	 	$orderArr['nb_order_platform'] = array();
	 	
	 	$orderArr['nb_order'] = $order;
	 	
	 	$orderProducts = self::getOrderProductData($orderId, $orderDpid);
	 	$orderArr['nb_order_product'] = $orderProducts;
	 	
	 	$orderPays = WxOrderPay::get($orderDpid, $orderId);
	 	$orderArr['nb_order_pay'] = $orderPays;
	 	
	 	$orderAddressArr = array();
	 	if(in_array($order['order_type'],array(2,3))){
	 		$orderAddress = self::getOrderAddress($orderId, $orderDpid);
	 		if(!empty($orderAddress)){
	 			array_push($orderAddressArr, $orderAddress);
	 		}
	 	}
	 	$orderArr['nb_order_address'] = $orderAddressArr;
	 	$orderArr['nb_order_taste'] = $order['taste'];
	 	
	 	$orderDiscount = self::getOrderAccountDiscount($orderId, $orderDpid);
	 	$orderArr['nb_order_account_discount'] = $orderDiscount;
	 	$orderStr = json_encode($orderArr);
	 	$result = WxRedis::pushPlatform($orderDpid, $orderStr);
	 	if(!$result){
	 		Helper::writeLog('redis缓存失败 :类型:微信-接单pushPlatform;dpid:'.$orderDpid.';data:'.$orderStr);
	 	}
	 }
	 /**
	  *桌台点单订单 
	  *放入缓存
	  */
	 public static function pushSiteOrderToRedis($order,$siteNo){
	 	// 餐桌模式 数据放入缓存中
	 	$orderId = $order['lid'];
	 	$orderDpid = $order['dpid'];
	 	
	 	$orderArr = array();
	 	$orderProduct = self::getOrderProductData($orderId, $orderDpid);
	 	$orderDiscount = self::getOrderAccountDiscount($orderId, $orderDpid);
	 	$orderArr['nb_site_no'] = $siteNo;
	 	$orderArr['nb_order_platform'] = array();
	 	$orderArr['nb_order'] = $order;
	 	$orderArr['nb_order_product'] = $orderProduct;
	 	$orderArr['nb_order_pay'] = array();
	 	$orderArr['nb_order_address'] = array();
	 	$orderArr['nb_order_taste'] = $order['taste'];
	 	$orderArr['nb_order_account_discount'] = array();
	 	$orderStr = json_encode($orderArr);
	 	$result = WxRedis::pushPlatform($orderDpid, $orderStr);
	 	if(!$result){
	 		Helper::writeLog('redis缓存失败 :类型:微信桌台-接单pushPlatform;dpid:'.$orderDpid.';data:'.$orderStr);
	 	}
	 }
     /**
      * 
      * 输入金额订单
      * 
      */
     public static function createBillOrder($dpid,$userId,$order_price,$offprice){
        $time = time();
        $accountNo = 0;
        $orignprice = $order_price + $offprice;
		$se = new Sequence("order");
	    $orderId = $se->nextval();
	    
		$accountNo = self::getAccountNo($dpid,0,1,$orderId);
		
		$transaction = Yii::app()->db->beginTransaction();
		try{
        	    $insertOrderArr = array(
        	        	'lid'=>$orderId,
        	        	'dpid'=>$dpid,
        	        	'create_at'=>date('Y-m-d H:i:s',$time),
        	        	'update_at'=>date('Y-m-d H:i:s',$time), 
        	        	'account_no'=>$accountNo,
        	        	'user_id'=>$userId,
        	        	'site_id'=>0,
        	        	'is_temp'=>1,
        	        	'number'=>1,
                        'should_total'=>$order_price,
                        'reality_total'=>$orignprice,
        	        	'order_status'=>1,
        	        	'order_type'=>5,
        	        	'is_sync'=>DataSync::getInitSync(),
        	        );
        		$result = Yii::app()->db->createCommand()->insert('nb_order', $insertOrderArr);
                
                $se = new Sequence("order_product");
		    	$orderProductId = $se->nextval();
	         	$orderProductData = array(
								'lid'=>$orderProductId,
								'dpid'=>$dpid,
								'create_at'=>date('Y-m-d H:i:s',$time),
	        					'update_at'=>date('Y-m-d H:i:s',$time), 
								'order_id'=>$orderId,
								'set_id'=>0,
								'product_id'=>0,
								'product_name'=>'扫码支付',
								'product_pic'=>'',
								'product_type'=>0,
								'price'=>$order_price,
								'original_price'=>$orignprice,
                                'offprice'=>$offprice,
								'amount'=>1,
								'product_order_status'=>9,
								'is_sync'=>DataSync::getInitSync(),
								);
			 Yii::app()->db->createCommand()->insert('nb_order_product',$orderProductData);
        	 $transaction->commit();
             $msg = json_encode(array('status'=>true,'order_id'=>$orderId));
		}catch (Exception $e) {
			$transaction->rollback();
			$msg = json_encode(array('status'=>false,'order_id'=>0));
		}
        return $msg;
     }
	 /**
	  * 
	  * 订单流水单号
	  * 
	  */
	  public static function getAccountNo($dpid,$siteId,$isTemp,$orderId){
          $ret = substr(date('Ymd',time()),-6).substr("0000000000".$orderId, -6);
          return $ret;
      }
}