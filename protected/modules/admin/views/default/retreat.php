						<div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        
                                                        <div class="actions">
                                                        <a href="<?php echo $this->createUrl('retreat/create' , array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> 添加</a>
                                        <a href="javascript:void(0)" class="btn red" onclick="document.getElementById('taste-form').submit();"><i class="fa fa-times"></i> 删除</a>
                                                        </div>
                                                </div>
                                                <div class="modal-body">
                                                        <table class="table table-striped table-bordered table-hover" id="table_retreat">
                                                            <?php if($models):?>
                                                                    <thead>
                                                                            <tr>
                                                                                    <th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>								
                                                                                    <th>名称</th>
                                                                                    <th>备注</th>
                                                                                    <th>&nbsp;</th>
                                                                            </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php foreach ($models as $model):?>
                                                                            <tr class="odd gradeX">
                                                                                    <td><input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="ids[]" /></td>
                                                                                    <td ><?php echo $model->name ;?></td>
                                                                                    <td ><?php   echo $form->textField($model, 'retreat_memo' ,array('class' => 'form-control','placeholder'=>$orderProduct->getAttributeLabel('price')));
                                                                                    ?></td>
                                                                                    <td class="center">
                                                                                    <a href="<?php echo $this->createUrl('retreat/update',array('lid' => $model->lid , 'companyId' => $model->dpid));?>">编辑</a>
                                                                                    </td>
                                                                            </tr>
                                                                    <?php endforeach;?>
                                                                    </tbody>                                                                    
                                                                    <?php endif;?>
                                                            </table>
                                                    </div>
                                                <div class="modal-footer">                                                        
                                                        <button type="button" class="btn default" data-dismiss="modal">确定</button>
                                                </div>
							<script>
                                                         	
							</script>