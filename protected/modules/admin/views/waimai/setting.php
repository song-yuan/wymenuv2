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
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','外卖管理'),'url'=>$this->createUrl('waimai/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','外卖设置'),'url'=>$this->createUrl('waimai/setting' , array('companyId'=>$this->companyId,'type'=>0,))),),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('waimai/list' , array('companyId' => $this->companyId,'type' => '0'))))
		);?>
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'product-form',
				'action' => $this->createUrl('waimai/setting' , array('companyId' => $this->companyId)),
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
		)); ?>
	<div class="form-body">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet box blue">
			<div class="portlet-title">
				<div class="caption"><i class="fa fa-reorder"></i><?php echo yii::t('app','是否自动接单');?></div>
				<div class="tools">
					<a href="javascript:;" class="collapse"></a>
				</div>
			</div>
			<div class="portlet-body form">
				<div class="form-group">
				<?php echo $form->label($model, 'dpid',array('class' => 'col-md-3 control-label'));?>
					<div class="col-md-4">
					<?php echo $form->textField($model, 'dpid',array('class' => 'form-control','disabled'=>'disabled','placeholder'=>$model->getAttributeLabel('dpid')));?>
					<?php echo $form->error($model, 'dpid' )?>
					</div>
				</div>
				<div class="form-group">
				<?php echo $form->label($model, 'is_receive',array('class' => 'col-md-3 control-label'));?>
					<div class="col-md-4">
					<?php echo $form->dropDownList($model, 'is_receive', array('0' => yii::t('app','否') , '1' => yii::t('app','是')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_receive')));?>
					</div>
				</div>
				<div class="form-actions fluid">
					<div class="col-md-offset-3 col-md-9">
						<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>                   
					</div>
				</div>
			</div>
		</div>
		<?php $this->endWidget(); ?>
	</div>
	<!-- END PAGE CONTENT-->	