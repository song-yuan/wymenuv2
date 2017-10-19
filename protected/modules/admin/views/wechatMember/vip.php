<style>

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
<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','微信会员'),'url'=>$this->createUrl('wechatMember/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','vip会员'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('wechatMember/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
<div class="row">
     <?php $form=$this->beginWidget('CActiveForm', array(
                        'id' => 'vipmember-form',
                        'action' => $this->createUrl('WechatMember/vipdelete', array('companyId' => $this->companyId)),
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

                             <a href="<?php echo $this->createUrl('wechatMember/vipCreate', array('companyId' => $this->companyId));?>" class="btn blue">
                                 <i class="fa fa-pencil"></i> 
                                 <?php echo yii::t('app','添加');?>
                             </a>
                             <div class="btn-group">
                                     <button type="submit"  class="btn red" >
                                     <i class="fa fa-ban"></i> 
                                     <?php echo yii::t('app','删除');?>
                                     </button>
                             </div>
                     </div>
                </div>
                <div class="portlet-body" id="table-manage">
				<div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                        <thead>
                            <tr>
                                <th class="table-checkbox">
                                    <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" />
                                </th>
                                <th><?php echo yii::t('app','等级名称');?></th>
                                <th><?php echo yii::t('app','会员折扣');?></th>
                              	<th><?php echo yii::t('app','生日折扣');?></th>
                                <th><?php echo yii::t('app','最低充值金额');?></th>
                                <th><?php echo yii::t('app','工本费');?></th>
                                <th><?php echo yii::t('app','卡图片');?></th>
                                <th><?php echo yii::t('app','等级最低积分');?></th>
                                <th><?php echo yii::t('app','等级最高积分');?></th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if($models) :?>
                        <?php foreach ($models as $model):?>
                            <tr class="odd gradeX">
                                <td>  
                                    <input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="vipIds[]" /> 
                                </td>
                                <td ><?php echo $model->level_name;?></td>
                                <td ><?php echo $model->level_discount;?></td>
                                <td ><?php echo $model->birthday_discount;?></td>
                                <td ><?php echo $model->min_charge_money;?></td>
                                <td ><?php echo $model->card_cost;?></td>
                                <td><img width="100" src="<?php echo $model->bgimg;?>" /></td>
                                <td ><?php echo $model->min_total_points;?></td>
                                <td ><?php echo $model->max_total_points;?></td>
                                <td class="center">
                                    <div class="actions">
                                        <?php if(Yii::app()->user->role < User::SHOPKEEPER) : ?>
                                        <!-- Yii::app()->params->master_slave=='m' -->
                                        <a  class='btn green' style="margin-top: 5px;" href="<?php echo $this->createUrl('wechatMember/vipUpdate',array('companyId' => $this->companyId,'lid'=>$model->lid));?>">
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
      				</div>
                </div>
                   
              
            </div>
    </div>
<?php $this->endWidget(); ?>
</div>        
</div>
