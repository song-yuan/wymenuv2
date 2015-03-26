<div class="page-content">
	<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
	<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">Modal title</h4>
				</div>
				<div class="modal-body">
					Widget settings form goes here
				</div>
				<div class="modal-footer">
					<button type="button" class="btn blue">Save changes</button>
					<button type="button" class="btn default" data-dismiss="modal">Close</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<div class="row">
		<div class="col-md-12">
			<!-- BEGIN PAGE TITLE & BREADCRUMB-->			
			<h3 class="page-title">
									<small></small>
			</h3>
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
	</div>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<style type="text/css">
		.site_list {
			padding-right:10px;
		}
		.site_list {
			display:inline-block;
		}
		.site_list ul li {
			float:left;
			width:80px;
			line-height:80px;
			border: 1px solid #add;
			margin:5px;
			list-style:none;
			text-align:center;
			vertical-align:middle;
		}
		.message_list {
			border-left:1px solid #000;
			padding-left:10px;
			min-height:500px;
		}
		.message_list ul li {
			list-style:none;
		}
	</style>
	<div class="row">
		<div class="col-md-8">
			<?php if($siteTypes):?>
					<div class="tabbable tabbable-custom">
						<ul class="nav nav-tabs">
						<?php foreach ($siteTypes as $key=>$siteType):?>
							<li class="<?php if($key == $typeId) echo 'active';?>"><a href="#tab_1_<?php echo $key;?>" data-toggle="tab" onclick="location.href='<?php echo $this->createUrl('default/index' , array('typeId'=>$key , 'companyId'=>$this->companyId));?>'"><?php echo $siteType ;?></a></li>
						<?php endforeach;?>
                                                        <li class="<?php if($typeId == 'tempsite') echo 'active';?>"><a href="#tab_1_tempsite" data-toggle="tab" onclick="location.href='<?php echo $this->createUrl('default/index' , array('typeId'=>'tempsite' , 'companyId'=>$this->companyId));?>'">临时座位</a></li>
						</ul>
						<div class="tab-content">
							<div class="portlet box purple">
								<div class="portlet-title">
									<div class="actions pull-left">
                                                                                <a href="<?php echo $this->createUrl('site/create' , array('typeId'=>$typeId , 'companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> 开台</a>
                                                                                <a href="<?php echo $this->createUrl('site/create' , array('typeId'=>$typeId , 'companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> 撤台</a>
                                                                                <?php if($typeId != 'tempsite') :?>
                                                                                <a href="<?php echo $this->createUrl('site/create' , array('typeId'=>$typeId , 'companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> 换台</a>
                                                                                <?php endif;?>
                                                                                <a href="<?php echo $this->createUrl('site/create' , array('typeId'=>$typeId , 'companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> 并台</a>
									</div>
									<div class="actions">
										<a href="<?php echo $this->createUrl('site/create' , array('typeId'=>$typeId , 'companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> 当前订单</a>
									</div>
								</div>
								<div class="portlet-body" id="table-manage">
				
                                                                        <div class="portlet-body site_list">
                                                                                <ul>
                                                                                        <li> 001 </li>
                                                                                        <li> 002 </li>
                                                                                        <li> 003 </li>
                                                                                        <li> 004 </li>
                                                                                        <li> 005 </li>
                                                                                        <li> 006 </li>
                                                                                        <li> 007 </li>
                                                                                        <li> 008 </li>
                                                                                        <li> 009 </li>
                                                                                        <li> 010 </li>
                                                                                        <li> 011 </li>
                                                                                        <li> 012 </li>
                                                                                        <li> 013 </li>
                                                                                        <li> 014 </li>
                                                                                        <li> 015 </li>
                                                                                        <li> 016 </li>
                                                                                        <li> 017 </li>
                                                                                </ul>
                                                                        </div>
                                                                    </div>
							</div>
							<!-- END EXAMPLE TABLE PORTLET-->												
						</div>
					</div>
				<?php endif;?>
			
		</div>	
		<div class="col-md-4 ">
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i>消息列表</div>
					<!-- <div class="actions">
						<?php if(Yii::app()->user->role == User::POWER_ADMIN):?>
						<a href="<?php echo $this->createUrl('company/create');?>" class="btn blue"><i class="fa fa-pencil"></i> 添加</a>
						<?php endif;?>
						<div class="btn-group">
							<a class="btn green" href="#" data-toggle="dropdown">
							<i class="fa fa-cogs"></i> Tools
							<i class="fa fa-angle-down"></i>
							</a>
							<ul class="dropdown-menu pull-right">
								<li><a href="#"><i class="fa fa-ban"></i> 冻结</a></li>
							</ul>
						</div>
					</div> -->
				</div>
				<div class="portlet-body message_list">
					<ul>
						<li>[001] 发表了评论</li>
						<li>[002] 发表了评论</li>
						<li>[003] 发表了评论：味道还不错, 量有点少 。</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!-- END PAGE CONTENT-->