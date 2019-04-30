<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('充值中心');
	
	// 支付渠道 1官方 2收钱吧（暂时不能使用） 3美团
	$compaychannel = WxCompany::getpaychannel($this->companyId);
	$payChannel = $compaychannel?$compaychannel['pay_channel']:0;
?>
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/wechat_css/weui.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/recharge.css?_t=20190430">
<div class="section">
<?php if($this->brandUser['user_name']&&$this->brandUser['mobile_num']):?>
	<div class="title">充值金额</div>
	<?php if($payChannel==1):?>
		<?php foreach($recharges as $recharge):?>
		<div class="item"  onclick="jsApiCall('<?php echo $recharge['lid'];?>','<?php echo $recharge['dpid'];?>','<?php echo $recharge['recharge_money'];?>')">
			<div class="top"><?php if($recharge['recharge_money']-(int)$recharge['recharge_money']==0){echo (int)$recharge['recharge_money'];}else{ echo $recharge['recharge_money'];}?>元</div>
			<div class="down">
			<?php if($recharge['recharge_cashback']!=0):?>赠送<?php echo $recharge['recharge_cashback'];?>元<br><?php endif;?>
			<?php if($recharge['recharge_pointback']!=0):?>赠送<?php echo $recharge['recharge_pointback'];?>积分<br><?php endif;?>
			<?php if($recharge['recharge_cashcard']!=0):?>赠送现金券<?php endif;?>
			</div>
		</div>
		<?php endforeach;?>
		<div class="clear"></div>
		<script type="text/javascript">
			//调用微信JS api 支付
			function jsApiCall(rlid,rdpid,remoney)
			{
				var userId = '<?php echo $userId?>';
				$.ajax({
					url:'<?php echo $this->createUrl('/mall/getJsapiparams',array('companyId'=>$this->companyId));?>',
					data:{rlid:rlid,rdpid:rdpid,remoney:remoney,userId:userId},
					success:function(parameters){
						if(parameters==''){
							layer.msg('支付异常,无法支付!');
							return;
						}
						WeixinJSBridge.invoke(
							'getBrandWCPayRequest',
							parameters,
							function(res){
								 if(res.err_msg == "get_brand_wcpay_request:ok" ) {
								 	// 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回    ok，但并不保证它绝对可靠。 
								 	layer.msg('支付成功!');
								 	<?php if($backUrl):?>
								 	location.href = "<?php echo $backUrl;?>";
								 	<?php else:?>
								 	history.go(-1);
								 	<?php endif;?>
								 }else if(res.err_msg == 'get_brand_wcpay_request:fail'){
								 	//支付失败或取消支付
								 	layer.msg('支付失败!');
								 }else{
									 layer.msg('支付取消!'); 
								 }     
							}
						);
					},
					dataType:'json'
				});
			}
		</script>
	<?php elseif($payChannel==3):?>
		<?php 
			foreach($recharges as $recharge):
				$rlid = $recharge['lid'];
				$rdpid = $recharge['dpid'];
				$se = new Sequence("order_subno");
				$orderSubNo = $se->nextval();
				$rechargeId = (int)$rlid.'-'.(int)$rdpid.'-'.(int)$userId.'-'.$orderSubNo;
				$remoney = $recharge['recharge_money'];
				
				$notifyUrl = 'http://'.$_SERVER['HTTP_HOST'].$this->createUrl('/mtpay/mtrechargeresult');
				$returnUrl = 'http://'.$_SERVER['HTTP_HOST'].$this->createUrl('/user/index',array('companyId'=>$this->companyId));
				$data = array(
						'companyId'=>$this->companyId,
						'dpid'=>$this->companyId,
						'outTradeNo'=>$rechargeId,
						'totalFee'=>$remoney*100,
						'subject'=>'wx-chongzhi',
						'body'=>'wx-chongzhi',
						'channel'=>'wx_scan_pay',
						'expireMinutes'=>'5',
						'tradeType'=>'JSAPI',
						'notifyUrl'=>$notifyUrl,
						'return_url'=>$returnUrl
				);
				$mtpayUrl = $this->createUrl('/mall/mtJsapiparams',$data);
		?>
		<div class="item" onclick="mtApiCall('<?php echo $mtpayUrl;?>')"><div class="top"><?php if($recharge['recharge_money']-(int)$recharge['recharge_money']==0){echo (int)$recharge['recharge_money'];}else{ echo $recharge['recharge_money'];}?>元</div><div class="down"><?php if($recharge['recharge_cashback']):?>返<?php echo $recharge['recharge_cashback'];?>元<?php endif;?> <?php if($recharge['recharge_pointback']):?>返<?php echo $recharge['recharge_pointback'];?>积分<?php endif;?></div></div>
		<?php endforeach;?>
		<div class="clear"></div>
		<script type="text/javascript">
			//调用微信JS api 支付
			function mtApiCall(mtUrl)
			{
				location.href = mtUrl;
			}
		</script>
	<?php endif;?>
<?php else:?>
	<div class="weui_dialog_alert" id="dialog2">
         <div class="weui_mask"></div>
         <div class="weui_dialog">
                <div class="weui_dialog_hd"><strong class="weui_dialog_title">提示</strong></div>
                  <div class="weui_dialog_bd">注册会员后才能使用充值功能哦</div>
                   <div class="weui_dialog_ft">
                      <a href="<?php echo $this->createUrl('/user/setUserInfo',array('companyId'=>$this->companyId));?>" class="weui_btn_dialog primary">去注册</a>
               </div>
         </div>
    </div>
<?php endif;?>
</div>