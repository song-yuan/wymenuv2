<?php $basePath = Yii::app()->baseUrl;?>
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/app.css">
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/mui.picker.min.css">
<header class="mui-bar mui-bar-nav">
		<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$this->companyId));?>"></a>
		<h1 class="mui-title">入库详情</h1>
		<?php if($model->status==0):?>
		<a class="mui-icon mui-pull-right" href="<?php echo $this->createUrl('appReport/createRkdd',array('companyId'=>$this->companyId));?>">新增</a>
		<?php endif;?>
</header>
<div class="sd">
	<ul class="mui-table-view">
		<li class="mui-table-view-cell">
			<span>入库单号</span> <span><?php echo $model->storage_account_no;?></span>
		</li>
		<li class="mui-table-view-cell">
			<span>状态</span> <span style="color:red;">
			<?php 
				if($model->status==0){ 
					echo '可编辑';
				}elseif($model->status==1){ 
					echo '审核通过';
				}elseif($model->status==2){
					echo '审核失败';
				}elseif($model->status==3){
					echo '已入库';
				}elseif($model->status==4){
					echo '送审中';
				}
			?>
			</span>
		</li>
	</ul>
</div>
<div class="dp">
	<div style="margin-top: 10px;">
		<ul class="ul">
			<li class="li-5">品项名称</li>
			<li class="li-5">单位规格</li>
			<li class="li-5">入库价格</li>
			<li class="li-5">入库数量</li>
			<li class="li-5">赠品数量</li>
			<div style="clear: both;"></div>
		</ul>
	</div>
	<div class="dp1">
		<?php 
			foreach ($details as $detail):
			$materialUnit = Common::getmaterialUnit($detail->material_id, $detail->dpid, 0);
		?>
		<ul class="ul">
			<li class="li-5"><?php echo $materialUnit['material_name'];?></li>
			<li class="li-5"><?php echo $materialUnit['unit_specifications'];?></li>
			<li class="li-5"><?php echo $detail->price;?></li>
			<li class="li-5"><?php echo $detail->stock;?></li>
			<li class="li-5"><?php echo $detail->free_stock;?></li>
			<div style="clear: both;"></div>
		</ul>
		<?php endforeach;?>
	</div>
</div>