<?php 
/**
 * 
 * 
 * 微信端购物车类
 * 堂吃必须有siteId
 * $pormotionYue 是否有储值支付活动
 * productArr = array('product_id'=>1,'num'=>1,'promotion_id'=>-1)
 * 
 */
class WxCart
{
	public $dpid;
	public $userId;
	public $siteId;
	public $type;
	public $pormotionYue = false;
	public $productArr = array();
	public $cart = array();
	
	public function __construct($dpid,$userId,$productArr = array(),$siteId,$type){
		$this->dpid = $dpid;
		$this->userId = $userId;
		$this->siteId = $siteId;
		$this->type = $type;
		$this->productArr = $productArr;
		if(!empty($this->productArr)){
			$this->isCart();
		}
	}
	//加入购物车 判断
	public function isCart(){
		$sql = 'select * from nb_cart where dpid=:dpid and user_id=:userId and product_id=:productId and is_set=:isSet and promotion_id=:privationPromotionId and to_group=:toGroup and can_cupon=:canCupon';
		$this->cart = Yii::app()->db->createCommand($sql)
					  ->bindValue(':dpid',$this->dpid)
					  ->bindValue(':userId',$this->userId)
					  ->bindValue(':productId',$this->productArr['product_id'])
					  ->bindValue(':isSet',$this->productArr['is_set'])
					  ->bindValue(':privationPromotionId',$this->productArr['promotion_type'])
					  ->bindValue(':privationPromotionId',$this->productArr['promotion_id'])
					  ->bindValue(':toGroup',$this->productArr['to_group'])
					  ->bindValue(':canCupon',$this->productArr['can_cupon'])
					  ->queryRow();
	}
	//判断产品库存
	public function checkStoreNumber(){
		if($this->productArr['is_set']){
			$sql = 'select * from nb_product_set where lid=:productId and dpid=:dpid and delete_flag=0';
		}else{
			$sql = 'select * from nb_product where lid=:productId and dpid=:dpid and delete_flag=0';
		}
		$product = Yii::app()->db->createCommand($sql)
					  ->bindValue(':dpid',$this->dpid)
					  ->bindValue(':productId',$this->productArr['product_id'])
					  ->queryRow();
		if($product['store_number']==0){
			return array('status'=>false,'msg'=>'该产品已售罄!');
		}
		if($product['store_number'] > 0){
			if($this->cart && $this->cart['num'] >= $product['store_number']){
				return array('status'=>false,'msg'=>'超出产品库存!');
			}
		}
		return array('status'=>true,'msg'=>'');
	}
	// 如果产品有优惠活动
	public function checkPromotion(){
		$now = date('Y-m-d H:i:s',time());
		if($this->productArr['promotion_id'] > 0){
			$sqla = 'select count(*) as count from nb_cart where dpid=:dpid and user_id=:userId and promotion_id=:privationPromotionId';
			$resulta = Yii::app()->db->createCommand($sqla)
					  ->bindValue(':dpid',$this->dpid)
					  ->bindValue(':userId',$this->userId)
					  ->bindValue(':privationPromotionId',$this->productArr['promotion_id'])
					  ->queryRow();
			
			if($this->productArr['promotion_type']=='promotion'){
				// 普通活动
				$sql = 'select t.order_num as product_num,t1.order_num,t1.promotion_type,t1.begin_time,t1.end_time,t1.weekday,t1.day_begin,t1.day_end from nb_normal_promotion_detail t,nb_normal_promotion t1 where t.normal_promotion_id=t1.lid and t.dpid=t1.dpid and t.normal_promotion_id=:privationPromotionId and t.dpid=:dpid and t.product_id=:productId and t.is_set=:isSet and t.delete_flag=0 and t1.delete_flag=0';
				$result = Yii::app()->db->createCommand($sql)
							->bindValue(':dpid',$this->dpid)
							->bindValue(':productId',$this->productArr['product_id'])
							->bindValue(':isSet',$this->productArr['is_set'])
							->bindValue(':privationPromotionId',$this->productArr['promotion_id'])
							->queryRow();
			}else{
				// 买送活动
				$sql = 'select t.limit_num as product_num,t1.order_num,t1.promotion_type,t1.begin_time,t1.end_time,t1.weekday,t1.day_begin,t1.day_end from nb_buysent_promotion_detail t,nb_buysent_promotion t1 where t.buysent_pro_id=t1.lid and t.dpid=t1.dpid and t.buysent_pro_id=:privationPromotionId and t.dpid=:dpid and t.product_id=:productId and t.is_set=:isSet and t.delete_flag=0 and t1.delete_flag=0';
				$result = Yii::app()->db->createCommand($sql)
							->bindValue(':dpid',$this->dpid)
							->bindValue(':productId',$this->productArr['product_id'])
							->bindValue(':isSet',$this->productArr['is_set'])
							->bindValue(':privationPromotionId',$this->productArr['promotion_id'])
							->queryRow();
			}
			if($now > $result['end_time']){
				return array('status'=>false,'msg'=>'活动已结束,活动截至时间'.$result['end_time']);
			}
			if($now < $result['begin_time']){
				return array('status'=>false,'msg'=>'活动未开始,活动开始时间'.$result['begin_time']);
			}
			$week = date('w');
			if($week==0){
				$week = 7;
			}
			$weekday = explode(',',$result['weekday']);
			if(!in_array($week, $weekday)){
				return array('status'=>false,'msg'=>'今天无活动!');
			}
			$time = date('H:i');
			$promotionBegin = date('H:i',strtotime($result['day_begin']));
			$promotionEnd = date('H:i',strtotime($result['day_end']));
			if($time > $promotionEnd||$time < $promotionBegin){
				return array('status'=>false,'msg'=>'今天活动未开始,活动时间'.$promotionBegin.'-'.$promotionEnd);
			}
			if($result['promotion_type']==0){
				$cartPromotions = $this->getCartPromotion();
				if(!empty($cartPromotions)){
					foreach($cartPromotions as $promotion){
						if($promotion['promotion_type']==0){
							return array('status'=>false,'msg'=>'本活动不与其他活动同时使用!');
						}
					}
				}
			}
			if($result['order_num']==0){
				if($result['product_num']==0){
					return array('status'=>true,'msg'=>'OK');
				}else{
					if((isset($this->cart['num'])?$this->cart['num']:0) >= $result['product_num']){
						return array('status'=>false,'msg'=>'超过活动商品数量,单个最多'.$result['product_num'].'个!');
					}
				}
			}else{
				if($result['product_num']==0){
					if(!$this->cart && $resulta['count'] >= $result['order_num']){
						return array('status'=>false,'msg'=>'超过活动商品数量,该活动最多'.$result['order_num'].'个!');
					}
				}else{
					if((!$this->cart &&$resulta['count'] >= $result['order_num'])||(isset($this->cart['num'])?$this->cart['num']:0) >= $result['product_num']){
						return array('status'=>false,'msg'=>'超过活动商品数量,该活动最多'.$result['order_num'].'个!');
					}
				}
			}
			return array('status'=>true,'msg'=>'OK');
		}
	}
	public function getCart(){
		$cartListArr = array();
		$cartListArr['disable'] = array();
		$cartListArr['available'] = array();
		if($this->type==2){
			$hideCate = WxCategory::getHideCate($this->dpid, 2);
			if(empty($hideCate)){
				$sql = 'select t.lid,t.dpid,t.product_id,t.is_set,t.num,t.promotion_type,t.promotion_id,t.to_group,t.can_cupon,t1.phs_code as pro_code,t1.product_name,t1.main_picture,t1.is_member_discount,t1.member_price,t1.original_price from nb_cart t,nb_product t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t.user_id=:userId and t.is_set=0 and t1.is_show=1 and t1.is_show_wx=1 and t1.delete_flag=0';
			}else{
				$categoryStr = join(',', $hideCate);
				$sql = 'select t.lid,t.dpid,t.product_id,t.is_set,t.num,t.promotion_type,t.promotion_id,t.to_group,t.can_cupon,t1.phs_code as pro_code,t1.product_name,t1.main_picture,t1.is_member_discount,t1.member_price,t1.original_price from nb_cart t,nb_product t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t1.category_id not in ('.$categoryStr.') and t.user_id=:userId and t.is_set=0 and t1.is_show=1 and t1.is_show_wx=1 and t1.delete_flag=0';
			}
		}elseif($this->type==6){
			$hideCate = WxCategory::getHideCate($this->dpid, 3);
			if(empty($hideCate)){
				$sql = 'select t.lid,t.dpid,t.product_id,t.is_set,t.num,t.promotion_type,t.promotion_id,t.to_group,t.can_cupon,t1.phs_code as pro_code,t1.product_name,t1.main_picture,t1.is_member_discount,t1.member_price,t1.original_price from nb_cart t,nb_product t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t.user_id=:userId and t.is_set=0 and t1.is_show=1 and t1.is_show_wx=1 and t1.delete_flag=0';
			}else{
				$categoryStr = join(',', $hideCate);
				$sql = 'select t.lid,t.dpid,t.product_id,t.is_set,t.num,t.promotion_type,t.promotion_id,t.to_group,t.can_cupon,t1.phs_code as pro_code,t1.product_name,t1.main_picture,t1.is_member_discount,t1.member_price,t1.original_price from nb_cart t,nb_product t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t1.category_id not in ('.$categoryStr.') and t.user_id=:userId and t.is_set=0 and t1.is_show=1 and t1.is_show_wx=1 and t1.delete_flag=0';
			}
		}else{
			$hideCate = WxCategory::getHideCate($this->dpid, 4);
			if(empty($hideCate)){
				$sql = 'select t.lid,t.dpid,t.product_id,t.is_set,t.num,t.promotion_type,t.promotion_id,t.to_group,t.can_cupon,t1.phs_code as pro_code,t1.product_name,t1.main_picture,t1.is_member_discount,t1.member_price,t1.original_price from nb_cart t,nb_product t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t.user_id=:userId and t.site_id=:siteId and t.is_set=0 and t1.is_show=1 and t1.is_show_wx=1 and t1.delete_flag=0';
			}else{
				$categoryStr = join(',', $hideCate);
				$sql = 'select t.lid,t.dpid,t.product_id,t.is_set,t.num,t.promotion_type,t.promotion_id,t.to_group,t.can_cupon,t1.phs_code as pro_code,t1.product_name,t1.main_picture,t1.is_member_discount,t1.member_price,t1.original_price from nb_cart t,nb_product t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t1.category_id not in ('.$categoryStr.') and t.user_id=:userId and t.is_set=0 and t1.is_show=1 and t1.is_show_wx=1 and t1.delete_flag=0';
			}
		}
		$sql .= ' union select t.lid,t.dpid,t.product_id,t.is_set,t.num,t.promotion_type,t.promotion_id,t.to_group,t.can_cupon,t1.pshs_code as pro_code,t1.set_name as product_name,t1.main_picture,t1.is_member_discount,t1.member_price,t1.set_price as original_price from nb_cart t,nb_product_set t1 where t.product_id=t1.lid and t.dpid=t1.dpid and t.dpid=:dpid and t.user_id=:userId and t.is_set=1 and t1.is_show=1 and t1.is_show_wx=1 and t1.delete_flag=0';
		$results = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$this->dpid)
				  ->bindValue(':userId',$this->userId)
				  ->queryAll();
		
		foreach($results as $result){
			if($result['is_set'] > 0){
				$detail = WxProduct::getProductSetDetail($result['product_id'], $result['dpid']);
				if(!empty($detail)){
					$result['detail'] = $detail;
				}else{
					$result['msg'] = '请添加套餐明细';
					array_push($cartListArr['disable'], $result);
					continue;
				}
			}else{
				$result['taste_groups'] = WxTaste::getProductTastes($result['product_id'],$this->dpid);
			}
			
			$promotionId = $result['promotion_id'];
			$promotionType = $result['promotion_type'];
			if($promotionId > 0){
				$productPromotion = WxPromotion::getProductPromotion($this->dpid, $promotionType,$promotionId,$result['product_id'],$result['is_set']);
				if(!$productPromotion){
					$result['msg'] = '该产品已无优惠活动';
					$result['buysent_pro_id'] = $promotionId;
					array_push($cartListArr['disable'], $result);
					continue;
				}
				$promotion = WxPromotion::isPromotionValid($this->dpid, $promotionType, $promotionId,$this->type);
				if(!$promotion){
					$result['msg'] = '优惠活动已结束';
					$result['buysent_pro_id'] = $promotionId;
					array_push($cartListArr['disable'], $result);
					continue;
				}
				if($result['to_group']==3){
					$this->pormotionYue = true;
				}elseif($result['to_group']==2){
					// 会员等级活动
					$user = WxBrandUser::get($this->userId, $this->dpid);
					$promotionUser = WxPromotion::getPromotionUser($this->dpid, $user['user_level_lid'], $promotionId);
					if(empty($promotionUser)){
						$result['msg'] = '会员不是该等级,不能享受优惠';
						$result['buysent_pro_id'] = $promotionId;
						array_push($cartListArr['disable'], $result);
						continue;
					}
				}
				
				if($promotionType=='promotion'){
					$productPrice = WxPromotion::getPromotionPrice($result['dpid'],$this->userId,$result['product_id'],$result['is_set'],$promotionId,$result['to_group']);
					$result['price'] = $productPrice['price'];
					$result['promotion'] = $productPrice;
				}elseif($promotionType=='sent'){
					$result['price'] = '0.00';
					$result['promotion'] = array('promotion_type'=>0,'price'=>0,'promotion_info'=>array());
				}else{
					$result['price'] = $result['member_price'];
					$result['promotion'] = array('promotion_type'=>0,'price'=>0,'promotion_info'=>array());
				}
			}else{
				$result['price'] = $result['member_price'];
				$result['promotion'] = array('promotion_type'=>0,'price'=>0,'promotion_info'=>array());
			}
			array_push($cartListArr['available'],$result);
		}
		return $cartListArr;
	}
	public function getCartPromotion(){
		$sql = 'select t.*,t1.promotion_type from nb_cart t,nb_normal_promotion t1 where t.promotion_id=t.lid and t.dpid=t1.dpid and t.dpid=:dpid and t.user_id=:userId and t.promotion_id > 0 and t.promotion_id!=:privationPromotionId and t.promotion_type="promotion" and t1.delete_flag=0'
				.' union select t.*,t1.promotion_type from nb_cart t,nb_buysent_promotion t1 where t.promotion_id=t.lid and t.dpid=t1.dpid and t.dpid=:dpid and t.user_id=:userId and t.promotion_id > 0 and t.promotion_id!=:privationPromotionId and t.promotion_type="buysent" and t1.delete_flag=0';
		$results = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$this->dpid)
				  ->bindValue(':userId',$this->userId)
				  ->bindValue(':privationPromotionId',$this->productArr['promotion_id'])
				  ->queryAll();
		return $results;
	}
	// 添加 买送产品
	public function addSentProduct($cartNum){
		$sentDetail = WxPromotion::getProductPromotion($this->dpid, $this->productArr['promotion_type'], $this->productArr['promotion_id'], $this->productArr['product_id'], $this->productArr['is_set']);
		if($sentDetail){
			$sentProductId = $sentDetail['s_product_id'];
			$buyNum = $sentDetail['buy_num'];
			$sentNum = $sentDetail['sent_num'];
			$realNum = floor($cartNum/$buyNum*$sentNum);
			$sql = 'select * from nb_cart where product_id='.$sentProductId.' and promotion_type="sent" and promotion_id='.$this->productArr['promotion_id'].' and 	promotion_detail_id='.$sentDetail['lid'].' and dpid='.$this->dpid.' and user_id='.$this->userId;
			$res = Yii::app()->db->createCommand($sql)->queryRow();
			if($res){
				if($realNum > 0){
					$sql = 'update nb_cart set num = '.$realNum.' where lid='.$res['lid'].' and dpid='.$res['dpid'];
					Yii::app()->db->createCommand($sql)->execute();
				}else{
					$sql = 'delete from nb_cart where lid='.$res['lid'].' and dpid='.$res['dpid'];
					Yii::app()->db->createCommand($sql)->execute();
				}
			}else{
				$time = time();
				$se = new Sequence("cart");
				$lid = $se->nextval();
				$insertCartArr = array(
						'lid'=>$lid,
						'dpid'=>$this->dpid,
						'create_at'=>date('Y-m-d H:i:s',$time),
						'update_at'=>date('Y-m-d H:i:s',$time),
						'user_id'=>$this->userId,
						'product_id'=>$sentProductId,
						'is_set'=>$sentDetail['is_set'],
						'num'=>$realNum,
						'site_id'=>$this->siteId,
						'promotion_type'=>'sent',
						'promotion_id'=>$this->productArr['promotion_id'],
						'promotion_detail_id'=>$sentDetail['lid'],
						'to_group'=>$this->productArr['to_group'],
						'can_cupon'=>$this->productArr['can_cupon'],
						'is_sync'=>DataSync::getInitSync(),
				);
				$result = Yii::app()->db->createCommand()->insert('nb_cart', $insertCartArr);
			}
		}
	}
	// 减少买送产品
	public function delSentProduct($cartNum){
		$sentDetail = WxPromotion::getProductPromotion($this->dpid, $this->productArr['promotion_type'], $this->productArr['promotion_id'], $this->productArr['product_id'], $this->productArr['is_set']);
		if($sentDetail){
			$sentProductId = $sentDetail['s_product_id'];
			$buyNum = $sentDetail['buy_num'];
			$sentNum = $sentDetail['sent_num'];
			$realNum = floor($cartNum/$buyNum*$sentNum);
			$sql = 'select * from nb_cart where product_id='.$sentProductId.' and promotion_type="sent" and promotion_id='.$this->productArr['promotion_id'].' and 	promotion_detail_id='.$sentDetail['lid'].' and dpid='.$this->dpid.' and user_id='.$this->userId;
			$res = Yii::app()->db->createCommand($sql)->queryRow();
			if($res){
				if($realNum > 0){
					$sql = 'update nb_cart set num = '.$realNum.' where lid='.$res['lid'].' and dpid='.$res['dpid'];
					Yii::app()->db->createCommand($sql)->execute();
				}else{
					$sql = 'delete from nb_cart where lid='.$res['lid'].' and dpid='.$res['dpid'];
					Yii::app()->db->createCommand($sql)->execute();
				}
			}
		}else{
			$sql = 'delete from nb_cart where dpid='.$this->dpid.' and promotion_type="sent" and promotion_id='.$this->productArr['promotion_id'].' and promotion_detail_id='.$sentDetail['lid'].' and user_id='.$this->userId;
			Yii::app()->db->createCommand($sql)->execute();
		}
	}
	/**
	 * @return boolean
	 * 
	 * 增加菜品
	 * 
	 */
	public function addCart(){
		$success = false;
		$time = time();
		if(empty($this->cart)){
			$se = new Sequence("cart");
	        $lid = $se->nextval();
	        $insertCartArr = array(
	        	'lid'=>$lid,
	        	'dpid'=>$this->dpid,
	        	'create_at'=>date('Y-m-d H:i:s',$time),
	        	'update_at'=>date('Y-m-d H:i:s',$time), 
	        	'user_id'=>$this->userId,
	        	'product_id'=>$this->productArr['product_id'],
        		'is_set'=>$this->productArr['is_set'],
	        	'num'=>$this->productArr['num'],
	        	'site_id'=>$this->siteId,
	        	'promotion_type'=>$this->productArr['promotion_type'],
	        	'promotion_id'=>$this->productArr['promotion_id'],
	        	'to_group'=>$this->productArr['to_group'],
	        	'can_cupon'=>$this->productArr['can_cupon'],
	        	'is_sync'=>DataSync::getInitSync(),	
	        );
			$result = Yii::app()->db->createCommand()->insert('nb_cart', $insertCartArr);
	        if($this->productArr['promotion_type'] == 'buysent'){
	        	$this->addSentProduct(1);
	        }
	        if($result){
	        	$success = true;
	        }
		}else{
			$isSync = DataSync::getInitSync();
			$sql = 'update nb_cart set num=num+1,is_sync='.$isSync.' where lid=:lid and dpid=:dpid';
			$result = Yii::app()->db->createCommand($sql)
					  ->bindValue(':dpid',$this->dpid)
					  ->bindValue(':lid',$this->cart['lid'])->execute();
			if($this->productArr['promotion_type'] == 'buysent'){
			   $this->addSentProduct($this->cart['num']+1);
			}
			if($result){
	        	$success = true;
	        }
		}
		return $success;
	}
	/**
	 * 
	 * @return boolean
	 * 
	 * 减少菜品
	 */
	public function deleteCart(){
		$success = false;
		$time = time();
		if($this->cart['num'] > 1){
			$isSync = DataSync::getInitSync();
			$sql = 'update nb_cart set num=num-1,is_sync='.$isSync.' where lid=:lid and dpid=:dpid';
			$result = Yii::app()->db->createCommand($sql)
					  ->bindValue(':dpid',$this->dpid)
					  ->bindValue(':lid',$this->cart['lid'])->execute();
			if($this->productArr['promotion_type'] == 'buysent'){
				$this->delSentProduct($this->cart['num']-1);
			}
	        if($result){
	        	$success = true;
	        }
		}else{
			$sql = 'delete from nb_cart where lid=:lid and dpid=:dpid';
			$result = Yii::app()->db->createCommand($sql) 
					  ->bindValue(':dpid',$this->dpid)
					  ->bindValue(':lid',$this->cart['lid'])->execute();
		  	if($this->productArr['promotion_type'] == 'buysent'){
		  		$this->delSentProduct($this->cart['num']-1);
		  	}
			if($result){
	        	$success = true;
	        }
		}
		return $success;
	}
	/**
	 * 
	 * 删除购物车某条记录
	 * 
	 */
	public static function deleteCartItem($lid,$dpid){
		$success = false;
		$sql = 'delete from nb_cart where lid=:lid and dpid=:dpid';
		$result = Yii::app()->db->createCommand($sql)
				->bindValue(':dpid',$dpid)
				->bindValue(':lid',$lid)->execute();
		
		if($result){
			$success = true;
		}
		return $success;
	}
	public static function getCartPrice($cartArrs,$user,$type){
		$price = 0;
		$levelDiscunt = 1;
		if($type!=2&&$user['level']){
			$birthday = date('m-d',strtotime($user['user_birthday']));
			$today = date('m-d',time());
			if($birthday==$today){
				$levelDiscunt = $user['level']['birthday_discount'];
			}else{
				$levelDiscunt = $user['level']['level_discount'];
			}
		}
		foreach($cartArrs as $cart){
			if($cart['promotion_id'] > 0){
				$price += $cart['price']*$cart['num'];
			}else{
				if($cart['is_member_discount']){
					$price += $cart['price']*$levelDiscunt*$cart['num'];
				}else{
					$price += $cart['price']*$cart['num'];
				}
			}
		}
		return number_format($price,2);
	}
	public static function getCartOrigianPrice($cartArrs){
		$price = 0;
		foreach($cartArrs as $cart){
			$price += $cart['price']*$cart['num'];
		}
		return number_format($price,2);
	}
	// 除去活动后的价格
	public static function getCartUnDiscountPrice($cartArrs){
		$price = 0;
		foreach($cartArrs as $cart){
			if($cart['promotion_id'] < 0){
				$price += $cart['price']*$cart['num'];
			}else{
				if($cart['can_cupon'] == 0){
					$price += $cart['price']*$cart['num'];
				}
			}
		}
		return number_format($price,2);
	}
	// 可使用代金券 产品的 code
	public static function getCartCanCuponProductCode($cartArrs){
		$productCodeArr = array();
		foreach($availables as $cart){
			if($cart['can_cupon'] == 0){
				array_push($productCodeArr,$cart['pro_code']);
			}
		}
		return $productCodeArr;
	}
	public static function clearCart($userId,$dpid){
		$sql = 'delete from nb_cart where dpid='.$dpid.' and user_id='.$userId;
		$result = Yii::app()->db->createCommand($sql)->execute();
		return $result;
	}
	public static function updateSiteId($userId,$dpid,$siteId){
		$isSync = DataSync::getInitSync();
		$sql = 'update nb_cart set site_id='.$siteId.',is_sync='.$isSync.' where dpid='.$dpid.' and user_id='.$userId.' and site_id!='.$siteId;
		Yii::app()->db->createCommand($sql)->execute();
	}
	public static function isEmptyCart($userId,$dpid){
		$sql = 'select * from nb_cart where dpid=:dpid and user_id=:userId';
		$results = Yii::app()->db->createCommand($sql)
				  ->bindValue(':dpid',$dpid)
				  ->bindValue(':userId',$userId)
				  ->queryAll();
		return $results;
	}
}