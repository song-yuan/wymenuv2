		<style>
			html{background-color: white!important;}
			.b-white{background-color: white!important;}
		</style>
		<header class="mui-bar mui-bar-nav mui-hbar">
		    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" style="color:white;"></a>
		    <h1 class="mui-title" style="color:white;">常用设置</h1>
		</header>
		<div class="mui-content">
		   <ul class="mui-table-view mui-grid-view mui-grid-9 b-white">
				<li class="mui-table-view-cell mui-media mui-col-xs-6">
					<a href="<?php echo $this->createUrl('myinfo/stockSetting',array('companyId'=>$this->companyId)) ?>">
						<span class="mui-icon mui-icon-gear"></span>
						<div class="mui-media-body">库存参数设置</div>
					</a>
				</li>
				<li class="mui-table-view-cell mui-media mui-col-xs-6">
					<a href="#">
						<span class="mui-icon mui-icon-gear"></span>
						<div class="mui-media-body">修改登录密码</div>
					</a>
				</li>
				<li class="mui-table-view-cell mui-media mui-col-xs-6">
					<a href="#">
						<span class="mui-icon mui-icon-gear"></span>
						<div class="mui-media-body">Home</div>
					</a>
				</li>
				<li class="mui-table-view-cell mui-media mui-col-xs-6">
					<a href="#">
						<span class="mui-icon mui-icon-gear"></span>
						<div class="mui-media-body">Home</div>
					</a>
				</li>
			</ul>

		</div>
		<script src="js/mui.min.js"></script>
		<script type="text/javascript">
			mui.init()
		</script>

