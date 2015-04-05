						<?php $form=$this->beginWidget('CActiveForm', array(
                                                        'id'=>'order',
                                                        'action' => $this->createUrl('default/producttaste',array('companyId'=>$this->companyId,'typeId'=>$typeId)),
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
                                                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                                                                <?php if($models):?>
                                                                        <div class="clearfix">
                                                                            <div class="btn-group" data-toggle="buttons">
                                                                        <?php foreach ($models as $model):?>                                                                                       
                                                                                        <label class="btn btn-default  <?php if(!empty($model['order_id'])) echo active; ?>">
                                                                                            <input type="checkbox" class="toggle"> <?php echo $model['name'];?>
                                                                                        </label>
                                                                         <?php endforeach;?>                                                                                        
                                                                            </div>
                                                                            <div class="form-group">
                                                                                    <?php echo $form->label($orderProduct, 'taste_memo',array('class' => 'col-md-4 control-label'));?>
                                                                                    <div class="col-md-6">
                                                                                            <?php echo $form->textArea($orderProduct, 'taste_memo' ,array('class' => 'form-control','placeholder'=>$orderProduct->getAttributeLabel('taste_memo')));?>
                                                                                            <?php echo $form->error($orderProduct, 'taste_memo' )?>
                                                                                    </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php endif;?>
                                                                </table>
                                                        </div>
                                                   <div class="modal-footer">
                                                           <button type="button" data-dismiss="modal" class="btn default">取 消</button>
                                                           <input type="submit" class="btn green" id="create_btn" value="确 定">
                                                   </div>

                                        <?php $this->endWidget(); ?>
                                                <script>

                                                </script>