<link type="text/css" href="<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-1.8.17.custom.css');?>" rel="stylesheet" />
<link type="text/css" href="<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-timepicker-addon.css');?>" rel="stylesheet" />
<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-1.7.1.min.js');?>"></script>
<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-1.8.17.custom.min.js');?>"></script>
<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-addon.js');?>"></script>
<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-zh-CN.js');?>"></script>

<link href="<?php echo Yii::app()->request->baseUrl;?>/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo Yii::app()->request->baseUrl;?>/plugins/bootstrap-modal/js/bootstrap-modalmanager.js" type="text/javascript" ></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/plugins/bootstrap-modal/js/bootstrap-modal.js" type="text/javascript" ></script>
<style>
.portlet.box > .portlet-body{
    padding-left: 30px!important;
    padding-top: 25px!important;
}
.cupon_list{
    width: 230px;
    height:175px;
    margin-top: 5px;
    margin-right: 23px;
    float: left;
}
.cupon_item{
    height: 115px;
    border-radius: 5px;
    border:1px dashed white;
    background-color: #EEA2AD;
}
.money_type{
    padding-left:5px;color: white;
}
.money_type .money{
    font-size: 48px;font-weight: bold;
}
.money_type .type{
    font-size: 20px;font-weight: bold;
}
.min_date{
    padding-left:5px;color:#222;
}
.min_date .min{
   font-size: 14px;text-align:left; 
}
.min_date .date{
    font-size: 14px;text-align: left;
}
.edit_del{
    margin-top:10px;display: none;
}
.edit_del .edit{
    margin-right:10px;
}
 .active{
        display:block!important;
    }

</style>

<!-- BEGIN PAGE -->
<div class="page-content">
	<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
	<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
			<!-- /.modal -->
			<!-- END BEGIN STYLE CUSTOMIZER -->            
                <!-- BEGIN PAGE HEADER-->
        <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array( 'breadcrumbs'=>array(array('word'=>yii::t('app','微信赠券'),'url'=>$this->createUrl('wechatMarket/list' , array('companyId'=>$this->companyId,'type'=>1,))),array('word'=>yii::t('app','系统券'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('wechatMarket/list' , array('companyId' => $this->companyId,'type'=>1)))));?>
                <!-- END PAGE HEADER-->
                <!-- BEGIN PAGE CONTENT-->
        <div class="row">
        <?php $form=$this->beginWidget('CActiveForm', array(
                        'id' => 'cupon-form',
                        'action' => $this->createUrl('cupon/delete' , array('companyId' => $this->companyId,)),
                        'errorMessageCssClass' => 'help-block',
                        'htmlOptions' => array(
                                'class' => 'form-horizontal',
                                'enctype' => 'multipart/form-data'
                        ),
        )); ?>
        <div class="col-md-12">
        <div class="tabbable tabbable-custom">
                <ul class="nav nav-tabs">
                        <!--<li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('cashcard/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','整体设置');?></a></li>
                        <li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('normalpromotion/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','普通优惠');?></a></li>
                        <li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('privatepromotion/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','特价优惠');?></a></li>
                        <li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('fullSentPromotion/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','满送优惠');?></a></li>
                        <li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('fullMinusPromotion/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','满减优惠');?></a></li> 
                        <li class="active"><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('cupon/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','代金券');?></a></li>
                        <li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('gift/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','礼品券');?></a></li>
                        <li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('wxcard/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','微信卡券');?></a></li>-->
                </ul>
		
                <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet box purple">
                        <div class="portlet-title">
                                <div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','系统券设置');?></div>
                                <div class="actions">
                                <!-- <p><input type="text" name="datetime" class="ui_timepicker" value=""></p> -->
                                        <a href="<?php echo $this->createUrl('cupon/create' , array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加代金券');?></a>
                                <!--	<div class="btn-group">
                                                <button type="submit"  class="btn red" ><i class="fa fa-ban"></i> <?php echo yii::t('app','删除代金券');?></button>
                                        </div>-->
                                </div>
					
					
                        </div>
                        <div class="portlet-body" id="table-manage">
                        <!--	<table class="table table-striped table-bordered table-hover" id="sample_1">
                                        <thead>
                                                <tr>
                                                        <th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
                                                        <th><?php echo yii::t('app','代金券名称');?></th>
                                                        <th><?php echo yii::t('app','摘要');?></th>
                                                        <th><?php echo yii::t('app','代金券金额');?></th>
                                                        <th><?php echo yii::t('app','最低消费');?></th>
                                                        <th><?php echo yii::t('app','兑换积分');?></th>
                                                        <th><?php echo yii::t('app','生效开始日期');?></th>
                                                        <th><?php echo yii::t('app','生效结束日期');?></th>
                                                        <th><?php echo yii::t('app','是否有效');?></th>
                                                        <th><?php echo yii::t('app','编辑');?></th>                                                                
                                                        <th><?php echo yii::t('app','发放');?></th> 
                                                        <th><?php echo yii::t('app','备注');?></th>

                                                </tr>
                                        </thead>
                                        <tbody>
                                        <?php if($models) :?>


                                        <?php foreach ($models as $model):?>
                                                        <tr class="odd gradeX">
                                                        <td><input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="ids[]" /></td>
                                                        <td><?php echo $model->cupon_title; ?></td>
                                                        <td><?php echo $model->cupon_abstract;?></td>
                                                        <td><?php echo $model->cupon_money;?></td>
                                                        <td><?php echo $model->min_consumer;?></td>
                                                        <td><?php echo $model->change_point;?></td>
                                                        <td><?php echo $model->begin_time;?></td>
                                                        <td><?php echo $model->end_time;?></td>
                                                        <td><?php switch ($model->is_available){case 0:echo yii::t('app','有效');break;case 1:echo yii::t('app','无效');break;default:echo '';break;} ?></td>
                                                        <td class="center">
                                                        <a href="<?php echo $this->createUrl('cupon/update',array('lid' => $model->lid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','编辑');?></a></td>
                                                        <td class="center">
                                                        <a href="javascript:;" class="sent" data-id="<?php echo $model->lid ;?>"><?php echo yii::t('app','发放');?></a> </td> 
                                                         <td><?php echo '';?></td>
                                                        </tr>

                                        <?php endforeach;?>	
                                        <?php endif;?>
                                        </tbody>

                                </table>-->
  <div>
        <?php if($models) :?>
           <?php foreach ($models as $model):?>
           <div class="cupon_list" >                                           
               <div class="cupon_item">
                   <div class="money_type" >
                       <span  class="money" >
                       <?php echo floor($model->cupon_money);?>
                       </span>
                       <span class="type" >元代金券</span>
                   </div>
                   <div class="min_date">
                       <div  class="min">满
                           <span>
                           <?php echo floor($model->min_consumer);?>
                           </span>
                           元可用
                       </div>
                       <div class="date">
                           限
                           <span>
                               <?php echo date('Y-m-d',strtotime($model->begin_time));?>
                           </span> 
                           至
                           <span>
                               <?php echo date('Y-m-d',strtotime($model->end_time));?>
                           </span>  
                           使用
                       </div> 
                   </div>

               </div>
               <div class="edit_del" style="">
                   <div class="btn-group edit" style="" >
                       <a type="submit"  class="btn blue"  
                          href="<?php echo $this->createUrl('cupon/update',array('lid' => $model->lid , 'companyId' => $model->dpid));?>">
                       <?php echo yii::t('app','编辑');?>
                       </a>
                   </div>
                     
                   <div class="btn-group" >
                       <a type="submit"  class="btn red"
                          href="<?php echo $this->createUrl('cupon/delete',array('lid' => $model->lid , 'companyId' => $model->dpid));?>">
                       <?php echo yii::t('app','删除');?>
                       </a>
                   </div>                                           
               </div>
           </div>                                   
           <?php endforeach;?>	
           <?php endif;?>
      <div style="clear:both"></div>
    </div>
                                       
 

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
                   </div>
                   <!-- END EXAMPLE TABLE PORTLET-->
           </div>

        </div>
		<?php $this->endWidget(); ?>
		</div>
		
</div>					<!-- END EXAMPLE TABLE PORTLET-->
</div>
<div id="responsive" class="modal fade" tabindex="-1" aria-hidden="true">
    <div id="ajax-modal" class="modal fade" tabindex="-1"  style="width:600px;">
    </div>
    <div class="modal-dialog">
        <div class="modal-content">

        </div>
    </div>
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
        var $modal = $('#ajax-modal');
        $('.sent').on('click',function(){
            var cuponid = $(this).attr('data-id');
            $modal.load('<?php echo $this->createUrl('/admin/cupon/sentCupon',array('companyId'=>$this->companyId));?>/cuponid/'+cuponid, '', function(){
                $modal.modal();
            });
        });
	$(".cupon_list").click( function () {
            $(this).siblings().find(".edit_del").removeClass("active");
            if($(this).find(".edit_del").hasClass("active"))
            {
               $(this).find(".edit_del").removeClass("active"); 
            }else{
              $(this).find(".edit_del").addClass("active");
          }
        });    
            
            
            
});
</script>