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
    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','营业数据'),'url'=>$this->createUrl('statements/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','收银机统计'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statements/list' , array('companyId' => $this->companyId,'type'=>0)))));?>

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
                    <select id="status" class="btn green" >
                        <option value="0" <?php if ($status==0){?> selected="selected" <?php }?> ><?php echo yii::t('app','全部收银机');?></option>
                        <option value="1" <?php if ($status==1){?> selected="selected" <?php }?> ><?php echo yii::t('app','未使用收银机');?></option>
                        <option value="2" <?php if ($status==2){?> selected="selected" <?php }?> ><?php echo yii::t('app','已使用收银机');?></option>
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
                            
                            <th>分店</th>                                                            
                            <th>类型</th> 
                            <th>POS序列号</th>
                            <th>创立时间</th>
                            <th>开始使用时间</th> 
                            <th>收银机地址</th>                                                               
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php if( $models) :?>
                        <?php foreach ($models as $model):?>
                        <tr class="odd gradeX">
                            <td><?php echo $model['company']['company_name'];?></td>
                            <td><?php if($model['pad_sales_type']==0)echo '单屏';else echo '双屏';?></td>
                            <td><?php echo $model['pad_code'];?></td>
                            <td><?php echo $model['create_at'];?></td>
                            <td><?php if($model->detail) echo $model->detail[0]->create_at;?></td>
                            <td><?php if($model->detail) echo $model->detail[0]->content;?></td>
                        </tr>
                        <?php endforeach;?>	                       
                        <?php endif;?>
                    </tbody>
                </table>
                <?php if($pages->getItemCount()):?>
                    <div class="row">
                        <div class="col-md-5 col-sm-12">
                            <div class="dataTables_info">
                                    <?php echo yii::t('app','共 ');?> <?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?>  , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> ,  <?php echo yii::t('app','当前是第');?> <?php echo $pages->getCurrentPage()+1;?> <?php echo yii::t('app','页');?>
                            </div>
                        </div>
                        <div class="col-md-7 col-sm-12">
                            <div class="dataTables_paginate paging_bootstrap">
                            <?php $this->widget('CLinkPager', array(
                                    'pages' => $pages,
                                    'header'=>'',
                                    'firstPageLabel' => '<<',
                                    'lastPageLabel' => '>>',
                                    'firstPageCssClass' => '',
                                    'lastPageCssClass' => '',
                                    'maxButtonCount' => 8,
                                    'nextPageCssClass' => '',
                                    'previousPageCssClass' => '',
                                    'prevPageLabel' => '<',
                                    'nextPageLabel' => '>',
                                    'selectedPageCssClass' => 'active',
                                    'internalPageCssClass' => '',
                                    'hiddenPageCssClass' => 'disabled',
                                    'htmlOptions'=>array('class'=>'pagination pull-right')
                            ));
                            ?>
                            </div>
                        </div>
                    </div>
                <?php endif;?>
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
        var status = $('#status').val();
        location.href="<?php echo $this->createUrl('pos/index' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/pos_type/"+pos_type+"/status/"+status;
			  
});
$('#excel').click(function excel(){
	var begin_time = $('#begin_time').val();
        var end_time = $('#end_time').val();
        var pos_type = $('#pos_type').val();
        var status = $('#status').val();	    	   
				  
        if(confirm('确认导出并且下载Excel文件吗？')){
            location.href="<?php echo $this->createUrl('pos/export' , array('companyId'=>$this->companyId));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/pos_type/"+pos_type+"/status/"+status;
        }
});
</script> 
