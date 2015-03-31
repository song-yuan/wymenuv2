			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'siteNo',
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
				<h4 class="modal-title">结单</h4>
			</div>
			<div class="modal-body">
				<div class="form-actions fluid">
                                        <div class="form-group">
                                                <?php echo $form->label($model, 'reality_total',array('class' => 'col-md-3 control-label'));?>
                                                <div class="col-md-2">
                                                        <?php echo $form->textField($model, 'reality_total' ,array('value'=>$total['total'],'class' => 'form-control','placeholder'=>$model->getAttributeLabel('reality_total')));?>
                                                        <?php echo $form->error($model, 'reality_total' )?>
                                                </div>
                                        </div>
                                        <div class="form-group">
                                                <?php echo $form->label($model, 'payment_method_id',array('class' => 'col-md-3 control-label'));?>
                                                <div class="col-md-2">
                                                        <?php echo $form->dropDownList($model, 'payment_method_id' ,$paymentMethods ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('payment_method_id')));?>
                                                        <?php echo $form->error($model, 'payment_method_id' )?>
                                                </div>
                                        </div>
                                        <div class="form-group">
                                                <?php echo $form->label($model, 'remark',array('class' => 'col-md-3 control-label'));?>
                                                <div class="col-md-4">
                                                        <?php echo $form->textArea($model, 'remark' ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('remark')));?>
                                                        <?php echo $form->error($model, 'remark' )?>
                                                </div>
                                        </div>
                                        <?php echo $form->hiddenField($model , 'order_status' , array('value'=>1));?>

                                               
                                        </div><!--订单明细中 退菜、勾挑、优惠、重新厨打///厨打、结单、整单优惠-->
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn default">取 消</button>
				<input type="submit" class="btn green" id="create_btn" value="确 定">
			</div>
                        
			<?php $this->endWidget(); ?>
                        <script type="text/javascript">
                            $(document).ready(function() {
                                //alert($('#site_number')[0]);
                                var sno=$("#site_number");
                                if(sno.length > 0)
                                {
                                    sno[0].focus();
                                }
                            });
                           $('#site_open').click(function(){
                               var siteNumber=$('#site_number').val();
                               var sid = $(this).attr('sid');
                               var istemp = $(this).attr('istemp');
                               //alert(istemp);
                               if(!isNaN(siteNumber) && siteNumber>0 && siteNumber < 99)
                               {
                                   //alert(!isNaN(siteNumber));
                                    $.ajax({
					'type':'POST',
					'dataType':'json',
					'data':{"sid":sid,"siteNumber":siteNumber,"companyId":'<?php echo $this->companyId; ?>',"istemp":'<?php echo $istemp; ?>'},
					'url':'<?php echo $this->createUrl('default/opensite',array());?>',
					'success':function(data){
						if(data.status == 0) {
							alert(data.message);
						} else {
							alert(data.message);
							location.href='<?php echo $this->createUrl('default/index',array('companyId'=>$this->companyId,'typeId'=>$typeId));?>';
						}
					}
                                    });
                                    return false;
                               }else{
                                   alert("输入合法人数");
                               }
                           });
                           
                           $('.closesite').on('click',function(){
                               var sid = $(this).attr('sid');
                               $.ajax({
                                    'type':'POST',
                                    'dataType':'json',
                                    'data':{"sid":sid,"companyId":'<?php echo $this->companyId; ?>',"istemp":'<?php echo $istemp; ?>'},
                                    'url':'<?php echo $this->createUrl('default/closesite',array());?>',
                                    'success':function(data){
                                            if(data.status == 0) {
                                                    alert(data.message);
                                            } else {
                                                    alert(data.message);
                                                    location.href='<?php echo $this->createUrl('default/index',array('companyId'=>$this->companyId,'typeId'=>$typeId));?>';
                                            }
                                    }
                                });
                                return false;                               
                           });
                           
                           $('.switchsite').on('click',function(){
                               //var sid = $(this).attr('sid');
                               var statu = confirm("确定换台吗？");
                                if(!statu){
                                    return false;
                                }  
                                location.href='<?php echo $this->createUrl('default/index',array('companyId'=>$this->companyId,'typeId'=>$typeId,'op'=>'switch','sistemp'=>$istemp,'ssid'=>$sid,'stypeId'=>$typeId));?>';
                           });                           
                           
                           $('.orderaction').on('click',function(){
                               var sid = $(this).attr('sid');
                               var istemp = $(this).attr('istemp');
                               //alert(istemp);
                               location.href='<?php echo $this->createUrl('default/order',array('companyId'=>$this->companyId));?>'+'/sid/'+sid+'/istemp/'+istemp;
                           });
                        </script>