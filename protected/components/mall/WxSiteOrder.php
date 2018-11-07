<?php 
/**
 * 
 * 
 * 微信端餐桌订单类
 * 餐桌多个订单合并到最新订单中进行支付
 *
 * 
 */
class WxSiteOrder
{
	public $dpid;
	public $siteId; // nb_site_no表的lid
	public $seatingFee = 0;
	public $levelDiscount = 1;//会员等级折扣
	public $user;
	public $hasfullsent = false;
	public $fullsent = '0-0-0';//满送满减信息
	public $fullMinus = 0; //满减金额
	public $fullSentProduct = array(); //满送产品
	public $others = array();//其他参数
	public $orders = false; //餐桌多次下单
	public $order = false;//最早一次下单
	public $orderSuccess = false;
	
	public function __construct($dpid, $siteId, $user, $others){
		$this->dpid = $dpid;
		$this->siteId = $siteId;
		$this->user = $user;
		$this->others = $others;
		$this->getSeatingFee();
		$this->getLevelDiscount();
		$this->getCupon();
		$this->getFullsent();
		$this->getSiteOrders();
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
	 * 处理满减满送活动
	 */
	public function getFullsent(){
		if($this->others['fullsent']!='0-0-0'){
			$now = date('Y-m-d H:i:s',time());
			$this->hasfullsent = true;
			$fullsentArr = explode('-', $this->fullsent);
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
					throw new Exception('无改满送产品');
				}
				$this->fullSentProduct = $fullsentdetail;
			}else{
				$this->fullMinus = $fullsentObj['extra_cost'];
			}
			
		}
		
	}
	//获取该餐桌所有订单
	public function getSiteOrders(){
		$this->orders = WxOrder::getOrderBySiteId($this->siteId, $this->dpid);
	}
	public function createOrder(){
		$orderId = 0;
		$memdisprice = 0;
		$orderPrice = 0;
		$realityPrice = 0;
		$accountNo = 0;
		$number = 0;
		$forderId = '';
		$sorderId = '';
		$sorderproductId = '';
		$time = time();
		$levelDiscount = $this->levelDiscount;
		foreach ($this->orders as $key=>$order){
			if($key==0){
				$forderId = $order['lid'];
				$accountNo = $order['account_no'];
				$number = $order['number'];
				$this->order = $order;
			}else{
				$sorderId .= $order['lid'].',';
			}
			
			$orderProducts = $order['product_list'];
			foreach ($orderProducts as $product){
				if($key!=0){
					$sorderproductId .= $product['lid'].',';
				}
				if($product['set_id'] > 0){
					$amount = $product['zhiamount'];
				}else{
					$amount = $product['amount'];
				}
				$isdiscount = $product['is_member_discount'];
				if($product['private_promotion_lid'] > 0){
					$isdiscount = 0;
				}
				if($isdiscount){
					$memdisprice += $amount*$product['price']*(1-$levelDiscount);
					$orderPrice +=  $amount*$product['price']*$levelDiscount;
				}else{
					$orderPrice +=  $amount*$product['price'];
				}
				$realityPrice += $amount*$product['original_price'];
			}
		}
		if(!empty($this->fullSentProduct)){
			$se = new Sequence("order_product");
			$orderProductId = $se->nextval();
		
			$orderProductData = array(
					'lid'=>$orderProductId,
					'dpid'=>$this->dpid,
					'create_at'=>date('Y-m-d H:i:s',$time),
					'update_at'=>date('Y-m-d H:i:s',$time),
					'order_id'=>$forderId,
					'set_id'=>0,
					'product_id'=>$this->fullSentProduct['product_id'],
					'product_name'=>$this->fullSentProduct['product_name'],
					'product_pic'=>$this->fullSentProduct['main_picture'],
					'price'=>$this->fullSentProduct['price'],
					'original_price'=>$this->fullSentProduct['original_price'],
					'amount'=>1,
					'product_order_status'=>2,
			);
			Yii::app()->db->createCommand()->insert('nb_order_product',$orderProductData);
		}
		if($this->seatingFee > 0){
			$se = new Sequence("order_product");
			$orderProductId = $se->nextval();
			$orderProductData = array(
					'lid'=>$orderProductId,
					'dpid'=>$this->dpid,
					'create_at'=>date('Y-m-d H:i:s',$time),
					'update_at'=>date('Y-m-d H:i:s',$time),
					'order_id'=>$forderId,
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
			$orderPrice +=  $this->seatingFee*$number;
			$realityPrice += $this->seatingFee*$number;
		}
		if(count($this->orders) > 1){
			$sorderId = rtrim($sorderId,',');
			$sorderproductId = rtrim($sorderId,',');
			//更改该订单为下单状态  取消该餐桌比较早订单
			$sql = 'update nb_order set order_status=2,user_id='.$this->user['lid'].' where lid = '.$forderId.' and dpid='.$this->dpid;
			Yii::app()->db->createCommand($sql)->execute();
			$sql = 'update nb_order set order_status=7 where lid in ('.$sorderId.') and dpid='.$this->dpid;
			Yii::app()->db->createCommand($sql)->execute();
			$sql = 'update nb_order_product set order_id='.$forderId.' where order_id in ('.$sorderId.') and dpid='.$this->dpid;
			Yii::app()->db->createCommand($sql)->execute();
			$sql = 'update nb_order_product_promotion set order_id='.$forderId.' where order_id in ('.$sorderId.') and dpid='.$this->dpid;
			Yii::app()->db->createCommand($sql)->execute();
			$sql = 'update nb_order_taste set order_id='.$forderId.' where order_id in ('.$sorderId.') and dpid='.$this->dpid.' and is_order=1 and delete_flag=0';
			Yii::app()->db->createCommand($sql)->execute();
			if($sorderproductId!=''){
				$sql = 'update nb_order_taste set order_id='.$forderId.' where order_id in ('.$sorderproductId.') and dpid='.$this->dpid.' and is_order=0 and delete_flag=0';
				Yii::app()->db->createCommand($sql)->execute();
			}
		}
		if($memdisprice > 0){
			$se = new Sequence("order_account_discount");
			$orderAccountId = $se->nextval();
			$orderAccountData = array(
					'lid'=>$orderAccountId,
					'dpid'=>$this->dpid,
					'create_at'=>date('Y-m-d H:i:s',$time),
					'update_at'=>date('Y-m-d H:i:s',$time),
					'order_id'=>$forderId,
					'account_no'=>$accountNo,
					'discount_title'=>'会员折扣',
					'discount_id'=>0,
					'discount_money'=>$memdisprice,
			);
			Yii::app()->db->createCommand()->insert('nb_order_account_discount',$orderAccountData);
		}
		if($this->fullMinus > 0){
			$se = new Sequence("order_account_discount");
			$orderAccountId = $se->nextval();
			$orderAccountData = array(
					'lid'=>$orderAccountId,
					'dpid'=>$this->dpid,
					'create_at'=>date('Y-m-d H:i:s',$time),
					'update_at'=>date('Y-m-d H:i:s',$time),
					'order_id'=>$forderId,
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
		if($payPrice <= 0){
			$this->orderSuccess = true;
		}
		return $forderId;
	}
}