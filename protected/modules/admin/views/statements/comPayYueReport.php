<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/css/datepicker.css';?>" />
   <!-- BEGIN PAGE -->
<div class="page-content">
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
            		<div class="btn-group">
            			<select  class="form-control select2me" name="selectDpid" data-placeholder="请选择店铺...">
							<option value=""></option>
							<?php foreach ($wxCompanys as $company):?>
							<option value="<?php echo $company['dpid'];?>" <?php if(strpos($selectDpid,$company['dpid'])!==false){ echo 'selected="selected"';}?>><?php echo $company['company_name'];?></option>
							<?php endforeach;?>
						</select>
					</div>
					<select name="text" class="btn yellow" >
						<option value="1" <?php if ($text==1){?> selected="selected" <?php }?> ><?php echo yii::t('app','年');?></option>
						<option value="2" <?php if ($text==2){?> selected="selected" <?php }?> ><?php echo yii::t('app','月');?></option>
						<option value="3" <?php if ($text==3){?> selected="selected" <?php }?> ><?php echo yii::t('app','日');?></option>
					</select>
                    <div class="btn-group">
	                    <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
	                         <input type="text" class="form-control" name="begtime" id="begin_time" placeholder="<?php echo yii::t('app','起始时间');?>" value="<?php echo $begin_time; ?>">
	                         <span class="input-group-addon">~</span>
	                         <input type="text" class="form-control" name="endtime" id="end_time" placeholder="<?php echo yii::t('app','终止时间');?>"  value="<?php echo $end_time;?>">
	                    </div>
                    </div>

                    <div class="btn-group">
                        <button type="submit" id="btn_time_query" class="btn red" ></i><?php echo yii::t('app','查 询');?></button>
                        <button type="button" id="excel"  class="btn blue" ><?php echo yii::t('app','导出Excel');?></button>
                    </div>
                </div>
            </div>

			<div class="portlet-body" id="table-manage">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="sample_1">
		        <thead>
		            <tr>
		                <?php  $grouppay_item = 0;?>
		               	<th><?php echo yii::t('app','日期');?></th>
		               	<th><?php echo yii::t('app','店铺名称');?></th>
		               	<th><?php echo yii::t('app','总单数');?></th>
		               	<th><?php echo yii::t('app','总营业额');?></th>
		               	<th><?php echo yii::t('app','微信点单');?></th>
		               	<th><?php echo yii::t('app','微信外卖');?></th>
		               	<th><?php echo yii::t('app','系统券');?></th>
		               	<th><?php echo yii::t('app','微信储值(充)');?></th>
						<th><?php echo yii::t('app','微信储值(返)');?></th>
		            </tr>
		        </thead>
				<tbody>

		        <!--foreach-->
		        <?php
		        $orders_total_nums = 0;
		        $orders_total_moneys = 0;
		        
		        $orders_total_wxord = 0;
		        $orders_total_wxord_count = 0;
		        $orders_total_wxwm = 0;
		        $orders_total_wxwm_count = 0;
		        $orders_total_cupon = 0;
		        $orders_total_cupon_count = 0;
		        $orders_total_cyue = 0;
		        $orders_total_cyue_count = 0;
		        $orders_total_fyue = 0;
		        $orders_total_fyue_count = 0;
		        

				if($models):?>
		      <?php 
		      	foreach ($models as $model): 
			      	$order = $model['order'];
			      	$orderPay = $model['order_pay'];
			      	$createArr = explode(':', $order['create_at']);
			      	$all_nums = $order['order_num'];
			      	$all_moneys = $order['should_total'];
			      	$orders_total_nums +=$all_nums;
			      	$orders_total_moneys +=$all_moneys;
			      	
			      	$yhqPay = 0;$yhqPayCount = 0;
			      	if(isset($orderPay['9-0'])){
			      		$yhqPay = $orderPay['9-0']['pay_amount'];
			      		$yhqPayCount = $orderPay['9-0']['pay_count'];
			      	}
			      	$orders_total_cupon += $yhqPay;
			      	$orders_total_cupon_count += $yhqPayCount;
			      	
			      	$cwxczPay = 0;$cwxczPayCount = 0;
			      	if(isset($orderPay['7-0'])){
			      		$cwxczPay = $orderPay['7-0']['pay_amount'];
			      		$cwxczPayCount = $orderPay['7-0']['pay_count'];
			      	}
			      	$orders_total_cyue += $cwxczPay;
			      	$orders_total_cyue_count += $cwxczPayCount;
			      	
			      	$fwxczPay = 0;$fwxczPayCount = 0;
			      	if(isset($orderPay['10-0'])){
			      		$fwxczPay = $orderPay['10-0']['pay_amount'];
			      		$fwxczPayCount = $orderPay['10-0']['pay_count'];
			      	}
			      	$orders_total_fyue += $fwxczPay;
			      	$orders_total_fyue_count += $fwxczPayCount;
			      	
			      	$wddPay = 0;$wddPayCount = 0;
			      	if(isset($orderPay['12-0'])){
			      		$wddPay = $orderPay['12-0']['pay_amount'];
			      		$wddPayCount = $orderPay['12-0']['pay_count'];
			      	}
			      	$orders_total_wxord += $wddPay;
			      	$orders_total_wxord_count += $wddPayCount;
			      	 
			      	$wwmPay = 0;$wwmPayCount = 0;
			      	if(isset($orderPay['13-0'])){
			      		$wwmPay = $orderPay['13-0']['pay_amount'];
			      		$wwmPayCount = $orderPay['13-0']['pay_count'];
			      	}
			      	$orders_total_wxwm += $wwmPay;
			      	$orders_total_wxwm_count += $wwmPayCount;
			      	
		      ?>

		        <tr class="odd gradeX">
		        	<td><?php echo $createArr[0];?></td>
		        	<td><?php echo $order['company_name'];?></td>
					<td><?php echo $all_nums;?></td>
		            <td><?php echo $all_moneys;?></td>
		            <td><?php echo $wddPay.'('.$wddPayCount.')';?></td>
		            <td><?php echo $wwmPay.'('.$wwmPayCount.')';?></td>
		            <td><?php echo $yhqPay.'('.$yhqPayCount.')';?></td>
		            <td><?php echo $cwxczPay.'('.$cwxczPayCount.')';?></td>
		            <td><?php echo $fwxczPay.'('.$fwxczPayCount.')';?></td>
		        </tr>

		        <?php endforeach;?>
		        <tr>
		            <td><?php echo "总计";?></td>
		            <td></td>
		            <td><?php echo $orders_total_nums; ?></td>
		            <td><?php echo $orders_total_moneys; ?></td>
		            <td><?php echo $orders_total_wxord.'('.$orders_total_wxord_count.')'; ?></td>
		            <td><?php echo $orders_total_wxwm.'('.$orders_total_wxwm_count.')'; ?></td>
		            <td><?php echo $orders_total_cupon.'('.$orders_total_cupon_count.')';?></td>
		            <td><?php echo $orders_total_cyue.'('.$orders_total_cyue_count.')';?></td>
					<td><?php echo $orders_total_fyue.'('.$orders_total_fyue_count.')';?></td>
		        </tr>
		       	<?php endif;?>
		        </tbody>
    	</table>
		</div>

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
	
	 $('#btn_time_query').click(function time() {
	     	var begin_time = $('#begin_time').val();
	     	var end_time = $('#end_time').val();
	     	var text = $('select[name="text"]').val();
	     	var selectDpid = $('select[name="selectDpid"]').val();
	     	location.href="<?php echo $this->createUrl('statements/comPayYueReport' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/text/"+text+"/selectDpid/"+selectDpid;
		});

		$('#excel').click(function excel(){
			//return false;
			var begin_time = $('#begin_time').val();
			var end_time = $('#end_time').val();
			var text = $('select[name="text"]').val();
			var selectDpid = $('select[name="selectDpid"]').val();
			if(confirm('确认导出并且下载Excel文件吗？')){
				location.href="<?php echo $this->createUrl('statements/comPayYueReport' , array('companyId'=>$this->companyId));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/text/"+text+"/selectDpid/"+selectDpid+'/d/1';
			}
		});
});
</script>
