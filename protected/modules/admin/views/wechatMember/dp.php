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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','店铺列表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('wechatMember/createdp' , array('num'=>$num,'companyId' => $this->companyId,)))));?>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
            <?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'company-form',
				'action' => $this->createUrl('wechatMember/deletedp', array('num'=>$num,'companyId' => $this->companyId)),
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','店铺列表');?></div>
					<div class="actions">
                        <?php if(Yii::app()->params->master_slave=='m') : ?>
						<?php if(Yii::app()->user->role == User::POWER_ADMIN||Yii::app()->user->role <= User::ADMIN_AREA):?>
							<div class="btn-group">
	                            <button type="submit"  class="btn red" ><i class="fa fa-ban"></i> <?php echo yii::t('app','删除');?></button>
	                        </div>
						<?php endif;?>
						<!-- <div class="btn-group">
							<a class="btn green" href="#" data-toggle="dropdown">
							<i class="fa fa-cogs"></i> Tools
							<i class="fa fa-angle-down"></i>
							</a>
							<ul class="dropdown-menu pull-right">
								<li><a href="#"><i class="fa fa-ban"></i> <?php echo yii::t('app','冻结');?></a></li>
							</ul>
						</div> -->
                                                
                                            <?php endif; ?>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr class="table-checkbox">
                            <th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" id="checkedAll"/></th>
                            <th>ID</th>
                            <th><?php echo yii::t('app','店铺名称');?></th>
                            <th><?php echo yii::t('app','管理员ID');?></th>
                            <th><?php echo yii::t('app','管理店铺ID');?></th>
                            <th><?php echo yii::t('app','创建时间');?></th>
                        </tr>
						</thead>
						<tbody>
						<?php foreach($models as $model):?>
                        <tr class="odd gradeX">
                            <td><input type="checkbox" class="checkboxes" name="checkbox_name[]" value="<?php echo $model['lid'];?>" /></td>
                            <td><?php echo $model['lid'];?></td>
                            <td><?php echo $model['company_name'];?></td>
                            <td><?php echo $model['brand_user_id'];?></td>
                            <td><?php echo $model['admin_dpid'];?></td>
                            <td><?php echo $model['create_at'];?></td>
                        </tr>
                    <?php endforeach;?>
						</tbody>
					</table>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
            <?php $this->endWidget(); ?>
	</div>
	<!-- END PAGE CONTENT-->