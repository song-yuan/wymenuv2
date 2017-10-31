<?php $basePath = Yii::app()->baseUrl;?>
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/app.css">
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/mui.picker.min.css" />
<style type="text/css">
	.mui-content {
		height: 0px;
		margin: 0px;
		background-color: #efeff4;
	}
	h5.mui-content-padded {
		margin-left: 3px;
		margin-top: 20px !important;
	}
	h5.mui-content-padded:first-child {
		margin-top: 12px !important;
	}
	.mui-btn {
		font-size: 16px;
		padding: 8px;
		margin: 3px;
	}
	.ui-alert {
		text-align: center;
		padding: 20px 10px;
		font-size: 16px;
	}
	* {
		-webkit-touch-callout: none;
		-webkit-user-select: none;
	}
</style>
<header class="mui-bar mui-bar-nav">
	<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$this->companyId));?>"></a>
	<h1 class="mui-title">营业数据</h1>
</header>
<div>
	<div class="sd">
		<form method="post">
		<ul class="mui-table-view">
			<li class="mui-table-view-cell">
				<span>门店</span>
				<span style="padding-left: 135px;"><?php if(empty($type)){echo Helper::getCompanyName($this->companyId);}else{echo $type['group_name'];} ?></span>
			</li>
			<li class="mui-table-view-cell">
				<span>开始时间</span>
				<span id='demo2' style="padding-left: 95px;" data-options='{"type":"date"}' class="btn mui-navigate-right"><?php if(empty($date)){?>选择日期<?php }else{echo $date['start'];}?></span>
				<input id="date1" type="hidden" name="date[start]">
			</li>
			<li class="mui-table-view-cell">
				<span>结束时间</span>
				<span id='demo4' style="padding-left: 95px;" data-options='{"type":"date"}' class="btn mui-navigate-right"><?php if(empty($date)){?>选择日期<?php }else{echo $date['End'];}?></span>
				<input id="date2" type="hidden" name="date[End]">
			</li>
			<li>
				<button type="submit" class="mui-btn mui-btn-primary mui-btn-block">查询</button>
			</li>
		</ul>
		</form>
	</div>
	<ul class="mui-table-view">
	  	<li class="mui-table-view-divider">交易统计</li>
		<li class="mui-table-view-cell">交易数<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php foreach($todayProfit as $Profit){
				if(!empty($Profit['counts'])){
					echo $Profit['counts'];
				}else{
					echo '0';
				}
				}?></span></li>
        <li class="mui-table-view-cell">票单价<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php foreach($todayProfit as $Profit){
					$reality_total = $Profit['reality_total'];
					$counts = $Profit['counts'];
				}
				$array = array();
				foreach ($Paymentmethod as $Pay) {
					
						array_push($array,$Pay['pay_amount']);	
				}
				$sum = array_sum($array);
				foreach($refunds as $refund){
					$repay_amount = $refund['pay_amount'];
				}
				if(!empty($counts)){
					echo round(($sum+$repay_amount)/$counts,2);
				}else{
					echo "0";
				}
				?></span></li>
        <li class="mui-table-view-cell">来客数<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php foreach($todayProfit as $Profit){
				if(!empty($Profit['number'])){
					$number = $Profit['number'];
					echo $number;
				}else{
					echo '0';
				}
				}?></span></li>
        <li class="mui-table-view-cell">客单价<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php if(!empty($number)){echo round(($sum+$repay_amount)/$number,2);}else{echo "0";} ?></span></li>
    </ul>
    <ul class="mui-table-view">
	  	<li class="mui-table-view-divider">营业数据</li>
	  	<li class="mui-table-view-cell">营业毛额<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php if(!empty($reality_total)){echo $reality_total;}else{echo "0";}?></span></li>
	  	<li class="mui-table-view-cell">优惠/折扣<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php if(!empty($sum)){
						echo round($reality_total-$sum+$repay_amount,2);
					   }else{
							echo "0";
					   }?></span></li>
	  	<li class="mui-table-view-cell">营业收入<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php echo $sum;?></span></li>
	 </ul>
	 <ul class="mui-table-view">
	  	<li class="mui-table-view-divider">收款统计</li>
	  	<?php foreach($Paymentmethod as $Pay):?>
			<?php if($Pay['paytype']==0):?>
	  	<li class="mui-table-view-cell">现金<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php echo $Pay['pay_amount']."(".$Pay['counts']."次)";?></span></li>
	  	<?php endif;?>
		<?php endforeach;?>
		<?php foreach($Paymentmethod as $Pay):?>
			<?php if($Pay['paytype']==1):?>
	  	<li class="mui-table-view-cell">微信支付<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php echo $Pay['pay_amount']."(".$Pay['counts']."次)";?></span></li>
	  	<?php endif;?>
		<?php endforeach;?>
		<?php foreach($Paymentmethod as $Pay):?>
			<?php if($Pay['paytype']==2):?>
	  	<li class="mui-table-view-cell">支付宝<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php echo $Pay['pay_amount']."(".$Pay['counts']."次)";?></span></li>
	  		<?php endif;?>
		<?php endforeach;?>
		<?php foreach($Paymentmethod as $Pay){
			if ($Pay['paytype']==3) {
				$juan = $Pay['pay_amount'];
				$juanci = $Pay['counts'];
				$id = $Pay['payment_method_id'];
			}
			}
			if(!empty($id)){
				$sql = "select name from nb_payment_method where lid=".$id;
			$names = Yii::app()->db->createCommand($sql)->queryRow();
			}
			?>
			<?php if(!empty($names)):?>
	  	<li class="mui-table-view-cell"><?php echo $names['name'];?><span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php echo $juan."(".$juanci."次)";?></span></li>
	  	<?php endif;?>
	  	<?php foreach($Paymentmethod as $Pay):?>
			<?php if($Pay['paytype']==4):?>
	  	<li class="mui-table-view-cell">会员卡<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php echo $Pay['pay_amount']."(".$Pay['counts']."次)";?></span></li>
	  		<?php endif;?>
		<?php endforeach;?>
		<?php foreach($Paymentmethod as $Pay):?>
			<?php if($Pay['paytype']==5):?>
	  	<li class="mui-table-view-cell">银联<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php echo $Pay['pay_amount']."(".$Pay['counts']."次)";?></span></li>
	  	<?php endif;?>
		<?php endforeach;?>
		<?php foreach($Paymentmethod as $Pay):?>
			<?php if($Pay['paytype']==10):?>
	  	<li class="mui-table-view-cell">微信储值支付<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php echo $Pay['pay_amount']."(".$Pay['counts']."次)";?></span></li>
	  	<?php endif;?>
		<?php endforeach;?>
		<?php foreach($Paymentmethod as $Pay):?>
			<?php if($Pay['paytype']==12):?>
	  	<li class="mui-table-view-cell">微点单<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php echo $Pay['pay_amount']."(".$Pay['counts']."次)";?></span></li>
	  	<?php endif;?>
		<?php endforeach;?>
		<?php foreach($Paymentmethod as $Pay):?>
			<?php if($Pay['paytype']==13):?>
	  	<li class="mui-table-view-cell">微外卖<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php echo $Pay['pay_amount']."(".$Pay['counts']."次)";?></span></li>
	  		<?php endif;?>
		<?php endforeach;?>
		<?php foreach($Paymentmethod as $Pay):?>
			<?php if($Pay['paytype']==14):?>
	  	<li class="mui-table-view-cell">美团外卖<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php echo $Pay['pay_amount']."(".$Pay['counts']."次)";?></span></li>
	  		<?php endif;?>
		<?php endforeach;?>
		<?php foreach($Paymentmethod as $Pay):?>
			<?php if($Pay['paytype']==15):?>
	  	<li class="mui-table-view-cell">饿了么外卖<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php echo $Pay['pay_amount']."(".$Pay['counts']."次)";?></span></li>
	  		<?php endif;?>
		<?php endforeach;?>
	  	<li class="mui-table-view-cell">总计<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php echo $sum;?></span></li>
	 </ul>
	 <ul class="mui-table-view">
	  	<li class="mui-table-view-divider">充值统计</li>
	  	<li class="mui-table-view-cell">传统会员<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php foreach($Recharges as $Recharge){
					if(!empty($Recharge['reality_money'])){
						echo $Recharge['reality_money'];
					}else{
						echo '0.00';
					}
				}?></span></li>
		<li class="mui-table-view-cell">传统会员赠送<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php foreach($Recharges as $Recharge){
					if(!empty($Recharge['give_money'])){
						echo $Recharge['give_money'];
					}else{
						echo '0.00';
					}
				}?></span></li>
		<li class="mui-table-view-cell">微信会员<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php foreach($records as $record){
					if(!empty($record['recharge_money'])){
						echo $record['recharge_money'];
					}else{
						echo '0.00';
					}
				}?></span></li>
		<li class="mui-table-view-cell">微信会员赠送<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php foreach($records as $record){
					if(!empty($record['cashback_num'])){
						echo $record['cashback_num'];
					}else{
						echo '0.00';
					}
				}?></span></li>	  	
	 </ul>
	 <ul class="mui-table-view">
	  	<li class="mui-table-view-divider">外卖营收</li>
	  	<?php foreach($Paymentmethod as $Pay):?>
			<?php if($Pay['paytype']==13):?>
	  	<li class="mui-table-view-cell">微外卖<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php echo $Pay['pay_amount']."(".$Pay['counts']."次)";?></span></li>
	  		<?php endif;?>
		<?php endforeach;?>
		<?php foreach($Paymentmethod as $Pay):?>
			<?php if($Pay['paytype']==14):?>
		<li class="mui-table-view-cell">美团外卖<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php echo $Pay['pay_amount']."(".$Pay['counts']."次)";?></span></li>
			<?php endif;?>
		<?php endforeach;?>
		<?php foreach($Paymentmethod as $Pay):?>
			<?php if($Pay['paytype']==15):?>
		<li class="mui-table-view-cell">饿了么外卖<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php echo $Pay['pay_amount']."(".$Pay['counts']."次)";?></span></li>
			<?php endif;?>
		<?php endforeach;?>
		<li class="mui-table-view-cell">外卖总计<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php 
			$array = array();
			$counts = array();
			foreach($Paymentmethod as $Pay){
					if($Pay['paytype']==13 || $Pay['paytype']==14 || $Pay['paytype']==15){
						array_push($array,$Pay['pay_amount']);	
						array_push($counts,$Pay['counts']);
					}
				}
				$arraysum = array_sum($array);
				$countssum = array_sum($counts);
				echo $arraysum; 
				?>(<?php echo $countssum;?>次)</span></li>		
	 </ul>
	 <ul class="mui-table-view">
	  	<li class="mui-table-view-divider">其他</li>
	  	<li class="mui-table-view-cell">退款金额<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php if(!empty($repay_amount)){
				echo $repay_amount;
				}else{
					echo "0";
					}?></span></li>
	  	<li class="mui-table-view-cell">退款次数<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php foreach($refunds as $refund){
				if(!empty($refund['count'])){
					echo $refund['count'];
				}else{
					echo '0';
				}
				}?></span></li>
	 </ul>
</div>
<!--<script src="../js/mui.picker.js"></script>
<script src="../js/mui.dtpicker.js"></script>-->
<script src="<?php echo $basePath;?>/js/appreport/mui.picker.min.js"></script>
<script>
	(function($) {
		$.init();
		var btns = $('.btn');
		// alert(btns.length);
		btns.each(function(i, btn) {
			btn.addEventListener('tap', function() {
				var optionsJson = this.getAttribute('data-options') || '{}';
				var options = JSON.parse(optionsJson);
				var id = this.getAttribute('id');
				// alert(id);
				/*
				 * 首次显示时实例化组件
				 * 示例为了简洁，将 options 放在了按钮的 dom 上
				 * 也可以直接通过代码声明 optinos 用于实例化 DtPicker
				 */
				var picker = new $.DtPicker(options);
				picker.show(function(rs) {
					/*
					 * rs.value 拼合后的 value
					 * rs.text 拼合后的 text
					 * rs.y 年，可以通过 rs.y.vaue 和 rs.y.text 获取值和文本
					 * rs.m 月，用法同年
					 * rs.d 日，用法同年
					 * rs.h 时，用法同年
					 * rs.i 分（minutes 的第二个字母），用法同年
					 */
					 // alert(rs.text);
					 // $('#'+id).html(rs.text);
					$('#'+id)[0].innerText = rs.text;

					/* 
					 * 返回 false 可以阻止选择框的关闭
					 * return false;
					 */
					/*
					 * 释放组件资源，释放后将将不能再操作组件
					 * 通常情况下，不需要示放组件，new DtPicker(options) 后，可以一直使用。
					 * 当前示例，因为内容较多，如不进行资原释放，在某些设备上会较慢。
					 * 所以每次用完便立即调用 dispose 进行释放，下次用时再创建新实例。
					 */
					picker.dispose();
					var date1 = document.getElementById("demo2").innerText;
					var date2 = document.getElementById("demo4").innerText;
					document.getElementById("date1").value = date1;
					document.getElementById("date2").value = date2;
				});
			}, false);
		});

	})(mui);
</script>