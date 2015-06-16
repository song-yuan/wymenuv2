                       	
                                        	<?php $form=$this->beginWidget('CActiveForm', array(
                                                        'id'=>'retreat_form',
                                                        'action' => $this->createUrl('defaultOrder/addRetreat',array('companyId'=>$this->companyId,'orderDetailId'=>$orderRetreat->order_detail_id)),
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
                                                        
                                                </div>
                                                <div class="modal-body">
                                                        <div class="form-actions fluid" id="product_panel">
                                                                <div class="form-group" <?php if($orderRetreat->hasErrors('retreat_id')) echo 'has-error';?>>
                                                                        <?php echo $form->label($orderRetreat, 'retreat_id',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">											
                                                                                <?php echo $form->dropDownList($orderRetreat, 'retreat_id', array('0' => yii::t('app','-- 请选择 --')) +$retreats ,array('class' => 'form-control','placeholder'=>$orderRetreat->getAttributeLabel('retreat_id')));?>
                                                                                <?php echo $form->error($orderRetreat, 'retreat_id' )?>
                                                                        </div>
                                                                </div>                                                      
                                                                 
                                                                <div class="form-group">
                                                                        <?php echo $form->label($orderRetreat, 'retreat_memo',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">
                                                                                <?php echo $form->textField($orderRetreat, 'retreat_memo' ,array('class' => 'form-control','placeholder'=>$orderRetreat->getAttributeLabel('retreat_memo')));?>
                                                                                <?php echo $form->error($orderRetreat, 'retreat_memo' )?>
                                                                        </div>
                                                                </div>
                                                                
                                                        </div><<?php echo yii::t('app','!--订单明细中 退菜、勾挑、优惠、重新厨打///厨打、结单、整单优惠--');?>>
                                                </div>
                                                
                                                <?php echo $form->hiddenField($orderRetreat,'order_detail_id',array('class'=>'form-control')); ?>
                                                
                                                <div class="modal-footer">
                                                        <button type="button" data-dismiss="modal" class="btn default"><?php echo yii::t('app','取 消');?></button>
                                                        <input type="button" class="btn green" id="create_btn_add_retreat" value="<?php echo yii::t('app','确 定');?>">
                                                </div>

                                                <?php $this->endWidget(); ?>
                                        
                    <script type="text/javascript">                           
                        $('#OrderRetreat_retreat_id').change(function(){
                            var id = $(this).val();                            
                                $.ajax({
                                        url:'<?php echo $this->createUrl('defaultOrder/retreatTip',array('companyId'=>$this->companyId));?>/id/'+id,
                                        type:'GET',
                                        dataType:'json',
                                        success:function(result){                                                                                                                                       
                                                $('#OrderRetreat_retreat_memo').val(result.cp); 
                                        }
                                });                            
                        });
                        
                        $('#create_btn_add_retreat').on(event_clicktouchstart,function(){                            
                           // var id = $(this).val();                            
                                $.ajax({
                                        'type':'POST',
					'dataType':'json',
					'data':$('#retreat_form').serialize(),
					'url':$('#retreat_form').attr('action'),
                                        success:function(result){                                                                                                                                       
                                                alert(result.msg);
                                                var $modal=$('#portlet-config');
                                                $modal.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/retreatProduct',array('companyId'=>$this->companyId,'id'=>$orderRetreat->order_detail_id));?>'
                                                ,'', function(){
                                                  //$modal.modal();
                                                  $('#portlet-config2').modal('hide');
                                                });                                                
                                        }
                                });                            
                        });
                    </script>