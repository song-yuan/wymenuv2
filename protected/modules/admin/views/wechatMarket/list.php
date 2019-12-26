<div class="page-content">
   <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','微信赠券'),'url'=>''))));?>
 					<?php if($iscom['type'] == "0" || $isrest=="3"):?>
                    <div class="portlet purple box">
                    <div class="portlet-title">
						<div class="caption"><i class=" fa  fa-book"></i>微信赠券</div>
					</div> 
                      <div class="portlet-body clearfix" >
                          <div class="panel_body row">
                                <p>优惠券</p>
                                    <div class="list col-sm-3 col-xs-12">
                                         <a href="<?php echo $this->createUrl('cupon/index',array('companyId' => $this->companyId))?>">
                                             <div class="list_big"> 现金券</div>
                                             <div class="list_small">系统内生成的券</div>
                                         </a> 
                                    </div>
                                    <div class="list col-sm-3 col-xs-12">
                                         <a href="<?php echo $this->createUrl('wxcard/index',array('companyId' => $this->companyId))?>">
                                              <div class="list_big"> 卡券</div>
                                              <div class="list_small">微信卡包里的券</div>
                                         </a> 
                                    </div>
                               
                            </div> 
                          <div class= "panel_body row">
                                <p>增加新顾客</p>
                         
                                    <div class="list col-sm-3 col-xs-12">
                                       <a href="<?php echo $this->createUrl('sentwxcardpromotion/index',array('companyId' => $this->companyId))?>">
                                           <div class="list_big">开卡关怀</div>
                                            <div class="list_small">建立首次开卡赠礼活动，配置开卡消息，贴心的开卡消息及开卡礼品往往可以将新用户带进店来</div>
                                       </a> 
                                      
                                    </div>
                                    <div class="list col-sm-3 col-xs-12">
                                         <a href="<?php echo $this->createUrl('sentwxcardImproinfo/index',array('companyId' => $this->companyId))?>">     
                                            <div class="list_big">填资料赠券</div>
                                            <div class="list_small">建立用户填写资料赠券活动，详细的用户资料总是可以帮你区分用户，达到精准营销</div>
                                       
                                         </a> 
                                    </div>
                                    <div class="list col-sm-3 col-xs-12">
                                        <a href="<?php echo $this->createUrl('SentNoConsume/index',array('companyId' => $this->companyId))?>">
                                         <div class="list_big">给开卡未消费会员赠券</div>
                                        <div class="list_small">给已开卡未消费的会员发券，刺激潜在顾客来店消费</div>
                                       
                                         
                                         </a> 
                                    </div>
                                    <div class="list col-sm-3 col-xs-12">
                                        <a href="<?php echo $this->createUrl('SentNewMember/index',array('companyId' => $this->companyId))?>">
                                           <div class="list_big">给新会员赠券</div>
                                            <div class="list_small">给30天内开卡并消费过1次的会员发券，刺激新会员再次来店消费，提高新会员忠诚度 </div>
                                       
                                         </a> 
                                    </div>

                                
                            </div>
                            <div class= "panel_body row">
                                 <p>稳固老顾客</p>
                                 
                                      <div class="list col-sm-3 col-xs-12">
                                        <a href="<?php echo $this->createUrl('SentActiveMember/index',array('companyId' => $this->companyId))?>">
                                            
                                              <div class="list_big"> 给活跃老会员赠券</div>
                                            <div class="list_small">给60天内消费2次以上的会员发券，他们可是消费的中坚力量，值得更好的奖励 </div>
                                       
                                         </a> 
                                    </div>
                                     <div class="list col-sm-3 col-xs-12">
                                        <a href="<?php echo $this->createUrl('SentSleepMember/index',array('companyId' => $this->companyId))?>">
                                             
                                              <div class="list_big"> 给沉寂会员赠券</div>
                                            <div class="list_small">给60天以上未到店消费的会员发券，刺激沉寂会员</div>
                                       
                                         </a> 
                                    </div>
                                     <div class="list col-sm-3 col-xs-12">
                                        <a href="<?php echo $this->createUrl('SentOldMember/index',array('companyId' => $this->companyId))?>">
                                             
                                              <div class="list_big"> 给老会员赠券</div>
                                            <div class="list_small">给注册一个月以上，且60天内消费过一次的会员发券，推动他们来店消费</div>
                                       
                                         </a> 
                                    </div>  
                            </div>
                            <div class= "panel_body row">
                                <p>营销活动</p>
                                
                                    <div class="list col-sm-3 col-xs-12">
                                        <a href="<?php echo $this->createUrl('SentTotalConsume/index',array('companyId' => $this->companyId))?>">
                                            <div class="list_big"> 累计消费返券</div>
                                            <div class="list_small">建立累计消费满X送Y的活动，如此给力的用户不妨奖励丰厚些，他们将为你带来更多</div>   
                                        </a> 
                                    </div>  
                                    <div class="list col-sm-3 col-xs-12">
                                        <a href="<?php echo $this->createUrl('SentConsume/index',array('companyId' => $this->companyId))?>">                                           
                                            <div class="list_big"> 消费返券</div>
                                            <div class="list_small">建立消费满X送Y的活动，用户在消费过后获得一些奖励将会使他们下次光顾的日期大大提前</div>                             
                                        </a> 
                                    </div>
                                    <div class="list col-sm-3 col-xs-12">
                                        <a href="<?php echo $this->createUrl('wechatMarket/list',array('companyId' => $this->companyId))?>">
                                            <div class="list_big">分享集券</div>
                                        </a> 
                                    </div>
                            </div>                           
                            <div class= "panel_body row">
                                 <p>会员关怀</p>
                                   <div class="list col-sm-3 col-xs-12">
                                        <a href="<?php echo $this->createUrl('wechatMarket/wxmembercube',array('companyId' => $this->companyId))?>">
                                            <div class="list_big"> 会员魔方赠券</div>
                                        	<div class="list_small">根据不同条件给会员赠券</div>
                                        </a> 
                                    </div> 
                                    <div class="list col-sm-3 col-xs-12">
                                        <a href="<?php echo $this->createUrl('sentwxcardBirthday/index',array('companyId' => $this->companyId))?>">
                                             <div class="list_big">生日赠券</div>
                                             <div class="list_small">建立会员生日送券活动，生日总要腐败一下，这时一条生日优惠也许会将他们拉到店中</div>
                                        </a> 
                                    </div>
                                    <div class="list col-sm-3 col-xs-12">
                                        <a href="<?php echo $this->createUrl('sentwxcardTime/index',array('companyId' => $this->companyId))?>">
                                             <div class="list_big">定时发券</div>
                                             <div class="list_small">设置固定的日期内给等级人员或全部会员发券</div>
                                        </a> 
                                    </div>
                            </div> 

                    </div>
                    </div>
                    <?php else:?>
                    <div class="portlet purple box">
                    	<div style = "color: red;" class="portlet-body" >
                    	<h5>请联系总部开启微店，才能使用赠券功能。</h5>
                    	</div>
                    </div>
                    <?php endif;?>
            </div> 
    
   