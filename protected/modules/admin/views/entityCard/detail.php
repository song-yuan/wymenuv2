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
    <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <!-- BEGIN PAGE HEADER-->
    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','实体卡'),'url'=>$this->createUrl('entityCard/list' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','会员流水'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('entityCard/list' , array('companyId' => $this->companyId)))));?>
	
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box purple">
                <div class="portlet-body" id="table-manage">
                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                        <thead>
                            <tr>
                               
                                <th><?php echo yii::t('app','会员卡号');?></th>
                                <th><?php echo yii::t('app','姓名');?></th>
                                <th><?php echo yii::t('app','等级');?></th>
                                <th><?php echo yii::t('app','性别');?></th>
                                <th><?php echo yii::t('app','生日');?></th>
                                <th><?php echo yii::t('app','联系方式');?></th>
                                <th><?php echo yii::t('app','金额');?></th>
                                <th><?php echo yii::t('app','积分');?></th>
                                <th><?php echo yii::t('app','状态');?></th>
                                <th><?php echo yii::t('app','折扣（生日折扣）');?></th>
                                <th><?php echo yii::t('app','操作');?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($models):?>
                            <?php foreach ($models as $model):?>
                            <tr>
                                   
                                    <td ><?php echo $model->selfcode;?></td>
                                    <td ><?php echo $model->name;?></td>
                                    <td ><?php echo $model->brandUserLevel?$model->brandUserLevel->level_name:'';?></td>
                                    <td ><?php if($model->sex=='m') echo '男';else echo '女';?></td>
                                    <td ><?php echo $model->birthday;?></td>
                                    <td ><?php echo $model->mobile;?></td>
                                    <td ><?php echo $model->all_money;?></td>
                                    <td ><?php echo $model->all_points;?></td>
                                    <td ><?php switch($model->card_status){case 0:echo '正常';break;case 1: echo "挂失";break;case 2: echo '注销';break;default:echo '';break;}?></td>
                                    <td ><?php echo sprintf("%.2f",$model->brandUserLevel?$model->brandUserLevel->level_discount:'1').'('.sprintf("%.2f",$model->brandUserLevel?$model->brandUserLevel->birthday_discount:'1').')';?></td>
                                    <td>
                                      <a  class='btn default green'  href="<?php echo $this->createUrl('EntityCard/consumedetail',array('lid'=>$model->lid,'companyId' => $this->companyId));?>">详情</a>
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