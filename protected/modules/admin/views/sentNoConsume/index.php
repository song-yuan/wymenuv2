<style>
    .modal-dialog{
            width: 80%;
            height: 70%;
    }
</style> 
<div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
    <div class="modal fade" id="portlet-consume" tabindex="-2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','微信赠券'),'url'=>$this->createUrl('wechatMarket/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','给开卡未消费会员赠券'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('wechatMarket/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
    <div class="row">   
        <div class="col-md-12">
            <div class="portlet purple box">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-group"></i>会员列表
                    </div>
                    <div class="actions">
                            <a href="javascript:;" class="btn red add_save">
                                <i class="fa fa-pencil"></i> 手动群发
                            </a>
                    </div>                    
                </div>
                <div class="portlet-body" id="table-manage">
                    <table class="table table-bordered table-hover" id="sample_1">
                        <thead>
                            <tr>
                                <th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
                                <th>卡号</th>
                                <th>名称|昵称</th>
                                <th>性别</th>
                                <th>手机号</th>	
                                <th>生日</th>
                                <th>等级</th>
                                <th>地区</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($models):?>
                                <?php foreach($models as $model):?>
                                <tr>
                                    <td><input type="checkbox" class="checkboxes" value="<?php echo $model['lid'];?>" name="idchk" /></td>
                                    <td><?php echo substr($model['card_id'],5,9);?></td>
                                    <td><?php echo $model['user_name']."|".$model['nickname'];?></td>
                                    <td><?php switch ($model['sex']){case 0:echo "未知"; break; case 1:echo "男";break; case 2:echo "女";};?></td>
                                    <td><?php echo $model['mobile_num'];?></td>
                                    <td><?php echo substr($model['user_birthday'],0,10);?></td>
                                    <td><?php echo $model['level_name'];?></td>
                                    <td><?php echo $model['country'];?> <?php echo $model['province'];?> <?php echo $model['city'];?></td>											
                                    
                                </tr>
                                <?php endforeach;?>	
                            <?php else:?>
                                <tr>
                                    <td colspan="12">没有找到数据</td>
                                </tr>
                            <?php endif;?>
                        </tbody>
                    </table>
                </div>
            </div>
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
    $('.add_save').on('click', function(){
        <?php if(Yii::app()->user->role > User::SHOPKEEPER):?>
            alert("您没有权限！");return false;
        <?php endif;?>
        var aa = document.getElementsByName("idchk");
        var users=new Array();
        for (var i = 0; i < aa.length; i++) {
                if (aa[i].checked){
                    users += aa[i].value +',';
                    
                }
        }
        if(users!=''){
            users = users.substr(0,users.length-1);//除去最后一个“，”
        }else{
            alert("<?php echo yii::t('app','请勾选会员，再发送优惠券！');?>");
            return false;
        }
        //alert(users);
        modalconsumetotal=$('#portlet-consume');
        modalconsumetotal.find('.modal-content').load('<?php echo $this->createUrl('SentNoConsume/addprod',array('companyId'=>$this->companyId));?>/users/'+users, '', function(){
            modalconsumetotal.modal();
        });
    });
</script>