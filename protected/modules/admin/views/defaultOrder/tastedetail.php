						<?php $form=$this->beginWidget('CActiveForm', array(
                                                        'id'=>'producttaste-form',
                                                        'action' => $this->createUrl('defaultOrder/productTaste',array('companyId'=>$this->companyId,'typeId'=>$typeId,'lid'=>$lid,'isall'=>$isall)),
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
                                                        <div class="clearfix col-md-12">
                                                        <?php if($tastegroups):?>
                                                            
                                                        <?php foreach ($tastegroups as $tastegroup):?> 
                                                            <div class="btn-group" data-toggle="buttons" style="margin-right: 10px;border: 1px solid red;background: rgb(245,230,230);">
                                                                    <?php 
                                                                    $tastes=TasteClass::gettastes($tastegroup['lid'],$this->companyId);
                                                                    foreach ($tastes as $taste):?> 
                                                                    <label tasteid="<?php echo $taste['lid']; ?>" group="tastegroup_<?php echo $tastegroup['lid']; ?>" class="selectTaste btn btn-default <?php if(in_array($taste['lid'],$orderTastes)) echo 'active'; ?>">
                                                                        <input type="checkbox" class="toggle"> <?php echo $taste['name'];?>
                                                                    </label>
                                                                    <?php endforeach;?> 
                                                           </div>
                                                        <?php endforeach;?>                                                                                        
                                                            
                                                        <?php endif;?>                                                            
                                                        </div>                                             
                                                        <div class="form-group">                                                            
                                                            <div class="col-md-12">
                                                                <textarea class="form-control" name="taste_memo" placeholder="<?php echo yii::t('app','请输入其他口味要求');?>" id="Order_remark"><?php echo $tasteMemo; ?></textarea>                                                                                                                                                   
                                                            </div>
                                                        </div>                                                   
                                                        <input class="form-control" name="selectTasteList" id="selectTasteListId" type="hidden" value="">                                                        
                                                    </div>
                                                <div class="modal-footer">
                                                        <button type="button" data-dismiss="modal" class="btn default"><?php echo yii::t('app','取 消');?></button>
                                                        <input type="button" class="btn green" id="addtaste-btn" value="<?php echo yii::t('app','确 定');?>">
                                                </div>

                                        <?php $this->endWidget(); ?>
                                                <script>
                                                    $('.selectTaste').click(function(){
                                                        var groupid=$(this).attr("group");
                                                        var lit=$('label.selectTaste[group="'+groupid+'"]');
                                                        lit.each(function(){
                                                            $(this).removeClass('active');
                                                        });
                                                   });
                                                    
                                                    $('#addtaste-btn').click(function(){
                                                        var selectTasteList="";
                                                        var selectTasteId="";
                                                        $('.selectTaste').each(function(){
                                                            if($(this).hasClass('active'))
                                                            {
                                                                selectTasteId=$(this).attr('tasteid');
                                                                if(selectTasteList.length===0)
                                                                {
                                                                    selectTasteList=selectTasteId;
                                                                }else{
                                                                    selectTasteList=selectTasteList+','+selectTasteId;
                                                                }
                                                            }
                                                        });
                                                        $('#selectTasteListId').val(selectTasteList);
                                                        //alert(selectTasteList);return;
                                                        $('#producttaste-form').submit();
                                                                                                             
                                                   });
                                                </script>