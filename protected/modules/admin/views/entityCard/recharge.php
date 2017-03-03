<style>
@media (max-width: 992px) {    
.portlet-body .row{
   margin-top: 15px;
}
}
@media (min-width: 992px) {
.input-group{
    margin-top: 20px;
    margin-left: 230px;
    margin-bottom: 20px;
}
}
.form-horizontal .control-label{
    margin-right: 5px;
}
.form-horizontal .form-group {
    margin-right: 30px;
    margin-left: 10px;
}

.form-body {
    padding-top: 0px!important;
  
}
</style>

<div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
    <div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                    <div class="modal-content">
                            <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h4 class="modal-title">Modal title</h4>
                            </div>
                            <div class="modal-body">
                                    Widget settings form goes here
                            </div>
                            <div class="modal-footer">
                                    <button type="button" class="btn blue">Save changes</button>
                                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                            </div>
                    </div>
                    <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
    </div>
 <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','实体卡'),'url'=>$this->createUrl('entityCard/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','充值'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('entityCard/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
<div class="row">   
    <div class="col-md-12">

    <div class="portlet purple box">

        <div class="portlet-body" >
  
                  
            <div class="input-group col-sm-12 col-md-6">

                <input type="text" class="form-control membercard" placeholder="请输入会员号、手机、会员姓名" value="" />
                <span class="input-group-btn">
                    <button class="btn blue getMember" type="button"> 搜 索 </button>
                </span>
            </div>
            <div style="clear:both"></div>
            <div class="row">
                <div class=" col-sm-12 col-md-7 col-md-offset-2" >
                    <div class="table-responsive" style="font-size:20px;">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td width="20%">会员号:</td>
                                    <td width="40%" id="selfcode"></td>
                                </tr>
                                <tr>
                                    <td>余 额:</td>
                                    <td id="all-money"></td>
                                </tr>
                                <tr>
                                    <td>姓 名:</td>
                                    <td id="name"></td>
                                </tr>
                                <tr>
                                    <td>手 机:</td>
                                    <td id="mobile"></td>
                                </tr>
                                <tr>
                                    <td>邮 箱:</td>
                                    <td id="email"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
           
            <!-- BEGIN FORM-->
            <?php $form=$this->beginWidget('CActiveForm', array(
                            'id' => 'taste-form',
                            'action'=>$this->createUrl('entityCard/recharge',array('companyId'=>$this->companyId)),
                            'errorMessageCssClass' => 'help-block',
                            'htmlOptions' => array(
                            'class' => 'form-horizontal',
                            'enctype' => 'multipart/form-data',
                            
                            ),
            )); ?>
                <div class="form-body">
                    <div class="col-md-offset-2">
                        <div class="form-group ">
                                <?php echo $form->label($model, 'reality_money',array('class' => 'pull-left control-label'));?>
                                <div class="pull-left">
                                        <?php echo $form->textField($model, 'reality_money',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('reality_money')));?>
                                        <?php echo $form->error($model, 'reality_money' )?>
                                </div>
                            
                        </div>
                        <div class="form-group ">
                                <?php echo $form->label($model, 'give_money',array('class' => 'pull-left control-label'));?>
                                <div class="pull-left">
                                        <?php echo $form->textField($model, 'give_money',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('give_money')));?>
                                        <?php echo $form->error($model, 'give_money' )?>
                                </div>
                        </div>
                    </div>
                     <div style="clear:both"></div>
                        <div class="col-md-offset-2 col-md-7">
                                        <button type="submit" class="btn blue" ><?php echo yii::t('app','确定');?></button>
                                        <a href="<?php echo $this->createUrl('entityCard/list' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
                        </div>
                </div>
                <input type="hidden" name="rfid" value="" />
                <input type="hidden" name="MemberRecharge[member_card_id]" value="" />
            <?php $this->endWidget(); ?>
                          
            </div>
            
        </div>
        
    </div>
    </div>
</div>

<script type="text/javascript">

jQuery(document).ready(function(){
    $("#taste-form").submit( function () { 
      
        var rfid = $('input[name="rfid"]').val();
        var membercard = $('input[name="MemberRecharge[member_card_id]"]').val();
        if(!rfid || !membercard){
              alert("请输入会员信息。");
                return false;
        }
        //判断充值和赠送金额是否为零，如果为零，禁止充值。
        
        return true;
    } );
    
    
    
        $('.getMember').click(function(){
        var card = $(this).parents('.input-group').find('input').val();
$.get(
        '<?php echo $this->createUrl('/admin/entityCard/GetMember', array('companyId' => $model->dpid));?>/card/'+card,
        function(data){
            if(data.status){

                    $('input[name="rfid"]').val(data.msg.rfid);
                    $('input[name="MemberRecharge[member_card_id]"]').val(data.msg.selfcode);
                    $('#selfcode').html(data.msg.selfcode)
                    $('#all-money').html(data.msg.all_money)
                    $('#name').html(data.msg.name)
                    $('#mobile').html(data.msg.mobile)
                    $('#email').html(data.msg.email)

                }else{
                        $('input[name="rfid"]').val('');
                        $('input[name="MemberRecharge[member_card_id]"]').val('');
                        $('#selfcode').html('')
                        $('#all-money').html('')
                        $('#name').html('')
                        $('#mobile').html('')
                        $('#email').html('')
                        alert(data.msg);
                }
        },'json') ;
        });
        $('.membercard').change(function(){
                $('.getMember').click();
        });
});
</script> 


