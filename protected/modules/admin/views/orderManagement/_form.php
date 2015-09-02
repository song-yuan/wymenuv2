							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'orderManagement-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							));
							
							?>
								<div class="form-body">

								<div class="form-group">
									<?php echo $form->label($model, '退款订单号',array('class' => 'col-md-3 control-label'));?>
									<div class="col-md-4">
									       <?php echo $form->label($model,$orderId,array('class' =>'form-control'));?>
									       <input type="hidden" name="OrderPay[order_id]" value="<?php echo $orderId;?>" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">退款类型</label>
									<div class="col-md-9">
									<div class="radio-list">
										<label class="radio-inline">
										<input type="radio" name="OrderPay[paytype]" value="0" <?php if(!$model->paytype) echo 'checked';?>> 现金支付
										</label>
										<label class="radio-inline">
										<input type="radio" name="OrderPay[paytype]" value="4" <?php if($model->paytype) echo 'checked';?>> 会员卡
										</label>
										<label class="radio-inline">
										<input type="radio" name="OrderPay[paytype]" value="5" <?php if($model->paytype) echo 'checked';?>> 银联
										</label>
									</div>
									</div>
								</div>
								<div class="form-group">
										<?php echo $form->label($model, '订单金额',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<input type="text" class="form-control"  value="<?php echo $order->reality_total;?>" disabled="disabled"/>
										</div>
									</div>
                                <div class="form-group">
										<?php echo $form->label($model, '退款金额',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<input type="text" class="form-control" name="OrderPay[pay_amount]" value=""/>
										</div>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, 'remark',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textArea($model, 'remark',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('remark')));?>
											<?php echo $form->error($model, 'remark' )?>
										</div>
									</div>
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<a href="<?php echo $this->createUrl('orderManagement/paymentRecord' , array('companyId' => $this->companyId));?>/orderID/<?php echo $orderId;?>/begin_time/<?php echo $begin_time;?>/end_time/<?php echo $end_time;?>" class="btn green"><?php echo yii::t('app','返回');?></a>  
											<a href="<?php echo $this->createUrl('orderManagement/paymentRecord' , array('companyId' => $this->companyId));?>/begin_time/<?php echo $begin_time;?>/end_time/<?php echo $end_time;?>" class="btn green"><?php echo yii::t('app','返回所有');?></a>                             
										</div>
									</div>
							<?php $this->endWidget(); ?>