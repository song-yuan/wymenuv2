<?php 
/**
* eleMetoken 授权
elemeGetToken 更新token
elemeId 查询店铺ID
elemeUpdateId 饿了么的店铺建立对应关系
productCategory 添加菜品分类
getProduct 获取我方菜品信息
batchCreateItems 把菜品添加到饿了么
order 获取订单
confirmOrder 确认订单
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
		$sql = "select * from nb_eleme_token where dpid=$dpid and delete_flag=0";
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
	public static function elemeUpdateId($dpid){
		$access_token = self::elemeGetToken($dpid);
		if(!$access_token){
			return '{"result": null,"error": {"code":"VALIDATION_FAILED","message": "请先绑定店铺"}}';
		}
		$resultid = self::ElemeId($dpid);
		$obj = json_decode($resultid);
		$auth = $obj->result->authorizedShops;
		$shopid = $auth[0]->id;
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
        $obj = json_decode($result);
        if(!empty($obj->result)){
        	$se=new Sequence("eleme_dpdy");
			$lid = $se->nextval();
			$creat_at = date("Y-m-d H:i:s");
			$update_at = date("Y-m-d H:i:s");
			$shopid = $obj->result->id;
			$inserData = array(
						'lid'=>	$lid,
						'dpid'=> $dpid,
						'create_at'=>$creat_at,
						'update_at'=>$update_at,
						'shopId'=>$shopid
				);
			$res = Yii::app()->db->createCommand()->insert('nb_eleme_dpdy',$inserData);
        }
	    return "店铺对应成功";
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
	public static function getShopCategories($dpid){
		$access_token = self::elemeGetToken($dpid);
		if(!$access_token){
			return '{"result": null,"error": {"code":"VALIDATION_FAILED","message": "请先绑定店铺"}}';
		}
		$resultid = self::ElemeId($dpid);
		$obj = json_decode($resultid);
		$auth = $obj->result->authorizedShops;
		$shopid = $auth[0]->id;
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
	public static function order($me){
		$orderId = $me->orderId;
		$dpid = $me->openId;
		$createdAt = $me->createdAt;
		$wmSetting = MtUnit::getWmSetting($dpid);
		if(!empty($wmSetting)&&$wmSetting['is_receive']==1){
			$orderArr = array();
			$orderArr['order_info'] = array('creat_at'=>$createdAt,'account_no'=>$me->orderId,'classes'=>0,'username'=>'','site_id'=>0,'is_temp'=>1,'number'=>0,'order_status'=>2,'order_type'=>8,'should_total'=>$me->totalPrice,'reality_total'=>$me->originalPrice,'takeout_typeid'=>0,'callno'=>'');
			$orderArr['order_product'] = array();
			$sql = 'select 0 as is_set,lid,product_name as name from nb_product where dpid='.$me->openId.' and phs_code="'.$me->groups[0]->items[0]->barCode.'" and delete_flag=0 union select 1 as is_set,lid,set_name as name from nb_product_set where dpid='.$me->openId.' and pshs_code="'.$me->groups[0]->items[0]->barCode.'" and delete_flag=0 ';
			$res = Yii::app()->db->createCommand($sql)->queryRow();
			$price = $me->totalPrice;
			$amount = $me->groups[0]->items[0]->quantity;
			if( $res['is_set']==0){
				$orderProduct = array('is_set'=>$res['is_set'],'set_id'=>0,'product_id'=>$res['lid'],'product_name'=>$res['name'],'original_price'=>$me->originalPrice,'price'=>$price,'amount'=>$amount,'zhiamount'=>$amount,'product_taste'=>array(),'product_promotion'=>array());
				array_push($orderArr['order_product'], $orderProduct);
			}else{
				$sql = 'select sum(t.number*t1.original_price) from nb_product_set_detail t left join nb_product t1 on t.product_id=t1.lid and t.dpid=t1.dpid where t.set_id='.$res['lid'].' and t.dpid='.$ePoiId.' and t.is_select=1 and t.delete_flag=0 and t1.delete_flag=0';
				$totalProductPrice = Yii::app()->db->createCommand($sql)->queryColumn();
				$sql = 'select t.*,t1.product_name,t1.original_price from nb_product_set_detail t left join nb_product t1 on t.product_id=t1.lid and t.dpid=t1.dpid where t.set_id='.$res['lid'].' and t.dpid='.$ePoiId.' and t.is_select=1 and t.delete_flag=0 and t1.delete_flag=0';
				$productDetails = Yii::app()->db->createCommand($sql)->queryAll();
				$hasPrice = 0;
				foreach ($productDetails as $i=>$detail){
					$eachPrice = $detail['original_price']*$detail['number']/$totalProductPrice*$price;
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
			if(empty($orderArr['order_product'])){
				return '{"message":"ok"}';
			}
			$orderArr['order_address'] = array(array('consignee'=>$me->consignee,'street'=>$me->deliveryPoiAddress,'mobile'=>$me->phoneList[0],'tel'=>$me->phoneList[0]));
			$orderArr['order_pay'] = array(array('pay_amount'=>$me->totalPrice,'paytype'=>15,'payment_method_id'=>0,'paytype_id'=>0,'remark'=>''));
			
			$orderStr = json_encode($orderArr);
			
			$data = array('dpid'=>$me->openId,'data'=>$orderStr);
			$result = DataSyncOperation::operateOrder($data);
			$order = self::confirmOrder($dpid,$orderId);
			return $order;
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
        $result =ElUnit::post($url,$protocol);
	}
	public static function orderStatus($me){
		$sql = "update nb_order set order_status=4 where account_no=".$me->orderId." and order_type=8";
		$res = Yii::app()->db->createCommand($sql)->execute();
	}
	public static function productUpdate($lid){
		$sql = "select * from nb_eleme_cpdy where fen_lei_id=$lid and delete_flag=0";
		$res = Yii::app()->db->createCommand($sql)->queryRow();
		return $res;
	}
	public static function updateItem($itemid,$dpid,$categoryid,$name,$original_price,$phs_code){
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
            	)),
        );
        $protocol['signature'] = ElUnit::generate_signature($protocol,$access_token,$secret);
        $result =ElUnit::post($url,$protocol);
        return $result;
	}
	public static function deleteItem($itemid,$dpid){
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
            "action" => "eleme.product.item.removeItem",
            "token" => $access_token,
            "metas" => array(
                "app_key" => $app_key,
                "timestamp" => time(),
            ),
            "params" => array(
            	'itemId'=>$itemid
            	)
        );
        $protocol['signature'] = ElUnit::generate_signature($protocol,$access_token,$secret);
        $result =ElUnit::post($url,$protocol);
        return $result;
	}
	public static function updateCategory($elemeID,$dpid,$name){
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
            "action" => "eleme.product.category.updateCategory",
            "token" => $access_token,
            "metas" => array(
                "app_key" => $app_key,
                "timestamp" => time(),
            ),
            "params" => array(
            	'categoryId'=>$elemeID,
            	'name'=>$name
            	)
        );
        $protocol['signature'] = ElUnit::generate_signature($protocol,$access_token,$secret);
        $result =ElUnit::post($url,$protocol);
        return $result;
	}
	public static function removeCategory($elemeID,$dpid){
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
            "action" => "eleme.product.category.removeCategory",
            "token" => $access_token,
            "metas" => array(
                "app_key" => $app_key,
                "timestamp" => time(),
            ),
            "params" => array(
            	'categoryId'=>$elemeID
            	)
        );
        $protocol['signature'] = ElUnit::generate_signature($protocol,$access_token,$secret);
        $result =ElUnit::post($url,$protocol);
        return $result;
	}
	public static function orderCancel($me){
		$se=new Sequence("waimai_status");
		$lid = $se->nextval();
		$creat_at = date("Y-m-d H:i:s");
		$update_at = date("Y-m-d H:i:s");
		$shopId = $me->shopId;
		$sql = "select dpid from nb_eleme_dpdy where shopId=$shopId and delete_flag=0";
		$res = Yii::app()->db->createCommand($sql)->queryRow();
		$dpid = $res['dpid'];
		$orderId = $me->orderId;
		$status = $me->refundStatus;
		$reason = $me->reason;
		$inserData = array(
					'lid'=>	$lid,
					'dpid'=>$dpid,
					'create_at'=>$creat_at,
					'update_at'=>$update_at,
					'orderId'=>$orderId,
					'status'=>"$status",
					'reason'=>"$reason",
					'type'=>1,
					'operation'=>'Q'
			);
		$res = Yii::app()->db->createCommand()->insert('nb_waimai_status',$inserData);
	}
	public static function refundOrder($me){
		$se=new Sequence("waimai_status");
		$lid = $se->nextval();
		$creat_at = date("Y-m-d H:i:s");
		$update_at = date("Y-m-d H:i:s");
		$shopId = $me->shopId;
		$sql = "select dpid from nb_eleme_dpdy where shopId=$shopId and delete_flag=0";
		$res = Yii::app()->db->createCommand($sql)->queryRow();
		$dpid = $res['dpid'];
		$orderId = $me->orderId;
		$status = $me->refundStatus;
		$reason = $me->reason;
		$inserData = array(
					'lid'=>	$lid,
					'dpid'=>$dpid,
					'create_at'=>$creat_at,
					'update_at'=>$update_at,
					'orderId'=>$orderId,
					'status'=>$status,
					'reason'=>$reason,
					'type'=>1,
					'operation'=>'T'
			);
		$res = Yii::app()->db->createCommand()->insert('nb_waimai_status',$inserData);
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
}
?>