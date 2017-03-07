<style>
    .card_body{
        margin-top: 30px;
        margin-left: 30px;
    }  
    .vip-card-list{
        margin-right: 50px;
        margin-bottom: 50px;
    }
    .card-edit-active{
        display:block!important;
    }
    .card_item img{
        border-radius: 6px;
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
<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','微信会员'),'url'=>$this->createUrl('wechatMember/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','会员卡样式'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('wechatMember/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
<div class="row">
     <?php $form=$this->beginWidget('CActiveForm', array(
                        'id' => 'MemberWxcardStyle-form',
                        //'action' => $this->createUrl('WxCardStyle/delete', array('companyId' => $this->companyId)),
                        'errorMessageCssClass' => 'help-block',
                        'htmlOptions' => array(
                                'class' => 'form-horizontal',
                                'enctype' => 'multipart/form-data'
                        ),
        )); ?>
    <div class="col-md-12">

        <div class="portlet purple box">
           
                 <div class="portlet-title">
                     <div class="caption">
                             <i class="fa fa-globe"></i>
                             <?php echo yii::t('app','vip会员');?>
                     </div>
                     <div class="actions">

                             <a href="<?php echo $this->createUrl('wxCardStyle/create', array('companyId' => $this->companyId));?>" class="btn blue">
                                 <i class="fa fa-pencil"></i> 
                                 <?php echo yii::t('app','添加');?>
                             </a>
                     </div>
                </div>
                <div class="portlet-body">
                    <div class="card_body">
                    <?php if($models) :?>
                        <?php foreach ($models as $model):?>
                        <div class="vip-card-list "style="float:left" >
                            <div class="edit_del " style=" display: none;text-align: center;position:absolute;  width:200px;height:145px">
                                <div style="margin-top:90px;">
                                    <div class="btn-group" >
                                            <a type="submit"  class="btn blue"
                                               href="<?php echo $this->createUrl('WxCardStyle/update',array('lid' => $model->lid,'companyId' => $this->companyId ));?>">
                                            <i class="fa fa-pencil"></i> 
                                            <?php echo yii::t('app','编辑');?>
                                            </a>
                                     </div>
                                     <div class="btn-group" >
                                        <a type="submit"  class="btn red" 
                                           href="<?php echo $this->createUrl('WxCardStyle/delete',array('lid' => $model->lid,'companyId' => $this->companyId ));?>">
                                        <i class="fa fa-ban"></i> 
                                        <?php echo yii::t('app','删除');?>
                                        </a>
                                     </div>
                                </div>

                            </div>
                            <div class=" card_item">
                                <img  width="200" height="130" src="<?php echo $model->bg_img;?>" />
                            </div>
                        </div>
                        <?php endforeach;?>
                        <?php endif;?>
                        <div style="clear: both;"></div>  
                    </div>
                </div>
            
     
              
            </div>
    </div>
<?php $this->endWidget(); ?>
</div>
        
</div>
<script>
    $(function(){
      $(".vip-card-list").click( function () {
          $(this).siblings().find(".edit_del").removeClass("card-edit-active");
          if($(this).find(".edit_del").hasClass("card-edit-active")){
             $(this).find(".edit_del").removeClass("card-edit-active"); 
          }else{
            $(this).find(".edit_del").addClass("card-edit-active");
        }
         });
    });
</script>
