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
										<?php echo $form->label($model, 'is_set',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<div class="radio-list">                                                
												<label class="radio-inline">
													<input type="radio" name="ProductSales[is_set]" value="0"  <?php echo $model->is_set ? '' : 'checked' ;?>/>否
												</label>
												<label class="radio-inline">
													<input type="radio" name="ProductSales[is_set]" value="1"  <?php echo $model->is_set ? 'checked' : '' ;?>/>是
												</label>  
											</div>
										</div>
									</div>
									
									<div class="form-group notSet">
										<label class="col-md-3 control-label">选择单品</label>
										<div class="col-md-4">
										<select neme="ProductDiscount[product_id]" class="form-control">
										<?php foreach($products as $product):?>
										<option value="<?php echo $product['lid'];?>"><?php echo $product['product_name'];?></option> 
										<?php endforeach;?>
										</select>
										</div>
									</div>
									
									<div class="form-group isSet">
										<label class="col-md-3 control-label">选择套餐</label>
										<div class="col-md-4">
										<select neme="ProductDiscount[product_id]"  class="form-control">
										<?php foreach($productSets as $productSet):?>
										<option value="<?php echo $productSet['lid'];?>"><?php echo $productSet['set_name'];?></option> 
										<?php endforeach;?>
										</select>
										</div>
									</div>
									
									<div class="form-group">
										<?php echo $form->label($model, 'is_discount',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<div class="radio-list">                                                
												<label class="radio-inline">
													<input type="radio" name="ProductSales[is_discount]" value="0"  <?php echo $model->is_discount ? '' : 'checked' ;?>/>优惠
												</label>
												<label class="radio-inline">
													<input type="radio" name="ProductSales[is_discount]" value="1"  <?php echo $model->is_discount ? 'checked' : '' ;?>/>折扣
												</label>  
											</div>
										</div>
									</div>
									<div class="form-group discount">
										<label class="col-md-3 control-label">优惠价格</label>
										<div class="col-md-4">
										<input type="text" class="form-control  input-xsmall" name="ProductSales[price_discount]" value="" />
										</div>
									</div>
									
									<div class="form-group isDiscount">
										<label class="col-md-3 control-label">折扣比例</label>
										<div class="col-md-4">
										<input type="text" class="form-control  input-xsmall" name="ProductSales[price_discount]" value="" />
										</div>
									</div>
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue">确定</button>
											<a href="<?php echo $this->createUrl('retreat/index' , array('companyId' => $model->dpid));?>" class="btn default">返回</a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>
							<script>
							$(document).ready(function(){
							   if(parseInt($('input:radio[name="ProductSales[is_set]"]:checked').val())) {
									$('.isSet').show();
									$('.notSet').hide();
								} else {
									$('.notSet').show();
									$('.isSet').hide();
								}
								$('input:radio[name="ProductSales[is_set]"]').change(function(){
									if(parseInt($(this).val())) {
										$('.isSet').show();
									    $('.notSet').hide();
									} else {
									   $('.notSet').show();
									   $('.isSet').hide();
									}
							    });
							    if(parseInt($('input:radio[name="ProductSales[is_discount]"]:checked').val())) {
									$('.isDiscount').show();
									$('.discount').hide();
								} else {
									$('.discount').show();
									$('.isDiscount').hide();
								}
								$('input:radio[name="ProductSales[is_discount]"]').change(function(){
									if(parseInt($(this).val())) {
										$('.isDiscount').show();
									    $('.discount').hide();
									} else {
									   $('.discount').show();
									   $('.isDiscount').hide();
									}
							    });
							});
							
							</script>