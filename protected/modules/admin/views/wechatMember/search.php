<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>"></script>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/address.js');?>
    <style>
<style>
.modal-dialog{
    width: 80%;
    height: 70%;
}
 .more-condition{
     margin-bottom: 15px !important;
 }
</style>
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

    <div id="main2" name="main2" style="min-width: 260px;min-height:170px;display:none;" onMouseOver="this.style.backgroundColor='rgba(255,222,212,1)'" onmouseout="this.style.backgroundColor=''">
		<div id="content"></div>
	</div>

    <!-- BEGIN PAGE HEADER-->
    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','微信会员'),'url'=>$this->createUrl('wechatMember/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','会员查询'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('wechatMember/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <?php $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'Promote',
                    'clientOptions'=>array(
                            'validateOnSubmit'=>true,
                    ),
                    'htmlOptions'=>array(
                            'class'=>'form-inline'
                    ),
            )); ?>
           <div class="col-md-12">
                <div class="table-responsive">
                    <fieldset>
                        <legend class="more-condition" style="font-size:1.2em;display:<?php echo isset($more) && $more?'':'none';?>;"">会员信息:</legend>
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
                                        <?php foreach($userlevels as $userlevel):?>
                                            <option value="<?php echo $userlevel->lid;?>" <?php if($userlevel->lid==$finduserlevel) echo 'selected';?>><?php echo $userlevel->level_name;?></option>
                                        <?php endforeach;?>
                                    <?php endif;?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group more-condition" style="float:left;width:200px;disabled:true;display:<?php echo isset($more) && $more?'':'none';?>;">
                            <div class="input-group" style="width:95%;">
                                <span class="input-group-addon">未消费时长</span>
                                <select class="form-control" name="noordertime" id="noordertime">
                                    <option value="%" <?php if("%"==$noordertime) echo 'selected';?>>--全部--</option>
                                    <option value="1" <?php if("1"==$noordertime) echo 'selected';?>>1个月</option>
                                    <option value="2" <?php if("2"==$noordertime) echo 'selected';?>>2个月</option>
                                    <option value="3" <?php if("3"==$noordertime) echo 'selected';?>>3个月</option>
                                    <option value="4" <?php if("4"==$noordertime) echo 'selected';?>>4个月</option>
                                    <option value="5" <?php if("5"==$noordertime) echo 'selected';?>>5个月</option>
                                    <option value="6" <?php if("6"==$noordertime) echo 'selected';?>>半年</option>
                                    <option value="12" <?php if("12"==$noordertime) echo 'selected';?>>一年</option>
                                    <option value="18" <?php if("18"==$noordertime) echo 'selected';?>>一年半</option>
                                    <option value="24" <?php if("24"==$noordertime) echo 'selected';?>>二年</option>
                                </select>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend class="more-condition" style="font-size:1.2em;display:<?php echo isset($more) && $more?'':'none';?>;"">来源信息:</legend>
                        <div class="form-group more-condition" style="float:left;width:200px;disabled:true;display:<?php echo isset($more) && $more?'':'none';?>;">
                            <div class="input-group" style="width:95%;">
                                <span class="input-group-addon">省份</span>
                                <select id="province" name="province" class="selectedclass form-control">
                                </select>
                            </div>
                        </div>

                        <div class="form-group more-condition" style="float:left;width:200px;disabled:true;display:<?php echo isset($more) && $more?'':'none';?>;">
                            <div class="input-group" style="width:95%;">
                                <span class="input-group-addon">地市</span>
                                <select id="city" name="city" class="selectedclass form-control">
                                </select>
                            </div>
                        </div>

                        <div class="form-group more-condition" style="float:left;width:200px;disabled:true;display:<?php echo isset($more) && $more?'':'none';?>;">
                            <div class="input-group" style="width:95%;">
                                <span class="input-group-addon">区县</span>
                                <select id="area" name="area" class="selectedclass form-control">
                                </select>
                            </div>
                        </div>
                        <div class="form-group more-condition" style="float:left;width:300px;disabled:true;display:<?php echo isset($more) && $more?'':'none';?>;">
                            <div class="input-group" style="width:95%;">
                                <span class="input-group-addon">来源店铺名</span>
                                <input id="source" name="source" class="form-control" placeholder="店铺名称关键字" value="<?php echo $source;?>" />
                            </div>
                        </div>

                        <div class="form-group more-condition" style="float:left;width:340px;disabled:true;display:<?php echo isset($more) && $more?'':'none';?>;">
                            <div class="input-group date-picker input-daterange" style="width:95%;">
                                <span class="input-group-addon">关注时间</span><input type="text" class="form-control" name="foucsfrom" value="<?php echo $foucsfrom; ?>"><span class="input-group-addon">~</span><input type="text" class="form-control" name="foucsto" value="<?php echo $foucsto; ?>">
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend class="more-condition" style="font-size:1.2em;display:<?php echo isset($more) && $more?'':'none';?>;"">订单信息:</legend>
                        <div class="form-group more-condition" style="float:left;width:340px;disabled:true;display:<?php echo isset($more) && $more?'':'none';?>;">
                            <div class="input-group date-picker input-daterange" style="width:95%;">
                                <span class="input-group-addon">订单时间</span><input type="text" class="form-control" name="datefrom" value="<?php echo $datefrom; ?>"><span class="input-group-addon">~</span><input type="text" class="form-control" name="dateto" value="<?php echo $dateto; ?>">
                            </div>
                        </div>
                        <div class="form-group more-condition" style="float:left;width:350px;disabled:true;display:<?php echo isset($more) && $more?'':'none';?>;">
                            <div class="input-group" style="width:95%;">
                                <span class="input-group-addon">消费总额</span>
                                <input type="text" maxlength="10" class="form-control" name="consumetotalfrom" value="<?php echo $consumetotalfrom; ?>">
                                <span class="input-group-addon">~</span>
                                <input type="text" maxlength="10" class="form-control" name="consumetotalto" value="<?php echo $consumetotalto; ?>">
                            </div>
                        </div>
                        <div class="form-group more-condition" style="float:left;width:280px;disabled:true;display:<?php echo isset($more) && $more?'':'none';?>;">
                            <div class="input-group" style="width:95%;">
                                <span class="input-group-addon">消费次数</span>
                                <input type="text" maxlength="6" class="form-control" name="timesfrom" value="<?php echo $timesfrom; ?>"><span class="input-group-addon">~</span><input type="text" maxlength="6" class="form-control" name="timesto" value="<?php echo $timesto; ?>">
                            </div>
                        </div>
                    </fieldset>
                    <hr class="more-condition" style="margin-top:0;color:#000;width:100%;size:6;display: <?php echo isset($more) && $more?'':'none';?>;">
                    <div class="input-group" style="float:left;width:700px;margin-bottom:15px;">
                        <span class="input-group-addon">会员卡号或电话号码</span><input type="text" name="cardmobile" class="form-control" style="width:200px;" value="<?php if($cardmobile=='%'){echo '';}else{echo $cardmobile;}?>"/>
                        <button type="submit" class="btn green">
                            <i class="fa fa-search"></i>
                            查找 &nbsp;
                        </button>
                        <button type="button" class="btn gray" style="margin-left:20px;" onclick="location.href='<?php echo $this->createUrl('wechatMember/search',array('companyId' => $this->companyId));?>'">
                            <i class="m-icon-swapright m-icon-white"></i>
                            复位 &nbsp;
                        </button>
                        <button type="button" class="btn blue" style="margin-left:20px;" id="download">
                            <i class="m-icon-swapright m-icon-white"></i>
                            导出Excel &nbsp;
                        </button>
                    </div>

                    <div style="text-align:center;display:inline;width:200px;">
                        <?php if(isset($more) && $more):?>
                            <a href="javascript:;" class="btn blue glyphicon glyphicon-chevron-up" style="height: 34px;"> 收起</a>
                        <?php else:?>
                            <a href="javascript:;" class="btn yellow glyphicon glyphicon-chevron-down" style="height: 34px;"> 展开</a>
                        <?php endif;?>
                        <input type="hidden" name="more" id="more" value="<?php echo isset($more) && $more?1:0;?>"/>
                    </div>
                </div>
            </div>

            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box purple">
                <div class="portlet-title">
                    <div class="caption"><i class="fa fa-group"></i>会员列表</div>
                    <div class="actions">
                    </div>
                </div>
                <div class="portlet-body" id="table-manage">
                    <table class="table table-bordered table-hover" id="sample_1">
                        <thead>
                                <tr>
                                <th>卡号</th>
                                <th>姓名|昵称</th>
                                <th>性别</th>
                                <th>手机号</th>
                                <th>生日</th>
                                <th>等级</th>
                                <th>地区(会员)</th>
                                <th>关注日期</th>
                                <th>来源店铺</th>
                                <th>余额</th>
                                <th>操作</th>
                                </tr>
                        </thead>

                        <tbody>
                            <?php if($models):?>

                            <?php foreach($models as $model):?>
                                <tr>
                                    <td><?php echo substr($model['card_id'],5,9);?></td>
                                    <td><?php echo $model['user_name']?$model['user_name'].'|'.$model['nickname']:$model['nickname'];?></td>
                                    <td><?php switch ($model['sex']){case 0:echo "未知"; break; case 1:echo "男";break; case 2:echo "女";};?></td>
                                    <td><?php
                                    if($model['mobile_num']){
                                    	if(Yii::app()->user->role == 8){
                                    		$str = substr_replace($model['mobile_num'],'****',3,4);
                                    	}else{
                                    		$str = $model['mobile_num'];
                                    	}
                                    	echo $str;
                                    }
                                    ?></td>
                                    <td><?php echo substr($model['user_birthday'],0,10);?></td>
                                    <td><?php echo $model['level_name'];?></td>
                                    <td><?php echo $model['country'];?> <?php echo $model['province'];?> <?php echo $model['city'];?></td>
                                    <td><?php echo substr($model['create_at'],0,10);?></td>
                                    <td><?php echo $model['company_name'];?></td>
                                    <td><?php echo $model['all_money'];?></td>
                                    <td class="button-column">
                                        <a  class='btn default btn-sm blue'  href="<?php echo $this->createUrl('wechatMember/searchdetail',array('num' => $model['lid'],'card_id' => $model['card_id'],'companyId' => $this->companyId));?>"><i class="fa fa-search"></i>详情</a>
                                        <?php if(Yii::app()->user->role <=5):?>
                                        <a  class='btn default yellow addCash' id="setAppid<?php echo $model['dpid'];?>" userid="<?php echo $model['lid'];?>" dpid="<?php echo $model['dpid'];?>" name="<?php echo $model['user_name'].'|'.$model['nickname'];?>"><i class="fa fa-rmb"></i><?php echo yii::t('app','充值');?></a>
                                        <?php endif;?>
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
									<?php echo yii::t('app','共 ');?> <?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?>  , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> ,  <?php echo yii::t('app','当前是第');?> <?php echo $pages->getCurrentPage()+1;?> <?php echo yii::t('app','页');?>
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
addressInit('province', 'city', 'area', '<?php echo $province;?>', '<?php echo $city;?>', '<?php echo $area;?>');
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
$(function(){
    $('#yw0').on('click',"li a",function(event) {
        // 获取这个a标签的href中的url
        url = $(this).attr("href");
        //alert(url);return false;
        // 取消点击事件的默认动作
        event.preventDefault();
        // 修改form中的action动作
        $("#Promote").attr("action", url);
        // 提交表单
        $("#Promote").submit();
    });
});

$(".glyphicon").click(function(){
        if($(this).hasClass('glyphicon-chevron-down')){
                    $(this).removeClass('glyphicon-chevron-down yellow').addClass("glyphicon-chevron-up red");
                    $(this).html(' 收起');
                    $('.more-condition').show();
                        $('#more').val(1);
        } else {
                    $(this).removeClass('glyphicon-chevron-up red').addClass("glyphicon-chevron-down yellow");
                    $(this).html(' 展开');
                    $('.more-condition').hide();
                    $('#more').val(0);
        }
});
$("#download").click(function(){
    if (confirm('确定导出所选条件的查询结果吗?')) {
       $('#Promote').attr('action', '<?php echo $this->createUrl('wechatMember/searchExport',array('companyId' => $this->companyId));?>');
       $('#Promote').submit();
       $('#Promote').attr('action', '<?php echo $this->createUrl('wechatMember/search',array('companyId' => $this->companyId));?>');

    }
});

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
		$('.addCash').on('click',function(){

			$('#content').html('');
			var name = $(this).attr('name');
			var userid = $(this).attr('userid');
			var dpid = $(this).attr('dpid');

			var content = '<div style="width: 88%;margin-left: 6%;padding-top: 10%;"><span>'+name+'</span></div>'
						+ '<div style="width: 88%;margin-left: 6%;padding-top: 10%;"><input id="addmoney" placeholder="输入金额"/></div>'
						+ '<div style="width: 88%;margin-left: 6%;padding-top: 20px;"><button id="add_cash" class="btn green">确认</button></div>'
						;
			$('#content').html(content);
			layer_chongzhi=layer.open({
			     type: 1,
			     //shift:5,
			     shade: [0.5,'#fff'],
			     //move:'#main2',
			     moveOut:true,
			     shade: false,
			     title: false, //不显示标题
			     area: ['100', '100'],
			     content: $('#main2'),
			     cancel: function(index){
			         layer.close(index);
			         layer_chongzhi=0;
			     }
			 });

			$('#add_cash').on('click',function(){
				var money = $('#addmoney').val();
				layer.msg(money);
				if(money == ''||money ==null){
					layer.msg('请输入充值金额！！！');
					return false;
				}
				if(window.confirm("确认进行充值？？")){
					var url = "<?php echo $this->createUrl('wechatMember/addcash',array('companyId'=>$this->companyId));?>/userid/"+userid+"/dpid/"+dpid+"/money/"+money;
			        $.ajax({
			            url:url,
			            type:'GET',
			            //data:orderid,//CF
			            async:false,
			            dataType: "json",
			            success:function(msg){
			                var data=msg;
			                if(data.status){
			                	layer.msg('充值成功！！！');
			                	layer.close(layer_chongzhi);
			                	layer_chongzhi=0;
			                	location.reload();
			                }else{
			                	layer.msg('充值失败！！！');
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