					<?php $form=$this->beginWidget('CActiveForm', array(
                                                        'id'=>'productretreat',
                                                        'enableAjaxValidation'=>true,
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
                                                        <h4 class="modal-title"><?php echo yii::t('app','口味选择');?></h4>
                                                </div>
                                                <div class="modal-body">
                                                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                                                                <?php if($models):?>
                                                                        <thead>
                                                                                <tr>
                                                                                        <th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
                                                                                        <th><?php echo yii::t('app','退菜理由');?></th>
                                                                                        <th><?php echo yii::t('app','备注');?></th>
                                                                                </tr>
                                                                        </thead>
                                                                        <tbody>

                                                                        <?php foreach ($models as $model):?>
                                                                                <tr class="odd gradeX">
                                                                                        <td><input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="ids[]" /></td>
                                                                                        <td><?php echo $model->name;?></td>
                                                                                        <td class="center"><input type="text" class="form-control input-small" value="<?php echo $model->retreat_memo;?>" placeholder="<?php echo $model->retreat_tip;?>"></td>                                                                                                         
                                                                                </tr>
                                                                        <?php endforeach;?>
                                                                        </tbody>
                                                                        <?php endif;?>
                                                                </table>
                                                    </div>
                                                   <div class="modal-footer">
                                                           <button type="button" data-dismiss="modal" class="btn default"><?php echo yii::t('app','取 消');?></button>
                                                           <input type="submit" class="btn green" id="create_btn" value="<?php echo yii::t('app','确 定');?>">
                                                   </div>

                                        <?php $this->endWidget(); ?>
							<script>
                                                         var setlistid=function(){
                                                             var objs=$('.groupradio');
                                                             var setsel='';
                                                             objs.each(function(){
                                                                    //这里是处理 obj 的函数
                                                                    var chk=$(this).val();
                                                                    
                                                                    if($(this).is(':checked'))
                                                                    {
                                                                        if(setsel.length===0)
                                                                        {
                                                                            setsel=chk;
                                                                        }else{
                                                                            setsel=setsel+','+chk;
                                                                        }
                                                                    }                                                                    
                                                                });  
                                                                $('#selsetlistid').val(setsel);
                                                         };
                                                         setlistid();
                                                         $('.groupradio').click(function(){
                                                            setlistid();
                                                         });	
							</script>