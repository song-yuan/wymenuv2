<!-- <style>
.portlet-body {
    margin-top: 30px;
}
.col-xs-6,col-sm-3{
    text-align: center;
    margin-bottom: 50px;
}
.col-xs-6 a,col-sm-3 a,
.col-xs-6 a:hover,col-sm-3 a:hover,
.col-xs-6 a:visited,col-sm-3 a:visited{
    text-decoration: none;  
    display:block;    
}
.col-xs-6 .list_picture,col-sm-3 .list_picture {
    font-size: 45px;
    color:#3385ff;    
}
.col-xs-6 .list_text,col-sm-3 .list_text{
    color:black; 
    font-size: 25px;
    margin-top: 10px;
}  
</style> -->
<div class="page-content">
<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','实体卡'),'url'=>''))));?>		
    <div class="portlet purple box ">
    	<div class="portlet-title">
			<div class="caption"><i class=" fa fa-credit-card"></i>实体卡</div>
		</div>                       
        <div class="portlet-body clearfix">
            <div class="panel_body row">
                <p>基础设置</p>
                <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                    <a href="<?php echo $this->createUrl('memberWxlevel/index',array('companyId'=>$this->companyId));?>">
                        <div class="list_big">卡等级</div>
                        <div class="list_small">设置实体会员卡的等级和折扣</div>
                    </a> 
                </div>
                <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                    <a href="<?php echo $this->createUrl('member/index',array('companyId'=>$this->companyId));?>">
                        <div class="list_big">添加会员</div>
                        <div class="list_small">添加新的会员用户</div>
                    </a> 
                </div>
                <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                    <a href="<?php echo $this->createUrl('entityCard/cardsearch',array('companyId'=>$this->companyId));?>">
                        <div class="list_big">卡查询</div>
                        <div class="list_small">按条件查询实体会员卡的基础信息</div>
                    </a> 
                </div>
                <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                    <a href="<?php echo $this->createUrl('entityCard/recharge',array('companyId'=>$this->companyId));?>">
                        <div class="list_big">充值</div>
                        <div class="list_small">为新老会员充值卡内金额</div>
                    </a> 
                </div>
                <?php if(Yii::app()->user->role <= User::ADMIN_VICE):?>
                <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                    <a href="<?php echo $this->createUrl('StaffRecharge/index',array('companyId'=>$this->companyId));?>">
                        <div class="list_big">员工充值</div>
                        <div class="list_small">按等级查询会员信息和批量充值卡内金额</div>
                    </a> 
                </div>
                <?php endif;?>
                <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                    <a href="<?php echo $this->createUrl('entityCard/zero',array('companyId'=>$this->companyId));?>">
                        <div class="list_big">清零</div>
                        <div class="list_small">将不需要的会员信息注销</div>
                    </a> 
                </div>
            </div>
            <div class="panel_body row">
                <p>实体会员的关怀</p>
                <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                    <a href="">
                        <div class="list_big">生日关怀</div>
                        <div class="list_small">为今天生日的会员用户送上一份生日折扣</div>
                    </a> 
                </div>
                <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                    <a href="">
                        <div class="list_big">卡魔方</div>
                        <div class="list_small">按不同的条件查询会员信息</div>
                    </a> 
                </div>
            </div>
            <div class="panel_body row">
                <p>实体会员的活跃度</p>
                <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                    <a href="<?php echo $this->createUrl('entityCard/active',array('companyId'=>$this->companyId));?>">
                        <div class="list_big">活跃会员</div>
                        <div class="list_small">查询常客会员的消费记录</div>
                    </a> 
                </div>
                <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                    <a href="<?php echo $this->createUrl('entityCard/unActive',array('companyId'=>$this->companyId));?>">
                        <div class="list_big">不活跃会员</div>
                        <div class="list_small">查询偶尔消费的会员记录</div>
                    </a> 
                </div>
            </div>
            <!-- <div class="panel_body">
                <div class="row"> -->
                    <!-- <div class="col-xs-6  col-sm-3">
                        <a href="<?php echo $this->createUrl('memberWxlevel/index',array('companyId'=>$this->companyId));?>">
                            <div class="fa  fa-sort-amount-asc list_picture"></div>
                            <div class="list_text">卡等级</div>
                        </a>  
                    </div> -->
                    <!-- <div class="col-xs-6  col-sm-3">
                        <a href="<?php echo $this->createUrl('entityCard/cardsearch',array('companyId'=>$this->companyId));?>">
                            <div class="fa  fa-user  list_picture"></div>
                            <div class="list_text">卡查询</div>
                        </a> 
                    </div> -->
                    <!-- <div class="col-xs-6 col-sm-3">
                       <a href="<?php echo $this->createUrl('entityCard/recharge',array('companyId'=>$this->companyId));?>">
                            <div class="fa fa-rmb list_picture"></div>
                            <div class="list_text">充值</div>
                        </a> 
                    </div> -->
                    <?php if(Yii::app()->user->role <= User::ADMIN_VICE):?>
                    <!-- <div class="col-xs-6 col-sm-3">
                        <a href="<?php echo $this->createUrl('StaffRecharge/index',array('companyId'=>$this->companyId));?>">
                            <div class="fa fa-dollar  list_picture"></div>
                            <div class="list_text">员工充值</div>
                        </a> 
                    </div> -->
                    <?php endif;?>
                     <!-- <div class="col-xs-6 col-sm-3">
                        <a href="<?php echo $this->createUrl('entityCard/zero',array('companyId'=>$this->companyId));?>">
                            <div class="fa fa-frown-o list_picture"></div>
                            <div class="list_text">清零</div>
                        </a> 
                     </div> -->
                    <!-- <div class="col-xs-6 col-sm-3">
                        <a href="<?php echo $this->createUrl('member/index',array('companyId'=>$this->companyId));?>">
                            <div class="fa fa-folder-open  list_picture"></div>
                            <div class="list_text">添加会员</div>
                        </a> 
                    </div> -->
                    <!-- <div class="col-xs-6 col-sm-3">
                        <a href="#">
                            <div class="fa fa-heart-o   list_picture"></div>
                            <div class="list_text">生日关怀</div>
                        </a> 
                    </div>
                    <div class="col-xs-6 col-sm-3">
                        <a href="javascript:void(0)">
                            <div class="fa  fa-th-large list_picture"></div>
                            <div class="list_text">卡魔方</div>
                        </a> 
                    </div> -->
                    <!-- <div class="col-xs-6 col-sm-3">
                        <a href="<?php echo $this->createUrl('entityCard/active',array('companyId'=>$this->companyId));?>">
                            <div class="fa  fa-sun-o list_picture"></div>
                            <div class="list_text">活跃会员</div>
                        </a> 
                    </div>  -->
                    <!-- <div class="col-xs-6 col-sm-3">
                        <a href="<?php echo $this->createUrl('entityCard/unActive',array('companyId'=>$this->companyId));?>">
                            <div class="fa  fa-moon-o list_picture"></div>
                            <div class="list_text">不活跃会员</div>
                        </a> 
                    </div> -->
                <!-- </div>                             
            </div>    -->     
        </div> 
    </div>                    
</div>



 
                      