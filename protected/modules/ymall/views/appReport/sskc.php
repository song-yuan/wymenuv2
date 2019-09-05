<?php $basePath = Yii::app()->baseUrl;?>
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/app.css">
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/mui.picker.min.css" />
<header class="mui-bar mui-bar-nav">
	<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$this->companyId));?>"></a>
	<h1 class="mui-title">原料实时库存</h1>
</header>
<div class="sd">
	<form class="mui-input-group" method="post">
	 	<div class="mui-input-row"></div>
		<div class="mui-input-row">
			<label>原料类别</label>
			<select name="cid">
				<option value="0">全部分类</option>
				<?php if(!empty($categorys)):?>
				<?php foreach ($categorys['lid-0000000000'] as $category):?>
				<optgroup label="<?php echo $category['category_name']?>">
				<?php if(!empty($categorys['lid-'.$category['lid']])):?>
				<?php foreach ($categorys['lid-'.$category['lid']] as $c):?>
				<option value="<?php echo $c['lid'];?>" <?php if($c['lid']==$categoryId){echo 'selected';}?>><?php echo $c['category_name'];?></option>
				<?php endforeach;?>
				<?php endif;?>
				</optgroup>
				<?php endforeach;?>
				<?php endif;?>
			</select>
		</div>
		<div class="mui-input-row"></div>
		<ul class="mui-table-view">
			<li>
				<button type="submit" class="mui-btn mui-btn-primary mui-btn-block">查询</button>
			</li>
		</ul>
	</form>
</div>
<div class="dp">
	<div style="margin-top: 10px;">
		<ul class="ul">
			<li class="li-4">原料分类</li>
			<li class="li-4">原料名称</li>
			<li class="li-4">实时库存</li>
			<li class="li-4">库存单位</li>
		</ul>
	</div>
	<div style="clear: both;"></div>
	<div class="dp1">
		<ul class="ul">
			<?php 
				foreach ($models as $model):
				$stock = ProductMaterial::getJitStock($model['lid'],$model['dpid']);
				$unitname =  Common::getStockName($model['sales_unit_id']);
			?>
			<li class="li-4"><?php echo $model['category_name'];?></li>
			<li class="li-4"><?php echo $model['material_name'];?></li>
			<li class="li-4"><?php if($stock <= 0){ echo '<span style="color:red;">'.$stock.'</span>';}else{echo $stock;}?></li>
			<li class="li-4"><?php echo $unitname;?></li>
			<?php endforeach;?>
		</ul>
	</div>
</div>