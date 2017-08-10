        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/product/jquery.form.js');?>
			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'productCategory-form',
				'action'=>$action,
				'enableAjaxValidation'=>false,
				'enableClientValidation'=>true,
				'clientOptions'=>array(
					'validateOnSubmit'=>false,
				),
				'htmlOptions'=>array(
					'class'=>'form-horizontal'
				),
			)); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><?php echo yii::t('app','添加商品类目');?></h4>
			</div>
			<div class="modal-body">
				<?php if($model->pid==0):?>
          <div class="form-group <?php if($model->hasErrors('main_picture')) echo 'has-error';?>">
              <?php echo $form->label($model,'main_picture',array('class'=>'control-label col-md-3')); ?>
              <div class="col-md-9">
                  <div class="fileupload fileupload-new" data-provides="fileupload">
                    <div class="fileupload-new thumbnail"  style="max-width: 200px; max-height: 200px; line-height: 20px;">
                      <img src="<?php echo $model->main_picture?$model->main_picture:'';?>" alt="" />
                    </div>
                    <div class="fileupload-preview fileupload-exists thumbnail" id="img1" style="max-width: 200px; max-height: 200px; line-height: 20px;"></div>
                    <div>
                      <span class="btn default btn-file">
                      <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 上传产品图片 </span>
                      <span class="fileupload-exists"><i class="fa fa-undo"></i> 更改 </span>
                      <input type="file" accept="image/png,image/jpg,image/jpeg" name="file" class="default" />
                      </span>
                      <a href="#" class="btn red fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash-o"></i> 移除 </a>
                    </div>
                  </div>
                  <span class="label label-danger">注意:</span>
                  <span>大小：建议300px*300px且不超过10kb 格式:jpg 、png、jpeg </span>
              </div>
              <input type="hidden" name="hidden" value="1" />
              <?php echo $form->hiddenField($model,'main_picture'); ?>
            </div>
				<?php endif;?>
				<div class="form-group">
					<?php echo $form->label($model,'category_name',array('class'=>'col-md-3 control-label')); ?>
					<div class="col-md-9">
						<?php echo $form->hiddenField($model,'pid'); ?>
						<?php echo $form->textField($model,'category_name',array('class'=>'form-control','placeholder'=>$model->getAttributeLabel('category_name'))); ?>
						<?php echo $form->error($model,'category_name',array('class'=>'errorMessage')); ?>
					</div>
				</div>
                <div class="form-group">
                    <?php echo $form->label($model, 'order_num',array('class' => 'col-md-3 control-label'));?>
                    <div class="col-md-4">
                         <?php echo $form->textField($model, 'order_num',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('order_num')));?>
                         <?php echo $form->error($model, 'order_num' )?>
                    </div>
                </div>
                <div class="form-group">
                     <?php echo $form->label($model, 'type',array('class' => 'col-md-3 control-label'));?>
                     <div class="col-md-4">
                          <?php echo $form->dropDownList($model, 'type', array('0' => yii::t('app','是') , '1' => yii::t('app','否')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('type')));?>
                          <?php echo $form->error($model, 'type' )?>
                     </div>
                </div>
                <?php if($model->pid==0):?>
                <div class="form-group">
                     <?php echo $form->label($model, 'cate_type',array('class' => 'col-md-3 control-label'));?>
                     <div class="col-md-4">
                          <?php echo $form->dropDownList($model, 'cate_type', array('0' => yii::t('app','单一类别') , '1' => yii::t('app','公共类别'), '2' => yii::t('app','套餐类别')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('cate_type')));?>
                          <?php echo $form->error($model, 'cate_type' )?>
                     </div>
                </div>
                <?php endif;?>
          		<div class="form-group">
                     <?php echo $form->label($model, 'show_type',array('class' => 'col-md-3 control-label'));?>
                     <div class="col-md-4">
                          <?php echo $form->dropDownList($model, 'show_type', array('1' => yii::t('app','都显示') , '2' => yii::t('app','微信外卖不显示'), '3' => yii::t('app','微信堂食不显示'), '4' => yii::t('app','微信端都不显示')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('show_type')));?>
                          <?php echo $form->error($model, 'show_type' )?>
                     </div>
                </div>
	</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn default"><?php echo yii::t('app','取 消');?></button>
				<input type="submit" class="btn green" id="create_btn" value="<?php echo yii::t('app','确 定');?>">
			</div>
			<?php $this->endWidget(); ?>
               
			<script>
			$('input[name="file"]').change(function(){
            $('form').ajaxSubmit(function(msg){
                var str = msg.substr(0,1);
                // alert(str);
                if (str=='/') {
                    $('#ProductCategory_main_picture').val(msg);
                    layer.msg('图片选择成功!!!');
                }else{
                    layer.msg(msg);
                    $('#img1 img').attr({
                        src: '',
                        width: '2px',
                        height: '2px',
                    });
                }
            });
       });
			</script>