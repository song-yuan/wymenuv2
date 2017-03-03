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
}
.portlet-body>.row{
    margin:15px 0 30px 0;
}
.item-header{
    text-align: right;
}

.radio-inline{
    padding-left:0!important;
}
.form-group{
    width:33.333%!important;
}
.row{
    margin-top:20px;
}
.items-selected{
    outline:2px solid red;
}
.radios{
    display: block;
    min-height: 20px;
    margin-bottom: 10px;
    vertical-align: middle;
}
.items{
    border:.1rem solid rgba(0,0,0,.3);
    padding:20px;
    background-color: #f2f2f2;
}
.options-warp{
    margin:10px;
}
input[type='button']{
    margin-left: 30px;
    width:36px;
}
@media (max-width: 768px) {
    .item-header{
        text-align: left;
        font-size:15px;
        margin-bottom: 10px;
        background-color:#f9f9f9;
        padding:10px;
    }
    .form-group{
        width:66.666%!important;
	}
	
}
.activeimg{
	border: 1px solid red;
	
	z-index: 99999999;
}
.wxcardimg{
	position: relative;
	width: 194px;
	height: 110px;
	float: left;
	margin: 8px 5px;
	border: 1px solid green;
	border-radius: 10px;
}
.wxcardchecked{
	position: absolute;
	top:10px;
}
.uhine{
	display: none;
}

</style>

<div class="page-content">
<?php $this->widget(
                    'application.modules.admin.components.widgets.PageHeader', 
                    array(
                        'breadcrumbs'=>array(
                                            array('word'=>yii::t('app','微信会员'),
                                                'url'=>$this->createUrl('wechatMember/list' , array('companyId'=>$this->companyId))),
                                            array('word'=>yii::t('app','VIP会员'),
                                                'url'=>$this->createUrl('wechatMember/vip' , array('companyId'=>$this->companyId))),
                                            array('word'=>yii::t('app','修改VIP等级'),'url'=>'')
                                            ),
                        'back'=>array(
                                    'word'=>yii::t('app','返回'),
                                    'url'=>$this->createUrl('wechatMember/vip' , array('companyId' => $this->companyId,))
                                    )
                        )
                    );?> 
                    <div class="portlet purple box">                      
                        <div class="portlet-body" >
                            <?php $form=$this->beginWidget('CActiveForm', array(
                                            'id' => 'wechat_vip_member-form',
                                            'errorMessageCssClass' => 'help-block',
                                            'htmlOptions' => array(
                                                'enctype' => 'multipart/form-data'
                                            ),
                            )); ?>
                            
                                <div class="row">
                                    <div class="col-xs-12 col-sm-2 item-header"><?php echo $form ->labelEx($model, 'level_name') ?>：</div>
                                    <div class=" col-xs-12 col-sm-10">
                                        <div class="form-group">                                    
                                            <?php echo $form->textField($model,'level_name',array('class'=>'form-control','id'=>'level_name')); ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class=" col-xs-12 col-sm-2 item-header"><?php echo $form ->labelEx($model, 'level_discount') ?>：</div>
                                    <div class=" col-xs-12 col-sm-10">
                                      	<div class="form-group">                                    
                                           <?php echo $form->textField($model,'level_discount',array('class'=>'form-control','id'=>'level_discount')); ?>
                                    	</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class=" col-xs-12 col-sm-2 item-header"><?php echo $form ->labelEx($model, 'birthday_discount') ?>：</div>
                                    <div class="col-xs-12 col-sm-10">
                                         <div class="form-group">                                    
                                           <?php echo $form->textField($model,'birthday_discount',array('class'=>'form-control','id'=>'birthday_discount')); ?>
                                          </div>
                                                                        
                                    </div>
                                </div> 
                                
                                <div class="row">
                                    <div class=" col-xs-12 col-sm-2 item-header"><?php echo $form ->labelEx($model, 'min_total_points') ?>：</div>
                                    <div class="col-xs-12 col-sm-10">
                                         <div class="form-group">                                    
                                           <?php echo $form->textField($model,'min_total_points',array('class'=>'form-control','id'=>'min_total_points')); ?>
                                          </div>
                                                                        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class=" col-xs-12 col-sm-2 item-header"><?php echo $form ->labelEx($model, 'max_total_points') ?>：</div>
                                    <div class="col-xs-12 col-sm-10">
                                         <div class="form-group">                                    
                                           <?php echo $form->textField($model,'max_total_points',array('class'=>'form-control','id'=>'max_total_points')); ?>
                                          </div>
                                                                        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class=" col-xs-12 col-sm-2 item-header"><?php echo $form ->labelEx($model, 'style_id') ?>：</div>
                                    <div class="col-xs-12 col-sm-9">
                                    <?php if($member_wxcard_bgimgs):?>
                                         <div style="width: 50% !important;" class="form-group">                                    
                                          <span> 请从如下背景选项中选择一个作为微信端的会员卡背景</span>
                                          </div>
                                         <div>
                                         
                                         <?php foreach ($member_wxcard_bgimgs as $bgimg):?>
                                         	<div class="wxcardimg" styleId = '<?php echo $bgimg->lid;?>'>
                                         	<div style=""><img style="border-radius: 10px;" width="192px" height="108px" src="<?php echo $bgimg->bg_img;?>" /></div>
                                         	<div class="wxcardchecked <?php if($bgimg->lid == $model->style_id)echo '';else echo 'uhine';?>"><img width="50px" style="margin-left: 60px;" src="../../../../../../img/checked.png"/></div>
                                         	</div>
                                         <?php endforeach;?>
                                         </div>
                                    <?php else:?>
                                        <div style="width: 50% !important;" class="form-group">                                    
                                          	<a href="<?php echo $this->createUrl('wxCardStyle/index',array('companyId' => $this->companyId,));?>" style="color: red;">请先添加会员卡样式，否则添加失败。点击添加</a>
                                        </div>
                                    <?php endif;?>
                                                                        
                                    </div>
                                    <input type="hidden" id="style_id" name="style_id" value="" />
                                </div>
                               
                                 <div class="row">
                                   <div class=" col-xs-12 col-sm-2 ">&nbsp;</div>
                                    <div class="col-xs-12 col-sm-10 ">
                                          <button type="submit"   class=' btn  btn-danger  '>确    定</button>            
                                    </div>
                                </div>    
                           <?php $this->endWidget(); ?>
                            </div>
                     </div>
                </div>
    </div>
</div>

<script type="text/javascript">
$(function(){
    $("img").click(function(){
        $("img").removeClass("items-selected");
        $(this).addClass("items-selected");
       $("input[name='WeChatVipMember[vip_card_img]']").val($(this).attr("src"));
    });
});    
    
function addnew(btn)
{   
	// 先获取点击的按钮所在的<div>
	var tr = $(btn).parent().parent();
        var id = $(".options-warp").length+1;
       
	if($(btn).val() == "+")
	{
		// 克隆<div>
		var newtr = tr.clone();
		// 把+变-
		newtr.find(":button").val("-");
		newtr.find(".privileg_content").val("");
                newtr.find(".id").empty().html(id);
		// 把到btn所在的TR前面
		$(".items").append(newtr);
	}
	else
		tr.remove();
}
$('.wxcardimg').on('click',function(){
	$('.wxcardimg').removeClass('activeimg');
	$(this).addClass('activeimg');
	$('.wxcardchecked').addClass('uhine');
	$(this).find('.wxcardchecked').removeClass('uhine');
	var styleId = $(this).attr('styleId');
	$('#style_id').val(styleId);
})

</script>

