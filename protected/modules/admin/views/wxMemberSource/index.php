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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','微信会员'),'url'=>$this->createUrl('wechatMember/list' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','会员渠道'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('wechatMember/list' , array('companyId' => $this->companyId)))));?>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
        <div class="row">
        <?php $form=$this->beginWidget('CActiveForm', array(
                                'id' => 'wxMemberSource-form',
                                'action' => $this->createUrl('wxMemberSource/delete' , array('companyId' => $this->companyId)),
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
                             <?php echo yii::t('app','会员渠道');?>
                     </div>
                     <div class="actions">

                             <a href="<?php echo $this->createUrl('wxMemberSource/create', array('companyId' => $this->companyId));?>" class="btn blue">
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
                                <th>lid</th>
                                <th><?php echo yii::t('app','二维码');?></th>
                                <th><?php echo yii::t('app','渠道');?></th>
                                <th><?php echo yii::t('app','渠道说明');?></th> 
                                <th><?php echo yii::t('app','创建时间');?></th>
                                 <th><?php echo yii::t('app','更新时间');?></th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if($models) :?>
                        <?php foreach ($models as $model):?>
                            <tr class="odd gradeX">
                                <td>  
                                    <input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="sourceIds[]" /> 
                                </td>
                                <td ><?php echo $model->lid;?></td>
                                <td><?php if($model->qrcode):?>
                                    <img style="width:100px;" src="<?php echo '/test/./'.$model->qrcode;?>" />
                                        <?php endif;?><br />
                                    <a class="btn btn-xs blue" onclick="genQrcode(this);" href="javascript:;" lid="<?php echo $model->lid;?>">
                                        <i class="fa fa-qrcode"></i> 
                                        生成二维码
                                    </a>
                                </td>
                                <td ><?php echo $model->channel_name;?></td>
                                <td><?php echo $model->channel_comment;?></td>
                                <td ><?php echo $model->create_at;?></td>
                                <td ><?php echo $model->update_at;?></td>
                                <td class="center">
                                    <div class="actions">
                                        <?php if(Yii::app()->user->role < User::SHOPKEEPER) : ?>
                                        <!-- Yii::app()->params->master_slave=='m' -->
                                        <a  class='btn green' style="margin-top: 5px;" href="<?php echo $this->createUrl('wxMemberSource/update',array('companyId' => $this->companyId,'lid'=>$model->lid));?>">
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
	<script type="text/javascript">
	function genQrcode(that){
		id = $(that).attr('lid');
		var $parent = $(that).parent();
		$.get('<?php echo $this->createUrl('/admin/site/genWxQrcode',array('companyId'=>$this->companyId));?>/id/'+id,function(data){
			if(data.status){
				$parent.find('img').remove();
				$parent.prepend('<img style="width:100px;" src="/test/./'+data.qrcode+'">');
			}
			alert(data.msg);
		},'json');
	}

	</script>