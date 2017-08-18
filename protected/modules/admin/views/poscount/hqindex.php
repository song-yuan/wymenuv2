<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>"></script>
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
.table{
    background: white;
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
    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','营业数据'),'url'=>$this->createUrl('statements/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','收银机结算'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statements/list' , array('companyId' => $this->companyId,'type'=>0)))));?>

    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
            <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet box purple">
                    <div class="portlet-title">
                        <div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','POS机结算报表');?></div>
                        <div class="actions">
                        <span style="color:white;">选择查询状态</span>

                            <?php if(Yii::app()->user->role <User::ADMIN):?>
                                <select id="pos_count" class="btn yellow width" >
                                    <option value="2" <?php if ($pos_count==2){?> selected="selected" <?php }?> ><?php echo yii::t('app','全部');?></option>
                                    <option value="1" <?php if ($pos_count==1){?> selected="selected" <?php }?> ><?php echo yii::t('app','已结算');?></option>
                                    <option value="0" <?php if ($pos_count==0){?> selected="selected" <?php }?> ><?php echo yii::t('app','未结算');?></option>
                                </select>
                                <select id="pos_used" class="btn yellow width" >
                                    <option value="2" <?php if ($pos_used==2){?> selected="selected" <?php }?> ><?php echo yii::t('app','全部');?></option>
                                    <option value="1" <?php if ($pos_used==1){?> selected="selected" <?php }?> ><?php echo yii::t('app','已使用');?></option>
                                    <option value="0" <?php if ($pos_used==0){?> selected="selected" <?php }?> ><?php echo yii::t('app','未使用');?></option>
                                </select>
                                <div class="btn-group">
                                    <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
                                        <input type="text" class="form-control" name="begtime" id="begin_time" placeholder="<?php echo yii::t('app','起始时间');?>" value="<?php echo $begin_time; ?>">
                                        <span class="input-group-addon">~</span>
                                        <input type="text" class="form-control" name="endtime" id="end_time" placeholder="<?php echo yii::t('app','终止时间');?>"  value="<?php echo $end_time;?>">
                                    </div>
                                </div>
                                <div class="btn-group">
                                    <button type="submit" id="btn_time_query" class="btn red" ><i class="fa fa-pencial"></i><?php echo yii::t('app','查 询');?></button>
                                </div>

                                <?php if(Yii::app()->user->role < 5):?>
                                <div class="btn-group">
                                    <button type="submit"  class="btn green" id="counts"><?php echo yii::t('app','进行结算');?></button>
                                </div>
                                <div class="btn-group">
                                    <button type="submit"  class="btn red" id="nocounts"><?php echo yii::t('app','取消结算');?></button>
                                </div>

                                <?php endif;?>
                                <div class="btn-group">
                                    <button type="submit"  class="btn blue" id="excel"><?php echo yii::t('app','导出Excel');?></button>
                                </div>
                            <?php endif;?>
                        </div>
                    </div>
                    <div class="portlet-body" id="table-manage">
                    <span style="font-size:1.5em;color:red;"><?php echo yii::t('app','(注意 : 查询时间为收银机开始使用时间)');?></span>
                        <table class="table table-striped table-bordered table-hover" id="sample_1">
                            <?php if($models):?>
                                <thead>
                                    <tr>
                                        <th noWrap><?php echo yii::t('app','店名');?></th>
                                        <th noWrap><?php echo yii::t('app','联系人');?></th>
                                        <th noWrap><?php echo yii::t('app','手机号');?></th>
                                        <th noWrap><?php echo yii::t('app','店铺创建时间');?></th>
                                        <th noWrap><?php echo yii::t('app','模式');?></th>
                                        <th noWrap><?php echo yii::t('app','POS序列号');?></th>
                                        <th noWrap><?php echo yii::t('app','序列号创建时间');?></th>
                                        <th noWrap><?php echo yii::t('app','收银机是否使用(开始使用时间)');?></th>
                                        <th noWrap><?php echo yii::t('app','收银机MAC地址');?></th>
                                        <th noWrap><?php echo yii::t('app','排序');?></th>
                                        <th noWrap><?php echo yii::t('app','是否结算');?></th>
                                        <?php if(Yii::app()->user->role <=5):?>
                                        <th class="table-checkbox" noWrap>
                                            <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" />
                                        </th>
                                        <!-- <th><?php echo yii::t('app','操作');?></th> -->
                                        <?php endif;?>
                                    </tr>
                                </thead>
                                <tbody>
                                        <tr>
                                            <th noWrap><?php echo yii::t('app','之前已使用未结算');?></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th class="table-checkbox"></th>
                                        </tr>
                                    <?php foreach ($models as $model):?>
                                        <?php if( (strtotime($model['used_at'])<strtotime($begin_time)) && $model['status']==0 && $model['use_status']==1 ): ?>
                                        <tr class="odd gradeX">
                                            <td noWrap>  <?php  echo $model['company_name'];?> </td>
                                            <td noWrap>  <?php  echo $model['contact_name'];?> </td>
                                            <td noWrap>  <?php  echo $model['mobile'];?> </td>
                                            <td noWrap><?php echo $model['comp_create_time']; ?></td>
                                            <td noWrap><?php if($model['screen_type']==0)echo '单屏';else echo '双屏';?><!-- screentype -->
                                            </td>
                                            <td noWrap><?php echo $model['pad_code'];?></td>
                                            <td noWrap><?php echo $model['poscreate_at'];?></td>
                                            <td noWrap>
                                                <?php if($model['use_status']){echo '<span style="color:green;">是</span> : '.$model['used_at'];}else{echo '<span style="color:red;">否</span>';}?>
                                            </td>
                                            <td noWrap><?php echo $model['content'];?></td>
                                            <td noWrap><?php echo $model['pad_no'];?></td>
                                            <td noWrap id= 'stat'>
                                                <?php switch($model['status']){
                                                        case 0: echo '<p style="color:red;">未结算</p>';break;
                                                        case 1: echo '<p style="color:green;">已结算</p>';break;
                                                        default: echo '<p style="color:blue;">未知状态</p>';break;
                                                }?>
                                            </td>

                                            <td noWrap>
                                            <input type="checkbox" class="checkboxes" value="<?php echo $model['lid']; ?>" name="ids[]" checked/>
                                            </td>

                                        </tr>
                                        <?php endif; ?>
                                    <?php endforeach;?>

                                    <tr>
                                        <th noWrap><?php echo yii::t('app','所选时间段的结果');?></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th class="table-checkbox"></th>
                                    </tr>
                                    <?php foreach ($models as $model):?>
                                        <?php if( (strtotime($model['used_at'])>strtotime($begin_time)) && ( $model['used_at'] < date( "Y-m-d H:i:s", strtotime( $end_time." +1 day"))) ): ?>
                                        <tr class="odd gradeX">

                                            <td noWrap>  <?php  echo $model['company_name'];?> </td>
                                            <td noWrap>  <?php  echo $model['contact_name'];?> </td>
                                            <td noWrap>  <?php  echo $model['mobile'];?> </td>
                                            <td noWrap><?php echo $model['comp_create_time']; ?></td>
                                            <td noWrap><?php if($model['screen_type']==0)echo '单屏';else echo '双屏';?>
                                            <!-- screen_type -->
                                            </td>
                                            <td noWrap><?php echo $model['pad_code'];?></td>
                                            <td noWrap><?php echo $model['poscreate_at'];?></td>
                                            <td noWrap><?php if($model['use_status']){echo '<span style="color:green;">是</span> : '.$model['used_at'];}else{echo '<span style="color:red;">否</span>';}?></td>
                                            <td noWrap><?php echo $model['content'];?></td>
                                            <td noWrap><?php echo $model['pad_no'];?></td>

                                            <td noWrap id= 'stat'>
                                                <?php switch($model['status']){
                                                        case 0: echo '<p style="color:red;">未结算</p>';break;
                                                        case 1: echo '<p style="color:green;">已结算</p>';break;
                                                        default: echo '<p style="color:blue;">未知状态</p>';break;
                                                }?>
                                            </td>

                                            <td noWrap>
                                            <input type="checkbox" class="checkboxes" value="<?php echo $model['lid']; ?>" name="ids[]" checked/>
                                            </td>

                                            <!-- <td class="center">
                                                <div class="actions">
                                                    <?php if($model['status']): ?>
                                                    <button type="button" class="btn red stocktaking" id="stocktaking" device_id="<?php echo $model['lid'];?>">取消结算</button>
                                                <?php else :?>
                                                    <button type="button" class="btn green stocktaking" id="stocktaking" device_id="<?php echo $model['lid'];?>">进行结算</button>
                                                <?php endif; ?>
                                                </div>
                                            </td> -->
                                        </tr>
                                        <?php endif; ?>
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
                url:"<?php echo $this->createUrl('poscount/counts',array('companyId'=>$this->companyId,));?>",
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
        var status = 1;//结算状态
        // alert(lid);
        $.ajax({
                type:'POST',
                url:"<?php echo $this->createUrl('poscount/counts',array('companyId'=>$this->companyId,));?>",
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
                    }else{
                        layer.msg("结算失败!");
                        location.reload();
                    }
                    },
                error:function(){
                    layer.msg("<?php echo yii::t('app','结算失败!'); ?>");
                },
        });
    });


    $("#nocounts").on('click',function(){
        var lid = [];
        $('input:checkbox:checked[name="ids[]"]').each(function() {
            lid.push($(this).val());
        });
        // alert(lid);
        var status = 0;//未结算状态
        $.ajax({
                type:'POST',
                url:"<?php echo $this->createUrl('poscount/counts',array('companyId'=>$this->companyId,));?>",
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
                    }else{
                        layer.msg("取消结算失败！!");
                        location.reload();
                    }
                    },
                error:function(){
                    layer.msg("<?php echo yii::t('app','取消结算失败'); ?>");
                },
        });
    });

    $('#btn_time_query').click(function time() {
            var begin_time = $('#begin_time').val();
            var end_time = $('#end_time').val();
            var pos_count = $('#pos_count').val();
            var pos_used = $('#pos_used').val();

            location.href="<?php echo $this->createUrl('poscount/hqindex' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/pos_count/"+pos_count+"/pos_used/"+pos_used;

    });



    // $('#countNum').click(function(){
    // location.href="<?php echo $this->createUrl('poscount/countNum' , array('companyId'=>$this->companyId));?>";
    // });

    // $('#POSsearch').click(function(){
    // var index0 = document.getElementById('statu').selectedIndex;
    // var statu = document.getElementById('statu').options[index0].value;

    // var index1 = document.getElementById('use_statu').selectedIndex;
    // var use_statu = document.getElementById('use_statu').options[index1].value;
    // location.href="<?php echo $this->createUrl('poscount/hqsearch' , array('companyId'=>$this->companyId));?>/statu/"+statu+'/use_statu/'+use_statu;
    // });
    $('#excel').click(function excel(){
        var begin_time = $('#begin_time').val();
            var end_time = $('#end_time').val();
            var pos_count = $('#pos_count').val();
            var pos_used = $('#pos_used').val();
            //alert(begin_time);alert(end_time);
            if(confirm('确认导出并且下载Excel文件吗？')){
                location.href="<?php echo $this->createUrl('poscount/poscountExport' , array('companyId'=>$this->companyId));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/pos_count/"+pos_count+"/pos_used/"+pos_used;
            }
    });

    jQuery(document).ready(function(){
        if (jQuery().datepicker) {
                $('.date-picker').datepicker({
                    format: 'yyyy-mm-dd',
                    language: 'zh-CN',
                    rtl: App.isRTL(),
                    autoclose: true
                });
                $('body').removeClass("modal-open");
        }
});
</script>