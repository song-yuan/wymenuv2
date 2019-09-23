<?php $basePath = Yii::app()->baseUrl;?>
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/app.css">
<script src="<?php echo $basePath;?>/js/mall/jquery-1.11.0.min.js"></script>
<header class="mui-bar mui-bar-nav">
		<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$this->companyId));?>"></a>
		<h1 class="mui-title">入库详情</h1>
		<?php if($model->status==0):?>
		<a class="mui-icon mui-pull-right" href="<?php echo $this->createUrl('appReport/createRkdd',array('companyId'=>$this->companyId,'id'=>$model->lid));?>">修改</a>
		<?php endif;?>
</header>
<div class="sd">
	<ul class="mui-table-view">
		<li class="mui-table-view-cell">
			<span>入库单号</span> <span><?php echo $model->storage_account_no;?></span>
		</li>
		<li class="mui-table-view-cell">
			<span>状态</span> 
			<?php
				if($model->status==0){
					echo '<span style="color:red;">可编辑</span><span class="storage mui-btn mui-btn-primary mui-pull-right" s-id="'.$model->lid.'">确认入库</span>';
				}elseif($model->status==1){
					echo '<span style="color:red;">审核通过</span>';
				}elseif($model->status==2){
					echo '<span style="color:red;">审核失败</span>';
				}elseif($model->status==3){
					echo '<span style="color:red;">已入库</span>';
				}elseif($model->status==4){
					echo '<span style="color:red;">送审中</span>';
				}
			?>
		</li>
	</ul>
</div>
<div class="dp">
	<div style="margin-top: 10px;">
		<ul class="ul">
			<li class="li-5">品项名称</li>
			<li class="li-5">单位规格</li>
			<li class="li-5">入库数量</li>
			<li class="li-5">赠品数量</li>
			<li class="li-5">入库价格</li>
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
			<li class="li-5"><?php echo $detail->stock;?></li>
			<li class="li-5"><?php echo $detail->free_stock;?></li>
			<li class="li-5"><?php echo $detail->price;?></li>
			<div style="clear: both;"></div>
		</ul>
		<?php endforeach;?>
	</div>
</div>
<script>
$(document).ready(function(){
	$('.storage').click(function(){
		var sid = $(this).attr('s-id');
		var btnArray = ['是', '否'];
		mui.confirm('是否确认要入库', '提示', btnArray, function(e) {
			if (e.index == 0) {
				$.ajax({
					url:"<?php echo $this->createUrl('appReport/store',array('companyId'=>$this->companyId));?>",
					data:{sid:sid},
					success:function(msg){
						if(msg=='true'){
						   	mui.alert('入库成功!','提示');	
						}else{
							mui.alert('入库失败!','提示');
						}
						history.go(0);
					}
				});
			}
		});
	});
});
</script>