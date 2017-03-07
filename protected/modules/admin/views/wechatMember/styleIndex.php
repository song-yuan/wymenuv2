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
                        'id' => 'CradImg-form',
                        //'action' => $this->createUrl('company/delete', array('companyId' => $this->companyId)),
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

                             <a href="<?php echo $this->createUrl('WechatMember/styleCreate', array('companyId' => $this->companyId));?>" class="btn red">
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
                                <div style="margin-top:100px;">
                                    <div class="btn-group" >
                                            <a type="submit"  class="btn blue"
                                               href="<?php echo $this->createUrl('WechatMember/styleUpdate',array('lid' => $model->lid,'companyId' => $this->companyId ));?>">
                                            <i class="fa fa-pencil"></i> 
                                            <?php echo yii::t('app','编辑');?>
                                            </a>
                                     </div>
                                     <div class="btn-group" >
                                        <a type="submit"  class="btn red" 
                                           href="<?php echo $this->createUrl('WechatMember/styleDelete',array('lid' => $model->lid,'companyId' => $this->companyId ));?>">
                                        <i class="fa fa-ban"></i> 
                                        <?php echo yii::t('app','删除');?>
                                        </a>
                                     </div>
                                </div>

                            </div>
                            <div class=" card_item">
                                <img  width="200" height="145" src="<?php echo $model->img_path;?>" />
                            </div>
                        </div>
                        <?php endforeach;?>
                        <?php endif;?>
                        <div style="clear: both;"></div>  
                    </div>
                </div>
            
            <!--    <div class="portlet-body" id="table-manage">
                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                        <thead>
                            <tr>
                                <th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
                                <th>lid</th>
                                <th><?php echo yii::t('app','卡等级');?></th>
                                <th><?php echo yii::t('app','卡图片');?></th>
                                <th><?php echo yii::t('app','创立时间');?></th>
                                <th><?php echo yii::t('app','更新时间');?></th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if($models) :?>
                        <?php foreach ($models as $model):?>
                            <tr class="odd gradeX">
                                <td>  
                                    <input type="checkbox" class="checkboxes" value="" name="companyIds[]" /> 
                                </td>
                                <td ><?php echo $model->lid;?></td>
                                <td ><?php echo $model->grade;?></td>
                                <td ><img width="100" src="<?php echo $model->img_path;?>" /></td>
                                <td><?php echo $model->create_at;?></td>
                                <td><?php echo $model->update_at;?></td>
                                <td class="center">
                                    <div class="actions">
                                        <?php if(Yii::app()->user->role < User::SHOPKEEPER) : ?>
                                       
                                        <a  class='btn green' style="margin-top: 5px;" href="<?php echo $this->createUrl('WechatMember/styleUpdate',array('companyId' => $this->companyId));?>">
                                            <?php echo yii::t('app','编辑');?>
                                        </a>
                                        <?php endif; ?>
                                       
                                    </div>	
                                </td>
                            </tr>
                        <?php endforeach;?>
                        <?php endif;?>
                        </tbody>
                </table>
      
            </div>-->
                   
              
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
