
		<style>
			.back-color{background-color: #F0F0E1;}
			.left{float:left;}
			.right{float:right;}
			.padding-top{padding-top:10px;}
			.padding-right{padding-right:10px;}
			.padding-right1{padding-right:15px;}
			.margin-top{margin-top:4px!important;}
			.padding{padding:5px;}
			.font-small{font-size: 14px;}
			.color-l-gray{color:#323232;}
			.color-l-orange{color:darkorange;}
			.banma{border-bottom:3px dashed red;}
			.big-ul{margin-bottom: 50px;margin-top:2px!important;}
			.edit{position: absolute;right:20px;top:11px;}
			/*.mui-table-view-divider{}*/
			.mui-table-view-cell {
				list-style: none;
			    font-weight: 500;
			    position: relative;
			    margin-top: -1px;
			    margin-left: 0;
			    padding-top: 6px;
			    padding-bottom: 6px;
			    padding-left: 15px;
			    color: #999;
			    background-color: #fafafa;
			}
		</style>

		<header class="mui-bar mui-bar-nav mui-hbar">
		    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" style="color:white;"></a>
		    <h1 class="mui-title" style="color:white;">选择收货地址</h1>
			<a class="mui-pull-left edit" id="" style="color:white;">管理</a>
		</header>
		<div class="mui-content">
		<div class="mui-scroll-wrapper">
			<div class="mui-scroll" style="margin-top:40px;">
			<ul class="mui-table-view margin-top">
			<?php if ($models): ?>
				<?php foreach ($models as $key => $model): ?>
				<li class="mui-table-view-divider" lid="<?php echo $model['lid']; ?>">
					<div class="mui-row padding">
						<span class="left color-l-gray"><?php echo $model['name'] ?></span>
						<span class="right padding-right1 color-l-gray"><?php echo $model['mobile'] ?></span>
					</div>
					<div class="mui-row  padding-top">
						<span class=" font-small mui-ellipsis-2 padding-right">
					<?php if( $model['default_address']): ?>
						<span class="color-l-orange"  style="height: 23px;line-height: 23px;" > [ 默认地址 ] </span>
					<?php endif; ?>
							<?php echo $model['pcc'].' '.$model['street'] ?>
						</span>
					</div>
				</li>
				<?php endforeach; ?>
			<?php else: ?>
				<li class="mui-table-view-divider">
					<div class="mui-row padding">
					还没有添加地址,请点击 [管理]
					</div>
				</li>
			<?php endif; ?>
			</ul>
	    </div>
	    </div>
	    </div>


		<script type="text/javascript">
			mui.init();
			mui('.mui-scroll-wrapper').scroll();
			mui('.mui-table-view').on('tap', '.mui-table-view-divider', function(event) {
				var btnArray = ['是','否'];
				var lid = this.attributes["lid"].value;
				var account_no = "<?php echo $account_no; ?>";
						// alert(account_no);
				mui.confirm('是否将本地址设置为本次订单的收货地址？','提示',btnArray,function(e){
					if(e.index==0){
						//自己的逻辑
						location.href = " <?php echo $this->createUrl('ymallcart/editgoodsorder',array('companyId'=>$this->companyId)) ?>/address_id/"+lid+'/account_no/'+account_no;
						// mui.alert('已经生成采购单 , 请到购物车再次确认采购单是否合适 ! ! !','提示',function(){});
					}else{
					}
				});
			});

			mui('.mui-bar').on('tap', '.edit', function(event) {
				var btnArray = ['是','否'];
				mui.confirm('将前往地址管理页面 , 订单地址没有改变 , 若要修改本订单地址 , 请点击 [ 我的 ]查找该订单进行修改 ？','提示',btnArray,function(e){
					if(e.index==0){
						//自己的逻辑
						location.href="<?php echo $this->createUrl('address/addressmanage',array('companyId'=>$this->companyId));?>";
						// mui.alert('已经生成采购单 , 请到购物车再次确认采购单是否合适 ! ! !','提示',function(){});
					}else{
					}
				});
			});
		</script>
