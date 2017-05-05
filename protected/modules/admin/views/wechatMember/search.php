<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>"></script>
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
            
                    <div class="form-group more-condition" style="float:left;width:200px;disabled:true;display:<?php echo isset($more) && $more?'':'none';?>;">
                        <div class="input-group" style="width:95%;">
                            <span class="input-group-addon">国家</span>
                            <select class="form-control" name="findcountry" id="findcountryid">
                                    <option value="%">--全体--</option>
                                    <?php if(!empty($modelcountrys)):?>
                                    <?php foreach($modelcountrys as $key=>$modelcountry):?>
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
                                        <?php foreach($modelprovinces as $key=>$modelprovince):?>
                                            <option country="<?php echo $modelprovince['country']; ?>"
                                                    style="display:<?php if($modelprovince['country']==$findcountry){echo "";}else{ echo "none";} ?>"
                                                    value="<?php echo $modelprovince['province'];?>" 
                                            <?php if($modelprovince['province']==$findprovince && $modelprovince['country']==$findcountry) echo 'selected';?>>
                                            <?php echo $modelprovince['province'];?>
                                            </option>
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
                                <?php foreach($modelcitys as $key=>$modelcity):?>
                                    <option country="<?php echo $modelprovince['country']; ?>" province="<?php echo $modelprovince['province'] ?>"
                                        style="display:<?php if($modelcity['country']==$findcountry && $modelcity['province']==$findprovince){echo "";}else{ echo "none";} ?>"
                                        value="<?php echo $modelcity['city'];?>" 
                                    <?php if($modelcity['city']==$findcity && $modelcity['province']==$findprovince && $modelcity['country']==$findcountry) echo 'selected';?>>
                                    <?php echo $modelcity['city'];?>
                                    </option>
                                <?php endforeach;?>
                                <?php endif;?>
                            </select>												
                        </div>
                    </div>   
                                                        					
                    <hr class="more-condition" style="color:#000;width:100%;size:6;display: <?php echo isset($more) && $more?'':'none';?>;">

                    <div class="input-group" style="float:left;width:650px;margin-bottom:15px;">
                        <span class="input-group-addon">会员卡号或电话号码</span><input type="text" name="cardmobile" class="form-control" style="width:200px;" value="<?php echo $cardmobile;?>"/>
                        <button type="submit" class="btn green">
                                查找 &nbsp; 
                        <i class="m-icon-swapright m-icon-white"></i>
                        </button>
                        <button type="button" class="btn gray" style="margin-left:20px;" onclick="location.href='<?php echo $this->createUrl('wechatMember/search',array('companyId' => $this->companyId));?>'">
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
                                <th>地区</th>
                                <th>来源</th>
                                <th>余额</th>
                                <th>操作</th>
                                </tr>
                        </thead>
                        <tbody>
                            <?php if($models):?>
                            <?php foreach($models as $model):?>
                                <tr>
                                    <td><?php echo substr($model['card_id'],5,9);?></td>
                                    <td><?php echo $model['user_name']."|".$model['nickname'];?></td>
                                    <td><?php switch ($model['sex']){case 0:echo "未知"; break; case 1:echo "男";break; case 2:echo "女";};?></td>
                                    <td><?php echo $model['mobile_num'];?></td>
                                    <td><?php echo substr($model['user_birthday'],0,10);?></td>
                                    <td><?php echo $model['level_name'];?></td>
                                    <td><?php echo $model['country'];?> <?php echo $model['province'];?> <?php echo $model['city'];?></td>											
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
			     offset:['100px','350px'],
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