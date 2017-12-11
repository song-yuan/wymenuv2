
<div class="page-content">
    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','微信会员'),'url'=>''))));?>
    
                    <div class="portlet purple box"> 
                    <div class="portlet-title">
						<div class="caption"><i class=" fa  fa-user"></i>微信会员</div>
					</div>                     
                        <div class="portlet-body clearfix" >
                            <div class="panel_body row">
                                <p>基础设置</p>
                                <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                                    <a href="<?php echo $this->createUrl('weixin/index',array('companyId' => $this->companyId))?>">
                                        <div class="list_big">设置</div>
                                        <div class="list_small">绑定微信信息</div>
                                    </a> 
                                </div>
                                <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                                    <a href="<?php echo $this->createUrl('wxCardStyle/index',array('companyId' => $this->companyId))?>">
                                        <div class="list_big">会员卡样式</div>
                                        <div class="list_small">设置微信会员卡的样式</div>
                                    </a> 
                                </div>
                                <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                                    <a href="<?php echo $this->createUrl('weixin/menu',array('companyId' => $this->companyId))?>">
                                        <div class="list_big">自定义菜单</div>
                                        <div class="list_small">设置微信服务号的首页的选项</div>
                                    </a> 
                                </div>
                                <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                                    <a href="<?php echo $this->createUrl('wechatMember/vip',array('companyId' => $this->companyId))?>">
                                        <div class="list_big">VIP会员</div>
                                        <div class="list_small">设置微信会员的等级</div>
                                    </a> 
                                </div>
                                <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                                    <a href="">
                                        <div class="list_big">会员渠道</div>
                                        <div class="list_small"></div>
                                    </a> 
                                </div>
                            </div>
                            <div class="panel_body row">
                                <p>会员的基本信息与操作</p>
                                <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                                    <a href="<?php echo $this->createUrl('wechatMember/search',array('companyId' => $this->companyId))?>">
                                        <div class="list_big">会员查询</div>
                                        <div class="list_small">查询微信会员的基本信息</div>
                                    </a> 
                                </div>
                                <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                                    <a href="<?php echo $this->createUrl('wxrecharge/index',array('companyId' => $this->companyId))?>">
                                        <div class="list_big">储值</div>
                                        <div class="list_small">设置微会员充值模板</div>
                                    </a> 
                                </div>
                                <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                                    <a href="">
                                        <div class="list_big">积分</div>
                                        <div class="list_small"></div>
                                    </a> 
                                </div>
                                <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                                    <a href="">
                                        <div class="list_big">会员商城</div>
                                        <div class="list_small"></div>
                                    </a> 
                                </div>
                                <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                                    <a href="<?php echo $this->createUrl('wechatMember/chain',array('companyId' => $this->companyId))?>">
                                        <div class="list_big">实体卡绑定</div>
                                        <div class="list_small">实体卡的会员等级与微信会员的会员等级绑定</div>
                                    </a> 
                                </div>
                                <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                                    <a href="<?php echo $this->createUrl('wxMessage/index',array('companyId' => $this->companyId))?>">
                                        <div class="list_big">模板消息</div>
                                        <div class="list_small">设置微信的消息模板</div>
                                    </a> 
                                </div>
                                <div style="height: 80px;" class="list col-sm-3 col-xs-12">
                                    <a href="<?php echo $this->createUrl('mobileMessage/index',array('companyId' => $this->companyId))?>">
                                        <div class="list_big">短信</div>
                                        <div class="list_small">查询短信消息提示是否成功</div>
                                    </a> 
                                </div>
                            </div>
                            <!-- <div class="panel_body">
                                <div class="row"> -->
                                    <!-- <div class="col-sm-3 col-xs-6">
                                        <a href="<?php echo $this->createUrl('weixin/index',array('companyId' => $this->companyId))?>">
                                            <span class="fa fa-cog list_picture"></span>
                                            <div class="list_text">设置</div>
                                        </a> 
                                    </div> -->
                                    <!-- <div class="col-sm-3 col-xs-6">
                                        <a href="<?php echo $this->createUrl('wxCardStyle/index',array('companyId' => $this->companyId))?>">
                                            <span class="fa  fa-credit-card list_picture"></span>
                                            <div class="list_text">会员卡样式</div>
                                        </a> 
                                    </div> -->
                                    <!-- <div class="col-sm-3 col-xs-6">
                                        <a href="<?php echo $this->createUrl('weixin/menu',array('companyId' => $this->companyId))?>">
                                            <span class="fa fa-book list_picture"></span>
                                            <div class="list_text">自定义菜单</div>
                                        </a> 
                                    </div> -->
                                    <!-- <div class="col-sm-3 col-xs-6">
                                        <a href="<?php echo $this->createUrl('wechatMember/vip',array('companyId' => $this->companyId))?>">
                                            <span class="fa fa-sort-amount-asc list_picture"></span>
                                            <div class="list_text">VIP会员</div>
                                        </a> 
                                     </div> -->
                                   <!--  <div class="col-sm-3 col-xs-6">
                                        <a href="javascript:void(0)">
                                            <span class="fa fa-truck list_picture"></span>
                                            <div class="list_text">会员渠道</div>
                                        </a> 
                                    </div> -->
                                   
                                    <!-- <div class="col-sm-3 col-xs-6 ">
                                        <a href="<?php echo $this->createUrl('wechatMember/search',array('companyId' => $this->companyId))?>">
                                            <span class="fa  fa-user list_picture"></span>
                                            <div class="list_text">会员查询</div>
                                        </a> 
                                    </div> -->
                                   <!--  <div class="col-sm-3 col-xs-6">
                                        <a href="<?php echo $this->createUrl('wxrecharge/index',array('companyId' => $this->companyId))?>">
                                            <span class="fa fa-yen  list_picture"></span>
                                            <div class="list_text">储值</div>
                                        </a> 
                                    </div> -->
                                    <!-- <div class="col-sm-3 col-xs-6">
                                        <a href="javascript:void(0)">
                                            <span class="fa  fa-star list_picture"></span>
                                            <div class="list_text">积分</div>
                                        </a> 
                                    </div> -->
                                    <!-- <div class="col-sm-3 col-xs-6">
                                        <a href="javascript:void(0)">
                                            <span class="fa fa-shopping-cart list_picture"></span>
                                            <div class="list_text">会员商城</div>
                                        </a> 
                                      </div> -->
                                    <!-- <div class="col-sm-3 col-xs-6">
                                        <a href="<?php echo $this->createUrl('wechatMember/chain',array('companyId' => $this->companyId))?>">
                                            <span class="fa fa-chain  list_picture"></span>
                                            <div class="list_text">实体卡绑定</div>
                                        </a> 
                                    </div> -->
                                    <!-- <div class="col-sm-3 col-xs-6">
                                        <a href="<?php echo $this->createUrl('wxMessage/index',array('companyId' => $this->companyId))?>">
                                            <span class="fa fa-envelope-o  list_picture"></span>
                                            <div class="list_text">模板消息</div>
                                        </a> 
                                    </div> -->
                                    <!-- <div class="col-sm-3 col-xs-6">
                                        <a href="<?php echo $this->createUrl('mobileMessage/index',array('companyId' => $this->companyId))?>">
                                            <span class="fa fa-envelope  list_picture"></span>
                                            <div class="list_text">短信</div>
                                        </a> 
                                    </div> -->
                            <!-- </div> 
                        </div> -->
                    </div>
                </div>
</div>