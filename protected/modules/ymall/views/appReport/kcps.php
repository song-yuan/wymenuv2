<?php 
	$basePath = Yii::app()->baseUrl;
?>
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/app.css">
<script src="<?php echo $basePath;?>/js/mall/jquery-1.11.0.min.js"></script>
<header class="mui-bar mui-bar-nav">
	<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$this->companyId));?>"></a>
	<h1 class="mui-title">库存盘损</h1>
	<a id="submit" class="mui-icon mui-pull-right" href="javascript:;">一键盘损</a>
</header>
<form class="mui-input-group" method="POST" onsubmit="return validate()" action="<?php echo $this->createUrl('appReport/ajaxKcps',array('companyId'=>$this->companyId));?>">
<div class="sd">
	<div class="mui-input-row">
		<label>操作员</label>
		<select id="opretion_id" name="Inventory[opretion_id]">
			<?php foreach ($users as $user):?>
			<option value="<?php echo $user['username'];?>"><?php echo $user['username'];?></option>
			<?php endforeach;?>
		</select>
	</div>
	<div class="mui-input-row">
		<label>盘损原因</label>
		<select id="reason_id" name="Inventory[reason_id]">
			<?php foreach ($reasons as $reason):?>
			<option value="<?php echo $reason['lid'];?>"><?php echo $reason['name'];?></option>
			<?php endforeach;?>
		</select>
	</div>
	<div class="mui-input-row" style="height:auto;">
		<label>备注说明</label>
		<textarea id="remark" name="Inventory[remark]" rows="5" placeholder="输入说明"></textarea>
	</div>
</div>
<div class="dp">
	<div style="margin-top: 10px;">
		<div class="mui-input-row">
			<label>分类类型</label>
			<select id="selcatetype">
				<option value="0" >全部</option>
				<option value="1" >原料</option>
				<option value="2" >成品</option>
			</select>
		</div>
		<div class="mui-input-row">
			<label>选择分类</label>
			<select id="mcategory">
				<option value="0">全部</option>
				<?php if($categorys):?>
				<?php 
					foreach ($categorys['0000000000'] as $cate):
					$lid = $cate['lid'];
				?>
				<optgroup im="1" label="<?php echo $cate['category_name'];?>">
					<?php if($categorys[$lid]):?>
					<?php foreach ($categorys[$lid] as $ca):?>
					<option im="1" value="<?php echo $ca['lid'];?>"><?php echo $ca['category_name'];?></option>
					<?php endforeach;?>
					<?php endif;?>
				</optgroup>
				<?php endforeach;?>
				<?php endif;?>
				
				<?php if($pcategorys):?>
				<?php 
					foreach ($pcategorys['0000000000'] as $pro):
					$lid = $pro['lid'];
				?>
				<optgroup im="2" label="<?php echo $pro['category_name'];?>">
					<?php if($pcategorys[$lid]):?>
					<?php foreach ($pcategorys[$lid] as $pca):?>
					<option im="2" value="<?php echo $pca['lid'];?>"><?php echo $pca['category_name'];?></option>
					<?php endforeach;?>
					<?php endif;?>
				</optgroup>
				<?php endforeach;?>
				<?php endif;?>
			</select>
		</div>
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
		foreach($materials as $material):
		$materialUnit = Common::getmaterialUnit($material['lid'], $material['dpid'], 0);
	?>
		<ul class="ul" im="1" c-id="<?php echo $material['category_id'];?>" m-id="<?php echo $material['lid'];?>">
			<li class="li-4"><?php echo $material['material_name'];?></li>
			<li class="li-4"><?php echo $materialUnit['unit_name'];?></li>
			<li class="li-4"><?php echo $materialUnit['unit_specifications'];?></li>
			<li class="li-4"><input class="stock" name="material[<?php echo $material['lid'];?>][number]" placeholder="输入盘损数量" value=""/></li>
			<div style="clear: both;"></div>
		</ul>
		<?php endforeach;?>
		<?php 
		foreach($products as $product):
	?>
		<ul class="ul" im="2" c-id="<?php echo $product['category_id'];?>" m-id="<?php echo $product['lid'];?>">
			<li class="li-4"><?php echo $product['product_name'];?></li>
			<li class="li-4">个</li>
			<li class="li-4">个</li>
			<li class="li-4"><input class="stock" name="product[<?php echo $product['lid'];?>][number]" placeholder="输入盘损数量" value=""/></li>
			<div style="clear: both;"></div>
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
		mui.alert('请输入盘损库存','提示');
	}
	return re;
}
$(document).ready(function(){
	$('#selcatetype').change(function(){
		var v = $(this).val();
		if(v=='0'){
			$('.dp1 .ul').show();
			$('#mcategory').find('optgroup').show();
		}else{
			$('.dp1 .ul').hide();
			$('#mcategory').find('optgroup').hide();
			$('#mcategory').find('optgroup[im="'+v+'"]').show();
			$('.dp1 .ul[im="'+v+'"]').show();
		}
	});
	$('#mcategory').change(function(){
		var v = $('#selcatetype').val();
		var s = $(this).val();
		if(s=='0'){
			$('.dp1 .ul').show();
		}else{
			if(v=='0'){
				v = $(this).find('option:selected').attr('im');
			}
			$('.dp1 .ul').hide();
			$('.dp1 .ul[im="'+v+'"][c-id="'+s+'"]').show();
		}
	});
	$('#submit').click(function(){
		$('form').submit();
	});
});
</script>