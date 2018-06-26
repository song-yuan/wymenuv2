<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box purple">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-globe">
                    </i><?php echo yii::t('app','缺货库存列表');?>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                </div>
            </div>
            <div class="portlet-body" id="table-manage">
                <div class="dataTables_wrapper form-inline">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="sample_1">
                            <thead>
                            <tr>
                                <th class="table-checkbox"></th>
                                <th style="width:16%"><?php echo yii::t('app','原料编号');?></th>
                                <th ><?php echo yii::t('app','原料名称');?></th>
                                <th ><?php echo yii::t('app','类型');?></th>
                                <th><?php echo yii::t('app','实时库存');?></th>
                                <th><?php echo yii::t('app','单位');?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if($models) :?>
                                <?php foreach ($models as $model):?>
                                    <tr class="odd gradeX">
                                        <td></td>
                                        <td><?php echo $model->material_identifier;?></td>
                                        <td ><?php echo $model->material_name;?></td>
                                        <td><?php if(!empty($model->category->category_name)) echo $model->category->category_name;?></td>
                                        <td ><?php echo ProductMaterial::getCaution($model->lid,$model->dpid);?></td>
                                        <!--<td><?php /*echo $M->category_name;*/?></td>
                                        <td><?php /*echo $M->stock_all;*/?></td>-->
                                        <td ><?php echo Common::getStockName($model->sales_unit_id);?></td>
                                    </tr>
                                <?php endforeach;?>
                            <?php endif;?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <span style="margin-left: 16px;color: #ff0000">注意：没有库存数量是不需要添加的</span>
                <button type="button" data-dismiss="modal" class="btn blue" style="margin-left: 87%">确定</button>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>

</div>