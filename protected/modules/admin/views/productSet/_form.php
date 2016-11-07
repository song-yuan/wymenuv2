							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'printer-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							)); ?>
								<div class="form-body">
								<?php if(!$model->dpid):?>
									<div class="form-group">
										<?php echo $form->label($model, 'dpid',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'dpid', array('0' => yii::t('app','-- 请选择 --')) +Helper::genCompanyOptions() ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('dpid')));?>
											<?php echo $form->error($model, 'dpid' )?>
										</div>
									</div>
								<?php endif;?>
									<div class="form-group">
										<?php echo $form->label($model, 'type',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'type', array('0' => '套餐' , '1' => '自由组合') , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('type')));?>
											<?php echo $form->error($model, 'type' )?>
										</div>
									</div>
                                    <div class="form-group">
                                            <?php echo $form->label($model, 'set_name',array('class' => 'col-md-3 control-label'));?>
                                            <div class="col-md-4">
                                                    <?php echo $form->textField($model, 'set_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('set_name')));?>
                                                    <?php echo $form->error($model, 'set_name' )?>
                                            </div>
                                    </div>
                                    <div class="form-group">
										<?php echo $form->label($model, 'set_price',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'set_price', array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('set_price')));?>
											<?php echo $form->error($model, 'set_price' )?>
											<span style="color: red;">套餐总价格 = 套餐基础价格 + 套餐明细里的各个菜品价格</span>
										
										</div>
									</div>
									<div class="form-group">
										<?php echo $form->label($model, 'member_price',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'member_price', array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('member_price')));?>
											<?php echo $form->error($model, 'member_price' )?>
											<span style="color: red;">设置该价格只针对会员进行优惠</span>
										
										</div>
									</div>
                                   <div class="form-group">
										<?php echo $form->label($model, 'rank',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'rank', array('1' => '1' , '2' => '2', '3' => '3', '4' => '4', '5' => '5') , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('rank')));?>
											<?php echo $form->error($model, 'rank' )?>
										</div>
									</div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, 'is_member_discount',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_member_discount', array('0' => yii::t('app','否') , '1' => yii::t('app','是')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_member_discount')));?>
											<?php echo $form->error($model, 'is_member_discount' )?>
										</div>
									</div>
                                                                        <!--<div class="form-group">
										<?php echo $form->label($model, 'is_special',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_special', array('0' =>yii::t('app','否')  , '1' => yii::t('app','是')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_special')));?>
											<?php echo $form->error($model, 'is_special' )?>
										</div>
									</div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, 'is_discount',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_discount', array('0' => yii::t('app','否') , '1' => yii::t('app','是')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_discount')));?>
											<?php echo $form->error($model, 'is_discount' )?>
										</div>
									</div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, 'status',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'status', array('0' => yii::t('app','否') , '1' => yii::t('app','是')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('status')));?>
											<?php echo $form->error($model, 'status' )?>
										</div>
									</div>-->
									<div class="form-group <?php if($model->hasErrors('main_picture')) echo 'has-error';?>">
										<?php echo $form->label($model,'main_picture',array('class'=>'control-label col-md-3')); ?>
										<div class="col-md-9">
										<?php
										$this->widget('application.extensions.swfupload.SWFUpload',array(
											'callbackJS'=>'swfupload_callback',
											'fileTypes'=> '*.jpg',
											'buttonText'=> yii::t('app','上传产品图片'),
											'companyId' => $model->dpid,
											'imgUrlList' => array($model->main_picture),
										));
										?>
										<?php echo $form->hiddenField($model,'main_picture'); ?>
										<?php echo $form->error($model,'main_picture'); ?>
										</div>
									</div>                                                                        
									<div class="form-group">
										<?php echo $form->label($model, 'description',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-8">
											<?php echo $form->textArea($model, 'description' , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('description')));?>
											<?php echo $form->error($model, 'description' )?>
										</div>
									</div>
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<a href="<?php echo $this->createUrl('productSet/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>
                                                        <?php $this->widget('ext.kindeditor.KindEditorWidget',array(
								'id'=>'ProductSet_description',	//Textarea id
								'language'=>'zh_CN',
								// Additional Parameters (Check http://www.kindsoft.net/docs/option.html)
								'items' => array(
									'height'=>'200px',
									'width'=>'100%',
									'themeType'=>'simple',
									'resizeType'=>1,
									'allowImageUpload'=>true,
									'allowFileManager'=>true,
								),
							)); ?>
           <script>
	   
	
		function swfupload_callback(name,path,oldname)  {
			$("#ProductSet_main_picture").val(name);
			$("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
		}
	</script>