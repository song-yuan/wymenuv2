<div class="page-content">

    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','供应链'),'url'=>''))));?>

    <div class="portlet purple box">
        <div class="portlet-title">
            <div class="caption"><i class="fa fa-cogs"></i><?php echo yii::t('app','供应链');?></div>
        </div>
        <div class="portlet-body clearfix">
            <div class="panel_body row">
                <p>销售单</p>

                <div class="list col-sm-3 col-xs-12">
                    <a href="<?php echo $this->createUrl('goodsorder/index',array('companyId'=>$this->companyId));?>">
                        <div class="list_big "><i class="fa fa-list-ol"></i> 销售订单列表</div>
                        <div class="list_small">操作销售订单，以及显示销售订单的状态</div>
                    </a>
                </div>
            </div>

            <div class="portlet-body clearfix">
                <div class="panel_body row">
                    <p>仓库管理</p>
                    <div class="list col-sm-3 col-xs-12">
                        <a href="<?php echo $this->createUrl('Storage/index',array('companyId'=>$this->companyId));?>">
                            <div class="list_big "><i class="fa fa-list-ol"></i> 内仓</div>
                            <div class="list_small">把采购入库和销售集合在一块，不假借于其他</div>
                        </a>
                    </div>
                    <div class="list col-sm-3 col-xs-12">
                        <a href="<?php echo $this->createUrl('',array('companyId'=>$this->companyId));?>">
                            <div class="list_big "><i class="fa fa-list-ol"></i> 外仓</div>
                            <div class="list_small">把销售订单中的商品转嫁给第三方，让其采购并销售运输</div>
                        </a>
                    </div>
                </div>

            <div class="panel_body row">
                <p>价格体系</p>
                <div class="list col-sm-3 col-xs-12">
                    <a href="<?php echo $this->createUrl('peisonggroup/index',array('companyId'=>$this->companyId));?>">
                        <div class="list_big "><i class="fa fa-th-list"></i> 配送体系及对应</div>
                        <div class="list_small">配送体系及对应 对应指定的仓库以及店铺不同门店不同原料从相同或不同仓库配送；原料商品展示出来的商品还对应着不同的配送体系一样或不一样的商品有着相同或不同的配送体系</div>
                    </a>
                </div>
                <div class="list col-sm-3 col-xs-12">
                    <a href="<?php echo $this->createUrl('pricegroupM/index',array('companyId'=>$this->companyId));?>">
                        <div class="list_big "><i class="fa fa-th-list"></i> 原料商品价格体系</div>
                        <div class="list_small">原料商品价格体系的品牌定价对应的是前台页面商品显示的价格，而原料商品列表的品牌定价对应的是商品的实际价格</div>
                    </a>
                </div>
                <div class="list col-sm-3 col-xs-12">
                    <a href="<?php echo $this->createUrl('goods/indexComp',array('companyId'=>$this->companyId));?>">
                        <div class="list_big "><i class="fa fa-th-list"></i> 原料商品列表</div>
                        <div class="list_small">原料商品列表对应的是前台的商品展示</div>
                    </a>
                </div>
            </div>

            <div class="panel_body row">
                <p>其他</p>
                <div class="list col-sm-3 col-xs-12">
                    <a href="<?php echo $this->createUrl('materialAd/index',array('companyId'=>$this->companyId));?>">
                        <div class="list_big "><i class="fa fa-th-list"></i> 原料商城轮播广告</div>
                        <div class="list_small">原料商城轮播广告对应前台首页的轮播图，可以修改</div>
                    </a>
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