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
		               <th><?php echo yii::t('app','店铺');?></th>
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
		               <th><?php echo yii::t('app','银联');?></th>
		               <th><?php echo yii::t('app','会员卡');?></th>
		               <th><?php echo yii::t('app','后台支付');?></th>  
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
		        $htpay_total = 0;		//后台支付统计
		        $all_wxcards = 0;		//系统券
		        $all_wxcharges = 0;		//微信余额
		        $all_wxpoints = 0;		//积分
		        $retreats = 0;			//退款
		        if( $models) :
		        foreach ($models as $model): ?>
		
		        <tr class="odd gradeX">
		        	<td><?php echo $model->dpid;?></td>
		            <td><?php if($text==1){echo $model->y_all;}elseif($text==2){ echo $model->y_all.-$model->m_all;}else{echo $model->y_all.-$model->m_all.-$model->d_all;}?></td>
		            <td><?php 
		                $orders_total = $orders_total+$model->all_nums;    //总单数
		                echo $model->all_nums;?></td>
		             <td><?php 
		                $reality_all = $this->getComGrossProfit($model->dpid,$begin_time,$end_time,$text,$model->y_all,$model->m_all,$model->d_all);
		                $grossprofit_total+=$reality_all;
		                echo $reality_all;
		                ?></td>
		            
		            <td><?php 
						//退款...
			            $retreat = $this->getComPaymentRetreat($model->dpid,$begin_time,$end_time,$text,$model->y_all,$model->m_all,$model->d_all);
			            $retreats+=$retreat;
		            	//优惠...
		                $discount=sprintf("%.2f",$reality_all-$model->all_reality+$retreat);
		                $discount_total += $discount;
		                echo $discount;
		            ?></td>
		            <td><?php 
		                $gather=$model->all_reality;
		                $gather_total += $gather;
		                echo $gather;
		            ?></td>
		            
		            <td><?php  
		                $cash = $this->getComPaymentPrice($model->dpid,$begin_time,$end_time,0,0,$text,$model->y_all,$model->m_all,$model->d_all);
		                $cash_total += $cash;
		                echo $cash;
		            ?></td>
		            <td><?php 
		                $wechat = $this->getComPaymentPrice($model->dpid,$begin_time,$end_time,0,1,$text,$model->y_all,$model->m_all,$model->d_all);
		                $wechat_total +=$wechat;
		                echo $wechat;
		            ?></td>
		            <td><?php 
		                $wxorderpay =  $this->getComPaymentPrice($model->dpid,$begin_time,$end_time,0,12,$text,$model->y_all,$model->m_all,$model->d_all);
		                $wxorder_total += $wxorderpay;
		                echo $wxorderpay;
		                ?>
		            </td>
		            <td><?php 
		                $wxwaimaipay =  $this->getComPaymentPrice($model->dpid,$begin_time,$end_time,0,13,$text,$model->y_all,$model->m_all,$model->d_all);
		                $wxwaimai_total += $wxwaimaipay;
		                echo $wxwaimaipay;
		                ?>
		            </td>
		            <td><?php
		                $alipay=$this->getComPaymentPrice($model->dpid,$begin_time,$end_time,0,2,$text,$model->y_all,$model->m_all,$model->d_all);
		                $alipay_total += $alipay;
		                echo $alipay; 
		            ?></td>
		            <td><?php 
		                $unionpay =  $this->getComPaymentPrice($model->dpid,$begin_time,$end_time,0,5,$text,$model->y_all,$model->m_all,$model->d_all);
		                $unionpay_total += $unionpay;
		                echo $unionpay;
		                ?>
		            </td>
		            <td id="alipay4"><?php 
		                $vipcard=$this->getComPaymentPrice($model->dpid,$begin_time,$end_time,0,4,$text,$model->y_all,$model->m_all,$model->d_all);
		                $vipcard_total += $vipcard;
		                echo $vipcard; 
		                ?>
		            </td>
		            <td>
		            <?php echo '';?>
		            </td> 
		            <td><?php 
		                $wxcard=$this->getComPaymentPrice($model->dpid,$begin_time,$end_time,0,9,$text,$model->y_all,$model->m_all,$model->d_all);
		                $all_wxcards = $all_wxcards + $wxcard;
		                echo $wxcard; 
		                ?>
		            </td>
		            <td><?php 
		                $wxpoint=$this->getComPaymentPrice($model->dpid,$begin_time,$end_time,0,8,$text,$model->y_all,$model->m_all,$model->d_all);
		                $all_wxpoints = $all_wxpoints + $wxpoint;
		                echo $wxpoint; 
		                ?>
		            </td>
		            <td><?php 
		                $wxcharge=$this->getComPaymentPrice($model->dpid,$begin_time,$end_time,0,10,$text,$model->y_all,$model->m_all,$model->d_all);
		                $all_wxcharges = $all_wxcharges + $wxcharge;
		                echo $wxcharge; 
		                ?>
		            </td>
		            <td><?php echo $retreat;?></td>
										
		        </tr>
		       
		        <?php endforeach;?>	
		        <?php endif;if($prices):?>
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
		            <td><?php $orders_total = $orders_total+$m['all_nums'];
		                echo $m['all_nums'];?></td>
		            <td><?php
		            		$reality_all = $m['all_should'];
		             		$grossprofit_total+=$reality_all;
		             		echo $reality_all;
		            ?></td>
		            
		            <td><?php 
				            //退款...
				            $retreat = $this->getComPaymentRetreat($m['dpid'],$begin_time,$end_time,$text,$m['y_all'],$m['m_all'],$m['d_all']);
				            $retreats+=$retreat;
				            $discount=sprintf("%.2f",$reality_all-$m['all_reality']+$retreat);
				            $discount_total += $discount;
				            echo $discount;
		            ?></td>
		            <td><?php 
		                	$gather=$m['all_reality'];
		                	$gather_total += $gather;
		                	echo $gather;
		            ?></td>
		            
		            <td><?php 
		            		$cash = $m['all_cash'];
				            $cash_total += $cash;
				            echo $cash;
		            ?></td>
		            <td><?php
				            $wechat = $m['all_wxpay']; 
				            $wechat_total +=$wechat;
				            echo $wechat;
		            ?></td>
		            <td><?php
				            $wxorderpay = $m['all_wxdd'];
				            $wxorder_total += $wxorderpay;
				            echo $wxorderpay;
		            ?></td>
		            <td><?php 
				            $wxwaimaipay = $m['all_wxwm'];
				            $wxwaimai_total += $wxwaimaipay;
				            echo $wxwaimaipay;
		            ?></td>
		            <td><?php
				            $alipay = $m['all_alipay'];
				            $alipay_total += $alipay;
				            echo $alipay;
		            ?></td>
		            <td><?php 
				            $unionpay = $m['all_bankpay'];
				            $unionpay_total += $unionpay;
				            echo $unionpay;
		            ?></td>
		            <td id="alipay4"><?php 
				            $vipcard = $m['all_member'];
				            $vipcard_total += $vipcard;
				            echo $vipcard;
		            ?></td>
		            <td><?php
		            $htpay = $m['all_htpay'];
		            $htpay_total += $htpay;
		            echo $htpay;  
		            ?></td>
		            <td>
		            <?php 
		            $wxcard = $m['all_cupon'];
		            $all_wxcards = $all_wxcards + $wxcard;
		                echo $wxcard;
		                ?>
		            </td> 
		            <td><?php 
		            $wxpoint = $m['all_point'];
		            $all_wxpoints = $all_wxpoints + $wxpoint;
		            echo $wxpoint;
		                ?>
		            </td>
		            <td><?php 
		            $wxcharge = $m['all_wxmember'];
		            $all_wxcharges = $all_wxcharges + $wxcharge;
		            echo $wxcharge;
		                ?>
		            </td>
		            <td><?php 
		            		echo $retreat;
		            ?></td>
		            					
		        </tr>
		       
		        <?php endforeach;?>
		        <tr>
		        	<td></td>
		            <td><?php echo "总计";?></td>
		            <td><?php echo $orders_total; ?></td>
		            <td><?php echo $grossprofit_total;?></td>
		            <td><?php echo $discount_total; ?></td>
		            <td><?php echo $gather_total;?></td>
		            <td><?php echo $cash_total; ?></td>
		            <td><?php echo $wechat_total;?></td>
		            <td><?php echo $wxorder_total;?></td>
		            <td><?php echo $wxwaimai_total;?></td>
		            <td><?php echo $alipay_total;?></td>
		            <td><?php echo $unionpay_total;?></td>
		            <td><?php echo $vipcard_total; ?></td>
		            <td><?php echo $htpay_total;?></td>
		            <td><?php echo $all_wxcards;?></td>
		            <td><?php echo $all_wxpoints;?></td>
		            <td><?php echo $all_wxcharges;?></td>
		            <td><?php echo $retreats;?></td>
										
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
     	location.href="<?php echo $this->createUrl('statements/comPaymentReport' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/text/"+text+"/dpname/"+dpname    

	});
	
	$('#excel').click(function excel(){
layer.msg('暂未开放');return false;
		var begin_time = $('#begin_time').val();
		var end_time = $('#end_time').val();
		var text = $('#text').val();
		var userid = $('#userid').val();
		if(confirm('确认导出并且下载Excel文件吗？')){
			location.href="<?php echo $this->createUrl('statements/comPaymentExport' , array('companyId'=>$this->companyId));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/text/"+text+"/userid/"+userid;
		}else{
			// location.href="<?php echo $this->createUrl('statements/export' , array('companyId'=>$this->companyId ));?>/str/"+str+"/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text;
		}
	});

</script> 
