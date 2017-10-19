<link rel="stylesheet" type="text/css" href="../../../../css/appReport/app.css">
<header class="mui-bar mui-bar-nav">
		<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$this->companyId));?>"></a>
		<h1 class="mui-title">支付方式报表</h1>
</header>
<div class="yy">
	<div class="sd">
		<form method="post">
			<table cellpadding="0" cellspacing="0" width="100%" class="tr2">
				<tr>
					<td class="tb2">门店</td>
					<td class="tb3"><span class="span"><?php echo Helper::getCompanyName($this->companyId);?></span></td>
				</tr>
				<tr>
					<td class="tb2">开始时间</td>
					<td class="tb3">
						<input class="date" type="date" value="<?php echo $date['start'];?>" name="date[start]">
						
					</td>
				</tr>
				<tr>
					<td class="tb2">结束时间</td>
					<td class="tb3"><input class="date" type="date" name="date[End]" value="<?php echo $date['End'];?>"></td>
				</tr>
				<tr class="tr2">
					<td colspan="2"><input type="submit" value="查询"></td>
				</tr>
			</table>
		</form>
	</div>
	<?php if(!empty($orders)):?>
	<div>
		<table id="table" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td class="td">总单数</td>
			<td class="td4"><?php if(!empty($orders)){
				foreach ($orders as $order) {
					echo $order['count'];
				}
				}?></td>
		</tr>
		<tr>
			<td class="td">毛利润</td>
			<td class="td4"><?php if(!empty($orders)){
				foreach ($orders as $order) {
					$reality_total = $order['reality_total'];
					echo $reality_total;
					}
				}?></td>
		</tr>
		<tr>
			<td class="td">优惠</td>
			<td class="td4"><?php 
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
			?></td>
		</tr>
		<tr>
			<td class="td">实收款</td>
			<td class="td4"><?php if(!empty($array)){ echo $sum;}?></td>
		</tr>
		<tr>
			<td class="td">现金</td>
			<td class="td4"><?php
				foreach ($zfs as $zf) {
					if($zf['paytype']==0){
						echo $zf['pay_amount'];
					}
				}
				?></td>
		</tr>
		<tr>
			<td class="td">微信</td>
			<td class="td4"><?php
				foreach ($zfs as $zf) {
					if($zf['paytype']==1){
						echo $zf['pay_amount'];
					}
				}
				?></td>
		</tr>
		<tr>
			<td class="td">微信点单</td>
			<td class="td4"><?php foreach ($zfs as $zf) {
					if($zf['paytype']==12){
						echo $zf['pay_amount'];
					}
				}?></td>
		</tr>
		<tr>
			<td class="td">微信外卖</td>
			<td class="td4"><?php foreach ($zfs as $zf) {
					if($zf['paytype']==13){
						echo $zf['pay_amount'];
					}
				}?></td>
		</tr>
		<tr>
			<td class="td">美团外卖</td>
			<td class="td4"><?php foreach ($zfs as $zf) {
					if($zf['paytype']==14){
						echo $zf['pay_amount'];
					}
				}?></td>
		</tr>
		<tr>
			<td class="td">饿了么外卖</td>
			<td class="td4"><?php foreach ($zfs as $zf) {
					if($zf['paytype']==15){
						echo $zf['pay_amount'];
					}
				}?></td>
		</tr>
		<tr>
			<td class="td">支付宝</td>
			<td class="td4"><?php foreach ($zfs as $zf) {
					if($zf['paytype']==2){
						echo $zf['pay_amount'];
					}
				}?></td>
		</tr>
		<tr>
			<td class="td">银联</td>
			<td class="td4"><?php foreach ($zfs as $zf) {
					if($zf['paytype']==5){
						echo $zf['pay_amount'];
					}
				}?></td>
		</tr>
		<tr>
			<td class="td">会员卡</td>
			<td class="td4"><?php foreach ($zfs as $zf) {
					if($zf['paytype']==4){
						echo $zf['pay_amount'];
					}
				}?></td>
		</tr>
		<tr>
			<td class="td">后台支付</td>
			<td class="td4"><?php foreach ($zfs as $zf) {
					if($zf['paytype']==3){
						echo $zf['pay_amount'];
					}
				}?></td>
		</tr>
		<tr>
			<td class="td">系统劵</td>
			<td class="td4"><?php foreach ($zfs as $zf) {
					if($zf['paytype']==9){
						echo $zf['pay_amount'];
					}
				}?></td>
		</tr>
		<tr>
			<td class="td">积分</td>
			<td class="td4"><?php foreach ($zfs as $zf) {
					if($zf['paytype']==8){
						echo $zf['pay_amount'];
					}
				}?></td>
		</tr>
		<tr>
			<td class="td">微信储值</td>
			<td class="td4"><?php foreach ($zfs as $zf) {
					if($zf['paytype']==10){
						echo $zf['pay_amount'];
					}
				}?></td>
		</tr>
		<tr>
			<td class="td">退款</td>
			<td class="td4"><?php if(!empty($pay_amount)){ echo $pay_amount;}?></td>
		</tr>
	</table>
<?php endif;?>
	</div>
</div>