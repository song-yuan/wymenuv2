<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-1.7.1.min.js');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/mobiscroll_002.css');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/mobiscroll.css');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/mobiscroll_003.css');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/mobiscroll_002.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/mall/date/mobiscroll_004.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/mobiscroll.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/waiter/mobiscroll_003.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/mall/date/mobiscroll_005.js');?> 
    <!-- BEGIN PAGE -->
    <div class="page-content">
	<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               

	<div id="main2" style="width: 600px;height: 400px;display: none;" onMouseOver="this.style.background='#fff'" onmouseout="this.style.background=''"></div>
	
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','报表中心'),'url'=>$this->createUrl('statementstock/list' , array('companyId'=>$this->companyId,'type'=>1,))),array('word'=>yii::t('app','进销存月报 '),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statementstock/list' , array('companyId' => $this->companyId,'type'=>1,)))));?>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','进销存月报');?></div>
					<div class="actions">
						<select id="text" class="btn yellow" >
						<option value="3" <?php if ($text==2){?> selected="selected" <?php }?> ><?php echo yii::t('app','月报');?></option>
						</select>
						<div class="btn-group">
							 <input type="text" class="form-control" name="codename" id="codename" placeholder="<?php echo yii::t('app','原料编号');?>" value="<?php echo $codename;?>" > 
						</div>
						<div class="btn-group">
							 <input type="text" class="form-control" name="matename" id="matename" placeholder="<?php echo yii::t('app','原料名称');?>" value="<?php echo $matename;?>" > 
						</div>
						<div class="btn-group">
							   <div class="input-group  date-picker input-daterange" >
									<input type="text" class="form-control" name="begtime" id="begin_time" placeholder="<?php echo yii::t('app','起始时间');?>" value="<?php echo $begin_time; ?>">  
							  </div>  
						</div>	
						<div class="btn-group">
								<?php echo CHtml::dropDownList('selectCategory', $categoryId, $categories , array('class'=>'form-control'));?>
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
                                <th><?php echo yii::t('app','销售单位');?></th>
                                <th><?php echo yii::t('app','上月库存');?></th>
                                <th><?php echo yii::t('app','入库总量');?></th>
								<th><?php echo yii::t('app','进货总量');?></th>
								<th><?php echo yii::t('app','进货成本');?></th>
								<th><?php echo yii::t('app','配送总量');?></th>
								<th><?php echo yii::t('app','调拨总量');?></th>
								<th><?php echo yii::t('app','损耗总量');?></th>
								<th><?php echo yii::t('app','损耗成本');?></th>
								<th><?php echo yii::t('app','销售总出库');?></th>
								<th><?php echo yii::t('app','销售总成本');?></th>
								<th><?php echo yii::t('app','总消耗量');?></th>
								<th><?php echo yii::t('app','本月库存');?></th>
								<th><?php echo yii::t('app','盘点库存');?></th>
								<th><?php echo yii::t('app','损溢总量');?></th>
								<th><?php echo yii::t('app','损溢成本');?></th>
							</tr>
						</thead>
						<tbody>
							<?php if( $sqlmodels) :?>
							<!--foreach-->
							<?php $a=1;?>
							<?php foreach ($sqlmodels as $model):?>
							<?php $laststock = 0;
								$allstoragestock = 0;
								$takingstock = 0;
								$salestock = 0;
							?>
								<tr class="odd gradeX">
								<td><?php if($text==1){echo $model['y_all'];}elseif($text==2){ echo $model['y_all'].-$model['m_all'];}else{echo $model['y_all'].-$model['m_all'].-$model['d_all'];}?></td>
								<td><?php echo $model['material_identifier'];?></td>
								<td><?php echo $model['material_name'];?></td>
								<td><?php echo $model['unit_name'];?></td>
								<td><?php $laststock = $model['lms_takingstock']; //上次库存
											echo $laststock;?></td>
								<td><?php $allstoragestock = $model['all_storagestock'];//总入库量
											echo $allstoragestock;?></td>
								<td><?php  $takingstock = $model['mms_takingstock'];//盘点库存
											$allsunyi = $model['all_sunyinum']; //损益量
											$allsunyiprice = $model['all_sunyi_price']; //损益成本
											echo $model['all_storagestock']; //总入库库存
								?></td>
								<td><?php echo $model['all_storageprice']; //进货成本?> </td>
								<td><?php $renum = $model['re_num'];
											if(!$renum){
												$renum = 1;
											}?></td>
								<td><?php echo '';?></td>
								<td><?php $demagestock = $model['all_demagestock']; //盘损量
											echo $demagestock;?></td>
								<td><?php $demageprice = $model['all_demageprice']; //盘损成本
											echo $demageprice;?></td>
								<td><?php $salestock = $model['all_salestock']; //销售总量
											echo $salestock;?></td>
								<td><?php $salesprice = $model['all_salesprice']; //销售总成本
											echo $salesprice;?></td>
								<td><?php $usestock = $demagestock+$salestock-$allsunyi; 
											echo $usestock;?></td>
								<!-- 总消耗 = 销售出库+损耗-损益 -->
								<td><?php echo $takingstock;?></td>
								<td><?php echo $takingstock;?></td>
								<td><?php //echo sprintf("%.2f",$allsunyi/$renum); 
										echo $model['all_sunyinum'];?></td>
								<td><?php echo sprintf("%.4f",$allsunyiprice/$renum);?></td>
							<?php $a++;?>
							<?php endforeach;?>	
							<!-- end foreach-->
							<?php else:?>
							<tr>
							<td colspan='15'>未查询到数据。</td>
							</tr>
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
jQuery(document).ready(function() {
	var currYear = (new Date()).getFullYear();
	var opt = {};
	opt.date = {preset : 'date'};
	opt.datetime = {preset : 'datetime'};
	opt.time = {preset : 'time'};
	opt.month = {preset : 'month'};
	opt.default = {
		theme : 'android-ics light', //皮肤样式
		display : 'modal', //显示方式
		mode : 'scroller', //日期选择模式
		dateWheels: 'yyyy-mm',
		dateFormat : 'yyyy-mm',
		//width : cHeight / 1.2,
		//height : cHeight / 1.6,
		width:100,
		height:40,
		circular:true,
		showScrollArrows:true,
		lang : 'zh',
		showNow : true,
		nowText : "现在",
		startYear : currYear-3 , //开始年份
		endYear : currYear,  //结束年份
		
	};
	
	var optDateTime = $.extend(opt['month'], opt['default']);
	$("#begin_time").mobiscroll(optDateTime).date(optDateTime);
  
	$('#btn_time_query').click(function time() {
		var begin_time = $('#begin_time').val();
		var end_time = $('#begin_time').val();
		var text = $('#text').val();
		var cid = $('#selectCategory').val();
		var codename = $('#codename').val();
		var matename = $('#matename').val();
		location.href="<?php echo $this->createUrl('statementstock/stockmonthReport' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/text/"+text+"/codename/"+codename+"/cid/"+cid+"/matename/"+matename;    
	});
	$('#excel').click(function excel(){
		layer.msg('此项功能暂未开放！！',{icon: 5});return false;
		var begin_time = $('#begin_time').val();
		var end_time = $('#begin_time').val();
		var text = $('#text').val();
		var cid = $('#selectCategory').val();
		var codename = $('#codename').val();
		var matename = $('#matename').val();
		if(confirm('确认导出并且下载Excel文件吗？')){
			location.href="<?php echo $this->createUrl('statementstock/stockmonthReport' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text+"/sex/"+sex+"/sub/"+sub;
		}
		else{
			location.href="<?php echo $this->createUrl('statementstock/stockmonthReport' , array('companyId'=>$this->companyId ));?>/str/"+str+"/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text+"/sex/"+sex+"/sub/"+sub;
		}
	});
})
</script> 
