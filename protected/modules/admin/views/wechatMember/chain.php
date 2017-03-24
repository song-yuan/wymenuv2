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
                                <?php foreach ($entity as $v):?>
                                    <tr class="odd gradeX">
                                        <td>
                                            <?php echo $v['level_name']."&nbsp;&nbsp;&nbsp;&nbsp;".$v['level_discount']."折"; ?>
                                            <input type="hidden" class="binds" name="bind[][membercard_level_id]"   value="<?php echo $v['lid'];?>"/>
                                        </td>
                                        <td>
                                           <select class="form-control category_selecter" tabindex="-1" name="category_id_selecter">
                                                <option value="">微信会员等级</option>
                                                <?php if($weixin):?>
                                                    <?php foreach ($weixin as $wx):?>  
                                                        <option  value="<?php echo $wx['lid']?>">
                                                                <?php echo $wx['level_name']."&nbsp;&nbsp;&nbsp;&nbsp;".$wx['level_discount']."折"; ?>
                                                            
                                                        </option>
                                                        
                                                    <?php endforeach;?>
                                                <?php endif;?> 
                                            </select> 
                                            <input type="hidden" class="binds" name="bind[][branduser_level_id]"   value=""/>
                                        </td>
                                     </tr>
                                <?php endforeach;?>
                                <?php endif;?>
                           </tbody>
                           
                        </table>
                              
                    </div>
                </div>
                <div class="col-md-offset-2 col-md-7">
                    <button type="submit" class="btn green" onclick = "return bind();"><?php echo yii::t('app','绑定');?></button>
                    <a href="<?php echo $this->createUrl('wechatMember/list' , array('companyId' => $this->companyId));?>" class="btn default"><?php echo yii::t('app','返回');?></a>
                </div>
               </form>
            </div>
        </div>
    </div>
</div>        
</div>
<script>
    $(function(){
        $("select[name='category_id_selecter']").change(function(){
            var value = $(this).val();
             $(this).parent().parent().find("input[name^='bind']").val(value);
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