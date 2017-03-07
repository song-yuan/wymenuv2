<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/echarts.min.js');?>
<style>
    .portlet-body  .input-group{
        width:50%;
        margin-left: 150px;
        margin-top: 20px;
    }
   @media (max-width: 768px) {
     .portlet-body  .input-group{
        width:100%;
        margin-left: 0px;
        margin-top: 0px;
    }
}

.demo{
    width:80%;
    margin-top: 20px;
    margin-left: 10px;
    padding:10px;
}
@media (max-width: 768px){
    .demo{
        width:360px;
        margin-top: 30px;
        margin-left: 30px;
        padding:10px;
    }
}
</style>

<div class="page-content">
	<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
	<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">Modal title</h4>
				</div>
				<div class="modal-body">
					Widget settings form goes here
				</div>
				<div class="modal-footer">
					<button type="button" class="btn blue">Save changes</button>
					<button type="button" class="btn default" data-dismiss="modal">Close</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','微信会员'),'url'=>$this->createUrl('wechatMember/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','会员渠道'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('wechatMember/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
<div class="row">   
    <div class="col-md-12">
            <div class="portlet purple box">
                <div class="portlet-body" >                   
                    <form action="" method="post" >
                        <div class="input-group">
                            <input type="text" name="" class="form-control" placeholder="" value="">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="submit">
                                    <span class="glyphicon glyphicon-search"></span>
                                </button>
                            </span>
                        </div>
                    </form>
                    <div id="main"> 
                        <div class="demo">
                            <div id="myChart" style="width:100%;height:500px;"></div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>        
</div>
<!-- END PAGE CONTENT-->
<script type="text/javascript">
var myChart = echarts.init(document.getElementById('myChart'));

option = {
 /*   title : {
        text: '某站点用户访问来源',
        subtext: '纯属虚构',
        x:'center'
    },*/
    tooltip : {
        trigger: 'item',
        formatter: "{a} <br/>{b} : {c} ({d}%)"
    },
    legend: {
        orient: 'vertical',
        left: 'right',
        data: ['渠道1','渠道2','渠道3','渠道4','渠道5']
    },
    series : [
        {
            name: '访问来源',
            type: 'pie',
            radius : '55%',
            center: ['50%', '60%'],
            data:[
                {value:335, name:'渠道1'},
                {value:310, name:'渠道2'},
                {value:234, name:'渠道3'},
                {value:135, name:'渠道4'},
                {value:1548, name:'渠道5'}
            ],
            itemStyle: {
                emphasis: {
                    shadowBlur: 10,
                    shadowOffsetX: 0,
                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
            }
        }
    ]
};


// 使用刚指定的配置项和数据显示图表。
myChart.setOption(option);
</script>
