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
<script src="http://echarts.baidu.com/build/dist/echarts-all.js"></script>
<script src="<?php echo $basePath;?>/js/mall/jquery-1.9.1.min.js"></script>
<style type="text/css">
	body{
		margin: 0px;
		padding: 0px;
		background-color: #CCCCCC;
		width: 100%;
	}
</style>

<div>
	<div style="background-color: #FFFFFF;margin-bottom: 10px;line-height: 30px;">
		<h4 style="text-align: center;">时段报表</h4>
		<form action="" method="post">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr style="text-align: center;">
					<td>门店</td>
					<td><input type="hidden" name="dpname" value="0000000009" style="width: 50%;border: none;padding-top: 25px;"/>上海斗石</td>
				</tr>
				<tr style="text-align: center;">
					<td>区域</td>
					<td>全部</td>
				</tr>
				<tr>
					<td style="padding-left:20%;">当前时间</td>
					<td style="padding-left: 10px"><span><?php echo date('Y-m-d');?></span></td>
				</tr>
				<tr>
					<td  style="text-align: center;">时段</td>
					<td><input type="date" name="date" style="width: 88%;" value="<?php echo $date?>"></td>
				</tr>
				<tr style="text-align: center;">
					<td colspan="2"><input id='ckbb' type="submit" value="查看报表"></td>
				</tr>
			</table>
		</form>
	</div>
</div>
<?php if(!empty($riq)):?>
<!-- 为ECharts准备一个具备大小（宽高）的Dom -->
    <div id="tu" style="background-color: #FFFFFF;">
    	<div id="main" style="height:400px;width: 100%;margin-right: 10%; "></div>
    	
    </div>
    <!-- ECharts单文件引入 -->
<script type="text/javascript">
        // 基于准备好的dom，初始化echarts图表
           var myChart = echarts.init(document.getElementById('main')); 
			        var option = {
					    tooltip : {
					        trigger: 'axis'
					    },
					    legend: {
					        data:['订单数','营业额']
					    },
					    calculable : true,
					    xAxis : [
					        {
					            type: "category",
				           		splitLine: {show: false},
					            data : [<?php foreach($riq as $ri){
				            	$data = $ri['hour'];
				            	echo "'$data:00'".",";
				            }?>]
				        }
				    ],
				      yAxis: [
						     {
						        max:'<?php echo ceil($ris[$pos]);?>',
						        min:'0'
						    }
				    ],
				    series : [
				        {
				            name:'订单数',
				            type:'line',
				            stack: '总量',
				            data:[<?php foreach($riq as $ri){echo $ri['count'].",";}?>]
				        },
				        {
				            name:'营业额',
				            type:'line',
				            stack: '总量',
				            data:[<?php foreach($riq as $ri){echo $ri['pay_amount'].",";}?>]
					        }
					    ]
					};
			        // 为echarts对象加载数据 
			        myChart.setOption(option);
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
<div style="background-color: #FFFFFF;"><button style="margin-left: 80%;"><a href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$this->companyId));?>#sdbb">返回</a></button></div>