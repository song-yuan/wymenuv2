<style>
	.card{padding:10px;height:150px;}
	.card .inner{width:100%;height:100%;position:relative;}
	.card .inner .inner-top{height:100px;width:100%;}
	.card .inner .inner-bottom{height:50px;width:100%;position:absolute;left:0;bottom:0;color:white;background-color:rgba(255,255,255,0.25);}
	.inner-top .left{width:60px;height:60px;margin:20px;float:left;}
	.inner-top .left img {border-radius:100%;width:60px;height:60px;}
	.inner-top .right{font-size:30px;text-align:left;height:70px;width:-moz-calc(100% - 100px);width:-webkit-calc(100% - 100px);width:calc(100% - 100px);margin-top:30px;float:left;color:white;}
	.inner-bottom .info{float:left;line-height:50px;}
	.l{width:-moz-calc(45% - 5px);width:-webkit-calc(45% - 5px);width:calc(45% - 5px);height:50px;text-align:left;padding-left:5px;}
	.r{width:-moz-calc(55% - 5px);width:-webkit-calc(55% - 5px);width:calc(55% - 5px);height:50px;text-align:right;padding-right:5px;}
	.clear{clear:both;}
</style>
<?php 
	$this->setPageTitle('卡券列表');
	$str = '';
	$accessObj = new AccessToken($this->companyId);
	$ticket = $accessObj->jsCardTicket();
	
	$wxCards = GetWxCard::get($this->companyId);
	if(!empty($wxCards)){
		$time = time();
		foreach($wxCards as $key=>$wxCard){
			$str = '';
			$signature = new Signature();
			$signature->add_data($ticket);
			$signature->add_data($wxCard['card_id']);
			$signature->add_data($time);
			$signature = $signature->get_signature();
			$str .="{\"card_id\":\"".$wxCard['card_id']."\",\"card_ext\":'{\"code\":\"\",\"openid\":\"\",\"timestamp\":\"".$time."\",\"signature\":\"".$signature."\",\"outer_id\":1}'},";
			$cardList = rtrim($str, ",");
			$cardListStr = '['.$cardList.']';
			?>
			<div class="card" id="batchAddCard<?php echo $key;?>">
				<div class="inner" style="background-color:<?php echo $wxCard['color'];?>">
					<div class="inner-top"><div class="left"><img src="<?php echo $wxCard['logo'];?>" /></div><div class="right"><?php echo $wxCard['title'];?></div><div class="clear"></div></div>
					<div class="inner-bottom"><div class="info l"><?php echo $wxCard['brand_name'];?></div><div class="info r"><?php if($wxCard['date_info_type']==1){ echo '有效期至:'.date('Y-m-d',$wxCard['end_timestamp']);}else{ echo '有效期至:'. date('Y-m-d',time() + ($wxCard['fixed_begin_term']+$wxCard['fixed_begin_term'])*24*3600);}?></div></div>
				</div>
			</div>
			<script>
			var readyFunc<?php echo $key;?> = function onBridgeReady() {
				document.querySelector('#batchAddCard<?php echo $key;?>').addEventListener('click',function(e) {
					WeixinJSBridge.invoke('batchAddCard', {
					"card_list":<?php echo $cardListStr;?>
					},
					function(res) {});
				}); 
			}
			if (typeof WeixinJSBridge === "undefined") {
				document.addEventListener('WeixinJSBridgeReady', readyFunc<?php echo $key;?>, false);
			} else {
				readyFunc<?php echo $key;?>();
			 }
			</script>
		<?php }
		}?>
		<?php if(!empty($coupons)):?>
		<?php foreach($coupons as $coupon):?>
		<div class="card">
			<a href="<?php echo $this->createUrl('mall/cupon',array('companyId'=>$this->companyId,'activeId'=>$coupon['lid']));?>">
			<div class="inner" style="background-color:rgb(221, 101, 73)">
				<div class="inner-top"><div class="left"><img src="<?php echo $coupon['main_picture'];?>" /></div><div class="right"><?php echo $coupon['activity_title'];?></div><div class="clear"></div></div>
				<div class="inner-bottom"><div class="info l"></div><div class="info r"><?php echo '有效期至:'. date('Y-m-d',strtotime($coupon['end_time']));?></div></div>
			</div>
			</a>
		</div>
		<?php endforeach;?>
		<?php endif;?>
	
