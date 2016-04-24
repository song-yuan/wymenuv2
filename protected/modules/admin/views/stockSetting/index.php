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
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','库存设置'),'subhead'=>yii::t('app','库存设置'),'breadcrumbs'=>array(array('word'=>yii::t('app','库存设置'),'url'=>''))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
		<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','店铺库存(库存少于安全库存时自动申请调拨)');?></div>
					<div class="tools">
						<a href="javascript:;" class="collapse"></a>
					</div>
				</div>
				<div class="portlet-body">

					<div class="col-md-offset-3">
						日均销量 = 最近<input type="text" name="" class="" value="<?php //echo $model->sales_day;?>" /> 天的日均销量<br /><br/>
						日均销量 X <input type="text" name="" class="" value="<?php //echo $model->safe_day;?>" /> 天<安全库存范围 < 日均销量 X <input type="text" name="" class="" value="<?php //echo $model->safe_day;?>" /> 天<br /><br/>
						<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
						<a href="<?php echo $this->createUrl('bom/bom' , array('companyId' => $this->companyId));?>" class="btn default"> <?php echo yii::t('app','返回');?></a>
					</div>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','仓库库存(库存少于安全库存时自动生成采购订单)');?></div>
					<div class="tools">
						<a href="javascript:;" class="collapse"></a>
					</div>
				</div>
				<div class="portlet-body">
					<div class="col-md-offset-3">
						日均销量 = 最近<input type="text" name="" class="" value="<?php //echo $model->sales_day;?>" /> 天的日均销量<br /><br/>
						日均销量 X <input type="text" name="" class="" value="<?php //echo $model->safe_day;?>" /> 天<安全库存范围 < 日均销量 X <input type="text" name="" class="" value="<?php //echo $model->safe_day;?>" /> 天<br /><br/>
						<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
						<a href="<?php echo $this->createUrl('bom/bom' , array('companyId' => $this->companyId));?>" class="btn default"> <?php echo yii::t('app','返回');?></a>
					</div>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
	</div>
	<!-- END PAGE CONTENT-->
