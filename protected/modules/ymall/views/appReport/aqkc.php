<?php 
$basePath = Yii::app()->baseUrl;
?>

<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/app.css">
<header class="mui-bar mui-bar-nav">
		<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$this->companyId));?>"></a>
		<h1 class="mui-title">安全库存设置</h1>
</header>

<div class="sd">
	<form class="mui-input-group" method="post">
		<div class="mui-input-row">
			<label>日均销量天数</label>
			<input type="text" name="m[dsales_day]" placeholder="输入天数" value="<?php echo $model['dsales_day'];?>">
		</div>
		<div class="mui-input-row"> 
			<p style="margin-left:20px;">日均销量 = 最近 *天的日均销量  *表示前面设置的天数</p>
		</div>
		<div class="mui-input-row">
			<label>最小天数</label>
			<input type="text" name="m[dsafe_min_day]" placeholder="输入天数" value="<?php echo $model['dsafe_min_day'];?>">
		</div>
		<div class="mui-input-row">
			<label>最大天数</label>
			<input type="text" name="m[dsafe_max_day]" placeholder="输入天数" value="<?php echo $model['dsafe_max_day'];?>">
		</div>
		<div class="mui-input-row" style="height:auto;"> 
			<p style="margin-left:20px;">日均销量  × *天 < 安全库存范围  < 日均销量 × *天  *表示前面设置的最小、最大天数</p>
		</div>
		<ul class="mui-table-view">
			<li>
				<button type="submit" class="mui-btn mui-btn-primary mui-btn-block">确定</button>
			</li>
		</ul>
	</form>
</div>