<?php
	$baseUrl = Yii::app()->baseUrl;
	$title = '微信点单:'.$this->company['company_name'];;
	$link = $this->createAbsoluteUrl('/mall/index',array('companyId'=>$this->companyId,'type'=>$this->type));
	$desc = '自助点餐,点餐不排队';
	$imgUrl = Yii::app()->request->getHostInfo().$this->company['logo'];
	if($this->type==2){
		$this->setPageTitle('外卖点单 '.$this->company['company_name']);
	}elseif($this->type==6){
		$this->setPageTitle('堂食点单 '.$this->company['company_name']);
	}else{
		$this->setPageTitle('自助点单 '.$this->company['company_name']);
	}
	$closeShop = false;
	if($this->company['is_rest'] < 3){
		$closeShop = true;
	}else{
		$currentTime = date('H:i');
		if($this->type==6||$this->type==1){
			if($this->company['sale_type']==3){
				$closeShop = true;
			}
			$shopTime = $this->company['shop_time'];
			$closeTime = $this->company['closing_time'];
		}elseif($this->type==2){
			if($this->company['sale_type']==2){
				$closeShop = true;
			}
			$shopTime = $this->company['wm_shop_time'];
			$closeTime = $this->company['wm_closing_time'];
		}else {
			$shopTime = '00:00';
			$closeTime = '23:59';
		}
		if($shopTime <= $closeTime){
			// 一天中的时间
			if($currentTime >= $closeTime || $currentTime <= $shopTime){
				$closeShop = true;
			}
		}else{
			// 横跨两天
			if($currentTime >= $closeTime && $currentTime <= $shopTime){
				$closeShop = true;
			}
		}
	}
	$current = false;
	$plus = '<img src="'.$baseUrl.'/img/mall/plus.png"/>';
	$minus = '<img src="'.$baseUrl.'/img/mall/minus.png"/>';
    $defaultImg = $baseUrl.'/img/product_default.png';
    $defaultNavImg = $baseUrl.'/img/product_nav_default.png';
	$navLiStr = '';
	$productStr = '';
	$cartStr = '';
	$topTitle = '';
	if(!empty($disables)){
		foreach ($disables as $disable){
			$productId = (int)$disable['product_id'];
			$promotionId = $disable['promotion_id'];
			$isSet = $disable['is_set'];
			$promotionType = $disable['promotion_type'];
			if($promotionType=='buysent'){
				$promotionId = (int)$disable['buysent_pro_id'];
			}
			$toGroup = $disable['to_group'];
			$canCupon = $disable['can_cupon'];
			
			$cartStr .='<div class="j-fooditem cart-dtl-item disable" data-price="0" data-category="#st0" data-orderid="'.$promotionType.'_'.$isSet.'_'.$productId.'_'.$promotionId.'_'.$toGroup.'_'.$canCupon.'">';
			$cartStr .='<div class="cart-dtl-item-inner">';
			$cartStr .='<i class="cart-dtl-dot"></i>';
			$cartStr .='<p class="cart-goods-name">'.$disable['product_name'].'-'.$disable['msg'].'</p>';
			$cartStr .='<div class="j-item-console cart-dtl-oprt">';
			$cartStr .='<span class="cart-delete" lid="'.$disable['lid'].'">删除</span>';
			$cartStr .='</div>';
			$cartStr .='<span class="cart-dtl-price">¥'.$disable['member_price'].'</span>';
			$cartStr .='</div></div>';
		}
	}
	
	// 买送活动
	if(!empty($buySentPromotions)){
		foreach ($buySentPromotions as $key=>$buysent){
			if($buysent['main_picture']==''){
				$buysent['main_picture'] = $defaultNavImg;
			}
			if($current){
				$navLiStr .= '<li class="" abstract="'.$buysent['promotion_abstract'].'"><a href="#st-buysent'.$key.'" onselectstart="return false"><img src="'.$buysent['main_picture'].'" class="nav-img"/><span class="nav-span">'.$buysent['promotion_title'].'</span></a><b></b></li>';
			}else{
				$current = true;
				$topTitle = $buysent['promotion_title'].$buysent['promotion_abstract'];
				$navLiStr .= '<li class="current" abstract="'.$buysent['promotion_abstract'].'"><a href="#st-buysent'.$key.'" onselectstart="return false"><img src="'.$buysent['main_picture'].'" class="nav-img"/><span class="nav-span">'.$buysent['promotion_title'].'</span></a><b></b></li>';
			}
			$productStr .= '<div class="section" id="st-buysent'.$key.'" type="buysent"><div class="prt-title">'.$buysent['promotion_title'].'</div>';
			$buyproCateArr = $buysent['product'];
			foreach ($buyproCateArr as $cateArr){
				foreach ($cateArr as $objPro){
					$pProduct = $objPro['product'];
					$productId = (int)$pProduct['lid'];
					$isSet = $objPro['is_set'];
					$promotionId = (int)$objPro['buysent_pro_id'];
					$toGroup = $objPro['to_group'];
					$canCupon = $objPro['can_cupon'];
					$cartKey = 'buysent-'.$productId.'-'.$isSet.'-'.$promotionId.'-'.$toGroup.'-'.$canCupon;
					if($pProduct['main_picture']==''){
						$pProduct['main_picture'] = $defaultImg;
					}
					if($isSet==0){
						// 单品
						$productStr .= '<div class="prt-lt"><div class="clearfix"><div class="lt-lt"><img src="'.$pProduct['main_picture'].'"></div>';
						$productStr .= '<div class="lt-ct"><p><span class="name">'.$pProduct['product_name'].'</span>';
						$spicy = $pProduct['spicy'];
						if($spicy==1){
							$productStr .='<span><img src="<?php echo $baseUrl;?>/img/mall/index/spicy1.png" style="width:15px;height:20px;"/></span>';
						}else if($spicy==2){
							$productStr .='<span><img src="<?php echo $baseUrl;?>/img/mall/index/spicy2.png" style="width:15px;height:20px;"/></span>';
						}else if($spicy==3){
							$productStr .='<span><img src="<?php echo $baseUrl;?>/img/mall/index/spicy3.png" style="width:15px;height:20px;"/></span>';
						}
						$productStr .='</p>';
					
						$productStr .='<p class="pr">';
						if($pProduct['price'] != $pProduct['original_price']){
							$productStr .='<span class="oprice"><strike>¥'.$pProduct['original_price'].'</strike></span>';
						}
						$productStr .= ' ¥<span class="price">'.$pProduct['price'].'</span>';
						$productStr .='</p>';
						if(!$closeShop){
							if(!empty($pProduct['taste_groups'])){
								$cartnum = '<b></b>';
								if(isset($cartList[$cartKey])){
									$cartLists = $cartList[$cartKey];
									//$cartnum = '<b style="display:inline;">'.count($cartLists).'</b>';
									foreach ($cartLists as $cartItem){
										$tasteStr = $cartItem['detail_id'];
										$tasteArr = explode(',',$tasteStr);
										$tasteNames = '';
										$pPrice = $pProduct['price'];
										foreach ($pProduct['taste_groups'] as $groups){
											foreach ($groups['tastes'] as $taste){
												if(in_array($taste['lid'], $tasteArr)){
													$tasteNames .= $taste['name'].' ';
													$pPrice += $taste['price'];
												}
											}
										}
										$pPrice = number_format($pPrice,2);
										$cartStr .='<div class="j-fooditem cart-dtl-item" data-price="'.$pPrice.'" data-category="#st-buysent'.$key.'" data-orderid="buysent_'.$isSet.'_'.$productId.'_'.$promotionId.'_'.$toGroup.'_'.$canCupon.'_'.$tasteStr.'">';
										$cartStr .='<div class="cart-dtl-item-inner">';
										$cartStr .='<i class="cart-dtl-dot"></i>';
										$cartStr .='<p class="cart-goods-name">'.$pProduct['product_name'].'</p>';
										$cartStr .='<div class="j-item-console cart-dtl-oprt">';
										$cartStr .='<a class="j-add-item add-food" href="javascript:void(0);"><span class="icon i-add-food">+</span></a>';
										$cartStr .='<span class="j-item-num foodop-num">'.$cartItem['num'].'</span> ';
										$cartStr .='<a class="j-remove-item remove-food" href="javascript:void(0);"><span class="icon i-remove-food">-</span></a>';
										$cartStr .='</div>';
										$cartStr .='<span class="cart-dtl-price">¥'.$pPrice.'</span>';
										$cartStr .='</div>';
										$cartStr .='<div class="cart-dtl-taste">'.$tasteNames.'</div>';
										$cartStr .='</div>';
									}
								}
								// 有口味
								$productStr .='<div class="lt-rt clearfix"><input type="text" class="result zero" is-set="'.$isSet.'" product-id="'.$productId.'" promote-id="'.$promotionId.'" to-group="'.$toGroup.'" can-cupon="'.$canCupon.'" is-discount="0" promotion-money="0" promotion-discount="1" store-number="'.$pProduct['store_number'].'" disabled="disabled" value="0"><div class="add-taste" taste="'.urlencode(json_encode($pProduct['taste_groups'])).'">选规格</div>'.$cartnum.'</div>';
							}else{
								if(isset($cartList[$cartKey])){
									$cartItem = $cartList[$cartKey][0];
									$productStr .='<div class="lt-rt clearfix"><div class="minus">'.$minus.'</div><input type="text" class="result" is-set="'.$isSet.'" product-id="'.$productId.'" promote-id="'.$promotionId.'" to-group="'.$toGroup.'" can-cupon="'.$canCupon.'" detail="0" store-number="'.$pProduct['store_number'].'" disabled="disabled" value="'.$cartItem['num'].'">';
									$productStr .='<div class="add">'.$plus.'</div></div>';
					
									$cartStr .='<div class="j-fooditem cart-dtl-item" data-price="'.$pProduct['price'].'" data-category="#st-buysent'.$key.'" data-orderid="buysent_'.$isSet.'_'.$productId.'_'.$promotionId.'_'.$toGroup.'_'.$canCupon.'_0">';
									$cartStr .='<div class="cart-dtl-item-inner">';
									$cartStr .='<i class="cart-dtl-dot"></i>';
									$cartStr .='<p class="cart-goods-name">'.$pProduct['product_name'].'</p>';
									$cartStr .='<div class="j-item-console cart-dtl-oprt">';
									$cartStr .='<a class="j-add-item add-food" href="javascript:void(0);"><span class="icon i-add-food">+</span></a>';
									$cartStr .='<span class="j-item-num foodop-num">'.$cartItem['num'].'</span> ';
									$cartStr .='<a class="j-remove-item remove-food" href="javascript:void(0);"><span class="icon i-remove-food">-</span></a>';
									$cartStr .='</div>';
									$cartStr .='<span class="cart-dtl-price">¥'.$pProduct['price'].'</span>';
									$cartStr .='</div></div>';
								}else{
									if($pProduct['store_number']!=0){
										$productStr .='<div class="lt-rt clearfix"><div class="minus zero">'.$minus.'</div><input type="text" class="result zero" is-set="'.$isSet.'" product-id="'.$productId.'" promote-id="'.$promotionId.'" to-group="'.$toGroup.'" can-cupon="'.$canCupon.'" detail="0" store-number="'.$pProduct['store_number'].'" disabled="disabled" value="0">';
										$productStr .='<div class="add">'.$plus.'</div><div class="sale-out zero"> 已售罄  </div></div>';
									}else{
										$productStr .='<div class="lt-rt clearfix"><div class="minus zero">'.$minus.'</div><input type="text" class="result zero" is-set="'.$isSet.'" product-id="'.$productId.'" promote-id="'.$promotionId.'" to-group="'.$toGroup.'" can-cupon="'.$canCupon.'" detail="0" store-number="'.$pProduct['store_number'].'" disabled="disabled" value="0">';
										$productStr .='<div class="add zero">'.$plus.'</div><div class="sale-out"> 已售罄  </div></div>';
									}
								}
							}
						}
						$productStr .='</div></div></div>';
					}else{
						// 套餐
						$productStr .= '<div class="prt-lt"><div class="clearfix"><div class="lt-lt"><img src="'.$pProduct['main_picture'].'"></div>';
						$productStr .= '<div class="lt-ct"><p><span class="name">'.$pProduct['product_name'].'</span>';
						$productStr .='</p>';
					
						$productStr .='<p class="pr">';
						if($pProduct['price'] != $pProduct['original_price']){
							$productStr .='<span class="oprice"><strike>¥'.$pProduct['original_price'].'</strike></span>';
						}
						$productStr .= ' ¥<span class="price">'.$pProduct['price'].'</span>';
						$productStr .='</p>';
						
						$hasSelect = false;
						$detailStr = '';
						$detailIds = '';
						// 套餐详情
						$pDetail = $pProduct['detail'];
						foreach($pDetail as $detail){
							if(count($detail) > 1){
								$hasSelect = true;
							}
							foreach ($detail as $detailItem){
								if($detailItem['is_select']=='1'){
									$detailIds .= $detailItem['product_id'].',';
									$detailStr .=$detailItem['product_name'].'×'.$detailItem['number'].' ';
								}
							}
						}
						if(!$closeShop){
							$detailIds = rtrim($detailIds,',');
							if($hasSelect){
								$cartnum = '<b></b>';
								if(isset($cartList[$cartKey])){
									$cartLists = $cartList[$cartKey];
									//$cartnum = '<b style="display:inline;">'.count($cartLists).'</b>';
									$pPrice = $pProduct['price'];
									foreach ($cartLists as $cartItem){
										$detailIds = $cartItem['detail_id'];
										$detailArr = explode(',',$detailIds);
										$cdetailStr = '';
										foreach($pDetail as $detail){
											foreach ($detail as $detailItem){
												if(in_array($detailItem['product_id'], $detailArr)){
													if($isDiscount){
														$pPrice += $detailItem['price']*$prodiscount;
													}else{
														$pPrice += $detailItem['price'];
													}
													$cdetailStr .=$detailItem['product_name'].'×'.$detailItem['number'].' ';
												}
											}
										}
										$pPrice = number_format($pPrice,2);
										$cartStr .='<div class="j-fooditem cart-dtl-item" data-price="'.$pPrice.'" data-category="#st-buysent'.$key.'" data-orderid="buysent_'.$isSet.'_'.$productId.'_'.$promotionId.'_'.$toGroup.'_'.$canCupon.'_'.$detailIds.'">';
										$cartStr .='<div class="cart-dtl-item-inner">';
										$cartStr .='<i class="cart-dtl-dot"></i>';
										$cartStr .='<p class="cart-goods-name">'.$pProduct['product_name'].'</p>';
										$cartStr .='<div class="j-item-console cart-dtl-oprt">';
										$cartStr .='<a class="j-add-item add-food" href="javascript:void(0);"><span class="icon i-add-food">+</span></a>';
										$cartStr .='<span class="j-item-num foodop-num">'.$cartItem['num'].'</span> ';
										$cartStr .='<a class="j-remove-item remove-food" href="javascript:void(0);"><span class="icon i-remove-food">-</span></a>';
										$cartStr .='</div>';
										$cartStr .='<span class="cart-dtl-price">¥'.$pPrice.'</span>';
										$cartStr .='</div>';
										$cartStr .='<div class="cart-dtl-taste">'.$cdetailStr.'</div>';
										$cartStr .='</div>';
									}
								}
								// 有可选套餐
								$productStr .='<div class="lt-rt clearfix"><input type="text" class="result zero" is-set="'.$isSet.'" product-id="'.$productId.'" promote-id="'.$promotionId.'" to-group="'.$toGroup.'" can-cupon="'.$canCupon.'" is-discount="0" promotion-money="0" promotion-discount="1" store-number="'.$pProduct['store_number'].'" disabled="disabled" value="0"><div class="add-detail" detail="'.urlencode(json_encode($pDetail)).'">选套餐</div>'.$cartnum.'</div>';
							}else{
								if(isset($cartList[$cartKey])){
									$cartItem = $cartList[$cartKey][0];
									$productStr .='<div class="lt-rt clearfix"><div class="minus">'.$minus.'</div><input type="text" class="result" is-set="'.$isSet.'" product-id="'.$productId.'" promote-id="'.$promotionId.'" to-group="'.$toGroup.'" can-cupon="'.$canCupon.'" detail="'.$detailIds.'" store-number="'.$pProduct['store_number'].'" disabled="disabled" value="'.$cartItem['num'].'">';
									$productStr .='<div class="add">'.$plus.'</div></div>';
										
									$cartStr .='<div class="j-fooditem cart-dtl-item" data-price="'.$pProduct['price'].'" data-category="#st-buysent'.$key.'" data-orderid="buysent_'.$isSet.'_'.$productId.'_'.$promotionId.'_'.$toGroup.'_'.$canCupon.'_'.$detailIds.'">';
									$cartStr .='<div class="cart-dtl-item-inner">';
									$cartStr .='<i class="cart-dtl-dot"></i>';
									$cartStr .='<p class="cart-goods-name">'.$pProduct['product_name'].'</p>';
									$cartStr .='<div class="j-item-console cart-dtl-oprt">';
									$cartStr .='<a class="j-add-item add-food" href="javascript:void(0);"><span class="icon i-add-food">+</span></a>';
									$cartStr .='<span class="j-item-num foodop-num">'.$cartItem['num'].'</span> ';
									$cartStr .='<a class="j-remove-item remove-food" href="javascript:void(0);"><span class="icon i-remove-food">-</span></a>';
									$cartStr .='</div>';
									$cartStr .='<span class="cart-dtl-price">¥'.$pProduct['price'].'</span>';
									$cartStr .='</div></div>';
								}else{
									if($sentProduct['store_number']!=0){
										$productStr .='<div class="lt-rt clearfix"><div class="minus zero">'.$minus.'</div><input type="text" class="result zero" is-set="'.$isSet.'" product-id="'.$productId.'" promote-id="'.$promotionId.'" to-group="'.$toGroup.'" can-cupon="'.$canCupon.'" detail="'.$detailIds.'" store-number="'.$pProduct['store_number'].'" disabled="disabled" value="0">';
										$productStr .='<div class="add">'.$plus.'</div><div class="sale-out zero"> 已售罄  </div></div>';
									}else{
										$productStr .='<div class="lt-rt clearfix"><div class="minus zero">'.$minus.'</div><input type="text" class="result zero" is-set="'.$isSet.'" product-id="'.$productId.'" promote-id="'.$promotionId.'" to-group="'.$toGroup.'" can-cupon="'.$canCupon.'" detail="'.$detailIds.'" store-number="'.$pProduct['store_number'].'" disabled="disabled" value="0">';
										$productStr .='<div class="add zero">'.$plus.'</div><div class="sale-out"> 已售罄  </div></div>';
									}
								}
							}
						}
						$productStr .='</div></div>';
						// 套餐详情
						$productStr .='<div class="tips">'.$detailStr.'</div>';
						$productStr .='</div>';
					}
				}
			}
			$productStr .='</div>';
		}
	}
	
	// 普通优惠活动
	if(!empty($promotions)){
		foreach ($promotions as $key=>$promotion){
			if($promotion['main_picture']==''){
				$promotion['main_picture']=$defaultNavImg;
			}
			if($current){
				$navLiStr .= '<li class="" abstract="'.$promotion['promotion_abstract'].'"><a href="#st-promotion'.$key.'" onselectstart="return false"><img src="'.$promotion['main_picture'].'" class="nav-img"/><span class="nav-span">'.$promotion['promotion_title'].'</span></a><b></b></li>';
			}else{
				$current = true;
				$topTitle = $promotion['promotion_title'].$promotion['promotion_abstract'];
				$navLiStr .= '<li class="current"abstract="'.$promotion['promotion_abstract'].'"><a href="#st-promotion'.$key.'" onselectstart="return false"><img src="'.$promotion['main_picture'].'" class="nav-img"/><span class="nav-span">'.$promotion['promotion_title'].'</span></a><b></b></li>';
			}
			$productStr .= '<div class="section" id="st-promotion'.$key.'" type="promotion"><div class="prt-title">'.$promotion['promotion_title'].'</div>';
			$proproCateArr = $promotion['product'];
			foreach ($proproCateArr as $cateArr){
				foreach ($cateArr as $objPro){
					$pProduct = $objPro['product'];
					$productId = (int)$pProduct['lid'];
					$isSet = $objPro['is_set'];
					$promotionId = (int)$objPro['normal_promotion_id'];
					$toGroup = $objPro['to_group'];
					$canCupon = $objPro['can_cupon'];
					$isDiscount = $objPro['is_discount'];
					$promoney = $objPro['promotion_money'];
					$prodiscount = $objPro['promotion_discount'];
					$cartKey = 'promotion-'.$productId.'-'.$isSet.'-'.$promotionId.'-'.$toGroup.'-'.$canCupon;
					
					if($pProduct['main_picture']==''){
						$pProduct['main_picture'] = $defaultImg;
					}
					if($isSet==0){
						// 单品
						$productStr .= '<div class="prt-lt"><div class="clearfix"><div class="lt-lt"><img src="'.$pProduct['main_picture'].'"></div>';
						$productStr .= '<div class="lt-ct"><p><span class="name">'.$pProduct['product_name'].'</span>';
						$spicy = $pProduct['spicy'];
						if($spicy==1){
							$productStr .='<span><img src="<?php echo $baseUrl;?>/img/mall/index/spicy1.png" style="width:15px;height:20px;"/></span>';
						}else if($spicy==2){
							$productStr .='<span><img src="<?php echo $baseUrl;?>/img/mall/index/spicy2.png" style="width:15px;height:20px;"/></span>';
						}else if($spicy==3){
							$productStr .='<span><img src="<?php echo $baseUrl;?>/img/mall/index/spicy3.png" style="width:15px;height:20px;"/></span>';
						}
						$productStr .='</p>';
						
						$productStr .='<p class="pr">';
						if($pProduct['price'] != $pProduct['original_price']){
							$productStr .='<span class="oprice"><strike>¥'.$pProduct['original_price'].'</strike></span>';
						}
						$productStr .= ' ¥<span class="price">'.$pProduct['price'].'</span>';
						$productStr .='</p>';
						
						if(!$closeShop){
							if(!empty($pProduct['taste_groups'])){
								$cartnum = '<b></b>';
								if(isset($cartList[$cartKey])){
									$cartLists = $cartList[$cartKey];
									//$cartnum = '<b style="display:inline;">'.count($cartLists).'</b>';
									foreach ($cartLists as $cartItem){
										$tasteStr = $cartItem['detail_id'];
										$tasteArr = explode(',',$tasteStr);
										$tasteNames = '';
										$pPrice = $pProduct['price'];
										foreach ($pProduct['taste_groups'] as $groups){
											foreach ($groups['tastes'] as $taste){
												if(in_array($taste['lid'], $tasteArr)){
													$tasteNames .= $taste['name'].' ';
													if($isDiscount){
														$pPrice += $taste['price']*$prodiscount;
													}else{
														$pPrice += $taste['price'];
													}
												}
											}
										}
										$pPrice = number_format($pPrice,2);
										$cartStr .='<div class="j-fooditem cart-dtl-item" data-price="'.$pPrice.'" data-category="#st-promotion'.$key.'" data-orderid="promotion_'.$isSet.'_'.$productId.'_'.$promotionId.'_'.$toGroup.'_'.$canCupon.'_'.$tasteStr.'">';
										$cartStr .='<div class="cart-dtl-item-inner">';
										$cartStr .='<i class="cart-dtl-dot"></i>';
										$cartStr .='<p class="cart-goods-name">'.$pProduct['product_name'].'</p>';
										$cartStr .='<div class="j-item-console cart-dtl-oprt">';
										$cartStr .='<a class="j-add-item add-food" href="javascript:void(0);"><span class="icon i-add-food">+</span></a>';
										$cartStr .='<span class="j-item-num foodop-num">'.$cartItem['num'].'</span> ';
										$cartStr .='<a class="j-remove-item remove-food" href="javascript:void(0);"><span class="icon i-remove-food">-</span></a>';
										$cartStr .='</div>';
										$cartStr .='<span class="cart-dtl-price">¥'.$pPrice.'</span>';
										$cartStr .='</div>';
										$cartStr .='<div class="cart-dtl-taste">'.$tasteNames.'</div>';
										$cartStr .='</div>';
									}
								}
								// 有口味
								$productStr .='<div class="lt-rt clearfix"><input type="text" class="result zero" is-set="'.$isSet.'" product-id="'.$productId.'" promote-id="'.$promotionId.'" to-group="'.$toGroup.'" can-cupon="'.$canCupon.'"  is-discount="'.$isDiscount.'" promotion-money="'.$promoney.'" promotion-discount="'.$prodiscount.'" store-number="'.$pProduct['store_number'].'" disabled="disabled" value="0"><div class="add-taste" taste="'.urlencode(json_encode($pProduct['taste_groups'])).'">选规格</div>'.$cartnum.'</div>';
							}else{
								if(isset($cartList[$cartKey])){
									$cartItem = $cartList[$cartKey][0];
									$productStr .='<div class="lt-rt clearfix"><div class="minus">'.$minus.'</div><input type="text" class="result" is-set="'.$isSet.'" product-id="'.$productId.'" promote-id="'.$promotionId.'" to-group="'.$toGroup.'" can-cupon="'.$canCupon.'" detail="0" store-number="'.$pProduct['store_number'].'" disabled="disabled" value="'.$cartItem['num'].'">';
									$productStr .='<div class="add">'.$plus.'</div></div>';
								
									$cartStr .='<div class="j-fooditem cart-dtl-item" data-price="'.$pProduct['price'].'" data-category="#st-promotion'.$key.'" data-orderid="promotion_'.$isSet.'_'.$productId.'_'.$promotionId.'_'.$toGroup.'_'.$canCupon.'_0">';
									$cartStr .='<div class="cart-dtl-item-inner">';
									$cartStr .='<i class="cart-dtl-dot"></i>';
									$cartStr .='<p class="cart-goods-name">'.$pProduct['product_name'].'</p>';
									$cartStr .='<div class="j-item-console cart-dtl-oprt">';
									$cartStr .='<a class="j-add-item add-food" href="javascript:void(0);"><span class="icon i-add-food">+</span></a>';
									$cartStr .='<span class="j-item-num foodop-num">'.$cartItem['num'].'</span> ';
									$cartStr .='<a class="j-remove-item remove-food" href="javascript:void(0);"><span class="icon i-remove-food">-</span></a>';
									$cartStr .='</div>';
									$cartStr .='<span class="cart-dtl-price">¥'.$pProduct['price'].'</span>';
									$cartStr .='</div></div>';
								}else{
									if($pProduct['store_number']!=0){
										$productStr .='<div class="lt-rt clearfix"><div class="minus zero">'.$minus.'</div><input type="text" class="result zero" is-set="'.$isSet.'" product-id="'.$productId.'" promote-id="'.$promotionId.'" to-group="'.$toGroup.'" can-cupon="'.$canCupon.'" detail="0" store-number="'.$pProduct['store_number'].'" disabled="disabled" value="0">';
										$productStr .='<div class="add">'.$plus.'</div><div class="sale-out zero"> 已售罄  </div></div>';
									}else{
										$productStr .='<div class="lt-rt clearfix"><div class="minus zero">'.$minus.'</div><input type="text" class="result zero" is-set="'.$isSet.'" product-id="'.$productId.'" promote-id="'.$promotionId.'" to-group="'.$toGroup.'" can-cupon="'.$canCupon.'" detail="0" store-number="'.$pProduct['store_number'].'" disabled="disabled" value="0">';
										$productStr .='<div class="add zero">'.$plus.'</div><div class="sale-out"> 已售罄  </div></div>';
									}
								}
							}
						}
						$productStr .='</div></div></div>';
					}else{
						// 套餐
						$productStr .= '<div class="prt-lt"><div class="clearfix"><div class="lt-lt"><img src="'.$pProduct['main_picture'].'"></div>';
						$productStr .= '<div class="lt-ct"><p><span class="name">'.$pProduct['product_name'].'</span>';
						$productStr .='</p>';
						
						$productStr .='<p class="pr">';
						if($pProduct['price'] != $pProduct['original_price']){
							$productStr .='<span class="oprice"><strike>¥'.$pProduct['original_price'].'</strike></span>';
						}
						$productStr .= ' ¥<span class="price">'.$pProduct['price'].'</span>';
						$productStr .='</p>';
						
						$hasSelect = false;
						$detailStr = '';
						$detailIds = '';
						// 套餐详情
						$pDetail = $pProduct['detail'];
						foreach($pDetail as $detail){
							if(count($detail) > 1){
								$hasSelect = true;
							}
							foreach ($detail as $detailItem){
								if($detailItem['is_select']=='1'){
									$detailIds .= $detailItem['product_id'].',';
									$detailStr .=$detailItem['product_name'].'×'.$detailItem['number'].' ';
								}
							}
						}
						if(!$closeShop){
							$detailIds = rtrim($detailIds,',');
							if($hasSelect){
								$cartnum = '<b></b>';
								if(isset($cartList[$cartKey])){
									$cartLists = $cartList[$cartKey];
									//$cartnum = '<b style="display:inline;">'.count($cartLists).'</b>';
									$pPrice = $pProduct['price'];
									foreach ($cartLists as $cartItem){
										$detailIds = $cartItem['detail_id'];
										$detailArr = explode(',',$detailIds);
										$cdetailStr = '';
										foreach($pDetail as $detail){
											foreach ($detail as $detailItem){
												if(in_array($detailItem['product_id'], $detailArr)){
													if($isDiscount){
														$pPrice += $detailItem['price']*$prodiscount;
													}else{
														$pPrice += $detailItem['price'];
													}
													$cdetailStr .=$detailItem['product_name'].'×'.$detailItem['number'].' ';
												}
											}
										}
										$pPrice = number_format($pPrice,2);
										$cartStr .='<div class="j-fooditem cart-dtl-item" data-price="'.$pPrice.'" data-category="#st-promotion'.$key.'" data-orderid="promotion_'.$isSet.'_'.$productId.'_'.$promotionId.'_'.$toGroup.'_'.$canCupon.'_'.$detailIds.'">';
										$cartStr .='<div class="cart-dtl-item-inner">';
										$cartStr .='<i class="cart-dtl-dot"></i>';
										$cartStr .='<p class="cart-goods-name">'.$pProduct['product_name'].'</p>';
										$cartStr .='<div class="j-item-console cart-dtl-oprt">';
										$cartStr .='<a class="j-add-item add-food" href="javascript:void(0);"><span class="icon i-add-food">+</span></a>';
										$cartStr .='<span class="j-item-num foodop-num">'.$cartItem['num'].'</span> ';
										$cartStr .='<a class="j-remove-item remove-food" href="javascript:void(0);"><span class="icon i-remove-food">-</span></a>';
										$cartStr .='</div>';
										$cartStr .='<span class="cart-dtl-price">¥'.$pPrice.'</span>';
										$cartStr .='</div>';
										$cartStr .='<div class="cart-dtl-taste">'.$cdetailStr.'</div>';
										$cartStr .='</div>';
									}
								}
								// 有可选套餐
								$productStr .='<div class="lt-rt clearfix"><input type="text" class="result zero" is-set="'.$isSet.'" product-id="'.$productId.'" promote-id="'.$promotionId.'" to-group="'.$toGroup.'" can-cupon="'.$canCupon.'"  is-discount="'.$isDiscount.'" promotion-money="'.$promoney.'" promotion-discount="'.$prodiscount.'" store-number="'.$pProduct['store_number'].'" disabled="disabled" value="0"><div class="add-detail" detail="'.urlencode(json_encode($pDetail)).'">选套餐</div>'.$cartnum.'</div>';
							}else{
								if(isset($cartList[$cartKey])){
									$cartItem = $cartList[$cartKey][0];
									$productStr .='<div class="lt-rt clearfix"><div class="minus">'.$minus.'</div><input type="text" class="result" is-set="'.$isSet.'" product-id="'.$productId.'" promote-id="'.$promotionId.'" to-group="'.$toGroup.'" can-cupon="'.$canCupon.'" detail="'.$detailIds.'" store-number="'.$pProduct['store_number'].'" disabled="disabled" value="'.$cartItem['num'].'">';
									$productStr .='<div class="add">'.$plus.'</div></div>';
								
									$cartStr .='<div class="j-fooditem cart-dtl-item" data-price="'.$pProduct['price'].'" data-category="#st-promotion'.$key.'" data-orderid="promotion_'.$isSet.'_'.$productId.'_'.$promotionId.'_'.$toGroup.'_'.$canCupon.'_'.$detailIds.'">';
									$cartStr .='<div class="cart-dtl-item-inner">';
									$cartStr .='<i class="cart-dtl-dot"></i>';
									$cartStr .='<p class="cart-goods-name">'.$pProduct['product_name'].'</p>';
									$cartStr .='<div class="j-item-console cart-dtl-oprt">';
									$cartStr .='<a class="j-add-item add-food" href="javascript:void(0);"><span class="icon i-add-food">+</span></a>';
									$cartStr .='<span class="j-item-num foodop-num">'.$cartItem['num'].'</span> ';
									$cartStr .='<a class="j-remove-item remove-food" href="javascript:void(0);"><span class="icon i-remove-food">-</span></a>';
									$cartStr .='</div>';
									$cartStr .='<span class="cart-dtl-price">¥'.$pProduct['price'].'</span>';
									$cartStr .='</div></div>';
								}else{
									if($pProduct['store_number']!=0){
										$productStr .='<div class="lt-rt clearfix"><div class="minus zero">'.$minus.'</div><input type="text" class="result zero" is-set="'.$isSet.'" product-id="'.$productId.'" promote-id="'.$promotionId.'" to-group="'.$toGroup.'" can-cupon="'.$canCupon.'" detail="'.$detailIds.'" store-number="'.$pProduct['store_number'].'" disabled="disabled" value="0">';
										$productStr .='<div class="add">'.$plus.'</div><div class="sale-out zero"> 已售罄  </div></div>';
									}else{
										$productStr .='<div class="lt-rt clearfix"><div class="minus zero">'.$minus.'</div><input type="text" class="result zero" is-set="'.$isSet.'" product-id="'.$productId.'" promote-id="'.$promotionId.'" to-group="'.$toGroup.'" can-cupon="'.$canCupon.'" detail="'.$detailIds.'" store-number="'.$pProduct['store_number'].'" disabled="disabled" value="0">';
										$productStr .='<div class="add zero">'.$plus.'</div><div class="sale-out"> 已售罄  </div></div>';
									}
								}
							}
						}
						$productStr .='</div></div>';
						// 套餐详情
						$productStr .='<div class="tips">'.$detailStr.'</div>';
						$productStr .='</div>';
					}
				}
			}
			$productStr .='</div>';
		}
	}
	
	foreach ($products as $product){
		if($product['main_picture']==''){
			$product['main_picture']=$defaultNavImg;
		}
		if($current){
			$navLiStr .= '<li class="" abstract=""><a href="#st'.$product['lid'].'"><img src="'.$product['main_picture'].'" class="nav-img" onselectstart="return false"/><span class="nav-span" onselectstart="return false">'.$product['category_name'].'</span></a><b></b></li>';
		}else{
			$current = true;
			$topTitle = $product['category_name'];
			$navLiStr .= '<li class="current" abstract=""><a href="#st'.$product['lid'].'"><img src="'.$product['main_picture'].'" class="nav-img" onselectstart="return false"/><span class="nav-span" onselectstart="return false">'.$product['category_name'].'</span></a><b></b></li>';
		}
		$productLists = $product['product_list'];
		if($product['cate_type']!='2'){
			// 单品分类
			$productStr .='<div class="section" id="st'.$product['lid'].'" type="normal"><div class="prt-title">'.$product['category_name']. '</div>';
			foreach ($productLists as $pProduct){
				$productId = (int)$pProduct['lid'];
				$isSet = 0;
				$promotionId = -1;
				$toGroup = -1;
				$canCupon = 0;
				$cartKey = 'normal-'.$productId.'-0--1--1-0';
				if(in_array('0-'.$pProduct['lid'], $proProIdList) && !isset($cartList[$cartKey])){
					continue;
				}
				if($this->type==2){
					$pProduct['member_price'] = $pProduct['original_price'];
				}
				if($pProduct['main_picture']==''){
					$pProduct['main_picture'] = $defaultImg;
				}
				$productStr .='<div class="prt-lt"><div class="clearfix"><div class="lt-lt"><img src="'.$pProduct['main_picture'].'"></div>';
				$productStr .='<div class="lt-ct"><p><span class="name">'.$pProduct['product_name'].'</span>';
				$spicy = $pProduct['spicy'];
				if($spicy==1){
					$productStr .='<span><img src="<?php echo $baseUrl;?>/img/mall/index/spicy1.png" style="width:15px;height:20px;"/></span>';
				}else if($spicy==2){
					$productStr .='<span><img src="<?php echo $baseUrl;?>/img/mall/index/spicy2.png" style="width:15px;height:20px;"/></span>';
				}else if($spicy==3){
					$productStr .='<span><img src="<?php echo $baseUrl;?>/img/mall/index/spicy3.png" style="width:15px;height:20px;"/></span>';
				}
				$productStr .='</p>';

                $productStr .='<p class="pr">';
                if($pProduct['member_price']!= $pProduct['original_price']){
                    $productStr .='<span class="oprice"><strike>¥'.$pProduct['original_price'].'</strike></span>';
                }
                $productStr .='¥<span class="price">'.$pProduct['member_price'].'</span>';
				$productStr .='</p>';
				if(!$closeShop){
					if(!empty($pProduct['taste_groups'])){
						$cartnum = '<b></b>';
						if(isset($cartList[$cartKey])){
							$cartLists = $cartList[$cartKey];
							//$cartnum = '<b style="display:inline;">'.count($cartLists).'</b>';
							foreach ($cartLists as $cartItem){
								$tasteStr = $cartItem['detail_id'];
								$tasteArr = explode(',',$tasteStr);
								$tasteNames = '';
								$pPrice = $pProduct['member_price'];
								foreach ($pProduct['taste_groups'] as $groups){
									foreach ($groups['tastes'] as $taste){
										if(in_array($taste['lid'], $tasteArr)){
											$tasteNames .= $taste['name'].' ';
											$pPrice += $taste['price'];
										}
									}
								}
								$pPrice = number_format($pPrice,2);
								$cartStr .='<div class="j-fooditem cart-dtl-item" data-price="'.$pPrice.'" data-category="#st'.$product['lid'].'" data-orderid="normal_0_'.$productId.'_-1_-1_0_'.$tasteStr.'">';
								$cartStr .='<div class="cart-dtl-item-inner">';
								$cartStr .='<i class="cart-dtl-dot"></i>';
								$cartStr .='<p class="cart-goods-name">'.$pProduct['product_name'].'</p>';
								$cartStr .='<div class="j-item-console cart-dtl-oprt">';
								$cartStr .='<a class="j-add-item add-food" href="javascript:void(0);"><span class="icon i-add-food">+</span></a>';
								$cartStr .='<span class="j-item-num foodop-num">'.$cartItem['num'].'</span> ';
								$cartStr .='<a class="j-remove-item remove-food" href="javascript:void(0);"><span class="icon i-remove-food">-</span></a>';
								$cartStr .='</div>';
								$cartStr .='<span class="cart-dtl-price">¥'.$pPrice.'</span>';
								$cartStr .='</div>';
								$cartStr .='<div class="cart-dtl-taste">'.$tasteNames.'</div>';
								$cartStr .='</div>';
							}
						}
						// 有口味
						$productStr .='<div class="lt-rt clearfix"><input type="text" class="result zero" is-set="0" product-id="'.$productId.'" promote-id="-1" to-group="-1" can-cupon="0" is-discount="0" store-number="'.$pProduct['store_number'].'" disabled="disabled" value="0"><div class="add-taste" taste="'.urlencode(json_encode($pProduct['taste_groups'])).'">选规格</div>'.$cartnum.'</div>';
					}else{
						// 无口味
						if(isset($cartList[$cartKey])){
							$cartItem = $cartList[$cartKey][0];
							$productStr .='<div class="lt-rt clearfix"><div class="minus">'.$minus.'</div><input type="text" class="result" is-set="0" product-id="'.$productId.'" promote-id="-1" to-group="-1" can-cupon="0" detail="0" store-number="'.$pProduct['store_number'].'" disabled="disabled" value="'.$cartItem['num'].'">';
							$productStr .='<div class="add">'.$plus.'</div></div>';
						
							$cartStr .='<div class="j-fooditem cart-dtl-item" data-price="'.$pProduct['member_price'].'" data-category="#st'.$product['lid'].'" data-orderid="normal_0_'.$productId.'_-1_-1_0_0_0">';
							$cartStr .='<div class="cart-dtl-item-inner">';
							$cartStr .='<i class="cart-dtl-dot"></i>';
							$cartStr .='<p class="cart-goods-name">'.$pProduct['product_name'].'</p>';
							$cartStr .='<div class="j-item-console cart-dtl-oprt">';
							$cartStr .='<a class="j-add-item add-food" href="javascript:void(0);"><span class="icon i-add-food">+</span></a>';
							$cartStr .='<span class="j-item-num foodop-num">'.$cartItem['num'].'</span> ';
							$cartStr .='<a class="j-remove-item remove-food" href="javascript:void(0);"><span class="icon i-remove-food">-</span></a>';
							$cartStr .='</div>';
							$cartStr .='<span class="cart-dtl-price">¥'.$pProduct['member_price'].'</span>';
							$cartStr .='</div>';
							$cartStr .='</div>';
						}else{
							if($pProduct['store_number'] != 0){
								$productStr .='<div class="lt-rt clearfix"><div class="minus zero">'.$minus.'</div><input type="text" class="result zero" is-set="0" product-id="'.$productId.'" promote-id="-1" to-group="-1" can-cupon="0" detail="0" store-number="'.$pProduct['store_number'].'" disabled="disabled" value="0">';
								$productStr .='<div class="add">'.$plus.'</div><div class="sale-out zero"> 已售罄  </div></div>';
							}else{
								$productStr .='<div class="lt-rt clearfix"><div class="minus zero">'.$minus.'</div><input type="text" class="result zero" is-set="0" product-id="'.$productId.'" promote-id="-1" to-group="-1" can-cupon="0" detail="0" store-number="'.$pProduct['store_number'].'" disabled="disabled" value="0">';
								$productStr .='<div class="add zero">'.$plus.'</div><div class="sale-out"> 已售罄  </div></div>';
							}
						}
					}
				}
				$productStr .='</div></div></div>';
			}
			$productStr .='</div>';
		}else{
			// 套餐分类
			$productStr .='<div class="section" id="st'.$product['lid'].'" type="normal"><div class="prt-title">'.$product['category_name']. '</div>';
			foreach($productLists as $pProductSet){
				$productId = (int)$pProductSet['lid'];
				$cartKey = 'normal-'.$productId.'-1--1--1-0';
				if(in_array('1-'.$pProductSet['lid'], $proProIdList) && !isset($cartList[$cartKey])){
					continue;
				}
				
				if($this->type==2){
					$pProductSet['member_price'] = $pProductSet['set_price'];
				}
				
				if($pProductSet['main_picture']==''){
					$pProductSet['main_picture'] = $defaultImg;
				}
				$productStr .='<div class="prt-lt"><div class="clearfix"><div class="lt-lt"><img src="'.$pProductSet['main_picture'].'"></div>';
				$productStr .='<div class="lt-ct"><p><span class="name">'.$pProductSet['set_name'].'</span>';
				
				$productStr .='</p>';

                $productStr .='<p class="pr">';
                if($pProductSet['member_price']!= $pProductSet['set_price']){
                    $productStr .='<span class="oprice"><strike>¥'.$pProductSet['set_price'].'</strike></span>';
                }
                $productStr .='¥<span class="price">'.$pProductSet['member_price'].'</span>';
				$productStr .='</p>';
				
				$hasSelect = false;
				$detailStr = '';
				$detailIds = '';
				// 套餐详情
				$pDetail = $pProductSet['detail'];
				foreach($pDetail as $detail){
					if(count($detail) > 1){
						$hasSelect = true;
					}
					foreach ($detail as $detailItem){
						if($detailItem['is_select']=='1'){
							$detailIds .= $detailItem['product_id'].',';
							$detailStr .=$detailItem['product_name'].'×'.$detailItem['number'].' ';
						}
					}
				}
				if(!$closeShop){
					$detailIds = rtrim($detailIds,',');
					if($hasSelect){
						$cartnum = '<b></b>';
						if(isset($cartList[$cartKey])){
							$cartLists = $cartList[$cartKey];
							//$cartnum = '<b style="display:inline;">'.count($cartLists).'</b>';
							$pPrice = $pProductSet['member_price'];
							foreach ($cartLists as $cartItem){
								$detailIds = $cartItem['detail_id'];
								$detailArr = explode(',',$detailIds);
								$cdetailStr = '';
								foreach($pDetail as $detail){
									foreach ($detail as $detailItem){
										if(in_array($detailItem['product_id'], $detailArr)){
											$pPrice += $detailItem['price'];
											$cdetailStr .=$detailItem['product_name'].'×'.$detailItem['number'].' ';
										}
									}
								}
								$pPrice = number_format($pPrice,2);
								$cartStr .='<div class="j-fooditem cart-dtl-item" data-price="'.$pPrice.'" data-category="#st'.$product['lid'].'" data-orderid="normal_1_'.$productId.'_-1_-1_0_'.$detailIds.'">';
								$cartStr .='<div class="cart-dtl-item-inner">';
								$cartStr .='<i class="cart-dtl-dot"></i>';
								$cartStr .='<p class="cart-goods-name">'.$pProductSet['set_name'].'</p>';
								$cartStr .='<div class="j-item-console cart-dtl-oprt">';
								$cartStr .='<a class="j-add-item add-food" href="javascript:void(0);"><span class="icon i-add-food">+</span></a>';
								$cartStr .='<span class="j-item-num foodop-num">'.$cartItem['num'].'</span> ';
								$cartStr .='<a class="j-remove-item remove-food" href="javascript:void(0);"><span class="icon i-remove-food">-</span></a>';
								$cartStr .='</div>';
								$cartStr .='<span class="cart-dtl-price">¥'.$pPrice.'</span>';
								$cartStr .='</div>';
								$cartStr .='<div class="cart-dtl-taste">'.$cdetailStr.'</div>';
								$cartStr .='</div>';
							}
						}
						// 有可选套餐
						$productStr .='<div class="lt-rt clearfix"><input type="text" class="result zero" is-set="1" product-id="'.$productId.'" promote-id="-1" to-group="-1" can-cupon="0" is-discount="0" store-number="'.$pProductSet['store_number'].'" disabled="disabled" value="0"><div class="add-detail" detail="'.urlencode(json_encode($pDetail)).'">选套餐</div>'.$cartnum.'</div>';
					}else{
						if(isset($cartList[$cartKey])){
							$cartItem = $cartList[$cartKey][0];
							$detailIds = $cartItem['detail_id'];
							$productStr .='<div class="lt-rt clearfix"><div class="minus">'.$minus.'</div><input type="text" class="result" is-set="1" product-id="'.$productId.'" promote-id="-1" to-group="-1" can-cupon="0" detail="'.$detailIds.'" store-number="'.$pProductSet['store_number'].'" disabled="disabled" value="'.$cartItem['num'].'">';
							$productStr .='<div class="add">'.$plus.'</div></div>';
						
							$cartStr .='<div class="j-fooditem cart-dtl-item" data-price="'.$pProductSet['member_price'].'" data-category="#st'.$product['lid'].'" data-orderid="normal_1_'.$productId.'_-1_-1_0_'.$detailIds.'">';
							$cartStr .='<div class="cart-dtl-item-inner">';
							$cartStr .='<i class="cart-dtl-dot"></i>';
							$cartStr .='<p class="cart-goods-name">'.$pProductSet['set_name'].'</p>';
							$cartStr .='<div class="j-item-console cart-dtl-oprt">';
							$cartStr .='<a class="j-add-item add-food" href="javascript:void(0);"><span class="icon i-add-food">+</span></a>';
							$cartStr .='<span class="j-item-num foodop-num">'.$cartItem['num'].'</span> ';
							$cartStr .='<a class="j-remove-item remove-food" href="javascript:void(0);"><span class="icon i-remove-food">-</span></a>';
							$cartStr .='</div>';
							$cartStr .='<span class="cart-dtl-price">¥'.$pProductSet['member_price'].'</span>';
							$cartStr .='</div>';
							$cartStr .='<div class="cart-dtl-taste">'.$detailStr.'</div>';
							$cartStr .='</div>';
						}else{
							if($pProductSet['store_number'] != 0){
								$productStr .='<div class="lt-rt clearfix"><div class="minus zero">'.$minus.'</div><input type="text" class="result zero" is-set="1" product-id="'.$productId.'" promote-id="-1" to-group="-1" can-cupon="0" detail="'.$detailIds.'" store-number="'.$pProductSet['store_number'].'" disabled="disabled" value="0">';
								$productStr .='<div class="add">'.$plus.'</div><div class="sale-out zero"> 已售罄  </div></div>';
							}else{
								$productStr .='<div class="lt-rt clearfix"><div class="minus zero">'.$minus.'</div><input type="text" class="result zero" is-set="1" product-id="'.$productId.'" promote-id="-1" to-group="-1" can-cupon="0" detail="'.$detailIds.'" store-number="'.$pProductSet['store_number'].'" disabled="disabled" value="0">';
								$productStr .='<div class="add zero">'.$plus.'</div><div class="sale-out"> 已售罄  </div></div>';
							}
						}
					}
				}
				$productStr .='</div></div>';
				// 套餐详情
				$productStr .='<div class="tips">'.$detailStr.'</div>';
				$productStr .='</div>';
			}
			$productStr .='</div>';
		}
	}
?>
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css?_=201901111705">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/index.css?_=201901111705">
<style type="text/css">
.layui-layer-setwin .layui-layer-close2 {
	right: -12px;
}
.boll {
	width: 15px;
	height: 15px;
	background-color: #FF5151;
	position: absolute;
	-moz-border-radius: 15px;
	-webkit-border-radius: 15px;
	border-radius: 15px;
	z-index: 5;
	display: none;
}

.none {
	display: none;
}
</style>
<?php if(empty($notices)):?>
<div class="header"><marquee scrolldelay="50">欢迎光临本店:<?php echo $this->company['company_name'];?></marquee></div>
<?php else:?>
<div class="header">
	<marquee scrolldelay="150">
	<?php $noticeInfo = '';
	foreach ($notices as $notice){
		$noticeInfo .= $notice['content'].';';
	}
	echo rtrim($noticeInfo,';');
	?>
	</marquee>
</div>
<?php endif;?>
<div class="content clearfix">
	<div class="nav-lf">
		<ul id="nav">
			<?php echo $navLiStr;?>
		</ul>
	</div>
	
	<div id="container" class="container">
		<div id="product-top" class="container-top" style="display:block;">
			<div><?php echo $topTitle;?></div>
		</div>
		<?php echo $productStr;?>
	</div>
</div>
<footer class="clearfix">
	<div class="cart-img"><div><img alt="" src="<?php echo $baseUrl;?>/img/mall/navcart.png"></div></div>
	<div class="ft-lt">
		<p>￥<span id="total" class="total">0.00</span><span class="nm">(<label class="share"></label>份)</span></p>
	</div>
    <?php if($this->type==2):?>
	    <?php if($start&&$start['fee_price']):?>
		    <div class="ft-rt start" start-price="<?php echo $start['fee_price'];?>">
				<p>
					<a href="<?php echo $this->createUrl('/mall/checkOrder',array('companyId'=>$this->companyId,'type'=>$this->type));?>">选好了 </a>
				</p>
			</div>
			<div class="ft-rt no-start" style="background: #6A706E" start-price="<?php echo $start['fee_price'];?>">
				<p><?php echo (int)$start['fee_price'];?>元起送</p>
			</div>
	    <?php else:?>
		    <div class="ft-rt has-cart" start-price="0">
				<p>
					<a href="<?php echo $this->createUrl('/mall/checkOrder',array('companyId'=>$this->companyId,'type'=>$this->type));?>">选好了 </a>
				</p>
			</div>
			<div class="ft-rt no-cart" style="background: #6A706E" start-price="<?php echo $start['fee_price'];?>">
				<p>选好了</p>
			</div>
	    <?php endif;?>
     <?php else:?>
     <div class="ft-rt has-cart">
     	<a href="<?php echo $this->createUrl('/mall/checkOrder',array('companyId'=>$this->companyId,'type'=>$this->type));?>">
		<p>选好了</p>
		</a>
	</div>
	<div class="ft-rt no-cart" style="background: #6A706E" start-price="<?php echo $start['fee_price'];?>">
		<p>选好了</p>
	</div>
    <?php endif;?>
</footer>

<div id="boll" class="boll"></div>

<div id="taste" class="taste">
	<div class="taste-content">
		<div style="height:50px;"></div>
	</div>
	<div class="taste-bottom">
		<div class="bottom-content clearfix">
			<div class="bottom-left left">￥<span class="p-price">0.00</span></div><div class="bottom-right right clearfix"><div class="minus zero"><?php echo $minus;?></div><input type="text" class="result zero" is-set="0" product-id="0" promote-id="-1" to-group="-1" can-cupon="0" is-discount="0" promotion-money="0" promotion-discount="1" store-number="-1" disabled="disabled" value="0"><div class="add"><?php echo $plus;?></div></div>
		</div>
	</div>
</div>

<div id="detail" class="detail">
	<div class="detail-content"></div>
	<div class="detail-bottom">
		<div class="bottom-content clearfix">
			<div class="bottom-left left">￥<span class="p-price">0.00</span></div><div class="bottom-right right clearfix"><div class="minus zero"><?php echo $minus;?></div><input type="text" class="result zero" is-set="1" product-id="0" promote-id="-1" to-group="-1" can-cupon="0" is-discount="0" promotion-money="0" promotion-discount="1" store-number="-1" disabled="disabled" value="0"><div class="add"><?php echo $plus;?></div></div>
		</div>
	</div>
</div>

<div class="j-mask mask cart-mask" style="display:none;"></div>
<div id="cart-dtl" class="cart-dtl" style="display:none;">
	<div class="cart-dtl-head" style="background-color: white;height:31px;z-index:1;">
		<span class="j-cart-dusbin cart-dusbin" style="background-color: white;"><i></i>清空购物车</span>
	</div>
	<div class="j-cart-dtl-list cart-dtl-list">
		<div class="j-cart-dtl-list-inner" style="transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
		<?php echo $cartStr;?>
		</div>
	</div>
	
</div>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/Adaptive.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/parabola.js"></script>
<script> 
var orderType = '<?php echo $this->type;?>';//订单类型
var hasclose = false; // 店铺是否休息
<?php if($closeShop):?>
hasclose = true;
var resMsg = '<?php echo $this->company['rest_message']?$this->company['rest_message']:"店铺休息中....";?>';
<?php endif;?>
function setTotal(){ 
    var s = 0;
    var v = 0;
    <!--计算总额s 计算总份数 v--> 
    $('.cart-dtl-item').each(function(){ 
		var num = parseInt($(this).find('.foodop-num').html());
		var price = parseFloat($(this).attr('data-price'));
		s += num*price;
		v += num;
    });
    <!-- 计算菜种n --> 
	$('li').each(function(){
		var n = 0;
		var category = $(this).find('a').attr('href');
		$('.cart-dtl-item[data-category="'+category+'"]').each(function(){
			var fnum = parseInt($(this).find('.foodop-num').html());
			n += fnum;
		});
		if(n>0){
			$(this).find("b").html(n).show();		
	    }else{
	    	$(this).find("b").html(n).hide();
	    }
	});
    $(".share").html(v);
    $("#total").html(s.toFixed(2)); 
    if(orderType==2){
    	var startPrice = $('.ft-rt').attr('start-price');
        var total = $("#total").html();
        if(parseFloat(startPrice) > parseFloat(total)){
        	$('.no-start').removeClass('none');
        	$('.start').addClass('none');
        }else{
        	$('.no-start').addClass('none');
        	$('.start').removeClass('none');
        }
    }else{
       if(v > 0){ 
    	   $('.has-cart').removeClass('none');
       	   $('.no-cart').addClass('none');
       }else{
    	   $('.has-cart').addClass('none');
       	   $('.no-cart').removeClass('none');
       }
    }
} 

wx.ready(function(){
	// 分享朋友圈
	wx.onMenuShareTimeline({
	    title: '<?php echo $title;?>', // 分享标题
	    link: '<?php echo $link;?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
	    imgUrl: '<?php echo $imgUrl;?>', // 分享图标
	    success: function () { 
	        // 用户确认分享后执行的回调函数
	    },
	    cancel: function () { 
	        // 用户取消分享后执行的回调函数
	    }
	});
	// 分享朋友
	wx.onMenuShareAppMessage({
	    title: '<?php echo $title;?>', // 分享标题
	    desc: '<?php echo $desc;?>', // 分享描述
	    link: '<?php echo $link;?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
	    imgUrl: '<?php echo $imgUrl;?>', // 分享图标
	    success: function () { 
	        // 用户确认分享后执行的回调函数
	    },
	    cancel: function () { 
	        // 用户取消分享后执行的回调函数
	    }
	});
});
$(document).ready(function(){ 
	var i = 0;
	var j = 0;
	var isScroll = false;
	var headHeight = $('.header').height();
	var topHeight = $('.top-des').height();
	var footHeight = $('footer').height();
	var cHeight = $('body').height()-topHeight-headHeight-footHeight;
	alert(cHeight);
	$(".content").css('padding-top',(topHeight+headHeight));
	$('#nav').css('height',cHeight);
	$('#container').css('height',cHeight);
	$('#nav').find('li.current').next().addClass('b-radius-rt');
	setTotal();
	if(hasclose){
		$('footer').html('<p class="sh-close">'+resMsg+'</p>');
	}
    $('#nav').on('touchstart','li',function(){
    	isScroll = true;
    	var _this = $(this);
    	var ptHeight = $('#product-top').outerHeight();
    	var href = _this.find('a').attr('href');
        $('#nav').find('li').removeClass('current');
        $('#nav').find('li').removeClass('b-radius-rt');
        $('#nav').find('li').removeClass('b-radius-rb');

        if(_this.next().attr('class')==''){
            _this.next().addClass('b-radius-rt');
        }
        if (_this.prev().attr('class')=='') {
            _this.prev().addClass('b-radius-rb');
        }
        _this.addClass('current');
        var pName = _this.find('a span.nav-span').html();
        var desc = _this.attr('abstract');
        $('#product-top').find('div').html(pName+desc);
    });

    $('#container').scroll(function(){
	    var ptHeight = $('.prt-title').outerHeight();
	    $('.section').each(function(){
			if(isScroll){
				isScroll = false;
				return false;
			}
	    	var id = $(this).attr('id');
	        var top = $(this).offset().top;
	        var height = $(this).outerHeight(); //div.section,,,height, padding, border
	        if(top <= ptHeight && (parseInt(top) + parseInt(height) - parseInt(ptHeight)) >= 0){
	            $('a[href=#'+id+']').parents('ul').find('li').removeClass('b-radius-rt');
	            $('a[href=#'+id+']').parents('ul').find('li').removeClass('b-radius-rb');
	    		$('a[href=#'+id+']').parents('ul').find('li').removeClass('current');
	            if($('a[href=#'+id+']').parent('li').next().attr('class')==''){
	                $('a[href=#'+id+']').parent('li').next().addClass('b-radius-rt');
	            }
	            if ($('a[href=#'+id+']').parent('li').prev().attr('class')=='') {
	                $('a[href=#'+id+']').parent('li').prev().addClass('b-radius-rb');
	            }
	        	$('a[href=#'+id+']').parent('li').addClass('current');
	        	var pName = $('a[href=#'+id+']').parent('li').find('a span.nav-span').html();
	        	var desc = $('a[href=#'+id+']').parent('li').attr('abstract');
	            $('#product-top').find('div').html(pName+desc);
	        	var index = $('a[href=#'+id+']').parent('li').index();
	        	var length = $('a[href=#'+id+']').parent('li').parent('ul').find('li').size();
	        	var height = $('#nav').outerHeight();
	        	if(index==0){
					$('#nav').parent().scrollTop(0);
	        	}
	        	if(index!=0&&(index/length)>0.06&&(index/length)<0.2){
					$('#nav').parent().scrollTop(60);
	        	}
	        	if(index!=0&&(index/length)>0.2&&(index/length)<0.6){
					$('#nav').parent().scrollTop(120);
	        	}
	        	if(index!=0&&(index/length)>0.6){
					$('#nav').parent().scrollTop(height);
	        	}
	        	return false;
	        }else{
	        	var pName = $('#nav').find('li.current').find('a span.nav-span').html();
	            var desc = $('#nav').find('li.current').attr('abstract');
	            $('#product-top').find('div').html(pName+desc);
	        }
	    });
	   
	});
	// 选口味
    $('#container').on('click','.add-taste',function(){
        var price = 0;
    	var taste = decodeURIComponent($(this).attr('taste'));
		var tasteObj = JSON.parse(taste);
        var productName = $(this).parents('.lt-ct').find('.name').html();
        var productPrice = $(this).parents('.lt-ct').find('.price').html();
        var parSec = $(this).parents('.section');
    	var parObj = $(this).parents('.prt-lt');
        var t = parObj.find('input[class*=result]');
        var secId = parSec.attr('id');;
        var promoteType = parSec.attr('type');
        var isSet = t.attr('is-set');
        var productId = t.attr('product-id');
        var promoteId = t.attr('promote-id');
        var toGroup = t.attr('to-group');
        var canCupon = t.attr('can-cupon');
        var isdiscount = t.attr('is-discount');
        var promoney = t.attr('promotion-money');
        var prodiscount = t.attr('promotion-discount');
        
        $('#taste').attr('cate-type',secId);
        $('#taste').attr('type',promoteType);
        $('#taste').attr('p-price',productPrice);
        $('#taste').attr('p-name',productName);
        var ti = $('#taste').find('input[class*=result]');
        ti.attr('product-id',productId);
        ti.attr('promote-id',promoteId);
        ti.attr('to-group',toGroup);
        ti.attr('can-cupon',canCupon);
        ti.attr('is-set',isSet);
        ti.attr('is-discount',isdiscount);
        ti.attr('promotion-money',promoney);
        ti.attr('promotion-discount',prodiscount);

        price += parseFloat(productPrice);
		var str = '';
		var tasteIdStr = '';
		for(var i in tasteObj){
			var tasteGroup = tasteObj[i];
			var tasteItems = tasteGroup['tastes'];
			str += '<div class="taste-group">';
			str += '<div class="group-title">'+tasteGroup['name']+'</div>';
			str += '<div class="group-content clearfix">';
			for(var j in tasteItems){
				var tasteItem = tasteItems[j];
				var active = '';
				if(tasteItem['is_selected']=='1'){
					active = 'taste-item-active';
					if(isdiscount=='1'){
						price += parseFloat(tasteItem['price']*prodiscount);
					}else{
						price += parseFloat(tasteItem['price']);
					}
					tasteIdStr += tasteItem['lid']+',';
				}
				str += '<div class="taste-item '+active+'" taste-id="'+tasteItem['lid']+'" taste-price="'+tasteItem['price']+'" taste-name="'+tasteItem['name']+'">'+tasteItem['name']+'</div>';
			}
			str += '</div>';
			str += '</div>';
		}
		str += '<div class="blank-h-5"></div>';
		tasteIdStr = tasteIdStr.substr(0,tasteIdStr.length-1);
		
		$('#taste').find('.taste-content').html(str);
		$('#taste').find('.p-price').html(price.toFixed(2));

		var cartObj = $('.cart-dtl-item[data-orderid="'+promoteType+'_'+isSet+'_'+productId+'_'+promoteId+'_'+toGroup+'_'+canCupon+'_'+tasteIdStr+'"]');
		if(cartObj.length > 0){
	        var num = cartObj.find('.foodop-num').html();
	        ti.val(num);
	        ti.removeClass('zero');
	        $('#taste').find('.minus').removeClass('zero');
        }else{
        	ti.val(0);
        	ti.addClass('zero');
        	$('#taste').find('.minus').addClass('zero'); 
	    }
		layer.open({
		    type: 1,
		    title: productName,
		    shadeClose: true,
		    area: ['80%','60%'],
		    content:$('#taste')
		});
    });
	// 选择规格
    $('#taste').on('click','.taste-item',function(){
        if(!$(this).hasClass('taste-item-active')){
            var price = 0;
            var parObj = $(this).parents('.taste');
            var secId = parObj.attr('cate-type');;
            var promoteType = parObj.attr('type');
            var t = parObj.find('input[class*=result]');
            var productId = t.attr('product-id');
            var promoteId = t.attr('promote-id');
            var toGroup = t.attr('to-group');
            var canCupon = t.attr('can-cupon');
            var isSet = t.attr('is-set');
            var isdiscount = t.attr('is-discount');
            var promoney = t.attr('promotion-money');
            var prodiscount = t.attr('promotion-discount');
            
            var pprice = $('#taste').attr('p-price');
            $(this).parents('.taste-group').find('.taste-item-active').removeClass('taste-item-active');
            $(this).addClass('taste-item-active');
            price += parseFloat(pprice);
            
            var tasteIdStr = '';
            parObj.find('.taste-item-active').each(function(){
            	var tasteId = $(this).attr('taste-id');
				var tprice = $(this).attr('taste-price');
				if(isdiscount=='1'){
					price += parseFloat(tprice*prodiscount);
				}else{
					price += parseFloat(tprice);
				}
				tasteIdStr += tasteId+',';
			});
            tasteIdStr = tasteIdStr.substr(0,tasteIdStr.length-1);
			$('#taste').find('.p-price').html(price.toFixed(2));
			var cartObj = $('.cart-dtl-item[data-orderid="'+promoteType+'_'+isSet+'_'+productId+'_'+promoteId+'_'+toGroup+'_'+canCupon+'_'+tasteIdStr+'"]');
	        if(cartObj.length > 0){
		        var num = cartObj.find('.foodop-num').html();
		        t.val(num);
		        t.removeClass('zero');
		        parObj.find('.minus').removeClass('zero');
	        }else{
        	 	t.val(0);
		        t.addClass('zero');
		        parObj.find('.minus').addClass('zero'); 
		    }
			
        }
    });
    // 规格添加产品
    $('#taste').on('touchstart','.add',function(){
    	var height = $('body').height();
    	var top = $(this).offset().top;
    	var left = $(this).offset().left;

		var parObj = $(this).parents('.taste');
        var secId = parObj.attr('cate-type');;
        var promoteType = parObj.attr('type');
        var pPrice =  parseFloat(parObj.attr('p-price'));
        var pName = parObj.attr('p-name');
        var t = parObj.find('input[class*=result]');
        var productId = t.attr('product-id');
        var promoteId = t.attr('promote-id');
        var toGroup = t.attr('to-group');
        var canCupon = t.attr('can-cupon');
        var isSet = t.attr('is-set');
        var storeNum = t.attr('store-number');
        var isdiscount = t.attr('is-discount');
        var promoney = t.attr('promotion-money');
        var prodiscount = t.attr('promotion-discount');
        var rand = new Date().getTime();

        var tasteNameStr = '';
        var tasteIdStr = '';

        var hasNoSelect = false;
        $('#taste').find('.taste-group').each(function(){
            var itemObj = $(this).find('.taste-item-active');
            if(itemObj.length==0){
            	hasNoSelect = true;
                layer.msg('请先选择规格!!!');
                return false;
            }
            var tasteId = itemObj.attr('taste-id');
            var tasteName = itemObj.attr('taste-name');
            var tprice = itemObj.attr('taste-price');
            tasteNameStr += tasteName+',';
            tasteIdStr += tasteId+',';
            if(isdiscount=='1'){
            	pPrice += parseFloat(tprice*prodiscount);
			}else{
				pPrice += parseFloat(tprice);
			}
        });
		if(hasNoSelect){
			return;
		}
		pPrice = pPrice.toFixed(2);
        tasteNameStr = tasteNameStr.substr(0,tasteNameStr.length-1);
        tasteIdStr = tasteIdStr.substr(0,tasteIdStr.length-1);

        $.ajax({
        	url:'<?php echo $this->createUrl('/mall/addCart',array('companyId'=>$this->companyId,'userId'=>$userId,'type'=>$this->type));?>',
        	data:{productId:productId,promoteType:promoteType,promoteId:promoteId,isSet:isSet,toGroup:toGroup,canCupon:canCupon,detail:tasteIdStr,rand:rand},
        	success:function(msg){
        		if(msg.status){
        			 t.val(parseInt(t.val())+1);
			        if(parseInt(t.val()) > 0){
			            t.siblings(".minus").removeClass('zero');
			            t.removeClass('zero');;
			        }
			        var cartObj = $('.cart-dtl-item[data-orderid="'+promoteType+'_'+isSet+'_'+productId+'_'+promoteId+'_'+toGroup+'_'+canCupon+'_'+tasteIdStr+'"]');
			        if(cartObj.length > 0){
			        	cartObj.find('.foodop-num').html(t.val());
			        }else{
				        var cartStr = '';
					    cartStr +='<div class="j-fooditem cart-dtl-item" data-price="'+pPrice+'" data-category="#'+secId+'" data-orderid="'+promoteType+'_'+isSet+'_'+productId+'_'+promoteId+'_'+toGroup+'_'+canCupon+'_'+tasteIdStr+'">';
        				cartStr +='<div class="cart-dtl-item-inner">';
        				cartStr +='<i class="cart-dtl-dot"></i>';
        				cartStr +='<p class="cart-goods-name">'+ pName +'</p>';
        				cartStr +='<div class="j-item-console cart-dtl-oprt">';
        				cartStr +='<a class="j-add-item add-food" href="javascript:void(0);"><span class="icon i-add-food">+</span></a>'; 
        				cartStr +='<span class="j-item-num foodop-num">1</span> ';
        				cartStr +='<a class="j-remove-item remove-food" href="javascript:void(0);"><span class="icon i-remove-food">-</span></a>';
        				cartStr +='</div>';
        				cartStr +='<span class="cart-dtl-price">¥'+pPrice+'</span>';
        				cartStr +='</div>';
        				cartStr +='<div class="cart-dtl-taste">'+tasteNameStr+'</div>';
        				cartStr +='</div>';
        				$('.j-cart-dtl-list-inner').append(cartStr);
			        }
			        setTotal();
			        //动画
			        var str = '<div id="boll'+i+'" class="boll"></div>';
			    	$('body').append(str);
			    	$('#boll'+i).css({top:top,left:left,display:"block"});
			    	var bool = new Parabola({
						el: "#boll"+i,
						offset: [-left+10, height-top-25],
						curvature: 0.005,
						duration: 500,
						callback:function(){
							$('#boll'+j).remove();
							j++;
						},
						stepCallback:function(x,y){
						}
					});
					
					bool.start();
					i++;
        		}else{
        			$('#boll'+(i-1)).remove();
        			layer.msg(msg.msg);
        		}
        	},
        	dataType:'json'
        });
    });
	// 规格减少产品
	$('#taste').on('touchstart','.minus',function(){ 
		var parObj = $(this).parents('.taste');
        var secId = parObj.attr('cate-type');;
        var promoteType = parObj.attr('type');
        var t = parObj.find('input[class*=result]');
        var productId = t.attr('product-id');
        var promoteId = t.attr('promote-id');
        var toGroup = t.attr('to-group');
        var canCupon = t.attr('can-cupon');
        var isSet = t.attr('is-set');
        var storeNum = t.attr('store-number');
        var rand = new Date().getTime();

        var tasteIdStr = '';
        $('#taste').find('.taste-group').each(function(){
            var itemObj = $(this).find('.taste-item-active');
            var tasteId = itemObj.attr('taste-id');
            tasteIdStr += tasteId+',';
        });
        tasteIdStr = tasteIdStr.substr(0,tasteIdStr.length-1);
        
        $.ajax({
        	url:'<?php echo $this->createUrl('/mall/deleteCart',array('companyId'=>$this->companyId,'userId'=>$userId,'type'=>$this->type));?>',
        	data:{productId:productId,promoteType:promoteType,promoteId:promoteId,isSet:isSet,toGroup:toGroup,canCupon:canCupon,detail:tasteIdStr,rand:rand},
        	success:function(msg){
        		if(msg.status){
    			  if(parseInt(t.val())==1){
			          t.siblings(".minus").addClass('zero');
			          t.addClass('zero');
			          if(parseInt(storeNum)==0){
			          	t.siblings(".add").addClass('zero');
			          	t.siblings(".sale-out").removeClass('zero');
			          }
			       }
			       t.val(parseInt(t.val())-1);
			       if(parseInt(t.val()) < 0){ 
			           t.val(0); 
			   	    }
			       	var cartObj = $('.cart-dtl-item[data-orderid="'+promoteType+'_'+isSet+'_'+productId+'_'+promoteId+'_'+toGroup+'_'+canCupon+'_'+tasteIdStr+'"]');
			        if(cartObj.length > 0){
				        if(parseInt(t.val()) == 0){
				        	cartObj.remove();
					    }else{
					    	cartObj.find('.foodop-num').html(t.val());
						}
			        }
			    	setTotal(); 
        		}else{
        			layer.msg(msg.msg);
        		}
        	},
        	dataType:'json'
        });
    });
    // 选套餐
    $('#container').on('click','.add-detail',function(){
    	var price = 0;
    	var detail = decodeURIComponent($(this).attr('detail'));
		var detailObj = JSON.parse(detail);
        var productName = $(this).parents('.lt-ct').find('.name').html();
        var productPrice = $(this).parents('.lt-ct').find('.price').html();
        var parSec = $(this).parents('.section');
    	var parObj = $(this).parents('.prt-lt');
        var t = parObj.find('input[class*=result]');
        var secId = parSec.attr('id');;
        var promoteType = parSec.attr('type');
        var productId = t.attr('product-id');
        var promoteId = t.attr('promote-id');
        var toGroup = t.attr('to-group');
        var canCupon = t.attr('can-cupon');
        var isSet = t.attr('is-set');
        var isdiscount = t.attr('is-discount');
        var promoney = t.attr('promotion-money');
        var prodiscount = t.attr('promotion-discount');
        
        $('#detail').attr('cate-type',secId);
        $('#detail').attr('type',promoteType);
        $('#detail').attr('p-price',productPrice);
        $('#detail').attr('p-name',productName);
        var ti = $('#detail').find('input[class*=result]');
        ti.attr('product-id',productId);
        ti.attr('promote-id',promoteId);
        ti.attr('to-group',toGroup);
        ti.attr('can-cupon',canCupon);
        ti.attr('is-set',isSet);
        ti.attr('is-discount',isdiscount);
        ti.attr('promotion-money',promoney);
        ti.attr('promotion-discount',prodiscount);

        price += parseFloat(productPrice);
		var str = '';
		var detailIdStr = '';
		for(var i in detailObj){
			var detailItems = detailObj[i];
			str += '<div class="detail-group">';
			str += '<div class="group-title">选择一个</div>';
			str += '<div class="group-content clearfix">';
			for(var j in detailItems){
				var detailItem = detailItems[j];
				var active = '';
				if(detailItem['is_select']=='1'){
					active = 'detail-item-active';
					if(isdiscount){
						price += parseFloat(detailItem['price']*prodiscount);
					}else{
						price += parseFloat(detailItem['price']);
					}
					detailIdStr += detailItem['product_id']+',';
				}
				str += '<div class="detail-item '+active+'" detail-id="'+detailItem['product_id']+'" detail-price="'+detailItem['price']+'" detail-name="'+detailItem['product_name']+'" detail-number="'+detailItem['number']+'">'+detailItem['product_name']+'</div>';
			}
			str += '</div>';
			str += '</div>';
		}
		str += '<div class="blank-h-5"></div>';
		detailIdStr = detailIdStr.substr(0,detailIdStr.length-1);

		$('#detail').find('.detail-content').html(str);
		$('#detail').find('.p-price').html(price.toFixed(2));

		var cartObj = $('.cart-dtl-item[data-orderid="'+promoteType+'_'+isSet+'_'+productId+'_'+promoteId+'_'+toGroup+'_'+canCupon+'_'+detailIdStr+'"]');
		if(cartObj.length > 0){
	        var num = cartObj.find('.foodop-num').html();
	        ti.val(num);
	        ti.removeClass('zero');
	        $('#detail').find('.minus').removeClass('zero');
        }else{
        	ti.val(0);
        	ti.addClass('zero');
        	$('#detail').find('.minus').addClass('zero'); 
	    }
		layer.open({
		    type: 1,
		    title: productName,
		    shadeClose: true,
		    area: ['80%','60%'],
		    content:$('#detail')
		});        
    });
   	// 套餐选择
    $('#detail').on('click','.detail-item',function(){
    	if(!$(this).hasClass('detail-item-active')){
            var price = 0;
            var parObj = $(this).parents('.detail');
            var secId = parObj.attr('cate-type');;
            var promoteType = parObj.attr('type');
            var t = parObj.find('input[class*=result]');
            var productId = t.attr('product-id');
            var promoteId = t.attr('promote-id');
            var toGroup = t.attr('to-group');
            var canCupon = t.attr('can-cupon');
            var isSet = t.attr('is-set');
            var isdiscount = t.attr('is-discount');
            var promoney = t.attr('promotion-money');
            var prodiscount = t.attr('promotion-discount');
            
            var pprice = $('#detail').attr('p-price');
            $(this).parents('.detail-group').find('.detail-item-active').removeClass('detail-item-active');
            $(this).addClass('detail-item-active');
            price += parseFloat(pprice);
            
            var detailIdStr = '';
            parObj.find('.detail-item-active').each(function(){
            	var detailId = $(this).attr('detail-id');
				var tprice = $(this).attr('detail-price');
				if(isdiscount){
					price += parseFloat(tprice*prodiscount);
				}else{
					price += parseFloat(tprice);
				}
				detailIdStr += detailId+',';
			});
            detailIdStr = detailIdStr.substr(0,detailIdStr.length-1);
			$('#detail').find('.p-price').html(price.toFixed(2));
			var cartObj = $('.cart-dtl-item[data-orderid="'+promoteType+'_'+isSet+'_'+productId+'_'+promoteId+'_'+toGroup+'_'+canCupon+'_'+detailIdStr+'"]');
	        if(cartObj.length > 0){
		        var num = cartObj.find('.foodop-num').html();
		        t.val(num);
		        t.removeClass('zero');
		        parObj.find('.minus').removeClass('zero');
	        }else{
        	 	t.val(0);
		        t.addClass('zero');
		        parObj.find('.minus').addClass('zero'); 
		    }
        }     	
    });
    // 套餐添加产品
    $('#detail').on('touchstart','.add',function(){
    	var height = $('body').height();
    	var top = $(this).offset().top;
    	var left = $(this).offset().left;

		var parObj = $(this).parents('.detail');
        var secId = parObj.attr('cate-type');;
        var promoteType = parObj.attr('type');
        var pPrice = parseFloat(parObj.attr('p-price'));
        var pName = parObj.attr('p-name');
        var t = parObj.find('input[class*=result]');
        var productId = t.attr('product-id');
        var promoteId = t.attr('promote-id');
        var toGroup = t.attr('to-group');
        var canCupon = t.attr('can-cupon');
        var isSet = t.attr('is-set');
        var isdiscount = t.attr('is-discount');
        var promoney = t.attr('promotion-money');
        var prodiscount = t.attr('promotion-discount');
        var storeNum = t.attr('store-number');
        var rand = new Date().getTime();

        var detailNameStr = '';
        var detailIdStr = '';
        $('#detail').find('.detail-group').each(function(){
            var itemObj = $(this).find('.detail-item-active');
            if(itemObj.length==0){
                layer.msg('请先选择一个产品!!!');
                return;
            }
            var detailId = itemObj.attr('detail-id');
            var detailName = itemObj.attr('detail-name');
            var detailPrice = itemObj.attr('detail-price');
            var number = itemObj.attr('detail-number');
            detailNameStr += detailName+'×'+number+' ';
            detailIdStr += detailId+',';
            if(isdiscount=='1'){
            	pPrice += parseFloat(detailPrice*prodiscount);
			}else{
				pPrice += parseFloat(detailPrice);
			}
        });
        pPrice = pPrice.toFixed(2);
        detailIdStr = detailIdStr.substr(0,detailIdStr.length-1);
        $.ajax({
        	url:'<?php echo $this->createUrl('/mall/addCart',array('companyId'=>$this->companyId,'userId'=>$userId,'type'=>$this->type));?>',
        	data:{productId:productId,promoteType:promoteType,promoteId:promoteId,isSet:isSet,toGroup:toGroup,canCupon:canCupon,detail:detailIdStr,rand:rand},
        	success:function(msg){
        		if(msg.status){
        			t.val(parseInt(t.val())+1);
			        if(parseInt(t.val()) > 0){
			            t.siblings(".minus").removeClass('zero');
			            t.removeClass('zero');
			        }
			        var cartObj = $('.cart-dtl-item[data-orderid="'+promoteType+'_'+isSet+'_'+productId+'_'+promoteId+'_'+toGroup+'_'+canCupon+'_'+detailIdStr+'"]');
			        if(cartObj.length > 0){
			        	cartObj.find('.foodop-num').html(t.val());
			        }else{
				        var cartStr = '';
					    cartStr +='<div class="j-fooditem cart-dtl-item" data-price="'+pPrice+'" data-category="#'+secId+'" data-orderid="'+promoteType+'_'+isSet+'_'+productId+'_'+promoteId+'_'+toGroup+'_'+canCupon+'_'+detailIdStr+'">';
        				cartStr +='<div class="cart-dtl-item-inner">';
        				cartStr +='<i class="cart-dtl-dot"></i>';
        				cartStr +='<p class="cart-goods-name">'+ pName +'</p>';
        				cartStr +='<div class="j-item-console cart-dtl-oprt">';
        				cartStr +='<a class="j-add-item add-food" href="javascript:void(0);"><span class="icon i-add-food">+</span></a>'; 
        				cartStr +='<span class="j-item-num foodop-num">1</span> ';
        				cartStr +='<a class="j-remove-item remove-food" href="javascript:void(0);"><span class="icon i-remove-food">-</span></a>';
        				cartStr +='</div>';
        				cartStr +='<span class="cart-dtl-price">¥'+pPrice+'</span>';
        				cartStr +='</div>';
        				cartStr +='<div class="cart-dtl-taste">'+detailNameStr+'</div>';
        				cartStr +='</div>';
        				$('.j-cart-dtl-list-inner').append(cartStr);
			        }
			        setTotal();
			        //动画
			        var str = '<div id="boll'+i+'" class="boll"></div>';
			    	$('body').append(str);
			    	$('#boll'+i).css({top:top,left:left,display:"block"});
			    	var bool = new Parabola({
						el: "#boll"+i,
						offset: [-left+10, height-top-25],
						curvature: 0.005,
						duration: 500,
						callback:function(){
							$('#boll'+j).remove();
							j++;
						},
						stepCallback:function(x,y){
						}
					});
					
					bool.start();
					i++;
        		}else{
        			$('#boll'+(i-1)).remove();
        			layer.msg(msg.msg);
        		}
        	},
        	dataType:'json'
        });   	
    });
    // 套餐减少产品
    $('#detail').on('touchstart','.minus',function(){ 
    		var parObj = $(this).parents('.detail');
            var secId = parObj.attr('cate-type');;
            var promoteType = parObj.attr('type');
            var t = parObj.find('input[class*=result]');
            var productId = t.attr('product-id');
            var promoteId = t.attr('promote-id');
            var toGroup = t.attr('to-group');
            var canCupon = t.attr('can-cupon');
            var isSet = t.attr('is-set');
            var storeNum = t.attr('store-number');
            var rand = new Date().getTime();

            var detailIdStr = '';
            $('#detail').find('.detail-group').each(function(){
                var itemObj = $(this).find('.detail-item-active');
                var detailId = itemObj.attr('detail-id');
                detailIdStr += detailId+',';
            });
            detailIdStr = detailIdStr.substr(0,detailIdStr.length-1);
            
            $.ajax({
            	url:'<?php echo $this->createUrl('/mall/deleteCart',array('companyId'=>$this->companyId,'userId'=>$userId,'type'=>$this->type));?>',
            	data:{productId:productId,promoteType:promoteType,promoteId:promoteId,isSet:isSet,toGroup:toGroup,canCupon:canCupon,detail:detailIdStr,rand:rand},
            	success:function(msg){
            		if(msg.status){
        			  if(parseInt(t.val())==1){
    			          t.siblings(".minus").addClass('zero');
    			          t.addClass('zero');
    			          if(parseInt(storeNum)==0){
    			          	t.siblings(".add").addClass('zero');
    			          	t.siblings(".sale-out").removeClass('zero');
    			          }
    			       }
    			       t.val(parseInt(t.val())-1);
    			       if(parseInt(t.val()) < 0){ 
    			           t.val(0); 
    			   	    }
    			       	var cartObj = $('.cart-dtl-item[data-orderid="'+promoteType+'_'+isSet+'_'+productId+'_'+promoteId+'_'+toGroup+'_'+canCupon+'_'+detailIdStr+'"]');
    			        if(cartObj.length > 0){
    				        if(parseInt(t.val()) == 0){
    				        	cartObj.remove();
    					    }else{
    					    	cartObj.find('.foodop-num').html(t.val());
    						}
    			        }
    			    	setTotal(); 
            		}else{
            			layer.msg(msg.msg);
            		}
            	},
            	dataType:'json'
            });
     });
    // 添加产品
    $('#container').on('touchstart','.add',function(){
    	var height = $('body').height();
    	var top = $(this).offset().top;
    	var left = $(this).offset().left;

		var parSec = $(this).parents('.section');
    	var parObj = $(this).parents('.prt-lt');
        var t = parObj.find('input[class*=result]');
        var secId = parSec.attr('id');;
        var promoteType = parSec.attr('type');
        var productId = t.attr('product-id');
        var promoteId = t.attr('promote-id');
        var toGroup = t.attr('to-group');
        var canCupon = t.attr('can-cupon');
        var isSet = t.attr('is-set');
        var detail = t.attr('detail');
        var rand = new Date().getTime();
        
        var detailStr = '';
        if(isSet=='1'){
        	detailStr = parObj.find('.tips').html();
        }
        $.ajax({
        	url:'<?php echo $this->createUrl('/mall/addCart',array('companyId'=>$this->companyId,'userId'=>$userId,'type'=>$this->type));?>',
        	data:{productId:productId,promoteType:promoteType,promoteId:promoteId,isSet:isSet,toGroup:toGroup,canCupon:canCupon,detail:detail,rand:rand},
        	success:function(msg){
        		if(msg.status){
        			 t.val(parseInt(t.val())+1);
			        if(parseInt(t.val()) > 0){
			            t.siblings(".minus").removeClass('zero');
			            t.removeClass('zero');
			        }
			        var cartObj = $('.cart-dtl-item[data-orderid="'+promoteType+'_'+isSet+'_'+productId+'_'+promoteId+'_'+toGroup+'_'+canCupon+'_'+detail+'"]');
			        if(cartObj.length > 0){
			        	cartObj.find('.foodop-num').html(t.val());
			        }else{
				        var pName = parObj.find('.name').html();
				        var pPrice = parObj.find('.price').html();
				        var cartStr = '';
					    cartStr +='<div class="j-fooditem cart-dtl-item" data-price="'+pPrice+'" data-category="#'+secId+'" data-orderid="'+promoteType+'_'+isSet+'_'+productId+'_'+promoteId+'_'+toGroup+'_'+canCupon+'_'+detail+'">';
        				cartStr +='<div class="cart-dtl-item-inner">';
        				cartStr +='<i class="cart-dtl-dot"></i>';
        				cartStr +='<p class="cart-goods-name">'+ pName +'</p>';
        				cartStr +='<div class="j-item-console cart-dtl-oprt">';
        				cartStr +='<a class="j-add-item add-food" href="javascript:void(0);"><span class="icon i-add-food">+</span></a>'; 
        				cartStr +='<span class="j-item-num foodop-num">1</span> ';
        				cartStr +='<a class="j-remove-item remove-food" href="javascript:void(0);"><span class="icon i-remove-food">-</span></a>';
        				cartStr +='</div>';
        				cartStr +='<span class="cart-dtl-price">¥'+pPrice+'</span>';
        				cartStr +='</div>';
        				if(detailStr!=''){
        					cartStr +='<div class="cart-dtl-taste">'+detailStr+'</div>';
            			}
        				cartStr +='</div>';
        				$('.j-cart-dtl-list-inner').append(cartStr);
			        }
			        setTotal();
			        //动画
			        var str = '<div id="boll'+i+'" class="boll"></div>';
			    	$('body').append(str);
			    	$('#boll'+i).css({top:top,left:left,display:"block"});
			    	var bool = new Parabola({
						el: "#boll"+i,
						offset: [-left+10, height-top-25],
						curvature: 0.005,
						duration: 500,
						callback:function(){
							$('#boll'+j).remove();
							j++;
						},
						stepCallback:function(x,y){
						}
					});
					
					bool.start();
					i++;
        		}else{
        			$('#boll'+(i-1)).remove();
        			layer.msg(msg.msg);
        		}
        	},
        	dataType:'json'
        });
    });
    // 减少产品
    $('#container').on('touchstart','.minus',function(){ 
    	var parObj = $(this).parents('.prt-lt');
        var t = parObj.find('input[class*=result]');
        var promoteType = $(this).parents('.section').attr('type');
        var productId = t.attr('product-id');
        var promoteId = t.attr('promote-id');
        var toGroup = t.attr('to-group');
        var isSet = t.attr('is-set');
        var canCupon = t.attr('can-cupon');
        var detail = t.attr('detail');
        var storeNum = t.attr('store-number');
        var rand = new Date().getTime();
        
        $.ajax({
        	url:'<?php echo $this->createUrl('/mall/deleteCart',array('companyId'=>$this->companyId,'userId'=>$userId,'type'=>$this->type));?>',
        	data:{productId:productId,promoteType:promoteType,promoteId:promoteId,isSet:isSet,toGroup:toGroup,canCupon:canCupon,detail:detail,rand:rand},
        	success:function(msg){
        		if(msg.status){
    			  if(parseInt(t.val())==1){
			          t.siblings(".minus").addClass('zero');
			          t.addClass('zero');
			          if(parseInt(storeNum)==0){
			          	t.siblings(".add").addClass('zero');
			          	t.siblings(".sale-out").removeClass('zero');
			          }
			       }
			       t.val(parseInt(t.val())-1);
			       if(parseInt(t.val()) < 0){ 
			           t.val(0); 
			   	    }
			       	var cartObj = $('.cart-dtl-item[data-orderid="'+promoteType+'_'+isSet+'_'+productId+'_'+promoteId+'_'+toGroup+'_'+canCupon+'_'+detail+'"]');
			        if(cartObj.length > 0){
				        if(parseInt(t.val()) == 0){
				        	cartObj.remove();
					    }else{
					    	cartObj.find('.foodop-num').html(t.val());
						}
			        }
			    	setTotal(); 
        		}else{
        			layer.msg(msg.msg);
        		}
        	},
        	dataType:'json'
        });
   });
	// 购物车增加菜品
   $('.j-cart-dtl-list-inner').on('click','.add-food',function(){
        var parentObj = $(this).parents('.cart-dtl-item');
        var dataId = parentObj.attr('data-orderid');
        var dataArr = dataId.split('_');

        var promotionType = dataArr[0];
        var isSet = dataArr[1];
        var productId = dataArr[2];
        var promoteId = dataArr[3];
        var toGroup = dataArr[4];
        var canCupon = dataArr[5];
        var detail = dataArr[6];
        var rand = new Date().getTime();

        var cartObj = $('.cart-dtl-item[data-orderid="'+promotionType+'_'+isSet+'_'+productId+'_'+promoteId+'_'+toGroup+'_'+canCupon+'_'+detail+'"]');
        var t = $('.prt-lt').find('input[class*=result][is-set="'+isSet+'"][product-id="'+productId+'"][promote-id="'+promoteId+'"][to-group="'+toGroup+'"][can-cupon="'+canCupon+'"]');
        
        $.ajax({
        	url:'<?php echo $this->createUrl('/mall/addCart',array('companyId'=>$this->companyId,'userId'=>$userId));?>',
        	data:{productId:productId,promoteType:promotionType,promoteId:promoteId,isSet:isSet,toGroup:toGroup,canCupon:canCupon,detail:detail,rand:rand},
        	success:function(msg){
        		if(msg.status){
            		var num = cartObj.find('.foodop-num').html();
			        if(cartObj.length > 0){
			        	cartObj.find('.foodop-num').html(parseInt(num)+1);
			        }
			        t.val(parseInt(num)+1);
			        setTotal();
        		}
        	},
        	dataType:'json'
        });
    });
	// 购物车减少菜品
    $('.j-cart-dtl-list-inner').on('click','.remove-food',function(){
       var parentObj = $(this).parents('.cart-dtl-item');
       var dataId = parentObj.attr('data-orderid');
       var dataArr = dataId.split('_');

       var promotionType = dataArr[0];
       var isSet = dataArr[1];
       var productId = dataArr[2];
       var promoteId = dataArr[3];
       var toGroup = dataArr[4];
       var canCupon = dataArr[5];
       var tastes = dataArr[6];
       
       var cartObj = $('.cart-dtl-item[data-orderid="'+promotionType+'_'+isSet+'_'+productId+'_'+promoteId+'_'+toGroup+'_'+canCupon+'_'+tastes+'"]');
       var t = $('.prt-lt').find('input[class*=result][is-set="'+isSet+'"][product-id="'+productId+'"][promote-id="'+promoteId+'"][to-group="'+toGroup+'"][can-cupon="'+canCupon+'"]');
       var storeNum = t.attr('store-number');
       var rand = new Date().getTime();
       $.ajax({
	       	url:'<?php echo $this->createUrl('/mall/deleteCart',array('companyId'=>$this->companyId,'userId'=>$userId));?>',
	       	data:{productId:productId,promoteType:promotionType,promoteId:promoteId,isSet:isSet,toGroup:toGroup,canCupon:canCupon,detail:tastes,rand:rand},
	       	success:function(msg){
	       		if(msg.status){
		       		var num = cartObj.find('.foodop-num').html();
	       			if(cartObj.length > 0){
				        if(parseInt(num) == 1){
				        	if($('.cart-dtl-item').length == 1){
				        		$('.ft-lt').trigger('click');
				        	}
				        	cartObj.remove();
					    }else{
					    	cartObj.find('.foodop-num').html(parseInt(num)-1);
						}
						
			        }
			        if(parseInt(num)<0){
			        	t.val(0); 
				    }else if(parseInt(num)==1){
			          	t.siblings(".minus").addClass('zero');
			          	t.addClass('zero');
			          	if(parseInt(storeNum)==0){
				          	t.siblings(".add").addClass('zero');
				          	t.siblings(".sale-out").removeClass('zero');
			          	}
			       	}else{
				       	t.val(parseInt(num)-1);
				    }
			    	setTotal(); 
	       		}else{
	       			layer.msg(msg.msg);
	       		}
	       	},
	       	dataType:'json'
       });
    });
	// 清空购物车
    $('.j-cart-dusbin').on('click',function(){
        $.ajax({
        	url:"<?php echo $this->createUrl('/mall/deleteCart',array('companyId'=>$this->companyId,'userId'=>$userId,'all'=>1));?>",
        	success:function(msg){
        		if(msg){
            		$('input.result').each(function(){
                		$(this).addClass('zero');
                		$(this).parent().find('.minus').addClass('zero');
                		$(this).val(0);
                	});
            		$('.ft-lt').trigger('click');
        			$('.j-cart-dtl-list-inner').html('');
			        setTotal();
        		}else{
        			layer.msg('清空购物车失败,请重试');
        		}
        	},
        });
    });
    // 删除购物车产品
    $('.j-cart-dtl-list-inner').on('click','.cart-delete',function(){ 
		var _this = $(this);
	  	var lid = _this.attr('lid');
	      
	    $.ajax({
	      	url:'<?php echo $this->createUrl('/mall/deleteCartItem',array('companyId'=>$this->companyId));?>',
	      	data:{lid:lid},
	      	success:function(msg){
	      		if(msg.status){
	      			_this.parents('.cart-dtl-item').remove();
	      		}else{
	      			layer.msg(msg.msg);
	      		}
	      	},
	      	dataType:'json'
	     });
	});
    $('.j-mask').on('click',function(){
        $('.ft-lt').trigger('click');
    });
    $('footer').on('click','.ft-lt,.cart-img',function(){
        if($('.cart-dtl-item').length == 0){
            return;
        }
        if($('.j-mask').is(':visible')){
             var hight = $('#cart-dtl').outerHeight();
             $('#cart-dtl').animate({bottom:-hight},function(){
                 $('.j-mask').hide();
                 $('.cart-dtl').hide();
             });
        }else{
             $('#cart-dtl').show();
             $('#cart-dtl').animate({bottom:50},function(){
                 $('.j-mask').show();
             });
        }
    });
//     $('#container').on('click','.lt-lt img',function(){
// 		var src = $(this).attr('src');
// 		var str = '<img src="'+src+'"/>';
//     	layer.open({
// 		    type: 1,
// 		    title: false,
// 		    closeBtn: true,
// 		    area: ['100%', 'auto'],
// 		    skin: 'layui-layer-nobg', //没有背景色
// 		    shadeClose: false,
// 		    content: str
// 		});
// 		$('.layui-layer-content').css('overflow','hidden');
//     });
});
</script>
