<style>
.table thead tr th {
    font-size: 15px!important;
}
.table tbody tr td {
    font-size: 14px!important;
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
<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','微信会员'),'url'=>$this->createUrl('wechatMember/list' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','实体卡绑定'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('wechatMember/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
<div class="row">  
    <div class="col-md-12">
        <div class="portlet purple box">
            <div class="portlet-body" id="table-manage">
              <form id="info" action="<?php echo $this->createUrl('wechatMember/chain',array('companyId' => $this->companyId))?>" method="post" >
                <div class=" col-sm-12 col-md-9 col-md-offset-1" >
                    <div class="table-responsive" style="font-size:20px;">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="color:blue">实体卡等级</th>
                                    <th style="color:blue">微信会员等级</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($entity):?>
                                <?php foreach ($entity as $k=>$v):?>
                                    <tr class="odd gradeX">
                                        <td>
                                            <?php echo $v['level_name']."(".$v['level_discount']."折)"; ?>
                                        </td>
                                        <td>
                                           <select class="form-control category_selecter" tabindex="-1" name="brand_user_level">
                                                <option value="">请选择</option>
                                                <?php if($weixin):?>
                                                    <?php foreach ($weixin as $wx):?>  
                                                        <option  value="<?php echo $wx['lid']?>" <?php if(isset($v->memberbind->branduser_level_id)&&$wx['lid']==$v->memberbind->branduser_level_id) echo 'selected';?>>
                                                                <?php echo $wx['level_name']."(".$wx['level_discount']."折)"; ?>
                                                        </option>
                                                    <?php endforeach;?>
                                                <?php endif;?> 
                                            </select> 
                                            <input type="hidden" class="membercard_level" name="bind[<?php echo $k;?>][membercard_level_id]"   value="<?php echo $v['lid'];?>"/>
                                            <input type="hidden" class="branduser_level" name="bind[<?php echo $k;?>][branduser_level_id]"   value="<?php if($v->memberbind){ echo $v->memberbind->branduser_level_id;}?>"/>
                                        </td>
                                     </tr>
                                <?php endforeach;?>
                                <?php endif;?>
                           </tbody>
                           
                        </table>
                              
                    </div>
                </div>
                <div class="col-md-offset-2 col-md-7">
                    <a href="<?php echo $this->createUrl('wechatMember/list' , array('companyId' => $this->companyId));?>" class="btn default"><?php echo yii::t('app','返回');?></a>
                    <button type="submit" class="btn green" onclick = "return bind();"><?php echo yii::t('app','绑定');?></button>
                </div>
               </form>
            </div>
        </div>
    </div>
</div>        
</div>
<script>
    $(function(){
        $("select[name='brand_user_level']").change(function(){
            var value = $(this).val();
             $(this).parents('td').find("input.branduser_level").val(value);
        });
    });
   function bind() {
        //quzhi
            var check_error = false;
            $("input[name^='bind']").each(function(){
                if($(this).val()){
                    //
                }else{
                   check_error = true;
                    return false;
                }
                        
            });
            if(check_error){
                alert("请选择微信等级！");
                return false;
            }
   }
</script>