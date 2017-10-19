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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','数据中心'),'subhead'=>yii::t('app','营业数据'),'breadcrumbs'=>array(array('word'=>yii::t('app','营业数据'),'url'=>$this->createUrl('statements/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','代金券使用情况报表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statements/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','代金券使用情况报表');?></div>
				<div class="actions">
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
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th><?php echo yii::t('app','序号');?></th>
								<!-- <th>
									<div class="btn-group">
										<button type="button" class="btn blue"><?php echo yii::t('app','请选择店铺');?></button>
										<button type="button" class="btn green dropdown-toggle" data-toggle="dropdown"><i class="fa fa-angle-down"></i></button>
										<div class="dropdown-menu hold-on-click dropdown-checkboxes" role="menu">
											
											
											<?php foreach($comName as $key=>$value):?>

											<label><input name="accept" id="cked" class="checkedCN" value="<?php echo $key;?>" type="checkbox"><?php echo $value;?></label>
											  
											<?php endforeach;?>
											
											 <button type="submit" id="cx" class="btn red" ><i class="fa fa-pencial"></i><?php echo yii::t('app','确定');?></button> 
												
										</div>
									</div>
								</th> -->
								<th><?php echo yii::t('app','类别');?></th>
                                <th><?php echo yii::t('app','数量');?></th>
                                <th><?php echo yii::t('app','占比');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if( $read) :?>
						<?php if( $receive) :?>
						<?php if( $used) :?>
							<tr class="odd gradeX">
								<td>1</td>
								<td><?php echo yii::t('app','发出数量');?></td>
								<td><?php echo $read['all_cupon']+$receive['all_cupon']+$used['all_cupon'];?></td>
								<td><?php if($read['all_cupon']+$receive['all_cupon']+$used['all_cupon']) echo yii::t('app','100%');else echo "0.00";?></td>
							</tr>
							<tr class="odd gradeX">
								<td>2</td>
								<td><?php echo yii::t('app','领取数量');?></td>
								<td><?php echo $receive['all_cupon']?$receive['all_cupon']:0;?></td>
								<td><?php if($receive['all_cupon']) echo sprintf("%.2f",$receive['all_cupon']*100/($read['all_cupon']+$receive['all_cupon']+$used['all_cupon']))."%";else echo "0.00";?></td>
							</tr>
							<tr class="odd gradeX">
								<td>3</td>
								<td><?php echo yii::t('app','使用数量');?></td>
								<td><?php echo $used['all_cupon']?$used['all_cupon']:0;?></td>
								<td><?php if($used['all_cupon'] )echo sprintf("%.2f",$used['all_cupon']*100/($read['all_cupon']+$receive['all_cupon']+$used['all_cupon']))."%";else echo "0.00";?></td>
							</tr>
						<!--<php else:?>
						<tr>
							<td colspan="3">没有查询到数据</td>
						</tr>-->
						<?php endif;?>
						<?php endif;?>
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
	            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
	            
           }
		});
		 
		       
		   $('#btn_time_query').click(function() {  
			   var begin_time = $('#begin_time').val();
			   var end_time = $('#end_time').val();
			  // var cid = $(this).val();
			   location.href="<?php echo $this->createUrl('statements/cuponReport' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time;
			  
	        });
		   $('#cx').click(function(){  
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
					   //var cid = $(this).val();
					   
					 location.href="<?php echo $this->createUrl('statements/cuponReport' , array('companyId'=>$this->companyId ));?>/str/"+str+"/begin_time/"+begin_time+"/end_time/"+end_time;
					  

			  });
			  $('#excel').click(function excel(){

				   
		    	   var begin_time = $('#begin_time').val();
				   var end_time = $('#end_time').val();
				  
				   //alert(str);
			       if(confirm('确认导出并且下载Excel文件吗？')){

			    	   location.href="<?php echo $this->createUrl('statements/cuponReportExport' , array('companyId'=>$this->companyId,'d'=>1));?>/begin_time/"+begin_time+"/end_time/"+end_time;
			       }
			       else{
			    	  // location.href="<?php echo $this->createUrl('statements/diningNum' , array('companyId'=>$this->companyId,'d'=>1));?>/str/"+str+"/begin_time/"+begin_time+"/end_time/"+end_time;
			       }
			      
			   });

</script> 
