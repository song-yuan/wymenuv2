  <?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/wechat_css/mobiscroll.min.css');?>
    <?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/wechat_js/mobiscroll.min.js');?>

<?php $form=$this->beginWidget('CActiveForm', array(
                'id' => 'normalpromotion-form',
                'errorMessageCssClass' => 'help-block',
                'htmlOptions' => array(
                        'class' => 'form-horizontal',
                        'enctype' => 'multipart/form-data'
                ),
)); ?>
        <style>
        #category_container select {display:block;float:left;margin-right:3px;max-width:200px;overflow:hidden;}
        .noclick{
        	background-color: silver !important;
        }
        </style>
        <?php if($source)$a=true;else $a=false;?>
        <?php ?>
        <div class="form-body">

        		<div class="form-group">
                	<em style="color: red;"></em>
                        <div class="col-md-4">
                        	<span style="color: red;"> <?php if($a)echo '***注意：该活动属于总部下发活动，无法进行修改！！！';?></span>
                        </div>
                </div>
                <div class="form-group">
                <em style="color: red;"><?php if($model->hasErrors('promotion_title')) echo '必填项';?></em>
                        <?php echo $form->label($model, yii::t('app','标题'),array('class' => 'col-md-3 control-label'));?>
                        <div class="col-md-4">
                                <?php echo $form->textField($model, 'promotion_title',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('promotion_title'),'disabled'=>$a));?>
                                <?php echo $form->error($model, 'promotion_title' )?>
                        </div>
                </div><!-- 活动标题 -->
                <div class="form-group">
                        <?php if($model->hasErrors('main_picture')) echo '必填项';?>
                        <?php echo $form->label($model,'main_picture',array('class'=>'control-label col-md-3')); ?>
                        <div class="col-md-9">
                        <?php
                        $this->widget('application.extensions.swfupload.SWFUpload',array(
                                'callbackJS'=>'swfupload_callback',
                                'fileTypes'=> '*.jpg',
                                'buttonText'=> yii::t('app','上传图片'),
                                'companyId' => $model->dpid,
                                'imgUrlList' => array($model->main_picture),
                        ));
                        ?>
                        <?php echo $form->hiddenField($model,'main_picture'); ?>
                        <?php echo $form->error($model,'main_picture'); ?>
                        </div>
                </div><!-- 主图片 -->

                <div class="form-group" >
                <em style="color: red;"><?php if($model->hasErrors('promotion_abstract')) echo '必填项';?></em>
                        <?php echo $form->label($model, yii::t('app','摘要'),array('class' => 'col-md-3 control-label'));?>
                        <div class="col-md-4">
                                <?php echo $form->textField($model, 'promotion_abstract',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('promotion_abstract'),'disabled'=>$a));?>
                                <?php echo $form->error($model, 'promotion_abstract' )?>
                        </div>
                </div><!-- 活动摘要 -->
                <div class="form-group">
                        <?php echo $form->label($model, yii::t('app','类型'),array('class' => 'col-md-3 control-label'));?>
                        <div class="col-md-4">
                                <?php echo $form->dropDownList($model, 'promotion_type', array('0' => yii::t('app','独享') , '1' => yii::t('app','共享')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('promotion_type'),'disabled'=>$a));?>
                                <?php echo $form->error($model, 'promotion_type' )?>
                        </div>
                </div><!-- 活动类型 -->
<div class="form-group">
                        <?php echo $form->label($model, yii::t('app','是否可用代金券'),array('class' => 'col-md-3 control-label'));?>
                        <div class="col-md-4">
                                <?php echo $form->dropDownList($model, 'can_cupon', array('0' => yii::t('app','可以使用代金券') , '1' => yii::t('app','不能使用代金券')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('can_cupon'),'disabled'=>$a));?>
                                <?php echo $form->error($model, 'can_cupon' )?>
                        </div>
                </div><!-- 是否可用代金券 -->


                <div class="form-group">
                        <?php echo $form->label($model, yii::t('app','活动针对对象'),array('class' => 'col-md-3 control-label'));?>
                        <div class="col-md-4">
                                <?php echo $form->dropDownList($model, 'to_group', array( '0' => yii::t('app','所有人'), '2' => yii::t('app','会员等级'), '3' => yii::t('app','储值用户') ) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('to_group'),'disabled'=>$a));?>
                                <?php echo $form->error($model, 'to_group' )?>
                        </div>
                </div>

                <?php if($model->to_group=="2"):{?>
                <div id="yincang" style="display: ;" class="form-group ">
                        <label class="col-md-3 control-label"><?php echo yii::t('app','会员等级');?></label>
                        <div class="col-md-4" style="border:1px solid red;">
                        <?php if($brdulvs) :{?>
                        <?php $i=1;?>
                        <?php foreach ($brdulvs as $brdulv):?>

                                <tr class="odd gradeX">
                                        <td><input type="checkbox" id="<?php echo $i;?>" class="checkboxes" <?php if(!empty($userlvs)){foreach ($userlvs as $userlv){if($userlv['brand_user_lid'] == $brdulv->lid) echo 'checked' ;}}else{echo 'kong';}?> value="<?php echo $brdulv->lid;?>" name="chk" /></td>
                                        <td><?php echo $i,$brdulv->level_name; ?></td>

                                </tr>
                        <?php $i=$i+1;?>
                        <?php endforeach;?>
                        <?php }endif;?>
                        </div>
                        <input type="hidden" id="hidden1" name="hidden1" value="" />
                </div>
        <?php }elseif($model->to_group!="2"):{?>
                        <div id="yincang" style="display:none ;" class="form-group ">
                        <label class="col-md-3 control-label"><?php echo yii::t('app','会员等级');?></label>
                        <div class="col-md-4" style="border:1px solid red;">
                        <?php if($brdulvs) :{?>
                        <?php $i=1;?>
                        <?php foreach ($brdulvs as $brdulv):?>

                                <tr class="odd gradeX">
                                        <td><input type="checkbox" id="<?php echo $i;?>" class="checkboxes" value="<?php echo $brdulv->lid;?>" name="chk" /></td>
                                        <td><?php echo $i,$brdulv->level_name; ?></td>

                                </tr>
                        <?php $i=$i+1;?>
                        <?php endforeach;?>
                        <?php }endif;?>
                        </div>
                        <input type="hidden" id="hidden1" name="hidden1" value="" />
                </div>
        <?php }endif;?>
        				<?php if(Yii::app()->user->role <=5):?>
		                <div class="form-group">
		                        <?php echo $form->label($model, yii::t('app','是否生效'),array('class' => 'col-md-3 control-label'));?>
		                        <div class="col-md-4">
		                                <?php echo $form->dropDownList($model, 'is_available', array('0' => yii::t('app','无效') , '1' => yii::t('app','只显示在POS机端'), '2' => yii::t('app','只显示在微信端'), '4' => yii::t('app','只显示在微信堂食'), '5' => yii::t('app','只显示在微信外卖'),'3' => yii::t('app','POS机及微信端都显示')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_available'),'disabled'=>$a));?>
		                                <?php echo $form->error($model, 'is_available' )?>
		                        </div>
		                </div><!-- 活动是否生效 -->
		                <?php else:?>
		                <div class="form-group">
		                        <?php echo $form->label($model, yii::t('app','是否生效'),array('class' => 'col-md-3 control-label'));?>
		                        <div class="col-md-4">
		                                <?php echo $form->dropDownList($model, 'is_available', array('0' => yii::t('app','无效') , '1' => yii::t('app','生效'),) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_available'),'disabled'=>$a));?>
		                                <?php echo $form->error($model, 'is_available' )?>
		                        </div>
		                </div><!-- 活动是否生效 -->
		                <?php endif;?>
						<div class="form-group">
                                <label class="control-label col-md-3"><?php echo yii::t('app','活动有效期限');?></label>
                                <div class="col-md-4">
                                        <!-- <div class="input-group date form_datetime" data-date="2012-12-21T15:25:00Z">                                       
                                                <input type="text" size="16" readonly class="form-control">
                                                <span class="input-group-btn">
                                                <button class="btn default date-reset" type="button"><i class="fa fa-times"></i></button>
                                                </span>
                                                <span class="input-group-btn">
                                                <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
                                                </span>
                                        </div> -->
                                         <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
                                                 <?php echo $form->textField($model,'begin_time',array('class' => 'form-control ui_timepicker','readonly'=>'true','style'=>'width:160px;','placeholder'=>$model->getAttributeLabel('begin_time'),'disabled'=>$a)); ?>
                                                 <span class="input-group-addon"> ~ </span>
                                                 <?php echo $form->textField($model,'end_time',array('class'=>'form-control ui_timepicker','readonly'=>'true','style'=>'width:160px;','placeholder'=>$model->getAttributeLabel('end_time'),'disabled'=>$a)); ?>
                                        </div> 
                                        <!-- /input-group -->
                                        <?php echo $form->error($model,'begin_time'); ?>
                                        <?php echo $form->error($model,'end_time'); ?>
                                </div>
                        </div>
                        <div id="" class="form-group ">
                        <label class="col-md-3 control-label"><?php echo yii::t('app','选择有效星期天数');?></label>
                        <div class="col-md-4" style="border:1px solid red;">

                                <tr class="odd gradeX">
                                        <td><input type="checkbox" id="7" disabled =<?php echo $a;?> class="checkboxes " <?php if(!empty($model)){$weekdayids = array();$weekdayids = explode(',',$model->weekday);if(in_array("7", $weekdayids)){echo "checked";}}else echo "123";?> value="7" name="week" /></td>
                                        <td><?php echo "星期日"; ?></td>
                                </tr>
                                <tr class="odd gradeX">
                                        <td><input type="checkbox" id="1" disabled =<?php echo $a;?> class="checkboxes " <?php if(!empty($model)){$weekdayids = array();$weekdayids = explode(',',$model->weekday);if(in_array("1", $weekdayids)){echo "checked";}}else echo "123";?> value="1" name="week" /></td>
                                        <td><?php echo "星期一"; ?></td>
                                </tr>
                                <tr class="odd gradeX">
                                        <td><input type="checkbox" id="2" disabled =<?php echo $a;?> class="checkboxes " <?php if(!empty($model)){$weekdayids = array();$weekdayids = explode(',',$model->weekday);if(in_array("2", $weekdayids)){echo "checked";}}else echo "123";?>  value="2" name="week" /></td>
                                        <td><?php echo "星期二"; ?></td>
                                </tr>
                                <tr class="odd gradeX">
                                        <td><input type="checkbox" id="3" disabled =<?php echo $a;?> class="checkboxes " <?php if(!empty($model)){$weekdayids = array();$weekdayids = explode(',',$model->weekday);if(in_array("3", $weekdayids)){echo "checked";}}else echo "123";?>  value="3" name="week" /></td>
                                        <td><?php echo "星期三"; ?></td>
                                </tr></br>
                                <tr class="odd gradeX">
                                        <td><input type="checkbox" id="4" disabled =<?php echo $a;?> class="checkboxes " <?php if(!empty($model)){$weekdayids = array();$weekdayids = explode(',',$model->weekday);if(in_array("4", $weekdayids)){echo "checked";}}else echo "123";?>  value="4" name="week" /></td>
                                        <td><?php echo "星期四"; ?></td>	
                                </tr>
                                <tr class="odd gradeX">
                                        <td><input type="checkbox" id="5" disabled =<?php echo $a;?> class="checkboxes " <?php if(!empty($model)){$weekdayids = array();$weekdayids = explode(',',$model->weekday);if(in_array("5", $weekdayids)){echo "checked";}}else echo "123";?>  value="5" name="week" /></td>
                                        <td><?php echo "星期伍"; ?></td>
                                </tr>
                                <tr class="odd gradeX">
                                        <td><input type="checkbox" id="6" disabled =<?php echo $a;?> class="checkboxes " <?php if(!empty($model)){$weekdayids = array();$weekdayids = explode(',',$model->weekday);if(in_array("6", $weekdayids)){echo "checked";}}else echo "123";?>  value="6" name="week" /></td>
                                        <td><?php echo "星期六"; ?></td>
                                </tr>
                        </div>
                        <input type="hidden" id="weekday" name="weekday" value="" />
                </div>
                <div class="form-group">
                                <label class="control-label col-md-3"><?php echo yii::t('app','优惠时段');?></label>
                                <div class="col-md-4">
                                         <div class="input-group input-large date-picker input-daterange" data-date="10:10" data-date-format="h:i">
                                                 <?php echo $form->timeField($model,'day_begin',array('class' => 'form-control ','style'=>'width:160px;','placeholder'=>$model->getAttributeLabel('day_begin'),'disabled'=>$a)); ?>
                                                 <span class="input-group-addon"> ~ </span>
                                                 <?php echo $form->timeField($model,'day_end',array('class'=>'form-control ','style'=>'width:160px;','placeholder'=>$model->getAttributeLabel('day_end'),'disabled'=>$a)); ?>
                                        </div> 
                                        <!-- /input-group -->
                                        <?php echo $form->error($model,'day_begin'); ?>
                                        <?php echo $form->error($model,'day_end'); ?>
                                </div>
                        </div>
                <div class="form-group">
                        <?php echo $form->label($model, yii::t('app','图文说明'),array('class' => 'col-md-3 control-label'));?>
                        <div class="col-md-8">
                                <?php echo $form->textArea($model, 'promotion_memo' , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('promotion_memo'),'disabled'=>$a));?>
                                <?php echo $form->error($model, 'promotion_memo' )?>
                        </div>
                </div><!-- 图文说明 -->
                <div class="form-actions fluid">
                        <div class="col-md-offset-3 col-md-9">
                                <button style="background-color: <?php if($a)echo 'silver !important;';?>" type="button" id="su" disabled = <?php echo $a;?> class="btn blue "><?php echo yii::t('app','确定并下一步');?></button>
                                <a href="<?php echo $this->createUrl('normalpromotion/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
                        </div>
                </div>
<?php $this->endWidget(); ?>
<?php $this->widget('ext.kindeditor.KindEditorWidget',array(
        'id'=>'NormalPromotion_promotion_memo',	//Textarea id
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

<script type="text/javascript">

 $(document).ready(function(){ 
        $('#NormalPromotion_to_group').change(function(){ 
        //alert($(this).children('option:selected').val()); 
        var p1=$(this).children('option:selected').val();//这就是selected的值 
               //alert(p1);
                if(p1=="2"){
                        $("#yincang").show();
                }else{
                       $("#yincang").hide();
                        }

        }); 

        var bgin_instance = mobiscroll.time('#NormalPromotion_day_begin', {
            theme: 'mobiscroll',
            lang: 'zh',
            display: 'center',
            headerText: false,
            maxWidth: 90
        });
        
         var end_instace = mobiscroll.time('#NormalPromotion_day_end', {
            theme: 'mobiscroll',
            lang: 'zh',
            display: 'center',
            headerText: false,
            maxWidth: 90
        });
    
}); 
		 
//	 	 function aa() {
//	 		 //alert(222);
//	          $(".chk").click(function() {
//	              var aa = document.getElementsByName("chk");
//	              var ss = "";
//	              alert(22);
//	              for (var i = 0; i < aa.length; i++) {
//	                  if (aa[i].checked) {
//	                      ss += aa[i].value;
//	                  }
//	              }
//	              $("#txt").val(ss);
//	              alert(22);
//	          });
//	      };

	     $("#su").on('click',function() {
	         //alert(11);
	         var p1 = $('#NormalPromotion_to_group').children('option:selected').val();
	         var begintime = $('#NormalPromotion_begin_time').val();
	         var endtime = $('#NormalPromotion_end_time').val();
	         var daybegin = $('#NormalPromotion_day_begin').val();
	         var dayend = $('#NormalPromotion_day_end').val();
	         var aa = document.getElementsByName("chk");
	         var weekday = document.getElementsByName("week");
	         var str=new Array();
	         var weekstr=new Array();
	         //alert(begintime);
	         //alert(dayend);
	         var dayendzero = "00:00";
	         //var ss = "";
	       // if(aa.checked){
	         if(endtime<=begintime){
	        	 alert("<?php echo yii::t('app','活动结束日期应该大于开始日期!!!');?>");
	        	 return false;
	         }
	         if(dayend > dayendzero){
		         if(dayend<=daybegin){
		        	 alert("<?php echo yii::t('app','每日优惠时段的结束时段应该大于开始时段!!!');?>");
		        	 return false;
		         }
	         }
	         if(p1=='2'){
	         for (var i = 0; i < aa.length; i++) {
	             if (aa[i].checked) {
	                 str += aa[i].value +',';
	             }
	         }
	         if(str!=''){
	         str = str.substr(0,str.length-1);//除去最后一个“，”
	         }else{
	        	 alert("<?php echo yii::t('app','请选择相应的会员等级！！！');?>");
	        	 return false;
	        	 }
	         }
	         for(var j = 0;j < weekday.length;j++){
	        	 if (weekday[j].checked) {
	                 weekstr += weekday[j].value +',';
	             }
	             }
	         if(weekstr!=''){
	             weekstr = weekstr.substr(0,weekstr.length-1);//除去最后一个“，”
	             }
	         //else{
	        //	 alert("<?php echo yii::t('app','请选择相应的会员等级！！！');?>");
	          //   }
	        // alert(str);
	      //  }else{
	        //alert(str);}
	         $("#weekday").val(weekstr);
	         $("#hidden1").val(str);
	         $("#normalpromotion-form").submit();
	     });
// 	   $('#category_container').on('change','.category_selecter',function(){
// 	   		var id = $(this).val();
// 	   		var $parent = $(this).parent();
//                         var sid ='0000000000';
//                         var len=$('.category_selecter').eq(1).length;
//                         if(len > 0)
//                         {
//                             sid=$('.category_selecter').eq(1).val();
//                             //alert(sid);
//                         }
                       
// 	   		$(this).nextAll().remove();
// 	   		$.ajax({
	   			//url:'<php echo $this->createUrl('product/getChildren',array('companyId'=>$this->companyId));?>/pid/'+id,
// 	   			type:'GET',
// 	   			dataType:'json',
// 	   			success:function(result){
// 	   				if(result.data.length){
// 	   					var str = '<select class="form-control category_selecter" tabindex="-1" name="category_id_selecter">'+
	   					//'<option value="">--'+"<php echo yii::t('app','请选择');?>"+'--</option>';
// 	   					$.each(result.data,function(index,value){
// 	   						str = str + '<option value="'+value.id+'">'+value.name+'</option>';
// 	   					});
// 	   					str = str + '</select>';
// 	   					$parent.append(str);
// 	   					$('#Product_category_id').val('');
// 	   					$parent.find('span').remove();
// 	   				}else{
//                                                 //if(selname == 'category_id_selecter2')
//                                                     $('#Product_category_id').val(sid);                                                
// 	   				}
// 	   			}
// 	   		});
// 	   });
	
		function swfupload_callback(name,path,oldname)  {
			//alert(6789);
			$("#NormalPromotion_main_picture").val(name);
			$("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
		}
	</script>