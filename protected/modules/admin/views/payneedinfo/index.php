  <?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/PCASClass.js');?>
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
	<div id="main2" name="main2" style="min-width: 500px;min-height:300px;display:none;" onMouseOver="this.style.backgroundColor='rgba(255,222,212,1)'" onmouseout="this.style.backgroundColor=''">
		<div id="content"></div>
	</div>
	
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','店铺管理'),'url'=>$this->createUrl('companyset/list' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','入网资料'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('companyset/list' , array('companyId' => $this->companyId,)))));?>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
            <?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'company-form',
				'action' => $this->createUrl('company/delete', array('companyId' => $this->companyId)),
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','入网资料');?></div>
					<div class="actions">
                                            <a href="<?php echo $this->createUrl('payneedinfo/create', array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加');?></a>
                                            <div class="btn-group">
                                            <button type="submit"  class="btn red" ><i class="fa fa-ban"></i> <?php echo yii::t('app','删除');?></button>
                                            </div>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th><?php echo yii::t('app','店铺ID');?></th>
                                                                <th><?php echo yii::t('app','商户名称');?></th>
								<th><?php echo yii::t('app','联系人');?></th>
								<th><?php echo yii::t('app','联系电话');?></th>
								<th><?php echo yii::t('app','店铺地址');?></th>
								<th><?php echo yii::t('app','开户行');?></th>
                                                                <th><?php echo yii::t('app','开户地址');?></th>
                                                                <th><?php echo yii::t('app','开户支行');?></th>
                                                                <th><?php echo yii::t('app','开户账号')?></th>
                                                                <th><?php echo yii::t('app','户名');?></th>
								<th><?php echo yii::t('app','创建时间');?></th>
							</tr>
                                                       
                                                        <tr>
                                                                <?php if($model->status==2):?>
                                                                <td colspan="12" style="color: red;text-align:right;">待审核...</td>
                                                                <?php endif;?>
                                                                <?php if($model->status==3):?>
                                                                <td colspan="12" style="color: red;text-align:right;">待激活...</td>
                                                                <?php endif;?>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="12" style="text-align:right;">
                                                                <input type="button" id="da" value="打印"/>
                                                            </td>
                                                        </tr>
					</table>
					</div>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
            <?php $this->endWidget(); ?>
	</div>
        <script>
            $(function(){
                $('#da').click(function(){
                    alert('<?php print_r($model);?>');
                });
            });
       </script>