							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'taste-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							)); ?>
								<div class="form-body">
									<div class="form-group notSet">
										<label class="col-md-3 control-label">选择单品</label>
										<div class="col-md-4">
										<select name="ProductTempprice[product_id]" class="form-control">
										<option value="<?php echo $product->lid;?>"><?php echo $product->product_name;?></option> 
										</select>
										</div>
									</div>
									
									<div class="form-group discount">
										<label class="col-md-3 control-label">时价价格</label>
										<div class="col-md-2">
											<div class="input-group">
											<input type="text" class="form-control" name="ProductTempprice[price]" value="<?php echo $model->price;?>" /><span class="input-group-addon">元</span>
											</div>
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-md-3 control-label">有效期</label>
										<div class="col-md-4">
										  <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
											<input type="text" class="form-control" name="ProductTempprice[begin_time]" placeholder="开始时间" value="<?php echo $model->begin_time;?>">
											<span class="input-group-addon">~</span>
											<input type="text" class="form-control" name="ProductTempprice[end_time]" placeholder="结束时间" value="<?php echo $model->end_time;?>">
										  </div>
										</div>
									</div>
									
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue">确定</button>
											<a href="<?php echo $this->createUrl('productTempprice/updatedetail' , array('companyId' => $model->dpid));?>" class="btn default">返回</a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>
							