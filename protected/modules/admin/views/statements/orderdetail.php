    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>"></script>
     <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/css/datepicker.css';?>" />
<style>
	.font18{
		font-size: 18px;
	}
	.font20{
		font-size: 20px;
	}
	.width50{
		width: 50%;
	}
	.floatleft{
		float: left;
	}	
	.contentdiv{
		text-align: center;
		width: 50%;
		float: left;
	}
	.detaildiv{
		text-align: center;
		width: 33%;
		float: left;
	}
	.detaildivtip{
		color: blue;		
		width: 96%;
		margin-left: 2%;
		padding: 6px;
		border-bottom: 1px solid blue;
	}
	.detailcontent{
		width: 96%;
		margin-left: 2%;
		padding: 4px;		
		border-bottom: 1px solid blue;
	}
	.contenthead{
		width: 96%;
		margin-left: 2%;
		padding: 4px;
		border-bottom: 1px solid red;
	}
	.contentheadtip{
		width: 96%;
		margin-left: 2%;
		padding: 4px;
		border-bottom: 1px solid white;
	}
	.clear{
		clear: both;
	}
       
    .accountno1{
        display: inline;
        width:429px;
        margin-left: 300px;        
     }   
    .find{
        margin-bottom: 20px;
     }
</style>  

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
	<div id="main2" name="main2" style="min-width: 500px;min-height:300px;display:none;" onMouseOver="this.style.backgroundColor='rgba(255,222,212,1)'" onmouseout="this.style.backgroundColor=''"></div>
	
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','数据中心'),'subhead'=>yii::t('app','营业数据'),'breadcrumbs'=>array(array('word'=>yii::t('app','营业数据'),'url'=>$this->createUrl('statements/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','账单详情查询'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statements/list' , array('companyId' => $this->companyId,'type'=>0)))));?>

	            <form action="" method="post" class="find">


                    <input type="text"  name="accountno1" class="accountno1 form-control" placeholder="账单号" value="<?php echo isset($accountno) && $accountno ?$accountno:'';?>" />     
                       <button type="submit" class="btn green">
                               查找 &nbsp;
                               <i class="m-icon-swapright m-icon-white"></i>
                       </button>
                </form>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','账单详情报表');?></div>
					<div class="actions">
						<div class="btn-group">
							<?php $this->widget('application.modules.admin.components.widgets.CompanySelect2', array('companyType'=>$this->comptype,'companyId'=>$this->companyId,'selectCompanyId'=>$selectDpid));?>
						</div>
						<div class="btn-group">
							<select id="paymentid" class="form-control btn yellow" >
	                            <option paymentid="1" value="-1" <?php if ($paymentid=='1' && $ordertype=='-1'){?> selected="selected" <?php }?> ><?php echo yii::t('app','--请选择订单类型--');?></option>
	                            <option paymentid="1" value="0" <?php if ($paymentid=='1' && $ordertype=='0'){?> selected="selected" <?php }?> ><?php echo yii::t('app','堂食');?></option>
	                            <option paymentid="1" value="1" <?php if ($paymentid=='1' && $ordertype=='1'){?> selected="selected" <?php }?> ><?php echo yii::t('app','微信桌台');?></option>
	                            <option paymentid="1" value="2" <?php if ($paymentid=='1' && $ordertype=='2'){?> selected="selected" <?php }?> ><?php echo yii::t('app','微信外卖');?></option>
	                            <option paymentid="1" value="4" <?php if ($paymentid=='1' && $ordertype=='4'){?> selected="selected" <?php }?> ><?php echo yii::t('app','后台外卖');?></option>
	                            <option paymentid="1" value="5" <?php if ($paymentid=='1' && $ordertype=='5'){?> selected="selected" <?php }?> ><?php echo yii::t('app','自助点单');?></option>
	                            <option paymentid="1" value="6" <?php if ($paymentid=='1' && $ordertype=='6'){?> selected="selected" <?php }?> ><?php echo yii::t('app','微信点单');?></option>
	                            <option paymentid="1" value="7" <?php if ($paymentid=='1'&& $ordertype=='7'){?> selected="selected" <?php }?> ><?php echo yii::t('app','美团外卖');?></option>
	                            <option paymentid="1" value="8" <?php if ($paymentid=='1'&& $ordertype=='8'){?> selected="selected" <?php }?> ><?php echo yii::t('app','饿了么外卖');?></option>
	                    	</select>
                    	</div>
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
							<!--  <a href="#" class="btn green" ><i class="fa fa-pencial"></i><?php echo yii::t('app','打 印');?></a>		  -->
					    </div>		
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
				<div class="dataTables_wrapper form-inline">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								
								<th><?php echo yii::t('app','账单号');?></th>
								<th><?php echo yii::t('app','下单时间');?></th>
								<th><?php echo yii::t('app','人数');?></th>
								<!-- <th><?php echo yii::t('app','座位');?></th> -->
                                <th><?php echo yii::t('app','类型');?></th>
                                <th><?php echo yii::t('app','原价');?></th>
                                <th><?php echo yii::t('app','优惠');?></th>                                                                
                                <th><?php echo yii::t('app','实收');?></th>
                                <th><?php echo yii::t('app','现金收款');?></th>
                                <th><?php echo yii::t('app','找零');?></th>
								
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<!--foreach-->
					
						<?php foreach ($models as $model):?>
								<tr class="odd gradeX">
								<td class="accountno" accountno="<?php echo $model['account_no'];?>" orderid="<?php echo $model['lid']?>" originalp="<?php echo sprintf("%.2f",$model['reality_total']);?>" shouldp="<?php echo sprintf("%.2f",$model['should_total']);?>" youhuip="<?php echo sprintf("%.2f",$model['reality_total']-$model['should_total']);?>"><?php echo $model['account_no']; ?></td>
								<td><?php echo $model['create_at'];?></td>
								<td><?php echo $model['number'];?></td>
								<td><?php
									if($model['order_type'] == 4){
											echo $model['channel_name'];
									}else{
										if($model['order_type']==0){
											echo '堂食';
										}elseif($model['order_type']==1){
											echo '微信桌台';
										}elseif($model['order_type']==2){
											echo '微信外卖';
										}elseif($model['order_type']==3){
											echo '微信预约';
										}elseif($model['order_type']==5){
											echo '自助点单';
										}elseif($model['order_type']==6){
											echo '微信点单';
										}elseif($model['order_type']==7){
											echo '美团外卖('.$model['callno'].')';
										}elseif($model['order_type']==8){
											echo '饿了么('.$model['callno'].')';
										}else{
											echo '其他';
										}
									}
								?></td>
								<td><?php echo sprintf("%.2f",$model['reality_total']);?></td>
								<td><?php echo sprintf("%.2f",$model['reality_total'] - $model['should_total']);?></td>
								<td><?php echo sprintf("%.2f",$model['should_total']);?></td>
								<td><?php echo sprintf("%.2f",OrderProduct::getMoney($model['dpid'],$model['lid']));?></td>
								<td><?php echo sprintf("%.2f",OrderProduct::getChange($model['dpid'],$model['lid']));?></td>
								</tr>
						
						<?php endforeach;?>	
						<!-- end foreach-->
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
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
	
	</div>
	<!-- END PAGE CONTENT-->

</div>

<script>
$(function () {
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
		$('.accountno').click(function() {
			  //alert(111);
			  $('#orderdetaildiv').remove();
			   var orderid = $(this).attr('orderid');
			   var accountno = $(this).attr('accountno');
			   var originalp = $(this).attr('originalp');
			   var shouldp = $(this).attr('shouldp');
			   var youhuip = $(this).attr('youhuip');
			   //alert(originalp); alert(shouldp);
			   var url = "<?php echo $this->createUrl('statements/accountDetail',array('companyId'=>$this->companyId));?>/orderid/"+orderid;
               $.ajax({
                   url:url,
                   type:'POST',
                   data:orderid,//CF
                   //async:false,
                   dataType: "json",
                   success:function(msg){
                       var data=msg;
                       if(data.status){
						//alert(data.msg);
							var model = data.msg;
							var change = data.change;
							var money = data.money;
							var prodDetailDivAll = '<div id="orderdetaildiv"><div class="contentheadtip font20">账单号：'+accountno+'</div><div class="contenthead font20"><div class="contentdiv"><span>菜品名称</span></div><div class="contentdiv"><span>数量</span></div><div class="clear"></div></div>';
							var prodDetailEnd = '</div>';
							var proDetailpayAll = '';
							for (var i in model){
								prodName = model[i].product_name;
								prodNum = model[i].all_amount;
								setName = model[i].set_name;
								var sets = '';
								if(setName){
									sets = '('+setName+')';
									}
								//alert(prodName);alert(prodNum);
								var prodDetailDivBody = '<div class="contenthead font18"><div class="contentdiv"><span>'+prodName+sets+'</span></div><div class="contentdiv"><span>'+prodNum+'</span></div><div class="clear"></div></div>' 
								prodDetailDivAll = prodDetailDivAll + prodDetailDivBody;
								}
							var proDetailBodyEnd = '<div class="font20 detaildivtip">账单详情</div>'
													+'<div class="detailcontent font18"><div class="detaildiv">原价:<span>'+originalp+'</span></div><div class="detaildiv">折后价:<span>'+shouldp+'</span></div><div class="detaildiv">优惠:<span>'+youhuip+'</span></div><div class="clear"></div></div>'
													+'<div class="detailcontent font18"><div class="detaildiv">收款现金:<span>'+money+'</span></div><div class="detaildiv">找零:<span>'+change+'</span></div><div class="clear"></div></div>';
							//var proDetailDiv = prodDetailDivAll+proDetailBodyEnd;
							var proDetailDiv = prodDetailDivAll;//去掉账单收支详情
							if(data.allpayment){
								var proDetailpayHead = '<div class="font20 detaildivtip">其他支付方式:</div>'
								var allpayment = data.allpayment;
								var proDetailpaymentall = '';
								for (var a in allpayment){
									var name = allpayment[a].name; 
									var nameprice = allpayment[a].pay_amount;
									var paytype = allpayment[a].paytype;
									if(name){
										//alert(name);
										var proDetailpayment = '<div class="detailcontent font18"><div class="detaildiv">'+name+':<span>'+nameprice+'</span></div><div class="clear"></div></div>';
											
										}else if(paytype){
											//alert(paytype);
											var paytypename = '';
											if (paytype==1){
												paytypename = '微信支付';
											}else if(paytype==2){
												paytypename = '支付宝支付';
											}else if(paytype==4){
												paytypename = '会员卡支付';
											}else if(paytype==5){
												paytypename = '银联支付';
											}else if(paytype==7){
												paytypename = '微信储值(充)';
											}else if(paytype==9){
												paytypename = '微信代金券';
											}else if(paytype==10){
												paytypename = '微信储值(返)';
											}else if(paytype==12){
												paytypename = '微信支付~';
											}else if(paytype==13){
												paytypename = '微信支付·';
											}
											else if(paytype==14){
												paytypename = '美团·外卖';
											}
											else if(paytype==15){
												paytypename = '饿了么·外卖';
											}
											var proDetailpayment = '<div class="detailcontent font18"><div class="detaildiv">'+paytypename+':<span>'+nameprice+'</span></div><div class="clear"></div></div>';
											}
									var proDetailpaymentall = proDetailpaymentall + proDetailpayment;
									}
								var proDetailpayAll =  proDetailpayHead + proDetailpaymentall + prodDetailEnd;
								}
							var proDetail = proDetailDiv + proDetailpayAll;
							$("#main2").append(proDetail);
            			   layer_zhexiantu=layer.open({
            				     type: 1,
            				     //shift:5,
            				     shade: [0.5,'#fff'],
            				     move:'#main2',
            				     moveOut:true,
            				     offset:['10px','350px'],
            				     shade: false,
            				     title: false, //不显示标题
            				     area: ['auto', 'auto'],
            				     content: $('#main2'),//$('#productInfo'), //捕获的元素
            				     cancel: function(index){
            				         layer.close(index);
            				         layer_zhexiantu=0;
            				     }
            				 });
            			   layer.style(layer_zhexiantu, {
            				   backgroundColor: 'rgba(255,255,255,0.2)',
            				 });  
                          
                       }else{
                           
                       }
                   },
                   error: function(msg){
                       layer.msg('网络错误！！！');
                   }
               });
			   

	        });
		       
		   $('#btn_time_query').click(function() {  
			  var paymentid = $("#paymentid").find("option:selected").attr("paymentid");
			  var otype = $('#paymentid').val();
			   var begin_time = $('#begin_time').val();
			   var end_time = $('#end_time').val();
			   var selectDpid = $('select[name="selectDpid"]').val();
			   location.href="<?php echo $this->createUrl('statements/orderdetail' , array('companyId'=>$this->companyId ));?>/otype/"+otype+"/begin_time/"+begin_time+"/end_time/"+end_time+'/selectDpid/'+selectDpid;    
	        });
		   $('#excel').click(function excel(){
		    	   var begin_time = $('#begin_time').val();
				   var end_time = $('#end_time').val();
				   var text = $('#text').val();
				   var otype = $('#paymentid').val();
			       if(confirm('确认导出并且下载Excel文件吗？')){
			    	   location.href="<?php echo $this->createUrl('statements/orderdetailExport' , array('companyId'=>$this->companyId,'d'=>1 ));?>/otype/"+otype+"/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text;
			       }
			      
			   });
</script> 