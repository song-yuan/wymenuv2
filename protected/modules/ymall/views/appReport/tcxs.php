<link rel="stylesheet" type="text/css" href="../../../../css/appReport/app.css">
<header class="mui-bar mui-bar-nav">
	<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$this->companyId));?>"></a>
	<h1 class="mui-title">套餐销售报表</h1>
</header>
<div class="sd">
	<form method="post">
	<ul class="mui-table-view">
		<li class="mui-table-view-cell">
			<span>门店</span>
			<span style="padding-left: 165px;"><?php echo Helper::getCompanyName($this->companyId);?></span>
		</li>
		<li class="mui-table-view-cell">
			<span>开始时间</span>
			<span id='demo2' style="padding-left: 135px;" data-options='{"type":"date"}' class="btn mui-navigate-right"><?php if(empty($date)){?>选择日期<?php }else{echo $date['start'];}?></span>
			<input id="date1" type="hidden" name="date[start]">
		</li>
		<li class="mui-table-view-cell">
			<span>结束时间</span>
			<span id='demo4' style="padding-left: 135px;" data-options='{"type":"date"}' class="btn mui-navigate-right"><?php if(empty($date)){?>选择日期<?php }else{echo $date['End'];}?></span>
			<input id="date2" type="hidden" name="date[End]">
		</li>
		<li>
			<button type="submit" class="mui-btn mui-btn-primary mui-btn-block">查询</button>
		</li>
	</ul>
	</form>
</div>
<div class="dp">
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