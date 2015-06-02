        <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    
            </div>
            <div class="modal-body">    
            <div class="row">	
		<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo $siteName;?></div>
					
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
                                                            <th><?php echo yii::t('app','时间');?></th>
                                                            <th><?php echo yii::t('app','消息');?></th>
                                                            <th><?php echo yii::t('app','消息对象');?></th>
                                                            <th><?php echo yii::t('app','消息备注');?></th>
                                                            <th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
						<?php if($models):?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td ><?php echo $model->create_at;?></td>
                                                                <td><?php echo FeedBackClass::getFeedbackName($model->feedback_id,$this->companyId);?></td>
								<td><?php echo FeedBackClass::getFeedbackObject($model->order_id,$model->is_order,$this->companyId);?></td>
                                                                <td ><?php echo $model->feedback_memo;?></td>
                                                                <td class="center">
								<a href="#" orderfeedbackid="<?php echo $model->lid; ?>" class="btn-over btn green msg_sure_btn" ><i class="fa fa-check"></i><?php echo yii::t('app','确定');?></a>
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
									<?php echo yii::t('app','共');?> <?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?>  , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> , <?php echo yii::t('app','当前是第');?> <?php echo $pages->getCurrentPage()+1;?> <?php echo yii::t('app','页');?>
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
</div>
<div class="modal-footer">
        <button type="button" class="btn blue" id="msgmodalsure"> <?php echo yii::t('app','确 定');?> </button>
</div>
	<!-- END PAGE CONTENT-->
<script type="text/javascript">
    
    $('.msg_sure_btn').on('click',function(){
        var orderfeedbackid = $(this).attr('orderfeedbackid');
        if(orderfeedbackid=='0000000000')
        {
            return;
        }
        var thisbtn=$(this);
        $.get('<?php echo $this->createUrl('default/readfeedback',array('companyId'=>$this->companyId));?>/orderfeedbackid/'+orderfeedbackid,function(data){
                if(data.status) {
                        thisbtn.attr('orderfeedbackid','0000000000');
                        thisbtn.removeClass('green');
                        alert(<?php echo yii::t('app','操作成功');?>);                        
                } else {
                        alert(data.msg);
                }
        },'json');
    });
    
    $('#msgmodalsure').on('click',function(){
        $('#messagepartid').load('<?php echo $this->createUrl('default/message',array('companyId'=>$this->companyId));?>');
        $('#portlet-config3').modal('hide');
    });
    </script>