<link type="text/css" href="<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-1.8.17.custom.css');?>" rel="stylesheet" />
<link type="text/css" href="<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-timepicker-addon.css');?>" rel="stylesheet" />
<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-1.7.1.min.js');?>"></script>
<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-1.8.17.custom.min.js');?>"></script>
<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-addon.js');?>"></script>
<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-zh-CN.js');?>"></script>
<style>
    .modal-dialog{
            width: 80%;
            height: 70%;
    }
    .wxcardbg{
            width: 220px;
            height: 115px;
            margin-top: 10px;
            margin-left: 15px;
            border:1px solid red;
            border-radius: 5px;
            float: left;
            color: red;
            background-color: #ff00bc;
            position: relative;
    }
    .wxcardhead{
            width: 100%;
            height: 85px;
            font-size: 22px;
    }
    .wxcardheadl{
            width: 50%;
            height: 85px;
            float: left;
            border-right: 1px dashed white;
    }
    .wxcardheadll{
            width: 90px;
            height: 75px;
            margin-top: 5px;
            marin-left: 5px;
    }
    .wxcardheadll .money{
            width: 75px;
            height: 75px;
            line-height: 75px;
            float: left;
            font-size: 40px;
            font-weight: 900;
            color: white;
            text-align: center;
    }
    .wxcardheadll .unit{
            width: 15px;
            height: 75px;
            line-height: 100px;
            font-size: 22px;
            float: left;
            color: black;
    }
    .wxcardheadr{
            width: 48%;
            height: 85px;
            float: left;
    }
    .wxcardheadr .top{
            width: 100%;height: 30px;line-height: 30px;font-size: 16px;text-align: center;color: #000;
    }
    .wxcardheadr .cen{
            width: 100%;
            height: 20px;
            line-height: 20px;
            font-size: 16px;
            text-align: center;
            color: #000;
            display: none;
    }
    .wxcardheadr .bot{
            width: 100%;
            height: 35px;
            line-height: 35px;
            font-size: 18px;
            text-align: center;
            color: #000;
            font-weight: 600;
    }
    .wxcardend{
            width: 100%;
            height: 30px;
            line-height: 30px;
            font-size: 12px;
            border-top: 1px dashed pink;
            text-align: center;
            color: #fff;
    }
    .wxcardactive{
            position: absolute;
            top: 30px;
            left: 80px;
    }
    .addsave{
            float: right;
            margin: 10px 10px 0px 0px;
    }
    .addsave button{
            font-size: 18px;
            padding: 4px 10px;
            border-radius: 5px;
            background-color: #6beaff;
    }
    .uhide{
            display: none;
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
    <!-- END BEGIN STYLE CUSTOMIZER -->            
    <!-- BEGIN PAGE HEADER-->
    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','微信赠券'),'url'=>$this->createUrl('wechatMarket/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','给老会员赠券'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('wechatMarket/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
    <div class="row">
    <?php $form=$this->beginWidget('CActiveForm', array(
                                                        'id' => 'sentwxcardpromotion-form',
                                                        'errorMessageCssClass' => 'help-block',
                                                        'htmlOptions' => array(
                                                                'class' => 'form-horizontal',
                                                                'enctype' => 'multipart/form-data'
                                                        ),
                                                    )); ?>
        <div class="col-md-12">
            <div class="tabbable tabbable-custom">			
		<div class="tab-content">
                    <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet box purple">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-globe"></i>
                                    <?php echo yii::t('app','给老会员赠券(*为必填项)');?>
                                </div>
                                <div class="actions">
                                    <?php if($model):?>
                                        <?php if($model->is_available):?>
                                            <div class="btn-group">
                                                <button type="button"  class="staclose btn green start2"><i class="fa  fa-check"></i> <?php echo yii::t('app','开启');?></button>
                                            </div>
                                        <?php else:?>
                                            <div class="btn-group">
                                                <button type="button"  class="staclose btn red close1"><i class="fa fa-ban"></i> <?php echo yii::t('app','关闭');?></button>
                                            </div>
                                        <?php endif;?>
                                    <?php else:?>
                                        <div class="btn-group">
                                            <button type="button"  class="staclose btn green start1"><i class="fa fa-check-circle"></i> <?php echo yii::t('app','开启');?></button>
                                        </div>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div class="portlet-body" id="table-manage">
				<?php if($model && $model->is_available=='0'):?>
                                    <div class="form-group ">	
                                        <i style="color: red;" class="fa  fa-star"></i>
                                        <?php echo $form->label($model, yii::t('app','标题'),array('class' => 'col-md-3 control-label'));?>
                                        <div class="col-md-4">
                                                <?php echo $form->textField($model, 'promotion_title',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('promotion_title')));?>
                                                <?php echo $form->error($model, 'promotion_title' )?>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group" >
                                    <i style="color: red;" class="fa  fa-star"></i>
                                    <?php if($model->hasErrors('promotion_abstract')) echo 'has-error';?>
                                            <?php echo $form->label($model, yii::t('app','摘要'),array('class' => 'col-md-3 control-label'));?>
                                            <div class="col-md-4">
                                                    <?php echo $form->textField($model, 'promotion_abstract',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('promotion_abstract')));?>
                                                    <?php echo $form->error($model, 'promotion_abstract' )?>
                                            </div>
                                    </div>								
                                    <div class="form-group">
	                		<i style="color: red;" class="fa  fa-star"></i>
                                        <label class="control-label col-md-3"><?php echo yii::t('app','活动有效期限');?></label>
                                        <div class="col-md-5">
                                                <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
                                                         <?php echo $form->textField($model,'begin_time',array('class' => 'form-control ui_timepicker','style'=>'width:160px;','placeholder'=>$model->getAttributeLabel('begin_time'))); ?>
                                                         <span class="input-group-addon"> ~ </span>
                                                         <?php echo $form->textField($model,'end_time',array('class'=>'form-control ui_timepicker','style'=>'width:160px;','placeholder'=>$model->getAttributeLabel('end_time'))); ?>
                                                </div> 
                                                <?php echo $form->error($model,'begin_time'); ?>
                                                <?php echo $form->error($model,'end_time'); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                            <i style="color: red;" class="fa  fa-star"></i>
                                            <?php echo $form->label($model, yii::t('app','发送信息'),array('class' => 'col-md-3 control-label'));?>
                                            <div class="col-md-8">
                                                    <?php echo $form->textArea($model, 'promotion_message' , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('promotion_message')));?>
                                                    <?php echo $form->error($model, 'promotion_message' )?>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                            <?php echo $form->label($model, yii::t('app','规则说明'),array('class' => 'col-md-3 control-label'));?>
                                            <div class="col-md-8">
                                                    <?php echo $form->textArea($model, 'promotion_memo' , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('promotion_memo')));?>
                                                    <?php echo $form->error($model, 'promotion_memo' )?>
                                            </div>
                                    </div>
					
                                <div style="width: 100%;height: 1px;border-top: 1px dashed salmon;margin-bottom: 10px;">
                                </div>
                                <div class="form-group">
                                    <i style="color: red;" class="fa  fa-star"></i>
                                    <label class="col-md-3 control-label " style="color: red;">添加优惠券</label>
                                    <div class="col-md-9">
                                        <div style="border: 1px solid silver;min-height: 40px;border-radius: 5px;padding-bottom: 10px;">
                                            <?php if($cupons):?>
                                                <?php foreach ($cupons as $cupon):?>
                                                    <div class="wxcardbg <?php if(in_array($cupon->lid, $selcups)) echo 'activechecked';?>" plid="<?php echo $cupon->lid;?>" pcode="<?php echo $cupon->sole_code;?>">
                                                        <div class="wxcardhead" style="">
                                                            <div class="wxcardheadl"style="">
                                                                <div class="wxcardheadll"style="">
                                                                    <div class="money" style=""><span><?php echo floor($cupon->cupon_money);?></span></div>
                                                                    <div class="unit" style="">元</div>
                                                                </div>
                                                            </div>
                                                            <div class="wxcardheadr" style="">
                                                                <div class="top" style="">满<span><?php echo floor($cupon->min_consumer);?></span>可使用</div>
                                                                <div class="cen" style="">赠送1张</div>
                                                                <div class="bot" style="">代金券</div>
                                                            </div>
                                                        </div>
                                                        <div class="wxcardend" style="">限<span><?php echo date('Y-m-d',strtotime($cupon->begin_time));?></span> 至<span><?php echo date('Y-m-d',strtotime($cupon->end_time));?></span>  使用</div>

                                                        <div class="wxcardactive <?php if(in_array($cupon->lid, $selcups)) echo '';else echo 'uhide';?>" ><img width="50px" style="" src="<?php if($is_new) echo '../../../../../../img/checked.png';else echo '../../../../img/checked.png';?>"/></div>
                                                    </div>
                                                <?php endforeach;?>
                                            <?php endif;?>
                                            <div style="clear: both;"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div style="width: 100%;height: 1px;border-top: 1px dashed salmon;margin-bottom: 10px;">
                                </div>
                                <input type="hidden" id="newselcups" name="newselcups"></input>
                                <input type="hidden" id="falid" name="falid"></input>
                                <input type="hidden" id="facode" name="facode"></input>
                                <div class="form-actions fluid">
                                        <div class="col-md-offset-3 col-md-9">
                                                <button type="button" id="su" class="btn blue"><?php echo yii::t('app','确定');?></button>
                                        </div>
                                </div>
                            </div>
                            <?php else:?>
                            <span style=" font-size: 22px;color: red;">请先 '开启' 给老会员赠券活动，再进行操作！</span>
                            <?php endif;?>
                            
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
                    </div>
		</div>
            </div>
            <?php $this->endWidget(); ?>
            <?php $this->widget('ext.kindeditor.KindEditorWidget',array(
                                                            'id'=>'SentwxcardPromotion_promotion_memo',	//Textarea id
                                                            'language'=>'zh_CN',
                                                            // Additional Parameters (Check http://www.kindsoft.net/docs/option.html)
                                                            'items' => array(
                                                                    'height'=>'200px',
                                                                    'width'=>'100%',
                                                                    'themeType'=>'simple',
                                                                    'resizeType'=>1,
                                                                    'allowImageUpload'=>true,
                                                                    'allowFileManager'=>true,
                                                            ),
                                                    )); ?>
        </div>		
    </div>					<!-- END EXAMPLE TABLE PORTLET-->
</div>				
<script type="text/javascript">
    $(document).ready(function(){
            $('#normalpromotion-form').submit(function(){
                if(!$('.checkboxes:checked').length){
                        alert("<?php echo yii::t('app','请选择要删除的项');?>");
                        return false;
                }
                return true;
        });
        $(function () {
                $(".ui_timepicker").datetimepicker({
                    //showOn: "button",
                    //buttonImage: "./css/images/icon_calendar.gif",
                    //buttonImageOnly: true,
                    showSecond: true,
                    timeFormat: 'hh:mm:ss',
                    stepHour: 1,
                    stepMinute: 1,
                    stepSecond: 1
                });
        });
        $('.wxcardbg').on('click',function(){
                if($(this).hasClass('activechecked')){
                        $(this).removeClass('activechecked');
                        $(this).find('.wxcardactive').addClass('uhide');
                }else{
                        $(this).find('.wxcardactive').removeClass('uhide');
                        $(this).addClass('activechecked');
                }
        });
        $('.staclose').on('click' , function(){
                //is_new 为1时，表示该店铺没有建立生日赠券活动；2时表示开启生日赠券活动；3表示关闭生日赠券活动。
                if($(this).hasClass('close1')){
                    location.href="<?php echo $this->createUrl('sentOldMember/index' , array('companyId'=>$this->companyId));?>/is_new/3";
                     }else if($(this).hasClass('start1')){
                        location.href="<?php echo $this->createUrl('sentOldMember/index' , array('companyId'=>$this->companyId));?>/is_new/1";
                     }else if($(this).hasClass('start2')){
                        location.href="<?php echo $this->createUrl('sentOldMember/index' , array('companyId'=>$this->companyId));?>/is_new/2";
                     }

        });
    });
    $("#su").on('click',function() {
            var plids = '';
            var facode = '<?php echo $phscode;?>';
            var falid = '<?php echo $pid;?>';
            $('.activechecked').each(function(){
                    var plid = $(this).attr('plid');
                    var pcode = $(this).attr('pcode');
                    plids = plid +','+ pcode +';'+ plids;
                    });
                    //alert(plids);
            if(plids!=''){
                    plids = plids.substr(0,plids.length-1);//除去最后一个“;”
                    //alert(plids);
            }else{
                     alert("<?php echo yii::t('app','请至少选择一项优惠券！！！');?>");
                     return false;
            }
            //alert(plids);
            var begintime = $('#SentwxcardPromotion_begin_time').val();
            var endtime = $('#SentwxcardPromotion_end_time').val();

            var title = $('#SentwxcardPromotion_promotion_title').val();
            
            var abstracts = $('#SentwxcardPromotion_promotion_abstract').val();
            var messages = $('#SentwxcardPromotion_promotion_message').val();
	         
            if(title&&abstracts&&messages){

                           }else{
                                   alert("请填写带星号的项！！");
                                   return false;
                           }
            if(endtime<=begintime){
                    alert("<?php echo yii::t('app','活动结束时间应该大于开始时间!!!');?>");
                    return false;
            }

            $("#falid").val(falid);
            $("#facode").val(facode);
            $("#newselcups").val(plids);
            $("#sentwxcardpromotion-form").submit();
    });
    function swfupload_callback(name,path,oldname)  {
            $("#SentwxcardPromotion_main_picture").val(name);
            $("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
    }
</script>