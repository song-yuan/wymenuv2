							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'company-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							)); ?>
								<div class="form-body">
									<div class="form-group">
										<label class="col-md-3 control-label" >支付方式名称</label>
										<div class="col-md-4">
											<input type="text" class='form-control' />
										</div>
									</div>
																		
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue">确定</button>
											<a href="<?php echo $this->createUrl('payMethod/index');?>" class="btn default">返回</a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>						