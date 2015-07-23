
<style>

.navigation {
    width:200px;
    font-family:Arial;
    float: left;
}
.navigation ul {
    list-style-type:none;                /* 不显示项目符号 */
    margin:0px;
    padding:0px;
}
.navigation li {
    border-bottom:1px solid #ED9F9F;    /* 添加下划线 */
    font-size: 18px;
}
.navigation li a{
    display:block;                        /* 区块显示 */
    padding:8px 8px 8px 0.5em;
    text-decoration:none;
    border-left:12px solid #711515;        /* 左边的粗红边 */
    border-right:1px solid #711515;        /* 右侧阴影 */
}
.navigation li a:link, .navigation li a:visited{
    background-color:#c11136;
    color:#FFFFFF;
}
.navigation li a:hover{                    /* 鼠标经过时 */
    background-color:#990020;            /* 改变背景色 */
    color:#ffff00;                        /* 改变文字颜色 */
}
.clear{
    clear: both;
}
</style>




                       			<?php $form=$this->beginWidget('CActiveForm', array(
                                                        'id'=>'orderProduct',
                                                        'action' => $this->createUrl('defaultOrder/addProduct',array('companyId'=>$this->companyId,'typeId'=>$typeId,'orderId'=>$orderId,'isset'=>$isset)),
                                                        'enableAjaxValidation'=>true,
                                                        'enableClientValidation'=>true,
                                                        'clientOptions'=>array(
                                                                'validateOnSubmit'=>false,
                                                        ),
                                                        'htmlOptions'=>array(
                                                                'class'=>'form-horizontal'
                                                        ),
                                                )); ?>                                                
                                                
                                                <div class="modal-body">
                                                                <div style="position:absolute; width: 97%;height: 98%; background: #fff;color: #555555;;border-radius: 0 !important;box-sizing: border-box;">
                                                                    <div class="navigation">
                                                                        <ul>
                                                                            <li><a href="#">Home</a></li>
                                                                            <li><a href="#">My Blog</a></li>
                                                                            <li><a href="#">Friends</a></li>
                                                                            <li><a href="#">Next Station</a></li>
                                                                            <li><a href="#">Contact Me</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="navigation">
                                                                        <ul>
                                                                            <li><a href="#">Home</a></li>
                                                                            <li><a href="#">My Blog</a></li>
                                                                            <li><a href="#">Friends</a></li>
                                                                            <li><a href="#">Next Station</a></li>
                                                                            <li><a href="#">Contact Me</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="navigation">
                                                                        <ul>
                                                                            <li><a href="#">Home</a></li>
                                                                            <li><a href="#">My Blog</a></li>
                                                                            <li><a href="#">Friends</a></li>
                                                                            <li><a href="#">Next Station</a></li>
                                                                            <li><a href="#">Contact Me</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="navigation" style="float:right;height: 100%;">
                                                                        <div style="float:right;">
                                                                            <input type="submit" class="btn green" id="create_btn" value="<?php echo yii::t('app','确 定');?>">
                                                                            <button type="button" data-dismiss="modal" class="btn default"><?php echo yii::t('app','取 消');?></button>
                                                                        </div>
                                                                        <div class="clear"></div>
                                                                        <div>
                                                                            <ul>
                                                                                <li><a href="#">Home</a></li>
                                                                                <li><a href="#">My Blog</a></li>
                                                                                <li><a href="#">Friends</a></li>
                                                                                <li><a href="#">Next Station</a></li>
                                                                                <li><a href="#">Contact Me</a></li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                    <div class="navigation" style="float:right;height: 100%;">
                                                                        <div>
                                                                            <ul>
                                                                                <li><a href="#">1Home</a></li>
                                                                                <li><a href="#">2My Blog</a></li>
                                                                                <li><a href="#">3Friends</a></li>
                                                                                <li><a href="#">4Next Station</a></li>
                                                                                <li><a href="#">5Contact Me</a></li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                        <div class="form-actions fluid <?php if($isset=='1') echo 'hidden';?>" id="product_panel">
                                                                <div class="form-group">
                                                                        <?php echo $form->label($orderProduct, 'category_id',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">
                                                                                <?php echo CHtml::dropDownList('selectCategory', '0', $categories , array('class'=>'form-control'));?>
                                                                        </div>
                                                                </div>

                                                                <div class="form-group" <?php if($orderProduct->hasErrors('product_id')) echo 'has-error';?>>
                                                                        <?php echo $form->label($orderProduct, 'product_id',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">											
                                                                                <?php echo $form->dropDownList($orderProduct, 'product_id', array('0' => yii::t('app','-- 请选择 --')) +$products ,array('class' => 'form-control','placeholder'=>$orderProduct->getAttributeLabel('dpid')));?>
                                                                                <?php echo $form->error($orderProduct, 'product_id' )?>
                                                                        </div>
                                                                </div>                                                      
                                                                <div class="form-group">
                                                                        <?php echo $form->label($orderProduct, 'amount',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-2">
                                                                                <?php echo $form->textField($orderProduct, 'amount' ,array('class' => 'form-control','placeholder'=>$orderProduct->getAttributeLabel('amount')));?>
                                                                                <?php echo $form->error($orderProduct, 'amount' )?>
                                                                        </div>
                                                                
                                                                        <?php echo $form->label($orderProduct, 'zhiamount',array('class' => 'col-md-2 control-label'));?>
                                                                        <div class="col-md-2">
                                                                                <?php echo $form->textField($orderProduct, 'zhiamount' ,array('class' => 'form-control','placeholder'=>$orderProduct->getAttributeLabel('zhiamount')));?>
                                                                                <?php echo $form->error($orderProduct, 'zhiamount' )?>
                                                                        </div>                                                  
                                                                        
                                                                </div> 
                                                                <div class="form-group">
                                                                        <?php echo $form->label($orderProduct, 'price',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-3">
                                                                                <?php echo $form->textField($orderProduct, 'price' ,array('class' => 'form-control','placeholder'=>$orderProduct->getAttributeLabel('price')));?>
                                                                                <?php echo $form->error($orderProduct, 'price' )?>
                                                                        </div>
                                                                        
                                                                        <div class="col-md-3">
                                                                                <?php echo $form->dropDownList($orderProduct, 'is_giving', array('0' => yii::t('app','不赠送' ), '1' => yii::t('app','赠送')) , array('class' => 'form-control','placeholder'=>$orderProduct->getAttributeLabel('is_giving')));?>
                                                                                <?php echo $form->error($orderProduct, 'is_giving' )?>
                                                                        </div>
                                                                        <div class="col-md-4"></div>
                                                                        <div class="col-md-4"><span class="label label-default center"><?php echo yii::t('app','原价');?></span></div>
                                                                </div>
                                                                
                                                        </div><!--订单明细中 退菜、勾挑、优惠、重新厨打///厨打、结单、整单优惠-->
                                                </div>
                                                <div class="form-actions fluid hidden <?php if($isset=='0') echo 'hidden';?>" id="set_panel">
                                                                <div class="form-group">
                                                                        <?php echo $form->label($orderProduct, 'set_id',array('class' => 'col-md-4 control-label'));?>
                                                                        <div class="col-md-6">
                                                                                <?php echo CHtml::dropDownList('setlist', '0', $setlist , array('class'=>'form-control'));?>
                                                                        </div>
                                                                </div>
                                                                <div class="portlet-body" id="table-set-detail">
                                                                                                                                                        
                                                                </div>
                                                                <!--list-->                                                             
                                                </div>
                                                <?php echo $form->hiddenField($orderProduct,'order_id',array('class'=>'form-control')); ?>
                                                <?php echo $form->hiddenField($orderProduct,'set_id',array('class'=>'form-control')); ?>
                                                <input class="form-control" name="selsetlist" id="selsetlistid" type="hidden" value="">
                                                

                                                <?php $this->endWidget(); ?>                
                    <script type="text/javascript">
                            var isset='<?php echo $isset; ?>';
                            $('#selectCategory').change(function(){
                                        var cid = $(this).val();
                                        //alert('<?php echo $this->createUrl('productSet/getChildren',array('companyId'=>$this->companyId));?>/pid/'+cid);
                                        //alert($('#ProductSetDetail_product_id').html());
                                        $.ajax({
                                                url:'<?php echo $this->createUrl('productSet/getChildren',array('companyId'=>$this->companyId));?>/pid/'+cid,
                                                type:'GET',
                                                dataType:'json',
                                                success:function(result){
                                                        //alert(result.data);
                                                        var str = '<option value=""><?php echo yii::t('app','-- 请选择 --');?></option>';                                                                                            
                                                        if(result.data.length){
                                                                //alert(1);
                                                                $.each(result.data,function(index,value){
                                                                        str = str + '<option value="'+value.id+'">'+value.name+'</option>';
                                                                });                                                                                                                                                                                                       
                                                        }
                                                        $('#OrderProduct_product_id').html(str); 
                                                }
                                        });
                                });
                        $('#create_btn').on(event_clicktouchstart,function(){
                            //alert($('#setlist').val());
                            if(isset=='0' && $('#OrderProduct_product_id').val()=='0')
                            {
                                alert("<?php echo yii::t('app','请选择产品！');?>");
                                return false;
                            }
                            if(isset=='1' && $('#setlist').val()=='0')
                            {
                                alert("<?php echo yii::t('app','请选择套餐！');?>");
                                return false;
                            }
                        });
                        $('#btn_product').on(event_clicktouchstart,function(){
                                $('#btn_product').removeClass('grey');
                                $('#btn_product').addClass('purple');
                                $('#btn_set').removeClass('purple');
                                $('#btn_set').addClass('grey');
                                $('#set_panel').addClass('hidden');
                                $('#product_panel').removeClass('hidden');
                                $('#isetid').val('0');
                        });
                        $('#btn_set').on(event_clicktouchstart,function(){
                                $('#btn_set').removeClass('grey');
                                $('#btn_set').addClass('purple');
                                $('#btn_product').removeClass('purple');
                                $('#btn_product').addClass('grey');
                                $('#product_panel').addClass('hidden');
                                $('#set_panel').removeClass('hidden');
                                $('#isetid').val('1');
                        });
                        $('#setlist').change(function(){
                            id = $(this).val();
                            $('#OrderProduct_set_id').val(id);
                            $('#table-set-detail').load('<?php echo $this->createUrl('defaultOrder/setdetail',array('companyId'=>$this->companyId));?>/id/'+id);
                            //alert('<?php echo $this->createUrl('defaultOrder/setdetail',array('companyId'=>$this->companyId));?>/id/'+id);
                        });
                        $('#OrderProduct_product_id').change(function(){
                            var id = $(this).val();                            
                                $.ajax({
                                        url:'<?php echo $this->createUrl('defaultOrder/currentprice',array('companyId'=>$this->companyId));?>/id/'+id,
                                        type:'GET',
                                        dataType:'json',
                                        success:function(result){                                                                                                                                       
                                                $('#OrderProduct_price').val(result.cp); 
                                        }
                                });                            
                        });
                        
                        
                    </script>