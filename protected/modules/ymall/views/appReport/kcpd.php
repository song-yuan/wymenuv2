<?php 
	$basePath = Yii::app()->baseUrl;
?>
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/app.css">
<script src="<?php echo $basePath;?>/js/mall/jquery-1.11.0.min.js"></script>
<header class="mui-bar mui-bar-nav">
	<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$this->companyId));?>"></a>
	<h1 class="mui-title">库存盘点</h1>
	<a id="submit" class="mui-icon mui-pull-right" href="javascript:;">一键盘点</a>
</header>
<form class="mui-input-group" method="POST" onsubmit="return validate()" action="<?php echo $this->createUrl('appReport/ajaxKcpd',array('companyId'=>$this->companyId));?>">
<div class="sd">
	<div class="mui-input-row">
		<label>盘点类型</label>
		<select name="type">
			<option value="0">请选择</option>
			<option value="1">日盘</option>
			<option value="2">周盘</option>
			<option value="3">月盘</option>
		</select>
	</div>
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
</div>
<div class="dp">
	<div style="margin-top: 10px;">
		<ul class="ul">
			<li class="li-4">品项名称</li>
			<li class="li-2">盘点库存</li>
			<div style="clear: both;"></div>
		</ul>
	</div>
	<div class="dp1" style="height:570px;">
	<?php 
		foreach($models as $model):
	?>
		<ul class="ul" c-id="<?php echo $model['category_id'];?>" m-id="<?php echo $model['lid'];?>">
			<li class="li-4"><?php echo $model['material_name'];?></li>
			<li class="li-2">
				<input class="kucundiv-left" type="text" style="width:100px;" name="material[<?php echo $model['lid'];?>][inventory_stock]" value="<?php echo $model['inventory_stock'];?>" stockid="0" onfocus=" if (value =='0.00'){value = '0.00'}" onblur="if (value ==''){value=''}"  onkeyup="if(isNaN(value))execCommand('undo')" onafterpaste="if(isNaN(value))execCommand('undo')" placeholder="库存大单位">
				<span><?php echo $model['unit_name'];?></span>
				<input class="kucundiv-right" type="text" style="width:100px;" name="material[<?php echo $model['lid'];?>][inventory_sales]" value="<?php echo $model['inventory_sales'];?>" stockid="0" onfocus=" if (value =='0.00'){value = '0.00'}" onblur="if (value ==''){value=''}"  onkeyup="if(isNaN(value))execCommand('undo')" onafterpaste="if(isNaN(value))execCommand('undo')" placeholder="零售小单位">
				<?php echo $model['sales_name'];?>
			</li>
			<div style="clear: both;"></div>
			<input type="hidden" name="material[<?php echo $model['lid'];?>][ratio]" value="<?php echo $this->getRatio($model['mu_lid'],$model['ms_lid']);?>"/>
			<input type="hidden" name="material[<?php echo $model['lid'];?>][origin-num]" value="<?php  echo $model['stock_all'];?>"/>
			<input type="hidden" name="material[<?php echo $model['lid'];?>][sales_name]" value="<?php  echo $model['sales_name'];?>"/>
		</ul>
		<?php endforeach;?>
	</div>
</div>
</form>
<script>
function validate(){
	var re = false;
	var type = $('select[name="type"]').val();
	if(type=='0'){
		mui.alert('请选择盘点类型','提示');
		return re;
	}
	$('.dp1').find('.ul').each(function(){
		var stock = $(this).find('.kucundiv-left').val();
		var salse = $(this).find('.kucundiv-right').val();
		if(parseInt(stock)>0||parseInt(salse)){
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