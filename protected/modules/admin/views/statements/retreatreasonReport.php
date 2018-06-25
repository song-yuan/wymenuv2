 <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>
	
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js');?>
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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','数据中心'),'subhead'=>yii::t('app','营业数据'),'breadcrumbs'=>array(array('word'=>yii::t('app','营业数据'),'url'=>$this->createUrl('statements/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','退菜原因统计报表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statements/list' , array('companyId' => $this->companyId,'type'=>0)))));?>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','退菜原因统计报表');?></div>
				<div class="actions">
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
				<div class="dataTables_wrapper form-inline">
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								
							<!-- 	<th>序号</th> -->
								<th><?php echo yii::t('app','时间');?></th>
								<th><?php echo yii::t('app','退菜原因');?></th>
                                <th><?php echo yii::t('app','次数');?></th>
                                <th><?php echo yii::t('app','退量');?></th> 
                              	<th><?php echo yii::t('app','金额');?></th>                                                         
                                <th><?php echo yii::t('app','备注');?></th>
								
							</tr>
						</thead>
						<tbody>
						<?php if( $models) :?>
						<!--foreach-->
						<?php $a=1;?>
						<?php foreach ($models as $model):?>

								<tr class="odd gradeX">
								<td><?php if($text==1){echo $model['y_all'];}elseif($text==2){ echo $model['y_all'].-$model['m_all'];}else{echo $model['y_all'].-$model['m_all'].-$model['d_all'];}?></td>
								
								<td><?php echo $model['name'];?></td>
								
								<td><?php echo $model['all_num'];?></td>
								<td><?php echo $model['all_amount'];?></td>
								<td><?php echo $model['all_retreatprice'];?></td>
								<td><?php ?></td>
								
							</tr>
						<?php $a++;?>
						<?php endforeach;?>	
						<!--<php foreach ($money as $moneys):?>
						<php 
						if(!empty($moneys)):?>
						<tr class="odd gradeX">
						<td><php if($text==1){echo $moneys['y_all'];}elseif($text==2){ echo $moneys['y_all'].-$moneys['m_all'];}else{echo $moneys['y_all'].-$moneys['m_all'].-$moneys['d_all'];}?></td>
						<td><php echo $moneys['company_name'];?></td>
						<td><php echo yii::t('app','会员卡支付');?></td>
						<td><php echo $moneys['all_huiyuan'];?></td>
						<td><php echo yii::t('app','请注意这是会员卡支付（每一页的数据都是一样的）');?></td>
						</tr>
						<php endif;?>
						<php endforeach;?>
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
	            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
	            
           }
		});
  
		   $('#btn_time_query').click(function time() {  
			   var begin_time = $('#begin_time').val();
			   var end_time = $('#end_time').val();
			   var text = $('#text').val();
			  // var cid = $(this).val();
			   location.href="<?php echo $this->createUrl('statements/retreatreasonReport' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/text/"+text    
			  
	        });
		   a = new Array();
		   $('#cx').click(function cx(){  
			   // var obj = document.getElementById('accept');
			    var obj=$('.checkedCN');
			   
			    var str=new Array();
					obj.each(function(){
						if($(this).attr("checked")=="checked")
						{
							
							str += $(this).val()+","
							
						}								
					});
				a = str = str.substr(0,str.length-1);//除去最后一个“，”
				//alert(str);
					  var begin_time = $('#begin_time').val();
					   var end_time = $('#end_time').val();
					   var text = $('#text').val();
					   
					   //var cid = $(this).val();
					  
					 location.href="<?php echo $this->createUrl('statements/retreatreasonReport' , array('companyId'=>$this->companyId ));?>/str/"+str+"/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text;	  
					 return a; 
			 });

			  $('#excel').click(function excel(){
// 				  var obj=$('#checkedCNid');
// 				    alert(obj);
// 				    var str=new Array();
// 						obj.each(function(){
// 							alert(1);
// 							if($(this).attr("checked")=="checked")
// 							{
// 								alert(str);
// 								str += $(this).val()+","
								
// 							}								
// 						});
// 					str = str.substr(0,str.length-1);//除去最后一个“，”
				   var str ='<?php echo $str;?>';
		    	   var begin_time = $('#begin_time').val();
				   var end_time = $('#end_time').val();
				   var text = $('#text').val();
				  
				   //alert(str);
			       if(confirm('确认导出并且下载Excel文件吗？')){
							//alert("<?php echo "sorry,您目前暂无权限！！！";?>")
							//return false;
			    	   location.href="<?php echo $this->createUrl('statements/retreatreasonReportExport' , array('companyId'=>$this->companyId,'d'=>1 ));?>/str/"+str+"/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text;
			       }
			       else{
			    	  // location.href="<?php echo $this->createUrl('statements/export' , array('companyId'=>$this->companyId ));?>/str/"+str+"/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text;
			       }
			      
			   });
			     excel();
			     cx();
</script> 
