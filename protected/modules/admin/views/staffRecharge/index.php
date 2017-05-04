
<div class="page-content">
    <div class="modal fade" id="portlet-consume" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
                <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">批量充值</h4>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">充值金额:</label>
                                    <input type="text" class="form-control" id="reality_money">
                                </div>
                                <div class="form-group">
                                    <label for="message-text" class="control-label">赠送金额:</label>
                                    <input type="text" class="form-control" id="give_money" >
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="recharge" class="btn blue">确定</button>
                            <button type="button" class="btn default" id="close_modal"  data-dismiss="modal">返回</button>
                        </div>
                </div>
                <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!-- /.modal -->
    <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <!-- BEGIN PAGE HEADER-->
    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','实体卡'),'url'=>$this->createUrl('entityCard/list' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','员工充值'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('entityCard/list' , array('companyId' => $this->companyId)))));?>

    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box purple">
                <div class="portlet-title">
                        <div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','会员列表');?></div>
                        <div class="actions">
                            <select id="level_id" class="btn yellow" >                          
                                <option value="0" <?php if ($level_id==0){?> selected="selected" <?php }?> ><?php echo yii::t('app','全部等级');?></option>
                                <?php if($levels):?>
                                <?php foreach ($levels as $level):?>
                                <option value="<?php echo $level['lid'];?>" <?php if ($level_id==$level['lid']){?> selected="selected" <?php }?> ><?php echo $level['level_name'];?></option>
                                <?php endforeach;?>
                                <?php endif;?>
                            </select>                            
                            <a href="javascript:;" id="level_query" class="btn green" ><i class="fa fa-pencial"></i><?php echo yii::t('app','查 询');?></a>
                            <a href="javascript:;" id="mass_recharge" class="btn red"><i class="fa fa-pencil"></i> <?php echo yii::t('app','批量充值');?></a>	                                                                        
                        </div>
                </div>
                <div class="portlet-body" id="table-manage">
                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                        <thead>
                            <tr>
                                <th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
                                <th><?php echo yii::t('app','会员卡号');?></th>
                                <th><?php echo yii::t('app','姓名');?></th>
                                <th><?php echo yii::t('app','性别');?></th>
                                <th><?php echo yii::t('app','生日');?></th>
                                <th><?php echo yii::t('app','联系方式');?></th>
                                <th><?php echo yii::t('app','金额');?></th>
                                <th><?php echo yii::t('app','积分');?></th>
                                <th><?php echo yii::t('app','状态');?></th>
                                <th><?php echo yii::t('app','折扣（生日折扣）');?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($models):?>
                            <?php foreach ($models as $model):?>
                            <tr>
                                <td><input type="checkbox" class="checkboxes" value="<?php echo $model['lid'];?>" name="idchk" /></td>
                                   
                                    <td ><?php echo $model->selfcode;?></td>
                                    <td ><?php echo $model->name;?></td>
                                    <td ><?php if($model->sex=='m') echo '男';else echo '女';?></td>
                                    <td ><?php echo $model->birthday;?></td>
                                    <td ><?php echo $model->mobile;?></td>
                                    <td ><?php echo $model->all_money;?></td>
                                    <td ><?php echo $model->all_points;?></td>
                                    <td ><?php switch($model->card_status){case 0:echo '正常';break;case 1: echo "挂失";break;case 2: echo '注销';break;default:echo '';break;}?></td>
                                    <td ><?php echo sprintf("%.2f",$model->brandUserLevel?$model->brandUserLevel->level_discount:'1').'('.sprintf("%.2f",$model->brandUserLevel?$model->brandUserLevel->birthday_discount:'1').')';?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <td colspan="10">没有找到数据</td>
                            <?php endif;?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>   
</div>
<div id="responsive" class="modal fade" tabindex="-1" aria-hidden="true">
        <div id="ajax-modal" class="modal fade" tabindex="-1"  style="width:600px;">
        </div>
        <div class="modal-dialog">
                <div class="modal-content">

                </div>
        </div>
</div>
<script>
$('#level_query').click(function() {  			 
       
        var level_id = $('#level_id').val();
        
        location.href="<?php echo $this->createUrl('StaffRecharge/index' , array('companyId'=>$this->companyId ));?>/level_id/"+level_id;
			  
});

$('#mass_recharge').on('click', function(){			    	
        var aa = document.getElementsByName("idchk");
        var users=new Array();
        for (var i = 0; i < aa.length; i++) {
                if (aa[i].checked){
                    users += aa[i].value +',';
                    //alert(str);
                }
        }
        if(users!=''){
            users = users.substr(0,users.length-1);//除去最后一个“，”
        }else{
            alert("<?php echo yii::t('app','请勾选会员，再充值！');?>");
            return false;
        }
        //alert(users);
        $('#portlet-consume').modal();
         $('#recharge').on('click', function(){
            var rmoney= $('#reality_money').val();
            
            var gmoney = $('#give_money').val();
           
           $.ajax({
                    url:'<?php echo $this->createUrl('StaffRecharge/recharge',array('companyId'=>$this->companyId));?>',
                    data:{users:users,gmoney:gmoney, rmoney:rmoney},
                    async: false,
                    success:function(msg){
                            if(msg){
                                layer.msg('充值成功！！！');
                                document.getElementById("close_modal").click();
                                location.reload();
                            }else{
                                layer.msg('充值失败！！！');
                            }
                    },
                    error: function(msg){
                            layer.msg('网络错误！！！');
                    }
            });
           

        });


      
});
       
</script>