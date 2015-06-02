                                        <?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'padbind-form',
                                                                        'action' => $this->createUrl('pad/bind' , array('companyId' => $this->companyId,'padId'=>$model->lid)),
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
					)); ?>
                                            <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                    <h4 class="modal-title"><?php echo yii::t('app','绑定PAD信息');?></h4>
                                            </div>
                                            <div class="modal-body">
                                                        
								<div class="form-body">
								
                                                                        <div class="form-group">
                                                                                <?php echo $form->label($model, 'name',array('class' => 'col-md-3 control-label'));?>
                                                                                <div class="col-md-4">
                                                                                        <?php echo $form->textField($model, 'name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('name'),'readonly'=>'readonly'));?>
                                                                                        <?php echo $form->error($model, 'name' )?>
                                                                                </div>
                                                                        </div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, 'server_address',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'server_address',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('server_address'),'readonly'=>'readonly'));?>
											<?php echo $form->error($model, 'server_address' )?>
                                                                                    
										</div>
									</div>
                                                                        <div class="form-group">
										<?php echo $form->label($model, 'pad_type',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'pad_type', array('0' => yii::t('app','收银台') , '1' => yii::t('app','点单PAD'),'2'=>yii::t('app','开台PAD')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('pad_type'),'disabled'=>'disabled'));?>
											<?php echo $form->error($model, 'pad_type' )?>
										</div>
									</div>		
                                                                </div>						
                                                </div>
                                    <div class="modal-footer">
                                            <button type="submit" class="btn blue"><?php echo yii::t('app','解除绑定');?></button>
                                            <button type="button" class="btn default" data-dismiss="modal"><?php echo yii::t('app','关闭');?></button>
                                    </div>
                                <?php $this->endWidget(); ?>
                            <script language="JavaScript" type="text/JavaScript">
                                $('#btnPadDisbind').click(function(){ 
                                    var companyId="<?php echo $model->dpid;?>";
                                    var padId="<?php echo $model->lid;?>";                                    
                                    if(Androidwymenuprinter.padDisbind(companyId,padId))
                                    {                                                                    
                                        alert("<?php echo yii::t('app','解除绑定成功！！');?>");
                                        //local.href="";
                                    }
                                    else
                                    {
                                        alert("<?php echo yii::t('app','解除绑定失败，请稍后再试！');?>");                                                                        
                                    }
                                });                                
                            </script>