		
		<link rel="stylesheet" href="metronic/plugins/jquery-treegrid/css/jquery.treegrid.css">
		<script type="text/javascript" src="metronic/plugins/jquery-treegrid/js/jquery.treegrid.js"></script>
		<script src="metronic/plugins/bootbox/bootbox.min.js" type="text/javascript" ></script>
		<link href="metronic/css/pages/profile.css" rel="stylesheet" type="text/css" />
		<!-- BEGIN PAGE -->
		<style>
			span.tab{
				color: black;
				border-right:1px dashed white;
				margin-right:10px;
				padding-right:10px;
				display:inline-block;
			}
			span.tab-active{
				color:white;
			}
		</style> 
		<div class="page-content">
			<!-- BEGIN STYLE CUSTOMIZER -->
			<?php $this->beginContent('//layouts/admin/styleCustomizer');?>
			<?php $this->endContent();?>
			<!-- END BEGIN STYLE CUSTOMIZER -->            
			<!-- BEGIN PAGE HEADER-->
			<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','微信管理'),'subhead'=>yii::t('app','发布菜单'),'breadcrumbs'=>array(array('word'=>yii::t('app','微信管理'),'url'=>''),array('word'=>yii::t('app','发布菜单'),'url'=>''))));?>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row profile">
				<div class="col-md-12">
					<!--BEGIN TABS-->
					<div class="tabbable tabbable-custom tabbable-full-width">
							<!-- BEGIN PAGE CONTENT-->
							<div class="row">
								<div class="col-md-12">
									<div class="portlet purple box">
										<div class="portlet-title">
											<div class="caption"><i class="fa fa-cogs"></i><a href="<?php echo $this->createUrl('weixin/menu',array('companyId'=>$this->companyId));?>"><span class="tab"><?php echo yii::t('app','公众号设置');?></span></a><span class="tab tab-active"><?php echo yii::t('app','发布菜单');?></span></div>
										</div>
										<div class="portlet-body">
											<div class="table-responsive">
												<form id="Menu" action="<?php echo $this->createUrl('/admin/weixin/menu',array('companyId'=>$this->companyId));?>" method="post">
												<div class="modal-body">
												<a href="javascript:" class="btn btn-sm green" id="add_level1"><i class="fa fa-plus"></i> 添加一级菜单</a>
													<div class="form-group">
														<table class="table table-bordered table-hover">
														<tr><td width="5%">菜单名称</td><td width="10%">菜单跳转链接或事件名</td><td width="5%">菜单类型</td><td width="2%">操作</td></tr>
											            <?php $level =0;$maxnum = 0; if(!empty($menuList)):?>
											            <?php foreach($menuList as $menu):?>
											            <tbody class="menu_level menu_level1-<?php echo $level;?>">
											            <tr class="level1" horizontal="<?php echo $menu['horizontal'];?>" vertical="0">
											               <td width="5%"><input type="hidden" name="menu[<?php echo $maxnum;?>][h]" value="<?php echo $level;?>"/><input type="hidden" name="menu[<?php echo $maxnum;?>][v]" value="0"/><input type="text" class="form-control menu_name" name="menu[<?php echo $maxnum;?>][name]" value="<?php echo $menu['name'];?>" placeholder="一级菜单名"  maxlength="4" /></td>
											               <td width="10%"><input type="text" class="form-control menu_val" name="menu[<?php echo $maxnum;?>][value]" value="<?php echo $menu['value'];?>" placeholder="一级菜单链接" /></td>
											               <td width="4%"><select class="form-control type" name="menu[<?php echo $maxnum;?>][type]"><option <?php echo ($menu['type']==0)?"selected":"";?>  value="0">有子菜单</option><option <?php echo ($menu['type']==1)?"selected":"";?> value="1">跳转网址</option><!--<option <?php echo ($menu['type']==2)?"selected":"";?> value="2">事件推送</option>--></select></td>
											               <td width="2%"><a class="btn btn-xs green add_btn add_level2" level="<?php echo $level;?>"><i class="fa fa-plus"></i></a><?php if($menu['horizontal']!=0) echo '&nbsp;&nbsp;<a class="btn btn-xs red del_level1_btn" level="'.$level.'"><i class="fa fa-times"></i></a>'; ?></td></tr>
												            <?php $maxnum++;$level++; if(isset($menu['children'])):?>
												            <?php foreach($menu['children'] as $children):?>
												            <tr class="level2" horizontal="<?php echo $children['horizontal'];?>" vertical="<?php echo $children['vertical'];?>">
												              <td width="5%"><input class="h" type="hidden" name="menu[<?php echo $maxnum;?>][h]" value="<?php echo $children['horizontal'];?>"><input class="v" type="hidden" name="menu[<?php echo $maxnum;?>][v]" value="<?php echo $children['vertical'];?>"><input type="text" class="form-control menu_name" name="menu[<?php echo $maxnum;?>][name]" maxlength="7" value="<?php echo $children['name'];?>" placeholder="子菜单名"  maxlength="7" /></td>
												              <td width="10%"><input type="text" class="form-control" name="menu[<?php echo $maxnum;?>][value]" value="<?php echo $children['value'];?>" placeholder="子菜单链接" /></td>
												              <td width="4%"><select class="form-control type" name="menu[<?php echo $maxnum;?>][type]"><option <?php echo ($children['type']==1)?"selected":"";?> value="1">跳转网址</option><!--<option <?php echo ($children['type']==2)?"selected":"";?> value="2">事件推送</option>--></select></td><td width="2%"><a class="btn btn-xs red del_level2_btn" level="<?php echo $children['horizontal'];?>"><i class="fa fa-times"></i></a></td></tr>
												            <?php $maxnum++; endforeach;?>
												            <?php endif;?>
												         </tbody>
												         <?php $maxnum++; endforeach;?>
												        <?php else:?>
												        <tbody class="menu_level menu_level1-0">
											            <tr class="level1" horizontal="0" vertical="0">
											              <td width="5%"><input type="hidden" name="menu[0][h]" value="0"/><input type="hidden" name="menu[0][v]" value="0"/><input type="text" class="form-control menu_name" name="menu[0][name]" value="<?php echo isset($menuList[0]['name'])?$menuList[0]['name']:'';?>" placeholder="一级菜单名"  maxlength="4" /></td>
											              <td width="10%"><input type="text" class="form-control menu_val" name="menu[0][value]" value="<?php echo isset($menuList[0]['value'])?$menuList[0]['value']:'';?>" placeholder="一级菜单链接" /></td>
											              <td width="4%"><select class="form-control type" name="menu[0][type]"><option selected  value="0">有子菜单</option><option value="1">跳转网址</option></select></td>
											              <td width="2%"><a class="btn btn-xs green add_btn add_level2" level="0"><i class="fa fa-plus"></i></a></td></tr>
											            </tbody>
											            <?php endif;?>
											             <input id="maxnum" type="hidden" value="<?php echo $maxnum?$maxnum:1; ?>"/>
									                    </table>
													</div>
												</div>
												<div class="form-actions fluid">
													<div class="col-md-offset-3 col-md-9">
														<input type="submit"  class="btn green" id="create_btn" value="发布">
													</div>	
												</div>
												<div class="alert alert-warning">
													<strong>注意:</strong>创建自定义菜单后，需要24小时微信客户端才会展现出来。请慎重发布菜单!</br></br>
												</div>
												</form>
								            </div>
										</div>
									</div>
								</div>
							</div>
							<!-- END PAGE CONTENT-->
					</div>
					<!--END TABS-->
				</div>
			</div>
			<!-- END PAGE CONTENT-->
		</div>
		<!-- END PAGE -->  
		<div id="responsive" class="modal fade" tabindex="-1" aria-hidden="true">
			<div id="ajax-modal" class="modal fade" tabindex="-1">
			</div>
			<div class="modal-dialog">
				<div class="modal-content">

				</div>
			</div>
		</div>
			
			<script>
			jQuery(document).ready(function() {
				$('.level1').find('.type').each(function(){
					if($(this).val()==0){
						$(this).parent('td').prev().children('select').val(0);
					}
				});
				$('#Menu').submit(function(){
					var sub = true;
					$('.menu_name').each(function(){
						if($(this).val() == ''){
						alert('菜单名必须填写');
						sub = false;
					 }
					});
					return sub;
				});
				var i = $('#maxnum').val();
				$("#add_level1").live('click',function(){
					var len = $('.menu_level').length;
					if(len<3){
					  var str='';
					  str=str+'<tbody class=\"menu_level menu_level1-'+len+'\"><tr class=\"level1\" horizontal=\"'+len+'\" vertical=\"0\"><td width=\"5%\"><input class=\"h\" type=\"hidden\" name=\"menu['+i+'][h]\" value=\"'+len+'\"><input class=\"v\" type=\"hidden\" name=\"menu['+i+'][v]\" value=\"0\"><input type=\"text\" class=\"form-control menu_name\" name=\"menu['+i+'][name]\" value=\"\" placeholder=\"一级菜单名\"  maxlength="4" /></td><td width=\"10%\"><input type=\"text\" class=\"form-control menu_val\" name=\"menu['+i+'][value]\" value=\"\" placeholder=\"一级菜单链接\" /></td><td width=\"4%\"><select class=\"form-control type\" name=\"menu['+i+'][type]\"><option selected  value=\"0\">有子菜单</option><option value=\"1\">跳转网址</option></select></td><td width=\"2%\"><a class=\"btn btn-xs green add_level2\" level=\"'+len+'\"><i class=\"fa fa-plus\"></i></a>&nbsp;&nbsp;<a class=\"btn btn-xs red del_level1_btn\" level=\"'+len+'\"><i class=\"fa fa-times\"></i></a></td></tr></tbody>';
					  $('.table').append(str);
					  i++;
					}else{
						alert("一级菜单不能超过三个!");
					}	
				});
				$(".add_level2").live('click',function(){
					var vertical = $(this).attr('level');
					var type = $('.menu_level1-'+vertical).find('.type').val();
					if(type == 0){
						if( $('.menu_level1-'+vertical).find('.menu_val').val()!=""){
							alert("菜单链接必须为空"); return;
						}
						
						var num = $('.menu_level1-'+vertical).children('.level2').length;
						if(num < 5){
						   var str='<tr class=\"level2\" horizontal=\"'+vertical+'\" vertical=\"'+(num+1)+'\"><td width=\"5%\"><input class="h" type=\"hidden\" name=\"menu['+i+'][h]\" value=\"'+vertical+'\"><input class="v" type=\"hidden\" name=\"menu['+i+'][v]\" value=\"'+(num+1)+'\"><input type=\"text\" class=\"form-control menu_name\" name=\"menu['+i+'][name]\" value=\"\" placeholder=\"子菜单名\"  maxlength="7" /></td><td width=\"10%\"><input type=\"text\" class=\"form-control\" name=\"menu['+i+'][value]\" value=\"\" placeholder=\"子菜单链接\" /></td><td width=\"4%\"><select class=\"form-control type\" name=\"menu['+i+'][type]\"><option value=\"1\">跳转网址</option></select></td><td width=\"2%\"><a class=\"btn btn-xs red del_level2_btn\" level=\"'+vertical+'\"><i class=\"fa fa-times\"></i></a></td></tr>';
						    $('.menu_level1-'+vertical).append(str);3
						    i++;
						}else{
							alert("子菜单不能超过5个!");
						}
					}else{
					   alert("不能添加子菜单!");
					}
					
				});
				//一级菜单菜单类型改变时 判断是否有子菜单
				$('.level1').find('.type').live('change', function(){
					if($(this).val()!=0){
						$(this).parent('td').prev().children('select').removeAttr('disabled','disabled');
						$(this).parents('.level1').siblings('.level2').remove();
					}else{
						$(this).parent('td').prev().children('select').val(0);
					}
				});
				
				//删除一级菜单
				
				$(".del_level1_btn").live('click',function(){
					var level = $(this).attr("level");
					$('.menu_level1-'+level).remove();
					var len = $('.menu_level').length;
					var i = 1;
					for(i=1;i< len;i++){
						if($('.menu_level').hasClass('menu_level1-2')){
							$('.menu_level').removeClass('menu_level1-2');
							$('.menu_level').eq(i).addClass('menu_level1-1');
							$('.menu_level').eq(i).find('.del_level1_btn').attr('level',1);
							$('.menu_level').eq(i).find('.add_level2').attr('level',1);
						}
					}
					$('.menu_level1-'+level).find('.level1').attr("horizontal",1);
					$('.menu_level1-'+level).find('.h').val(1);
				
				});
				
				//删除二级菜单
				
				$(".del_level2_btn").live('click',function(){
					var level = $(this).attr("level");
					$(this).parent().parent().remove();
					var len = $('.menu_level1-'+level).find('.level2').length;
					var i;
					for(i=0;i< len;i++){
						$('.menu_level1-'+level).find('.level2').eq(i).attr("vertical",i+1);
						$('.menu_level1-'+level).find('.level2').eq(i).find('.v').val(i+1);
					}
				});
			});
			</script>