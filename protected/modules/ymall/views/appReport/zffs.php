<!doctype html>
<html>

	<head>
		<meta charset="UTF-8">
		<title></title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<link href="../../../../css/shouji/mui.min.css" rel="stylesheet" />
		<script src="../../../../js/shouji/mui.min.js"></script>
		<script type="text/javascript">
			mui.init()
		</script>
		<style>
			body{
				margin: 0px;
				padding: 0px;
				background-color: #CCCCCC;
				width: 100%;
			}
			body .td{
				padding-left: 20px;
			}
			body div{
				background-color: #FFFFFF;
			}
		</style>
	</head>

	<body>
		<div>
			<div style="margin-bottom: 10px;">
				<form method="post">
					<table cellpadding="0" cellspacing="0" width="100%" style="line-height: 30px;">
						<tr style="text-align: center;">
							<td colspan="2"><h4>上海斗石</h4></td>
						</tr>
						<tr>
							<td class="td">开始时间</td>
							<td>
								<input type="date" value="<?php echo $date['start'];?>" name="date[start]" style="width: 70%;">
								
							</td>
						</tr>
						<tr>
							<td class="td">结束时间</td>
							<td><input type="date" value="<?php echo $date['End'];?>" name="date[End]" style="width: 70%;"></td>
						</tr>
						<tr>
							<td colspan="2" class="td" style="text-align: center;"><input type="submit" value="查询"></td>
						</tr>
					</table>
				</form>
			</div>
			<div>
				<table id="table" cellpadding="0" cellspacing="0" width="100%" style="line-height: 30px;" border="1">
				<tr>
					<td class="td">总单数</td>
					<td class="td"><?php if(!empty($orders)){
						foreach ($orders as $order) {
							echo $order['count'];
						}
						}?></td>
				</tr>
				<tr>
					<td class="td">毛利润</td>
					<td class="td"><?php if(!empty($orders)){
						foreach ($orders as $order) {
							$reality_total = $order['reality_total'];
							echo $reality_total;
							}
						}?></td>
				</tr>
				<tr>
					<td class="td">优惠</td>
					<td class="td"><?php 
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
					<td class="td"><?php if(!empty($array)){ echo $sum;}?></td>
				</tr>
				<tr>
					<td class="td">现金</td>
					<td class="td"><?php
						foreach ($zfs as $zf) {
							if($zf['paytype']==0){
								echo $zf['pay_amount'];
							}
						}
						?></td>
				</tr>
				<tr>
					<td class="td">微信</td>
					<td class="td"><?php
						foreach ($zfs as $zf) {
							if($zf['paytype']==1){
								echo $zf['pay_amount'];
							}
						}
						?></td>
				</tr>
				<tr>
					<td class="td">微信点单</td>
					<td class="td"><?php foreach ($zfs as $zf) {
							if($zf['paytype']==12){
								echo $zf['pay_amount'];
							}
						}?></td>
				</tr>
				<tr>
					<td class="td">微信外卖</td>
					<td class="td"><?php foreach ($zfs as $zf) {
							if($zf['paytype']==13){
								echo $zf['pay_amount'];
							}
						}?></td>
				</tr>
				<tr>
					<td class="td">美团外卖</td>
					<td class="td"><?php foreach ($zfs as $zf) {
							if($zf['paytype']==14){
								echo $zf['pay_amount'];
							}
						}?></td>
				</tr>
				<tr>
					<td class="td">饿了么外卖</td>
					<td class="td"><?php foreach ($zfs as $zf) {
							if($zf['paytype']==15){
								echo $zf['pay_amount'];
							}
						}?></td>
				</tr>
				<tr>
					<td class="td">支付宝</td>
					<td class="td"><?php foreach ($zfs as $zf) {
							if($zf['paytype']==2){
								echo $zf['pay_amount'];
							}
						}?></td>
				</tr>
				<tr>
					<td class="td">银联</td>
					<td class="td"><?php foreach ($zfs as $zf) {
							if($zf['paytype']==5){
								echo $zf['pay_amount'];
							}
						}?></td>
				</tr>
				<tr>
					<td class="td">会员卡</td>
					<td class="td"><?php foreach ($zfs as $zf) {
							if($zf['paytype']==4){
								echo $zf['pay_amount'];
							}
						}?></td>
				</tr>
				<tr>
					<td class="td">后台支付</td>
					<td class="td"><?php foreach ($zfs as $zf) {
							if($zf['paytype']==3){
								echo $zf['pay_amount'];
							}
						}?></td>
				</tr>
				<tr>
					<td class="td">系统劵</td>
					<td class="td"><?php foreach ($zfs as $zf) {
							if($zf['paytype']==9){
								echo $zf['pay_amount'];
							}
						}?></td>
				</tr>
				<tr>
					<td class="td">积分</td>
					<td class="td"><?php foreach ($zfs as $zf) {
							if($zf['paytype']==8){
								echo $zf['pay_amount'];
							}
						}?></td>
				</tr>
				<tr>
					<td class="td">微信储值</td>
					<td class="td"><?php foreach ($zfs as $zf) {
							if($zf['paytype']==10){
								echo $zf['pay_amount'];
							}
						}?></td>
				</tr>
				<tr>
					<td class="td">退款</td>
					<td class="td"><?php if(!empty($pay_amount)){ echo $pay_amount;}?></td>
				</tr>
			</table>
			</div>
			<div style="margin-top: 10px;"><button style="margin-left: 80%;"><a href="<?php echo $this->createUrl('shoujiduan/index',array('companyId'=>$this->companyId));?>#zffs">返回</a></button></div>
		</div>
	</body>
</html>