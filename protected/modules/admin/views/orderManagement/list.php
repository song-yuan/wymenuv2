<link href="../../../../css/jxcgl.css" rel="stylesheet" type="text/css">
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
					<!--  
					<button type="button" class="btn blue">Save changes</button>
					-->
					<button type="button" class="btn default" data-dismiss="modal">确定</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
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
			border:2px solid black;
			box-shadow: 5px 5px 5px #888888;
			vertical-align:middle;
		}
		.ku-item-info{
			width:144px;
			font-size:2em;
			color:black;
			text-align:center;
		}
		.ku-purple{
			background-color:#852b99;
		}
		.ku-grey{
			background-color:rgb(68,111,120);
		}
		.ku-item.dpgl{
			background-image:url(../../../../img/waiter/icon-dpjcsz.png);
			background-position: 15px 15px;
    		background-repeat: no-repeat;
		}
		.ku-item.czygl{
			background-image:url(../../../../img/waiter/icon-dpjcsz.png);
			background-position: -135px 15px;
    		background-repeat: no-repeat;
		}
		.ku-item.qxsz{
			background-image:url(../../../../img/waiter/icon-dpjcsz.png);
			background-position: -285px 15px;
    		background-repeat: no-repeat;
		}
		.ku-item.fdgl{
			background-image:url(../../../../img/waiter/icon-dpjcsz.png);
			background-position: -425px 15px;
    		background-repeat: no-repeat;
		}
		.ku-item.wxdp{
			background-image:url(../../../../img/waiter/icon-dpjcsz.png);
			background-position: -575px 15px;
    		background-repeat: no-repeat;
		}
		.ku-item.tbsj{
			background-image:url(../../../../img/waiter/icon-dpjcsz.png);
			background-position: -725px 15px;
    		background-repeat: no-repeat;
		}
		.margin-left-right{
			margin-left:10px;
			margin-right:10px;
		}
	</style>
	<!-- BEGIN PAGE CONTENT-->
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','订单管理'),'subhead'=>yii::t('app','订单管理'),'breadcrumbs'=>array(array('word'=>yii::t('app','订单管理'),'url'=>''))));?>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet purple box">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-tasks"></i><?php echo yii::t('app','订单管理');?></div>
				</div>
				<div class="portlet-body" style="min-height: 750px">
					<a href="<?php echo $this->createUrl('orderManagement/index',array('companyId'=>$this->companyId,'begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey dpgl"></div>
							<div class="ku-item-info">历史订单</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('orderManagement/accountStatement',array('companyId'=>$this->companyId,'begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey czygl"></div>
							<div class="ku-item-info">日结对账单</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('orderManagement/paymentRecord',array('companyId'=>$this->companyId,'begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-grey wxdp"></div>
							<div class="ku-item-info">退付款记录</div>
						</div>
					</a>
					<!--
					<a href="#">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple qxsz"></div>
							<div class="ku-item-info">权限设置</div>
						</div>
					</a>
					-->
					
					
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