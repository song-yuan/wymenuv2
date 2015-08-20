						
                                                        <div class="clearfix col-md-12">
                                                        <?php if($tastegroups):?>
                                                            
                                                        <?php foreach ($tastegroups as $tastegroup):?> 
                                                            <div class="btn-group" data-toggle="buttons" style="margin: 5px;border: 1px solid red;background: rgb(245,230,230);">
                                                                    <?php 
                                                                    $tastes=TasteClass::gettastes($tastegroup['lid'],$this->companyId);
                                                                    foreach ($tastes as $taste):?> 
                                                                    <label tasteid="<?php echo $taste['lid']; ?>" group="tastegroup_<?php echo $tastegroup['lid']; ?>" class="selectTaste btn btn-default <?php if(in_array($taste['lid'],$orderTastes)) echo 'active';?>">
                                                                        <input type="checkbox" class="toggle"> <?php echo $taste['name'];?>
                                                                    </label>
                                                                    <?php endforeach;?> 
                                                           </div>
                                                        <?php endforeach;?>                                                                                        
                                                            
                                                        <?php endif;?>                                                            
                                                        </div>                                             
                                                        <div class="form-group">                                                            
                                                            <div class="col-md-12">
                                                                <textarea class="form-control" name="taste_memo" placeholder="<?php echo yii::t('app','请输入其他口味要求');?>" id="Order_remark_taste"><?php echo $tasteMemo; ?></textarea>                                                                                                                                                   
                                                            </div>
                                                        </div>                                                   
                                                        
                                                <script>
                                                    $(document).ready(function () {
                                                        var tasteids=$("#spanTasteIds").text();
                                                        var tastememo=$("#spanTasteMemo").text();;
                                                        $.each(tasteids.split("|"),function(index,data){
//                                                            if($(".selectTaste[tasteid="+data+"]").hasClass("active"))
//                                                            {
//                                                                $(".selectTaste[tasteid="+data+"]").removeClass("active");
//                                                            }else{
                                                                $(".selectTaste[tasteid="+data+"]").addClass("active");
//                                                            }
                                                        });
                                                        $("#Order_remark_taste").val(tastememo);
                                                    });
                                                        

                                                    $('.selectTaste').click(function(){
                                                        var groupid=$(this).attr("group");
                                                        var lit=$('label.selectTaste[group="'+groupid+'"]');
                                                        var chk=$(this).hasClass("active");
                                                        //alert(chk);
                                                        lit.each(function(){
                                                            $(this).removeClass('active');
                                                        });
                                                        if(chk)
                                                        {
                                                            return false;
                                                        }
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