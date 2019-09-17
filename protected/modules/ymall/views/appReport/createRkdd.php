<?php 
	$basePath = Yii::app()->baseUrl;
?>
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/app.css">
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/mui.picker.min.css">
<header class="mui-bar mui-bar-nav">
		<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$this->companyId));?>"></a>
		<h1 class="mui-title">新增入库订单</h1>
</header>
<div class="sd">
	<form class="mui-input-group" method="POST" onsubmit="return validate()">
		<div class="mui-input-row">
			<label>供应厂商</label>
			<select id="manufacturer_id" name="StorageOrder[manufacturer_id]">
				<?php foreach ($mfrs as $mfr):?>
				<option value="<?php echo $mfr['lid'];?>"><?php echo $mfr['manufacturer_name'];?></option>
				<?php endforeach;?>
			</select>
		</div>
		<div class="mui-input-row">
			<label>经办人员</label>
			<select id="admin_id" name="StorageOrder[admin_id]">
				<?php foreach ($users as $user):?>
				<option value="<?php echo $user['lid'];?>"><?php echo $user['username'];?></option>
				<?php endforeach;?>
			</select>
		</div>
		<div class="mui-input-row" style="height:auto;">
			<label>备注说明</label>
			<textarea id="remark" name="StorageOrder[remark]" rows="5" placeholder="输入说明"><?php if($model){echo $model->remark;}?></textarea>
		</div>
	</form>
</div>
<div class="dp">
	<div style="margin-top: 10px;">
		<div class="mui-input-row">
			<label>原料分类</label>
			<select id="mcategory" name="mcategory">
				<option value="0">全部</option>
				<?php if($categorys):?>
				<?php 
					foreach ($categorys['0000000000'] as $cate):
					$lid = $cate['lid'];
				?>
				<optgroup label="<?php echo $cate['category_name'];?>">
					<?php if($categorys[$lid]):?>
					<?php foreach ($categorys[$lid] as $ca):?>
					<option value="<?php echo $ca['lid'];?>"><?php echo $ca['category_name'];?></option>
					<?php endforeach;?>
					<?php endif;?>
				</optgroup>
				<?php endforeach;?>
				<?php endif;?>
			</select>
		</div>
		<ul class="ul">
			<li class="li-4">品项名称</li>
			<li class="li-4">入库库存</li>
			<li class="li-4">单位规格</li>
			<li class="li-4">入库进价</li>
			<div style="clear: both;"></div>
		</ul>
	</div>
	<div class="dp1">
	<?php 
		foreach($materials as $material):
		$materialUnit = Common::getmaterialUnit($material['lid'], $material['dpid'], 0);
	?>
		<ul class="ul" c-id="<?php echo $material['category_id'];?>" m-id="<?php echo $material['lid'];?>">
			<li class="li-4"><?php echo $material['material_name']?></li>
			<li class="li-4"><input placeholder="输入入库库存" value="0.00"/></li>
			<li class="li-4"><?php echo $materialUnit['unit_specifications'];?></li>
			<li class="li-4"><input placeholder="输入入库价格" value="0.00"/></li>
			<div style="clear: both;"></div>
		</ul>
		<?php endforeach;?>
	</div>
</div>
<script src="<?php echo $basePath;?>/js/mall/jquery-1.11.0.min.js"></script>
<script>
$(document).ready(function(){
	$('select[name="mcategory"]').change(function(){
		var v = $(this).val();
		alert(v);
	});
});
</script>