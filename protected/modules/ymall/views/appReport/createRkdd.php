<?php 
	$basePath = Yii::app()->baseUrl;
?>
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/app.css">
<script src="<?php echo $basePath;?>/js/mall/jquery-1.11.0.min.js"></script>
<header class="mui-bar mui-bar-nav">
	<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$this->companyId));?>"></a>
	<?php if($model):?>
	<h1 class="mui-title">更新入库订单</h1>
	<?php else:?>
	<h1 class="mui-title">新增入库订单</h1>
	<?php endif;?>
	<a id="submit" class="mui-icon mui-pull-right" href="javascript:;">保存</a>
</header>
<form class="mui-input-group" method="POST" onsubmit="return validate()">
<div class="sd">
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
		$stock = '0.00';
		$price = '0.00';
		if(isset($details[$material['lid']])){
			foreach ($details[$material['lid']] as $de){
				$s = $de['stock'];
				$p = $de['price'];
				$stock += $s;
				$price += $p;
			}
		}
		$materialUnit = Common::getmaterialUnit($material['lid'], $material['dpid'], 0);
	?>
		<ul class="ul" c-id="<?php echo $material['category_id'];?>" m-id="<?php echo $material['lid'];?>">
			<li class="li-4"><?php echo $material['material_name'];?></li>
			<li class="li-4"><input class="stock" name="material[<?php echo $material['lid'];?>][stock]" placeholder="输入入库库存" value="<?php echo $stock;?>"/></li>
			<li class="li-4"><?php echo $materialUnit['unit_specifications'];?></li>
			<li class="li-4"><input class="price" name="material[<?php echo $material['lid'];?>][price]" placeholder="输入入库价格" value="<?php echo $price;?>"/></li>
			<div style="clear: both;"></div>
			<input name="material[<?php echo $material['lid'];?>][mphs_code]" type="hidden" value="<?php echo $material['mphs_code'];?>"/>
		</ul>
		<?php endforeach;?>
	</div>
</div>
</form>
<script>
function validate(){
	var re = false;
	$('.dp1').find('.ul').each(function(){
		var stock = $(this).find('.stock').val();
		if(parseInt(stock)>0){
			re = true;
		}
	});
	if(!re){
		mui.alert('请输入库存','提示');
	}
	return re;
}
$(document).ready(function(){
	$('select[name="mcategory"]').change(function(){
		var v = $(this).val();
		if(v=='0'){
			$('.dp1 .ul').show();
		}else{
			$('.dp1 .ul').hide();
			$('.dp1 .ul[c-id="'+v+'"]').show();
		}
	});
	$('#submit').click(function(){
		$('form').submit();
	});
});
</script>