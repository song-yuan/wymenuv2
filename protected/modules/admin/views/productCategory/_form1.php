        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/product/jquery.form.js');?>			<?php $form=$this->beginWidget('CActiveForm', array(
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
				<h4 class="modal-title"><?php echo yii::t('app','添加商品类目');?><span id="ntice1"></span></h4>
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
                     <label class="col-md-3 control-label" for="ProductCategory_show_type">微信端是否显示</label>
                     <div class="col-md-4">
                         <select class="form-control" placeholder="微信端是否显示" name="ProductCategory[show_type]" id="ProductCategory_show_type">
                         <?php if(Yii::app()->user->role < User::SHOPKEEPER){?>
                                <option value="1" selected="selected">都显示</option>
                                <option value="2">外卖不显示</option>
                                <option value="3">堂食不显示</option>
                                <option value="4">微信端都不显示</option>
                              <?php }else{?>
                                <option value="1" disabled="true">都显示</option>
                                <option value="2" disabled="true">外卖不显示</option>
                                <option value="3" disabled="true">堂食不显示</option>
                                <option value="4"selected="true">微信端都不显示</option>
                                <?php }?>
                          </select>
                     </div>
                </div>
                <?php if($model->pid==0):?>
                 <?php if(!isset($model->category_name)){?>
                    <div class="form-group" id="div">
                        <label class="col-md-3 control-label" for="category_name">添加二级分类</label>
                             <div class="col-md-9">
                                <input style="width: 160px;" id="input" class="form-control" placeholder="添加二级分类" name="ProductCategory2[][category_name]" type="text" maxlength="45">	                    
                             </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="ProductCategory_show_type">终端显示</label>
                        <div class="col-md-4">
                            <select class="form-control" placeholder="微信端是否显示" name="ProductCategory[show_type]">
                            <?php if(Yii::app()->user->role < User::SHOPKEEPER){?>
                                   <option value="1" selected="selected">都显示</option>
                                   <option value="2">外卖不显示</option>
                                   <option value="3">堂食不显示</option>
                                   <option value="4">微信端都不显示</option>
                                   <option value="5">POS端不显示</option>
                                   <option value="6">都不显示</option>
                                 <?php }else{?>
                                   <option value="1" disabled="true">都显示</option>
                                   <option value="2" disabled="true">外卖不显示</option>
                                   <option value="3" disabled="true">堂食不显示</option>
                                   <option value="4" selected="true">微信端都不显示</option>
                                   <option value="5" disabled="true">POS端不显示</option>
                                   <option value="6" disabled="true">都不显示</option>
                                   <?php }?>
                             </select>
                        </div>
                    </div>
                    <div class="form-group " id="div1">
                        <label class="col-md-3 control-label" for="ProductCategory_order_num">显示顺序</label>                    
                        <div class="col-md-4 ">
                           <input class="form-control" id="input1" placeholder="显示顺序" name="ProductCategory3[]" type="text" maxlength="4" value="0">                        
                        </div>
                    </div>
                    <a style="margin-left: 320px;"  class="btn btn-xs green add_btn"  data-toggle="modal"><i class="fa fa-plus"></i></a>
                    
                   <?php }?>
                 <?php endif;?>           
	</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn default"><?php echo yii::t('app','取 消');?></button>
				<input type="submit" class="btn green" id="create_btn" value="<?php echo yii::t('app','确 定');?>">
			</div>
			<?php $this->endWidget(); ?>
               
			<script>
			function swfupload_callback(name,path,oldname)  {
				$("#ProductCategory_main_picture").val(name);
				$("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
			}
    $(function(){
        $(".add_btn").click(function(){
           $("#div1:first").after('<div>'
   +'<div class="form-group div"><label class="col-md-3 control-label" for="category_name">添加二级分类</label> <div class="col-md-9"><input style="width: 160px;" id="input" class="form-control" placeholder="添加二级分类" name="ProductCategory2[][category_name]" type="text" maxlength="45"></div></div>'
    +'<div class="form-group">'
        +'<label class="col-md-3 control-label" for="ProductCategory_show_type">微信端是否显示</label>'
        +'<div class="col-md-4">'
            +'<select class="form-control" placeholder="微信端是否显示" name="ProductCategory[show_type]">'
            +'<?php if(Yii::app()->user->role < User::SHOPKEEPER){?>'
                   +'<option value="1" selected="selected">都显示</option>'
                   +'<option value="2">外卖不显示</option>'
                   +'<option value="3">堂食不显示</option>'
                  +' <option value="4">微信端都不显示</option>'
                  +' <option value="5">POS端不显示</option>'
                  +' <option value="6">都不显示</option>'
                 +'<?php }else{?>'
                   +'<option value="1" disabled="true">都显示</option>'
                  +' <option value="2" disabled="true">外卖不显示</option>'
                 +'  <option value="3" disabled="true">堂食不显示</option>'
                +'   <option value="4" selected="true">微信端都不显示</option>'
                +' <option value="5" disabled="true">POS端不显示</option>'
                +' <option value="6" disabled="true">都不显示</option>'
                 +'  <?php }?>'
            +' </select>'
        +'</div>'
+' </div>'
   +'<div class="form-group div1">'+
    '<label class="col-md-3 control-label" for="ProductCategory_order_num">显示顺序</label>'                    
   + '<div class="col-md-4 ">'
       +'<input class="form-control" id="input1" placeholder="显示顺序" name="ProductCategory3[]" type="text" maxlength="4" value="0">'                        
    +'</div>'
+ ' </div>'
+'<a style="margin-left: 300px;" class="btn btn-xs red btn_delete"><i class="fa fa-times"></i></a>'
+'</div>');
        });
        $(".btn_delete").live("click",function(){
            $(this).parent().remove();
        });
      });
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