
<?php $form=$this->beginWidget('CActiveForm', array(
                'id' => 'company-form',
                'errorMessageCssClass' => 'help-block',
                'htmlOptions' => array(
                        'class' => 'form-horizontal',
                        'enctype' => 'multipart/form-data'
                ),
)); ?>
<style>
        .selectedclass{
                font-size: 14px;
                color: #333333;
                height: 34px;
                line-height: 34px;
                padding: 6px 12px;
        }
</style>
<div class="form-body">
                <div class="form-group">
                        <?php echo $form->label($model, 'dpid',array('class' => 'col-md-3 control-label'));?>
                        <div class="col-md-4">
                                <?php echo $form->textField($model, 'dpid',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('dpid')));?>
                                <?php echo $form->error($model, 'dpid' )?>
                        </div>
                </div>
                <div class="form-group">
                        <?php echo $form->label($model, 'company_name',array('class' => 'col-md-3 control-label'));?>
                        <div class="col-md-4">
                                <?php echo $form->textField($model, 'company_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('company_name')));?>
                                <?php echo $form->error($model, 'company_name' )?>
                        </div>
                </div>
                <div class="form-group">
                        <?php echo $form->label($model, 'contact_name',array('class' => 'col-md-3 control-label'));?>
                        <div class="col-md-4">
                                <?php echo $form->textField($model, 'contact_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('contact_name')));?>
                                <?php echo $form->error($model, 'contact_name' )?>
                        </div>
                </div>
                <div class="form-group">
                        <?php echo $form->label($model, 'mobile',array('class' => 'col-md-3 control-label'));?>
                        <div class="col-md-4">
                                <?php echo $form->textField($model, 'mobile',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('mobile')));?>
                                <?php echo $form->error($model, 'mobile' )?>
                        </div>
                </div>
                <div class="form-group">
                        <?php echo $form->label($model, 'company_address',array('class' => 'col-md-3 control-label'));?>
                        <div class="col-md-4">
                                <?php echo $form->textField($model, 'company_address',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('company_address')));?>
                                <?php echo $form->error($model, 'company_address' )?>
                        </div>
                </div>
                <div class="form-group">
                        <?php echo $form->label($model, 'bank_name',array('class' => 'col-md-3 control-label'));?>
                        <div class="col-md-4">
                                <div class="input-group">
                                        <?php echo $form->textField($model, 'bank_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('bank_name')));?>
                                </div>
                                <?php echo $form->error($model, 'bank_name' )?>
                        </div>
                </div>
                <div class="form-group">
                        <?php echo $form->label($model, 'bank_address',array('class' => 'col-md-3 control-label'));?>
                        <div class="col-md-4">
                                <?php echo $form->textField($model, 'bank_address',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('bank_address')));?>
                                <?php echo $form->error($model, 'bank_address' )?>
                        </div>
                </div>
                <div class="form-group">
                        <?php echo $form->label($model, 'sub_branch_add',array('class' => 'col-md-3 control-label'));?>
                        <div class="col-md-4">
                                <?php echo $form->textField($model, 'sub_branch_add',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('sub_branch_add')));?>
                                <?php echo $form->error($model, 'sub_branch_add' )?>
                        </div>
                </div>
                <div class="form-group">
                        <?php echo $form->label($model, 'account_opening',array('class' => 'col-md-3 control-label'));?>
                        <div class="col-md-4">
                                <?php echo $form->textField($model, 'account_opening',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('account_opening')));?>
                                <?php echo $form->error($model, 'account_opening' )?>
                        </div>
                </div>
                <div class="form-group">
                        <?php echo $form->label($model, 'opening_name',array('class' => 'col-md-3 control-label'));?>
                        <div class="col-md-4">
                                <?php echo $form->textField($model, 'opening_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('opening_name')));?>
                                <?php echo $form->error($model, 'opening_name' )?>
                        </div>
                </div>
                <div class="form-group <?php if($model->hasErrors('photo_head')) echo 'has-error';?>">
                        <?php echo $form->label($model,'photo_head',array('class'=>'control-label col-md-3')); ?>
                        <div class="col-md-9">
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail"  style="max-width: 200px; max-height: 200px; line-height: 20px;">
                                            <img src="<?php echo $model->photo_head?$model->photo_head:'';?>" alt="" />
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 200px; line-height: 20px;"></div>
                                    <div>
                                            <span class="btn default btn-file">
                                            <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 上传照片 </span>
                                            <span class="fileupload-exists"><i class="fa fa-undo"></i> 更改 </span>
                                            <input type="file" accept="image/png,image/jpg,image/jpeg" name="file" class="default" />
                                            </span>
                                            <a href="#" class="btn red fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash-o"></i> 移除 </a>
                                    </div>
                            </div>
                            <span class="label label-danger">注意:</span>
                            <span>大小：建议300px*300px且不超过2M 格式:jpg 、png、jpeg </span>
                        </div>
                        <?php echo $form->hiddenField($model,'photo_head'); ?>
                </div>
                <div class="form-group <?php if($model->hasErrors('photo_indoor')) echo 'has-error';?>">
                        <?php echo $form->label($model,'photo_indoor',array('class'=>'control-label col-md-3')); ?>
                        <div class="col-md-9">
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail"  style="max-width: 200px; max-height: 200px; line-height: 20px;">
                                            <img src="<?php echo $model->photo_indoor?$model->photo_indoor:'';?>" alt="" />
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 200px; line-height: 20px;"></div>
                                    <div>
                                            <span class="btn default btn-file">
                                            <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 上传照片 </span>
                                            <span class="fileupload-exists"><i class="fa fa-undo"></i> 更改 </span>
                                            <input type="file" accept="image/png,image/jpg,image/jpeg" name="file" class="default" />
                                            </span>
                                            <a href="#" class="btn red fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash-o"></i> 移除 </a>
                                    </div>
                            </div>
                            <span class="label label-danger">注意:</span>
                            <span>大小：建议300px*300px且不超过2M 格式:jpg 、png、jpeg </span>
                        </div>
                        <?php echo $form->hiddenField($model,'photo_indoor'); ?>
                </div>
                <div class="form-group <?php if($model->hasErrors('photo_outdoor')) echo 'has-error';?>">
                        <?php echo $form->label($model,'photo_outdoor',array('class'=>'control-label col-md-3')); ?>
                        <div class="col-md-9">
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail"  style="max-width: 200px; max-height: 200px; line-height: 20px;">
                                            <img src="<?php echo $model->photo_outdoor?$model->photo_outdoor:'';?>" alt="" />
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 200px; line-height: 20px;"></div>
                                    <div>
                                            <span class="btn default btn-file">
                                            <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 上传照片 </span>
                                            <span class="fileupload-exists"><i class="fa fa-undo"></i> 更改 </span>
                                            <input type="file" accept="image/png,image/jpg,image/jpeg" name="file" class="default" />
                                            </span>
                                            <a href="#" class="btn red fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash-o"></i> 移除 </a>
                                    </div>
                            </div>
                            <span class="label label-danger">注意:</span>
                            <span>大小：建议300px*300px且不超过2M 格式:jpg 、png、jpeg </span>
                        </div>
                        <?php echo $form->hiddenField($model,'photo_outdoor'); ?>
                </div>
                <div class="form-group <?php if($model->hasErrors('photo_otherone')) echo 'has-error';?>">
                        <?php echo $form->label($model,'photo_otherone',array('class'=>'control-label col-md-3')); ?>
                        <div class="col-md-9">
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail"  style="max-width: 200px; max-height: 200px; line-height: 20px;">
                                            <img src="<?php echo $model->photo_otherone?$model->photo_otherone:'';?>" alt="" />
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 200px; line-height: 20px;"></div>
                                    <div>
                                            <span class="btn default btn-file">
                                            <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 上传照片 </span>
                                            <span class="fileupload-exists"><i class="fa fa-undo"></i> 更改 </span>
                                            <input type="file" accept="image/png,image/jpg,image/jpeg" name="file" class="default" />
                                            </span>
                                            <a href="#" class="btn red fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash-o"></i> 移除 </a>
                                    </div>
                            </div>
                            <span class="label label-danger">注意:</span>
                            <span>大小：建议300px*300px且不超过2M 格式:jpg 、png、jpeg </span>
                        </div>
                        <?php echo $form->hiddenField($model,'photo_otherone'); ?>
                </div>
                <div class="form-group <?php if($model->hasErrors('photo_othertwo')) echo 'has-error';?>">
                        <?php echo $form->label($model,'photo_othertwo',array('class'=>'control-label col-md-3')); ?>
                        <div class="col-md-9">
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail"  style="max-width: 200px; max-height: 200px; line-height: 20px;">
                                            <img src="<?php echo $model->photo_othertwo?$model->photo_othertwo:'';?>" alt="" />
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 200px; line-height: 20px;"></div>
                                    <div>
                                            <span class="btn default btn-file">
                                            <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 上传照片 </span>
                                            <span class="fileupload-exists"><i class="fa fa-undo"></i> 更改 </span>
                                            <input type="file" accept="image/png,image/jpg,image/jpeg" name="file" class="default" />
                                            </span>
                                            <a href="#" class="btn red fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash-o"></i> 移除 </a>
                                    </div>
                            </div>
                            <span class="label label-danger">注意:</span>
                            <span>大小：建议300px*300px且不超过2M 格式:jpg 、png、jpeg </span>
                        </div>
                        <?php echo $form->hiddenField($model,'photo_othertwo'); ?>
                </div>
                <div class="form-group <?php if($model->hasErrors('photo_otherthr')) echo 'has-error';?>">
                        <?php echo $form->label($model,'photo_otherthr',array('class'=>'control-label col-md-3')); ?>
                        <div class="col-md-9">
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail"  style="max-width: 200px; max-height: 200px; line-height: 20px;">
                                            <img src="<?php echo $model->photo_otherthr?$model->photo_otherthr:'';?>" alt="" />
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 200px; line-height: 20px;"></div>
                                    <div>
                                            <span class="btn default btn-file">
                                            <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 上传照片 </span>
                                            <span class="fileupload-exists"><i class="fa fa-undo"></i> 更改 </span>
                                            <input type="file" accept="image/png,image/jpg,image/jpeg" name="file" class="default" />
                                            </span>
                                            <a href="#" class="btn red fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash-o"></i> 移除 </a>
                                    </div>
                            </div>
                            <span class="label label-danger">注意:</span>
                            <span>大小：建议300px*300px且不超过2M 格式:jpg 、png、jpeg </span>
                        </div>
                        <?php echo $form->hiddenField($model,'photo_otherthr'); ?>
                </div>
                <div class="form-actions fluid">
                        <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
                                <a href="<?php echo $this->createUrl('payneedinfo/index', array('companyId' => $this->companyId));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
                        </div>
                </div>
<?php $this->endWidget(); ?>
    <script>
        $('input[name="file"]').change(function(){
		  	$('form').ajaxSubmit(function(msg){
				$('#PayNeedinfo_photo_head').val(msg);
			});
	   });
            $('input[name="file"]').change(function(){
		  	$('form').ajaxSubmit(function(msg){
				$('#PayNeedinfo_photo_indoor').val(msg);
			});
	   });
            $('input[name="file"]').change(function(){
		  	$('form').ajaxSubmit(function(msg){
				$('#PayNeedinfo_photo_outdoor').val(msg);
			});
	   });
            $('input[name="file"]').change(function(){
		  	$('form').ajaxSubmit(function(msg){
				$('#PayNeedinfo_photo_otherone').val(msg);
			});
	   });
            $('input[name="file"]').change(function(){
		  	$('form').ajaxSubmit(function(msg){
				$('#PayNeedinfo_photo_othertwo').val(msg);
			});
	   });
            $('input[name="file"]').change(function(){
		  	$('form').ajaxSubmit(function(msg){
				$('#PayNeedinfo_photo_otherthr').val(msg);
			});
	   });
    </script>