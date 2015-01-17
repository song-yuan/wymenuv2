							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'siteType-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							)); ?>
								<div class="form-body">
								<?php if(!$model->company_id):?>
									<div class="form-group">
										<?php echo $form->label($model, 'company_id',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'company_id', array_merge(array('0' => '-- 请选择 --') , Helper::genCompanyOptions()) ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('company_id')));?>
											<?php echo $form->error($model, 'company_id' )?>
										</div>
									</div>
								<?php endif;?>
									<div class="form-group">
										<?php echo $form->label($model, 'name',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('name')));?>
											<?php echo $form->error($model, 'name' )?>
										</div>
									</div>
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue">确定</button>
											<a href="<?php echo $this->createUrl('siteType/index' , array('companyId' => $model->company_id));?>" class="btn default">返回</a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>