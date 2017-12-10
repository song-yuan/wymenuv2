<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/PCASClass.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/address.js');?>
<style>
.radio-inline div{padding-top:0!important;}
.selectedclass{
	font-size: 14px;
	color: #333333;
	border-radius: 4px;
    padding: 3px 0px;
    outline: none !important;
    -webkit-box-shadow: none !important;
    -moz-box-shadow: none !important;
    box-shadow: none !important;
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
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','店铺管理'),'url'=>$this->createUrl('company/list' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','店铺价格体系设置'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('peisonggroup/index' , array('companyId' => $this->companyId,)))));?>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'price-group-id-form',
				//'action' => $this->createUrl('companyGroup/index' , array('companyId' => $this->companyId)),
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
	)); ?>
	<div class="col-md-12">
    <div class="tabbable tabbable-custom">

			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','店铺价格体系设置');?></div>
					<div class="actions">
						<div class="btn-group">
							<select id="province" name="province" class="selectedclass">
							</select>
							<select id="city" name="city" class="selectedclass">
							</select>
							<select id="area" name="area" class="selectedclass">
							</select>
		                </div>
						<div class="btn-group">
							<input type="search" name="cname" id="cname" value="<?php echo $cname; ?>" placeholder="店铺名,手机号,联系人" class="btn width2">
						</div>
						<div class="btn-group">
						    <span id="search" class="btn blue" ><i class="fa fa-pencial"></i><?php echo yii::t('app','查 询');?></span>
						</div>
						<div class="btn-group">
							<input type="submit"  class="btn yellow" value=<?php echo yii::t('app','批量保存');?> >
						</div>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<?php if($models) : ?>
						<thead>
							<tr>
								<th class="table-checkbox" ><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<?php if(Yii::app()->user->role < '5'): ?><th>ID</th><?php endif; ?>
								<th><?php echo yii::t('app','店铺名称');?></th>
								<th><?php echo yii::t('app','联系人');?></th>
								<th><?php echo yii::t('app','地址');?></th>
								<th><?php echo yii::t('app','价格分组设置');?></th>
								<th><?php echo yii::t('app','操作');?></th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model['lid'];?>" name="ids[]" /></td>
								<?php if(Yii::app()->user->role < '5'): ?><td><?php echo $model['dpid'];?></td><?php endif; ?>
								<td style="width:10%"><?php echo $model['company_name'];?></td>
								<td ><?php echo $model['contact_name'];?></td>
								<td ><?php echo $model['province'].' '.$model['city'].' '.$model['county_area'].' '.$model['address'];?></td>
								<td>
									<select name="<?php echo $model['dpid']; ?>" id="aa<?php echo $model['dpid']; ?>" class="btn" style="border:1px solid gray;padding:2px 3px;">
										<?php if (!$groups):?>
											<option value="">亲 , 您还没有添加价格分组,默认总部价格</option>
										<?php else:?>
											<option value="0" <?php if ($model['price_group_id']==0) {echo 'selected';} ?>>-默认(总部)-</option>
											<?php foreach($groups as $group ): ?>
												<option value="<?php echo $group['lid']; ?>" <?php if ($group['lid']==$model['price_group_id']) {echo 'selected';} ?>>-<?php echo $group['group_name']; ?>-</option>
											<?php endforeach; ?>
										<?php endif;?>
									</select>
								</td>
								<td>
									<div class="row" style="padding-left:10px;">
	                                    <input type="button" class="btn green saved " valued="<?php echo $model['dpid']; ?>" value="<?php echo yii::t('app','保存');?>">
									</div>
								</td>
							</tr>
						<?php endforeach;?>
						<?php else: ?>
							<tr><td>亲,请先添加下级店铺....</td></tr>
						<?php endif;?>
						</tbody>
					</table>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
        	</div>


		</div>
		<?php $this->endWidget(); ?>
	</div>
	<script type="text/javascript">
		addressInit('province', 'city', 'area', '<?php echo $province;?>', '<?php echo $city;?>', '<?php echo $area;?>');
		$(document).keydown(function(event){
		  switch(event.keyCode){
		     case 13:return false; 
		     }
		});
		$('#province').change(function(){
			changeselect();
		});
		$('#city').change(function(){
			changeselect();
		});
		$('#area').change(function(){
			changeselect();
		});
		function changeselect(){
			var province = $('#province').children('option:selected').val();
			var city = $('#city').children('option:selected').val();
			var area = $('#area').children('option:selected').val();
			location.href="<?php echo $this->createUrl('companyGroup/peisindex' , array('companyId'=>$this->companyId));?>/province/"+province+"/city/"+city+"/area/"+area;
		}
		$('.saved').on('click',function(){
			var dpid =$(this).attr('valued');
			var ss = $('#aa'+dpid ).find('option:selected').attr('value');
			var arr = new Array;
			arr[dpid] = ss;
			var arrs = dpid+':'+ss;
			$.ajax({
	            type:'get',
				url:"<?php echo $this->createUrl('companyGroup/peistore',array('companyId'=>$this->companyId,));?>/arr/"+arrs,
				async: false,
	            cache:false,
	            dataType:'json',
				success:function(msg){
					location.reload();
				},
				error:function(msg){
					location.reload();
				},
			});
		});
		$('#search').click(function(event) {
			/* Act on the event */
			var cname = $('#cname').val();
			location.href="<?php echo $this->createUrl('companyGroup/peisindex' , array('companyId'=>$this->companyId));?>/cname/"+cname;
		});
		document.onkeydown=function(event){
	        var e = event || window.event || arguments.callee.caller.arguments[0];
	        if(e && e.keyCode==13){ // enter键
	        //要做的事情
			var cname = $('#cname').val();
			location.href="<?php echo $this->createUrl('companyGroup/peisindex' , array('companyId'=>$this->companyId));?>/cname/"+cname;
	        }
	    };
	</script>
	<!-- END PAGE CONTENT-->
