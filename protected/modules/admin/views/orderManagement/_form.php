							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'orderManagement-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							));
							$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
							$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
							$orderID = Yii::app()->request->getParam('orderID');
							
							?>
								<div class="form-body">

								<div class="form-group">
									<?php echo $form->label($model, '退款订单号',array('class' => 'col-md-3 control-label'));?>
									<div class="col-md-4">
									       <?php echo $form->label($model,$orderID,array('class' =>'form-control'));?>
									
								<!--		<?php echo $form->textField($model, 'name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('name')));?>
										<?php echo $form->error($model, 'name' )?>
								-->		
									</div>
								</div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, '退款菜品',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
									 				<?php echo $form->dropDownList($model, 'language', array('1' => yii::t('app','小鸡炖蘑菇') , '2' => yii::t('app','鲤鱼跃龙门')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('language')));?>
													<?php echo $form->error($model, 'language' )?>
										<!-- 	<?php echo $form->textField($model, 'address',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('address')));?>
											<?php echo $form->error($model, 'address' )?>
										 -->
										</div>
									</div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, '退款理由',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'language', array('1' => yii::t('app','上菜太慢') , '2' => yii::t('app','有异物')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('language')));?>
											<?php echo $form->error($model, 'language' )?>
										</div>
									</div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, '退款金额',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'printer_type', array('0' => yii::t('app','88.00') , '1' => yii::t('app','随便')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('printer_type')));?>
											<?php echo $form->error($model, 'printer_type' )?>
										</div>
									</div>
								<!-- <div class="form-group">
										<?php echo $form->label($model, 'brand',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'brand',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('brand')));?>
											<?php echo $form->error($model, 'brand' )?>
										</div>
									</div>
                                 -->	                                    
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
											<a href="<?php echo $this->createUrl('orderManagement/paymentRecord' , array('companyId' => $this->companyId));?>/orderID/<?php echo $orderID;?>/begin_time/<?php echo $begin_time;?>/end_time/<?php echo $end_time;?>" class="btn green"><?php echo yii::t('app','返回');?></a>  
											<a href="<?php echo $this->createUrl('orderManagement/paymentRecord' , array('companyId' => $this->companyId));?>/begin_time/<?php echo $begin_time;?>/end_time/<?php echo $end_time;?>" class="btn green"><?php echo yii::t('app','返回所有');?></a>                             
										</div>
									</div>
							<?php $this->endWidget(); ?>