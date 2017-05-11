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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','进销存管理'),'subhead'=>yii::t('app','库存设置'),'breadcrumbs'=>array(array('word'=>yii::t('app','库存设置'),'url'=>$this->createUrl('bom/bom' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','库存设置'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('bom/bom' , array('companyId' => $this->companyId,'type' => '0')))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
		<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'product-form',
				'action' => $this->createUrl('stockSetting/index' , array('companyId' => $this->companyId,)),
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
						'class' => 'form-horizontal',
						'enctype' => 'multipart/form-data'
				),
		)); ?>
		<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','库存参数设置');?></div>
					<div class="tools">
						<a href="javascript:;" class="collapse"></a>
					</div>
				</div>
				<div class="portlet-body" style="border-bottom: 1px dashed #002a80;">
					<p>店铺库存(库存小于安全库存范围时自动申请调拨)</p>
					<div class="col-md-offset-3">
						日均销量 = 最近 <input type="text" name="StockSetting[dsales_day]" class="" value="<?php echo $model->dsales_day;?>" /> 天的日均销量<br /><br/>
						日均销量 X <input type="text" name="StockSetting[dsafe_min_day]" class="" value="<?php echo $model->dsafe_min_day;?>" /> 天< 安全库存范围 < 日均销量 X <input type="text" name="StockSetting[dsafe_max_day]" class="" value="<?php echo $model->dsafe_max_day;?>" /> 天<br /><br/>
					</div>
					<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
				</div>
				<?php if(0):?>
				<div class="portlet-body">
					<p>仓库库存(库存小于安全库存范围时自动生成采购订单)</p>
					<div class="col-md-offset-3">
                        日均销量 = 最近 <input type="text" name="StockSetting[csales_day]" class="" value="<?php echo $model->csales_day;?>" /> 天的日均销量<br /><br/>
                        日均销量 X <input type="text" name="StockSetting[csafe_min_day]" class="" value="<?php echo $model->csafe_min_day;?>" /> 天< 安全库存范围 < 日均销量 X <input type="text" name="StockSetting[csafe_max_day]" class="" value="<?php echo $model->csafe_max_day;?>" /> 天<br /><br/>
                        <button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
						<!-- <a href="<?php echo $this->createUrl('bom/bom' , array('companyId' => $this->companyId));?>" class="btn default"> <?php echo yii::t('app','返回');?></a> -->
					</div>
				</div>
				<?php endif;?>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
        <?php $this->endWidget();?>
	</div>
	<!-- END PAGE CONTENT-->
