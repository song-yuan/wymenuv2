<?php


/* @var $this \yii\web\View */
/* @var $content string */
$basePath = Yii::app()->baseUrl;
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
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/mui.picker.min.css">
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
<div class="yy">
	<header class="mui-bar mui-bar-nav">
		<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$this->companyId));?>"></a>
		<h1 class="mui-title">时段报表</h1>
	</header>
	<div class="sd">
		<form method="post">
			<ul class="mui-table-view">
				<li class="mui-table-view-cell">
					<span>门店</span>
					<span style="padding-left: 135px;"><?php if(empty($type)){echo Helper::getCompanyName($this->companyId);}else{echo $type['group_name'];}?></span>
				</li>
				<li class="mui-table-view-cell">
					<span>开始时间</span>
					<span id='demo2' style="padding-left: 95px;" data-options='{"type":"date"}' class="btn mui-navigate-right"><?php if(empty($date)){?><a>选择日期</a><?php }else{echo $date;}?></span>
					<input id="date1" type="hidden" name="date" value="<?php echo $date;?>">
				</li>
				<li>
					<button type="submit" class="mui-btn mui-btn-primary mui-btn-block">查询</button>
				</li>
			</ul>
		</form>
	</div>
 <?php if(!empty($riq)):?>
<!-- 为ECharts准备一个具备大小（宽高）的Dom -->
<div id="tu">
	<div class="mui-content">
		<div class="mui-content-padded">
			<div class="chart" id="lineChart"></div>
		</div>
	</div>
</div>
    <!-- ECharts单文件引入 -->
<script src="<?php echo $basePath;?>/js/appreport/echarts-all.js"></script>
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
    <div style="width: 100%;overflow: auto;height: 200px;background-color: #FFFFFF;">
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
<script src="<?php echo $basePath;?>/js/appreport/mui.picker.min.js"></script>
<script>
	(function($) {
		$.init();
		var btns = $('.btn');
		// alert(btns.length);
		btns.each(function(i, btn) {
			btn.addEventListener('tap', function() {
				var optionsJson = this.getAttribute('data-options') || '{}';
				var options = JSON.parse(optionsJson);
				var id = this.getAttribute('id');
				// alert(id);
				/*
				 * 首次显示时实例化组件
				 * 示例为了简洁，将 options 放在了按钮的 dom 上
				 * 也可以直接通过代码声明 optinos 用于实例化 DtPicker
				 */
				var picker = new $.DtPicker(options);
				picker.show(function(rs) {
					/*
					 * rs.value 拼合后的 value
					 * rs.text 拼合后的 text
					 * rs.y 年，可以通过 rs.y.vaue 和 rs.y.text 获取值和文本
					 * rs.m 月，用法同年
					 * rs.d 日，用法同年
					 * rs.h 时，用法同年
					 * rs.i 分（minutes 的第二个字母），用法同年
					 */
					 // alert(rs.text);
					 // $('#'+id).html(rs.text);
					$('#'+id)[0].innerText = rs.text;

					/* 
					 * 返回 false 可以阻止选择框的关闭
					 * return false;
					 */
					/*
					 * 释放组件资源，释放后将将不能再操作组件
					 * 通常情况下，不需要示放组件，new DtPicker(options) 后，可以一直使用。
					 * 当前示例，因为内容较多，如不进行资原释放，在某些设备上会较慢。
					 * 所以每次用完便立即调用 dispose 进行释放，下次用时再创建新实例。
					 */
					picker.dispose();
					var date1 = document.getElementById("demo2").innerText;
					document.getElementById("date1").value = date1;
				});
			}, false);
		});

	})(mui);
</script>