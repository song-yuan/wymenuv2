<link rel="stylesheet" type="text/css" href="../../../../css/appReport/app.css">
<header class="mui-bar mui-bar-nav">
	<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$this->companyId));?>"></a>
	<h1 class="mui-title">套餐销售报表</h1>
</header>
<div class="dp">
	<div class="sd">
		<form method="post">
			<table cellpadding="0" cellspacing="0" width="100%" style="text-align: center;">
				<tr class="tr2">
					<td class="tb2" class="tb">门店</td>
					<td class="tb3"><span class="span"><?php echo Helper::getCompanyName($this->companyId);?></span></td>
				</tr>
				<tr class="tr2">
					<td class="tb2">开始时间</td>
					<td class="tb3"><input class="date" type="date" name="date[start]" value="<?php echo $date['start'];?>" style="width: 70%;"></td>
				</tr>
				<tr class="tr2">
					<td class="tb2">结束时间</td>
					<td class="tb3"><input class="date" type="date" name="date[End]" value="<?php echo $date['End'];?>" style="width: 70%;"></td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" value="查询"></td>
				</tr>
			</table>
		</form>
	</div>
	<table cellpadding="0" cellspacing="10" width="100%" style="text-align: center;">
		<tr>
			<td width="30%">套餐名称</td>
			<td width="15%">销量</td>
			<td width="20%">销售额</td>
			<td width="15%">折扣</td>
			<td width="20%">实收</td>
		</tr>
	</table>
	<div class="dp1">
		<table cellpadding="0" cellspacing="10" width="100%" style="text-align: center;">
			<?php if(!empty($orders)):?>
			<?php foreach($orders as $order):?>
			<tr>
				<td width="30%"><?php echo $order['set_name'];?></td>
				<td width="20%"><?php echo $order['all_setnum'];?></td>
				<td><?php echo round($order['all_orisetprice'],2);?></td>
				<td width="15%"><?php echo round($order['all_orisetprice']-$order['all_setprice'],2);?></td>
				<td width="20%"><?php echo round($order['all_setprice'],2);?></td>
			</tr>
			<?php endforeach;?>
			<?php endif;?>
		</table>
	</div>
</div>