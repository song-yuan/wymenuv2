		
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
					<!--<ul class="nav nav-tabs">
						<li class="active"><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('/brand/member/index',array('cid'=>$this->companyId));?>'" data-toggle="tab">已关注会员</a></li>
						<li><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('/brand/member/unSubList',array('cid'=>$this->companyId));?>'" data-toggle="tab">未关注会员</a></li>
					</ul>-->
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
							.more-condition
                                                        {margin-bottom: 15px !important;}
							</style>							
                                                                 
										<div class="form-group more-condition" style="float:left;width:150px;disabled:true;display:<?php echo isset($more) && $more?'':'none';?>;">
                                                                                    <div class="input-group" style="width:95%;">
												<span class="input-group-addon">性别</span>
												<select class="form-control" name="findsex">
                                                                                                        <option value="%">全部</option>
													<option value="0">未知</option>
                                                                                                        <option value="1">男</option>
                                                                                                        <option value="2">女</option>
												</select>												
											</div>
										</div>
									
										<div class="form-group more-condition" style="float:left;width:200px;disabled:true;display:<?php echo isset($more) && $more?'':'none';?>;">
											<div class="input-group" style="width:95%;">
												<span class="input-group-addon">年龄</span>
												<input type="text" maxlength="2" class="form-control" name="agefrom" value="0"><span class="input-group-addon">~</span><input type="text" maxlength="2" class="form-control" name="ageto" value="100">
											</div>
										</div>									 
										<div class="form-group more-condition" style="float:left;width:280px;disabled:true;display:<?php echo isset($more) && $more?'':'none';?>;">
											<div class="input-group" style="width:95%;">
												<span class="input-group-addon">出生日期</span>
												<input type="text" maxlength="5" class="form-control" name="birthfrom" value="01-01"><span class="input-group-addon">~</span><input type="text" maxlength="5" class="form-control" name="birthto" value="12-31">
											</div>
										</div>
									 
										<div class="form-group more-condition" style="float:left;width:200px;disabled:true;display:<?php echo isset($more) && $more?'':'none';?>;">
											<div class="input-group" style="width:95%;">
												<span class="input-group-addon">会员等级</span>
												<select class="form-control" name="finduserlevel">
													<option value="0">--全体--</option>
													<?php if(!empty($userlevels)):?>
													<?php foreach($userlevels as
                                                                                                         $userlevel):?>
													<option value="<?php echo $userlevel->lid;?>" <?php if($userlevel->lid==$finduserlevel) echo 'selected';?>><?php echo $userlevel->level_name;?></option>
													<?php endforeach;?>
													<?php endif;?>
												</select>												
											</div>
										</div>
                                                                                <div class="form-group more-condition" style="float:left;width:200px;disabled:true;display:<?php echo isset($more) && $more?'':'none';?>;">
											<div class="input-group" style="width:95%;">
												<span class="input-group-addon">微信分组</span>
												<select class="form-control" name="findweixingroup">
													<option value="0">--全体--</option>
													<?php if(!empty($weixingroups)):?>
													<?php foreach($weixingroups as
                                                                                                         $weixingroup):?>
													<option value="<?php echo $weixingroup['id'];?>" <?php if($weixingroup['id']==$findweixingroup) echo 'selected';?>><?php echo $weixingroup['name'];?></option>
													<?php endforeach;?>
													<?php endif;?>
												</select>
												
												</div>
										</div>
                                                        
                                                                                <div class="form-group more-condition" style="float:left;width:200px;disabled:true;display:<?php echo isset($more) && $more?'':'none';?>;">
											<div class="input-group" style="width:95%;">
												<span class="input-group-addon">国家</span>
												<select class="form-control" name="findcountry">
													<option value="0">--全体--</option>
													<?php if(!empty($modelcountrys)):?>
													<?php foreach($modelcountrys as
                                                                                                         $key=>$modelcountry):?>
													<option value="<?php echo $modelcountry['country'];?>" <?php if($modelcountry['country']==$findcountry) echo 'selected';?>><?php echo $modelcountry['country'];?></option>
													<?php endforeach;?>
													<?php endif;?>
												</select>
												
												</div>
										</div>
                                                        
                                                                                <div class="form-group more-condition" style="float:left;width:200px;disabled:true;display:<?php echo isset($more) && $more?'':'none';?>;">
											<div class="input-group" style="width:95%;">
												<span class="input-group-addon">省份</span>
												<select class="form-control" name="findprovince">
													<option value="0">--全体--</option>
													<?php if(!empty($modelprovinces)):?>
													<?php foreach($modelprovinces as
                                                                                                         $key=>$modelprovince):?>
                                                                                                        <option country="<?php echo $findcountry; ?>"
                                                                                                            style="display:<?php if($modelprovince['country']==$findcountry){echo "";}else{ echo "none";} ?>"
                                                                                                                value="<?php echo $modelprovince['province'];?>" 
                                                                                                                <?php if($modelprovince['province']==$findprovince && $modelprovince['country']==$findcountry) echo 'selected';?>>
                                                                                                                    <?php echo $modelprovince['province'];?></option>
													<?php endforeach;?>
													<?php endif;?>
												</select>
												
												</div>
										</div>
                                                        
                                                                                <div class="form-group more-condition" style="float:left;width:200px;disabled:true;display:<?php echo isset($more) && $more?'':'none';?>;">
											<div class="input-group" style="width:95%;">
												<span class="input-group-addon">市区</span>
												<select class="form-control" name="findcity">
													<option value="0">--全体--</option>
													<?php if(!empty($modelcitys)):?>
													<?php foreach($modelcitys as
                                                                                                         $key=>$modelcity):?>
													<option country="<?php echo $findcountry; ?>" province="<?php echo $findprovince ?>"
                                                                                                            style="display:<?php if($modelcity['country']==$findcountry && $modelcity['province']==$findprovince){echo "";}else{ echo "none";} ?>"
                                                                                                            value="<?php echo $modelcity['city'];?>" 
                                                                                                                <?php if($modelcity['city']==$findcity && $modelcity['province']==$findprovince && $modelcity['country']==$findcountry) echo 'selected';?>>
                                                                                                                    <?php echo $modelcity['city'];?></option>
													<?php endforeach;?>
													<?php endif;?>
												</select>												
												</div>
										</div>
                                                                                <div class="form-group more-condition" style="float:left;width:350px;disabled:true;display:<?php echo isset($more) && $more?'':'none';?>;">
											<div class="input-group" style="width:95%;">
												<span class="input-group-addon">消费总额</span>
                                                                                                <input type="text" maxlength="10" class="form-control" name="consumetotalfrom" value="0"><span class="input-group-addon">~</span><input type="text" maxlength="10" class="form-control" name="consumetotalto" value="9999999999">
											</div>
										</div>
                                                                                <div class="form-group more-condition" style="float:left;width:350px;disabled:true;display:<?php echo isset($more) && $more?'':'none';?>;">
											<div class="input-group" style="width:95%;">
												<span class="input-group-addon">积分</span>
												<input type="text" maxlength="10" class="form-control" name="pointfrom" value="0"><span class="input-group-addon">~</span><input type="text" maxlength="10" class="form-control" name="pointto" value="9999999999">
											</div>
										</div>
                                                                                <div class="form-group more-condition" style="float:left;width:350px;disabled:true;display:<?php echo isset($more) && $more?'':'none';?>;">
											<div class="input-group" style="width:95%;">
												<span class="input-group-addon">余额</span>
												<input type="text" maxlength="10" class="form-control" name="remainfrom" value="0"><span class="input-group-addon">~</span><input type="text" maxlength="10" class="form-control" name="remainto" value="9999999999">
											</div>
										</div>
									
                                                                    <div style="clear:both;"></div>
                                                                    
                                                                    <div class="input-group" style="float:left;width:450px;margin-bottom:15px;">
                                                                        <span class="input-group-addon">会员卡号或电话号码</span><input type="text" name="id" class="form-control" style="width:200px;" value="<?php echo isset($id) && $id ?$id:'';?>"/>
                                                                        <button type="submit" class="btn green">
                                                                                查找 &nbsp; 
                                                                                <i class="m-icon-swapright m-icon-white"></i>
                                                                        </button>
                                                                    </div>                                                                    									
                                                                    
                                                                    <div style="text-align:center;display:inline;width:200px;float:left;margin-bottom:15px;">                                                                                    
                                                                            <?php if(isset($more) && $more):?>
                                                                                <a href="javascript:;"><span class="glyphicon glyphicon-chevron-up">收起</span></a>
                                                                                <?php else:?>
                                                                                <a href="javascript:;"><span class="glyphicon glyphicon-chevron-down">更多查找条件</span></a>
                                                                                <?php endif;?>
                                                                                <input type="hidden" name="more" id="more" value="<?php echo isset($more) && $more?1:0;?>"/>                                                                                    
                                                                    </div>
                                                                   
								
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
                                                                <a href="javascript:;" class="btn red">
									<i class="fa fa-pencil"></i> 手动群发
								</a>
							</div>
						</div>					
						<div class="portlet-body">
							<table class="table table-bordered table-hover">
								<thead>
									<tr>
                                                                                <th width="6%">卡号</th>
                                                                                <th width="10%">名称</th>
                                                                                <th width="6%">性别</th>
                                                                                <th width="10%">出生日期</th>
                                                                                <th width="6%">等级</th>
										<th width="8%">微信分组</th>
                                                                                <th width="12%">地区</th>
										<th width="8%">手机号</th>				
                                                                                <th width="10%"><a href="javascript:;" onclick="sort(1,<?php echo $sort?0:1;?>);">消费总额 <i class="fa <?php echo $order==1?($sort?'fa-chevron-circle-down':'fa-chevron-circle-up'):'fa-chevron-circle-down';?>"></i></a></th>
										<th width="10%"><a href="javascript:;" onclick="sort(2,<?php echo $sort?0:1;?>);">积分 <i class="fa <?php echo $order==2?($sort?'fa-chevron-circle-down':'fa-chevron-circle-up'):'fa-chevron-circle-down';?>"></i></a></th>
										<th width="10%"><a href="javascript:;" onclick="sort(3,<?php echo $sort?0:1;?>);">余额 <i class="fa <?php echo $order==3?($sort?'fa-chevron-circle-down':'fa-chevron-circle-up'):'fa-chevron-circle-down';?>"></i></a></th>
										<th width="6%">操作</th>
									</tr>
								</thead>
								<tbody>
									<?php if($models):?>
									<?php foreach($models as $model):?>
										<tr>
                                                                                    <td><?php echo substr($model['card_id'],-5,5);?></td>
                                                                                    <td><?php echo $model['user_name'];?></td>
                                                                                    <td><?php switch ($model['sex']){case 0:echo "未知"; break; case 1:echo "男";break; case 2:echo "女";};?></td>
                                                                                    <td><?php echo substr($model['user_birthday'],0,10);?></td>
                                                                                    <td><?php if(!empty($model->level)) echo $model->level->level_name;?></td>
                                                                                    <td><?php if(!empty($weixingroup[$model['weixin_group']])) {echo $weixingroup[$model['weixin_group']];} else {echo $model['weixin_group'];}?></td>
                                                                                    <td><?php echo $model['country'];?> <?php echo $model['province'];?> <?php echo $model['city'];?></td>											
                                                                                    <td><?php echo $model['mobile_num'];?></td>
                                                                                    <td><?php echo $model['consume_total_money'];?><a class="btn default btn-xs blue pointsnum" title="详细列表" data-id="<?php echo $model['lid'];?>" point="<?php echo $model['consume_total_money'];?>" href="javascript:;" style="float:right;"><i class="fa fa-edit"></i></a></td>
                                                                                    <td><?php echo $model['consume_point_history'];?><a class="btn default btn-xs blue pointsnum" title="详细列表" data-id="<?php echo $model['lid'];?>" point="<?php echo $model['consume_point_history'];?>" href="javascript:;" style="float:right;"><i class="fa fa-edit"></i></a></td>
                                                                                    <td><?php echo $model['remain_money'];?><a class="btn default btn-xs blue pointsnum" title="详细列表" data-id="<?php echo $model['lid'];?>" point="<?php echo $model['remain_money'];?>" href="javascript:;" style="float:right;"><i class="fa fa-edit"></i></a></td>
                                                                                    
                                                                                    <td class="button-column">
                                                                                        <a class="btn default btn-xs blue" title="详细" href="<?php echo $this->createUrl('/admin/weixin/memberdetail',array('cid'=>$this->companyId,'id'=>$model['lid']));?>"><i class="fa fa-search"></i>详细</a>
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
			oIndex = url.indexOf('/o/');
			if(oIndex >0){
				var reg = new RegExp("([\\w\\/\\.]*)\\/o\\/\\d+\\/s\\/\\d+","i");
                                alert(reg);
				url = url.replace(reg,"$1\/o\/"+o+"\/s\/"+s);
			} else {
				url += '/o/'+o+'/s/'+s;
			}
                        alert(url)
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
		   //App.init();
		   //checkSelect();
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