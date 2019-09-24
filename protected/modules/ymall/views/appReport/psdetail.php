<?php 
	$basePath = Yii::app()->baseUrl;
?>
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/app.css">
<header class="mui-bar mui-bar-nav">
	<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" href="<?php echo $this->createUrl('appReport/kcps',array('companyId'=>$this->companyId));?>"></a>
	<h1 class="mui-title">盘损详情</h1>
</header>
<form class="mui-input-group">
<div class="sd">
	<div class="mui-input-row">
		<label>盘损时间</label><label style="width:55%;"><?php echo $model->create_at;?></label>
	</div>
	<div class="mui-input-row">
		<label>操作员</label>
		<label><?php echo $model->opretion_id;?></label>
	</div>
	<div class="mui-input-row">
		<label>盘损原因</label><label><?php echo $model->retreat->name;?></label>
	</div>
	<div class="mui-input-row" style="height:auto;">
		<label>说明</label><label><?php echo $model->remark;?></label>
	</div>
</div>
<div class="dp">
	<div style="margin-top: 10px;">
		<ul class="ul">
			<li class="li-4">品项名称</li>
			<li class="li-4">单位规格</li>
			<li class="li-4">单位名称</li>
			<li class="li-4">盘损库存</li>
			<div style="clear: both;"></div>
		</ul>
	</div>
	<div class="dp1">
	<?php 
		foreach($models as $m):
	?>
	<?php 
		if($m['type']==1):
		$materialUnit = Common::getmaterialUnit($m['material_id'], $m['dpid'], 0);
	?>
		<ul class="ul">
			<li class="li-4"><?php echo $materialUnit['material_name'];?></li>
			<li class="li-4"><?php echo $materialUnit['unit_name'];?></li>
			<li class="li-4"><?php echo $materialUnit['unit_specifications'];?></li>
			<li class="li-4"><?php echo $m['inventory_stock'];?></li>
			<div style="clear: both;"></div>
		</ul>
		<?php 
			else:
			$productName = Common::getproductName($m['material_id']);
		?>
		<ul class="ul">
			<li class="li-4"><?php echo $productName;?></li>
			<li class="li-4">个</li>
			<li class="li-4">个</li>
			<li class="li-4"><?php echo $m['inventory_stock'];?></li>
			<div style="clear: both;"></div>
		</ul>
		<?php endif?>
		<?php endforeach;?>
	</div>
</div>
</form>