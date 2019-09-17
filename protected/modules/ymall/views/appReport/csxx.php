<?php $basePath = Yii::app()->baseUrl;?>
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/app.css">
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/mui.picker.min.css" />
<header class="mui-bar mui-bar-nav">
	<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$this->companyId));?>"></a>
	<h1 class="mui-title">厂商分类</h1>
</header>
<div class="sd">
	
</div>
<div class="dp">
	<div style="margin-top: 10px;border-bottom: 1px solid #efeff4;">
		<ul class="ul">
			<li class="li-4">厂商名称</li>
			<li class="li-4">联系人</li>
			<li class="li-4">电话</li>
			<li class="li-4"><a href="<?php echo $this->createUrl('appReport/createCsxx',array('companyId'=>$this->companyId));?>">添加</a></li>
			<div style="clear: both;"></div>
		</ul>
	</div>
	<div class="dp1">
		<ul class="ul">
			<?php foreach ($models as $model):?>
			<li class="li-4"><?php echo $model['manufacturer_name'];?></li>
			<li class="li-4"><?php echo $model['contact_name'];?></li>
			<li class="li-4"><?php echo $model['contact_tel'];?></li>
			<li class="li-4"><a href="<?php echo $this->createUrl('appReport/createCsxx',array('companyId'=>$this->companyId,'id'=>$model['lid']));?>">编辑</a>&nbsp;&nbsp;<a class="delete" onclick="deleteItem('<?php echo $model['lid'];?>')">删除</a></li>
			<?php endforeach;?>
		</ul>
	</div>
</div>
<script>
function deleteItem(id){
	var btnArray = ['是', '否'];
	mui.confirm('是否删除该厂商分类', '提示', btnArray, function(e) {
		if (e.index == 0) {
			location.href = "<?php echo $this->createUrl('appReport/deleteCsxx',array('companyId'=>$this->companyId));?>/id/"+id;
		}
	});
}
</script>