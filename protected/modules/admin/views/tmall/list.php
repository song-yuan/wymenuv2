<div class="page-content">

    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(
        array('word'=>yii::t('app','仓库列表'),'url'=>$this->createUrl('Storage/index' , array('companyId'=>$this->companyId))),
        array('word'=>yii::t('app','仓库设置'),'url'=>'')),
        'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('Storage/index' , array('companyId' => $this->companyId,)))));?>

    <div class="portlet purple box">
        <div class="portlet-title">
            <div class="caption"><i class="fa fa-cogs"></i><?php echo yii::t('app','仓库设置');?></div>
        </div>
        <div class="portlet-body clearfix">
            <div class="panel_body row">
                <p>供应商 && 仓库管理员信息设置</p>

                <div class="list col-sm-3 col-xs-12">
                    <a href="<?php echo $this->createUrl('SupplierClass/index',array('companyId'=>$this->companyId));?>">
                        <div class="list_big "><i class="fa fa-list-ol"></i> 供应商分类</div>
                        <div class="list_small">将不同的供应商根据需要类型建立以个分类</div>
                    </a>
                </div>
                <div class="list col-sm-3 col-xs-12">
                    <a href="<?php echo $this->createUrl('Supplier/index',array('companyId'=>$this->companyId));?>">
                        <div class="list_big "><i class="fa fa-list-ol"></i> 供应商信息</div>
                        <div class="list_small">添加新的供应商以及填写相应的供应商信息</div>
                    </a>
                </div>
                <div class="list col-sm-3 col-xs-12">
                    <a href="<?php echo $this->createUrl('Storekeeper/index',array('companyId'=>$this->companyId));?>">
                        <div class="list_big "><i class="fa fa-list-ol"></i> 仓库管理员</div>
                        <div class="list_small">添加、编辑、删除仓库管理员以及填写相应的管理员信息</div>
                    </a>
                </div>
            </div>

            <div class="panel_body row">
                <p>商城设置</p>

                <div class="list col-sm-3 col-xs-12">
                    <a href="<?php echo $this->createUrl('goods/index',array('companyId'=>$this->companyId));?>">
                        <div class="list_big "><i class="fa fa-list-ol"></i> 商品录入</div>
                        <div class="list_small">录入仓库的商品，操作商品的上下架</div>
                    </a>
                </div>
                <div class="list col-sm-3 col-xs-12">
                    <a href="<?php echo $this->createUrl('goodstock/goodsdelivery',array('companyId'=>$this->companyId));?>">
                        <div class="list_big "><i class="fa fa-list-ol"></i> 配货单</div>
                        <div class="list_small">根据审核后的发货单，到配货单进行配货</div>
                    </a>
                </div>
                <div class="list col-sm-3 col-xs-12">
                    <a href="<?php echo $this->createUrl('goodsinvoice/goodsinvoice',array('companyId'=>$this->companyId));?>">
                        <div class="list_big "><i class="fa fa-list-ol"></i> 发货（出库）单</div>
                        <div class="list_small">根据配货单生成出库单，在发货单内确认出库</div>
                    </a>
                </div>
                <div class="list col-sm-3 col-xs-12">
                    <a href="<?php echo $this->createUrl('goodsinvoice/goodsinvoice',array('companyId'=>$this->companyId));?>">
                        <div class="list_big "><i class="fa fa-list-ol"></i> 添加配送员</div>
                        <div class="list_small">添加指定的配送员进行送货服务</div>
                    </a>
                </div>
            </div>

            <div class="panel_body row">
                <p>采购与入库</p>

                <div class="list col-sm-3 col-xs-12">
                    <a href="<?php echo $this->createUrl('purchaseOrder/ckindex',array('companyId'=>$this->companyId));?>">
                        <div class="list_big "><i class="fa fa-list-ol"></i> 采购订单</div>
                        <div class="list_small">添加查询删除采购订单信息等</div>
                    </a>
                </div>
                <div class="list col-sm-3 col-xs-12">
                    <a href="<?php echo $this->createUrl('storageOrder/ckindex',array('companyId'=>$this->companyId));?>">
                        <div class="list_big "><i class="fa fa-list-ol"></i> 入库订单</div>
                        <div class="list_small">添加查询删除入库订单信息以及操作入库和状态等</div>
                    </a>
                </div>
                <div class="list col-sm-3 col-xs-12">
                    <a href="<?php echo $this->createUrl('nowmaterialstock/ckindex',array('companyId'=>$this->companyId));?>">
                        <div class="list_big "><i class="fa fa-list-ol"></i> 实时库存</div>
                        <div class="list_small">查看现有的原料商品库存储量</div>
                    </a>
                </div>

            </div>

            <div class="panel_body row">
                <p>库存的盘点与盘损</p>

                <div class="list col-sm-3 col-xs-12">
                    <a href="<?php echo $this->createUrl('',array('companyId'=>$this->companyId));?>">
                        <div class="list_big "><i class="fa fa-list-ol"></i> 盘损原因</div>
                        <div class="list_small">设置添加损耗商品的损耗原因</div>
                    </a>
                </div>
                <div class="list col-sm-3 col-xs-12">
                    <a href="<?php echo $this->createUrl('',array('companyId'=>$this->companyId));?>">
                        <div class="list_big "><i class="fa fa-list-ol"></i> 盘损</div>
                        <div class="list_small">查询所损耗的商品信息和损耗库存以及添加盘损原因</div>
                    </a>
                </div>
                <div class="list col-sm-3 col-xs-12">
                    <a href="<?php echo $this->createUrl('',array('companyId'=>$this->companyId));?>">
                        <div class="list_big "><i class="fa fa-list-ol"></i> 盘点</div>
                        <div class="list_small">按日周月查询商品所对应的库存信息</div>
                    </a>
                </div>
            </div>

            <div class="panel_body row">
                <p>库存的盘点与盘损记录</p>

                <div class="list col-sm-3 col-xs-12">
                    <a href="<?php echo $this->createUrl('',array('companyId'=>$this->companyId));?>">
                        <div class="list_big "><i class="fa fa-list-ol"></i> 盘点日志</div>
                        <div class="list_small">记录盘点的日期、负责人和盘点备注等信息</div>
                    </a>
                </div>
                <div class="list col-sm-3 col-xs-12">
                    <a href="<?php echo $this->createUrl('',array('companyId'=>$this->companyId));?>">
                        <div class="list_big "><i class="fa fa-list-ol"></i> 盘损日志</div>
                        <div class="list_small">记录盘损的日期、负责人和损耗原因等信息</div>
                    </a>
                </div>
                <div class="list col-sm-3 col-xs-12">
                    <a href="<?php echo $this->createUrl('',array('companyId'=>$this->companyId));?>">
                        <div class="list_big "><i class="fa fa-list-ol"></i> 库存日志</div>
                        <div class="list_small">查询日常库存的调整类型、调整时间以及状态等信息</div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
        <!-- END PAGE CONTENT-->
        <script>
            $(document).ready(function() {
                $('.relation').click(function(){
                    $('.modal').modal();
                });
            });
        </script>