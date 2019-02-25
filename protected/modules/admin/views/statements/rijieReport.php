<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>"></script>
 <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/css/datepicker.css';?>" /> 
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
    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','数据中心'),'subhead'=>yii::t('app','营业数据'),'breadcrumbs'=>array(array('word'=>yii::t('app','营业数据'),'url'=>$this->createUrl('statements/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','支付方式（员工营业额）报表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statements/list' , array('companyId' => $this->companyId,'type'=>0)))));?>

    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
    	<div class="col-md-12">
    		<?php $this->widget('application.modules.admin.components.widgets.CompanySelect2', array('companyType'=>$this->comptype,'companyId'=>$this->companyId,'selectCompanyId'=>$selectDpid));?>
    		<select id="userid" class="btn yellow" >
             	<option value="0" <?php if ($userid==0){?> selected="selected" <?php }?> ><?php echo yii::t('app','--请选择服务员--');?></option>
                <option value="-1" <?php if ($userid==-1){?> selected="selected" <?php }?> ><?php echo yii::t('app','--列出所有--');?></option>
                <?php if($username):?>
                <?php foreach ($username as $user):?>
                <option value="<?php echo $user['username'];?>" <?php if ($userid==$user['username']){?> selected="selected" <?php }?> ><?php echo $user['username'].'('.$user['staff_no'].')';?></option>
                <?php endforeach;?>
                <?php endif;?>
             </select>
             <select id="text" class="btn yellow" >
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
              <a id="btn_time_query" class="btn red" ><?php echo yii::t('app','查 询');?></a>
              <a id="excel"  class="btn green" ><?php echo yii::t('app','导出Excel');?></a>				
    	</div>
    </div>
    <br>
    <div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box purple">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','支付方式（员工营业额）报表');?></div>
            </div> 
			
			<div class="portlet-body" id="table-manage">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="sample_1">
		        <thead>
		            <tr>
		                <?php  $grouppay_item = 0;?>
		               <th><?php echo yii::t('app','时间');?></th>
		               <th><?php echo yii::t('app','总单数');?></th> 
		               <th><?php echo yii::t('app','毛利润');?></th> 
		               <th><?php echo yii::t('app','折扣优惠');?></th>
		               <th><?php echo yii::t('app','营业额');?></th>
		               <th><?php echo yii::t('app','实收款');?></th>
		               <?php if($userid != '0'): ?>
		               <th><?php echo yii::t('app','营业员');?></th>
		               <?php endif;?>
		               <th><?php echo yii::t('app','现金');?></th>
		               <th><?php echo yii::t('app','微信');?></th>
		               <th><?php echo yii::t('app','微点单');?></th>
		               <th><?php echo yii::t('app','微外卖');?></th>
		               <th><?php echo yii::t('app','支付宝');?></th>
		               <th><?php echo yii::t('app','会员卡');?></th>
		               <th><?php echo yii::t('app','微信储值(充)');?></th>
		               <th><?php echo yii::t('app','微信储值(返)');?></th>
		               <th><?php echo yii::t('app','美团·外卖');?></th>
		               <th><?php echo yii::t('app','饿了么·外卖');?></th>
		               <?php 
	                    	foreach ($payments as $payment):
	                    ?>
		                   <th><?php echo $payment['name'];?></th>
		                   <?php endforeach;?>
		               <th><?php echo yii::t('app','微信现金券');?></th>
		               <th><?php echo yii::t('app','微信积分');?></th> 
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
		        $allOrderTotal = 0;
		        $orderRetreatTotal = 0;
		        $apaytypeTotal = 0;
		        $paymentPayTotal = array();
		        
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
		        $cwxczPayTotal = 0;$cwxczPayCountTotal = 0;
		        $fwxczPayTotal = 0;$fwxczPayCountTotal = 0;
		        foreach ($models as $key=>$model):
		        	$paytypeTotal = 0; // 支付方式总和统计
		        	$orderTotal = 0;// 实收款
		        	$orderPay = $model;
		        	$orderNum = 0;
		        	$orderReal = 0; //毛利润
		        	$orderShould = 0;
		        	if(isset($orderPay['20-0'])){
		        		$orderReal =$orderPay['20-0']['pay_amount'];
		        		$orderNum = $orderPay['20-0']['pay_count'];
		        	}
		        	if(isset($orderPay['22-0'])){
		        		$orderShould =$orderPay['22-0']['pay_amount'];
		        	}
		        	$discount = $orderReal-$orderShould;
		        	$orderDiscountTotal += $discount;
		        	
		        	
		        	$cashPay = 0;$cashPayCount = 0;
		        	if(isset($orderPay['0-0'])){
		        		$cashPay = $orderPay['0-0']['pay_amount'];
		        		$cashPayCount = $orderPay['0-0']['pay_count'];
		        	}
		        	$orderTotal += $cashPay;
		        	$paytypeTotal += $cashPay;
		        	$cashPayTotal += $cashPay;
		        	$cashPayCount += $cashPayCount;
		        	
		        	$wxPay = 0;$wxPayCount = 0;
		        	if(isset($orderPay['1-0'])){
		        		$wxPay = $orderPay['1-0']['pay_amount'];
		        		$wxPayCount = $orderPay['1-0']['pay_count'];
		        	}
		        	$orderTotal += $wxPay;
		        	$paytypeTotal += $wxPay;
		        	$wxPayTotal += $wxPay;
		        	$wxPayCountTotal += $wxPayCount;
		        	
		        	$wddPay = 0;$wddPayCount = 0;
		        	if(isset($orderPay['12-0'])){
		        		$wddPay = $orderPay['12-0']['pay_amount'];
		        		$wddPayCount = $orderPay['12-0']['pay_count'];
		        	}
		        	$orderTotal += $wddPay;
		        	$paytypeTotal += $wddPay;
		        	$wddPayTotal += $wddPay;
		        	$wddPayCountTotal += $wddPayCount;
		        	
		        	$wwmPay = 0;$wwmPayCount = 0;
		        	if(isset($orderPay['13-0'])){
		        		$wwmPay = $orderPay['13-0']['pay_amount'];
		        		$wwmPayCount = $orderPay['13-0']['pay_count'];
		        	}
		        	$orderTotal += $wwmPay;
		        	$paytypeTotal += $wwmPay;
		        	$wwmPayTotal += $wwmPay;
		        	$wwmPayCountTotal += $wwmPayCount;
		        	
		        	$zfbPay = 0;$zfbPayCount = 0;
		        	if(isset($orderPay['2-0'])){
		        		$zfbPay = $orderPay['2-0']['pay_amount'];
		        		$zfbPayCount = $orderPay['2-0']['pay_count'];
		        	}
		        	$orderTotal += $zfbPay;
		        	$paytypeTotal += $zfbPay;
		        	$zfbPayTotal += $zfbPay;
		        	$zfbPayCountTotal += $zfbPayCount;
		        	
		        	$hykPay = 0;$hykPayCount = 0;
		        	if(isset($orderPay['4-0'])){
		        		$hykPay = $orderPay['4-0']['pay_amount'];
		        		$hykPayCount = $orderPay['4-0']['pay_count'];
		        	}
		        	$orderTotal += $hykPay;
		        	$paytypeTotal += $hykPay;
		        	$hykPayTotal += $hykPay;
		        	$hykPayCountTotal += $hykPayCount;
		        	
		        	$mtPay = 0;$mtPayCount = 0;
		        	if(isset($orderPay['14-0'])){
		        		$mtPay = $orderPay['14-0']['pay_amount'];
		        		$mtPayCount = $orderPay['14-0']['pay_count'];
		        	}
		        	$orderTotal += $mtPay;
		        	$paytypeTotal += $mtPay;
		        	$mtPayTotal += $mtPay;
		        	$mtPayCountTotal += $mtPayCount;
		        	
		        	$elmPay = 0;$elmPayCount = 0;
		        	if(isset($orderPay['15-0'])){
		        		$elmPay = $orderPay['15-0']['pay_amount'];
		        		$elmPayCount = $orderPay['15-0']['pay_count'];
		        	}
		        	$orderTotal += $elmPay;
		        	$paytypeTotal += $elmPay;
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
		        		//现金券
		        		$yhqPay = $orderPay['9-0']['pay_amount'];
		        		$yhqPayCount = $orderPay['9-0']['pay_count'];
		        		$paytypeTotal += $yhqPay;
		        	}
		        	$yhqPayTotal += $yhqPay;
		        	$yhqPayCountTotal += $yhqPayCount;
		        	
		        	$cwxczPay = 0;$cwxczPayCount = 0;
		        	if(isset($orderPay['7-0'])){
		        		$cwxczPay = $orderPay['7-0']['pay_amount'];
		        		$cwxczPayCount = $orderPay['7-0']['pay_count'];
		        	}
		        	$orderTotal += $cwxczPay;
		        	$paytypeTotal += $cwxczPay;
		        	$cwxczPayTotal += $cwxczPay;
		        	$cwxczPayCountTotal += $cwxczPayCount;
		        	
		        	$fwxczPay = 0;$fwxczPayCount = 0;
		        	if(isset($orderPay['10-0'])){
		        		$fwxczPay = $orderPay['10-0']['pay_amount'];
		        		$fwxczPayCount = $orderPay['10-0']['pay_count'];
		        		$paytypeTotal += $fwxczPay;
		        	}
		        	$fwxczPayTotal += $fwxczPay;
		        	$fwxczPayCountTotal += $fwxczPayCount;
		        	
		        	$paymentPayArr = array();
		        	foreach ($payments as $payment){
		        		$paymentPay = 0;$paymentPayCount = 0;
		        		if(isset($orderPay['3-'.(int)$payment['lid']])){
		        			$paymentPay = $orderPay['3-'.(int)$payment['lid']]['pay_amount'];
		        			$paymentPayCount = $orderPay['3-'.(int)$payment['lid']]['pay_count'];
		        		}
		        		if(!isset($paymentPayTotal[$payment['lid']])){
		        			$paymentPayTotal[$payment['lid']] = array('pay_amount'=>0,'pay_count'=>0);
		        		}
		        		if(!isset($paymentPayArr[$payment['lid']])){
		        			$paymentPayTotal[$payment['lid']] = array('pay_amount'=>0,'pay_count'=>0);
		        		}
		        		$paymentPayArr[$payment['lid']]['pay_amount'] = $paymentPay;
		        		$paymentPayArr[$payment['lid']]['pay_count'] = $paymentPayCount;
		        		$paymentPayTotal[$payment['lid']]['pay_amount'] += $paymentPay;
		        		$paymentPayTotal[$payment['lid']]['pay_count'] += $paymentPayCount;
		        		$orderTotal += $paymentPay;
		        		$paytypeTotal += $paymentPay;
		        	}
		        	
		        	$orderRealTotal += $orderReal;
		        	$orderShouldTotal += $orderShould;
		        	$allOrderTotal += $orderTotal;
		        	$orderNumTotal += $orderNum;
		        	
		        	$apaytypeTotal += $paytypeTotal;
		        ?>
		
		        <tr class="odd gradeX">
		            <td><?php echo $key;?></td>
		            <td><?php echo $orderNum;?></td>
		            <td><?php echo number_format($orderReal,2);?></td>
		            <td><?php echo number_format($discount,2);?></td>
		            <td><?php echo number_format($orderShould,2);?></td>
		            <td><?php echo number_format($orderTotal,2);?></td>
		            <?php if($userid != '0'): ?>
		            	<td><?php echo $seUsername;?></td>
		            <?php endif;?>
		            <td><?php echo $cashPay?number_format($cashPay,2).'('.$cashPayCount.')':'';?></td>
	               	<td><?php echo $wxPay?number_format($wxPay,2).'('.$wxPayCount.')':'';?></td>
	               	<td><?php echo $wddPay?number_format($wddPay,2).'('.$wddPayCount.')':'';?></td>
	               	<td><?php echo $wwmPay?number_format($wwmPay,2).'('.$wwmPayCount.')':'';?></td>
	               	<td><?php echo $zfbPay?number_format($zfbPay,2).'('.$zfbPayCount.')':'';?></td>
	               	<td><?php echo $hykPay?number_format($hykPay,2).'('.$hykPayCount.')':'';?></td>
	               	<td><?php echo $cwxczPay ? number_format($cwxczPay,2).'('.$cwxczPayCount.')':'';?></td>
	               	<td><?php echo $fwxczPay ? number_format($fwxczPay,2).'('.$fwxczPayCount.')':'';?></td>
	               	<td><?php echo $mtPay?number_format($mtPay,2).'('.$mtPayCount.')':'';?></td>
	               	<td><?php echo $elmPay?number_format($elmPay,2).'('.$elmPayCount.')':'';?></td>
		            <?php 
		            	foreach ($payments as $payment):
			            		$paymentPay = $paymentPayArr[$payment['lid']]['pay_amount'];
			            		$paymentPayCount = $paymentPayArr[$payment['lid']]['pay_count'];
		            ?>
	                <td><?php echo $paymentPay?number_format($paymentPay,2).'('.$paymentPayCount.')':'';?></td>
	                <?php endforeach;?>
	                 <td><?php echo $yhqPay ? number_format($yhqPay,2).'('.$yhqPayCount.')':'';?></td>
	                <td><?php echo $jfPay ? number_format($jfPay,2).'('.$jfPayCount.')':'';?></td> 
					<?php if(number_format($paytypeTotal,2)==number_format($orderShould,2)):?>
					<td><?php echo number_format($paytypeTotal,2);?></td>
					<?php else:?>
					<td><span style="color:red"><?php echo number_format($paytypeTotal,2);?></span></td>
					<?php endif;?>					
		        </tr>
		       
		        <?php 
		        	endforeach;
		        ?>	
		        <tr class="odd gradeX">
		            <td>总计</td>
		            <td><?php echo $orderNumTotal;?></td>
		             <td><?php echo number_format($orderRealTotal,2);?></td>
		            <td><?php echo number_format($orderDiscountTotal,2);?></td>
		            <td><?php echo number_format($orderShouldTotal,2);?></td>
		            <td><?php echo number_format($allOrderTotal,2);?></td>
		            <?php if($userid != '0'): ?>
		            	<td><?php echo $seUsername;?></td>
		            <?php endif;?>
		            <td><?php echo $cashPayTotal?number_format($cashPayTotal,2).'('.$cashPayCountTotal.')':'';?></td>
	               	<td><?php echo $wxPayTotal?number_format($wxPayTotal,2).'('.$wxPayCountTotal.')':'';?></td>
	               	<td><?php echo $wddPayTotal?number_format($wddPayTotal,2).'('.$wddPayCountTotal.')':'';?></td>
	               	<td><?php echo $wwmPayTotal?number_format($wwmPayTotal,2).'('.$wwmPayCountTotal.')':'';?></td>
	               	<td><?php echo $zfbPayTotal?number_format($zfbPayTotal,2).'('.$zfbPayCountTotal.')':'';?></td>
	               	<td><?php echo $hykPayTotal?number_format($hykPayTotal,2).'('.$hykPayCountTotal.')':'';?></td>
	               	<td><?php echo $cwxczPayTotal ? number_format($cwxczPayTotal,2).'('.$cwxczPayCountTotal.')':'';?></td>
	               	<td><?php echo $fwxczPayTotal ? number_format($fwxczPayTotal,2).'('.$fwxczPayCountTotal.')':'';?></td>
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
    	var selectDpid = $('select[name="selectDpid"]').val();
     	var begin_time = $('#begin_time').val();
     	var end_time = $('#end_time').val();
     	var text = $('#text').val();
     	var userid = $('#userid').val();
     	location.href="<?php echo $this->createUrl('statements/rijieReport' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/text/"+text+"/userid/"+userid+"/selectDpid/"+selectDpid;  

	});
	
	$('#excel').click(function(){
		var selectDpid = $('select[name="selectDpid"]').val();
		var begin_time = $('#begin_time').val();
		var end_time = $('#end_time').val();
		var text = $('#text').val();
		var userid = $('#userid').val();
		if(confirm('确认导出并且下载Excel文件吗？')){
			location.href="<?php echo $this->createUrl('statements/rijieReport' , array('companyId'=>$this->companyId));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/text/"+text+"/userid/"+userid+"/selectDpid/"+selectDpid+"/d/1";
		}
	});
	$('select[name="selectDpid"]').change(function(){
		var selectDpid = $(this).val();
		$.ajax({
				url:'<?php echo $this->createUrl('statements/ajaxGetusername',array('companyId'=>$this->companyId));?>/selectDpid/'+selectDpid,
				success:function(data){
					var str = '';
					str += '<option value="0" selected="selected">--请选择服务员--</option>';
					str += '<option value="-1" selected="selected">--列出所有--</option>';
					for(var i in data){
						var obj = data[i];
		                str +='<option value="'+obj.username+'">'+obj.username+'('+obj.staff_no+')</option>';
					}
					$('#userid').html(str);
				},
				dataType:'json'
			});
	});
</script> 
