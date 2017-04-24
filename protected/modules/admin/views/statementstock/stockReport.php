    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>"></script>
    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/echarts.min.js');?>"></script>
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
	<div id="main2" style="width: 600px;height: 400px;display: none;" onMouseOver="this.style.background='#fff'" onmouseout="this.style.background=''"></div>
	
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','报表中心'),'url'=>$this->createUrl('statementstock/list' , array('companyId'=>$this->companyId,'type'=>1,))),array('word'=>yii::t('app','进销存损'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statementstock/list' , array('companyId' => $this->companyId,'type'=>1,)))));?>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','进销存损报表');?></div>
				<div class="actions">
					<select id="text" class="btn yellow" >
					<option value="1" <?php if ($text==1){?> selected="selected" <?php }?> ><?php echo yii::t('app','年');?></option>
					<option value="2" <?php if ($text==2){?> selected="selected" <?php }?> ><?php echo yii::t('app','月');?></option>
					<option value="3" <?php if ($text==3){?> selected="selected" <?php }?> ><?php echo yii::t('app','日');?></option>
					</select>
					
					<select id="sex" class="btn green" >
					<option value="-1" <?php if ($sex==-1){?> selected="selected" <?php }?> ><?php echo yii::t('app','所有');?></option>
					<option value="0" <?php if ($sex==0){?> selected="selected" <?php }?> ><?php echo yii::t('app','未知');?></option>
					<option value="1" <?php if ($sex==1){?> selected="selected" <?php }?> ><?php echo yii::t('app','男');?></option>
					<option value="2" <?php if ($sex==2){?> selected="selected" <?php }?> ><?php echo yii::t('app','女');?></option>
					</select>
					<select id="sub" class="btn " >
					<option value="-1" <?php if ($sub==-1){?> selected="selected" <?php }?> ><?php echo yii::t('app','所有');?></option>
					<option value="0" <?php if ($sub==0){?> selected="selected" <?php }?> ><?php echo yii::t('app','关注');?></option>
					<option value="1" <?php if ($sub==1){?> selected="selected" <?php }?> ><?php echo yii::t('app','取消关注');?></option>
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
			<div style="overflow-x: auto;" class="portlet-body" class="portlet-body" >
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th><?php echo yii::t('app','时间');?></th>
								<th><?php echo yii::t('app','原料编码');?></th>
								<th><?php echo yii::t('app','名称');?></th>                                                              
                                <th><?php echo yii::t('app','库存单位');?></th>
                                <th><?php echo yii::t('app','上期库存');?></th>
                                <th><?php echo yii::t('app','入库量');?></th>
								<th><?php echo yii::t('app','进货量');?></th>
								<th><?php echo yii::t('app','配送量');?></th>
								<th><?php echo yii::t('app','调拨量');?></th>
								<th><?php echo yii::t('app','损耗量');?></th>
								<th><?php echo yii::t('app','销售出库');?></th>
								<th><?php echo yii::t('app','总消耗量');?></th>
								<th><?php echo yii::t('app','本期库存');?></th>
								<th><?php echo yii::t('app','盘点库存');?></th>
								<th><?php echo yii::t('app','损溢量');?></th>
								
							</tr>
						</thead>
						<tbody>
							<?php if( $sqlmodels) :?>
							<!--foreach-->
							<?php $a=1;?>
							<?php foreach ($sqlmodels as $model):?>
								<tr class="odd gradeX">
								<td><?php if($text==1){echo $model['y_all'];}elseif($text==2){ echo $model['y_all'].-$model['m_all'];}else{echo $model['y_all'].-$model['m_all'].-$model['d_all'];}?></td>
								<td><?php echo $model['material_identifier'];?></td>
								<td><?php echo $model['material_name'];?></td>
								<td><?php echo $model['unit_name'];?></td>
								<td><?php echo $model['md'];?></td>
								<td><?php echo $model['all_num'];?></td>
								<td></td>
							<?php $a++;?>
							<?php endforeach;?>	
							<!-- end foreach-->
							<?php endif;?>
						</tbody>
					</table>
					
				</div></div>
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
  
		   $('#btn_time_query').click(function time() {
			   var begin_time = $('#begin_time').val();
			   var end_time = $('#end_time').val();
			   var text = $('#text').val();
			   var sex = $('#sex').val();
			   var sub = $('#sub').val();
			   location.href="<?php echo $this->createUrl('statementstock/stockReport' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/text/"+text+"/sex/"+sex+"/sub/"+sub;    
	        });
		   $('#cx').click(function cx(){  
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
					  
					 location.href="<?php echo $this->createUrl('statementstock/stockReport' , array('companyId'=>$this->companyId ));?>/str/"+str+"/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text+"/sex/"+sex+"/sub/"+sub;	  
					 //return a; 
			 });

			  $('#excel').click(function excel(){
				   var begin_time = $('#begin_time').val();
				   var end_time = $('#end_time').val();
				   var text = $('#text').val();
				   var sex = $('#sex').val();
				   var sub = $('#sub').val();
			       if(confirm('确认导出并且下载Excel文件吗？')){
				       //layer.msg('暂未开放！');
			    	   location.href="<?php echo $this->createUrl('statementstock/wxmemberExport' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text+"/sex/"+sex+"/sub/"+sub;
			       }
			       else{
			    	   location.href="<?php echo $this->createUrl('statementstock/wxmemberReport' , array('companyId'=>$this->companyId ));?>/str/"+str+"/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text+"/sex/"+sex+"/sub/"+sub;
			       }
			      
			   });
</script> 
