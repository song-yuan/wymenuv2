<!doctype html>
<html>

	<head>
		<meta charset="UTF-8">
		<title></title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<link href="../../../../css/mui.min.css" rel="stylesheet" />
		<script src="../../../../js/mui.min.js"></script>
		<script type="text/javascript">
			mui.init()
		</script>
		<style>
			body{
				margin: 0px;
				padding: 0px;
				background-color: #CCCCCC;
				width: 100%;
				height: 100%;
			}
			body div{
				background-color: #FFFFFF;
				line-height: 40px;
			}
		</style>
	</head>

	<body>
		<div>
			<table cellpadding="0" cellspacing="0" width="100%" style="text-align: center;">
				<tr>
					<td><h4>上海斗石</h4></td>
					<td><h4><span><?php echo date('Y-m-d H:i:s');?></span></h4></td>
				</tr>
				<tr>
					<td colspan="2"><h4>单品销售报表</h4></td>
				</tr>
				<tr>
					<td>开始时间</td>
					<td><span><?php echo date('Y-m-d');?></span> 00:00:00</td>
				</tr>
				<tr>
					<td>结束时间</td>
					<td><span><?php echo date('Y-m-d');?></span> 23:59:59</td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0" width="100%" style="text-align: center;">
				<tr>
					<td width="30%"><h4>销售数据</h4></td>
				</tr>
				<tr>
					<td width="40%">单品名称</td>
					<td width="15%">销量</td>
					<td width="15%">原价</td>
					<td width="15%">折扣</td>
					<td width="15%">实收</td>
				</tr>
			</table>
			<div style="overflow: auto;height: 465px;">
				<table cellpadding="0" cellspacing="0" width="100%">
					<?php foreach($products as $product):?>
					<tr>
						<td width="40%" style="padding-left: 5px"><?php echo $product['product_name'];?></td>
						<td width="15%" style="text-align: center;"><?php echo $product['counts'];?></td>
						<td width="15%" style="text-align: center;"><?php echo round($product['original_price'],2);?></td>
						<td width="15%" style="text-align: center;"><?php echo round($product['original_price']-$product['price'],2);?></td>
						<td width="15%" style="text-align: center;"><?php echo round($product['price'],2);?></td>
					</tr>
				<?php endforeach;?>
				</table>
			</div>
		</div>
		<div style="background-color: #FFFFFF;width: 100%;text-align: right;line-height: 30px;">
			<button><a href="<?php echo $this->createUrl('shoujiduan/index',array('companyId'=>$this->companyId));?>#dpxs">返回</a></button>
		</div>
	</body>
</html>