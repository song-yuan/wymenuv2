<!-- BEGIN SIDEBAR -->
<div class="page-sidebar navbar-collapse collapse">
    <!-- BEGIN SIDEBAR MENU -->        	
    <ul class="page-sidebar-menu">
            <li>
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                <div class="sidebar-toggler hidden-phone"></div>
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
            </li>
            <!--  
            <li class="<?php if(Yii::app()->controller->id == 'default') echo 'active';?>">
                    <a href="<?php echo $this->createUrl('default/index',array('companyId' => $this->companyId));?>">
                    <i class="fa fa-home"></i> 
                    <span class="title"><?php echo yii::t('app','首页');?></span>					
                    </a>
            </li>
            -->
   <?php if($this->comptype != 2 && Yii::app()->user->role <= '15' && Yii::app()->user->role !='4'): ?>
        <?php if(Yii::app()->user->role != '8'): ?>
            <li class="<?php if(in_array(Yii::app()->controller->id , array('company', 'companyWx', 'user' ,'synchronous','poscode','postable','connectUs','uploadApk','announcement','pricegroup','companyGroup','doubleScreen','copyScreen','companySetting' ))) echo 'active';?>">
                    <a href="<?php echo $this->createUrl('company/list',array('companyId' => $this->companyId));?>">
                        <i class="fa fa-home"></i> 
                        <span class="title"><?php echo yii::t('app','店铺管理');?></span>					
                    </a>
            </li>
            <li class="<?php if(in_array(Yii::app()->controller->id , array('product' ,'payMethod', 'basicFee' ,'productAddition','productSet','productSim','productImg','productCategory','retreat','productPrinter','productClean','productWeight','productSales','productSpecial', 'productTempprice', 'copyproduct', 'floor', 'site', 'siteType', 'sitePersons', 'siteChannel', 'pad', 'printer', 'printerWay', 'taste', 'productTaste', 'takeawayMember','muchupdateProd','muchprinterProd','materialCategory','materialUnit','productMaterial','materialUnitRatio','productBom','copyproductSet','copytaste','productLabel','copyPrinter'))) echo 'active';?>">
                <a href="<?php echo $this->createUrl('product/list',array('companyId' => $this->companyId,'type'=>0));?>">
                    <i class="fa fa-cog"></i> 
                    <span class="title"><?php echo yii::t('app','基础设置');?></span>					
                </a>
            </li>
        <?php endif; ?>    
             
            <li class="<?php if(in_array(Yii::app()->controller->id , array('wechatMember','weixin','wxCardStyle','wxrecharge','mobileMessage'))) echo 'active';?>">
                   <a href="<?php echo $this->createUrl('wechatMember/list',array('companyId' => $this->companyId));?>">
                        <i class="fa fa-comments"></i> 
                        <span class="title"><?php echo yii::t('app','微信会员');?></span>					
                   </a>
           </li>
            <li class="<?php if(in_array(Yii::app()->controller->id , array('wechatMarket','cupon','wxcard','sentwxcardpromotion','sentwxcardImproinfo'))) echo 'active';?>">
                    <a href="<?php echo $this->createUrl('wechatMarket/list',array('companyId' => $this->companyId) );?>">
                        <i class="fa  fa-ticket"></i> 
                        <span class="title"><?php echo yii::t('app','微信赠券');?></span>					
                    </a>
            </li>
        <?php if(Yii::app()->user->role != '8'): ?>
            <li class="<?php if(in_array(Yii::app()->controller->id , array('entityCard','memberWxlevel','staffRecharge','member'))) echo 'active';?>">
                    <a href="<?php echo $this->createUrl('entityCard/list',array('companyId' => $this->companyId));?>">
                        <i class="fa fa-credit-card"></i> 
                        <span class="title"><?php echo yii::t('app','实体卡');?></span>					
                    </a>
            </li>
        <?php endif; ?>
            <li class="<?php if(in_array(Yii::app()->controller->id , array('entityMarket','discount','normalpromotion','fullSentPromotion','fullMinusPromotion','buysentpromotion'))) echo 'active';?>">
                    <a href="<?php echo $this->createUrl('entityMarket/list',array('companyId' => $this->companyId));?>">
                    <i class="fa  fa-info-circle"></i> 
                    <span class="title"><?php echo yii::t('app','营销活动');?></span>					
                    </a>
            </li>
        <?php if(Yii::app()->user->role != '8'): ?>
            <li class="<?php if(in_array(Yii::app()->controller->id , array('bom','stocktakinglog','nowmaterialstock','copymaterial','orgClassification','orgInformation','purchaseOrder','purchaseOrderDetail','storageOrder','storageOrderDetail','mfrClassification','mfrInformation','refundOrder','refundOrderDetail','bomProduct','bomproductCategory','stockSetting','materialStockLog','commit','commitDetail','inventory','stockInventory','stockTaking'))) echo 'active';?>">
                    <a href="<?php echo $this->createUrl('bom/bom',array('companyId' => $this->companyId,'type'=>2));?>">
                    <i class="fa fa-coffee"></i> 
                    <span class="title"><?php echo yii::t('app','进销存管理');?></span>
                    </a>
            </li>
            <li class="<?php if(in_array(Yii::app()->controller->id , array('statements','orderManagement','statementmember','pos','statementstock'))) echo 'active';?>">
                    <a href="<?php echo $this->createUrl('statements/list',array('companyId' => $this->companyId,'type'=>0));?>">
                    <i class="fa fa-bar-chart-o"></i> 
                    <span class="title"><?php echo yii::t('app','报表中心');?></span>					
                    </a>
            </li>
        <?php endif; ?>
			 <?php if(Yii::app()->user->role != '8'): ?>
            <li class="<?php if(in_array(Yii::app()->controller->id , array('waimai',))) echo 'active';?>">
                    <a href="<?php echo $this->createUrl('waimai/list',array('companyId' => $this->companyId,'type'=>0));?>">
                    <i class="fa fa-gift"></i> 
                    <span class="title"><?php echo yii::t('app','外卖管理');?></span>                 
                    </a>
            </li>
            <?php endif;?>
             <?php if(Yii::app()->user->role ==1):?>
            <li class="<?php if(in_array(Yii::app()->controller->id , array('cfceshi',))) echo 'active';?>">
                    <a href="<?php echo $this->createUrl('cfceshi/list',array('companyId' => $this->companyId,'type'=>0));?>">
                    <i class="fa fa-flask"></i> 
                    <span class="title"><?php echo yii::t('app','cf测试中心');?></span>					
                    </a>
            </li>
            <?php endif;?>
        <?php endif; ?>
        
            <?php if(Yii::app()->user->role<=1 || $this->comptype == 2):?>
            <li class="<?php if(in_array(Yii::app()->controller->id , array('tmall','goods'))) echo 'active';?>">
                    <a href="<?php echo $this->createUrl('tmall/list',array('companyId' => $this->companyId,'type'=>0));?>">
                    <i class="fa fa-shopping-cart"></i> 
                    <span class="title"><?php echo yii::t('app','仓库配置');?></span>					
                    </a>
            </li>
            <?php endif;?>
            
            <?php if(Yii::app()->user->role <= 4 && $this->comptype != 2):?>
            <li class="<?php if(in_array(Yii::app()->controller->id , array('comtopay'))) echo 'active';?>">
                    <a href="<?php echo $this->createUrl('comtopay/index',array('companyId' => $this->companyId));?>">
                    <i class="fa fa-shopping-cart"></i> 
                    <span class="title"><?php echo yii::t('app','支付配置');?></span>					
                    </a>
            </li>
            <li class="<?php if(in_array(Yii::app()->controller->id , array('poscount'))) echo 'active';?>">
                    <a href="<?php echo $this->createUrl('poscount/hqindex',array('companyId' => $this->companyId));?>">
                    <i class="fa fa-shopping-cart"></i> 
                    <span class="title"><?php echo yii::t('app','收银机结算');?></span>					
                    </a>
            </li>
            <?php endif;?>

                
        </ul>
        <!-- END SIDEBAR MENU -->
</div>
<!-- END SIDEBAR -->