<?php 
	$basePath = Yii::app()->baseUrl;
?>
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/app.css">
<header class="mui-bar mui-bar-nav">
	<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" href="<?php echo $this->createUrl('appReport/kcpd',array('companyId'=>$this->companyId));?>"></a>
	<h1 class="mui-title">盘点详情</h1>
</header>
<form class="mui-input-group">
<div class="sd">
	<div class="mui-input-row">
		<label>盘点时间</label>
		<label style="width:65%;"><?php echo $model->create_at;?></label>
	</div>
	<div class="mui-input-row">
		<label>盘点类型</label>
		<?php if($model->type==1):?>
		<label>日盘</label>
		<?php elseif($model->type==2):?>
		<label>周盘</label>
		<?php else:?>
		<label>月盘</label>
		<?php endif;?>
	</div>
	<div class="mui-input-row">
		<label>盘点人</label>
		<label><?php echo $model->username;?></label>
	</div>
</div>
<div class="dp">
	<div style="margin-top: 10px;">
		<ul class="ul">
			<li class="li-5">品项名称</li>
			<li class="li-5">单位规格</li>
			<li class="li-5">单位名称</li>
			<li class="li-5">盘点库存</li>
			<li class="li-5">库存差异</li>
			<div style="clear: both;"></div>
		</ul>
	</div>
	<div class="dp1">
	<?php 
		foreach($models as $m):
		$materialUnit = Common::getmaterialUnit($m['material_id'], $m['dpid'], 0);
	?>
		<ul class="ul">
			<li class="li-5"><?php echo $materialUnit['material_name'];?></li>
			<li class="li-5"><?php echo $materialUnit['unit_name'];?></li>
			<li class="li-5"><?php echo $materialUnit['unit_specifications'];?></li>
			<li class="li-5"><?php echo $m['taking_stock'];?></li>
			<li class="li-5">
			<?php if($m['number']<0){
				echo '<span style="color:red">'.$m['number'].'</span>';
			}elseif($m['number']>0){
				echo '<span style="color:blue">'.$m['number'].'</span>';
			}else{
				echo $m['number'];
			}?>
			</li>
			<div style="clear: both;"></div>
		</ul>
		<?php endforeach;?>
	</div>
</div>
</form>