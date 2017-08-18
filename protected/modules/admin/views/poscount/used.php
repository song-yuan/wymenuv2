<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>"></script>
   <!-- BEGIN PAGE -->
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
    <!-- /.modal -->
    <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <!-- BEGIN PAGE HEADER-->
    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','营业数据'),'url'=>$this->createUrl('statements/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','收银机排序'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statements/list' , array('companyId' => $this->companyId,'type'=>0)))));?>

    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box purple">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-globe"></i>
                    <?php echo yii::t('app','收银机统计');?>
                </div>
                <div class="actions">
                    <select id="pos_type" class="btn yellow" >
                        <option value="0" <?php if ($pos_type==0){?> selected="selected" <?php }?> ><?php echo yii::t('app','全部');?></option>
                        <option value="1" <?php if ($pos_type==1){?> selected="selected" <?php }?> ><?php echo yii::t('app','单屏');?></option>
                        <option value="2" <?php if ($pos_type==2){?> selected="selected" <?php }?> ><?php echo yii::t('app','双屏');?></option>
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
                        <button type="submit" id="excel"  class="btn green" ><i class="fa fa-pencial"></i><?php echo yii::t('app','导出Excel');?></button>
                    </div>
                </div>
            </div>

            <div class="portlet-body" id="table-manage">
                <table class="table table-striped table-bordered table-hover" id="sample_1">
                    <thead>
                        <tr>

                            <th>店名</th>
                            <th>店铺创立时间</th>
                            <th>类型</th>
                            <th>POS序列号</th>
                            <th>收银机开始使用时间</th>
                            <th>收银机地址</th>
                            <th>排序</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        if( $models){
                                foreach ($models as $v) {
                                   
                        ?>
                                        <tr class="odd gradeX">
                                            <td><?php echo $v['company_name'];?></td>
                                            <td><?php echo $v['comp_create_time'];?></td>
                                            <td><?php if($v['screen_type']==0)echo '单屏';else echo '双屏';?></td>
                                            <td><?php echo $v['pad_code'];?></td>

                                            <td><?php echo $v['used_at'];?></td>
                                            <td><?php echo $v['content'];?></td>
                                            <td><?php echo $v['pad_no']; ?></td>
                                        </tr>
                        <?php
                                    
                            }
                        }
                        ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    </div>
</div>
<script>
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
$('#btn_time_query').click(function time() {
        var begin_time = $('#begin_time').val();
        var end_time = $('#end_time').val();
        var pos_type = $('#pos_type').val();

        location.href="<?php echo $this->createUrl('poscount/used' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/pos_type/"+pos_type;

});
$('#excel').click(function excel(){
	var begin_time = $('#begin_time').val();
        var end_time = $('#end_time').val();
        var pos_type = $('#pos_type').val();
		//alert(begin_time);alert(end_time);
        if(confirm('确认导出并且下载Excel文件吗？')){
            location.href="<?php echo $this->createUrl('poscount/UsedExport' , array('companyId'=>$this->companyId));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/pos_type/"+pos_type;
        }
});
</script>
