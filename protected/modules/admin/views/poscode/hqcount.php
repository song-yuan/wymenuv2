<!-- poscode控制器/hqindex方法页面中统计按钮跳转的页面 -->
<style>
.selectedclass{
    font-size: 14px;
    color: #333333;
    height: 28px;
    line-height: 28px;
    padding: 6px 12px;
}
.orange{
    background:orange;
    color: white;
}
</style>  
<div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
    <div class="modal fade" id="portlet-pad-bind" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
    <!-- /.modal -->
    <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <!-- BEGIN PAGE HEADER-->
    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','店铺管理'),'url'=>$this->createUrl('company/list' , array('companyId'=>$this->companyId,))),array('word'=>yii::t('app','POS状态列表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('poscode/hqindex' , array('companyId' => $this->companyId,)).'/cdpid/'.$cdpid  )));?>

    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">

            <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet box purple">
                    <div class="portlet-title">
                        <div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','POS状态列表');?></div>
                        <div class="actions">
                        <select name="" id="cdpid" class="selectedclass btn" >
                            <option value="">- 请选择总公司 -</option>
                            <?php foreach($hqcompany as $hq): ?>
                                <option value="<?php echo $hq['dpid']; ?>" <?php if ($comp_name==$hq['company_name']) {echo 'selected';} ?>>
                                        <?php echo $hq['company_name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>



                            <?php if(Yii::app()->user->role <User::ADMIN):?>
                                <div class="btn-group">
                                <select name="use_statu" id="use_statu" class="selectedclass btn" >
                                    <option value="null" <?php if($use_statu==='null'){echo 'selected';} ?>>未选择</option>
                                    <option value="0" <?php if($use_statu==='0'){echo 'selected';} ?>>未使用</option>
                                    <option value="1" <?php if($use_statu==='1'){echo 'selected';} ?>>已使用</option>
                                </select>
                                <select name="statu" id="statu" class="selectedclass btn" >
                                    <option value="null" <?php if($statu==='null'){echo 'selected';} ?>>未选择</option>
                                    <option value="0" <?php if($statu==='0'){echo 'selected';} ?>>未结算</option>
                                    <option value="1" <?php if($statu==='1'){echo 'selected';} ?>>已结算</option>
                                </select>
                                    <button type="submit"  class="btn green" id="POSsearch"><?php echo yii::t('app','查询');?></button>
                                </div>
                            <?php endif;?>
                        </div>
                    </div>
                    <div class="portlet-body" id="table-manage">
                        <table class="table table-striped table-bordered table-hover" id="sample_1">
                            <caption>
                                <h3>
                                    <b id='hqcname'><?php if($comp_name!=null) echo $comp_name; else echo '';?></b>
                                </h3>
                            </caption>
                            <?php if($models):?>
                                <thead>
                                    <tr>
                                        <th><?php echo yii::t('app','店铺名称');?></th>
                                        <th><?php echo yii::t('app','POS机数量');?></th>
                                    </tr>
                                </thead>
                                <tbody>						
                                    <?php foreach ($models as $model):?>
                                        <tr class="odd gradeX">
                                            <td>  <?php  echo $model['company_name'];?> </td>
                                            <td id= 'stat'>
                                                <?php echo $model['cnum'];?>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>
                            <?php elseif($models==0):?>
                                <tr><td><?php echo yii::t('app','还没有选择总公司');?></td></tr>
                            <?php else:?>
                                <tr><td><?php echo yii::t('app','没有符合条件的子店铺');?></td></tr>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
			<!-- END EXAMPLE TABLE PORTLET-->
            </div>
    </div>
	<!-- END PAGE CONTENT-->


<script>


    $("#putout").on('click',function(){
        var index = document.getElementById('cdpid').selectedIndex;
        var val = document.getElementById('cdpid').options[index].value;
        var download = 1;
        if(confirm('确认导出并且下载Excel文件吗？')){
        location.href="<?php echo $this->createUrl('poscode/hqindex',array('companyId'=>$this->companyId,));?>/cdpid/"+val+"/download/"+download ;
       }

    });



    //选择总公司
   $('#cdpid').change(function(){

    var index = document.getElementById('cdpid').selectedIndex;
    var val = document.getElementById('cdpid').options[index].value;
    location.href="<?php echo $this->createUrl('poscode/hqindex' , array('companyId'=>$this->companyId));?>/cdpid/"+val;

    });

   $('#POSsearch').click(function(){
   var index = document.getElementById('cdpid').selectedIndex;
   var val = document.getElementById('cdpid').options[index].value;

   var index0 = document.getElementById('statu').selectedIndex;
   var statu = document.getElementById('statu').options[index0].value;

   var index1 = document.getElementById('use_statu').selectedIndex;
   var use_statu = document.getElementById('use_statu').options[index1].value;
   location.href="<?php echo $this->createUrl('poscode/hqsearch' , array('companyId'=>$this->companyId));?>/cdpid/"+val+'/statu/'+statu+'/use_statu/'+use_statu;
   });
</script>