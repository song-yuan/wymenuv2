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
	public $user;
	public $hasfullsent = false;
	public $fullsent = '0-0-0';//满送满减信息
	public $fullMinus = 0; //满减金额
	public $fullSentProduct = array(); //满送产品
	public $orders = false;
	
	public function __construct($dpid, $siteId, $user, $others){
		$this->dpid = $dpid;
		$this->siteId = $siteId;
		$this->user = $user;
		$this->fullsent = $others['fullsent'];
		$this->getSeatingFee();
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
	public function getFullsent(){
		if($this->fullsent!='0-0-0'){
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
		$accountNo = 0;
		$number = 0;
		$forderId = '';
		$sorderId = '';
		$sorderproductId = '';
		$time = time();
		$levelDiscount = WxBrandUser::getUserDiscount($this->user,'1');
		foreach ($this->orders as $key=>$order){
			if($key==0){
				$forderId = $order['lid'];
				$accountNo = $order['account_no'];
				$number = $order['number'];
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
				if($isdiscount){
					$memdisprice += $amount*$product['price']*(1-$levelDiscount);
					$price +=  $amount*$product['price']*$levelDiscount;
				}else{
					$price +=  $amount*$product['price'];
				}
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
					'is_sync'=>$isSync,
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
			// 取消 该餐桌比较早订单
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
		if($memdiscount > 0){
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
					'discount_money'=>$memdiscount,
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
					'is_sync'=>$isSync,
			);
			Yii::app()->db->createCommand()->insert('nb_order_account_discount',$orderAccountData);
			$orderPrice = $orderPrice - $this->fullMinus;
			if($orderPrice < 0){
				$orderPrice = 0;
			}
		}
		if($orderPrice==0){
			$sql = 'update nb_order set should_total='.$orderPrice.',reality_total='.$realityPrice.',order_status=3 where lid='.$forderId.' and dpid='.$this->dpid;
			Yii::app()->db->createCommand($sql)->execute();
		}else{
			$sql = 'update nb_order set should_total='.$orderPrice.',reality_total='.$realityPrice.' where lid='.$forderId.' and dpid='.$this->dpid;
			Yii::app()->db->createCommand($sql)->execute();
		}
		return $forderId;
	}
}