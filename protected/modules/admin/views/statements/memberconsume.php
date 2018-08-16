    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>"></script>

<div class="page-content">
    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','营业数据'),'url'=>$this->createUrl('statements/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','会员卡消费'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statements/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box purple">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-globe"></i>
                        <?php echo yii::t('app','会员卡消费');?>
                    </div>
                    <div class="actions">
                    	<div class="btn-group">
							<?php $this->widget('application.modules.admin.components.widgets.CompanySelect2', array('companyType'=>$this->comptype,'companyId'=>$this->companyId,'selectCompanyId'=>$selectDpid));?>
						</div>
                        <div class="btn-group">
                            <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
                                <input type="text" class="form-control ui_timepicker" name="begtime" id="begin_time" placeholder="<?php echo yii::t('app','起始时间');?>" value="<?php echo $begin_time; ?>">  
                                <span class="input-group-addon">~</span>
                                <input type="text" class="form-control ui_timepicker" name="endtime" id="end_time" placeholder="<?php echo yii::t('app','终止时间');?>"  value="<?php echo $end_time;?>">           
                            </div>  
                        </div>
                        <div class="btn-group">
                            <button type="submit" id="btn_time_query" class="btn red" ><i class="fa fa-pencial"></i><?php echo yii::t('app','查 询');?></button>
                        </div>
                    </div>
                </div>
                <div class="portlet-body" id="table-manage">
                <div class="dataTables_wrapper form-inline">
				<div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                        <thead>
                            <tr>
                                <th><?php echo yii::t('app','卡号');?></th>
                                <th><?php echo yii::t('app','姓名');?></th>
                                <th><?php echo yii::t('app','联系方式');?></th>
                                <th><?php echo yii::t('app','账单号');?></th>
                                <th><?php echo yii::t('app','下单时间');?></th>	
                                <th><?php echo yii::t('app','付款金额');?></th>	
                                </tr>
                        </thead>
                        <tbody>
                            <?php if($models) :?>
                            <?php foreach ($models as $model):?>
                            <tr class="odd gradeX">                               
                                <td><?php echo $model->card?$model->card->selfcode:'';?></td>
                                <td><?php echo $model->card?$model->card->name:'';?></td>
                                <td><?php echo $model->card?$model->card->mobile:'';?></td>                                
                                <td><?php echo $model->account_no;?></td>
                                <td><?php echo $model->create_at;?></td>
                                <td><?php echo $model->pay_amount;?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php endif;?>
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
        $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
        
   }
});
    $('#btn_time_query').click(function() {
        var begin_time = $('#begin_time').val();
        var end_time = $('#end_time').val();
        var selectDpid = $('select[name="selectDpid"]').val();
        location.href="<?php echo $this->createUrl('statements/memberconsume' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+'/selectDpid/'+selectDpid;  

    });
</script> 