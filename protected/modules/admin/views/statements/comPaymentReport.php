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
                <div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','支付方式（员工营业额）报表');?></div>
            	<div class="actions">
	            	<div class="btn-group">
						<?php $this->widget('application.modules.admin.components.widgets.CompanySelect2', array('companyType'=>$this->comptype,'companyId'=>$this->companyId,'selectCompanyId'=>$selectDpid));?>
					</div>
                    <select id="text" class="btn yellow" >
                            <option value="1" <?php if ($text==1){?> selected="selected" <?php }?> ><?php echo yii::t('app','年');?></option>
                            <option value="2" <?php if ($text==2){?> selected="selected" <?php }?> ><?php echo yii::t('app','月');?></option>
                            <option value="3" <?php if ($text==3){?> selected="selected" <?php }?> ><?php echo yii::t('app','日');?></option>
                    </select>
                    <div class="btn-group">
	                    <div class="input-group input-large date-picker input-daterange" data-date="<?php echo date('d/m/Y',strtotime('-1 months'));?>" data-date-format="mm/dd/yyyy">
	                         <input type="text" class="form-control" name="begtime" id="begin_time" placeholder="<?php echo yii::t('app','开始时间');?>" value="<?php echo $begin_time; ?>">  
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
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="sample_1">
		        <thead>
		            <tr>
		               <th><?php echo yii::t('app','时间');?></th>
		               <th><?php echo yii::t('app','总单数');?></th> 
		               <th><?php echo yii::t('app','毛利润');?></th> 
		               <th><?php echo yii::t('app','优惠');?></th>
		               <th><?php echo yii::t('app','实收款');?></th>
		               <th><?php echo yii::t('app','现金');?></th>
		               <th><?php echo yii::t('app','微信');?></th>
		               <th><?php echo yii::t('app','微点单');?></th>
		               <th><?php echo yii::t('app','微外卖');?></th>
		               <th><?php echo yii::t('app','支付宝');?></th>
		               <!--  
		               <th><?php echo yii::t('app','银联');?></th>
		               -->
		               <th><?php echo yii::t('app','会员卡');?></th>
		               <th><?php echo yii::t('app','微信储值');?></th> 
		               <th><?php echo yii::t('app','美团·外卖');?></th>
		               <th><?php echo yii::t('app','饿了么·外卖');?></th>
	                    <?php 
	                    	$paymentPayTotal = array();
	                    	foreach ($payments as $payment):
	                    	$paymentPayTotal[$payment['lid']] = array('pay_amount'=>0,'pay_count'=>0);
	                    ?>
	                         <th><?php echo $payment['name'];?></th>
	                    <?php endforeach;?>
		               <th><?php echo yii::t('app','系统券');?></th>
		               <th><?php echo yii::t('app','积分');?></th> 
		               <th><?php echo yii::t('app','退款');?></th>
					   <th><?php echo yii::t('app','支付方式总和');?></th>
		            </tr>
		        </thead>
				<tbody>
		        <?php if( $models) :?>
		        <!--foreach-->
		        <?php 
		        $orderNumTotal = 0;
		        $orderRealTotal = 0;
		        $orderDiscountTotal = 0;
		        $orderShouldTotal = 0;
		        $orderRetreatTotal = 0;
		        $apaytypeTotal = 0;
		        
		        $cashPayTotal = 0;$cashPayCountTotal = 0;
		        $wxPayTotal = 0;$wxPayCountTotal = 0;
		        $wddPayTotal = 0;$wddPayCountTotal = 0;
		        $wwmPayTotal = 0;$wwmPayCountTotal = 0;
		        $zfbPayTotal = 0;$zfbPayCountTotal = 0;
		        $hykPayTotal = 0;$hykPayCountTotal = 0;
		        $mtPayTotal = 0;$mtPayCountTotal = 0;
		        $elmPayTotal = 0;$elmPayCountTotal = 0;
		        $yhqPayTotal = 0;$yhqPayCountTotal = 0;
		        $jfPayTotal = 0;$jfPayCountTotal = 0;
		        $wxczPayTotal = 0;$wxczPayCountTotal = 0;
		        foreach ($models as $model):
		        	$paytypeTotal = 0; // 支付方式总和统计
		        	$order = $model['order'];
		        	$orderPay = $model['order_pay'];
		        	$discount = $order['reality_total']-$order['should_total'];
		        	$orderNumTotal += $order['order_num'];
		        	$orderRealTotal += $order['reality_total'];
		        	$orderDiscountTotal += $discount;
		        	$orderShouldTotal += $order['should_total'];
		        	$orderRetreatTotal += $order['order_retreat'];
		        	$cashPay = 0;$cashPayCount = 0;
		        	if(isset($orderPay['0-0'])){
		        		$cashPay = $orderPay['0-0']['pay_amount'];
		        		$cashPayCount = $orderPay['0-0']['pay_count'];
		        		$paytypeTotal += $cashPay;
		        	}
		        	$cashPayTotal += $cashPay;
		        	$cashPayCountTotal += $cashPayCount;
		        	
		        	$wxPay = 0;$wxPayCount = 0;
		        	if(isset($orderPay['1-0'])){
		        		$wxPay = $orderPay['1-0']['pay_amount'];
		        		$wxPayCount = $orderPay['1-0']['pay_count'];
		        		$paytypeTotal += $wxPay;
		        	}
		        	$wxPayTotal += $wxPay;
		        	$wxPayCountTotal += $wxPayCount;
		        	
		        	$wddPay = 0;$wddPayCount = 0;
		        	if(isset($orderPay['12-0'])){
		        		$wddPay = $orderPay['12-0']['pay_amount'];
		        		$wddPayCount = $orderPay['12-0']['pay_count'];
		        		$paytypeTotal += $wddPay;
		        	}
		        	$wddPayTotal += $wddPay;
		        	$wddPayCountTotal += $wddPayCount;
		        	
		        	$wwmPay = 0;$wwmPayCount = 0;
		        	if(isset($orderPay['13-0'])){
		        		$wwmPay = $orderPay['13-0']['pay_amount'];
		        		$wwmPayCount = $orderPay['13-0']['pay_count'];
		        		$paytypeTotal += $wwmPay;
		        	}
		        	$wwmPayTotal += $wwmPay;
		        	$wwmPayCountTotal += $wwmPayCount;
		        	
		        	$zfbPay = 0;$zfbPayCount = 0;
		        	if(isset($orderPay['2-0'])){
		        		$zfbPay = $orderPay['2-0']['pay_amount'];
		        		$zfbPayCount = $orderPay['2-0']['pay_count'];
		        		$paytypeTotal += $zfbPay;
		        	}
		        	$zfbPayTotal += $zfbPay;
		        	$zfbPayCountTotal += $zfbPayCount;
		        	
		        	$hykPay = 0;$hykPayCount = 0;
		        	if(isset($orderPay['4-0'])){
		        		$hykPay = $orderPay['4-0']['pay_amount'];
		        		$hykPayCount = $orderPay['4-0']['pay_count'];
		        		$paytypeTotal += $hykPay;
		        	}
		        	$hykPayTotal += $hykPay;
		        	$hykPayCountTotal += $hykPayCount;
		        	
		        	$mtPay = 0;$mtPayCount = 0;
		        	if(isset($orderPay['14-0'])){
		        		$mtPay = $orderPay['14-0']['pay_amount'];
		        		$mtPayCount = $orderPay['14-0']['pay_count'];
		        		$paytypeTotal += $mtPay;
		        	}
		        	$mtPayTotal += $mtPay;
		        	$mtPayCountTotal += $mtPayCount;
		        	
		        	$elmPay = 0;$elmPayCount = 0;
		        	if(isset($orderPay['15-0'])){
		        		$elmPay = $orderPay['15-0']['pay_amount'];
		        		$elmPayCount = $orderPay['15-0']['pay_count'];
		        		$paytypeTotal += $elmPay;
		        	}
		        	$elmPayTotal += $elmPay;
		        	$elmPayCountTotal += $elmPayCount;
		        	
		        	$jfPay = 0;$jfPayCount = 0;
		        	if(isset($orderPay['8-0'])){
		        		$jfPay = $orderPay['8-0']['pay_amount'];
		        		$jfPayCount = $orderPay['8-0']['pay_count'];
		        		$paytypeTotal += $jfPay;
		        	}
		        	$jfPayTotal += $jfPay;
		        	$jfPayCountTotal += $jfPayCount;

		        	$yhqPay = 0;$yhqPayCount = 0;
		        	if(isset($orderPay['9-0'])){
		        		$yhqPay = $orderPay['9-0']['pay_amount'];
		        		$yhqPayCount = $orderPay['9-0']['pay_count'];
		        		$paytypeTotal += $yhqPay;
		        	}
		        	$yhqPayTotal += $yhqPay;
		        	$yhqPayCountTotal += $yhqPayCount;
		        	
		        	$wxczPay = 0;$wxczPayCount = 0;
		        	if(isset($orderPay['10-0'])){
		        		$wxczPay = $orderPay['10-0']['pay_amount'];
		        		$wxczPayCount = $orderPay['10-0']['pay_count'];
		        		$paytypeTotal += $wxczPay;
		        	}
		        	$wxczPayTotal += $wxczPay;
		        	$wxczPayCountTotal += $wxczPayCount;
		        ?>
		
		        <tr class="odd gradeX">
		            <td><?php echo $order['create_at'];?></td>
		            <td><?php echo $order['order_num'];?></td>
		            <td><?php echo number_format($order['reality_total'],2);?></td>
		            <td><?php echo number_format($discount,2);?></td>
		            <td><?php echo number_format($order['should_total'],2);?></td>
		            <td><?php echo $cashPay?number_format($cashPay,2).'('.$cashPayCount.')':'';?></td>
	               	<td><?php echo $wxPay?number_format($wxPay,2).'('.$wxPayCount.')':'';?></td>
	               	<td><?php echo $wddPay?number_format($wddPay,2).'('.$wddPayCount.')':'';?></td>
	               	<td><?php echo $wwmPay?number_format($wwmPay,2).'('.$wwmPayCount.')':'';?></td>
	               	<td><?php echo $zfbPay?number_format($zfbPay,2).'('.$zfbPayCount.')':'';?></td>
	               	<td><?php echo $hykPay?number_format($hykPay,2).'('.$hykPayCount.')':'';?></td>
	               	<td><?php echo $wxczPay ? number_format($wxczPay,2).'('.$wxczPayCount.')':'';?></td>
	               	<td><?php echo $mtPay?number_format($mtPay,2).'('.$mtPayCount.')':'';?></td>
	               	<td><?php echo $elmPay?number_format($elmPay,2).'('.$elmPayCount.')':'';?></td>
		            <?php 
		            	foreach ($payments as $payment):
		            		$paymentPay = 0;$paymentPayCount = 0;
			            	if(isset($orderPay['3-'.(int)$payment['lid']])){
			            		$paymentPay = $orderPay['3-'.(int)$payment['lid']]['pay_amount'];
			            		$paymentPayCount = $orderPay['3-'.(int)$payment['lid']]['pay_count'];
			            	}
			            	$paymentPayTotal[$payment['lid']]['pay_amount'] += $paymentPay;
			            	$paymentPayTotal[$payment['lid']]['pay_count'] += $paymentPayCount;
			            	$paytypeTotal += $paymentPay;
		            ?>
	                    <td><?php echo $paymentPay?number_format($paymentPay,2).'('.$paymentPayCount.')':'';?></td>
	                <?php endforeach;?>
		            <td><?php echo $yhqPay ? number_format($yhqPay,2).'('.$yhqPayCount.')':'';?></td>
	                <td><?php echo $jfPay ? number_format($jfPay,2).'('.$jfPayCount.')':'';?></td> 
		            <td><?php echo number_format($order['order_retreat'],2);?></td>
					<?php if(number_format($paytypeTotal,2)==number_format($order['should_total'],2)):?>
					<td><?php echo number_format($paytypeTotal,2);?></td>
					<?php else:?>
					<td><span style="color:red"><?php echo number_format($paytypeTotal,2);?></span></td>
					<?php endif;?>					
		        </tr>
		       
		        <?php 
		        	$apaytypeTotal += $paytypeTotal;
		        	endforeach;
		        ?>	
		        <tr class="odd gradeX">
		            <td>总计</td>
		            <td><?php echo $orderNumTotal;?></td>
		             <td><?php echo number_format($orderRealTotal,2);?></td>
		            <td><?php echo number_format($orderDiscountTotal,2);?></td>
		            <td><?php echo number_format($orderShouldTotal,2);?></td>
		            <td><?php echo $cashPayTotal?number_format($cashPayTotal,2).'('.$cashPayCountTotal.')':'';?></td>
	               	<td><?php echo $wxPayTotal?number_format($wxPayTotal,2).'('.$wxPayCountTotal.')':'';?></td>
	               	<td><?php echo $wddPayTotal?number_format($wddPayTotal,2).'('.$wddPayCountTotal.')':'';?></td>
	               	<td><?php echo $wwmPayTotal?number_format($wwmPayTotal,2).'('.$wwmPayCountTotal.')':'';?></td>
	               	<td><?php echo $zfbPayTotal?number_format($zfbPayTotal,2).'('.$zfbPayCountTotal.')':'';?></td>
	               	<td><?php echo $hykPayTotal?number_format($hykPayTotal,2).'('.$hykPayCountTotal.')':'';?></td>
	               	<td><?php echo $wxczPayTotal ? number_format($wxczPayTotal,2).'('.$wxczPayCountTotal.')':'';?></td>
	               	<td><?php echo $mtPayTotal?number_format($mtPayTotal,2).'('.$mtPayCountTotal.')':'';?></td>
	               	<td><?php echo $elmPayTotal?number_format($elmPayTotal,2).'('.$elmPayCountTotal.')':'';?></td>
		            <?php 
		            	foreach ($payments as $payment):
		            		$paymentPayTo = $paymentPayTotal[$payment['lid']]['pay_amount'];
		            		$paymentPayCountTo = $paymentPayTotal[$payment['lid']]['pay_count'];
			            	
		            ?>
	                    <td><?php echo $paymentPayTo?number_format($paymentPayTo,2).'('.$paymentPayCountTo.')':'';?></td>
	                <?php endforeach;?>
		            <td><?php echo $yhqPayTotal ? number_format($yhqPayTotal,2).'('.$yhqPayCountTotal.')':'';?></td>
	                <td><?php echo $jfPayTotal ? number_format($jfPayTotal,2).'('.$jfPayCountTotal.')':'';?></td> 
		            <td><?php echo number_format($orderRetreatTotal,2);?></td>
					<td><?php echo number_format($apaytypeTotal,2);?></td>					
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
});
  	$('#btn_time_query').click(function time() {  
     	var begin_time = $('#begin_time').val();
     	var end_time = $('#end_time').val();
     	var text = $('#text').val();
     	var selectDpid = $('select[name="selectDpid"]').val();
     	location.href="<?php echo $this->createUrl('statements/comPaymentReport' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/text/"+text+"/selectDpid/"+selectDpid    
	});
	
	$('#excel').click(function excel(){
		var begin_time = $('#begin_time').val();
		var end_time = $('#end_time').val();
		var text = $('#text').val();
		var selectDpid = $('select[name="selectDpid"]').val();
		if(confirm('确认导出并且下载Excel文件吗？')){
			location.href="<?php echo $this->createUrl('statements/comPaymentExport' , array('companyId'=>$this->companyId));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/text/"+text;
		}
	});

</script> 
