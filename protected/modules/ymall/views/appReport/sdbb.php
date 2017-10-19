<?php


/* @var $this \yii\web\View */
/* @var $content string */
$basePath = yii::app()->baseUrl;
$ris = array();
foreach($riq as $ri){ 
	array_push($ris,$ri['pay_amount']);
}
if (!empty($ris)){
	$pos=array_search(max($ris),$ris);
}else{
	$pos = 0;
}


?>
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/app.css">
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/mui.css">
<script src="<?php echo $basePath;?>/js/mall/jquery-1.9.1.min.js"></script>
		<style>
			.chart {
				height: 200px;
				margin: 0px;
				padding: 0px;
			}
			h5 {
				margin-top: 30px;
				font-weight: bold;
			}
			h5:first-child {
				margin-top: 15px;
			}
		</style>
<script src="../../../../js/appReport/mui.min.js"></script>
<div class="yy">
	<header class="mui-bar mui-bar-nav">
		<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$this->companyId));?>"></a>
		<h1 class="mui-title">时段报表</h1>
	</header>
	<div class="sd">
		<form method="post">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr class="tr2">
					<td class="tb2">门店</td>
					<td class="tb3"><span class="span"><?php echo Helper::getCompanyName($this->companyId);?></span></td>
				</tr>
				<tr class="tr2">
					<td class="tb2">选择时间</td>
					<td class="tb3"><input class="date" type="date" name="date" value="<?php echo $date?>"></td>
				</tr>
				<tr class="tr2">
					<td colspan="2"><input type="submit" value="查询"></td>
				</tr>
			</table>
		</form>
	</div>

 <?php if(!empty($riq)):?>
<!-- 为ECharts准备一个具备大小（宽高）的Dom -->
<div id="tu" style="background-color: #FFFFFF;">
	<div class="mui-content">
		<div class="mui-content-padded">
			<div class="chart" id="lineChart"></div>
		</div>
	</div>
</div>
    <!-- ECharts单文件引入 -->
<script src="../../../../js/appReport/echarts-all.js"></script>
		<script>
			var getOption = function(chartType) {
				var chartOption = chartType == 'pie' ? {
					calculable: false,
					series: [{
						name: '访问来源',
						type: 'pie',
						radius: '65%',
						center: ['50%', '50%'],
						data: [{
							value: 335,
							name: '直接访问'
						}, {
							value: 310,
							name: '邮件营销'
						}, {
							value: 234,
							name: '联盟广告'
						}, {
							value: 135,
							name: '视频广告'
						}, {
							value: 1548,
							name: '搜索引擎'
						}]
					}]
				} : {
					legend: {
						data: ['订单数', '营业额']
					},
					grid: {
						x: 35,
						x2: 10,
						y: 30,
						y2: 25
					},
					toolbox: {
						show: false,
						feature: {
							mark: {
								show: true
							},
							dataView: {
								show: true,
								readOnly: false
							},
							magicType: {
								show: true,
								type: ['line', 'bar']
							},
							restore: {
								show: true
							},
							saveAsImage: {
								show: true
							}
						}
					},
					calculable: false,
					xAxis: [{
						type: 'category',
						data: [<?php foreach ($riq as $ri) {
							$hour = $ri['hour'];
							echo"'$hour:00',";
						}?>]
					}],
					yAxis: [{
						type: 'value',
						splitArea: {
							show: true
						}
					}],
					series: [{
						name: '订单数',
						type: chartType,
						data: [<?php foreach ($riq as $ri) {
							 echo $ri['count'].",";
						}?>]
					}, {
						name: '营业额',
						type: chartType,
						data: [<?php foreach ($riq as $ri) {
							 echo $ri['pay_amount'].",";
						}?>]
					}]
				};
				return chartOption;
			};
			var byId = function(id) {
				return document.getElementById(id);
			};
			var lineChart = echarts.init(byId('lineChart'));
			lineChart.setOption(getOption('line'));
			byId("echarts").addEventListener('tap',function(){
				var url = this.getAttribute('data-url');
				plus.runtime.openURL(url);
			},false);
		</script>
<?php endif;?>
    <?php if(!empty($riq)):?>
    <div id="biao">
    	<div style="background-color: #FFFFFF;">
    	<table cellpadding="0" cellspacing="0" style="border-bottom: none;text-align: center;" width="100%">
    		<tr>
				<td width="34%">时段</td>
				<td width="33%">订单数</td>
				<td width="33%">营业额</td>
			</tr>
    	</table>
    </div>
    <div style="width: 102%;overflow: auto;height: 200px;background-color: #FFFFFF;">
    	<table cellpadding="0" cellspacing="0" width="100%" style="text-align: center;">

				<?php foreach($riq as $ri){?>
				<tr>
					<td width="34%"><?php echo $ri['hour'].":00";?></td>
					<td width="33%"><?php echo $ri['count'];?></td>
					<td width="33%"><?php echo $ri['pay_amount'];?></td>
				</tr>
				<?php }?>
			</table>
		</div>
    </div>
<?php endif;?>
</div>