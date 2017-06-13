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
				<div class="portlet-body" style="min-height: 750px">
                 <a href="<?php echo $this->createUrl('waimai/dpbd',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item-info">店铺映射</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('waimai/caipinyingshe',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item-info">菜品映射</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('waimai/jcbd',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item-info">解除绑定</div>
						</div>
					</a>
				</div>
			</div>
		</div>
	</div>