		<script type="text/javascript" src="metronic/plugins/select2/select2.min.js"></script>
		<script type="text/javascript" src="metronic/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script type="text/javascript" src="metronic/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js"></script>
		<link rel="stylesheet" type="text/css" href="metronic/plugins/select2/select2_metro.css" />
		
		<link href="metronic/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
		<script src="metronic/plugins/bootstrap-modal/js/bootstrap-modalmanager.js" type="text/javascript" ></script>
		<script src="metronic/plugins/bootstrap-modal/js/bootstrap-modal.js" type="text/javascript" ></script>
		<!-- END SIDEBAR -->
		<!-- BEGIN PAGE -->
		    <div class="page-content">
			<!-- BEGIN STYLE CUSTOMIZER -->
			<?php $this->beginContent('//layouts/admin/styleCustomizer');?>
			<?php $this->endContent();?>
			<!-- END BEGIN STYLE CUSTOMIZER -->            
			<!-- BEGIN PAGE HEADER-->
			<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>'会员列表','subhead'=>'会员列表','breadcrumbs'=>array(array('word'=>'会员管理','url'=>''),array('word'=>'会员列表','url'=>''),)));?>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12 col-sm-12">
					<ul class="nav nav-tabs">
						<li class="active"><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('/brand/member/index',array('cid'=>$this->companyId));?>'" data-toggle="tab">已关注会员</a></li>
						<li><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('/brand/member/unSubList',array('cid'=>$this->companyId));?>'" data-toggle="tab">未关注会员</a></li>
					</ul>
					<?php $form=$this->beginWidget('CActiveForm', array(
						'id'=>'Promote',
						'clientOptions'=>array(
							'validateOnSubmit'=>true,
						),
						'htmlOptions'=>array(
							'class'=>'form-inline pull-right'
						),
					)); ?>
					<div class="col-md-12">
						<div class="table-responsive">
							<style>
							#search-form tr,#search-form tr td{border:none !important;}
							</style>
							<table id="search-form" class="table">
								<tr>
									<td width="15%"><label class="control-label">按号码查找</label></td>
									<td width="35%">
									<div class="input-group">
									<span class="input-group-addon">会员卡号</span><input type="text" name="id" class="form-control input-medium" value="<?php echo isset($id) && $id ?$id:'';?>"/>
									</div>
									</td>
									<td width="15%">
									</td>
									<td width="35%">
									   <button type="submit" class="btn green">
											查找 &nbsp; 
											<i class="m-icon-swapright m-icon-white"></i>
										</button>
										<div style="text-align:center;display:inline;width:50%;float:right;">
											<?php if(isset($more) && $more):?>
											<a href="javascript:;"><span class="glyphicon glyphicon-chevron-up">收起</span></a>
											<?php else:?>
											<a href="javascript:;"><span class="glyphicon glyphicon-chevron-down">更多查找条件</span></a>
											<?php endif;?>
											<input type="hidden" name="more" id="more" value="<?php //echo isset($more) && $more?1:0;?>"/>
										</div>
									</td>
								</tr>
								<tr class="more-condition" style="display:<?php echo isset($more) && $more?'':'none';?>;">
									<td><label class="control-label">按来源门店查找</label></td>
									<td> 
										<div class="form-group" style="disabled:true;">
											<div class="input-group">
												<span class="input-group-addon">来源门店</span>
												<?php //if($objects):?>
												<select class="form-control" name="original_shop">
													<option value="">--请选择实体店--</option>
													<?php //foreach($objects as $region):?>
													<optgroup label="<?php //echo $region->region_name;?>">
													<?php //foreach($region->shop as $shop):?>
													<option value="1_<?php //echo $shop->shop_id;?>" <?php // if(isset($original_shop) && ('1_'.$shop->shop_id == $original_shop)) echo 'selected';?>><?php //echo $shop->shop_name;?></option>
													<?php //endforeach;?>
													</optgroup>
													<?php //endforeach;?>
												</select>
												<?php //endif;?>
												</div>
										</div>
									</td>
									<td><label class="control-label">按来源渠道查找</label></td>
									<td> 
										<div class="form-group" style="disabled:true;">
											<div class="input-group">
												<span class="input-group-addon">来源渠道</span>
												<select class="form-control" name="promote">
													<option value="">--请选择渠道--</option>
													<?php //if($promotes):?>
													<?php //foreach($promotes as
                                                                                                        // $promote):?>
													<option value="<?php //echo $promote['type'].'_'.$promote['promote_id'];?>" <?php //if(isset($promoteType) && ($promoteType == $promote['type'].'_'.$promote['promote_id'])) echo 'selected';?>><?php //echo $promote['channel_name'];?></option>
													<?php //endforeach;?>
													<?php //endif;?>
												</select>
												
												</div>
										</div>
									</td>
								</tr>
								<tr class="more-condition" style="display:<?php //echo isset($more) && $more?'':'none';?>;">
								  <td><label class="control-label">按来源活动查找</label></td>
									<td> 
										<div class="form-group" style="disabled:true;">
											<div class="input-group">
												<span class="input-group-addon">来源活动</span>
												<?php //if($activites):?>
												<select class="form-control" name="active">
													<option value="">--请选择活动--</option>
													<?php //foreach($activites as $activite):?>
													<option value="<?php// echo $activite['type'].'_'.$activite['id'];?>" <?php //if(isset($activeType) && ($activeType == $activite['type'].'_'.$activite['id'])) echo 'selected';?>><?php// echo $activite['title'];?></option>
													<?php// endforeach;?>
												</select>
												<?php //endif;?>
												</div>
										</div>
									</td>
									<td><label class="control-label">按关注时间查找</label></td>
									<td> 
										<div class="form-group">
											<div class="input-group date-picker input-daterange">
												<span class="input-group-addon">关注时间</span><input type="text" class="form-control input-small" name="from" value="<?php //echo isset($from)&&$from?$from:'';?>"><span class="input-group-addon">~</span><input type="text" class="form-control input-small" name="to" value="<?php //echo isset($to)&&$to?$to:'';?>">
											</div>
										</div>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet box purple">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-group"></i>会员列表</div>
							<div class="actions">
								<a href="javascript:;" class="btn blue" onclick="exportFile();">
									<i class="fa fa-pencil"></i> 导出Excel文件
								</a>							
							</div>
						</div>					
						<div class="portlet-body">
							<table class="table table-bordered table-hover">
								<thead>
									<tr>
                                                                                <th width="8%">卡号</th>
                                                                                <th width="10%">名称</th>
                                                                                <th width="4%">性别</th>
                                                                                <th width="10%">出生日期</th>
                                                                                <th width="6%">等级</th>
										<th width="8%">微信分组</th>
                                                                                <th width="10%">地区</th>
										<th width="8%">手机号</th>				
                                                                                <th width="10%"><a href="javascript:;" onclick="sort(1,<?php //echo $sort?0:1;?>);">消费额 <i class="fa <?php //echo $order==1?($sort?'fa-chevron-circle-down':'fa-chevron-circle-up'):'fa-chevron-circle-down';?>"></i></a></th>
										<th width="10%"><a href="javascript:;" onclick="sort(3,<?php //echo $sort?0:1;?>);">消费积分 <i class="fa <?php //echo $order==3?($sort?'fa-chevron-circle-down':'fa-chevron-circle-up'):'fa-chevron-circle-down';?>"></i></a></th>
										<th width="10%"><a href="javascript:;" onclick="sort(4,<?php //echo $sort?0:1;?>);">余额返现 <i class="fa <?php //echo $order==4?($sort?'fa-chevron-circle-down':'fa-chevron-circle-up'):'fa-chevron-circle-down';?>"></i></a></th>
										<th width="6%">操作</th>
									</tr>
								</thead>
								<tbody>
									<?php if($models):?>
									<?php foreach($models as $model):?>
										<tr>
                                                                                    <td><?php echo substr($model['card_id'],5);?></td>
                                                                                    <td><?php echo $model['user_name'];?></td>
                                                                                    <td><?php echo $model['sex'];?></td>
                                                                                    <td><?php echo $model['user_birthday'];?></td>
                                                                                        <td><?php echo $model['user_level_lid'];?></td>
											<td><?php echo $model['weixin_group'];?></td>
                                                                                        <td><?php echo $model['country'];?><br><?php echo $model['province'];?><br><?php echo $model['city'];?></td>											
											<td><?php echo $model['mobile_num'];?></td>
											<td><?php ?></td>											
											<td><?php //echo $model['rest_consume_point'];?><a class="btn default btn-xs blue points-edit" title="编辑" data-id="<?php echo $model['lid'];?>" point="<?php //echo $model['rest_consume_point'];?>" href="javascript:;" style="float:right;"><i class="fa fa-edit"></i></a></td>
											<td><?php //echo $model['rest_activity_point'];?></td>
											<td class="button-column">
												<a class="btn default btn-xs blue" title="查看" href="<?php echo $this->createUrl('/brand/member/view',array('cid'=>$this->companyId,'id'=>$model['lid']));?>"><i class="fa fa-search"></i> 查 看</a>
											</td>
										</tr>
									<?php endforeach;?>	
									<?php else:?>
									<tr>
										<td colspan="10">没有找到数据</td>
									</tr>
									<?php endif;?>
								</tbody>
							</table>
							<?php if($pages->getItemCount()):?>
							<div class="row">
								<div class="col-md-5 col-sm-12">
									<div class="dataTables_info">
										共 <?php echo $pages->getPageCount();?> 页  , <?php echo $pages->getItemCount();?> 条数据 , 当前是第 <?php echo $pages->getCurrentPage()+1;?> 页
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
						<div>
						<?php endif;?>
					</div>
					<?php $this->endWidget(); ?>
					<!-- END EXAMPLE TABLE PORTLET-->
				</div>
			</div>
			<!-- END PAGE CONTENT-->
		</div>
		<!-- END PAGE -->
		<div id="responsive" class="modal fade" tabindex="-1" aria-hidden="true">
			<div id="ajax-modal" class="modal fade" tabindex="-1"  style="width:600px;">
			</div>
			<div class="modal-dialog">
				<div class="modal-content">

				</div>
			</div>
		</div>
	<script>
	    function checkSelect(){
	    	var promote = $('select[name="promote"]');
	    	var active = $('select[name="active"]');
	    	if(promote.val()!=""){
	       	  	active.attr('disabled','disabled');
	    	}
	       	if(active.val()!=""){
	       	  	promote.attr('disabled','disabled');
	    	}
	    }
		function sort(o,s){
			var url = $('#Promote').attr('action');
			oIndex = url.indexOf('&o=');
			if(oIndex >0){
				var reg = new RegExp("([\\w\\/\\.\\?]*)&o=\\d+&s=\\d+","i");
				url = url.replace(reg,"$1&o="+o+"&s="+s);
			} else {
				url += '&o='+o+'&s='+s;
			}
			$('#Promote').attr('action',url);
			$('#Promote').submit();
		}
		function exportFile(){
			var url = $('#Promote').attr('action');
			dIndex = url.indexOf('&d=1');
			if(dIndex <0){
				url += '&d=1';
			}
			url += $('#Promote').serialize();
			location.href=url;
		}
		jQuery(document).ready(function() {       
		   App.init();
		   checkSelect();
	       if (jQuery().datepicker) {
	           $('.date-picker').datepicker({
	           		format: 'yyyy-mm-dd',
	            	language: 'zh-CN',
	                rtl: App.isRTL(),
	                autoclose: true
	            });
	            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
	       }
		   $('#promote_btn li').click(function(){
		   		var str = $(this).find('a').html() + '<i class="fa fa-angle-down"></i>';
		   		var type = $(this).attr('originaltype');
		   		$('#original_type').val(type);
		   		$('#promote_btn_container').html(str);
		   });
	       $(".glyphicon").click(function(){
	       		if($(this).hasClass('glyphicon-chevron-down')){
				    $(this).removeClass('glyphicon-chevron-down').addClass("glyphicon-chevron-up");
				    $(this).html('收起');
				    $('.more-condition').show();
					$('#more').val(1);
	       		} else {
				    $(this).removeClass('glyphicon-chevron-up').addClass("glyphicon-chevron-down");
				    $(this).html('更多查找条件');
				    $('.more-condition').hide();
				    $('#more').val(0);
	       		}
	       });
	        $('select[name="original_shop"]').change(function(){
	        	$('#Promote').submit();
	        });
	       $('select[name="promote"]').change(function(){
	       	  if($(this).val()!=""){
	       	  	$('select[name="active"]').attr('disabled','disabled');
	       	  }else{
	       	  	$('select[name="active"]').removeAttr('disabled');
	       	  }
	       	  $('#Promote').submit();
	       });
	        $('select[name="active"]').change(function(){
	       	  if($(this).val()!=""){
	       	  	$('select[name="promote"]').attr('disabled','disabled');
	       	  }else{
	       	  	$('select[name="promote"]').removeAttr('disabled');
	       	  }
	       	   $('#Promote').submit();
	       });
	       var $modal = $('#ajax-modal');
	         $('.points-edit').on('click',function(){
	         	var id = $(this).attr('data-id');
	        	var point = $(this).attr('point');
	        	 $modal.load('<?php echo $this->createUrl('/brand/member/updateConsumePoint',array('cid'=>$this->companyId));?>&id='+id+'&point='+point, '', function(){
                   $modal.modal();
                 });
	        });
		});
	</script>	