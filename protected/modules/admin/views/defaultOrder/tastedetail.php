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
                                                        <h4 class="modal-title">口味选择</h4>
                                                </div>
                                                <div class="modal-body">
                                                        <div class="clearfix col-md-12">
                                                        <?php if($tastes):?>
                                                            <div class="btn-group" data-toggle="buttons">
                                                        <?php foreach ($tastes as $taste):?>                                                                                       
                                                                <label tasteid="<?php echo $taste['lid']; ?>" class="selectTaste btn btn-default  <?php if(in_array($taste['lid'],$orderTastes)) echo 'active'; ?>">
                                                                    <input type="checkbox" class="toggle"> <?php echo $taste['name'];?>
                                                                </label>
                                                        <?php endforeach;?>                                                                                        
                                                            </div>
                                                        <?php endif;?>                                                            
                                                        </div>                                             
                                                        <div class="form-group">
                                                            <label class ='col-md-6 control-label'>其他口味</label><br>
                                                            <div class="col-md-12">
                                                                <textarea class="form-control" name="taste_memo" id="Order_remark"><?php echo $tasteMemo; ?></textarea>                                                                                                                                                   
                                                            </div>
                                                        </div>                                                   
                                                        <input class="form-control" name="selectTasteList" id="selectTasteListId" type="hidden" value="">                                                        
                                                    </div>
                                                <div class="modal-footer">
                                                        <button type="button" data-dismiss="modal" class="btn default">取 消</button>
                                                        <input type="button" class="btn green" id="addtaste-btn" value="确 定">
                                                </div>

                                        <?php $this->endWidget(); ?>
                                                <script>
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