<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl.'/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js';?>"></script>
<link href="<?php echo Yii::app()->request->baseUrl.'/plugins/bootstrap-timepicker/compiled/timepicker.css';?>" rel="stylesheet" type="text/css"/>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/PCASClass.js');?>
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
	<div class="modal fade" id="wx-timeset" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">微店营业时间设置</h4>
				</div>
				<div class="modal-body">
					<form action="#" class="form-horizontal">
						<div class="form-group">
							<label class="control-label col-md-4">堂食营业时间</label>
							<div class="col-md-5">
								<div class="input-group bootstrap-timepicker">                                       
									<input id="shop_time" type="text" class="form-control timepicker-24">
									<span class="input-group-btn">
									<button class="btn default" type="button"><i class="fa fa-clock-o"></i></button>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">堂食打烊时间</label>
							<div class="col-md-5">
								<div class="input-group bootstrap-timepicker">                                       
									<input id="closing_time" type="text" class="form-control timepicker-24">
									<span class="input-group-btn">
									<button class="btn default" type="button"><i class="fa fa-clock-o"></i></button>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">外卖营业时间</label>
							<div class="col-md-5">
								<div class="input-group bootstrap-timepicker">                                       
									<input id="wm_shop_time" type="text" class="form-control timepicker-24">
									<span class="input-group-btn">
									<button class="btn default" type="button"><i class="fa fa-clock-o"></i></button>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">外卖打烊时间</label>
							<div class="col-md-5">
								<div class="input-group bootstrap-timepicker">                                       
									<input id="wm_closing_time" type="text" class="form-control timepicker-24">
									<span class="input-group-btn">
									<button class="btn default" type="button"><i class="fa fa-clock-o"></i></button>
									</span>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn default" data-dismiss="modal">关闭</button>
					<button type="button" class="btn blue confirm-settime" dpid="0">确定</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<div id="main2" name="main2" style="min-width: 300px;min-height:200px;display:none;" onMouseOver="this.style.backgroundColor='rgba(255,222,212,1)'" onmouseout="this.style.backgroundColor=''">
		<div id="content"></div>
	</div>
	
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','店铺管理'),'url'=>$this->createUrl('companyset/list' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','微店列表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('companyset/list' , array('companyId' => $this->companyId,)))));?>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
            <?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'company-form',
				'method' => 'GET',
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','微店列表');?></div>
					<div class="actions">
					<div class="btn-group">
						<select id="province" name="province" class="selectedclass"></select>
						<select id="city" name="city" class="selectedclass"></select>
						<select id="area" name="area" class="selectedclass"></select>
						<select id="isopen" name="isopen" class="selectedclass">
							<option value="0" <?php if($isopen==0) echo 'selected';?>>全部</option>
							<option value="1" <?php if($isopen==1) echo 'selected';?>>开通</option>
							<option value="1" <?php if($isopen==2) echo 'selected';?>>堂食</option>
							<option value="1" <?php if($isopen==3) echo 'selected';?>>外卖</option>
							<option value="1" <?php if($isopen==4) echo 'selected';?>>全开</option>
							<option value="2" <?php if($isopen==5) echo 'selected';?>>未开通</option>
						</select>
                    </div>
                    <div class="btn-group">
	                	<button type="button" id="cityselect" class="btn green" ><i class="fa fa-repeat"></i> <?php echo yii::t('app','查询');?></button>
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
								<?php if(Yii::app()->user->role < '5'): ?><th>ID</th><?php endif; ?>
                                <th><?php echo yii::t('app','店铺名称');?></th>
								<th>logo</th>
								<th><?php echo yii::t('app','是否开通微店');?></th>
								<th><?php echo yii::t('app','营业状态');?></th>
								<th><?php echo yii::t('app','堂食营业时间');?></th>
								<th><?php echo yii::t('app','外卖营业时间');?></th>
								<th><?php echo yii::t('app','是否同步价格');?></th>
								<th><?php echo yii::t('app','是否锁定');?></th>
								<th><?php echo yii::t('app','微店类型');?></th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><?php if(Yii::app()->user->role >= User::POWER_ADMIN_VICE && Yii::app()->user->role <= User::ADMIN_AREA&&$model->type=="0"):?><?php else:?><input type="checkbox" class="checkboxes" value="<?php echo $model->dpid;?>" name="companyIds[]" /><?php endif;?></td>
								<?php if(Yii::app()->user->role < '5'): ?><td><?php echo $model->dpid;?></td><?php endif; ?>
                                <td><a ><?php echo $model->company_name;?></a></td>
								<td ><img width="100" src="<?php echo $model->logo;?>" /></td>
								<td >
								<?php if($model->property){
									switch ($model->property->is_rest){
										case 0: $info = '未开通微店'; echo yii::t('app','未开通');break;
										case 1: $info = '总部强制关闭'; echo yii::t('app','开通');break;
										case 2: $info = '店铺打烊了'; echo yii::t('app','开通');break;
										case 3: $info = '营业中...'; echo yii::t('app','开通');break;
										default: $info = '未开通微店'; echo yii::t('app','未知');break;
									}
								}else{$info=''; echo yii::t('app','未开通');}?></td>
								<td ><?php if($model->property){
									echo $model->property->rest_message?$model->property->rest_message:$info;
								};?></td>
								<td ><?php if($model->property){
									echo $model->property->shop_time.'-'.$model->property->closing_time;
								};?></td>
								<td ><?php if($model->property){
									echo $model->property->wm_shop_time.'-'.$model->property->wm_closing_time;
								};?></td>
								<td><?php if($model->property){if($model->property->is_copyprice) echo '已同步';else echo '未同步';}?></td>
								<td><?php if($model->property){if($model->property->is_lock) echo '已锁定';else echo '未锁定';}?></td>
								<td><?php if($model->property){switch($model->property->sale_type): case 1: echo '正常';break;case 2: echo '堂食';break;case 3: echo '外卖';break;default: echo '未知';break;endswitch;}?></td>
								<td class="center">
									<div class="actions">
									<?php if($model->type == 1):?>
                                        <?php if(Yii::app()->user->role < User::ADMIN_AREA) : ?>
                                        	<?php if($model->property):
                                        		if($model->property->is_rest == '0'):?>
                                        			<a class='btn green open-wxdpid' style="margin-top: 5px;" rest='2' dpid='<?php echo $model->dpid;?>'><?php echo yii::t('app','开通');?></a>
                                        		<?php elseif($model->property->is_rest == '1'):?>
                                        			<a class='btn green open-wxdpid' style="margin-top: 5px;" rest='3' dpid='<?php echo $model->dpid;?>'><?php echo yii::t('app','开店');?></a>
                                        		<?php elseif($model->property->is_rest == '2'):?>
                                        			<a class='btn green open-wxdpid' style="margin-top: 5px;" rest='3' dpid='<?php echo $model->dpid;?>'><?php echo yii::t('app','开店');?></a>
                                        		<?php elseif($model->property->is_rest == '3'):?>
                                        			<a class='btn red open-wxdpid' style="margin-top: 5px;" rest='2' dpid='<?php echo $model->dpid;?>'><?php echo yii::t('app','关店');?></a>
                                        			<a class='btn red open-wxdpid' style="margin-top: 5px;" rest='1' dpid='<?php echo $model->dpid;?>'><?php echo yii::t('app','强制关店');?></a>
                                        			<?php if($model->property->sale_type == '1'):?>
	                                        			<a class='btn green open-wxtype' style="margin-top: 5px;" rest='2' dpid='<?php echo $model->dpid;?>'><?php echo yii::t('app','开堂食');?></a>
	                                        			<a class='btn green open-wxtype' style="margin-top: 5px;" rest='3' dpid='<?php echo $model->dpid;?>'><?php echo yii::t('app','开外卖');?></a>
                                        			<?php elseif($model->property->sale_type == '2'):?>
                                        				<a class='btn green open-wxtype' style="margin-top: 5px;" rest='3' dpid='<?php echo $model->dpid;?>'><?php echo yii::t('app','开外卖');?></a>
                                        				<a class='btn green open-wxtype' style="margin-top: 5px;" rest='1' dpid='<?php echo $model->dpid;?>'><?php echo yii::t('app','全开');?></a>
                                        			<?php elseif($model->property->sale_type == '3'):?>
                                        				<a class='btn green open-wxtype' style="margin-top: 5px;" rest='2' dpid='<?php echo $model->dpid;?>'><?php echo yii::t('app','开堂食');?></a>
                                        				<a class='btn green open-wxtype' style="margin-top: 5px;" rest='1' dpid='<?php echo $model->dpid;?>'><?php echo yii::t('app','全开');?></a>
                                        			<?php endif;?>
                                        		<?php endif;?>
                                        	<?php else:?>
                                        		<a class='btn green open-wxdpid' style="margin-top: 5px;" rest='2' dpid='<?php echo $model->dpid;?>'><?php echo yii::t('app','开通');?></a>
                                        	<?php endif;?>
                                            <a class='btn green copy-price' style="margin-top: 5px;" rest='2' dpid='<?php echo $model->dpid;?>'><?php echo yii::t('app','同步价格');?></a>
                                            <a class='btn yellow dislock-price' style="margin-top: 5px;" rest='0' dpid='<?php echo $model->dpid;?>'><?php echo yii::t('app','解除锁定');?></a>
                                        <?php else: ?>
                                        	<?php if($model->property):
                                        		if($model->property->is_rest == '2'):?>
                                        			<a class='btn green open-wxdpid' style="margin-top: 5px;" rest='3' dpid='<?php echo $model->dpid;?>'><?php echo yii::t('app','开店');?></a>
                                        		<?php elseif($model->property->is_rest == '3'):?>
                                        			<a class='btn red open-wxdpid' style="margin-top: 5px;" rest='2' dpid='<?php echo $model->dpid;?>'><?php echo yii::t('app','关店');?></a>
                                        			<a class='btn green open-wxtype' style="margin-top: 5px;" rest='2' dpid='<?php echo $model->dpid;?>'><?php echo yii::t('app','开堂食');?></a>
                                        			<a class='btn green open-wxtype' style="margin-top: 5px;" rest='3' dpid='<?php echo $model->dpid;?>'><?php echo yii::t('app','开外卖');?></a>
                                        				<a class='btn green open-wxtype' style="margin-top: 5px;" rest='1' dpid='<?php echo $model->dpid;?>'><?php echo yii::t('app','全开');?></a>
                                        		<?php endif;?>
                                        	<?php else:?>
                                        		
                                        	<?php endif;?>
                                        <?php endif;?>
                                         <a  class='btn blue setAppid' style="margin-top: 5px;" dpid="<?php echo $model->dpid;?>" dpidname="<?php echo $model->company_name;?>" shop_time="<?php echo $model->property->shop_time;?>" closing_time="<?php echo $model->property->closing_time;?>" wmshop_time="<?php echo $model->property->wm_shop_time;?>" wmclosing_time="<?php echo $model->property->wm_closing_time;?>"><?php echo yii::t('app','设置营业时间');?></a>
                                    <?php endif;?>
                                    </div>	
								</td>
							</tr>
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
jQuery(document).ready(function() {
								
	new PCAS("province","city","area","<?php echo $province;?>","<?php echo $city;?>","<?php echo $area;?>");
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
	
	$('.timepicker-24').timepicker({
        autoclose: true,
        minuteStep: 1,
        showSeconds: false,
        showMeridian: false
    });
	$('#cityselect').on('click',function(){
		var province = $('#province').val();
		var city = $('#city').val();
		var area = $('#area').val();
		var isopen = $('#isopen').val();
		location.href = '<?php echo $this->createUrl('/admin/companyWx/index',array('companyId'=>$this->companyId));?>/province/'+province+'/city/'+city+'/area/'+area+'/isopen/'+isopen;
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
		var province = $('#province').val();
		var city = $('#city').val();
		var area = $('#area').val();
		var isopen = $('#isopen').val();
		location.href = '<?php echo $this->createUrl('/admin/companyWx/index',array('companyId'=>$this->companyId));?>/province/'+province+'/city/'+city+'/area/'+area+'/isopen/'+isopen;
	}

	$('.open-wxdpid').on('click',function(){
		var rest = $(this).attr('rest');
		var dpid = $(this).attr('dpid');
		
		var url = "<?php echo $this->createUrl('companyWx/store');?>/companyId/"+dpid+"/rest/"+rest;
		//alert(url);
		//return false;
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
                	location.reload();
                }else{
                	layer.msg('失败！！！');
                }
            },
            error: function(msg){
                layer.msg('网络错误！！！');
            }
        });
		});
	$('.open-wxtype').on('click',function(){
		var rest = $(this).attr('rest');
		var dpid = $(this).attr('dpid');
		
		var url = "<?php echo $this->createUrl('companyWx/typestore');?>/companyId/"+dpid+"/rest/"+rest;
		//alert(url);
		//return false;
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
                	location.reload();
                }else{
                	layer.msg('失败！！！');
                }
            },
            error: function(msg){
                layer.msg('网络错误！！！');
            }
        });
	});
	$('.setAppid').on('click',function(){
		var shop_time = $(this).attr('shop_time');
		var closing_time =  $(this).attr('closing_time');
		var wmshop_time = $(this).attr('wmshop_time');
		var wmclosing_time = $(this).attr('wmclosing_time');
		var dpid = $(this).attr('dpid');
		$('#shop_time').val(shop_time);
		$('#closing_time').val(closing_time);
		$('#wm_shop_time').val(wmshop_time);
		$('#wm_closing_time').val(wmclosing_time);
		$('.confirm-settime').attr('dpid',dpid);
		$('.modal').modal();
	});
	$('.confirm-settime').on('click',function(){
		var dpid = $(this).attr('dpid');
		var shop_time = $('#shop_time').val();
		var closing_time = $('#closing_time').val();
		var wmshop_time = $('#wm_shop_time').val();
		var wmclosing_time = $('#wm_closing_time').val();
		if(shop_time&&closing_time&&wmshop_time&&wmclosing_time){
			var url = "<?php echo $this->createUrl('companyWx/storetime');?>";
	        $.ajax({
	            url:url,
	            type:'GET',
	            data:{companyId:dpid,shop_time:shop_time,closing_time:closing_time,wm_shop_time:wmshop_time,wm_closing_time:wmclosing_time},
	            dataType: "json",
	            success:function(msg){
	                var data=msg;
	                if(data.status){
	                	layer.msg('成功！！！');
	   		        	location.reload();
	                }else{
	                	layer.msg('失败！！！');
	                }
	            },
	            error: function(msg){
	                layer.msg('网络错误！！！');
	            }
	        });
		}else{
			layer.msg('请完善信息！！！');}
	});

	$('.copy-price').on('click',function(){
		var dpid = $(this).attr('dpid');
		if(window.confirm("同步价格会锁定价格，店铺将无权限修改价格?")){
			var url = "<?php echo $this->createUrl('companyWx/copyprice');?>/companyId/"+dpid;
	        $.ajax({
	            url:url,
	            type:'GET',
	            async:false,
	            dataType: "json",
	            success:function(msg){
	                var data=msg;
	                if(data.status){
	                	layer.msg('成功！！！');
	                	location.reload();
	                }else{
	                	layer.msg('失败！！！');
	                }
	            },
	            error: function(msg){
	                layer.msg('网络错误！！！');
	            }
	        });
		}
	});

	$('.dislock-price').on('click',function(){
		var dpid = $(this).attr('dpid');
		if(window.confirm("确定取消锁定价格?")){
			var url = "<?php echo $this->createUrl('companyWx/dislockprice');?>/companyId/"+dpid;
	        $.ajax({
	            url:url,
	            type:'GET',
	            async:false,
	            dataType: "json",
	            success:function(msg){
	                var data=msg;
	                if(data.status){
	                	layer.msg('成功！！！');
	                	location.reload();
	                }else{
	                	layer.msg('失败！！！');
	                }
	            },
	            error: function(msg){
	                layer.msg('网络错误！！！');
	            }
	        });
		}
	});
});	
</script>