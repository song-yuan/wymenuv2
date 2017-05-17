							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'taste-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							)); ?>
								<div class="form-body">
									<div class="form-group">
										<?php echo $form->label($model, 'name',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('name')));?>
											<?php echo $form->error($model, 'name' )?>
										</div>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, 'price',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'price',array('class' => 'form-control','onfocus'=>" if (value =='0.00'){value = ''}", 'onblur'=>"if (value ==''){value='0.00'}" ,'placeholder'=>$model->getAttributeLabel('price')));?>
											<?php echo $form->error($model, 'price' )?>
										</div>
									</div>
									<div class="form-group">
											<?php echo $form->label($model, 'is_selected',array('class' => 'col-md-3 control-label'));?>
											<div class="col-md-4">
												<?php echo $form->dropDownList($model, 'is_selected', array( '1' =>yii::t('app','是'), '0' =>yii::t('app','否')) , array('id'=>'isSelectId', 'class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_selected')));?>
												<?php echo $form->error($model, 'is_selected' )?>
											</div>
										</div>
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<a href="<?php echo $this->createUrl('taste/detailIndex' , array('companyId' => $model->dpid,'groupid'=>$groupid,'groupname'=>$groupname,'type'=>$type));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>