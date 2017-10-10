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
    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','数据中心'),'subhead'=>yii::t('app','营业数据'),'breadcrumbs'=>array(array('word'=>yii::t('app','营业数据'),'url'=>$this->createUrl('statements/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','支付方式报表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statements/list' , array('companyId' => $this->companyId,'type'=>0)))));?>

    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box purple">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','支付方式报表');?></div>
            	<div class="actions">
                    <select id="text" class="btn yellow" >
                            <option value="1" <?php if ($text==1){?> selected="selected" <?php }?> ><?php echo yii::t('app','年');?></option>
                            <option value="2" <?php if ($text==2){?> selected="selected" <?php }?> ><?php echo yii::t('app','月');?></option>
                            <option value="3" <?php if ($text==3){?> selected="selected" <?php }?> ><?php echo yii::t('app','日');?></option>
                    </select>
                    <?php if(Yii::app()->user->role <11):?>
                    <div class="btn-group" style="width: 140px;">
						<input type="text" class="form-control" name="dpname" id="dpname" placeholder="<?php echo yii::t('app','店铺名称');?>" value="<?php echo $dpname;?>" >
					</div>
					<?php endif;?>
                    <div class="btn-group">
	                    <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
	                         <input type="text" class="form-control" name="begtime" id="begin_time" placeholder="<?php echo yii::t('app','起始时间');?>" value="<?php echo $begin_time; ?>">
	                         <span class="input-group-addon">~</span>
	                         <input type="text" class="form-control" name="endtime" id="end_time" placeholder="<?php echo yii::t('app','终止时间');?>"  value="<?php echo $end_time;?>">
	                    </div>
                    </div>

                    <div class="btn-group">
                                    <button type="submit" id="btn_time_query" class="btn red" ><i class="fa fa-pencial"></i><?php echo yii::t('app','查 询');?></button>
                                    <button type="submit" id="excel"  class="btn blue" ><i class="fa fa-pencial"></i><?php echo yii::t('app','导出Excel');?></button>
                                    <!-- <a href="<?php echo $this->createUrl('statements/export' , array('companyId' => $this->companyId));?>/text/<?php echo $text;?>/begin_time/<?php echo $begin_time;?>/end_time/<?php echo $end_time;?>" class="btn green" ><i class="fa fa-pencial"></i><?php echo yii::t('app','导出Excel2');?></a> -->
                    </div>
                </div>
            </div>

			<div class="portlet-body" id="table-manage">
			<table class="table table-striped table-bordered table-hover" id="sample_1">
		        <thead>
		            <tr>
		                <?php  $grouppay_item = 0;?>
		               <th><?php echo yii::t('app','店铺');?></th>
		               <th><?php echo yii::t('app','时间');?></th>
		               <th><?php echo yii::t('app','单数');?></th>
		               <th><?php echo yii::t('app','系统券');?></th>
		               <th><?php echo yii::t('app','单数');?></th>
		               <th><?php echo yii::t('app','微信储值消费');?></th>

		            </tr>
		        </thead>
				<tbody>

		        <!--foreach-->
		        <?php $a=1;?>
		        <?php
		        $orders_total_cupon=0;      // 总单数
		        $orders_total_yue=0;
		        $all_wxcards = 0;		//系统券
		        $all_wxcharges = 0;		//微信余额

				if($prices):?>
		      <?php foreach ($prices as $m): ?>

		        <tr class="odd gradeX">
		        	<td><?php echo $m['company_name'];?></td>
		            <td><?php
		            		if($text==1){
		            			echo $m['y_all'];
		            		}elseif($text==2){
								echo $m['y_all'].-$m['m_all'];
							}else{
								echo $m['y_all'].-$m['m_all'].-$m['d_all'];
							}
					?></td>
		            <td><?php $orders_total_cupon = $orders_total_cupon+$m['nums_cupon'];
		                echo $m['nums_cupon'];?></td>
		            <td>
		            <?php
		            $wxcard = $m['all_cupon'];
		            $all_wxcards = $all_wxcards + $wxcard;
		                echo $wxcard;
		                ?>
		            </td>
		            <td><?php $orders_total_yue = $orders_total_yue+$m['nums_yue'];
		                echo $m['nums_yue'];?>
		            </td>
		            
		            <td><?php
		            $wxcharge = $m['all_wxmember'];
		            $all_wxcharges = $all_wxcharges + $wxcharge;
		            echo $wxcharge;
		                ?>
		            </td>
		        </tr>

		        <?php endforeach;?>
		        <tr>
		        	<td></td>
		            <td><?php echo "总计";?></td>
		            <td><?php echo $orders_total_cupon; ?></td>
		            <td><?php echo $all_wxcards;?></td>
		            <td><?php echo $orders_total_yue; ?></td>
		            <td><?php echo $all_wxcharges;?></td>

		        </tr>
		       	<?php endif;?>
		        </tbody>
    	</table>


    </div>
</div>
			<!-- END EXAMPLE TABLE PORTLET-->
</div>
	<!-- END PAGE CONTENT-->
</div>
<!-- END PAGE -->

<script>
//var str=new array();
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
     	var text = $('#text').val();
     	var userid = $('#userid').val();
     	var dpname = $('#dpname').val();
     	location.href="<?php echo $this->createUrl('statements/comPayYueReport' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/text/"+text+"/dpname/"+dpname

	});

	$('#excel').click(function excel(){
		//return false;
		var begin_time = $('#begin_time').val();
		var end_time = $('#end_time').val();
		var text = $('#text').val();
		var userid = $('#userid').val();
     	var dpname = $('#dpname').val();
		if(confirm('确认导出并且下载Excel文件吗？')){
			location.href="<?php echo $this->createUrl('statements/comPayYueExport' , array('companyId'=>$this->companyId));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/text/"+text+"/userid/"+userid+"/dpname/"+dpname;
		}
	});

</script>
