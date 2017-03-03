<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-1.8.17.custom.css');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-timepicker-addon.css');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-1.7.1.min.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-1.8.17.custom.min.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-addon.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-zh-CN.js');?>

<style>
.page-content .page-breadcrumb.breadcrumb {
   margin-top: 0px;
   margin-bottom: 20px;
}
.portlet.box.purple {
    border: 1px solid #CFCFCF;
}
.portlet-body{
   min-height: 550px;
   font-size: 14px;
}

.heading{
    font-size: 20px!important;
    font-weight: bold;
    text-align: right;
    padding-right: 25px;
}
.item-header{
    text-align: right;
}



@media (max-width: 768px) {
    .item-header{
        text-align: left;
        font-size:15px;
        margin-bottom: 10px;      
        padding:10px 0px 10px 15px;
    }
}
.radio-inline {
     padding-left: 0px!important; 
}
 .checkbox {
    padding-left: 0px!important; 
 }
.portlet-body .row{
    margin-top:20px;
}
.form-control{
    display: inline;
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
 <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','微信会员'),'url'=>$this->createUrl('wechatMember/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','积分'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('wechatMember/list' , array('companyId' => $this->companyId,'type'=>0)))));?>


    <div class="portlet purple box">

        <div class="portlet-body" >
             <?php $form=$this->beginWidget('CActiveForm', array(
                                            'id' => 'wechat_vip_member-form',
                                            'errorMessageCssClass' => 'help-block',
                                            'htmlOptions' => array(
                                                'enctype' => 'multipart/form-data'
                                            ),
                            )); ?>
            <div class="row ">
                <div class="col-xs-12 col-sm-2 item-header">
                    <?php echo $form ->labelEx($model, 'is_available') ?>：
                </div>
                <div class="col-xs-12 col-sm-10">
                    <?php echo $form->radioButtonList($model,'is_available',$is_available,array('separator'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;') );?>
                </div>
            </div>
            <div class="row ">
                 <div class="heading col-xs-12 col-sm-2 ">发放积分</div>
            </div>
             <div class="row ">
                    <div class="col-xs-12 col-sm-2 item-header">奖励规则 ：</div>
                    <div class="col-xs-12 col-sm-10">
                        每消费&nbsp;&nbsp;<input class="" type="text" value="" style="width:100px;">&nbsp;&nbsp;元，赠送1积分
                    </div>
            </div>
            <div class="row ">
                    <div class="col-xs-12 col-sm-2 item-header">奖励范围 ：</div>
                    <div class="col-xs-12 col-sm-10">
                        <label class="radio-inline">
                             <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1"> 现金消费
                        </label>
                        <label class="radio-inline">
                             <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> 现金和储值消费
                        </label>
                    </div>
            </div>
            <div class="row ">
                    <div class="col-xs-12 col-sm-2 item-header">积分有效期 ：</div>
                    <div class="col-xs-12 col-sm-10">
                        <div class="radios">
                            <label>
                               <input type="radio" name="optionskaimaian" id="" value="option1" >
                               当年发放的所有积分，在&nbsp;&nbsp;<?php echo $form->textField($model,'deadline',array('class' => 'form-control ui_timepicker','style'=>'width:160px;','placeholder'=>'0000-00-00 00:00:00')); ?>&nbsp;&nbsp;过期
                              </label>
                       </div>
                       <div class="radios">
                            <label>
                                <input type="radio" name="optionskaimaian" id="" value="option2" >
                                永久有效
                            </label>
                        </div>
                    </div>
            </div>
            <div class="row ">
                 <div class="heading col-xs-12 col-sm-2 ">消耗积分</div>
            </div>
             <div class="  ">
                    <div class="col-xs-12 col-sm-2 item-header"></div>
                    <div class="col-xs-12 col-sm-10">
                            <div class="checkbox  cash">
                                
                                    <div class="item">
                                        <input class="" type="checkbox" id="" value="option1">积分抵现
                                    </div>
                                  <div class="item">顾客消费时可使用积分抵用现金，1积分可抵用1元</div>
                                  
                                
                            </div>
                            <div class="checkbox  cash">
                                <div class="item">
                                    <input class="" type="checkbox" id="" value="option2">积分换礼
                                </div>
                                 <?php echo $form->textArea($model, 'use_point' , array('class' => 'form-control'));?>
                            </div>
                    </div>
            </div>
            <div class="row ">
                 <div class="heading col-xs-12 col-sm-2 ">限制与说明</div>
            </div>
            <div class="row">
                    <div class="col-xs-12 col-sm-2 item-header"><?php echo $form ->labelEx($model, 'limit_comment') ?>：</div>
                    <div class=" col-xs-12 col-sm-10">
                       <?php echo $form->textArea($model,'limit_comment',array('class'=>'form-control','id'=>'limit_comment','rows'=>'4','cols'=>'50')); ?>
                    </div>
            </div>
             <div class="row">
                    <div class=" col-xs-12 col-sm-2 ">&nbsp;</div>
                    <div class="col-xs-12 col-sm-10 ">
                       <button type="button"   class=' btn  btn-danger  '>确    定</button>            
                    </div>
             </div>  
            <?php $this->endWidget(); ?>
        </div> 
    </div>
        
</div>
<?php $this->widget('ext.kindeditor.KindEditorWidget',array(
        'id'=>'WxPoint_use_point',	//Textarea id
        'language'=>'zh_CN',
        // Additional Parameters (Check http://www.kindsoft.net/docs/option.html)
        'items' => array(
                'height'=>'200px',
                'width'=>'80%',
                'themeType'=>'simple',
                'resizeType'=>1,
                'allowImageUpload'=>true,
                'allowFileManager'=>true,
        ),
)); ?>
<script type="text/javascript">
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
        })
    });
    </script>  
