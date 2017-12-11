<div class="page-content">
<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','营销活动'),'url'=>''))));?>
   

        <div class="portlet purple box">
			<div class="portlet-title">
				<div class="caption"><i class=" fa fa-gift"></i>营销活动</div>
			</div> 
            <div class="portlet-body clearfix" >
                <div class="panel_body row">
                <p>基础设置</p>
                    <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                        <a href="">
                            <div class="list_big">设置</div>
                            <div class="list_small"></div>
                        </a> 
                    </div>
                    <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                        <a href="<?php echo $this->createUrl('discount/index',array('companyId' => $this->companyId))?>">
                            <div class="list_big">POS折扣模板</div>
                            <div class="list_small">设置POS端的结账打折</div>
                        </a> 
                    </div>
                 </div>
                <div class="panel_body row">
                    <p>优惠活动</p>
                    <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                        <a href="<?php echo $this->createUrl('normalpromotion/index',array('companyId' => $this->companyId))?>">
                            <div class="list_big">普通优惠</div>
                            <div class="list_small">设置商品打折活动</div>
                        </a> 
                    </div>
                    <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                        <a href="<?php echo $this->createUrl('fullSentPromotion/index',array('companyId' => $this->companyId))?>">
                            <div class="list_big">满送优惠</div>
                            <div class="list_small">设置满多少送什么活动</div>
                        </a> 
                    </div>
                    <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                        <a href="<?php echo $this->createUrl('fullMinusPromotion/index',array('companyId' => $this->companyId))?>">
                            <div class="list_big">满减优惠</div>
                            <div class="list_small">设置满多少减多少活动</div>
                        </a> 
                    </div>
                    <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                        <a href="<?php echo $this->createUrl('buysentpromotion/index',array('companyId' => $this->companyId))?>">
                            <div class="list_big">买送</div>
                            <div class="list_small">设置买什么送什么活动</div>
                        </a> 
                    </div>
                    <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                        <a href="">
                            <div class="list_big">买减</div>
                            <div class="list_small"></div>
                        </a> 
                    </div>
                </div>   
        </div>             
    </div>
</div>
