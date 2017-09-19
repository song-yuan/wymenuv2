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
    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','数据中心'),'subhead'=>yii::t('app','营业数据'),'breadcrumbs'=>array(array('word'=>yii::t('app','营业数据'),'url'=>$this->createUrl('statements/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','支付方式（员工营业额）报表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statements/list' , array('companyId' => $this->companyId,'type'=>0)))));?>

    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box purple">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','支付方式（员工营业额）报表');?></div>
            	<div class="actions">
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
					
                    <div class="btn-group">
                                    <button type="submit" id="btn_time_query" class="btn red" ><i class="fa fa-pencial"></i><?php echo yii::t('app','查 询');?></button>
                                    <button type="submit" id="excel"  class="btn green" ><i class="fa fa-pencial"></i><?php echo yii::t('app','导出Excel');?></button>				
                                    <!-- <a href="<?php echo $this->createUrl('statements/export' , array('companyId' => $this->companyId));?>/text/<?php echo $text;?>/begin_time/<?php echo $begin_time;?>/end_time/<?php echo $end_time;?>" class="btn green" ><i class="fa fa-pencial"></i><?php echo yii::t('app','导出Excel2');?></a> -->
                    </div>			
                </div>
            </div> 
			
			<div class="portlet-body" id="table-manage">
			<table class="table table-striped table-bordered table-hover" id="sample_1">
		        <thead>
		            <tr>
		                <?php  $grouppay_item = 0;?>
		               <th><?php echo yii::t('app','时间');?></th>
		               <th><?php echo yii::t('app','总单数');?></th> 
		               <th><?php echo yii::t('app','毛利润');?></th> 
		               <th><?php echo yii::t('app','优惠');?></th>
		               <th><?php echo yii::t('app','实收款');?></th>
		               <?php if($userid != '0'): ?>
		               <th><?php echo yii::t('app','营业员');?></th>
		               <?php endif;?>
		               <th><?php echo yii::t('app','现金');?></th>
		               <th><?php echo yii::t('app','微信');?></th>
		               <th><?php echo yii::t('app','微点单');?></th>
		               <th><?php echo yii::t('app','微外卖');?></th>
		               <th><?php echo yii::t('app','支付宝');?></th>
		               <th><?php echo yii::t('app','银联');?></th>
		               <th><?php echo yii::t('app','会员卡');?></th>
		               <th><?php echo yii::t('app','美团·外卖');?></th>
		               <th><?php echo yii::t('app','饿了么·外卖');?></th>
		               <?php if($payments):?>
		                    <?php foreach ($payments as $payment):?>
		                         <th><?php echo $payment['name'];
		                            $grouppay_item ++;
		                         ?></th>
		                    <?php endforeach;?>
		               <?php endif;?>   
		               <th><?php echo yii::t('app','系统券');?></th>
		               <th><?php echo yii::t('app','积分');?></th> 
		               <th><?php echo yii::t('app','微信余额');?></th>                                                            
		               <th><?php echo yii::t('app','退款');?></th>
		
		            </tr>
		        </thead>
				<tbody>
		        
		        <!--foreach-->
		        <?php $a=1;?>
		        <?php 
		         $orders_total=0;      // 总单数
		         $grossprofit_total=0; // 总毛利润
		         $discount_total=0;    // 总优惠
		         $gather_total=0;      // 实收款 
		         $cash_total=0;        // 现金
		         $wechat_total = 0;    // 微信
		         $wxorder_total = 0;    // 微信点单
		         $wxwaimai_total = 0;    // 微信外卖
		         $alipay_total = 0;    // 支付宝
		         $unionpay_total=0;    // 银联
		         $vipcard_total = 0;   // 会员卡 
		         $mtwm_total = 0;   // 对接美团
		         $eleme_total = 0;   // 对接饿了么
		         $htpay_total = 0;
		         $grouppay_arr = array();   //支付宝/美团
		        for($i =0;$i<$grouppay_item;$i++){
		           $grouppay_arr[$i] =0; 
		           // $grouppay.$i =0;
		        }
		        $all_wxcards = 0;
		        $all_wxcharges = 0;
		        $all_wxpoints = 0;
		        $retreats = 0;
		         if($prices):?>
		      	<?php foreach ($prices as $m): ?>
		
		        <tr class="odd gradeX">
		            <td><?php 
		            		if($text==1){
		            			echo $m['y_all'];
		            		}elseif($text==2){ 
								echo $m['y_all'].-$m['m_all'];
							}else{
								echo $m['y_all'].-$m['m_all'].-$m['d_all'];
							}
					?></td>
		            <td><?php $orders_total = $orders_total+$m['all_nums'];
		                echo $m['all_nums'];?></td>
		            <td><?php
		            		$reality_all = $m['maoli_money'];
		             		$grossprofit_total+=$reality_all;
		             		echo $reality_all;
		            ?></td>
		            
		            <td><?php 
				            //退款...
				            $retreat = $this->getRijieRetreat($m['dpid'],$begin_time,$end_time,$text,$m['y_all'],$m['m_all'],$m['d_all'],$userid,$m['username']);
				            $retreats+=$retreat;
				            
				            //折扣
				            $discount=sprintf("%.2f",$reality_all-$m['chunli_money']);
				            $discount_total += $discount;
				            echo $discount;
		            ?></td>
		            <td><?php 
		                	$gather=$m['chunli_money'];
		                	$gather_total += $gather;
		                	echo $gather;
		            ?></td>
		            <?php if($userid != '0'): ?>
		            <td><?php 
		                echo $m['username'].'('.$this->getUserstaffno($this->companyId,$m['username']).')';
		             ?></td>
		            <?php endif;?>
		            <td><?php 
		            		$cash = $m['cash_money'];
				            $cash_total += $cash;
				            echo $cash;
		            ?></td>
		            <td><?php
				            $wechat = $m['wx_money']; 
				            $wechat_total +=$wechat;
				            echo $wechat;
		            ?></td>
		            <td><?php
				            $wxorderpay = $m['wxord_money'];
				            $wxorder_total += $wxorderpay;
				            echo $wxorderpay;
		            ?></td>
		            <td><?php 
				            $wxwaimaipay = $m['wxwm_money'];
				            $wxwaimai_total += $wxwaimaipay;
				            echo $wxwaimaipay;
		            ?></td>
		            <td><?php
				            $alipay = $m['ali_money'];
				            $alipay_total += $alipay;
				            echo $alipay;
		            ?></td>
		            <td><?php 
				            $unionpay = $m['visa_money'];
				            $unionpay_total += $unionpay;
				            echo $unionpay;
		            ?></td>
		            <td id="alipay4"><?php 
				            $vipcard = $m['member_money'];
				            $vipcard_total += $vipcard;
				            echo $vipcard;
		            ?></td>
		            <td id="mtwm"><?php 
				            $mtwm = $m['mt_money'];
				            $mtwm_total += $mtwm;
				            echo $mtwm;
		            ?></td>
		            <td id="elemewm"><?php 
				            $elemewm = $m['elem_money'];
				            $eleme_total += $elemewm;
				            echo $elemewm;
		            ?></td>
		            <?php if($payments):?>
		                
		                <?php $j = 0;foreach ($payments as $payment):?>
		                    <td><?php 
		                           $pay_item =  $this->getRijiePrice($m['dpid'],$begin_time,$end_time,$payment['lid'],$text,$m['y_all'],$m['m_all'],$m['d_all'],$userid,$m['username']); 
		                           $grouppay_arr[$j] +=$pay_item;
		                            
		                            $j++;
		                            echo $pay_item;
		                            ?>
		                    </td>
		                <?php endforeach;?>
		            <?php endif;?> 
		            <td>
		            <?php 
		            $wxcard = $m['cupon_money'];
		            $all_wxcards = $all_wxcards + $wxcard;
		                echo $wxcard;
		                ?>
		            </td> 
		            <td><?php 
		            $wxpoint = $m['jifen_money'];
		            $all_wxpoints = $all_wxpoints + $wxpoint;
		            echo $wxpoint;
		                ?>
		            </td>
		            <td><?php 
		            $wxcharge = $m['wxyue_money'];
		            $all_wxcharges = $all_wxcharges + $wxcharge;
		            echo $wxcharge;
		                ?>
		            </td>
		            <td><?php 
		            	echo $retreat;
		            ?></td>
		            					
		        </tr>
		       
		        <?php endforeach;?>
		        <?php endif;?>
		        <tr>
		            <td><?php echo "总计";?></td>
		            <td><?php echo $orders_total; ?></td>
		            <td><?php  echo $grossprofit_total;?></td>
		            <td><?php echo $discount_total; ?></td>
		            <td><?php  echo $gather_total;?></td>
		            <?php if($userid != '0'): ?>
		                <td><?php   
		                    ?>
		                </td>
		            <?php endif;?>
		            <td><?php  echo $cash_total; ?></td>
		            <td><?php  echo $wechat_total;?></td>
		            <td><?php  echo $wxorder_total;?></td>
		            <td><?php  echo $wxwaimai_total;?></td>
		            <td><?php  echo $alipay_total;?></td>
		            <td><?php  echo $unionpay_total;?></td>
		            <td><?php  echo $vipcard_total; ?></td>
		            <td><?php  echo $mtwm_total; ?></td>
		            <td><?php  echo $eleme_total; ?></td>
		            <?php if($payments):?>
		                <?php  $j =0;foreach ($payments as $payment):?>
		                    <td><?php  echo $grouppay_arr[$j++];
		                   // echo $grouppay.$i;
		                   // $i++;
		                    ?></td>
		                    
		                <?php endforeach;?>
		            <?php endif;?> 
		            <td><?php echo $all_wxcards;?></td>
		            <td><?php echo $all_wxpoints;?></td>
		            <td><?php echo $all_wxcharges;?></td>
		            <td><?php echo $retreats;?></td>
										
		        </tr>

		       
		        
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
     	location.href="<?php echo $this->createUrl('statements/rijieReport' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/text/"+text+"/userid/"+userid    

	});
	
	$('#excel').click(function excel(){
		layer.msg('暂未开放');return false;
		var begin_time = $('#begin_time').val();
		var end_time = $('#end_time').val();
		var text = $('#text').val();
		var userid = $('#userid').val();
		if(confirm('确认导出并且下载Excel文件吗？')){
			location.href="<?php echo $this->createUrl('statements/paymentSqlExport' , array('companyId'=>$this->companyId));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/text/"+text+"/userid/"+userid;
		}else{
			// location.href="<?php echo $this->createUrl('statements/export' , array('companyId'=>$this->companyId ));?>/str/"+str+"/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text;
		}
	});

</script> 
