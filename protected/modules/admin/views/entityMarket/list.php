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
                            <div class="list_small">设置POS机中菜品折扣的信息模板</div>
                        </a> 
                    </div>
                 </div>
                <div class="panel_body row">
                    <p>优惠活动</p>
                    <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                        <a href="<?php echo $this->createUrl('normalpromotion/index',array('companyId' => $this->companyId))?>">
                            <div class="list_big">普通优惠</div>
                            <div class="list_small">添加所要举办的优惠活动信息及折扣力度等</div>
                        </a> 
                    </div>
                    <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                        <a href="<?php echo $this->createUrl('fullSentPromotion/index',array('companyId' => $this->companyId))?>">
                            <div class="list_big">满送优惠</div>
                            <div class="list_small">添加满送优惠的相应活动信息</div>
                        </a> 
                    </div>
                    <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                        <a href="<?php echo $this->createUrl('fullMinusPromotion/index',array('companyId' => $this->companyId))?>">
                            <div class="list_big">满减优惠</div>
                            <div class="list_small">添加满减优惠的相应活动信息</div>
                        </a> 
                    </div>
                    <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                        <a href="<?php echo $this->createUrl('buysentpromotion/index',array('companyId' => $this->companyId))?>">
                            <div class="list_big">买送</div>
                            <div class="list_small">设置买送优惠的数量、菜品信息等</div>
                        </a> 
                    </div>
                    <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                        <a href="">
                            <div class="list_big">买减</div>
                            <div class="list_small">设置买减优惠的相应活动内容</div>
                        </a> 
                    </div>
                </div>   
        </div>             
    </div>
</div>
