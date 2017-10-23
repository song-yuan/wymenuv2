<?php $basePath = Yii::app()->baseUrl;?>
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/app.css">
<header class="mui-bar mui-bar-nav">
		<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$this->companyId));?>"></a>
		<h1 class="mui-title">原料消耗报表</h1>
</header>
<div class="dp">
	<div class="sd">
		<form method="post">
			<ul class="mui-table-view">
				<li class="mui-table-view-cell">
					<span>门店</span>
					<span style="padding-left: 165px;"><?php echo Helper::getCompanyName($this->companyId);?></span>
				</li>
				<li class="mui-table-view-cell">
					<span>开始时间</span>
					<span id='demo2' style="padding-left: 135px;" data-options='{"type":"date"}' class="btn mui-navigate-right"><?php if(empty($date)){?>选择日期<?php }else{echo $date['start'];}?></span>
					<input id="date1" type="hidden" name="date[start]">
				</li>
				<li class="mui-table-view-cell">
					<span>结束时间</span>
					<span id='demo4' style="padding-left: 135px;" data-options='{"type":"date"}' class="btn mui-navigate-right"><?php if(empty($date)){?>选择日期<?php }else{echo $date['End'];}?></span>
					<input id="date2" type="hidden" name="date[End]">
				</li>
				<li>
					<button type="submit" class="mui-btn mui-btn-primary mui-btn-block">查询</button>
				</li>
			</ul>
		</form>
	</div>
	<ul class="mui-table-view">
	  	<li class="mui-table-view-cell">原料名称<span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;">原料名称</span></li>
	  	<div class="dp1">
	  	<?php foreach($materials as $material):?>
	  		<li class="mui-table-view-cell"><?php echo $material['material_name'];?><span class="mui-badge mui-badge-inverted" style="font-size: 18px;color: #000;"><?php echo $material['stock_num'];?></span></li>
	  	<?php endforeach;?>
	  	</div>
	 </ul>
</div>