
		<style>
			.back-color{background-color: #F0F0E1;}
			.left{float:left;}
			.right{float:right;}
			.padding-top{padding-top:10px;}
			.padding-right{padding-right:10px;}
			.padding-right1{padding-right:15px;}
			.margin-top{margin-top:4px!important;}
			.padding{padding:5px;}
			.font-small{font-size: 12px;}
			.color-l-gray{color:#323232;}
			.color-l-red{color:red;}
			.color-l-green{color:green;}
			.color-l-orange{color:darkorange;}
			.banma{border-bottom:1px dashed white;}
			.big-ul{margin-bottom: 50px;margin-top:2px!important;}
			.edit{position: absolute;right:20px;top:11px;}
			.mui-table-view{margin-top:100px;}
			.mui-table-view-divider{list-style: none;}
			.ui-tab-item {
				display: table-cell;
				overflow: hidden;
				width: 1%;
				height: 50px;
				text-align: center;
				vertical-align: middle;
				white-space: nowrap;
				text-overflow: ellipsis;
				color: #929292;
			}
		</style>

		<header class="mui-bar mui-bar-nav">
		    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
		    <h1 class="mui-title">管理收货地址</h1>
		</header>
		<div class="mui-content">
			<ul class="mui-table-view ">

				<?php if ($models): ?>
				<?php foreach ($models as $key => $model): ?>
				<li class="mui-table-view-divider" >
					<div class="mui-row padding">
						<span class="left color-l-gray"><?php echo $model['name'] ?></span>
						<span class="right padding-right1 color-l-gray"><?php echo $model['mobile'] ?></span>
					</div>
					<span class="mui-row  padding-top">
						<span class=" font-small mui-ellipsis-2 padding-right"><?php echo $model['pcc'].' '.$model['street'] ?></span>
					</span>
					<span class="mui-row  padding-top">
						<span class="left ">
						  	<input name="addres"  <?php if ($model['default_address']){echo 'checked';} ?> value="<?php echo $model['lid'] ;?>" type="radio" style="zoom:180%;vertical-align: middle;" id="a<?php echo $model['lid'] ;?>">
						  	<label class="<?php if ($model['default_address']){echo 'color-l-orange';} ?>" style="height: 23px;line-height: 23px;" for="a<?php echo $model['lid'] ;?>"> [ 默认地址 ] </label>
						</span>
						<span class="right padding-right">
							<a class="color-l-green" href="<?php echo $this->createUrl('address/editaddress',array('companyId'=>$this->companyId,'lid'=>$model['lid']));?>"><span class="mui-icon mui-icon-compose"></span>编辑</a>
							<a class="color-l-red delete" href="<?php echo $this->createUrl('address/addressdelete',array('companyId'=>$this->companyId,'lid'=>$model['lid']));?>"><span class="mui-icon mui-icon-trash"></span>删除</a>
						</span>
					</span>
				</li>
				<?php endforeach; ?>
				<?php else: ?>
				<li class="mui-table-view-divider">
					<div class="mui-row padding">
					还没有添加地址,请点击 [添加收货地址]
					</div>
				</li>
				<?php endif; ?>

			</ul>
			<nav class="mui-bar mui-bar-tab" id="" style="margin-bottom: 50px;">
		        <div class="ui-tab-item " style="">
		            <button type="button" class="mui-btn mui-btn-primary mui-btn-block" onclick="fn()"  style="margin:0;height:50px;top:0;border-radius: 0;">
		            	<span class="mui-icon mui-icon-plusempty" style="font-weight:900;"></span>添加新地址
		            </button>
		        </div>
		    </nav>


	    </div>

		<script type="text/javascript">
			//删除
			var x = document.getElementsByTagName('li').length;
			for(i=0;i<x;i++){
				document.getElementsByClassName('delete')[i].addEventListener('tap', function() {
				var btnArray = ['否', '是'];
				mui.confirm('删除地址，确认？', '提示', btnArray, function(e) {
					if (e.index == 1) {
						location.href='';
						mui.toast('删除成功');
					} else {
						location.href='';
						mui.toast('删除失败');
					}
				})
			});
			}
			//添加
			function fn(){
				location.href='<?php echo $this->createUrl('address/addaddress',array('companyId'=>$this->companyId));?>';
			}
			//设置默认
			$("input[name='addres']").change(function(event) {
				var lid = $("input[name='addres']:checked").val();
				mui.post('<?php echo $this->createUrl('address/editaddress',array('companyId'=>$this->companyId));?>',
					{lid: lid,
					default_address:1,
					},
					function(data) {
						if (data == 1) {
							mui.toast('修改成功');
							$('.color-l-orange').removeClass('color-l-orange');
							$("input[name='addres']:checked").parent('span').children('label').addClass('color-l-orange');
						}else if(data == 0){
							mui.toast('修改失败');
							$(".color-l-orange").parent('span').children('input').attr('checked','checked');
						}
				});
			});

			//状态提示
			var status = '<?php echo $success; ?>';
			if (status == '1') {
				mui.toast('添加成功');
			}else if(status == '2'){
				mui.toast('修改成功');
			}
		</script>
