								<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'noemalpromotion-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions'=>array(
										'class'=>'form-horizontal',
										'enctype'=>'multipart/form-data'
									),
								)); ?>
								<style>
								#category_container select {display:block;float:left;margin-right:3px;max-width:200px;overflow:hidden;}
								</style>
									<div class="form-body">
										<div class="form-group">
											<?php echo $form->label($model,'promotion_title',array('class'=>'col-md-3 control-label')); ?>
											<div class="col-md-4">
												<?php echo $form->textField($model,'promotion_title',array('class'=>'form-control','placeholder'=>$model->getAttributeLabel('promotion_title'))); ?>
												<?php echo $form->error($model,'promotion_title'); ?>
											</div>
										</div>
										<!--  <div class="form-group">
											<?php echo $form->label($model,'cash',array('class'=>'col-md-3 control-label')); ?>
											<div class="col-md-4">
												<div class="input-group">
												<?php echo $form->textField($model,'cash',array('class'=>'form-control','placeholder'=>$model->getAttributeLabel('cash'))); ?>
												<span class="input-group-addon">￥</span>
												</div>
												<?php echo $form->error($model,'cash'); ?>
											</div>
										</div>
										<div class="form-group">
											<?php echo $form->label($model,'stock',array('class'=>'col-md-3 control-label')); ?>
											<div class="col-md-4">
												<?php echo $form->textField($model,'stock',array('class'=>'form-control','placeholder'=>$model->getAttributeLabel('stock'))); ?>
												<?php echo $form->error($model,'stock'); ?>
											</div>
										</div>
										<div class="form-group">
											<?php echo $form->label($model,'count',array('class'=>'col-md-3 control-label')); ?>
											<div class="col-md-4">
												<?php echo $form->textField($model,'count',array('class'=>'form-control','placeholder'=>$model->getAttributeLabel('count'))); ?>
												<?php echo $form->error($model,'count'); ?>
											</div>
										</div>
										<div class="form-group">
											<?php echo $form->label($model,'exchangeable',array('class'=>'col-md-3 control-label')); ?>
											<div class="col-md-4">
												<?php echo $form->dropDownList($model,'exchangeable',array('0'=>'否','1'=>'是'),array('class'=>'form-control','onchange'=>'js:if(parseInt($(this).val())){$("#exchangeable_point").show()}else{$("#exchangeable_point").hide()}')); ?>
												<?php echo $form->error($model,'exchangeable'); ?>
											</div>
										</div>
										<div  id="exchangeable_point" style="<?php if(!$model->exchangeable) echo 'display:none;';?>">
											<div class="form-group">
												<?php echo $form->label($model,'consume_point',array('class'=>'col-md-3 control-label')); ?>
												<div class="col-md-4">
													<?php echo $form->textField($model,'consume_point',array('class'=>'form-control','placeholder'=>$model->getAttributeLabel('consume_point'))); ?>
													<?php echo $form->error($model,'consume_point'); ?>
												</div>
											</div>
											<div class="form-group">
												<?php echo $form->label($model,'activity_point',array('class'=>'col-md-3 control-label')); ?>
												<div class="col-md-4">
													<?php echo $form->textField($model,'activity_point',array('class'=>'form-control','placeholder'=>$model->getAttributeLabel('activity_point'))); ?>
													<?php echo $form->error($model,'activity_point'); ?>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3" for="Cashcard_shopId">选择实体店</label>
											<div class="col-md-9">
												<div class="col-md-3">待选-<a href='javascript:;' id='select-all'>全选</a> </div> 
												<div class="col-md-2">已选-<a href='javascript:;' id='deselect-all'>全不选</a></div>
												<?php if($objects):?>
												<select multiple="multiple" class="multi-select" id="shopId" name="shopId[]">
													<?php foreach($objects as $region):?>
													<optgroup label="<?php echo $region->region_name;?>">
													<?php foreach($region->shop as $shop):?>
													<option value="<?php echo $shop->shop_id;?>" <?php if(in_array($shop->shop_id,$selectedShopIds)) echo 'selected';?>><?php echo $shop->shop_name;?></option>
													<?php endforeach;?>
													</optgroup>
													<?php endforeach;?>
												</select>
												<?php endif;?>
												<?php echo $form->error($model,'shop_flag'); ?>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">商城抵用券有效期限</label>
											<div class="col-md-4">
												<div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
													<?php echo $form->textField($model,'start_time',array('class'=>'form-control','placeholder'=>$model->getAttributeLabel('start_time'))); ?>
													<span class="input-group-addon"> ~ </span>
													<?php echo $form->textField($model,'end_time',array('class'=>'form-control','placeholder'=>$model->getAttributeLabel('end_time'))); ?>
												</div>
												<!-- /input-group --
												<?php echo $form->error($model,'start_time'); ?>
												<?php echo $form->error($model,'end_time'); ?>
											</div>
										</div>
										<div class="form-group">
											<?php echo $form->label($model,'pic',array('class'=>'col-md-3 control-label')); ?>
											<div class="col-md-9">
												<?php  
												$this->widget('application.extensions.uploadImage.UploadImage',array(
														'defaultImg'=>$model->pic?$model->pic:'images/150x150.gif',
														'fieldName'=>'pic',
														'companyId'=>$this->companyId,
														'folder'=>'cashcard',
														'formId'=>'Cashcard',
														'thumb_width' => 640,
														'thumb_height' => 640
												    )
												);
												?>
												<?php echo $form->error($model,'pic'); ?>
											</div>
										</div>
										<div class="form-group">
											<?php echo $form->label($model,'is_exclusive',array('class'=>'col-md-3 control-label')); ?>
											<div class="col-md-9">
												<div class="radio-list">
													<label class="radio-inline">
														<?php echo $form->radioButton($model,'is_exclusive',array('value'=>0,'checked'=>$model->is_exclusive ?false:true));?>
														通用券<br/>
														<small>可在有效期内任意用于指定店铺和商品<br/>&nbsp;</small>
													</label>
													<label class="radio-inline">
														<?php echo $form->radioButton($model,'is_exclusive',array('value'=>1,'checked'=>$model->is_exclusive ?true:false));?>
														限制券<br/>
														<small>满 <?php echo $form->textField($model,'order_consume',array('class'=>'inline-form-control input-small','style'=>'inline-block'));?> 元，可以用一张。<br/>不可与其他优惠活动同时使用</small>
													</label>
												</div>
												<?php echo $form->error($model,'order_consume'); ?>
											</div>
										</div>
										<div class="form-group">
											<?php echo $form->label($model,'intro',array('class'=>'col-md-3 control-label')); ?>
											<div class="col-md-9">
												<?php echo $form->textArea($model,'intro',array('class'=>'form-control','rows'=>5,'placeholder'=>$model->getAttributeLabel('intro'))); ?>
												<?php echo $form->error($model,'intro'); ?>
											</div>
										</div>
										<div class="form-group">
										<label class="control-label col-md-3" ><a href="javarscript:;"  class="cashcard-info alert-link">商城抵用券的使用规则</a></label>
										 <div class="col-md-9 hidden">
										  <div class="alert alert-info">
										    1.	现金券根据适用对象分品牌券和门店券。品牌券所有门店皆可使用，门店券只在相应门店才能使用。<br/>
											2.	现金券根据适用订单金额分通用券和限制券。通用券不论订单金额多少，皆可使用，限制券需要订单总额大于使用的所有的限制券的限额之和才能够使用。<br/>
											3.	根据两对属性，可组成四种券，品牌通用券、品牌限制券、门店通用券、门店限制券。<br/>
											4.	如果券金额超过需要支付的金额则超过的金额部分不产生其他衍生作用，如：退款，找零等。<br/>
											5.	指定到具体门店的券，是不能在品牌订单中使用该券的。<br/>
											6.	在门店选择，全选或者全不选指品牌券，其它指门店券。<br/>
										   </div>
										  </div>
										</div>
										-->
										<div class="form-actions fluid">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-offset-3 col-md-9">
														<button type="submit" class="btn green"><i class="fa fa-check"></i> 确 认</button>
													</div>
												</div>
											</div>
										</div>
								<?php $this->endWidget(); ?>
								<?php $this->widget('ext.kindeditor.KindEditorWidget',array(
									'id'=>'Cashcard_intro',	//Textarea id
									'language'=>'zh_CN',
									// Additional Parameters (Check http://www.kindsoft.net/docs/option.html)
									'items' => array(
										'height'=>'200px',
										'width'=>'100%',
										'themeType'=>'simple',
										'resizeType'=>1,
										'allowImageUpload'=>true,
										'allowFileManager'=>true,
										'items'=>array(
											'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic',
											'underline', 'removeformat', '|', 'justifyleft', 'justifycenter',
											'justifyright', 'insertorderedlist','insertunorderedlist', '|',
											'emoticons', 'image', 'link',),
									),
								)); ?>