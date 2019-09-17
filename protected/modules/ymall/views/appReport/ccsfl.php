<?php $basePath = Yii::app()->baseUrl;?>
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/app.css">
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/mui.picker.min.css" />
<script src="<?php echo $basePath;?>/js/mall/jquery-1.9.1.min.js"></script>
<header class="mui-bar mui-bar-nav">
	<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$this->companyId));?>"></a>
	<h1 class="mui-title">添加厂商分类</h1>
</header>
<div class="sd">
	
</div>
<div class="dp">
	<form class="mui-input-group" method="POST" onsubmit="return validate()">
		<div class="mui-input-row">
			<label>类型名称</label>
			<input id="name" type="text" name="ManufacturerClassification[classification_name]" placeholder="输入类型名称" value="<?php echo $model->classification_name;?>">
		</div>
		<div class="mui-input-row" style="height:auto;">
			<label>说明</label>
			<textarea id="remark" name="ManufacturerClassification[remark]" rows="5" placeholder="输入说明"><?php echo $model->remark;?></textarea>
		</div>
		<div class="mui-button-row">
			<button type="submit" class="mui-btn mui-btn-primary">确认</button>&nbsp;&nbsp;
			<button type="button" class="mui-btn mui-btn-danger" onclick="back()">取消</button>
		</div>
	</form>
</div>
<script>
function validate(){
	var name = $('#name').val();
	if(name==''){
		mui.alert('类型名称不能为空', '提示');
		return false;
	}
	return true;
}
function back(){
	location.href = "<?php echo $this->createUrl('appReport/csfl',array('companyId'=>$this->companyId));?>";
}
</script>