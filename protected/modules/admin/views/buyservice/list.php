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
		
		.margin-left-right{
			margin-left:10px;
			margin-right:10px;
		}
		.cf-black{
			color: #000 !important;
			
		}
	</style>
<div class="page-content">
	<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               

	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	

		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','菜品设置'),'url'=>''))));?>

	
			<div class="portlet purple box">
				
				<div class="portlet-body clearfix" >
					
					<div class="panel_body row">
					<p>短息套餐购买</p>
						<div class="list col-sm-3 col-xs-12">
			                <a href="<?php echo $this->createUrl('message/index',array('companyId'=>$this->companyId));?>">
			                	<div class="margin-left-right">
			                	<div class="list_big"><i class="fa fa-home"></i>短信套餐购买</div>
			                	<div class="list_small">购买短信套餐，短信用来微信会员注册</div>
			                	</div>
			                </a> 
						</div>
					</div>
					
				</div>
			</div>
		
	<!-- END PAGE CONTENT-->
	<script>
	$(document).ready(function() {
		window.$ = function(id) {
			  return (typeof id == 'string') ? document.getElementById(id) : id;
			}
		  	var k = $('cpxf');
		  	var k2 = $('kwxf');
		  	var k3 = $('ylxfs');
		  	var k4 = $('pfxfs');
		  	//if(!k) return;
		  	if(k){
			  	onhover(function() {
			      	msgfunction('cpxf');
			  	}, k, 1500);
				onhover(function() {
					msgfunction('kwxf');
			    }, k2, 1500);
		  	}
		  	if(k3){
				onhover(function() {
					msgfunction('ylxfs');
			    }, k3, 1500);
				onhover(function() {
					msgfunction('pfxfs');
			    }, k4, 1500);
		  	}
		    //alert(1);
		});
		 
		function onhover(fun, obj, time) {
		  var s;
		  obj.onmouseover = function() {
		      s = setTimeout(fun, time);
		    };
		  obj.onmouseout = function() {
		      if(!s) return;
		      clearTimeout(s);
		      layer.closeAll('tips');
		    };
		};
		function msgfunction(type){
			var divid = "#"+type;
			if(type == 'cpxf'){
				var msg = '菜品下发之前，请先进行如下操作：<br/>1、添加菜品分类，并设置二级分类；<br/>2、添加菜品；';
				}
			if(type == 'kwxf'){
				var msg = '口味下发之前，请先进行如下操作：<br/>1、添加口味；<br/>2、进行菜品的口味对应；<br/>3、进行菜品下发；';
				}
			if(type == 'ylxfs'){
				var msg = '原料下发之前，请先进行如下操作：<br/>1、添加原料分类；<br/>2、添加入库单位和零售单位；<br/>3、设置单位系数；<br/>4、添加原料信息；';
				}
			if(type == 'pfxfs'){
				var msg = '配方下发之前，请先进行如下操作：<br/>1、添加产品配方详情；<br/>2、进行原料下发；<br/>';
				}
			//alert(1);
			layer.tips(msg,divid, {
				  tips: [4, '#78BA32'],
				  time: 0,
				  shift: 5,
				  closeBtn: 0,
				});
			
			}
//         $(document).ready(function() {
//             $('.relation').click(function(){
//                 $('.modal').modal();
//            });
//         });
	</script>