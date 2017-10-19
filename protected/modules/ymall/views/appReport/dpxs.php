<link rel="stylesheet" type="text/css" href="../../../../css/appReport/app.css">
<header class="mui-bar mui-bar-nav">
		<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$this->companyId));?>"></a>
		<h1 class="mui-title">单品销售报表</h1>
</header>
<div class="dp">
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
	<div style="margin-top: 10px;">
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td style="padding-left: 10px;" width="40%">单品名称</td>
				<td width="15%">销量</td>
				<td width="15%">原价</td>
				<td width="15%">折扣</td>
				<td width="15%">实收</td>
			</tr>
		</table>
	</div>
	<div class="dp1">
		<table cellpadding="0" cellspacing="0" width="100%">
			<?php foreach($products as $product):?>
			<tr class="tr3">
				<td width="45%" class="td"><?php echo $product['product_name'];?></td>
				<td width="10%"><?php echo $product['counts'];?></td>
				<td width="15%"><?php echo round($product['original_price'],2);?></td>
				<td width="15%"><?php echo round($product['original_price']-$product['price'],2);?></td>
				<td width="15%"><?php echo round($product['price'],2);?></td>
			</tr>
		<?php endforeach;?>
		</table>
	</div>
</div>
