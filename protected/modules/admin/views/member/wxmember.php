	<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
        <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>"></script>
    	
		<!-- END SIDEBAR -->
		<!-- BEGIN PAGE -->
	<div class="page-content">
            <div class="modal fade" id="portlet-consume" tabindex="-2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
			<!-- BEGIN PAGE HEADER-->
			<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>'会员列表','subhead'=>'会员列表','breadcrumbs'=>array(array('word'=>'会员管理','url'=>''),array('word'=>'会员列表','url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('member/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
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
                                                                                                        <option value="%" <?php if("%"==$findsex) echo 'selected';?>>全部</option>
													<option value="0" <?php if("0"==$findsex) echo 'selected';?>>未知</option>
                                                                                                        <option value="1" <?php if("1"==$findsex) echo 'selected';?>>男</option>
                                                                                                        <option value="2" <?php if("2"==$findsex) echo 'selected';?>>女</option>
												</select>												
											</div>
										</div>
									
										<div class="form-group more-condition" style="float:left;width:200px;disabled:true;display:<?php echo isset($more) && $more?'':'none';?>;">
											<div class="input-group" style="width:95%;">
												<span class="input-group-addon">年龄</span>
												<input type="text" maxlength="2" class="form-control" name="agefrom" value="<?php echo $agefrom; ?>"><span class="input-group-addon">~</span><input type="text" maxlength="3" class="form-control" name="ageto" value="<?php echo $ageto; ?>">
											</div>
										</div>									 
										<div class="form-group more-condition" style="float:left;width:260px;disabled:true;display:<?php echo isset($more) && $more?'':'none';?>;">
											<div class="input-group" style="width:95%;">
												<span class="input-group-addon">出生日期</span>
												<input type="text" maxlength="5" class="form-control" name="birthfrom" value="<?php echo $birthfrom; ?>"><span class="input-group-addon">~</span><input type="text" maxlength="5" class="form-control" name="birthto" value="<?php echo $birthto; ?>">
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
													<option value="0000000000">--全体--</option>
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
												<select class="form-control" name="findcountry" id="findcountryid">
													<option value="%">--全体--</option>
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
												<select class="form-control" name="findprovince" id="findprovinceid">
													<option country="%" value="%">--全体--</option>
													<?php if(!empty($modelprovinces)):?>
													<?php foreach($modelprovinces as
                                                                                                         $key=>$modelprovince):?>
                                                                                                        <option country="<?php echo $modelprovince['country']; ?>"
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
												<select class="form-control" name="findcity" id="findcityid">
													<option country="%" province="%" value="%">--全体--</option>
													<?php if(!empty($modelcitys)):?>
													<?php foreach($modelcitys as
                                                                                                         $key=>$modelcity):?>
													<option country="<?php echo $modelprovince['country']; ?>" province="<?php echo $modelprovince['province'] ?>"
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
												<span class="input-group-addon">积分</span>
												<input type="text" maxlength="10" class="form-control" name="pointfrom" value="<?php echo $pointfrom; ?>"><span class="input-group-addon">~</span><input type="text" maxlength="10" class="form-control" name="pointto" value="<?php echo $pointto; ?>">
											</div>
										</div>
                                                                                <div class="form-group more-condition" style="float:left;width:350px;disabled:true;display:<?php echo isset($more) && $more?'':'none';?>;">
											<div class="input-group" style="width:95%;">
												<span class="input-group-addon">余额</span>
												<input type="text" maxlength="10" class="form-control" name="remainfrom" value="<?php echo $remainfrom; ?>"><span class="input-group-addon">~</span><input type="text" maxlength="10" class="form-control" name="remainto" value="<?php echo $remainto; ?>">
											</div>
										</div>
                                                        <hr class="more-condition" style="color:#000;width:100%;size:6;display: <?php echo isset($more) && $more?'':'none';?>;">
                                                                                <div class="form-group more-condition" style="float:left;width:340px;disabled:true;display:<?php echo isset($more) && $more?'':'none';?>;">
											<div class="input-group date-picker input-daterange" style="width:95%;">
												<span class="input-group-addon">时间范围</span><input type="text" class="form-control" name="datefrom" value="<?php echo $datefrom; ?>"><span class="input-group-addon">~</span><input type="text" class="form-control" name="dateto" value="<?php echo $dateto; ?>">
											</div>
										</div>
                                                                                <div class="form-group more-condition" style="float:left;width:350px;disabled:true;display:<?php echo isset($more) && $more?'':'none';?>;">
											<div class="input-group" style="width:95%;">
												<span class="input-group-addon">消费总额</span>
                                                                                                <input type="text" maxlength="10" class="form-control" name="consumetotalfrom" value="<?php echo $consumetotalfrom; ?>"><span class="input-group-addon">~</span><input type="text" maxlength="10" class="form-control" name="consumetotalto" value="<?php echo $consumetotalto; ?>">
											</div>
										</div>
                                                                                <div class="form-group more-condition" style="float:left;width:280px;disabled:true;display:<?php echo isset($more) && $more?'':'none';?>;">
											<div class="input-group" style="width:95%;">
												<span class="input-group-addon">消费次数</span>
												<input type="text" maxlength="6" class="form-control" name="timesfrom" value="<?php echo $timesfrom; ?>"><span class="input-group-addon">~</span><input type="text" maxlength="6" class="form-control" name="timesto" value="<?php echo $timesto; ?>">
											</div>
										</div>
									
                                                                    <hr class="more-condition" style="color:#000;width:100%;size:6;display: <?php echo isset($more) && $more?'':'none';?>;">
                                                                    
                                                                    <div class="input-group" style="float:left;width:650px;margin-bottom:15px;">
                                                                        <span class="input-group-addon">会员卡号或电话号码</span><input type="text" name="cardmobile" class="form-control" style="width:200px;" value="<?php echo $cardmobile;?>"/>
                                                                        <button type="submit" class="btn green">
                                                                                查找 &nbsp; 
                                                                                <i class="m-icon-swapright m-icon-white"></i>
                                                                        </button>
                                                                        <button type="button" class="btn gray" style="margin-left:20px;" onclick="location.href='<?php echo $this->createUrl('member/wxmember',array('companyId' => $this->companyId));?>'">
                                                                                复位 &nbsp; 
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
                                                                                <th width="10%">名称|昵称</th>
                                                                                <th width="6%">性别</th>
                                                                                <th width="10%">出生日期</th>
                                                                                <th width="6%">等级</th>
										<th width="8%">微信分组</th>
                                                                                <th width="12%">地区</th>
										<th width="8%">手机号</th>				
                                                                                <th width="10%"><a href="javascript:;" onclick="sort(1,<?php echo $sort?0:1;?>);">消费总额|次数 <i class="fa <?php echo $order==1?($sort?'fa-chevron-circle-down':'fa-chevron-circle-up'):'fa-chevron-circle-down';?>"></i></a></th>
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
                                                                                    <td><?php echo $model['user_name']."|".$model['nickname'];?></td>
                                                                                    <td><?php switch ($model['sex']){case 0:echo "未知"; break; case 1:echo "男";break; case 2:echo "女";};?></td>
                                                                                    <td><?php echo substr($model['user_birthday'],0,10);?></td>
                                                                                    <td><?php echo $model['level_name'];//(!empty($model->level)) echo $model->level->level_name;?></td>
                                                                                    <td><?php 
                                                                                    $hasname=false;
                                                                                    if(!empty($weixingroups))
                                                                                    {
                                                                                        foreach ($weixingroups as $wg)
                                                                                        {
                                                                                            if($wg["id"]==$model['weixin_group'])
                                                                                            {
                                                                                                echo $wg['name'];
                                                                                                $hasname=true;
                                                                                                break;
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                    if(!$hasname)
                                                                                    {
                                                                                        echo $model['weixin_group'];
                                                                                    }
                                                                                    ?></td>
                                                                                    <td><?php echo $model['country'];?> <?php echo $model['province'];?> <?php echo $model['city'];?></td>											
                                                                                    <td><?php echo $model['mobile_num'];?></td>
                                                                                    <td><?php echo $model['consumetotal']."|".$model['consumetimes'];//echo $model['consume_total_money'];?><a class="btn default btn-xs blue consumelist" title="消费列表" data-id="<?php echo $model['lid'];?>"  href="javascript:;" style="float:right;"><i class="fa fa-edit"></i></a></td>
                                                                                    <td><?php echo $model['pointvalidtotal'];//echo $model['pointvalidtotal'];//echo $model['consume_point_history'];?><a class="btn default btn-xs blue pointlist" title="积分列表" data-id="<?php echo $model['lid'];?>"  href="javascript:;" style="float:right;"><i class="fa fa-edit"></i></a></td>
                                                                                    <td><?php echo $model['remaintotal'];//echo $model['remaintotal'];//echo $model['remain_money'];?><a class="btn default btn-xs blue cashbacklist" title="充值返现列表" data-id="<?php echo $model['lid'];?>"  href="javascript:;" style="float:right;"><i class="fa fa-edit"></i></a></td>
                                                                                    
                                                                                    <td class="button-column">
                                                                                        <a class="btn default btn-xs blue branduserdetail" title="详细" href="javascript:;"
                                                                                           data-id="<?php echo $model['lid'];?>"
                                                                                           data-wg="<?php if(!empty($weixingroup[$model['weixin_group']])) {echo $weixingroup[$model['weixin_group']];} else {echo $model['weixin_group'];}?>"
                                                                                           data-ul="<?php echo $model['level_name'];?>"><i class="fa fa-search"></i>详细</a>
                                                                                    </td>
										</tr>
									<?php endforeach;?>	
									<?php else:?>
									<tr>
										<td colspan="12">没有找到数据</td>
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
	    
		function sort(o,s){
			var url = $('#Promote').attr('action');
			oIndex = url.indexOf('/o/');
			if(oIndex >0){
				var reg = new RegExp("([\\w\\/\\.]*)\\/o\\/\\d+\\/s\\/\\d+","i");
                                //alert(reg);
				url = url.replace(reg,"$1\/o\/"+o+"\/s\/"+s);
			} else {
				url += '/o/'+o+'/s/'+s;
			}
                        //alert(url)
			$('#Promote').attr('action',url);
			$('#Promote').submit();
		}
		function exportFile(){
                    alert("暂不提供");
                    return;
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
//		});
                
                //////////////new layout by osy//////
                var totalurl='';
                var modalconsumetotal;
                $(".consumelist").on("click",function(){
                    var lid=$(this).attr("data-id");
                    modalconsumetotal=$('#portlet-consume');
                        totalurl='<?php echo $this->createUrl('member/consumelist',array('companyId'=>$this->companyId));?>/lid/'+lid;
                        modalconsumetotal.find('.modal-content').load(totalurl
                        ,'', function(){
                          modalconsumetotal.modal();
                    });
                })
                
                $(".pointlist").on("click",function(){
                    var lid=$(this).attr("data-id");
                    modalconsumetotal=$('#portlet-consume');
                        totalurl='<?php echo $this->createUrl('member/pointlist',array('companyId'=>$this->companyId));?>/lid/'+lid;
                        modalconsumetotal.find('.modal-content').load(totalurl
                        ,'', function(){
                          modalconsumetotal.modal();
                    });
                })
                
                $(".cashbacklist").on("click",function(){
                    var lid=$(this).attr("data-id");
                    modalconsumetotal=$('#portlet-consume');
                        totalurl='<?php echo $this->createUrl('member/cashbacklist',array('companyId'=>$this->companyId));?>/lid/'+lid;
                        modalconsumetotal.find('.modal-content').load(totalurl
                        ,'', function(){
                          modalconsumetotal.modal();
                    });
                })
                
                $(".branduserdetail").on("click",function(){
                    var lid=$(this).attr("data-id");
                    var wg=$(this).attr("data-wg");
                    var ul=$(this).attr("data-ul");
                    modalconsumetotal=$('#portlet-consume');
                        totalurl='<?php echo $this->createUrl('member/branduserdetail',array('companyId'=>$this->companyId));?>/lid/'+lid+'/wg/'+wg+'/ul/'+ul;
                        modalconsumetotal.find('.modal-content').load(totalurl
                        ,'', function(){
                          modalconsumetotal.modal();
                    });
                })
                
                var selectcountry;
                
               
                $("#findcountryid").on("change",function(){
                    selectcountry=$(this).val();
                    if(selectcountry=="%")
                    {
                        $("#findprovinceid").val("%");
                    }
                    $("#findprovinceid").find("option").each(function(){
                        if($(this).attr("country")=="%" || $(this).attr("country")==selectcountry)
                        {
                           $(this).show(); 
                        }else{
                            $(this).hide();
                        }
                    })
                    $("#findcityid").val("%");
                    $("#findcityid").find("option").each(function(){
                        $(this).hide();
                    })
                })
                
                $("#findprovinceid").on("change",function(){
                    var thisval=$.trim($(this).val());
                    //alert(thisval);
                    if(thisval=="%")
                    {
                        $("#findcityid").val("%");
                    }
                    $("#findcityid").find("option").each(function(){
                        if($(this).attr("country")=="%" || $(this).attr("country")==selectcountry)
                        {
                            if($(this).attr("province")=="%" || $.trim($(this).attr("province"))==thisval)
                            {
                               $(this).show();
                           }else{
                                $(this).hide();
                           }
                        }else{
                            $(this).hide();
                        }
                    })
                })
	</script>	