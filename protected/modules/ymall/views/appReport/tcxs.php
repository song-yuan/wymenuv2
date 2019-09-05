<?php $basePath = Yii::app()->baseUrl;?>
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/app.css">
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/mui.picker.min.css" />
<header class="mui-bar mui-bar-nav">
	<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$this->companyId));?>"></a>
	<h1 class="mui-title">套餐销售报表</h1>
</header>
<div class="sd">
	<form method="post">
	<ul class="mui-table-view">
		<li class="mui-table-view-cell">
			<span>门店</span>
			<span style="padding-left: 135px;"><?php if(empty($type)){echo Helper::getCompanyName($this->companyId);}else{echo $type['group_name'];} ?></span>
		</li>
		<li class="mui-table-view-cell">
			<span>开始时间</span>
			<span id='demo2' style="padding-left: 95px;" data-options='{"type":"date"}' class="btn mui-navigate-right"><?php if(empty($date)){?><a>选择日期</a><?php }else{echo $date['start'];}?></span>
			<input id="date1" type="hidden" name="date[start]" value="<?php echo $date['start'];?>">
		</li>
		<li class="mui-table-view-cell">
			<span>结束时间</span>
			<span id='demo4' style="padding-left: 95px;" data-options='{"type":"date"}' class="btn mui-navigate-right"><?php if(empty($date)){?><a>选择日期</a><?php }else{echo $date['End'];}?></span>
			<input id="date2" type="hidden" name="date[End]" value="<?php echo $date['End'];?>">
		</li>
		<li>
			<button type="submit" class="mui-btn mui-btn-primary mui-btn-block">查询</button>
		</li>
	</ul>
	</form>
</div>
<div class="dp">
	<div style="margin-top: 10px;">
		<ul class="ul">
			<li class="li1">套餐名称</li>
			<li class="li2">销量</li>
			<li class="li3">应收</li>
			<li class="li5">实收</li>
		</ul>
	</div>
	<div style="clear: both;"></div>
	<div class="dp1">
		<ul class="ul">
		<?php if(!empty($orders)):?>
			<?php foreach($orders as $order):?>
			<li class="li1"><?php echo $order['set_name'];?></li>
			<li class="li2"><?php echo $order['all_setnum'];?></li>
			<li class="li3"><?php echo round($order['all_orisetprice'],2);?></li>
			<li class="li5"><?php echo round($order['all_setprice'],2);?></li>
			<div style="clear: both;"></div>
		<?php endforeach;?>
			<?php endif;?>
		</ul>
	
	</div>
</div>
<script src="<?php echo $basePath;?>/js/appreport/mui.picker.min.js"></script>
<script>
	(function($) {
		$.init();
		var btns = $('.btn');
		// alert(btns.length);
		btns.each(function(i, btn) {
			btn.addEventListener('tap', function() {
				var optionsJson = this.getAttribute('data-options') || '{}';
				var options = JSON.parse(optionsJson);
				var id = this.getAttribute('id');
				// alert(id);
				/*
				 * 首次显示时实例化组件
				 * 示例为了简洁，将 options 放在了按钮的 dom 上
				 * 也可以直接通过代码声明 optinos 用于实例化 DtPicker
				 */
				var picker = new $.DtPicker(options);
				picker.show(function(rs) {
					/*
					 * rs.value 拼合后的 value
					 * rs.text 拼合后的 text
					 * rs.y 年，可以通过 rs.y.vaue 和 rs.y.text 获取值和文本
					 * rs.m 月，用法同年
					 * rs.d 日，用法同年
					 * rs.h 时，用法同年
					 * rs.i 分（minutes 的第二个字母），用法同年
					 */
					 // alert(rs.text);
					 // $('#'+id).html(rs.text);
					$('#'+id)[0].innerText = rs.text;

					/* 
					 * 返回 false 可以阻止选择框的关闭
					 * return false;
					 */
					/*
					 * 释放组件资源，释放后将将不能再操作组件
					 * 通常情况下，不需要示放组件，new DtPicker(options) 后，可以一直使用。
					 * 当前示例，因为内容较多，如不进行资原释放，在某些设备上会较慢。
					 * 所以每次用完便立即调用 dispose 进行释放，下次用时再创建新实例。
					 */
					picker.dispose();
					var date1 = document.getElementById("demo2").innerText;
					var date2 = document.getElementById("demo4").innerText;
					document.getElementById("date1").value = date1;
					document.getElementById("date2").value = date2;
				});
			}, false);
		});

	})(mui);
</script>