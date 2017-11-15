		<style>

			 h4{
			 	font-size: 1em;
		        margin-top: 15px;
		        padding:  15px 0;
		        background-color: #C7C7CC;
		    }

		    .field-contain label{
		        width: auto;
		        padding-right: 0;
		    }

		    .field-contain input[type='text']{
		        width: 40px;
		        height: 30px;
		        padding: 5px 0;
		        float: none;
		        text-align: center;
		    }
		    .c-red{color:red;}
		    .c-green{color:green;}
		    .m-top{margin-top: 60px;}
		</style>


	<body>
		<header class="mui-bar mui-bar-nav mui-hbar">
		    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"  style="color:white;"></a>
		    <h1 class="mui-title"  style="color:white;">库存参数设置</h1>
		</header>
		<div class="mui-content">
		    <div class="mui-content-padded">
		     	<div style="height: 100px;background-color: white;">
			        <h4>日均销量 = 最近 <span id='inline-range-val' class="c-red"><?php if ($model['csales_day']) {echo $model['csales_day']; }else{echo '20';} ?></span> 天的日均销量</h4>
			        <div class="mui-input-row mui-input-range">
			            <label>日均天数：</label>
			            <input type="range" id='inline-range' value="<?php if ($model['csales_day']) {echo $model['csales_day']; }else{echo '20';} ?>" min="1" max="31" >
			        </div>
		     	</div>
		     	<div style="height: 138px;background-color: white;">
			     	<h4>日均销量 x <span id='inline-range1-val' class="c-red"><?php if ($model['csafe_min_day']) {echo $model['csafe_min_day']; }else{echo '3';} ?></span>天 < <span class="c-green">安全库存</span> < 日均销量 x <span id='inline-range2-val' class="c-red"><?php if ($model['csafe_max_day']) {echo $model['csafe_max_day']; }else{echo '20';} ?></span>天 </h4>
			        <div class="mui-input-row mui-input-range">
			            <label>最小天数：</label>
			            <input type="range" id='inline-range1' value="<?php if ($model['csafe_min_day']) {echo $model['csafe_min_day']; }else{echo '3';} ?>" min="1" max="31" >
			        </div>
			        <div class="mui-input-row mui-input-range">
			            <label>最大天数：</label>
			            <input type="range" id='inline-range2' value="<?php if ($model['csafe_max_day']) {echo $model['csafe_max_day']; }else{echo '20';} ?>" min="1" max="31" >
			        </div>
		     	</div>
		        <button type="button" class="m-top mui-btn mui-btn-block mui-btn-primary" id="sure">确认</button>


		    </div>
		</div>
	</body>
	<script>
		mui.init({
				swipeBack:true //启用右滑关闭功能
			});
	    //监听input事件，获取range的value值，也可以直接element.value获取该range的值
	    var rangeList = document.querySelectorAll('input[type="range"]');
	    for(var i=0,len=rangeList.length;i<len;i++){
	        rangeList[i].addEventListener('input',function(){
	            if(this.id.indexOf('field')>=0){
	                document.getElementById(this.id+'-input').value = this.value;
	            }else{
	                document.getElementById(this.id+'-val').innerHTML = this.value;
	            }
	        });
	    }


	    mui('.mui-content-padded').on('tap', '#sure', function() {

			var csales_day = $('#inline-range').val();
			var csafe_min_day = $('#inline-range1').val();
			var csafe_max_day = $('#inline-range2').val();
			console.log('日均天数'+csales_day);
			console.log('最小天数'+csafe_min_day);
			console.log('最大天数'+csafe_max_day);
			mui.post('<?php echo $this->createUrl("myinfo/stockSetting",array("companyId"=>$this->companyId)) ?>',{  //请求接口地址
				   csales_day:csales_day, // 参数  键 ：值
				   csafe_min_day:csafe_min_day,
				   csafe_max_day:csafe_max_day,
				},
				function(data){ //data为服务器端返回数据
					//自己的逻辑
					console.log(data);
					if (data=='1111') {
						mui.toast('保存成功!!!');
					}else{
						mui.toast('保存失败!!!');
					}

				},'json'
			);
	    });
	</script>