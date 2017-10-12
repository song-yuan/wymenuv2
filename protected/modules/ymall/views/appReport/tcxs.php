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
	body div{
		background-color: #FFFFFF;
		line-height: 40px;
	}
</style>

<div>
	<form method="post">
		<table cellpadding="0" cellspacing="0" width="100%" style="text-align: center;">
			<tr>
				<td><h4>上海斗石</h4></td>
				<td><h4><span><?php echo date('Y-m-d');?></span></h4></td>
			</tr>
			<tr>
				<td colspan="2"><h4>套餐销售报表</h4></td>
			</tr>
			<tr>
				<td>开始时间</td>
				<td><input type="date" name="date[start]" value="<?php echo $date['start'];?>" style="width: 70%;"></td>
			</tr>
			<tr>
				<td>结束时间</td>
				<td><input type="date" name="date[End]" value="<?php echo $date['End'];?>" style="width: 70%;"></td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" value="查询"></td>
			</tr>
		</table>
	</form>
	<table cellpadding="0" cellspacing="10" width="100%" style="text-align: center;">
		<tr>
			<td><h4>销售数据</h4></td>
		</tr>
		<tr>
			<td width="35%">套餐名称</td>
			<td width="15%">销量</td>
			<td width="20%">销售额</td>
			<td width="15%">折扣</td>
			<td width="15%">实收</td>
		</tr>
	</table>
	<div style="overflow: auto;height: 465px;">
		<table cellpadding="0" cellspacing="10" width="100%" style="text-align: center;">
			<?php if(!empty($orders)):?>
			<?php foreach($orders as $order):?>
			<tr>
				<td width="35%"><?php echo $order['set_name'];?></td>
				<td width="15%"><?php echo $order['all_setnum'];?></td>
				<td><?php echo round($order['all_orisetprice'],2);?></td>
				<td width="15%"><?php echo round($order['all_orisetprice']-$order['all_setprice'],2);?></td>
				<td width="15%"><?php echo round($order['all_setprice'],2);?></td>
			</tr>
			<?php endforeach;?>
			<?php endif;?>
		</table>
	</div>
	<div style="margin-top: 10px;"><button style="margin-left: 80%;"><a href="<?php echo $this->createUrl('shoujiduan/index',array('companyId'=>$this->companyId));?>#tcxs">返回</a></button></div>
</div>