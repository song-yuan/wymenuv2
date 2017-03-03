<style>
.radio{
            padding-top: 0px !important;
    }
    .item_list{
        padding-top: 7px;
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
<?php $this->widget('application.modules.admin.components.widgets.PageHeader', 
        array( 
            'breadcrumbs'=>array(
                                array('word'=>yii::t('app','微信会员'),'url'=>$this->createUrl('wechatMember/list' , array('companyId'=>$this->companyId,'type'=>0,))),
                                array('word'=>yii::t('app','会员商城'),'url'=>'')),
            'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('WxMemberShop/index' , array('companyId' => $this->companyId,'type'=>0)))));?>
<div class="row">
    <div class="col-md-12">
            <div class="portlet box blue">
                    <div class="portlet-title">
                            <div class="caption"><i class="fa fa-reorder"></i><?php echo yii::t('app','添加商品');?></div>
                            <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                            </div>
                    </div>
                    <div class="portlet-body form">
                            <!-- BEGIN FORM-->
                            <?php echo $this->renderPartial('_form', array('model'=>$model, "goods_category"=>$goods_category, "state"=>$state)); ?>
                            <!-- END FORM--> 
                    </div>
            </div>
    </div>
</div>
        
</div>

