<?php //Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/PCASClass.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/address.js');?>
<style>
.selectedclass{
	font-size: 14px;
	color: #333333;
	height: 34px;
	line-height: 34px;
	padding: 6px 12px;
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
	<div id="main2" name="main2" style="min-width: 500px;min-height:300px;display:none;" onMouseOver="this.style.backgroundColor='rgba(255,222,212,1)'" onmouseout="this.style.backgroundColor=''">
		<div id="content"></div>
	</div>

	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','店铺管理'),'url'=>$this->createUrl('companyset/list' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','店铺列表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('companyset/list' , array('companyId' => $this->companyId,)))));?>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
            <?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'company-form',
				//'action' => $this->createUrl('company/delete', array('companyId' => $this->companyId)),
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
		)); ?>
		<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','店铺列表');?></div>
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
						<input type="text" class="form-control" name="ccontent" id="ccontent" placeholder='手机号, 联系人, 店铺名' >
					</div>
                    <div class="btn-group">
	                	<button type="button" id="serch" class="btn green" ><i class="fa fa-repeat"></i> <?php echo yii::t('app','查询');?></button>
	                	<?php if(Yii::app()->user->role<='1'):?>
	                	<button type="button" id="posfee" class="btn red" ><i class="fa fa-home"></i> <?php echo yii::t('app','生成POS收费列表');?></button>
	                	<?php endif;?>
	                </div>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
				<div class="dataTables_wrapper form-inline">
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th>店铺序号</th>
                                <th><?php echo yii::t('app','店铺名称');?></th>
								<th><?php echo yii::t('app','联系人');?></th>
								<th><?php echo yii::t('app','序列号');?></th>
								<th><?php echo yii::t('app','使用时间');?></th>
								<th><?php echo yii::t('app','到期时间');?></th>
								<th><?php echo yii::t('app','操作');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<?php foreach ($model->posfee as $pf):?>
							<tr class="odd gradeX">
								<td><?php if(Yii::app()->user->role >= User::POWER_ADMIN_VICE && Yii::app()->user->role <= User::ADMIN_AREA&&$model->type=="0"):?><?php else:?><input type="checkbox" class="checkboxes" value="<?php echo $model->dpid;?>" name="companyIds[]" /><?php endif;?></td>
								<td><?php echo $model->dpid;?></td>
                                <td><?php echo $model->company_name;?></td>
								<td ><?php echo $model->contact_name;?></td>
								<td ><?php echo $pf->poscode;?></td>
								<td ><?php echo $pf->used_at;?></td>
								<td >
									<?php 
										if(strtotime($pf->exp_time)){
											$leaveday = ceil((strtotime($pf->exp_time) - time())/(24*60*60));
											if($leaveday > 30){
												echo $pf->exp_time.'(剩余:<span>'.$leaveday.'</span>天)';
											}else{
												echo $pf->exp_time.'(剩余:<span class="text-danger">'.$leaveday.'</span>天)';
											}
										}else{
											echo $pf->exp_time;
										}
									?>
								</td>
								<td><a  class='btn green setAppid' style="margin-top: 5px;" id="setAppid<?php echo $model->dpid;?>" dpid="<?php echo $model->dpid;?>" poscode="<?php echo $pf->poscode;?>" expt="<?php echo $pf->exp_time;?>"><?php echo yii::t('app','延期设置');?></a></td>
							</tr>
							<?php endforeach;?>
						<?php endforeach;?>
						<?php endif;?>
						</tbody>
					</table>
					</div>
						<?php if($pages->getItemCount()):?>
						<div class="row">
							<div class="col-md-5 col-sm-12">
								<div class="dataTables_info">
									<?php echo yii::t('app','共');?> <?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?>  , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> , <?php echo yii::t('app','当前是第');?> <?php echo $pages->getCurrentPage()+1;?> <?php echo yii::t('app','页');?>
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
            <?php $this->endWidget(); ?>
	</div>
	<!-- END PAGE CONTENT-->
<script>
	// new PCAS("province","city","area");
	// new PCAS("province","city","area","<?php echo $province;?>","<?php echo $city;?>","<?php echo $area;?>");
    addressInit('province', 'city', 'area', '<?php echo $province;?>', '<?php echo $city;?>', '<?php echo $area;?>');
	$(document).keydown(function(event){
	  switch(event.keyCode){
	     case 13:return false; 
	     }
	});


	function genQrcode(that){
		var id = $(that).attr('lid');
		var $parent = $(that).parent();
		$.get('<?php echo $this->createUrl('/admin/company/genWxQrcode');?>/dpid/'+id,function(data){
			if(data.status){
				$parent.find('img').remove();
				$parent.prepend('<img style="width:100px;" src="/wymenuv2/./'+data.qrcode+'">');
			}
			alert(data.msg);
		},'json');
	}
	$('#serch').on('click',function(){
		 var content = $('#ccontent').val();
		//alert(111);
         location.href="<?php echo $this->createUrl('posfee/setindex' , array('companyId'=>$this->companyId));?>/content/"+content;
	});
	document.onkeydown=function(event){
        var e = event || window.event || arguments.callee.caller.arguments[0];
        if(e && e.keyCode==13){ // enter 键
             //要做的事情
		var content = $('#ccontent').val();
		//alert(111);
        location.href="<?php echo $this->createUrl('posfee/setindex' , array('companyId'=>$this->companyId));?>/content/"+content;
		
        }
    };

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

			location.href="<?php echo $this->createUrl('posfee/setindex' , array('companyId'=>$this->companyId));?>/province/"+province+"/city/"+city+"/area/"+area;

		 }
	$('.setAppid').on('click',function(){

		$('#content').html('');
		var dpid = $(this).attr('dpid');
		var code = $(this).attr('poscode');
		var expt = $(this).attr('expt');
		var content = '<div style="width: 88%;margin-left: 6%;padding-top: 10px;"><input id="poscode" disabled placeholder="appid" value="'+code+'"/></div>'
					+ '<div style="width: 88%;margin-left: 6%;padding-top: 10px;"><select id="years"><option value="0">--请选择需要延长的年限--</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="5">5</option><option value="30">永久</option></select></div>'
					+ '<div style="width: 88%;margin-left: 6%;padding-top: 10px;"><input id="month" placeholder="输入要延长的月份"/>月</div>'
					+ '<div style="width: 88%;margin-left: 6%;padding-top: 10px;"><select id="status"><option value="0">正常</option><option value="1">POS端不可用</option><option value="2">线上不可用</option><option value="3">都不可用</option></select></div>'
					+ '<div style="width: 88%;margin-left: 6%;padding-top: 10px;"><button id="appid_store" class="btn green">确认</button></div>'
					;
		$('#content').html(content);
		//alert(dpid);
		layer_zhexiantu=layer.open({
		     type: 1,
		     //shift:5,
		     shade: [0.5,'#fff'],
		     //move:'#main2',
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
		$('#appid_store').on('click',function(){
			var years = $('#years').val();
			var month = $('#month').val();
			var status = $('#status').val();
			//alert(appid);
			
			if(years == '0' && month == ''){
				layer.msg('请完善信息！！！');
				return false;
			}
			if(month==''){
				month=0;
			}
			//layer.msg(years);return false;
			var url = "<?php echo $this->createUrl('posfee/postore');?>/companyId/"+dpid+"/poscode/"+code+"/years/"+years+"/month/"+month+"/expt/"+expt+"/status/"+status;
	        $.ajax({
	            url:url,
	            type:'GET',
	            //data:orderid,//CF
	            async:false,
	            dataType: "json",
	            success:function(msg){
	                var data=msg;
	                if(data.status){
	                	layer.msg('成功！！！');
	                	layer.close(layer_zhexiantu);
	   		        	layer_zhexiantu=0;
	                }else{
	                	layer.msg('失败！！！');
	                }
	            },
	            error: function(msg){
	                layer.msg('网络错误！！！');
	            }
	        });
		});
	});

	$('#posfee').on('click',function(){
		var url = "<?php echo $this->createUrl('posfee/store');?>";
			
		$.ajax({
            url:url,
            type:'GET',
            //data:orderid,//CF
            dataType: "json",
            success:function(msg){
                var data=msg;
                if(data.status){
                	layer.msg('成功！！！');
                }else{
                	layer.msg('失败！！！');
                }
            },
            error: function(msg){
                layer.msg('网络错误！！！');
            }
        });
	});
</script>