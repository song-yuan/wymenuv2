						<div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        
                                                        <div class="actions">
                                                        <a href="javascript:void(0);" class="btn blue" id="btn-retreat-add"><i class="fa fa-pencil"></i> 添加</a>
                                                        <a href="javascript:void(0);" class="btn red" id="btn-retreat-delete"><i class="fa fa-times"></i> 删除</a>
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
                                                                                    <td ><?php echo $model->retreat->name ;?></td>
                                                                                    <td><?php echo $model->retreat_memo ;?></td>
                                                                                    <td class="center">
                                                                                    <a href="javascript:void(0);" class="btn-over btn green edit_retreat_btn" orderRetreatId="<?php echo $model->lid; ?>">编辑</a>
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
                                                            $('#btn-retreat-add').click(function(){
                                                               var orderDetailId = '<?php echo $orderDetailId; ?>';
                                                               var $modal=$('#portlet-config2');
                                                               $modal.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/addRetreat',array('companyId'=>$this->companyId));?>/orderDetailId/'+orderDetailId
                                                               ,'', function(){
                                                                 $modal.modal();
                                                               });
                                                            });
                                                            
                                                            $('.edit_retreat_btn').click(function(){
                                                               var orderRetreatId = $(this).attr('orderRetreatId');
                                                               //alert(orderRetreatId);
                                                               var $modal=$('#portlet-config2');
                                                               $modal.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/editRetreat',array('companyId'=>$this->companyId));?>/orderRetreatId/'+orderRetreatId
                                                               ,'', function(){
                                                                 $modal.modal();
                                                               });
                                                            });
							</script>