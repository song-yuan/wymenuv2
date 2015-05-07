							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'pad-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							)); ?>
								<div class="form-body">
									<div class="form-group  <?php if($model->hasErrors('dpid')) echo 'has-error';?>">
										<?php echo $form->label($model, 'dpid',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'dpid', array('0' => '-- 请选择 --') + Helper::genCompanyOptions() ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('dpid')));?>
											<?php echo $form->error($model, 'dpid' )?>
										</div>
									</div>
									<div class="form-group <?php if($model->hasErrors('lid')) echo 'has-error';?>">
										<?php echo $form->label($model, 'lid',array('class' => 'col-md-3 control-label','id'=>'padId'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'lid', array('0'=>'-- 请选择 --') ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('lid')));?>
											<?php echo $form->error($model, 'lid' )?>
										</div>
									</div>
									
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
                                                                                    <button type="button" class="btn blue" id="btnPadSet">绑 定</button>											                              
										</div>
									</div>
							<?php $this->endWidget(); ?>
                                                        <script language="JavaScript" type="text/JavaScript">
                                                            $('#Pad_dpid').change(function(){
                                                                    var companyid = $(this).val();
                                                                    $.ajax({
                                                                            url:'<?php echo $this->createUrl('pad/getPadList');?>/companyid/'+companyid,
                                                                            type:'GET',
                                                                            dataType:'json',
                                                                            success:function(result){
                                                                                    //alert(result.data);
                                                                                    var str = '<option value="">--请选择--</option>';                                                                                            
                                                                                    if(result.data.length){
                                                                                            $.each(result.data,function(index,value){
                                                                                                    str = str + '<option value="'+value.id+'">'+value.name+'</option>';
                                                                                            });                                                                                                                                                                                                       
                                                                                    }
                                                                                    $('#Pad_lid').html(str); 
                                                                            }
                                                                    });
                                                            });
                                                            
                                                            $('#btnPadSet').click(function(){ 
                                                                var companyId=$('#Pad_dpid').val();
                                                                var padId=$('#Pad_lid').val();
                                                                if(companyId=="0000000000"||padId=="0000000000")
                                                                {
                                                                    alert("请选择店铺和打印机！");
                                                                    return;
                                                                }
                                                                if(Androidwymenuprinter.padSet(companyId,padId))
                                                                {
                                                                    //should deal in android.
                                                                   /* $.ajax({
                                                                        url:'<?php echo $this->createUrl('pad/bind');?>/companyid/'+companyid+'/padid/'+padId,
                                                                        type:'GET',
                                                                        dataType:'json',
                                                                        success:function(result){
                                                                                if(result.data)
                                                                                {
                                                                                    local.href='<?php echo $this->createUrl('pad/index');?>/companyid/'+companyId+'/padid/'+padId;
                                                                                }else{
                                                                                    Androidwymenuprinter.padSet("0000000000","0000000000")
                                                                                }
                                                                        }
                                                                    });*/
                                                                    alert("绑定成功！！");
                                                                }
                                                                else
                                                                {
                                                                    alert("绑定失败，请稍后再试！");                                                                        
                                                                }
                                                            });
                                                        </script>