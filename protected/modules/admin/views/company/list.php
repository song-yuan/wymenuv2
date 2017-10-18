
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<style>
		span.tab{
			color: black;
			border-right:1px dashed white;
			margin-right:10px;
			padding-right:10px;
			display:inline-block;
		}
		span.tab-active{
			color:white;
		}
		.ku-item{
			width:100px;
			height:100px;
			margin-right:20px;
			margin-top:20px;
			margin-left:20px;
			border-radius:5px !important;
			/*border:2px solid black;*/
			/*box-shadow: 5px 5px 5px #888888;*/
			vertical-align:middle;
		}
		.ku-item-info{
			width:144px;
			font-size:2em;
			color:black;
			text-align:center;
		}
		.ku-purple{
			/*background-color:#852b99;*/
		}
		.ku-grey{
			background-color:rgb(68,111,120);
		}
		.ku-item.dpgl{
			background-image:url(../../../../img/waiter/icon-dpjcsz.png);
			background-position: 15px 15px;
    		background-repeat: no-repeat;
		}
		.ku-item.czygl{
			background-image:url(../../../../img/waiter/icon-dpczy.png);
			background-position: 25px 27px;
    		background-repeat: no-repeat;
		}
		.ku-item.qxsz{
			background-image:url(../../../../img/waiter/icon-dpjcsz.png);
			background-position: -285px 15px;
    		background-repeat: no-repeat;
		}
		.ku-item.fdgl{
			background-image:url(../../../../img/waiter/icon-dpjcsz.png);
			background-position: -425px 15px;
    		background-repeat: no-repeat;
		}
		.ku-item.wxdp{
			background-image:url(../../../../img/waiter/icon-dpjcsz.png);
			background-position: -575px 15px;
    		background-repeat: no-repeat;
		}
		.ku-item.zfbsz{
			background-image:url(../../../../img/waiter/icon-zfbsz.png);
			background-position: 15px 15px;
			background-size: 76%;
    		background-repeat: no-repeat;
		}
		.ku-item.tbsj{
			background-image:url(../../../../img/waiter/icon-dpjcsz.png);
			background-position: -725px 15px;
    		background-repeat: no-repeat;
		}
		.ku-item.syjsz{
			background-image:url(../../../../img/waiter/icon-syjsz.png);
			background-position: 13px 13px;
    		background-repeat: no-repeat;
			background-size: 80%;
		}
		.ku-item.spsz{
			background-image:url(../../../../img/waiter/icon-spsz.png);
			background-position: 13px 13px;
    		background-repeat: no-repeat;
			background-size: 80%;
		}
		.ku-item.bgx{
			background-image:url(../../../../img/waiter/icon-bgx.png);
			background-position: 13px 13px;
    		background-repeat: no-repeat;
			background-size: 80%;
		}
		.ku-item.app{
			background-image:url(../../../../img/waiter/icon-android.png);
			background-position: 15px 10px;
    		background-repeat: no-repeat;
			background-size: 70%;
		}
		.ku-item.connect{
			background-image:url(../../../../img/waiter/phone3.png);
			background-position: 8px 3px;
    		background-repeat: no-repeat;
			background-size: 90%;
		}
		.ku-item.ggsz{
			background-image:url(../../../../img/icon-announcement.png);
			background-position: 8px 10px;
    		background-repeat: no-repeat;
			background-size: 90%;
		}
		.ku-item.rwzl{
			background-image:url(../../../../img/waiter/icon-rwzl.png);
			background-position: 15px 15px;
    		background-repeat: no-repeat;
            background-size: 60%;
		}
		.ku-item.dljm{
			background-image:url(../../../../img/waiter/icon-login.jpg);
			background-position: 15px 15px;
    		background-repeat: no-repeat;
            background-size: 80%;
		}
		.margin-left-right{
			margin-left:10px;
			margin-right:10px;
		}
		.ku-item.cpfl{
			background-image:url(../../../../img/waiter/icon-cpsz.png);
			background-position: 15px 15px;
    		background-repeat: no-repeat;
		}
		.ku-purple.area{
			background-image:url(../../../../img/waiter/icon_area.jpg);
			background-position: 0px 0px;
    		background-repeat: no-repeat;
		}
		.margin-left-right{
			margin-left:10px;
			margin-right:10px;
		}
		
	</style>
	<div class="page-content">
		<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">关系图</h4>
					</div>
					<div class="modal-body">
						<img alt="" src="">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn default" data-dismiss="modal">确定</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
	<!-- BEGIN PAGE CONTENT-->
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','店铺管理'),'url'=>''))));?>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet purple box">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-cogs"></i><?php echo yii::t('app','店铺管理');?></div>
				</div>
				<div class="portlet-body" style="min-height: 750px">
					
					<a href="<?php echo $this->createUrl('company/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple dpgl"></div>
							<div class="ku-item-info">店铺管理</div>
						</div>
					</a>
					<?php if(Yii::app()->user->role !='4'):?>
					<a href="<?php echo $this->createUrl('user/index',array('companyId'=>$this->companyId,'type'=>0));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple czygl"></div>
							<div class="ku-item-info">操作员管理</div>
						</div>
					</a>
					<?php if(Yii::app()->user->role <= User::SHOPKEEPER):?>
<!--					<a href="<?php// echo $this->createUrl('weixin/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple wxdp"></div>
							<div class="ku-item-info">微信设置</div>
						</div>
					</a>-->
					<a href="<?php echo $this->createUrl('alipay/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple zfbsz"></div>
							<div class="ku-item-info">支付宝设置</div>
						</div>
					</a>
					<?php endif;?>
					<!-- <?php //if(Yii::app()->user->role <= User::ADMIN_AREA):?> -->
					<?php  if($role <= 5):?>
					<a href="<?php echo $this->createUrl('poscode/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple syjsz"></div>
							<div class="ku-item-info">收银机设置</div>
						</div>
					</a>
					<?php endif;?>
					<?php if(Yii::app()->user->role <1):?>
				<!-- 	<a href="<?php echo $this->createUrl('poscode/hqindex',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right hov">
							<div class="ku-item ku-purple syjsz"></div>
							<div class="ku-item-info">收银机状态</div>
						</div>
					</a> -->
					<?php endif;?>
					<a href="<?php echo $this->createUrl('doubleScreen/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple spsz"></div>
							<div class="ku-item-info">双屏设置</div>
						</div>
					</a>
                                       
                    <?php if(Yii::app()->user->role <= 7):?>
					<a href="<?php echo $this->createUrl('copyScreen/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple spsz"></div>
							<div class="ku-item-info">双屏下发</div>
						</div>
					</a>
					<?php endif;?>
                                    
					<?php if(Yii::app()->user->role < User::ADMIN):?>
					<a href="<?php echo $this->createUrl('postable/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple bgx"></div>
							<div class="ku-item-info">表更新</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('uploadApk/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple app"></div>
							<div class="ku-item-info">app更新</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('syncFailure/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple tbsj"></div>
							<div class="ku-item-info">同步失败</div>
						</div>
					</a>
					<a href="<?php echo $this->createUrl('connectUs/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple connect"></div>
							<div class="ku-item-info">联系我们</div>
						</div>
					</a>
					<?php endif;?>
					<a href="<?php echo $this->createUrl('companyWx/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple wxdp"></div>
							<div class="ku-item-info">微店营业</div>
						</div>
					</a>
					<?php if(Yii::app()->user->role < User::SHOPKEEPER):?>
					<a href="<?php echo $this->createUrl('announcement/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple ggsz"></div>
							<div class="ku-item-info">公告设置</div>
						</div>
					</a>
					<?php endif;?>
					<?php if(Yii::app()->user->role < 11):?>
					<a href="<?php echo $this->createUrl('companySetting/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple dljm"></div>
							<div class="ku-item-info">登陆界面</div>
						</div>
					</a>
					<?php endif;?>
					<a style="display: none;" href="<?php echo $this->createUrl('payneedinfo/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple rwzl"></div>
							<div class="ku-item-info">入网资料</div>
						</div>
					</a>
					<?php if(Yii::app()->user->role <= 7):?>
					<a href="<?php echo $this->createUrl('pricegroup/index',array('companyId'=>$this->companyId));?>" title="价格体系 可以添加多套价格体系 根据不到区域添加不同的价格体系  不同的店铺设置不同的价格体系">
						<div class="pull-left margin-left-right hov" >
							<div class="ku-item ku-purple cpfl"></div>
							<div class="ku-item-info">价格体系</div>
						</div>
					</a>
					
					
					<a href="<?php echo $this->createUrl('areaGroup/index',array('companyId'=>$this->companyId,'type'=>2));?>" title="添加店铺的类型,用来区分不同的类型的店铺">
						<div class="pull-left margin-left-right hov" >
							<div class="ku-item ku-purple dpgl"></div>
							<div class="ku-item-info">店铺类型</div>
						</div>
					</a>
					
					<a href="<?php echo $this->createUrl('areaGroup/index',array('companyId'=>$this->companyId,'type'=>3));?>" title="管理员在手机中管理的店进行分组显示">
						<div class="pull-left margin-left-right hov" >
							<div class="ku-item ku-purple dpgl"></div>
							<div class="ku-item-info">店铺分组</div>
						</div>
					</a>
					<!-- <a href="<?php echo $this->createUrl('areaGroup/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right hov" >
							<div class="ku-item ku-purple area"></div>
							<div class="ku-item-info">地区分组</div>
						</div>
					</a> -->
					
					<?php endif; ?>
					<?php endif; ?>
					<!--
					<a href="#">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple qxsz"></div>
							<div class="ku-item-info">权限设置</div>
						</div>
					</a>
					
					<a href="<?php echo $this->createUrl('synchronous/index',array('companyId'=>$this->companyId));?>">
						<div class="pull-left margin-left-right">
							<div class="ku-item ku-purple tbsj"></div>
							<div class="ku-item-info">同步数据</div>
						</div>
					</a>
					-->
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