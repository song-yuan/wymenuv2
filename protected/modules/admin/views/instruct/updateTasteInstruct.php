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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','基础设置'),'url'=>$this->createUrl('product/list' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','口味指令对应'),'url'=>$this->createUrl('instruct/tasteInstruct' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','口味指令对应'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('instruct/tasteInstruct' , array('companyId'=>$this->companyId)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box blue">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-reorder"></i><?php echo yii::t('app','修改产品口味');?></div>
				</div>
				<div class="portlet-body form">
					<!-- BEGIN FORM-->
					<?php $form=$this->beginWidget('CActiveForm', array(
						'id' => 'taste-form',
						'errorMessageCssClass' => 'help-block',
						'htmlOptions' => array(
							'class' => 'form-horizontal',
							'enctype' => 'multipart/form-data'
						),
					)); ?>
						<div class="form-body">
							<?php foreach ($models as $model):?>
							<div class="form-group">
								<?php echo $form->label($model, 'name',array('class' => 'col-md-3 control-label'));?>
								<div class="col-md-4">
									<?php echo $form->textField($model, 'name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('name'),'disabled'=>'disabled'));?>
									<?php echo $form->error($model, 'name' )?>
								</div>
							</div>
							<?php endforeach;?>
							<?php if(count($models) < 3):?>
							<div class="form-group">
								<label  class="col-md-3 control-label"><?php echo yii::t('app','口味名称');?></label>
								<div class="col-md-4">
									<select class="form-control" name="Taste[]">
									<option value="0">---请选择---</option>
										<?php foreach ($tastes as $taste):?>
										<option value="<?php echo $taste['lid']?>"><?php echo $taste['name']?></option>
										<?php endforeach;?>
									</select>
								</div>
							</div>
							<?php endif;?>
							<div class="form-group">
								<label  class="col-md-3 control-label"><?php echo yii::t('app','指令选择');?></label>
								<div class="col-md-8">
									<?php foreach($instructions as $instruction):?>
									<label class="checkbox-inline">
									<input type="checkbox" name="Instruct[]" <?php if(in_array($instruction['lid'],$productInstructs)) echo 'checked';?> value="<?php echo $instruction['lid'];?>"> <?php echo $instruction['instruct_name'];?>
									</label>
									<?php endforeach;?>
								</div>
							</div>
							<div class="form-actions fluid">
								<div class="col-md-offset-3 col-md-9">
									<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
									<a href="<?php echo $this->createUrl('instruct/tasteInstruct' , array('companyId'=>$model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
								</div>
							</div>
					<?php $this->endWidget(); ?>
					<!-- END FORM--> 
				</div>
			</div>
		</div>
	</div>
	<!-- END PAGE CONTENT-->    
</div>
<!-- END PAGE --> 		 