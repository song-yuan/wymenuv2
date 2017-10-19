<style>
.page-content .page-breadcrumb.breadcrumb {
   margin-top: 0px;
   margin-bottom: 20px;
}
.portlet.box.purple {
    border: 1px solid #CFCFCF;

}
.portlet-body{
   min-height: 550px;
}
.panel_body{
    margin-top: 30px;
}
 .col-sm-3,.col-xs-6{
 text-align: center;
 margin-bottom: 50px;
}

.row a,
.row a:hover,
.row a:visited
{
    text-decoration: none;
    text-align: center;  
}

 .list_picture{
    font-size: 45px;
    color:#3385ff;
    
}
.list_text{
    color:black; 
    font-size: 25px;
    margin-top: 3px;
}

</style>

<div class="page-content">
    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','微信会员'),'url'=>''))));?>
    
                    <div class="portlet purple box"> 
                    <div class="portlet-title">
						<div class="caption"><i class=" fa  fa-user"></i>微信会员</div>
					</div>                     
                        <div class="portlet-body clearfix" >
                            <div class="panel_body">
                                <div class="row">
                                    <div class="col-sm-3 col-xs-6">
                                        <a href="<?php echo $this->createUrl('weixin/index',array('companyId' => $this->companyId))?>">
                                            <span class="fa fa-cog list_picture"></span>
                                            <div class="list_text">设置</div>
                                        </a> 
                                    </div>
                                    <div class="col-sm-3 col-xs-6">
                                        <a href="<?php echo $this->createUrl('wxCardStyle/index',array('companyId' => $this->companyId))?>">
                                            <span class="fa  fa-credit-card list_picture"></span>
                                            <div class="list_text">会员卡样式</div>
                                        </a> 
                                    </div>
                                    <div class="col-sm-3 col-xs-6">
                                        <a href="<?php echo $this->createUrl('weixin/menu',array('companyId' => $this->companyId))?>">
                                            <span class="fa fa-book list_picture"></span>
                                            <div class="list_text">自定义菜单</div>
                                        </a> 
                                    </div>
                                    <div class="col-sm-3 col-xs-6">
                                        <a href="<?php echo $this->createUrl('wechatMember/vip',array('companyId' => $this->companyId))?>">
                                            <span class="fa fa-sort-amount-asc list_picture"></span>
                                            <div class="list_text">VIP会员</div>
                                        </a> 
                                     </div>
                                    <div class="col-sm-3 col-xs-6">
                                        <a href="javascript:void(0)">
                                            <span class="fa fa-truck list_picture"></span>
                                            <div class="list_text">会员渠道</div>
                                        </a> 
                                    </div>
                                   
                                    <div class="col-sm-3 col-xs-6 ">
                                        <a href="<?php echo $this->createUrl('wechatMember/search',array('companyId' => $this->companyId))?>">
                                            <span class="fa  fa-user list_picture"></span>
                                            <div class="list_text">会员查询</div>
                                        </a> 
                                    </div>
                                    <div class="col-sm-3 col-xs-6">
                                        <a href="<?php echo $this->createUrl('wxrecharge/index',array('companyId' => $this->companyId))?>">
                                            <span class="fa fa-yen  list_picture"></span>
                                            <div class="list_text">储值</div>
                                        </a> 
                                    </div>
                                    <div class="col-sm-3 col-xs-6">
                                        <a href="javascript:void(0)">
                                            <span class="fa  fa-star list_picture"></span>
                                            <div class="list_text">积分</div>
                                        </a> 
                                    </div>
                                    <div class="col-sm-3 col-xs-6">
                                        <a href="javascript:void(0)">
                                            <span class="fa fa-shopping-cart list_picture"></span>
                                            <div class="list_text">会员商城</div>
                                        </a> 
                                      </div>
                                    <div class="col-sm-3 col-xs-6">
                                        <a href="<?php echo $this->createUrl('wechatMember/chain',array('companyId' => $this->companyId))?>">
                                            <span class="fa fa-chain  list_picture"></span>
                                            <div class="list_text">实体卡绑定</div>
                                        </a> 
                                    </div>
                                    <div class="col-sm-3 col-xs-6">
                                        <a href="<?php echo $this->createUrl('wxMessage/index',array('companyId' => $this->companyId))?>">
                                            <span class="fa fa-envelope-o  list_picture"></span>
                                            <div class="list_text">模板消息</div>
                                        </a> 
                                    </div>
                                    <div class="col-sm-3 col-xs-6">
                                        <a href="<?php echo $this->createUrl('mobileMessage/index',array('companyId' => $this->companyId))?>">
                                            <span class="fa fa-envelope  list_picture"></span>
                                            <div class="list_text">短信</div>
                                        </a> 
                                    </div>
                            </div> 
                        </div>
                    </div>
                </div>
</div>