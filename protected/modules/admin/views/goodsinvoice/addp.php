<style>
	.pbom{
		width: 98%;
		height: auto;
		margin-left: 1%;
		border: 1px solid pink;
	}
	.pbomhead{
		width: 98%;
		margin-left: 1%;
		border-bottom: 1px solid silver;
	}
	.pbomheadtitle{
		padding: 2px 8px;
		float: left;
		font-size: 18px;
	}
	.pbombody{
		width:98%;
		height: 400px;
		margin-left: 1%;
		border: 1px solid red;
		border-top: none;
		overflow-y: auto;
	}

	.pageend {
		margin-bottom: 10px;
	}
	.pageend .closediv {
		float: right;
		margin-right: 10px;
	}
	.pageend .closediv button{
		border: 1px solid silver;
	}
	.width40{
		width: 30% !important;
	}
	.clear{
		clear: both;
	}
	.uhide{
		display: none;
	}
	input[type="checkbox"]{
		width: 20px;
		height: 20px;
	}
	.wxcardbg{
		width: 220px;
		height: 115px;
		margin-top: 10px;
		margin-left: 15px;
		border:1px solid red;
		border-radius: 5px;
		float: left;
		color: red;
		background-color: #ff4940;
		position: relative;
	}
	.wxcardhead{
		width: 100%;
		height: 85px;
		font-size: 22px;
	}
	.wxcardheadl{
		width: 50%;
		height: 85px;
		float: left;
		border-right: 1px dashed white;
	}
	.wxcardheadll{
		width: 90px;
		height: 75px;
		margin-top: 5px;
		marin-left: 5px;
	}
	.wxcardheadll .money{
		width: 75px;
		height: 75px;
		line-height: 75px;
		float: left;
		font-size: 40px;
		font-weight: 900;
		color: white;
		text-align: center;
	}
	.wxcardheadll .unit{
		width: 15px;
		height: 75px;
		line-height: 100px;
		font-size: 22px;
		float: left;
		color: black;
	}
	.wxcardheadr{
		width: 48%;
		height: 85px;
		float: left;
	}
	.wxcardheadr .top{
		width: 100%;height: 30px;line-height: 30px;font-size: 16px;text-align: center;color: #000;
	}
	.wxcardheadr .cen{
		width: 100%;
		height: 20px;
		line-height: 20px;
		font-size: 16px;
		text-align: center;
		color: #000;
		display: none;
	}
	.wxcardheadr .bot{
		width: 100%;
		height: 35px;
		line-height: 35px;
		font-size: 18px;
		text-align: center;
		color: #000;
		font-weight: 600;
	}
	.wxcardend{
		width: 100%;
		height: 30px;
		line-height: 30px;
		font-size: 12px;
		border-top: 1px dashed pink;
		text-align: center;
		color: #fff;
	}
	.wxcardactive{
		position: absolute;
		top: 30px;
		left: 80px;
	}
	.addsave{
		float: right;
		margin: 10px 10px 0px 0px;
	}
	.addsave button{
		font-size: 18px;
		padding: 4px 10px;
		border-radius: 5px;
		background-color: #6beaff;
	}
	.cfs{text-align: center;line-height: 35px;height: 35px;width: 100%;
	}
</style>


			<div class="row">
				<div class="col-md-12">
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-reorder"></i><?php echo yii::t('app','---分配送货员');?></div>
							<div class="tools">
								<a href="javascript:;" class="collapse"></a>
							</div>
						</div>
						<div style="min-height: 300px;" class="portlet-body ">
							
							<div class="form-group cfs">
							<LABEL class="col-md-3 control-label">选择配送类型:</LABEL>
									<div class="col-md-4">
										<select id="sentype" class="form-control" >
				                            <option value="1">自配送</option>
				                            <option value="3">第三方物流</option>
			                    		</select>
			                    	</div>
			                    </div>
			                    <div class="center self">
									<div class="form-group cfs">
									<LABEL class="col-md-3 control-label">选择配送员:</LABEL>
										<div class="col-md-4">
											<select id="paymentid" class="form-control stockselect" >
					                            <?php $phone=''; if($pers):?>
					                            <?php foreach ($pers as $p):?>
					                            <?php $phone = $pers[0]['phone_number'];?>
					                            <option mobile="<?php echo $p['phone_number'];?>" value="<?php echo $p['member_name'];?>"><?php echo $p['member_name'];?></option>
					                            <?php endforeach;?>
					                            <?php endif;?>
				                    		</select>
				                    	</div>
				                    </div>
				                    <div class="form-group cfs">
									<LABEL class="col-md-3 control-label">联系电话:</LABEL>
										<div class="col-md-4">
					                    	<input id="mobile" class="form-control" disabled value="<?php echo $phone;?>"/>
				                    	</div>
				                    </div>
								</div>
								<div class="center orther uhide">
									<div class="form-group cfs">
									<LABEL class="col-md-3 control-label">第三方物流公司:</LABEL>
										<div class="col-md-4">
											<input id="wuliu_name" class="form-control" value=""/>
				                    	</div>
				                    </div>
				                    <div class="form-group cfs">
									<LABEL class="col-md-3 control-label">物流单号:</LABEL>
										<div class="col-md-4">
					                    	<input id="wuliu_nums" class="form-control" value=""/>
				                    	</div>
				                    </div>
								</div>
							<div class="addsave"style="float: right;"><button id="add_save">保存</button></div>
						</div>
						</div>
						<div class="pageend">
							<div class="closediv">
								<button id="close_modal" type="button" data-dismiss="modal" class="btn default"><?php echo yii::t('app','关 闭');?></button>
							</div>
							<div class="clear"></div>
						</div>
					</div>
							
			</div>
		
			<!-- END PAGE CONTENT-->
<script type="text/javascript">
$(document).ready(function(){
	$('.stockselect').on('change',function(){
		var mobile = $(this).find("option:selected").attr('mobile');
		$('#mobile').val(mobile);
	});
	$('#sentype').on('change',function(){
		var type = $(this).find("option:selected").val();
		if(type ==1){
			$('.self').removeClass('uhide');
            $('.orther').addClass('uhide');
		}else{
			$('.orther').removeClass('uhide');
            $('.self').addClass('uhide');
		}
	});
	$('#add_save').on('click', function(){
		var gid = '<?php echo $gid;?>';
		var type= $('#sentype').find('option:selected').val();
		layer.msg(type);
		if(type ==1){
			var name = $('.stockselect').find('option:selected').val();
			var nums = $('.stockselect').find('option:selected').attr('mobile');
		}else{
			var name = $('#wuliu_name').val();
			var nums = $('#wuliu_nums').val();
		}
// 		layer.msg(name+'@'+nums);
// 		alert(name);
		if(name == '' || nums == '' || name == undefined || nums == undefined){
			layer.msg('请填写相应信息，再保存！');
			return false;
		}else{
			var url = "<?php echo $this->createUrl('goodsinvoice/storestock',array('companyId'=>$this->companyId));?>/name/"+name+"/nums/"+nums+"/gid/"+gid+"/type/"+type;
	        $.ajax({
	            url:url,
	            type:'POST',
	            //data:plids,//CF
	            //async:false,
	            dataType: "json",
	            success:function(msg){
	                var data=msg;
	                if(data.status){
	                    layer.msg("保存成功");
	                    document.getElementById("close_modal").click();
	                    history.go(0);
	                }else{
	                    alert("保存失败");
	                }
	            },
	            error: function(msg){
	                var data=msg;
	                alert(data.msg);
	            }
	        });
		}

	});





});       
</script>	