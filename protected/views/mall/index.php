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
			if($promotionId > 0){
				$promotionType = $disable['promotion_type'];
				if($promotionType=='buysent'){
					$promotionId = (int)$disable['buysent_pro_id'];
				}
				$toGroup = $disable['to_group'];
				$canCupon = $disable['can_cupon'];
				
				$cartStr .='<div class="j-fooditem cart-dtl-item disable" data-orderid="'.$promotionType.'_'.$isSet.'_'.$productId.'_'.$promotionId.'_'.$toGroup.'_'.$canCupon.'">';
				$cartStr .='<div class="cart-dtl-item-inner">';
				$cartStr .='<i class="cart-dtl-dot"></i>';
				$cartStr .='<p class="cart-goods-name">'.$disable['product_name'].'-'.$disable['msg'].'</p>';
				$cartStr .='<div class="j-item-console cart-dtl-oprt">';
				$cartStr .='<span class="cart-delete" lid="'.$disable['lid'].'">删除</span>';
				$cartStr .='</div>';
				$cartStr .='<span class="cart-dtl-price">¥'.$disable['member_price'].'</span>';
				$cartStr .='</div></div>';
			}else{
				$cartStr .='<div class="j-fooditem cart-dtl-item disable" data-orderid="normal_'.$isSet.'_'.$productId.'_-1_-1_0">';
				$cartStr .='<div class="cart-dtl-item-inner">';
				$cartStr .='<i class="cart-dtl-dot"></i>';
				$cartStr .='<p class="cart-goods-name">'.$disable['product_name'].'-'.$disable['msg'].'</p>';
				$cartStr .='<div class="j-item-console cart-dtl-oprt">';
				$cartStr .='<span class="cart-delete" lid="'.$disable['lid'].'">删除</span>';
				$cartStr .='</div>';
				$cartStr .='<span class="cart-dtl-price">¥'.$disable['member_price'].'</span>';
				$cartStr .='</div>';
				$cartStr .='</div>';
			}
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
				foreach ($cateArr as $sent){
					$isSet = $sent['is_set'];
					$sentProduct = $sent['product'];
					if($sentProduct['main_picture']==''){
						$sentProduct['main_picture'] = $defaultImg;
					}
					$productStr .= '<div class="prt-lt"><div class="lt-lt"><img src="'.$sentProduct['main_picture'].'"></div>';
					$productStr .= '<div class="lt-ct"><p><span class="name">'.$sentProduct['product_name'].'</span>';
					if($isSet==0){
						$spicy = $sentProduct['spicy'];
						if($spicy==1){
							$productStr .='<span><img src="<?php echo $baseUrl;?>/img/mall/index/spicy1.png" style="width:15px;height:20px;"/></span>';
						}else if($spicy==2){
							$productStr .='<span><img src="<?php echo $baseUrl;?>/img/mall/index/spicy2.png" style="width:15px;height:20px;"/></span>';
						}else if($spicy==3){
							$productStr .='<span><img src="<?php echo $baseUrl;?>/img/mall/index/spicy3.png" style="width:15px;height:20px;"/></span>';
						}
					}
					$productStr .='</p>';
						
					$productStr .='<p class="pr">';
					if($sentProduct['price'] != $sentProduct['original_price']){
						$productStr .='<span class="oprice"><strike>¥'.$sentProduct['original_price'].'</strike></span>';
					}
					$productStr .= ' ¥<span class="price">'.$sentProduct['price'].'</span>';
					$productStr .='</p>';
						
					if(!$closeShop){
						$productId = (int)$sentProduct['lid'];
						$promotionId = (int)$sent['buysent_pro_id'];
						$toGroup = $sent['to_group'];
						$canCupon = $sent['can_cupon'];
						$cartKey = 'buysent-'.$productId.'-'.$isSet.'-'.$promotionId.'-'.$toGroup.'-'.$canCupon;
						if(isset($cartList[$cartKey])){
							$cartItem = $cartList[$cartKey];
							$productStr .='<div class="lt-rt"><div class="minus">'.$minus.'</div><input type="text" class="result" is-set="'.$isSet.'" product-id="'.$productId.'" promote-id="'.$promotionId.'" to-group="'.$toGroup.'" can-cupon="'.$canCupon.'" store-number="'.$sentProduct['store_number'].'" disabled="disabled" value="'.$cartItem['num'].'">';
							$productStr .='<div class="add">'.$plus.'</div><div class="clear"></div></div>';
								
							$cartStr .='<div class="j-fooditem cart-dtl-item" data-orderid="buysent_'.$isSet.'_'.$productId.'_'.$promotionId.'_'.$toGroup.'_'.$canCupon.'">';
							$cartStr .='<div class="cart-dtl-item-inner">';
							$cartStr .='<i class="cart-dtl-dot"></i>';
							$cartStr .='<p class="cart-goods-name">'.$sentProduct['product_name'].'</p>';
							$cartStr .='<div class="j-item-console cart-dtl-oprt">';
							$cartStr .='<a class="j-add-item add-food" href="javascript:void(0);"><span class="icon i-add-food">+</span></a>';
							$cartStr .='<span class="j-item-num foodop-num">'.$cartItem['num'].'</span> ';
							$cartStr .='<a class="j-remove-item remove-food" href="javascript:void(0);"><span class="icon i-remove-food">-</span></a>';
							$cartStr .='</div>';
							$cartStr .='<span class="cart-dtl-price">¥'.$sentProduct['price'].'</span>';
							$cartStr .='</div></div>';
						}else{
							if($sentProduct['store_number']!=0){
								$productStr .='<div class="lt-rt"><div class="minus zero">'.$minus.'</div><input type="text" class="result zero" is-set="'.$isSet.'" product-id="'.$productId.'" promote-id="'.$promotionId.'" to-group="'.$toGroup.'" can-cupon="'.$canCupon.'" store-number="'.$sentProduct['store_number'].'" disabled="disabled" value="0">';
								$productStr .='<div class="add">'.$plus.'</div><div class="clear"></div><div class="sale-out zero"> 已售罄  </div></div>';
							}else{
								$productStr .='<div class="lt-rt"><div class="minus zero">'.$minus.'</div><input type="text" class="result zero" is-set="'.$isSet.'" product-id="'.$productId.'" promote-id="'.$promotionId.'" to-group="'.$toGroup.'" can-cupon="'.$canCupon.'" store-number="'.$sentProduct['store_number'].'" disabled="disabled" value="0">';
								$productStr .='<div class="add zero">'.$plus.'</div><div class="clear"></div><div class="sale-out"> 已售罄  </div></div>';
							}
						}
					}
					$productStr .='</div></div>';
				}
			}
			$productStr .='</div>';
		}
	}
	
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
					$isSet = $objPro['is_set'];
					$promotionProduct = $objPro['product'];
					if($promotionProduct['main_picture']==''){
						$promotionProduct['main_picture'] = $defaultImg;
					}
					$productStr .= '<div class="prt-lt"><div class="lt-lt"><img src="'.$promotionProduct['main_picture'].'"></div>';
					$productStr .= '<div class="lt-ct"><p><span class="name">'.$promotionProduct['product_name'].'</span>';
					if($isSet==0){
						$spicy = $promotionProduct['spicy'];
						if($spicy==1){
							$productStr .='<span><img src="<?php echo $baseUrl;?>/img/mall/index/spicy1.png" style="width:15px;height:20px;"/></span>';
						}else if($spicy==2){
							$productStr .='<span><img src="<?php echo $baseUrl;?>/img/mall/index/spicy2.png" style="width:15px;height:20px;"/></span>';
						}else if($spicy==3){
							$productStr .='<span><img src="<?php echo $baseUrl;?>/img/mall/index/spicy3.png" style="width:15px;height:20px;"/></span>';
						}
					}
					$productStr .='</p>';
			
					$productStr .='<p class="pr">';
					if($promotionProduct['price'] != $promotionProduct['original_price']){
						$productStr .='<span class="oprice"><strike>¥'.$promotionProduct['original_price'].'</strike></span>';
					}
					$productStr .= ' ¥<span class="price">'.$promotionProduct['price'].'</span>';
					$productStr .='</p>';
			
					if(!$closeShop){
						$productId = (int)$promotionProduct['lid'];
						$isSet = $objPro['is_set'];
						$promotionId = (int)$objPro['normal_promotion_id'];
						$toGroup = $objPro['to_group'];
						$canCupon = $objPro['can_cupon'];
						$cartKey = 'promotion-'.$productId.'-'.$isSet.'-'.$promotionId.'-'.$toGroup.'-'.$canCupon;
						if(isset($cartList[$cartKey])){
							$cartItem = $cartList[$cartKey];
							$productStr .='<div class="lt-rt"><div class="minus">'.$minus.'</div><input type="text" class="result" is-set="'.$isSet.'" product-id="'.$productId.'" promote-id="'.$promotionId.'" to-group="'.$toGroup.'" can-cupon="'.$canCupon.'" store-number="'.$promotionProduct['store_number'].'" disabled="disabled" value="'.$cartItem['num'].'">';
							$productStr .='<div class="add">'.$plus.'</div><div class="clear"></div></div>';
								
							$cartStr .='<div class="j-fooditem cart-dtl-item" data-orderid="promotion_'.$isSet.'_'.$productId.'_'.$promotionId.'_'.$toGroup.'_'.$canCupon.'">';
							$cartStr .='<div class="cart-dtl-item-inner">';
							$cartStr .='<i class="cart-dtl-dot"></i>';
							$cartStr .='<p class="cart-goods-name">'.$promotionProduct['product_name'].'</p>';
							$cartStr .='<div class="j-item-console cart-dtl-oprt">';
							$cartStr .='<a class="j-add-item add-food" href="javascript:void(0);"><span class="icon i-add-food">+</span></a>';
							$cartStr .='<span class="j-item-num foodop-num">'.$cartItem['num'].'</span> ';
							$cartStr .='<a class="j-remove-item remove-food" href="javascript:void(0);"><span class="icon i-remove-food">-</span></a>';
							$cartStr .='</div>';
							$cartStr .='<span class="cart-dtl-price">¥'.$promotionProduct['price'].'</span>';
							$cartStr .='</div></div>';
						}else{
							if($promotionProduct['store_number']!=0){
								$productStr .='<div class="lt-rt"><div class="minus zero">'.$minus.'</div><input type="text" class="result zero" is-set="'.$isSet.'" product-id="'.$productId.'" promote-id="'.$promotionId.'" to-group="'.$toGroup.'" can-cupon="'.$canCupon.'" store-number="'.$promotionProduct['store_number'].'" disabled="disabled" value="0">';
								$productStr .='<div class="add">'.$plus.'</div><div class="clear"></div><div class="sale-out zero"> 已售罄  </div></div>';
							}else{
								$productStr .='<div class="lt-rt"><div class="minus zero">'.$minus.'</div><input type="text" class="result zero" is-set="'.$isSet.'" product-id="'.$productId.'" promote-id="'.$promotionId.'" to-group="'.$toGroup.'" can-cupon="'.$canCupon.'" store-number="'.$promotionProduct['store_number'].'" disabled="disabled" value="0">';
								$productStr .='<div class="add zero">'.$plus.'</div><div class="clear"></div><div class="sale-out"> 已售罄  </div></div>';
							}
						}
					}
					$productStr .='</div></div>';
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
				$productStr .='<div class="prt-lt"><div class="lt-lt"><img src="'.$pProduct['main_picture'].'"></div>';
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
						// 有口味
						$productStr .='<div class="lt-rt"><div class="add-taste">选口味</div></div>';
					}else{
						// 无口味
						if(isset($cartList[$cartKey])){
							$cartItem = $cartList[$cartKey];
							$productStr .='<div class="lt-rt"><div class="minus">'.$minus.'</div><input type="text" class="result" is-set="0" product-id="'.$productId.'" promote-id="-1" to-group="-1" can-cupon="0" store-number="'.$pProduct['store_number'].'" disabled="disabled" value="'.$cartItem['num'].'">';
							$productStr .='<div class="add">'.$plus.'</div><div class="clear"></div></div>';
						
							$cartStr .='<div class="j-fooditem cart-dtl-item" data-orderid="normal_0_'.$productId.'_-1_-1_0">';
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
								$productStr .='<div class="lt-rt"><div class="minus zero">'.$minus.'</div><input type="text" class="result zero" is-set="0" product-id="'.$productId.'" promote-id="-1" to-group="-1" can-cupon="0" store-number="'.$pProduct['store_number'].'" disabled="disabled" value="0">';
								$productStr .='<div class="add">'.$plus.'</div><div class="clear"></div><div class="sale-out zero"> 已售罄  </div></div><div class="clear"></div>';
							}else{
								$productStr .='<div class="lt-rt"><div class="minus zero">'.$minus.'</div><input type="text" class="result zero" is-set="0" product-id="'.$productId.'" promote-id="-1" to-group="-1" can-cupon="0" store-number="'.$pProduct['store_number'].'" disabled="disabled" value="0">';
								$productStr .='<div class="add zero">'.$plus.'</div><div class="clear"></div><div class="sale-out"> 已售罄  </div></div><div class="clear"></div>';
							}
						}
					}
				}
				$productStr .='</div></div>';
			}
			$productStr .='</div>';
		}else{
			// 套餐
			$productStr .='<div class="section" id="st'.$product['lid'].'" type="normal"><div class="prt-title">'.$product['category_name']. '</div>';
			foreach($productLists as $pProductSet){
				if(in_array('1-'.$pProductSet['lid'], $proProIdList)){
					continue;
				}
				if($this->type==2){
					$pProductSet['member_price'] = $pProductSet['set_price'];
				}
				$pDetail = $pProductSet['detail'];
				if($pProductSet['main_picture']==''){
					$pProductSet['main_picture'] = $defaultImg;
				}
				$productStr .='<div class="prt-lt"><div class="lt-lt"><img src="'.$pProductSet['main_picture'].'"></div>';
				$productStr .='<div class="lt-ct"><p><span class="name">'.$pProductSet['set_name'].'</span>';
				
				$productStr .='</p>';

                $productStr .='<p class="pr">';
                if($pProductSet['member_price']!= $pProductSet['set_price']){
                    $productStr .='<span class="oprice"><strike>¥'.$pProductSet['set_price'].'</strike></span>';
                }
                $productStr .='¥<span class="price">'.$pProductSet['member_price'].'</span>';
				$productStr .='</p>';
				if(!$closeShop){
					$productId = (int)$pProductSet['lid'];
					$cartKey = 'normal-'.$productId.'-1--1--1-0';
					if(isset($cartList[$cartKey])){
						$cartItem = $cartList[$cartKey];
						$productStr .='<div class="lt-rt"><div class="minus">'.$minus.'</div><input type="text" class="result" is-set="1" product-id="'.$productId.'" promote-id="-1" to-group="-1" can-cupon="0" store-number="'.$pProductSet['store_number'].'" disabled="disabled" value="'.$cartItem['num'].'">';
						$productStr .='<div class="add">'.$plus.'</div><div class="clear"></div></div>';
		
						$cartStr .='<div class="j-fooditem cart-dtl-item" data-orderid="1_'.$productId.'_-1_-1_0">';
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
						$cartStr .='</div>';
					}else{
						if($pProductSet['store_number'] != 0){
							$productStr .='<div class="lt-rt"><div class="minus zero">'.$minus.'</div><input type="text" class="result zero" is-set="1" product-id="'.$productId.'" promote-id="-1" to-group="-1" can-cupon="0" store-number="'.$pProductSet['store_number'].'" disabled="disabled" value="0">';
							$productStr .='<div class="add">'.$plus.'</div><div class="clear"></div><div class="sale-out zero"> 已售罄  </div></div>';
						}else{
							$productStr .='<div class="lt-rt"><div class="minus zero">'.$minus.'</div><input type="text" class="result zero" is-set="1" product-id="'.$productId.'" promote-id="-1" to-group="-1" can-cupon="0" store-number="'.$pProductSet['store_number'].'" disabled="disabled" value="0">';
							$productStr .='<div class="add zero">'.$plus.'</div><div class="clear"></div><div class="sale-out"> 已售罄  </div></div>';
						}
					}
				}
				$productStr .='</div><div class="clear"></div>';
				// 套餐详情
				$productStr .='<div class="tips">';
				foreach($pDetail as $detail){
					foreach ($detail as $detailItem){
						if($detailItem['is_select']=='1'){
							$productStr .=$detailItem['product_name'].'x'.$detailItem['number'].' ';
						}
					}
				}
				$productStr .='</div></div>';
			}
			$productStr .='</div>';
		}
	}
?>
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css?_=201712121424">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/index.css?_=201812191541">
<style type="text/css">
.layui-layer-content img {
	width: 100%;
	height: 100%;
}
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
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/Adaptive.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/parabola.js"></script>
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
<div class="content">
	<div class="nav-lf">
		<ul id="nav">
			
		</ul>
	</div>
	
	<div id="container" class="container">
		<div id="product-top" class="container-top" style="display:block;">
			<div><?php echo $topTitle;?></div>
		</div>
		
	</div>
</div>
<footer>
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
    <div class="clear"></div>
</footer>

<div id="boll" class="boll"></div>


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
<script> 
var orderType = '<?php echo $this->type;?>';//订单类型
var hasclose = false; // 店铺是否休息
<?php if($closeShop):?>
hasclose = true;
var resMsg = '<?php echo $this->company['rest_message']?$this->company['rest_message']:"店铺休息中....";?>';
<?php endif;?>
function setTotal(){ 
    var s=0;
    var v=0;
    var n=0;
    <!--计算总额--> 
    $(".lt-rt").each(function(){ 
    	s+=parseInt($(this).find('input[class*=result]').val())*parseFloat($(this).siblings().find('span[class*=price]:last-child').text()); 
    });

    <!--计算菜种-->
    $('li').each(function(){
    	var nIn = $(this).find("a").attr("href");
	    $(nIn).find("input[type='text']").each(function() {
	    	if(parseInt($(this).val()) > 0){
	    		n++;
	    	}
	    });
	    if(n>0){
    		$(this).find("b").html(n).show();		
	    }else{
	    	$(this).find("b").hide();		
	    }
	    n = 0;	
    });

    <!--计算总份数-->
    $("input[type='text']").each(function(){
    	v += parseInt($(this).val());
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
	var navListr = '<?php echo $navLiStr;?>';
	var productStr = '<?php echo $productStr;?>';
	$(".content").css({"height" : cHeight+"px","padding-top" : (topHeight+headHeight)+"px"});
	$('#nav').html(navListr);
	$('#nav').find('li.current').next().addClass('b-radius-rt');
	$('#container').append(productStr);
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

		// var top = $(href).offset().top;
		// var height = $(href).outerHeight();
  //       $('#container').scrollTop(top-40);

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
		// alert(isScroll);
	    var ptHeight = $('.prt-title').outerHeight();
	    $('.section').each(function(){
		if(isScroll){
			isScroll = false;
			return false;
		}
			// alert(isScroll);
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
        	// alert("index"+index);
				$('#nav').parent().scrollTop(0);
        	}
        	if(index!=0&&(index/length)>0.06&&(index/length)<0.2){
        	// alert(index/length);
				$('#nav').parent().scrollTop(60);
        	}
        	if(index!=0&&(index/length)>0.2&&(index/length)<0.6){
        	// alert(index/length);
				$('#nav').parent().scrollTop(120);
        	}
        	if(index!=0&&(index/length)>0.6){
        	// alert(index/length);
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


    $('#container').on('touchstart','.add',function(){
    	var height = $('body').height();
    	var top = $(this).offset().top;
    	var left = $(this).offset().left;
		
    	var parObj = $(this).parents('.prt-lt');
        var t = parObj.find('input[class*=result]');
        var promoteType = $(this).parents('.section').attr('type');
        var productId = t.attr('product-id');
        var promoteId = t.attr('promote-id');
        var toGroup = t.attr('to-group');
        var canCupon = t.attr('can-cupon');
        var isSet = t.attr('is-set');
        var rand = new Date().getTime();
        
        $.ajax({
        	url:'<?php echo $this->createUrl('/mall/addCart',array('companyId'=>$this->companyId,'userId'=>$userId,'type'=>$this->type));?>',
        	data:{productId:productId,promoteType:promoteType,promoteId:promoteId,isSet:isSet,toGroup:toGroup,canCupon:canCupon,rand:rand},
        	success:function(msg){
        		if(msg.status){
        			 t.val(parseInt(t.val())+1);
			        if(parseInt(t.val()) > 0){
			            t.siblings(".minus").show();
			            t.show();
			        }
			        var cartObj = $('.cart-dtl-item[data-orderid="'+promoteType+'_'+isSet+'_'+productId+'_'+promoteId+'_'+toGroup+'_'+canCupon+'"]');
			        if(cartObj.length > 0){
			        	cartObj.find('.foodop-num').html(t.val());
			        }else{
				        var pName = parObj.find('.name').html();
				        var pPrice = parObj.find('.price').html();
				        var cartStr = '';
					    cartStr +='<div class="j-fooditem cart-dtl-item" data-orderid="'+promoteType+'_'+isSet+'_'+productId+'_'+promoteId+'_'+toGroup+'_'+canCupon+'">';
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
							$('#boll'+j).css('display','none');
							j++;
						},
						stepCallback:function(x,y){
						}
					});
					
					bool.start();
					i++;
        		}else{
        			$('#boll'+(i-1)).css('display','none');
        			layer.msg(msg.msg);
        		}
        	},
        	dataType:'json'
        });
    });
     
    $('#container').on('touchstart','.minus',function(){ 
    	var parObj = $(this).parents('.prt-lt');
        var t = parObj.find('input[class*=result]');
        var promoteType = $(this).parents('.section').attr('type');
        var productId = t.attr('product-id');
        var promoteId = t.attr('promote-id');
        var toGroup = t.attr('to-group');
        var isSet = t.attr('is-set');
        var canCupon = t.attr('can-cupon');
        var storeNum = t.attr('store-number');
        var rand = new Date().getTime();
        
        $.ajax({
        	url:'<?php echo $this->createUrl('/mall/deleteCart',array('companyId'=>$this->companyId,'userId'=>$userId,'type'=>$this->type));?>',
        	data:{productId:productId,promoteType:promoteType,promoteId:promoteId,isSet:isSet,toGroup:toGroup,canCupon:canCupon,rand:rand},
        	success:function(msg){
        		if(msg.status){
    			  if(parseInt(t.val())==1){
			          t.siblings(".minus").hide();
			          t.hide();
			          if(parseInt(storeNum)==0){
			          	t.siblings(".add").hide();
			          	t.siblings(".sale-out").show();
			          }
			       }
			       t.val(parseInt(t.val())-1);
			       if(parseInt(t.val()) < 0){ 
			           t.val(0); 
			   	    }
			       	var cartObj = $('.cart-dtl-item[data-orderid="'+promoteType+'_'+isSet+'_'+productId+'_'+promoteId+'_'+toGroup+'_'+canCupon+'"]');
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
        
        var t = $('input[class*=result][is-set="'+isSet+'"][product-id="'+productId+'"][promote-id="'+promoteId+'"][to-group="'+toGroup+'"][can-cupon="'+canCupon+'"]');
        var rand = new Date().getTime();
        
        $.ajax({
        	url:'<?php echo $this->createUrl('/mall/addCart',array('companyId'=>$this->companyId,'userId'=>$userId));?>',
        	data:{productId:productId,promoteType:promotionType,promoteId:promoteId,isSet:isSet,toGroup:toGroup,canCupon:canCupon,rand:rand},
        	success:function(msg){
        		if(msg.status){
        			 t.val(parseInt(t.val())+1);
			        if(parseInt(t.val()) > 0){
			            t.siblings(".minus").show();
			            t.show();
			        }
			        var cartObj = $('.cart-dtl-item[data-orderid="'+promotionType+'_'+isSet+'_'+productId+'_'+promoteId+'_'+toGroup+'_'+canCupon+'"]');
			        if(cartObj.length > 0){
			        	cartObj.find('.foodop-num').html(t.val());
			        }
			        setTotal();
        		}
        	},
        	dataType:'json'
        });
    });
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
       
       var t = $('input[class*=result][is-set="'+isSet+'"][product-id="'+productId+'"][promote-id="'+promoteId+'"][to-group="'+toGroup+'"][can-cupon="'+canCupon+'"]');
       var storeNum = t.attr('store-number');
       var rand = new Date().getTime();
       $.ajax({
	       	url:'<?php echo $this->createUrl('/mall/deleteCart',array('companyId'=>$this->companyId,'userId'=>$userId));?>',
	       	data:{productId:productId,promoteType:promotionType,promoteId:promoteId,isSet:isSet,toGroup:toGroup,canCupon:canCupon,rand:rand},
	       	success:function(msg){
	       		if(msg.status){
	   			  if(parseInt(t.val())==1){
			          t.siblings(".minus").hide();
			          t.hide();
			          if(parseInt(storeNum)==0){
			          	t.siblings(".add").hide();
			          	t.siblings(".sale-out").show();
			          }
			       }
			       t.val(parseInt(t.val())-1);
			       if(parseInt(t.val()) < 0){ 
			           t.val(0); 
			   	    }
			       	var cartObj = $('.cart-dtl-item[data-orderid="'+promotionType+'_'+isSet+'_'+productId+'_'+promoteId+'_'+toGroup+'_'+canCupon+'"]');
			        if(cartObj.length > 0){
				        if(parseInt(t.val()) == 0){
				        	if($('.cart-dtl-item').length == 1){
				        		$('.ft-lt').trigger('click');
				        	}
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
    $('.j-cart-dusbin').on('click',function(){
        $.ajax({
        	url:"<?php echo $this->createUrl('/mall/deleteCart',array('companyId'=>$this->companyId,'userId'=>$userId,'all'=>1));?>",
        	success:function(msg){
        		if(msg){
            		$('input.result').each(function(){
                		$(this).hide();
                		$(this).parent().find('.minus').hide();
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
