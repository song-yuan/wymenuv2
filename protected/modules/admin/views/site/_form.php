							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'site-form',
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
											<?php echo $form->dropDownList($model, 'company_id', array('0' => '-- 请选择 --') +Helper::genCompanyOptions() ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('company_id')));?>
											<?php echo $form->error($model, 'company_id' )?>
										</div>
									</div>
								<?php endif;?>
									<div class="form-group">
										<?php echo $form->label($model, 'type_id',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'type_id', array('0' => '-- 请选择 --') +$types ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('type_id')));?>
											<?php echo $form->error($model, 'type_id' )?>
										</div>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, 'site_level',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'site_level',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('site_level')));?>
											<?php echo $form->error($model, 'site_level' )?>
										</div>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, 'serial',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'serial',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('serial')));?>
											<?php echo $form->error($model, 'serial' )?>
										</div>
									</div>

									<div class="form-group">
										<?php echo $form->label($model, 'has_minimum_consumption',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<div class="radio-list">                                                
												<label class="radio-inline">
													<input type="radio" name="Site[has_minimum_consumption]" value="0"  <?php echo $model->has_minimum_consumption ? '' : 'checked' ;?>/>无
												</label>
												<label class="radio-inline">
													<input type="radio" name="Site[has_minimum_consumption]" value="1"  <?php echo $model->has_minimum_consumption ? 'checked' : '' ;?>/>有
												</label>  
											</div>
										</div>
									</div>
									<div class="form-group has_minumum_consumption">
										<?php echo $form->label($model, 'minimum_consumption_type',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<div class="radio-list">
												<label class="radio-inline">
													<input type="radio" name="Site[minimum_consumption_type]" value="0"  <?php echo $model->minimum_consumption_type ? '' : 'checked' ;?>/>按时间计费
												</label>  
												<label class="radio-inline">
													<input type="radio" name="Site[minimum_consumption_type]" value="1"  <?php echo $model->minimum_consumption_type ? 'checked' : '' ;?>/>按人数计费
												</label> 
											</div>
										</div>
									</div>
									<div class="form-group has_minumum_consumption">
										<?php echo $form->label($model, 'minimum_consumption',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'minimum_consumption',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('minimum_consumption')));?>
											<?php echo $form->error($model, 'minimum_consumption' )?>
										</div>
									</div>
									<div class="form-group has_minumum_consumption type0">
										<?php echo $form->label($model, 'period',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'period',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('period')));?>
											<?php echo $form->error($model, 'period' )?>
										</div>
									</div>
									<div class="form-group has_minumum_consumption type0">
										<?php echo $form->label($model, 'overtime',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'overtime',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('overtime')));?>
											<?php echo $form->error($model, 'overtime' )?>
										</div>
									</div>
									<div class="form-group has_minumum_consumption type0">
										<?php echo $form->label($model, 'buffer',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'buffer',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('buffer')));?>
											<?php echo $form->error($model, 'buffer' )?>
										</div>
									</div>
									<div class="form-group has_minumum_consumption type0">
										<?php echo $form->label($model, 'overtime_fee',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'overtime_fee',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('overtime_fee')));?>
											<?php echo $form->error($model, 'overtime_fee' )?>
										</div>
									</div>
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue">确定</button>
											<a href="<?php echo $this->createUrl('site/index' , array('companyId' => $model->company_id));?>" class="btn default">返回</a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>
							<script>
							$(document).ready(function(){
								$('input:radio[name=sex]:checked').val();
								if(parseInt($('input:radio[name="Site[has_minimum_consumption]"]:checked').val())) {
									$('.has_minumum_consumption').show();
									if(parseInt($('input:radio[name="Site[minimum_consumption_type]"]:checked').val())) {
										$('.type0').hide();
										$('.type1').show();
									} else {
										$('.type0').show();
										$('.type1').hide();
									}
								} else {
									$('.has_minumum_consumption').hide();
								}
								$('input:radio[name="Site[has_minimum_consumption]"]').change(function(){
									if(parseInt($(this).val())) {
										$('.has_minumum_consumption').show();
										if(parseInt($('input:radio[name="Site[minimum_consumption_type]"]:checked').val())) {
											$('.type0').hide();
											$('.type1').show();
										} else {
											$('.type0').show();
											$('.type1').hide();
										}
									} else {
										$('.has_minumum_consumption').hide();
									}
								});
								$('input:radio[name="Site[minimum_consumption_type]"]').change(function(){
									if(parseInt($(this).val())) {
										$('.type0').hide();
										$('.type1').show();
									} else {
										$('.type0').show();
										$('.type1').hide();
									}
								});
								
							});
							</script>