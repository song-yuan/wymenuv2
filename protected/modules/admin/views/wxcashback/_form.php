							<?php 
	$baseUrl = Yii::app()->baseUrl;
?>
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/plugins/bootstrap-fileupload/bootstrap-fileupload.css" />
<script type="text/javascript" src="<?php echo $baseUrl;?>/plugins/bootstrap-fileupload/bootstrap-fileupload.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/product/jquery.form.js"></script> 
							
							
							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'wxlevel-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							)); ?>
								<div class="form-body">
									<div class="form-group">
										<?php echo $form->label($model, 'ccp_name',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'ccp_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('ccp_name')));?>
											<?php echo $form->error($model, 'ccp_name' )?>
										</div>
									</div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, 'point_type',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'point_type', array( '0' =>yii::t('app','历史积分'),'1' => yii::t('app','有效积分') ) , array('id'=>'point_type', 'class' => 'form-control','placeholder'=>$model->getAttributeLabel('point_type')));?>
											<?php echo $form->error($model, 'point_type' )?>
                                                                                    
										</div>
									</div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, 'min_available_point',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'min_available_point',array('class' => 'form-control','maxLength'=>9,'placeholder'=>$model->getAttributeLabel('min_available_point')));?>
											<?php echo $form->error($model, 'min_available_point' )?>
										</div>
									</div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, 'max_available_point',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'max_available_point',array('class' => 'form-control','maxLength'=>9,'placeholder'=>$model->getAttributeLabel('max_available_point')));?>
											<?php echo $form->error($model, 'max_available_point' )?>
										</div>
									</div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, 'proportion_points',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'proportion_points',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('proportion_points')));?>
											<?php echo $form->error($model, 'proportion_points' )?>
                                                                                    消费一元钱，获得多少返现的比例；如：消费一元获得0.01元，此处就填0.01
										</div>
									</div>
									
									<div class="form-group">
										<label class="control-label col-md-3 ">有效期</label>
										<div class="col-md-8">
											<div class="row">
												 <div style="width:20%;" class="col-md-4">
													 <div class="radio-list">
													  	<label>
															<input type="radio" name="date_info_type"  value="1" checked> 固定日期
														</label>
													  </div>
												  </div>
												  <div class="col-md-8">
												  <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
													 <?php echo $form->textField($model,'begin_timestamp',array('name'=>"begin_timestamp",'class' => 'form-control ui_timepicker','style'=>'width:160px;','placeholder'=>$model->getAttributeLabel('begin_timestamp'))); ?>
													 <span class="input-group-addon"> ~ </span>
													 <?php echo $form->textField($model,'end_timestamp',array('name'=>"end_timestamp",'class'=>'form-control ui_timepicker','style'=>'width:160px;','placeholder'=>$model->getAttributeLabel('end_timestamp'))); ?>
												</div> 
												<!-- /input-group -->
												<?php echo $form->error($model,'begin_timestamp'); ?>
												<?php echo $form->error($model,'end_timestamp'); ?>
													  <!-- <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
														<input type="text" class="form-control" name="begin_timestamp" value="<?php echo date('Y-m-d H:i:s',time());?>" />
														<span class="input-group-addon"> ~ </span>
														<input type="text" class="form-control" name="end_timestamp" value="<?php echo date('Y-m-d H:i:s',time()+24*3600);?>"  />
													  </div> -->
												  </div>
											</div>
											<div class="row last">
												  <div style="width:20%;" class="col-md-4">
													  <div class="radio-list">
													  	<label>
															<input type="radio" name="date_info_type"  value="2" > 领取后，
														</label>
													  </div>
												  </div>
												  <div class="col-md-8">
												    <div class="row input-large">
													   <div class="col-md-4 select left">
														   <select class="form-control" name="fixed_begin_term" disabled="disabled">
																<option value="0">当天</option>
																<?php for($i=1;$i<91;$i++):?>
																<option value="<?php echo $i;?>"><?php echo $i;?>天</option>
																<?php endfor;?>
															</select>
														</div>
														<div class="col-md-4 select middle">&nbsp;&nbsp;生效,有效天数 </div>
														<div class="col-md-4 select left">
															<select class="form-control" name="fixed_term" disabled="disabled">
																<?php for($i=1;$i<37;$i++):?>
																<option value="<?php echo $i;?>" <?php if($i==3) echo 'selected';?>><?php echo $i;?>个月</option>
																<?php endfor;?>
															</select>
														</div>
													</div>
												  </div>
											</div>
										</div>
										  
									</div>
									
                                                                     <!--   <div class="form-group">
										<?php echo $form->label($model, 'is_available',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_available', array( '0' =>yii::t('app','是'),'1' => yii::t('app','否') ) , array('id'=>'is_available', 'class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_available')));?>
											<?php echo $form->error($model, 'is_available' )?>
                                                                                    
										</div>
									</div> --> 
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<a href="<?php echo $this->createUrl('wxcashback/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>