<style>
    .page-content{
        padding-top:0!important;
    }
    .portlet.box > .portlet-body{
        min-height:auto!important;
    }
    .pay-message{
        margin:10px;
    }
    .pay-message div span{
        color:red;
        font-weight: 900;
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
    <!-- /.modal -->
    <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <!-- BEGIN PAGE HEADER-->
    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','短信套餐购买'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('product/list' , array('companyId' => $this->companyId,'type' => '2',)))));?>

    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box purple">
                <div class="portlet-title">
                    <div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','短信套餐剩余详情');?></div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                        <?php if ($infos==null): ?>
                        <tr>
                            <td><h3><?php echo yii::t('app','您还没有购买过短信套餐 , 或者 , 您购买的短信套餐已经过期 ! ! !');?></h3></td>
                        </tr>
                        <?php else: ?>
                        <thead>
                            <tr>
                                <th><?php echo yii::t('app','剩余数量/条');?></th>
                                <th ><?php echo yii::t('app','到期时间');?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($infos as $key => $info): ?>
                            <tr class="odd gradeX">
                                <td><?php echo yii::t('app',$info['odd_message_no']);?></td>
                                <td><?php echo yii::t('app',$info['downdate_at']);?></td>
                            </tr>
                            <?php endforeach ?>
                            
                        </tbody>
                        <?php endif; ?>
                    </table>
                </div>
                <div class="portlet-title">
                    <div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','短信套餐选择');?></div>
                </div>
                <div class="portlet-body" id="table-manage" style="overflow: hidden;">
                    <div class="row">
                        <?php if ($models==null): ?>
                        <div>
                            <h3>请通知壹点吃公司,设置短信套餐</h3>
                        </div>
                        <?php else: ?>
                        <?php foreach ($models as $key => $model): ?>
                        <div class="col-md-3 pay-message">
                            <div>数量/条 : <span><?php echo $model['all_message_no'] ?></span></div>
                            <div>赠送数量/条 :  <span><?php echo $model['send_message_no'] ?></span></div>
                            <div>使用年限/年 : <span> <?php echo $model['downdate'] ?></span></div>
                            <div>价格/元 : <span> <?php echo $model['money'] ?></span></div><br>

                            <a class="btn green"  <?php echo $this->createUrl('message/surepay',array('message_set_id'=>$model['lid'],'companyId'=>$this->companyId)); ?> >立即购买</a>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
			<!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>
	<!-- END PAGE CONTENT-->
</div>

<script type="text/javascript">

</script>

