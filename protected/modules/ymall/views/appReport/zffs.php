<?php $basePath = Yii::app()->baseUrl;?>
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appReport/app.css">
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/mui.picker.min.css" />
<header class="mui-bar mui-bar-nav">
		<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$this->companyId));?>"></a>
		<h1 class="mui-title">支付方式报表</h1>
</header>

<div class="sd">
	<form method="post">
	<ul class="mui-table-view">
		<li class="mui-table-view-cell">
			<span>门店</span>
			<span style="padding-left: 135px;"><?php if(empty($type)){echo Helper::getCompanyName($this->companyId);}else{echo $type['group_name'];}?></span>
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
	<?php if(!empty($orders)):?>
<div class="dp">
	<ul class="mui-table-view">
		<li class="mui-table-view-cell">总单数<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php if(!empty($orders)){
				foreach ($orders as $order) {
					echo $order['count'];
				}
				}?></span></li>
		<li class="mui-table-view-cell">毛利润<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php if(!empty($orders)){
				foreach ($orders as $order) {
					$reality_total = $order['reality_total'];
					echo $reality_total;
					}
				}?></span></li>
		<li class="mui-table-view-cell">优惠<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php 
				if(!empty($refunds)){
					foreach ($refunds as $refund) {
					$pay_amount = $refund['pay_amount'];
					}
				}else{
					$pay_amount = 0;
				}
				$array = array();
				foreach ($zfs as $zf) {
					
						array_push($array,$zf['pay_amount']);	
				}
				$sum = array_sum($array);
				if($orders && $zfs){
					echo $reality_total-$sum+$pay_amount;
				}
			?></span></li>
		<li class="mui-table-view-cell">实收款<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php if(!empty($array)){ echo $sum;}?></span></li>
		<li class="mui-table-view-cell">现金<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php
				foreach ($zfs as $zf) {
					if($zf['paytype']==0){
						echo $zf['pay_amount'];
					}
				}
				?></span></li>
		<li class="mui-table-view-cell">微信<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php
				foreach ($zfs as $zf) {
					if($zf['paytype']==1){
						echo $zf['pay_amount'];
					}
				}
				?></span></li>
		<li class="mui-table-view-cell">微信点单<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php foreach ($zfs as $zf) {
					if($zf['paytype']==12){
						echo $zf['pay_amount'];
					}
				}?></span></li>
		<li class="mui-table-view-cell">微信外卖<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php foreach ($zfs as $zf) {
					if($zf['paytype']==13){
						echo $zf['pay_amount'];
					}
				}?></span></li>
		<li class="mui-table-view-cell">美团外卖<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php foreach ($zfs as $zf) {
					if($zf['paytype']==14){
						echo $zf['pay_amount'];
					}
				}?></span></li>
		<li class="mui-table-view-cell">饿了么外卖<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php foreach ($zfs as $zf) {
					if($zf['paytype']==15){
						echo $zf['pay_amount'];
					}
				}?></span></li>
		<li class="mui-table-view-cell">支付宝<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php foreach ($zfs as $zf) {
					if($zf['paytype']==2){
						echo $zf['pay_amount'];
					}
				}?></span></li>
		<li class="mui-table-view-cell">银联<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php foreach ($zfs as $zf) {
					if($zf['paytype']==5){
						echo $zf['pay_amount'];
					}
				}?></span></li>
		<li class="mui-table-view-cell">会员卡<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php foreach ($zfs as $zf) {
					if($zf['paytype']==4){
						echo $zf['pay_amount'];
					}
				}?></span></li>
		<li class="mui-table-view-cell">后台支付<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php foreach ($zfs as $zf) {
					if($zf['paytype']==3){
						echo $zf['pay_amount'];
					}
				}?></span></li>
		<li class="mui-table-view-cell">系统劵<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php foreach ($zfs as $zf) {
					if($zf['paytype']==9){
						echo $zf['pay_amount'];
					}
				}?></span></li>
		<li class="mui-table-view-cell">积分<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php foreach ($zfs as $zf) {
					if($zf['paytype']==8){
						echo $zf['pay_amount'];
					}
				}?></span></li>
		<li class="mui-table-view-cell">微信储值<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php foreach ($zfs as $zf) {
					if($zf['paytype']==10){
						echo $zf['pay_amount'];
					}
				}?></span></li>
		<li class="mui-table-view-cell">退款<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php if(!empty($pay_amount)){ echo $pay_amount;}?></span></li>	
	</ul>
<?php endif;?>
</div>
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