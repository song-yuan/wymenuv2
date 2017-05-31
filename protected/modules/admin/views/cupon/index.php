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
.min_date span{
    color: red;
}
.edit_del{
    margin-top:10px;display: none;
}
.edit_del .edit{
    margin-right:10px;
}
 .show{
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
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet box purple">
            <div class="portlet-title">
                    <div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','系统券设置');?></div>
                    <div class="actions">
                            <a href="<?php echo $this->createUrl('cupon/create' , array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加代金券');?></a>   
                    </div>		
            </div>
        <div class="portlet-body" id="table-manage">
        
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
                             <?php if($model->type_dpid == '2'):?>
                             <span style="color: red;border: 1px solid red;border-radius: 14px;float: right;" class="type" >限</span>
                             <?php endif;?>
                             <?php if($model->type_prod == '1' ):?>
                             <span style="color: #eafb03;border: 1px solid red;border-radius: 14px;float: right;" class="type" >限</span>
                             <?php endif;?>
                         </div>
                         <div class="min_date">
                             <div  class="min">满
                                 <span>
                                 <?php echo floor($model->min_consumer);?>
                                 </span>
                                 元可用
                             </div>
                             <?php if($model->time_type=='1'):?>
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
                             <?php else:?>
                             <div class="date">
                                 领取
                                 <span>
                                     <?php echo $model->day_begin?$model->day_begin:'当';?>天
                                 </span> 
                                 生效，有效天数：
                                 <span>
                                     <?php echo $model->day;?>天
                                 </span>  
                                 
                             </div>
                             <?php endif;?> 
                         </div>

                     </div>
                     <div class="edit_del" style="">
                         <div class="btn-group edit" style="" >
                             <a type=""  class="btn blue"  
                                href="<?php echo $this->createUrl('cupon/update',array('lid' => $model->lid , 'companyId' => $model->dpid));?>">
                             <?php echo yii::t('app','编辑');?>
                             </a>
                         </div>

                         <div class="btn-group edit" >
                             <a type="submit"  class="btn red"
                                href="<?php echo $this->createUrl('cupon/delete',array('lid' => $model->lid , 'companyId' => $model->dpid));?>">
                             <?php echo yii::t('app','删除');?>
                             </a>
                         </div>  
                         <div class="btn-group" >
                             <a type="submit"  class="btn green"
                             	href="<?php echo $this->createUrl('cupon/detailinfo',array('lid' => $model->lid ,'code' => $model->sole_code, 'companyId' => $model->dpid));?>">
                             <?php echo yii::t('app','详情');?>
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
        

   
		<?php $this->endWidget(); ?>
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
            $(this).siblings().find(".edit_del").removeClass("show");
            if($(this).find(".edit_del").hasClass("show"))
            {
               $(this).find(".edit_del").removeClass("show"); 
            }else{
              $(this).find(".edit_del").addClass("show");
          }
        });    
            
            
            
});
</script>