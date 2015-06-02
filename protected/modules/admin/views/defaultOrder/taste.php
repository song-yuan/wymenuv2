			
						<?php $form=$this->beginWidget('CActiveForm', array(
                                                        'id'=>'producttaste-form',
                                                        'action' => $this->createUrl('defaultOrder/productTaste',array('companyId'=>$$companyId,'typeId'=>$typeId,'lid'=>$lid)),
                                                        'enableAjaxValidation'=>true,
                                                        //'method'=>'POST',
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
                                                        <div class="clearfix">
								<div class="btn-group" data-toggle="buttons">
									<label class="btn btn-default">
									<input type="checkbox" class="toggle"> Option 1
									</label>
									<label class="btn btn-default  active">
									<input type="checkbox" class="toggle"> Option 2
									</label>
									<label class="btn btn-default">
									<input type="checkbox" class="toggle"> Option 3
									</label>
								</div>
							</div>
                                                <div class="modal-footer">
                                                        
                                                        <button type="button" data-dismiss="modal" class="btn default"><?php echo yii::t('app','取 消');?></button>
                                                        <input type="submit" class="btn green" id="create_btn" value="<?php echo yii::t('app','确 定');?>">
                                                </div>

                                                <?php $this->endWidget(); ?>
					
			
			<script type="text/javascript">
                            
                        </script>