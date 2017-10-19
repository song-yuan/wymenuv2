<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>"></script>
      
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
        <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','微信会员'),'url'=>$this->createUrl('wechatMember/list' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','短信'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('wechatMember/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
<div class="row">   
    <div class="col-md-12">
        <div class="portlet purple box">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-globe"></i>
                        <?php echo yii::t('app','短信统计表');?>
                </div>
                <div class="actions">                          
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
            <div class="dataTables_wrapper form-inline">
				<div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="sample_1">
                    <thead>
                        <tr>
                            <th>时间</th>
                            <th>手机号</th> 
                            <th>验证码</th> 
                            <th>类型</th>                                                               
                            <th>状态</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            if($models) :	
                            foreach ($models as $model):
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $model->create_at;?></td>
                            <td><?php echo $model->mobile;?></td>
                            <td><?php echo $model->code;?></td>
                            <td>
                                <?php if($model->type == "0")  echo "新增信息";?>
                                <?php if($model->type == "1") echo "修改信息";?>
                            </td>
                            <td>
                                <?php 
                                  echo $model->status == "1" ? "成功" : "失败";  
                                ?>
                            </td>
                            
                        </tr>
                        <?php
                            endforeach;	
                            endif;
                         ?>  
                        <tr>
		            <td><?php echo "总计短信：".$pages->getItemCount()."条";?></td>
		            <td><?php echo "成功短信：".$success."条"; ?></td>
		            <td><?php echo "失败短信：".($pages->getItemCount()-$success)."条";?></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                </div>
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
 
$('#btn_time_query').click(function() {  
        var begin_time = $('#begin_time').val();
        var end_time = $('#end_time').val();
        
        location.href="<?php echo $this->createUrl('mobileMessage/index' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time;
});

$('#excel').click(function excel(){
        var begin_time = $('#begin_time').val();
        var end_time = $('#end_time').val();
         //alert(str);
        if(confirm('确认导出并且下载Excel文件吗？')){
            location.href="<?php echo $this->createUrl('mobileMessage/Export' , array('companyId'=>$this->companyId,'d'=>1));?>/begin_time/"+begin_time+"/end_time/"+end_time;
     
}else{
        // location.href="<?php echo $this->createUrl('statements/turnOver' , array('companyId'=>$this->companyId,'d'=>1));?>/str/"+str+"/begin_time/"+begin_time+"/end_time/"+end_time;
        }
 });
</script> 
