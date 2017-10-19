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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','数据中心'),'subhead'=>yii::t('app','营业数据'),'breadcrumbs'=>array(array('word'=>yii::t('app','营业数据'),'url'=>$this->createUrl('statements/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','单品销售报表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statements/list' , array('companyId' => $this->companyId,'type'=>0)))));?>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','单品销售报表');?></div>
				<div class="actions">
					<select id="ordertype" class="btn yellow" >
					<option value="0" <?php if ($ordertype==0){?> selected="selected" <?php }?> ><?php echo yii::t('app','全部');?></option>
					<option value="1" <?php if ($ordertype==1){?> selected="selected" <?php }?> ><?php echo yii::t('app','堂食');?></option>
					<option value="2" <?php if ($ordertype==2){?> selected="selected" <?php }?> ><?php echo yii::t('app','微信堂食');?></option>
					<option value="3" <?php if ($ordertype==3){?> selected="selected" <?php }?> ><?php echo yii::t('app','外卖');?></option>
					<option value="4" <?php if ($ordertype==4){?> selected="selected" <?php }?> ><?php echo yii::t('app','微信外卖');?></option>
					<option value="5" <?php if ($ordertype==5){?> selected="selected" <?php }?> ><?php echo yii::t('app','套餐');?></option>
					</select>
					<select id="text" class="btn yellow" >
					<option value="1" <?php if ($text==1){?> selected="selected" <?php }?> ><?php echo yii::t('app','年');?></option>
					<option value="2" <?php if ($text==2){?> selected="selected" <?php }?> ><?php echo yii::t('app','月');?></option>
					<option value="3" <?php if ($text==3){?> selected="selected" <?php }?> ><?php echo yii::t('app','日');?></option>
					</select>
					<select id="setid" class="btn green" >
					<option value="1" <?php if ($setid==1){?> selected="selected" <?php }?> ><?php echo yii::t('app','综合');?></option>
					<option value="0" <?php if ($setid==0){?> selected="selected" <?php }?> ><?php echo yii::t('app','单品');?></option>
					<option value="2" <?php if ($setid==2){?> selected="selected" <?php }?> ><?php echo yii::t('app','套餐单品');?></option>
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
					</div>			
			    </div>
			 </div> 
			
				<div class="portlet-body" id="table-manage">
				<div class="dataTables_wrapper form-inline">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								
								
								<th><?php echo yii::t('app','时间');?></th>
								<th>
									<div class="btn-group">
										<button type="button" class="btn blue"><?php echo yii::t('app','请选择店铺');?></button>
										<button type="button" class="btn blue dropdown-toggle" data-toggle="dropdown"><i class="fa fa-angle-down"></i></button>
										<div class="dropdown-menu hold-on-click dropdown-checkboxes" role="menu">
											
											
											<?php foreach($comName as $key=>$value):?>

											<label><input name="accept" id="cked" class="checkedCN" value="<?php echo $key;?>" type="checkbox"><?php echo $value;?></label>
											  
											<?php endforeach;?>
											
											 <button type="submit" id="cx" class="btn red" ><i class="fa fa-pencial"></i><?php echo yii::t('app','确定');?></button> 
												
										</div>
									</div>
								</th>
								<!-- <th><div class=""><php echo CHtml::dropdownlist('selectUser',$catId,$comName,array('class'=>'form-control'));?></div></th> -->
                                <th><?php echo yii::t('app','单品名称');?></th>
                                <th><?php echo yii::t('app','排名');?></th>
                                <th><?php echo yii::t('app','销量');?></th>
                                <th><?php echo yii::t('app','销售金额');?></th>                                                        
                                <th><?php echo yii::t('app','折扣金额');?></th>
								<th><?php echo yii::t('app','实收金额');?></th>
								<th><?php echo yii::t('app','原始均价');?></th>
								<th><?php echo yii::t('app','折后均价');?></th>
								<th><?php echo yii::t('app','备注');?></th>
								
							</tr>
						</thead>
						<tbody>
						<?php if( $models) :?>
						<!--foreach-->
						<?php $a=1;?>
						<?php foreach ($models as $model):?>
						<?php if($model->all_total == 0 || $model->all_total == null): $model->total = 1;endif;?>
								<tr class="odd gradeX">
								<td><?php if($text==1){echo $model->y_all;}elseif($text==2){ echo $model->y_all.-$model->m_all;}else{echo $model->y_all.-$model->m_all.-$model->d_all;}?></td>
								<td><?php echo $model->company->company_name;?></td>
								<td><?php if($model->product_type !=2) echo $model->product_name;else echo '打包费';?></td>
								<td><?php echo $a+$pages->getCurrentPage()*10;?></td>
								<td><?php echo $model->all_total;?></td>
								<td><?php echo sprintf("%.2f",$model->all_jiage);?></td>
								<td><?php echo sprintf("%.2f",$model->all_jiage-$model->all_price);?></td>
								<td><?php echo sprintf("%.2f",$model->all_price);?></td>
								<td><?php echo sprintf("%.2f",$model->all_jiage/$model->all_total);?></td>
								<td><?php echo sprintf("%.2f",$model->all_price/$model->all_total);?></td>
								<td></td>
								
							</tr>
						<?php $a++;?>
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

		   $('#btn_time_query').click(function() {
			  // alert($('#begin_time').val()); 
			  // alert($('#end_time').val()); 
			  // alert(111);
			   var ordertype = $('#ordertype').val();
			   var begin_time = $('#begin_time').val();
			   var end_time = $('#end_time').val();
			   var text = $('#text').val();
			   var setid = $('#setid').val();
			  // var cid = $(this).val();
			 // alert ($('#text').val());
			   location.href="<?php echo $this->createUrl('statements/ceshiproductReport' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text+"/ordertype/"+ordertype+"/setid/"+setid;
			  
	        });
		   $('#selectUser').change(function(){
			   var begin_time = $('#begin_time').val();
			   var end_time = $('#end_time').val();
			   var text = $('#text').val();
			   var setid = $('#setid').val();
			  // var cid = $(this).val();
			   location.href="<?php echo $this->createUrl('statements/ceshiproductReport' , array('companyId'=>$this->companyId));?>/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text+"/setid/"+setid;
			});
			$('#cx').click(function(){  
				   // var obj = document.getElementById('accept');
				    var obj=$('.checkedCN');
				    
				    var str=new Array();
						obj.each(function(){
							if($(this).attr("checked")=="checked")
							{
								//$(this).parent().parent().remove();
								str += $(this).val()+","
								//alert($(this).val());
								//var cked=$(this).val();
							}								
						});
					str = str.substr(0,str.length-1);//除去最后一个“，”
					//alert(str);
						  var begin_time = $('#begin_time').val();
						   var end_time = $('#end_time').val();
						   var text = $('#text').val();
						   var cid = $(this).val();
						   var setid = $('#setid').val();
						   
						 location.href="<?php echo $this->createUrl('statements/ceshiproductReport' , array('companyId'=>$this->companyId ));?>/str/"+str+"/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text+"/setid/"+setid;
						  
					//alert($('#cked').val());
//	  			    if(obj.checked) {
//	 			      alert(obj.value);
//	  			    } else {
//	  			      alert('未选中');
//	  			    }
				  });
			  $('#excel').click(function excel(){

				   var ordertype = $('#ordertype').val();
				   var begin_time = $('#begin_time').val();
				   var end_time = $('#end_time').val();
				   var text = $('#text').val();
				   var setid = $('#setid').val();
				   //alert(str);
			       if(confirm('确认导出并且下载Excel文件吗？')){
							//alert("<?php echo "然而你并没有权限！！！";?>");
							//return false;
			    	   location.href="<?php echo $this->createUrl('statements/ceshiproductReportExport' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text+"/ordertype/"+ordertype+"/setid/"+setid;
			       }
			       else{
			    	  // location.href="<?php echo $this->createUrl('statements/export' , array('companyId'=>$this->companyId ));?>/str/"+str+"/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text;
			       }
			      
			   });

</script> 
