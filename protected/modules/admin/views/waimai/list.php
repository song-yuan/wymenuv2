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
		.ku-item.mtwm{
			background-image:url(../../../../../../img/waiter/icon-mtwm.png);
			background-position: 0px 0px;
            background-repeat: no-repeat;
            background-size: 100px;
		}
		.ku-item.eleme{
			background-image:url(../../../../../../img/waiter/icon-eleme.png);
			background-position: 0px 20px;
            background-repeat: no-repeat;
            background-size: 100px;
		}
		.ku-item.wmsz{
			background-image:url(../../../../../../img/waiter/icon-wmsz.png);
			background-position: 0px 20px;
            background-repeat: no-repeat;
            background-size: 100px;
		}
		.margin-left-right{
			margin-left:10px;
			margin-right:10px;
		}
	
</style>	
	<div class="page-content">
		<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">关系图</h4>
					</div>
					<div class="modal-body">
						<img alt="" src="">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn default" data-dismiss="modal">确定</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
	<!-- BEGIN PAGE CONTENT-->
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','外卖管理'),'url'=>''))));?>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet purple box">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-cogs"></i><?php echo yii::t('app','外卖管理');?></div>
				</div>
				<div class="portlet-body clearfix" style="min-height: 750px">
                 <a href="<?php echo $this->createUrl('waimai/Setting',array('companyId'=>$this->companyId));?>">
					<div class="pull-left margin-left-right">
						<div class="ku-item ku-purple wmsz"></div>
						<div class="ku-item-info">外卖设置</div>
					</div>
				</a>
                 <a href="<?php echo $this->createUrl('waimai/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple mtwm"></div>
							<div class="ku-item-info">美团外卖</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('eleme/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple eleme"></div>
							<div class="ku-item-info">饿了么外卖</div>
						</div>
					</a>
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