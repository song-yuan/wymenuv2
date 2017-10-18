<!-- BEGIN PAGE -->  
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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','基础设置'),'url'=>$this->createUrl('product/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','分组设置'),'url'=>$this->createUrl('areaGroup/index' , array('companyId'=>$this->companyId,'type'=>$type))),array('word'=>yii::t('app','添加分组'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('areaGroup/index' , array('companyId'=>$this->companyId)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><?php echo yii::t('app','添加分组');?></div>
				</div>
				<div class="portlet-body form">
					<!-- BEGIN FORM-->

					<?php $form=$this->beginWidget('CActiveForm', array(
					    'id'=>'area-group-create-form',
						'errorMessageCssClass' => 'help-block',
						'htmlOptions' => array(
							'class' => 'form-horizontal',
							'enctype' => 'multipart/form-data',
						),
					    'enableAjaxValidation'=>false,
					)); ?>

					    <?php echo $form->errorSummary($model); ?>
					    <div class="form-body">
						    <div class="form-group" >
						        <?php echo $form->labelEx($model,'group_name',array('class' => 'col-md-offset-1 col-md-2 control-label')); ?>
						    <div class="col-md-5">
						        <?php echo $form->textField($model,'group_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('group_name'))); ?>
						        <?php echo $form->error($model,'group_name'); ?>
						    </div>
						    </div>

						    <div class="form-group">
						        <?php echo $form->labelEx($model,'group_desc',array('class' => 'col-md-offset-1 col-md-2 control-label')); ?>
						    <div class="col-md-5">
						        <?php echo $form->textArea($model,'group_desc',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('group_desc'))); ?>
						        <?php echo $form->error($model,'group_desc'); ?>
						    </div>
							</div>
						    <div class="col-md-offset-3 col-md-9" style="margin-top:10px;">
								<?php echo CHtml::submitButton('确定',array('class' => 'btn blue')); ?>
						    	<a href="<?php echo $this->createUrl('areaGroup/index' , array('companyId' => $this->companyId,'type'=>$type));?>" class="btn default"><?php echo yii::t('app','返回');?></a>
						    </div>
						</div>
					<?php $this->endWidget(); ?>

					<!-- END FORM--> 
				</div>

		</div>
	</div>
	<!-- END PAGE CONTENT-->    
</div>
<!-- END PAGE -->  