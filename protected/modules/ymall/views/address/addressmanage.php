
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
			.mui-toast-container{bottom: 50%!important;}
		</style>

		<header class="mui-bar mui-bar-nav mui-hbar">
		    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" style="color:white;"></a>
		    <a class="mui-pull-right  edit" style="color:white;" onclick="fn()">添加</a>
		    <h1 class="mui-title" style="color:white;">管理收货地址</h1>
		</header>
		<div class="mui-content ">
				<ul class="mui-table-view " style="padding-bottom: 50px;overflow:scroll;">
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
								<a class="color-l-red delete" lid="<?php echo $model['lid']; ?>"><span class="mui-icon mui-icon-trash"></span>删除</a>
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
	    </div>

		<script type="text/javascript">
			//删除
			$('.delete').on('tap', function() {
				var btnArray = ['否', '是'];
				var lid = $(this).attr('lid');
				// alert(lid);
				$(this).parent().parent().parent().attr('id', 'absign');
				mui.confirm('删除地址，确认？', '提示', btnArray, function(e) {
					if (e.index == 1) {
						mui.post('<?php echo $this->createUrl("address/deleteaddress",array("companyId"=>$this->companyId)) ?>',{
								   lid:lid,
								},
								function(data){
									if (data == 1) {
										$('#absign').fadeOut(1000).remove();
										mui.toast('删除成功',{ duration:'long', type:'div' });
									}else if(data == 0) {
										mui.toast('因网络原因删除失败 , 请重新删除 ! ! !',{ duration:'long', type:'div' });
									}
								},'json'
							);


					}
				})
			});
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
							mui.toast('修改成功',{ duration:'long', type:'div' });
							$('.color-l-orange').removeClass('color-l-orange');
							$("input[name='addres']:checked").parent('span').children('label').addClass('color-l-orange');
						}else if(data == 0){
							mui.toast('修改失败',{ duration:'long', type:'div' });
							$(".color-l-orange").parent('span').children('input').attr('checked','checked');
						}
				});
			});

			//状态提示
			var status = '<?php echo $success; ?>';
			if (status == '1') {
				mui.toast('添加成功',{ duration:'long', type:'div' });
			}else if(status == '2'){
				mui.toast('修改成功',{ duration:'long', type:'div' });
			}
		</script>
