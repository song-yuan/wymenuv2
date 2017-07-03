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
    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','店铺管理'),'url'=>$this->createUrl('company/list' , array('companyId'=>$this->companyId,))),array('word'=>yii::t('app','POS状态列表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('company/list' , array('companyId' => $this->companyId,)))));?>

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
                                    <button type="submit"  class="btn orange" id="countNum"><?php echo yii::t('app','统计');?></button>
                                <div class="btn-group">
                                    <button type="submit"  class="btn green" id="counts"><?php echo yii::t('app','批量结算');?></button>
                                </div>
                                <div class="btn-group">
                                    <button type="submit"  class="btn red" id="nocounts"><?php echo yii::t('app','批量取消结算');?></button>
                                </div>
                                <div class="btn-group">
                                    <button type="submit"  class="btn blue" id="putout"><?php echo yii::t('app','导出Excel');?></button>
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
                                        <th class="table-checkbox">
                                            <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" />
                                        </th>
                                        <th><?php echo yii::t('app','序号');?></th>
                                        <th><?php echo yii::t('app','POS序列号');?></th>
                                        <th><?php echo yii::t('app','是否使用');?></th>
                                        <th><?php echo yii::t('app','模式');?></th>
                                        <th><?php echo yii::t('app','店铺名称');?></th>
                                        <th><?php echo yii::t('app','是否结算');?></th>
                                        <?php if(Yii::app()->user->role <=5):?>
                                        <th><?php echo yii::t('app','操作');?></th>
                                        <?php endif;?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($models as $model):?>
                                        <tr class="odd gradeX">
                                            <td>
                                            <input type="checkbox" class="checkboxes" value="<?php echo $model['lid']; ?>" name="ids[]" />
                                            </td>

                                            <td><?php echo $model['lid']; ?></td>
                                            <td><?php echo $model['pad_code'];?></td>
                                            <td><?php if($model['use_status']) echo '已使用';else echo '未使用';?>
                                            </td>
                                            <td><?php if($model['pad_sales_type']==0)echo '单屏模式';else echo '双屏模式';?>
                                            </td>

                                            <td>  <?php  echo $model['company_name'];?> </td>
                                            <td id= 'stat'>
                                                <?php switch($model['status']){
                                                        case 0: echo '<p style="color:red;">未结算</p>';break;
                                                        case 1: echo '<p style="color:green;">已结算</p>';break;
                                                        default: echo '<p style="color:blue;">未知状态</p>';break;
                                                }?>
                                            </td>


                                            <td class="center">
                                                <div class="actions">
                                                    <?php if($model['status']): ?>
                                                    <button type="button" class="btn red stocktaking" id="stocktaking" device_id="<?php echo $model['lid'];?>">取消结算</button>
                                                <?php else :?>
                                                    <button type="button" class="btn green stocktaking" id="stocktaking" device_id="<?php echo $model['lid'];?>">进行结算</button>
                                                <?php endif; ?>
                                                </div>
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
    $(".stocktaking").on('click',function(){
        var cla = $(this).attr('class');
        var lid = $(this).attr('device_id');
        // alert(lid);
        if (cla=='btn red stocktaking') {
            var status = 0;
        }else if(cla=='btn green stocktaking'){
            var status = 1;
        }
        $.ajax({
                type:'POST',
                url:"<?php echo $this->createUrl('poscode/counts',array('companyId'=>$this->companyId,));?>",
                // async: false,
                data: {
                    ids: lid,
                    status: status,
                },
                cache:false,
                dataType:'json',//html
                success:function(data){
                    if (data) {
                        layer.msg("结算状态修改成功！");
                    }
                        location.reload();
                    },
                error:function(){
    				layer.msg("<?php echo yii::t('app','结算状态修改失败'); ?>");
    			},
        });
    });


    $("#counts").on('click',function(){
        var lid = [];
        // $('input:checkbox:checked').each(function() {
        $('input:checkbox:checked[name="ids[]"]').each(function() {
            lid.push($(this).val());
        });
        // alert(lid);
        var status = 1;//结算状态
        $.ajax({
                type:'POST',
                url:"<?php echo $this->createUrl('poscode/counts',array('companyId'=>$this->companyId,));?>",
                // async: false,
                data: {
                    ids: lid,
                    status:status,
                },
                cache:false,
                dataType:'json',//html
                success:function(data){
                    if (data) {
                        layer.msg("结算成功！");
                        location.reload();
                    }
                    },
                error:function(){
                    layer.msg("<?php echo yii::t('app','结算失败'); ?>");
                },
        });
    });


    $("#nocounts").on('click',function(){
        var lid = [];
        $('input:checkbox:checked[name="ids[]"]').each(function() {
            lid.push($(this).val());
        });
        // alert(lid);
        var status = 0;//结算状态
        $.ajax({
                type:'POST',
                url:"<?php echo $this->createUrl('poscode/counts',array('companyId'=>$this->companyId,));?>",
                // async: false,
                data: {
                    ids: lid,
                    status:status,
                },
                cache:false,
                dataType:'json',//html
                success:function(data){
                    if (data) {
                        layer.msg("取消结算成功！");
                        location.reload();
                    }
                    },
                error:function(){
                    layer.msg("<?php echo yii::t('app','取消结算失败'); ?>");
                },
        });
    });


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

    $('#countNum').click(function(){
    var index = document.getElementById('cdpid').selectedIndex;
    var val = document.getElementById('cdpid').options[index].value;
    location.href="<?php echo $this->createUrl('poscode/countNum' , array('companyId'=>$this->companyId));?>/cdpid/"+val;
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