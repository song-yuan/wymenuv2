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
<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','微信会员'),'url'=>$this->createUrl('wechatMember/list' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','模板消息'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('wechatMember/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
<div class="row">
     <?php $form=$this->beginWidget('CActiveForm', array(
                        'id' => 'WeixinMessagetpl-form',
                        'action' => $this->createUrl('wxMessage/delete', array('companyId' => $this->companyId)),
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
                    <?php echo yii::t('app','模板消息');?>
                </div>
                <div class="actions">
                    <a href="<?php echo $this->createUrl('wxMessage/create', array('companyId' => $this->companyId));?>" class="btn blue">
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
                <table class="table table-striped table-bordered table-hover" id="sample_1">
                    <thead>
                        <tr>
                            <th class="table-checkbox">
                                <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" />
                            </th>
                            <th><?php echo yii::t('app','模板消息类型');?></th>
                            <th><?php echo yii::t('app','模板消息ID');?></th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if($models) :?>
                    <?php foreach ($models as $model):?>
                            <tr class="odd gradeX">
                                <td>  
                                    <input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="Ids[]" /> 
                                </td>
                                <td><?php echo $model->message_type?'代金券领取消息':'支付成功消息';?></td>
                                <td><?php echo $model->message_tpl_id;?></td>
                                <td class="center">
                                    <div class="actions">
                                        <?php if(Yii::app()->user->role < User::SHOPKEEPER) : ?>
                                        <a  class='btn green' style="margin-top: 5px;" href="<?php echo $this->createUrl('wxMessage/update',array('companyId' => $this->companyId,'lid'=>$model->lid));?>">
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
    </div>
<?php $this->endWidget(); ?>
</div>        
</div>

