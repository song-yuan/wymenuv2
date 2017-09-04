<?php 
/**
* eleMetoken 授权
*elemeGetToken 更新token
*elemeId 查询店铺ID
*elemeUpdateId 饿了么的店铺建立对应关系
*productCategory 添加菜品分类
*getProduct 获取我方菜品信息
*batchCreateItems 把菜品添加到饿了么
*order 获取订单
*confirmOrder 确认订单
*/
class Elm
{
	public static function eleMetoken($code,$dpid){
			$key = ElmConfig::key;
			$secret = ElmConfig::secret;
			$callback_url = Yii::app()->createAbsoluteUrl('/eleme/elemetoken');
			$token_url = ElmConfig::token;
			$header = array(
	            "Authorization: Basic " . base64_encode(urlencode($key) . ":" . urlencode($secret)),
	            "Content-Type: application/x-www-form-urlencoded; charset=utf-8",
	            "Accept-Encoding: gzip");
			$body = array(
	            "grant_type" => "authorization_code",
	            "code" => $code,
	            "redirect_uri" => $callback_url,
	            "client_id" => $key
	        );
			$re = ElUnit::postHttpsHeader($token_url,$header,$body);
			$obj = json_decode($re);
			if(isset($obj->access_token)){
				$refresh_token = $obj->refresh_token;
				$token_type = $obj->token_type;
				$access_token = $obj->access_token;
				$expires_in =time() + $obj->expires_in;
				$se=new Sequence("eleme_token");
				$lid = $se->nextval();
				$creat_at = date("Y-m-d H:i:s");
				$update_at = date("Y-m-d H:i:s");
				$inserData = array(
							'lid'=>	$lid,
							'dpid'=>$dpid,
							'create_at'=>$creat_at,
							'update_at'=>$update_at,
							'token_type'=>$token_type,
							'access_token'=>$access_token,
							'expires_in'=>$expires_in,
							'refresh_token'=>$refresh_token
				);
				$res = Yii::app()->db->createCommand()->insert('nb_eleme_token',$inserData);
				return "授权成功";
			}else{
				return "授权失败";
			}
	}
	public static function elemeGetToken($dpid){
		$sql = "select * from nb_eleme_token where dpid=".$dpid." and delete_flag=0";
		$res = Yii::app()->db->createCommand($sql)->queryRow();
		if($res){
			$nowtime = time();
			if($res['expires_in']>$nowtime){
				$access_token = $res['access_token'];
				return $access_token;
			}else{
				$refresh_token = $res['refresh_token'];
				$key = ElmConfig::key;
				$secret = ElmConfig::secret;
				$token_url = ElmConfig::token;
				$header = array(
		            "Authorization: Basic " . base64_encode(urlencode($key) . ":" . urlencode($secret)),
		            "Content-Type: application/x-www-form-urlencoded; charset=utf-8",
		            "Accept-Encoding: gzip");
				$body = array(
		            "grant_type" => "refresh_token",
		            "refresh_token"=>$refresh_token
		        );
				$re = ElUnit::postHttpsHeader($token_url,$header,$body);
				$obj = json_decode($re);
				if(isset($obj->access_token)){
					$access_token = $obj->access_token;
					$expires_in =time() + $obj->expires_in;
					$refresh_token = $obj->refresh_token;
					$sql1 = "update nb_eleme_token set access_token='$access_token',expires_in=$expires_in,refresh_token='$refresh_token' where dpid=$dpid and delete_flag=0";
					$res1 = Yii::app()->db->createCommand($sql1)->execute();
					return $access_token;
				}
			}
		}else{
			return false;
		}
	}
	public static function elemeId($dpid){
		$access_token = self::elemeGetToken($dpid);
		$app_key = ElmConfig::key;
		$secret = ElmConfig::secret;
		$url = ElmConfig::url;
		$protocol = array(
            "nop" => '1.0.0',
            "id" => ElUnit::create_uuid(),
            "action" => "eleme.user.getUser",
            "token" => $access_token,
            "metas" => array(
                "app_key" => $app_key,
                "timestamp" => time(),
            ),
            "params" => array("key"=>"value"),
        );
        $protocol['signature'] = ElUnit::generate_signature($protocol,$access_token,$secret);
         $result =ElUnit::post($url,$protocol);
         return $result;
	}
	public static function elemeUpdateId($dpid,$shopid){
		$access_token = self::elemeGetToken($dpid);
		if(!$access_token){
			return '{"result": null,"error": {"code":"VALIDATION_FAILED","message": "请先绑定店铺"}}';
		}
		$app_key = ElmConfig::key;
		$secret = ElmConfig::secret;
		$url = ElmConfig::url;
		$protocol = array(
            "nop" => '1.0.0',
            "id" => ElUnit::create_uuid(),
            "action" => "eleme.shop.updateShop",
            "token" => $access_token,
            "metas" => array(
                "app_key" => $app_key,
                "timestamp" => time(),
            ),
            "params" => array('shopId'=>$shopid,
            	'properties'=>array('openId'=>$dpid)
            	),
        );
        $protocol['signature'] = ElUnit::generate_signature($protocol,$access_token,$secret);
        $result =ElUnit::post($url,$protocol);
	    return $result;
	}
	public static function productCategory($dpid,$cpid,$name,$shopid){
		$access_token = self::elemeGetToken($dpid);
		if(!$access_token){
			return '{"result": null,"error": {"code":"VALIDATION_FAILED","message": "请先绑定店铺"}}';
		}
		$app_key = ElmConfig::key;
		$secret = ElmConfig::secret;
		$url = ElmConfig::url;
		$protocol = array(
            "nop" => '1.0.0',
            "id" => ElUnit::create_uuid(),
            "action" => "eleme.product.category.createCategory",
            "token" => $access_token,
            "metas" => array(
                "app_key" => $app_key,
                "timestamp" => time(),
            ),
            "params" => array(
            	'shopId'=>$shopid,
            	'name'=>"$name"
            	)
        );
        $protocol['signature'] = ElUnit::generate_signature($protocol,$access_token,$secret);
        $result =ElUnit::post($url,$protocol);
        return $result;
	}
	public static function getShopCategories($dpid,$shopid){
		$access_token = self::elemeGetToken($dpid);
		if(!$access_token){
			return '{"result": null,"error": {"code":"VALIDATION_FAILED","message": "请先绑定店铺"}}';
		}
		$app_key = ElmConfig::key;
		$secret = ElmConfig::secret;
		$url = ElmConfig::url;
		$protocol = array(
            "nop" => '1.0.0',
            "id" => ElUnit::create_uuid(),
            "action" => "eleme.product.category.getShopCategories",
            "token" => $access_token,
            "metas" => array(
                "app_key" => $app_key,
                "timestamp" => time(),
            ),
            "params" => array(
            	'shopId'=>$shopid
            	),
        );
        $protocol['signature'] = ElUnit::generate_signature($protocol,$access_token,$secret);
        $result =ElUnit::post($url,$protocol);
        return $result;
	}
	public static function getErpDpid($shopId){
		$sql = "select * from nb_eleme_dpdy where shopId=".$shopId." and delete_flag=0";
		$res = Yii::app()->db->createCommand($sql)->queryRow();
		return $res;
	}
	public static function selectProduct($product_id){
		$sql = "select lid,category_id,product_name,phs_code,original_price from nb_product where lid=$product_id and delete_flag=0";
		$res = Yii::app()->db->createCommand($sql)->queryRow();
		return $res;
	}
	public static function selectCategory($category_id){
		$sql = "select pid from nb_product_category where lid=$category_id and delete_flag=0";
		$res = Yii::app()->db->createCommand($sql)->queryRow();
		return $res;
	}
	public static function getProduct($fen_lei_id){
		$sql = "select elemeID from nb_eleme_cpdy where fen_lei_id=$fen_lei_id and delete_flag=0";
		$res = Yii::app()->db->createCommand($sql)->queryRow();
		return $res;
	}
	public static function batchCreateItems($dpid,$id,$product_id,$name,$phs_code,$original_price){
		$access_token = self::elemeGetToken($dpid);
		if(!$access_token){
			return '{"result": null,"error": {"code":"VALIDATION_FAILED","message": "请先绑定店铺"}}';
		}
		$app_key = ElmConfig::key;
		$secret = ElmConfig::secret;
		$url = ElmConfig::url;
		$protocol = array(
            "nop" => '1.0.0',
            "id" => ElUnit::create_uuid(),
            "action" => "eleme.product.item.createItem",
            "token" => $access_token,
            "metas" => array(
                "app_key" => $app_key,
                "timestamp" => time(),
            ),
            "params" => array(
            	'categoryId'=>$id,
            	'properties'=>array(
            		'name'=>$name,
            		'specs'=>array(array(
            			'specId'=>0,
            			'name'=>"",
            			'price'=>$original_price,
            			'stock'=>9000,
            			'maxStock'=>10000,
            			'packingFee'=>0,
            			'onShelf'=>1,
            			'extendCode'=>$phs_code,
            			'barCode'=>$phs_code,
            			'weight'=>0
            			))
            		),
            	'backCategoryId'=>$product_id
            	),
        );
        $protocol['signature'] = ElUnit::generate_signature($protocol,$access_token,$secret);
        $result =ElUnit::post($url,$protocol);
        return $result;
	}
	public static function order($message,$dpid){
		$me = json_decode($message);
		$wmSetting = MtUnit::getWmSetting($dpid);
		if(!empty($wmSetting)&&$wmSetting['is_receive']==1){
			$orderId = $me->id;
			Yii::app()->cache->set($orderId,$message);
			$order = self::confirmOrder($dpid,$orderId);
			$obj = json_decode($order);
			if(empty($obj->error)){
				return true;
			}else{
				return false;
			}
		}else{
			return true;
		}
	}
	public static function confirmOrder($dpid,$orderId){
		$access_token = self::elemeGetToken($dpid);
		if(!$access_token){
			return '{"result": null,"error": {"code":"VALIDATION_FAILED","message": "请先绑定店铺"}}';
		}
		$app_key = ElmConfig::key;
		$secret = ElmConfig::secret;
		$url = ElmConfig::url;
		$protocol = array(
            "nop" => '1.0.0',
            "id" => ElUnit::create_uuid(),
            "action" => "eleme.order.confirmOrderLite",
            "token" => $access_token,
            "metas" => array(
                "app_key" => $app_key,
                "timestamp" => time(),
            ),
            "params" => array(
            	'orderId'=>$orderId
            	),
        );
        $protocol['signature'] = ElUnit::generate_signature($protocol,$access_token,$secret);
        $result = ElUnit::post($url,$protocol);
        return $result;
	}
	public static function orderStatus($message,$dpid){
		$me = json_decode($message);
		$accountNo = $me->orderId;
		$cache = Yii::app()->cache->get($accountNo);
		if($cache!=false){
			$order = json_decode($cache);
			$res = self::dealOrder($order,$dpid,4);
			Yii::app()->cache->delete($accountNo);
			return $res;
		}else{
			$access_token = self::elemeGetToken($dpid);
			if(!$access_token){
				return '{"result": null,"error": {"code":"VALIDATION_FAILED","message": "请先绑定店铺"}}';
			}
			$app_key = ElmConfig::key;
			$secret = ElmConfig::secret;
			$url = ElmConfig::url;
			$protocol = array(
					"nop" => '1.0.0',
					"id" => ElUnit::create_uuid(),
					"action" => "eleme.order.getOrder",
					"token" => $access_token,
					"metas" => array(
							"app_key" => $app_key,
							"timestamp" => time(),
					),
					"params" => array(
							'orderId'=>$accountNo
					),
			);
			$protocol['signature'] = ElUnit::generate_signature($protocol,$access_token,$secret);
			$result = ElUnit::post($url,$protocol);
			$orderObj = json_decode($result);
			$order = $orderObj->result;
			if($order){
				$res = self::dealOrder($order,$dpid,4);
				Yii::app()->cache->delete($accountNo);
				return $res;
			}else{
				self::orderStatus($message,$dpid);
			}
		}
	}
	public static function productUpdate($lid){
		$sql = "select * from nb_eleme_cpdy where fen_lei_id=$lid and delete_flag=0";
		$res = Yii::app()->db->createCommand($sql)->queryRow();
		return $res;
	}
	public static function updateItem($itemid,$dpid,$categoryid,$name,$original_price,$phs_code,$specId){
		$access_token = self::elemeGetToken($dpid);
		if(!$access_token){
			return '{"result": null,"error": {"code":"VALIDATION_FAILED","message": "请先绑定店铺"}}';
		}
		$app_key = ElmConfig::key;
		$secret = ElmConfig::secret;
		$url = ElmConfig::url;
		$protocol = array(
            "nop" => '1.0.0',
            "id" => ElUnit::create_uuid(),
            "action" => "eleme.product.item.updateItem",
            "token" => $access_token,
            "metas" => array(
                "app_key" => $app_key,
                "timestamp" => time(),
            ),
            "params" => array(
            	'itemId'=>$itemid,
            	'categoryId'=>$categoryid,
            	'properties'=>array(
            		'name'=>$name,
            		'specs'=>array(array(
            			'specId'=>$specId,
            			'name'=>"",
            			'price'=>$original_price,
            			'stock'=>9000,
            			'maxStock'=>10000,
            			'packingFee'=>0,
            			'onShelf'=>1,
            			'extendCode'=>$phs_code,
            			'barCode'=>$phs_code,
            			'weight'=>0
            			)),
            	)),
        );
        $protocol['signature'] = ElUnit::generate_signature($protocol,$access_token,$secret);
        $result =ElUnit::post($url,$protocol);
        return $result;
	}
	public static function updateItem1($itemid,$dpid,$categoryid,$name,$original_price,$phs_code,$specId,$description,$attributes1){
		$access_token = self::elemeGetToken($dpid);
		if(!$access_token){
			return '{"result": null,"error": {"code":"VALIDATION_FAILED","message": "请先绑定店铺"}}';
		}
		$app_key = ElmConfig::key;
		$secret = ElmConfig::secret;
		$url = ElmConfig::url;
			$protocol = array(
            "nop" => '1.0.0',
            "id" => ElUnit::create_uuid(),
            "action" => "eleme.product.item.updateItem",
            "token" => $access_token,
            "metas" => array(
                "app_key" => $app_key,
                "timestamp" => time(),
            ),
            "params" => array(
            	'itemId'=>$itemid,
            	'categoryId'=>$categoryid,
            	'properties'=>array(
            		'name'=>$name,
            		'description'=>$description,
            		'specs'=>array(array(
            			'specId'=>$specId,
            			'name'=>"",
            			'price'=>$original_price,
            			'stock'=>9000,
            			'maxStock'=>10000,
            			'packingFee'=>0,
            			'onShelf'=>1,
            			'extendCode'=>$phs_code,
            			'barCode'=>$phs_code,
            			'weight'=>0
            			)),
            		'attributes'=>$attributes1
            	)),
        );
        $protocol['signature'] = ElUnit::generate_signature($protocol,$access_token,$secret);

        $result =ElUnit::post($url,$protocol);
        return $result;
	}
	public static function updateItem2($itemid,$dpid,$categoryid,$name,$original_price,$phs_code,$specId,$description){
		$access_token = self::elemeGetToken($dpid);
		if(!$access_token){
			return '{"result": null,"error": {"code":"VALIDATION_FAILED","message": "请先绑定店铺"}}';
		}
		$app_key = ElmConfig::key;
		$secret = ElmConfig::secret;
		$url = ElmConfig::url;
			$protocol = array(
            "nop" => '1.0.0',
            "id" => ElUnit::create_uuid(),
            "action" => "eleme.product.item.updateItem",
            "token" => $access_token,
            "metas" => array(
                "app_key" => $app_key,
                "timestamp" => time(),
            ),
            "params" => array(
            	'itemId'=>$itemid,
            	'categoryId'=>$categoryid,
            	'properties'=>array(
            		'name'=>$name,
            		'description'=>$description,
            		'specs'=>array(array(
            			'specId'=>$specId,
            			'name'=>"",
            			'price'=>$original_price,
            			'stock'=>9000,
            			'maxStock'=>10000,
            			'packingFee'=>0,
            			'onShelf'=>1,
            			'extendCode'=>$phs_code,
            			'barCode'=>$phs_code,
            			'weight'=>0
            			))
            	)),
        );
        $protocol['signature'] = ElUnit::generate_signature($protocol,$access_token,$secret);

        $result =ElUnit::post($url,$protocol);
        return $result;
	}
	public static function orderCancel($message){
		
	}
	public static function refundOrder($me){
		
	}
	public static function Agree($orderId,$dpid){
		$access_token = self::elemeGetToken($dpid);
		if(!$access_token){
			return '{"result": null,"error": {"code":"VALIDATION_FAILED","message": "请先绑定店铺"}}';
		}
		$app_key = ElmConfig::key;
		$secret = ElmConfig::secret;
		$url = ElmConfig::url;
		$protocol = array(
            "nop" => '1.0.0',
            "id" => ElUnit::create_uuid(),
            "action" => "eleme.order.agreeRefundLite",
            "token" => $access_token,
            "metas" => array(
                "app_key" => $app_key,
                "timestamp" => time(),
            ),
            "params" => array(
            	'orderId'=>$orderId
            	)
        );
        $protocol['signature'] = ElUnit::generate_signature($protocol,$access_token,$secret);
        $result =ElUnit::post($url,$protocol);
	}
	public static function getItems($dpid,$categoryId){
		//查询菜品分类下的菜品
		$access_token = self::elemeGetToken($dpid);
		if(!$access_token){
			return '{"result": null,"error": {"code":"VALIDATION_FAILED","message": "请先绑定店铺"}}';
		}
		$app_key = ElmConfig::key;
		$secret = ElmConfig::secret;
		$url = ElmConfig::url;
		$protocol = array(
				"nop" => '1.0.0',
				"id" => ElUnit::create_uuid(),
				"action" => "eleme.product.item.getItemsByCategoryId",
				"token" => $access_token,
				"metas" => array(
						"app_key" => $app_key,
						"timestamp" => time(),
				),
				"params" => array(
						'categoryId'=>$categoryId
				),
		);
		$protocol['signature'] = ElUnit::generate_signature($protocol,$access_token,$secret);
		$result =ElUnit::post($url,$protocol);
		return $result;
	}
	public static function getItem($dpid,$id){
		//查询菜品
		$access_token = self::elemeGetToken($dpid);
		if(!$access_token){
			return '{"result": null,"error": {"code":"VALIDATION_FAILED","message": "请先绑定店铺"}}';
		}
		$app_key = ElmConfig::key;
		$secret = ElmConfig::secret;
		$url = ElmConfig::url;
		$protocol = array(
				"nop" => '1.0.0',
				"id" => ElUnit::create_uuid(),
				"action" => "eleme.product.item.getItem",
				"token" => $access_token,
				"metas" => array(
						"app_key" => $app_key,
						"timestamp" => time(),
				),
				"params" => array(
						'itemId'=>$id
				),
		);
		$protocol['signature'] = ElUnit::generate_signature($protocol,$access_token,$secret);
		$result =ElUnit::post($url,$protocol);
		return $result;
	}
	public static function dealOrder($order,$dpid,$orderStatus){
		$me = $order;
		$orderId = $me->id;
		$createdAt = $me->createdAt;
		$price = $me->totalPrice;
		$originalPrice = $me->originalPrice;
		$income = $me->income;//店铺实收
		$daySn = $me->daySn;
		$groups = $me->groups;
		$deliverFee = $me->deliverFee;// 配送费
		$serviceFee = $me->serviceFee;//饿了么服务费
		$vipDeliveryFeeDiscount = $me->vipDeliveryFeeDiscount;// 会员配送费
		$orderActivities = $me->orderActivities;// 订单活动
		if($me->onlinePaid){
			$payType = 2;
			$orderPayPaytype = 15;
		}else{
			$payType = 1;
			$orderPayPaytype = 0;
		}
		$orderArr = array();
		$orderArr['order_info'] = array('creat_at'=>$createdAt,'account_no'=>$orderId,'classes'=>0,'username'=>'','site_id'=>0,'is_temp'=>1,'number'=>1,'order_status'=>$orderStatus,'order_type'=>8,'should_total'=>$income,'reality_total'=>$originalPrice,'takeout_typeid'=>0,'callno'=>$daySn,'paytype'=>$payType,'remark'=>$me->description);
		$orderArr['order_platform'] = array('original_total'=>$originalPrice,'logistics_total'=>$deliverFee,'platform_total'=>$serviceFee,'pay_total'=>$price,'receive_total'=>$income);
		$orderArr['order_product'] = array();
		foreach ($groups as $group){
			$groupType = $group->type;
			$items = $group->items;
			if($groupType=='extra'){
				foreach ($items as $item){
					$amount = $item->quantity;
					$itemprice = $item->price;
					$foodName = $item->name;
					$orderProduct = array('is_set'=>0,'set_id'=>0,'product_id'=>0,'product_name'=>'餐盒费','original_price'=>$itemprice,'price'=>$itemprice,'amount'=>$amount,'zhiamount'=>$amount,'product_type'=>2,'product_taste'=>array(),'product_promotion'=>array());
					array_push($orderArr['order_product'], $orderProduct);
				}
			}else{
				foreach ($items as $item){
					$elemeId = $item->id;
					$amount = $item->quantity;
					$itemprice = $item->price;
					$foodName = $item->name;
					$newSpecs = $item->newSpecs;
					$attributes = $item->attributes;
					$extendCode = $item->extendCode;
					$tasteArr = array();
					if(!empty($newSpecs)){
						foreach ($newSpecs as $newSpec){
							if(strpos($foodName,$newSpec->value)===false){
								array_push($tasteArr, array("taste_id"=>"0","is_order"=>"0","taste_name"=>$newSpec->value));
							}
						}
					}
					if(!empty($attributes)){
						foreach ($attributes as $attribute){
							array_push($tasteArr, array("taste_id"=>"0","is_order"=>"0","taste_name"=>$attribute->value));
						}
					}
					
					$sql = 'select 0 as is_set,lid,product_name as name,phs_code from nb_product where dpid='.$dpid.' and phs_code="'.$extendCode.'" and delete_flag=0 union select 1 as is_set,lid,set_name as name,pshs_code as phs_code  from nb_product_set where dpid='.$dpid.' and pshs_code="'.$extendCode.'" and delete_flag=0';
					$res = Yii::app()->db->createCommand($sql)->queryRow();
					if(!$res){
						$orderProduct = array('is_set'=>0,'set_id'=>0,'product_id'=>0,'product_name'=>$foodName.'(未对应菜品)','original_price'=>$itemprice,'price'=>$itemprice,'amount'=>$amount,'zhiamount'=>$amount,'product_taste'=>array(),'product_promotion'=>array());
						array_push($orderArr['order_product'], $orderProduct);
					}else{
						if( $res['is_set']==0){
							$orderProduct = array('is_set'=>$res['is_set'],'set_id'=>0,'product_id'=>$res['lid'],'product_name'=>$res['name'],'original_price'=>$itemprice,'price'=>$itemprice,'amount'=>$amount,'zhiamount'=>$amount,'product_taste'=>$tasteArr,'product_promotion'=>array());
							array_push($orderArr['order_product'], $orderProduct);
						}else{
							$sql = 'select sum(t.number*t1.original_price) from nb_product_set_detail t left join nb_product t1 on t.product_id=t1.lid and t.dpid=t1.dpid where t.set_id='.$res['lid'].' and t.dpid='.$dpid.' and t.is_select=1 and t.delete_flag=0 and t1.delete_flag=0';

							$totalProductPrice = Yii::app()->db->createCommand($sql)->queryScalar();
							$sql = 'select t.*,t1.product_name,t1.original_price from nb_product_set_detail t left join nb_product t1 on t.product_id=t1.lid and t.dpid=t1.dpid where t.set_id='.$res['lid'].' and t.dpid='.$dpid.' and t.is_select=1 and t.delete_flag=0 and t1.delete_flag=0';
							
							$productDetails = Yii::app()->db->createCommand($sql)->queryAll();

							$hasPrice = 0;
							foreach ($productDetails as $i=>$detail){
								if($totalProductPrice > 0){
									$eachPrice = $detail['original_price']*$detail['number']/$totalProductPrice*$itemprice;
									
								}else{
									$eachPrice = 0;
								}
								$hasPrice += $eachPrice;
								if($i+1 == count($detail)){
									$leavePrice = $hasPrice - $price;
									if($leavePrice > 0){
										$itemPrice =  $eachPrice - $leavePrice;
									}else{
										$itemPrice =  $eachPrice - $leavePrice;
									}
								}else{
									$itemPrice = $eachPrice;
								}
								$itemPrice = number_format($itemPrice,4);

								$orderProduct = array('is_set'=>$res['is_set'],'set_id'=>$res['lid'],'product_id'=>$detail['product_id'],'product_name'=>$detail['product_name'],'original_price'=>$itemPrice,'price'=>$itemPrice,'amount'=>$amount*$detail['number'],'zhiamount'=>$amount,'product_taste'=>array(),'product_promotion'=>array());
								array_push($orderArr['order_product'], $orderProduct);
							}
						}
					}
				}
			}
		}
		
		if($deliverFee!=$vipDeliveryFeeDiscount){
			$orderProduct = array('is_set'=>0,'set_id'=>0,'product_id'=>0,'product_name'=>'配送费','original_price'=>$deliverFee,'price'=>$deliverFee-$vipDeliveryFeeDiscount,'amount'=>1,'zhiamount'=>1,'product_type'=>3,'product_taste'=>array(),'product_promotion'=>array());
			array_push($orderArr['order_product'], $orderProduct);
		}
		if(empty($orderArr['order_product'])){
			return true;
		}
		$me->deliveryPoiAddress = Helper::dealString($me->deliveryPoiAddress);
		$orderArr['order_address'] = array(array('consignee'=>$me->consignee,'street'=>$me->deliveryPoiAddress,'mobile'=>$me->phoneList[0],'tel'=>$me->phoneList[0]));
		$orderArr['order_pay'] = array(array('pay_amount'=>$income,'paytype'=>$orderPayPaytype,'payment_method_id'=>0,'paytype_id'=>0,'remark'=>''));
			
		if(!empty($orderActivities)){
			$orderArr['order_discount'] = array();
			foreach ($orderActivities as $orderActivitive){
				array_push($orderArr['order_discount'],array('discount_title'=>$orderActivitive->name,'discount_type'=>'5','discount_id'=>'0','discount_money'=>abs($orderActivitive->amount)));
			}
		}
			
		$orderStr = json_encode($orderArr);
			
		$data = array('dpid'=>$dpid,'data'=>$orderStr);
		$result = DataSyncOperation::operateOrder($data);
		$reobj = json_decode($result);
		if($reobj->status){
			return true;
		}else{
			return false;
		}
	}
}
?>