							<table class="table table-striped table-bordered table-hover" id="sample_1">
                                                                        <?php if($models):?>
                                                                                <thead>
                                                                                        <tr>
                                                                                                <th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
                                                                                                <th>退菜理由</th>
                                                                                                <th>备注</th>
                                                                                        </tr>
                                                                                </thead>
                                                                                <tbody>

                                                                                <?php foreach ($models as $model):?>
                                                                                        <tr class="<?php echo $model->group_no%2==0?'active':'success';?>">
                                                                                                <td><input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="ids[]" /></td>
                                                                                                <td><?php echo $model->name;?></td>
                                                                                                <td class="center"><input type="text" class="form-control input-small" value="<?php echo $model->retreat_memo;?>" placeholder="<?php echo $model->retreat_tip;?>"></td>                                                                                                         
                                                                                        </tr>
                                                                                <?php endforeach;?>
                                                                                </tbody>
                                                                                <?php endif;?>
                                                                        </table>
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