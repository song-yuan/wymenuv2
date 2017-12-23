
		<!--App自定义的css-->

		<style>
			.back-color{background-color: #F0F0E1;}
			.width{width:100px;}
			.left{float:left;}
			.right{float:right;}
			.padding-top{padding-top:10px;}
			.padding-top1{padding-top:8px;}
			.padding-right{padding-right:10px;}
			.padding-right1{padding-right:15px;}
			.margin-top{margin-top:15px!important;}
			.padding{padding:5px;}
			.border-none{border:0;}
			.font-small{font-size: 12px;}
			.color-l-gray{color:#323232;}
			.color-h-gray{color:#555555;}
			.color-l-red{color:red;}
			.color-l-green{color:green;}
			.color-l-orange{color:darkorange;}
			.banma{border-bottom:1px dashed white;}
			.big-ul{margin-bottom: 50px;margin-top:2px!important;}
			.edit{position: absolute;right:20px;top:11px;}
			.mui-table-view-divider{list-style: none;}
			.mui-input-row label {
				width: 30%;
			}
			.aa {
				width: 85%!important;
			}
			.mui-input-row label~input, .mui-input-row label~select, .mui-input-row label~textarea {
				width: 70%;
			}
			.mui-table-view-cell>a:not(.mui-btn) {
				padding: 0;
			}
			.fontsize{font-size: 0.9em;}
			#cityResult3{
				overflow: hidden;
				width: 70%;
			}
			.mui-toast-container{bottom: 50%!important;}
		</style>

		<header class="mui-bar mui-bar-nav mui-hbar">
		    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" style="color:white;"></a>
		    <h1 class="mui-title" style="color:white;">添加收货地址</h1>
		</header>
		<div class="mui-content">
			<form class="mui-input-group margin-top" action="<?php echo $this->createUrl('address/addaddress',array('companyId'=>$this->companyId)) ?>" method="POST">
				<div class="mui-input-row ">
					<label class="color-h-gray">收货人</label>
					<input type="text" name="name" id="name" class="mui-input-clear" placeholder="请输入收货人姓名" >
				</div>
				<div class="mui-input-row">
					<label class="color-h-gray">联系电话</label>
					<input type="number" name="mobile" id="mobile" class="mui-input-clear mui-numbox-input" placeholder="请输入收货人联系电话" oninput="if(value.length>11)value=value.slice(0,11)">
				</div>
				<ul class="mui-table-view mui-table-view-chevron mui-input-row">
					<li class="mui-table-view-cell">
						<a class="mui-navigate-right" id='showCityPicker3'>
							<label class="color-h-gray">所在地区</label>
							<div id='cityResult3' class="left border-none padding-top1 fontsize"></div>
							<input type="hidden" name="pcc" value="" id="cityResult4" />
						</a>
					</li>
				</ul>
				<div class="mui-input-row" style="height:80px;">
					<textarea id="textarea" name="street" rows="3" placeholder="详细地址" ></textarea>
				</div>
				<div class="mui-input-row mui-checkbox">
					<label class="color-h-gray aa" for="mo">设置为默认</label>
					<input name="default_address" value="1" id="mo" type="checkbox" >
				</div>
				<div class="mui-button-row" style="margin-top:20px;">
					<button type="reset" class="mui-btn mui-btn-danger width" onclick="return false;">取消</button>&nbsp;&nbsp;
					<button type="submit" class="mui-btn mui-btn-primary width" id="save" >保存</button>
				</div>
			</form>
		

		    
	    </div>

		<script type="text/javascript">
			(function($, doc) {
				$.init();
				$.ready(function() {
					/**
					 * 获取对象属性的值
					 * 主要用于过滤三级联动中，可能出现的最低级的数据不存在的情况，实际开发中需要注意这一点；
					 * @param {Object} obj 对象
					 * @param {String} param 属性名
					 */
					var _getParam = function(obj, param) {
						return obj[param] || '';
					};
					
					//-----------------------------------------
					//					//级联示例
					var cityPicker3 = new $.PopPicker({
						layer: 3
					});
					cityPicker3.setData(cityData3);
					var showCityPickerButton = doc.getElementById('showCityPicker3');
					var cityResult3 = doc.getElementById('cityResult3');
					var cityResult4 = doc.getElementById('cityResult4');
					showCityPickerButton.addEventListener('tap', function(event) {
						cityPicker3.show(function(items) {
							cityResult3.innerText = _getParam(items[0], 'text') + " " + _getParam(items[1], 'text') + " " + _getParam(items[2], 'text');
							cityResult4.value  = _getParam(items[0], 'text') + " " + _getParam(items[1], 'text') + " " + _getParam(items[2], 'text');
							//返回 false 可以阻止选择框的关闭
							//return false;
						});
					}, false);
				});

			})(mui, document);
			$('form').submit(function(event) {
				var name = document.getElementById('name');
				var mobile = document.getElementById('mobile');
				var cityResult4 = document.getElementById('cityResult4');
				var textarea = document.getElementById('textarea');
				if(name.value == ''){
					mui.toast('请填写联系人姓名 !!!',{ duration:'long', type:'div' });
					// name.focus();
					return false;
				}else if(mobile.value == ''){
					mui.toast('请填写联系电话 !!!',{ duration:'long', type:'div' });
					// mobile.focus();
					return false;
				}else if ( cityResult4.value == '') {
					mui.toast('请选择所在地区 !!!',{ duration:'long', type:'div' });
					// cityResult4.focus();
					return false;
				}else if( textarea.value == ''){
					mui.toast('请填写详细地址 !!!',{ duration:'long', type:'div' });
					// textarea.focus();
					return false;
				}
			});
			//状态提示
			var status = '<?php echo $error; ?>';
			if (status == '1') {
				mui.toast('添加失败 !!!',{ duration:'long', type:'div' });
			}else if (status == '3') {
				mui.toast('下单失败 , 请添加收货地址 !!!',{ duration:'long', type:'div' });
			}

		</script>
