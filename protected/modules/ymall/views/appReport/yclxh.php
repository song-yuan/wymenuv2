<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/app.css">
<header class="mui-bar mui-bar-nav">
		<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$this->companyId));?>"></a>
		<h1 class="mui-title">原料消耗报表</h1>
</header>
<div class="dp">
	<div class="sd">
		<form method="post">
			<table cellpadding="0" cellspacing="0" width="100%" class="tr2">
				<tr>
					<td class="tb2">门店</td>
					<td class="tb3"><span class="span"><?php echo Helper::getCompanyName($this->companyId);?></span></td>
				</tr>
				<tr>
					<td class="tb2">开始时间</td>
					<td class="tb3">
						<input class="date" type="date" value="<?php echo $date['start'];?>" name="date[start]">
						
					</td>
				</tr>
				<tr>
					<td class="tb2">结束时间</td>
					<td class="tb3"><input class="date" type="date" name="date[End]" value="<?php echo $date['End'];?>"></td>
				</tr>
				<tr class="tr2">
					<td colspan="2"><input type="submit" value="查询"></td>
				</tr>
			</table>
		</form>
	</div>
	<table cellpadding="0" cellspacing="0" width="100%">
		<tr class="tr3">
			<td class="td" width="50%">原料名称</td>
			<td class="td2" width="50%">消耗量</td>
		</tr>
	</table>
	<div class="dp1">
		<table cellpadding="0" cellspacing="0" width="100%">
			<?php foreach($materials as $material):?>
				<tr class="tr3">
					<td class="td" width="50%"><?php echo $material['material_name'];?></td>
					<td  class="td2"><?php echo $material['stock_num'];?></td>
				</tr>
			<?php endforeach;?>
		</table>
	</div>
</div>