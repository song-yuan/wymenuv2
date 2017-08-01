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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','数据中心'),'subhead'=>yii::t('app','营业数据'),'breadcrumbs'=>array(array('word'=>yii::t('app','营业数据'),'url'=>$this->createUrl('statements/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','营业收入（产品分类）报表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statements/list' , array('companyId' => $this->companyId,'type'=>0)))));?>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','营业收入报表（菜品分类）');?></div>
				<div class="actions">
					<select id="text" class="btn yellow" >
					<option value="1" <?php if ($text==1){?> selected="selected" <?php }?> ><?php echo yii::t('app','年');?></option>
					<option value="2" <?php if ($text==2){?> selected="selected" <?php }?> ><?php echo yii::t('app','月');?></option>
					<option value="3" <?php if ($text==3){?> selected="selected" <?php }?> ><?php echo yii::t('app','日');?></option>
					</select>
					
					<select id="setid" class="btn green" >
					<option value="1" <?php if ($setid==1){?> selected="selected" <?php }?> ><?php echo yii::t('app','综合');?></option>
					<option value="0" <?php if ($setid==0){?> selected="selected" <?php }?> ><?php echo yii::t('app','单品');?></option>
					<option value="2" <?php if ($setid==2){?> selected="selected" <?php }?> ><?php echo yii::t('app','套餐');?></option>
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
								
							<!-- 	<th>序号</th> -->
								<th><?php echo yii::t('app','时间');?></th>
								<th style="width:20%;">
									<div class="btn-group">
										<button type="button" class="btn blue"><?php echo yii::t('app','请选择店铺');?></button>
										<button type="button" class="btn green dropdown-toggle" data-toggle="dropdown"><i class="fa fa-angle-down"></i></button>
										<div class="dropdown-menu hold-on-click dropdown-checkboxes" role="menu">
											
											
											<?php foreach($comName as $key=>$value):?>

											<label><input name="accept" id="checkedCNid" class="checkedCN" value="<?php echo $key;?>" type="checkbox"><?php echo $value;?></label>
											  
											<?php endforeach;?>
											
											 <button type="submit" id="cx" class="btn red" ><i class="fa fa-pencial"></i><?php echo yii::t('app','确定');?></button> 
												
										</div>
									</div>
								</th>
                                <th><?php echo yii::t('app','产品类型');?></th> 
                                <th><?php echo yii::t('app','数量');?></th> 
                                <th><?php echo yii::t('app','金额统计(退款)');?></th>                                                               
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
								<td style="width:20%;"><?php echo $model['company_name']; ?></td>
								<td><?php if (!empty($model['category_name'])){echo $model['category_name'];}else{echo "基础费（餐位费等）";} ?></td>
								<td><?php echo $model['all_num'];?></td>
								<?php $retreatprice = $this->getRetreatPrice($begin_time,$end_time,$str,$text,$model['y_all'],$model['m_all'],$model['d_all'],$setid,$model['category_id']);?>
								<td><?php echo sprintf("%.3f",$model['all_price']-$retreatprice).'(-'.$retreatprice.')';?></td>
								<td></td>
								
								
								
							<?php $a++;?>
							<?php endforeach;?>	
							<!-- end foreach-->
							<?php endif;?>
						</tbody>
					</table>
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
	            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
	            
           }
		});
// 		  $('#explode1').click(function(){
// 			  exportincomeReport($models);
// 		  });
  
		   $('#btn_time_query').click(function time() {  
			  // alert($('#begin_time').val()); 
			  // alert($('#end_time').val()); 
			  // alert(111);
			   var begin_time = $('#begin_time').val();
			   var end_time = $('#end_time').val();
			   var text = $('#text').val();
			   var setid = $('#setid').val();
			  // var cid = $(this).val();
			   location.href="<?php echo $this->createUrl('statements/incomeReport' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/text/"+text+"/setid/"+setid    
			  
	        });
		   //a = new Array();
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
				 str = str.substr(0,str.length-1);//除去最后一个“，”
				//alert(str);
					  var begin_time = $('#begin_time').val();
					   var end_time = $('#end_time').val();
					   var text = $('#text').val();
					   var setid = $('#setid').val();
					   //var cid = $(this).val();
					  
					 location.href="<?php echo $this->createUrl('statements/incomeReport' , array('companyId'=>$this->companyId ));?>/str/"+str+"/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text+"/setid/"+setid;	  
					 //return a; 
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
				   var setid = $('#setid').val();
				   //alert(str);
			       if(confirm('确认导出并且下载Excel文件吗？')){
			    	   //location.href="<?php echo $this->createUrl('statements/incomeReport' , array('companyId'=>$this->companyId,'d'=>1 ));?>/str/"+str+"/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text;
				       
				    	   

			    	   location.href="<?php echo $this->createUrl('statements/incomeExport' , array('companyId'=>$this->companyId ));?>/str/"+str+"/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text+"/setid/"+setid;
			       }
			       else{
			    	  // location.href="<?php echo $this->createUrl('statements/export' , array('companyId'=>$this->companyId ));?>/str/"+str+"/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text;
			       }
			      
			   });
</script> 
