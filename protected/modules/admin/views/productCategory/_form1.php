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
				<div class="form-group">
					<?php echo $form->label($model,'main_picture',array('class'=>'col-md-3 control-label')); ?>
					<div class="col-md-9">
						<?php
						$this->widget('application.extensions.swfupload.SWFUpload',array(
							'callbackJS'=>'swfupload_callback',
							'fileTypes'=> '*.jpg',
							'buttonText'=> yii::t('app','上传类别图片'),
							'companyId' => $model->dpid,
							'imgUrlList' => array($model->main_picture),
						));
						?>
						<?php echo $form->hiddenField($model,'main_picture'); ?>
						<?php echo $form->error($model,'main_picture'); ?>
					</div>
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
                <?php if($model->pid==0):?>
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
                 <?php if(!isset($model->category_name)){?>
                    <div class="form-group" id="div">
                        <label class="col-md-3 control-label" for="category_name">添加二级分类</label>
                             <div class="col-md-9">
                                <input style="width: 160px;" id="input" class="form-control" placeholder="添加二级分类" name="ProductCategory2[][category_name]" type="text" maxlength="45">	                    
                             </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="ProductCategory_show_type">微信端是否显示</label>
                        <div class="col-md-4">
                            <select class="form-control" placeholder="微信端是否显示" name="ProductCategory[show_type]">
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
                                     +'<?php }else{?>'
                                       +'<option value="1" disabled="true">都显示</option>'
                                      +' <option value="2" disabled="true">外卖不显示</option>'
                                     +'  <option value="3" disabled="true">堂食不显示</option>'
                                    +'   <option value="4"selected="true">微信端都不显示</option>'
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
			</script>