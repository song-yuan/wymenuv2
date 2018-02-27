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
    <?php

    	if (1 == $type) {
    		$backurl = $this->createUrl('costs/costsDayReport' , array('companyId'=>$this->companyId));
    	} else {
    		$backurl = $this->createUrl('costs/costsReport' , array('companyId'=>$this->companyId));
    	}

    	$this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','数据中心'),'subhead'=>yii::t('app','营业数据'),'breadcrumbs'=>array(array('word'=>yii::t('app','营业数据'),'url'=>$this->createUrl('statements/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','成本管控报表'),'url'=>$this->createUrl('costs/costsReport' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','添加支出'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$backurl)));?>

			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption"><?php echo yii::t('app','添加支出');?></div>
						</div>
					</div>
						<div class="portlet-body form" style="margin-top:20px;">
							<!-- BEGIN FORM-->

							<?php $form=$this->beginWidget('CActiveForm', array(
							    'id'=>'price-group-create-form',
								'errorMessageCssClass' => 'help-block',
								'htmlOptions' => array(
									'class' => 'form-horizontal',
									'enctype' => 'multipart/form-data',
								),
							    'enableAjaxValidation'=>false,
							)); ?>

							    <?php echo $form->errorSummary($model); ?>
							    <div class="row" >
							        <?php echo $form->labelEx($model,'item',array('class' => 'col-md-offset-1 col-md-2 control-label')); ?>
							    <div class="col-md-5">
							        <?php echo $form->textField($model,'item',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('item'))); ?>
							        <?php echo $form->error($model,'item'); ?>
							    </div>
							    </div>
							    <div class="row" style="margin-top:10px;">
							        <?php echo $form->labelEx($model,'happen_at',array('class' => 'col-md-offset-1 col-md-2 control-label')); ?>
							    <div class="col-md-5">
							    	<input type="date" class="form-control" name="Costs[happen_at]" placeholder="发生时间" id="Costs_happen_at" value="<?php echo $model->happen_at; ?>" />
							        <?php echo $form->error($model,'happen_at'); ?>
							    </div>
							    </div>

							    <div class="row" style="margin-top:10px;">
							        <?php echo $form->labelEx($model,'price',array('class' => 'col-md-offset-1 col-md-2 control-label')); ?>
							    <div class="col-md-5">
							    	<input type="number" step="0.01" class="form-control" name="Costs[price]" placeholder="支出款项" id="Costs_price" value="<?php echo $model->price; ?>" />
							        <?php echo $form->error($model,'price'); ?>
							    </div>
							    </div>
							    <?php if ($cost_type==0): ?>
							    	<input type="hidden"  name="time" value="<?php echo $time; ?>" />
								<?php elseif($cost_type==1): ?>
							    	<input type="hidden"  name="month" value="<?php echo $month; ?>" />
								<?php elseif($cost_type==2): ?>
							    	<input type="hidden"  name="year" value="<?php echo $month; ?>" />
							    <?php endif; ?>
							    <div class="row" style="margin-top:10px;">
							        <?php echo $form->labelEx($model,'description',array('class' => 'col-md-offset-1 col-md-2 control-label')); ?>
							    <div class="col-md-5">
							        <?php echo $form->textArea($model,'description',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('description'))); ?>
							        <?php echo $form->error($model,'description'); ?>
							    </div>
								</div>

							    <div class="row" style="margin-top:10px;">

							    <?php echo $form->label($model, yii::t('app','支出类型'),array('class' => 'col-md-offset-1 col-md-2 control-label'));?>
								<div class="col-md-5">
									<?php echo $form->dropDownList($model, 'pay_type', array( '0' => yii::t('app','当日支出'), '1' => yii::t('app','月支出'), '2' => yii::t('app','年支出')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('pay_type')));?>
									<?php echo $form->error($model, 'pay_type' )?>
								</div>
								</div>

							    <div class="col-md-offset-3 col-md-9" style="margin-top:10px;">
									<?php echo CHtml::submitButton('确定',array('class' => 'btn blue')); ?>
							    	<a href="<?php echo $this->createUrl('pricegroup/index' , array('companyId' => $this->companyId));?>" class="btn default"><?php echo yii::t('app','返回');?></a>
							    </div>

							<?php $this->endWidget(); ?>

							<!-- END FORM-->
						</div>

				</div>
			</div>
			<!-- END PAGE CONTENT-->
		</div>
		<!-- END PAGE -->