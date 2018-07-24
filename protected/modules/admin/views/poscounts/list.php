<style>
		span.tab{
			color: black;
			border-right:1px dashed white;
			margin-right:10px;
			padding-right:10px;
			display:inline-block;
		}
        span.tab-active{
			color:white;
		}
		.ku-item{
			width:100px;
			height:100px;
			margin-right:20px;
			margin-top:20px;
			margin-left:20px;
			border-radius:5px !important;
		/*	border:2px solid black;
			box-shadow: 5px 5px 5px #888888;*/
			vertical-align:middle;
		}
		.ku-item-info{
			width:144px;
			font-size:2em;
			color:black;
			text-align:center;
		}
		.margin-left-right{
			margin-left:10px;
			margin-right:10px;
		}
		.cf-black{
			color: #000 !important;

		}
/*        .portlet-body a{
            display: inline-block;
        	height: 80px;
        	border: 1px solid white;
        }*/
	</style>
<div class="page-content">
	<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->

	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->

	<!-- BEGIN PAGE CONTENT-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','收银机结算'),'url'=>''))));?>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet purple box">
			      	<div class="portlet-title">
					<div class="caption"><?php echo yii::t('app','收银机结算');?></div>
				</div>
				<div class="portlet-body clearfix" >
        			<div class="panel_body row">
                        <p>收银机结算</p>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                            <a href="<?php echo $this->createUrl('poscounts/hqindex',array('companyId' => $this->companyId));?>">
                                <div class="list_big">收银机结算报表</div>
                                <div class="list_small">查询门店收款机结算情况</div>
                            </a> 
                        </div>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                            <a href="<?php echo $this->createUrl('poscounts/pospay',array('companyId' => $this->companyId));?>">
                                <div class="list_big">美团支付开通报表</div>
                                <div class="list_small">查询门店开通美团支付信息情况</div>
                            </a> 
                        </div>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                            <a href="<?php echo $this->createUrl('poscounts/posfee',array('companyId' => $this->companyId));?>">
                                <div class="list_big">系统年费报表</div>
                                <div class="list_small">查询门店续费延期情况</div>
                            </a> 
                        </div>
                    </div>
                </div> 
				
			</div>
		</div>
	</div>
	<!-- END PAGE CONTENT-->
	<script>
        $(document).ready(function() {
            $('.relation').click(function(){
                $('.modal').modal();
           });
        });
	</script>