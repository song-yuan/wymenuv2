<style type="text/css">
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
			/*border:2px solid black;*/
			/*box-shadow: 5px 5px 5px #888888;*/
			vertical-align:middle;
		}
		.ku-item-info{
			width:144px;
			font-size:2em;
			color:black;
			text-align:center;
		}
		.ku-purple{
			/*background-color:#852b99;*/
		}
		.ku-grey{
			background-color:rgb(68,111,120);
		}
		/*.ku-item.mtwm{
			background-image:url(../../../../../../img/waiter/icon-mtwm.png);
			background-position: -5px -20px;
            background-repeat: no-repeat;
            background-size: 115px 180px;
		}
		.ku-item.eleme{
			background-image:url(../../../../../../img/waiter/icon-eleme.png);
			background-position: 0px 15px;
            background-repeat: no-repeat;
            background-size: 115px 120px;
		}
		.ku-item.wmsz{
			background-image:url(../../../../../../img/waiter/icon-wmsz.png);
			background-position: 0px 0px;
            background-repeat: no-repeat;
            background-size: 100px;
		}*/
		.margin-left-right{
			margin-left:10px;
			margin-right:10px;
		}
	
</style>	
	<div class="page-content">
		<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE CONTENT-->
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','外卖管理'),'url'=>$this->createUrl('waimai/list' , array('companyId'=>$this->companyId,'type'=>0,))))));?>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet purple box">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-cogs"></i><?php echo yii::t('app','外卖管理');?></div>
				</div>
				<div class="portlet-body clearfix">
					<div class="panel_body row">
                        <p>外卖设置</p>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                            <a href="<?php echo $this->createUrl('waimai/setting',array('companyId'=>$this->companyId));?>">
                                <div class="list_big">设置是否自动接单</div>
                                <div class="list_small">设置美团和饿了么是否自动接单</div>
                            </a> 
                        </div>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                            <a href="<?php echo $this->createUrl('waimai/index',array('companyId'=>$this->companyId));?>">
                                <div class="list_big">美团外卖</div>
                                <div class="list_small">设置绑定美团外卖，菜品映射和解除绑定</div>
                            </a> 
                        </div>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                            <a href="<?php echo $this->createUrl('eleme/index',array('companyId'=>$this->companyId));?>">
                                <div class="list_big">饿了么外卖</div>
                                <div class="list_small">设置绑定饿了么外卖，店铺对应和菜品对应</div>
                            </a> 
                        </div>
                    </div>
                    <div class="panel_body row">
                    	<p>外卖订单</p>
                        <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                            <a href="<?php echo $this->createUrl('waimai/order',array('companyId'=>$this->companyId));?>">
                                <div class="list_big">外卖订单查询</div>
                                <div class="list_small">查询外卖订单是否已经存在，漏单补单功能</div>
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