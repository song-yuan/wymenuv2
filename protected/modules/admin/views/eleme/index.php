<style type="text/css">
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
		.ku-item{
			width:100px;
			height:100px;
			margin-right:20px;
			margin-top:20px;
			margin-left:20px;
			border-radius:5px !important;
			/*border:2px solid black;*/
			/*box-shadow: 5px 5px 5px #888888;*/
			vertical-align:middle;
		}
		.ku-item-info{
			width:144px;
			font-size:2em;
			color:black;
			text-align:center;
		}
		.ku-purple{
			/*background-color:#852b99;*/
		}
		.ku-grey{
			background-color:rgb(68,111,120);
		}
		.margin-left-right{
			margin-left:10px;
			margin-right:10px;
		}
	
</style>	
	<div class="page-content">
		<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">关系图</h4>
					</div>
					<div class="modal-body">
						<img alt="" src="">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn default" data-dismiss="modal">确定</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
	<!-- BEGIN PAGE CONTENT-->
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','外卖设置'),'url'=>$this->createUrl('waimai/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','饿了么外卖'),'url'=>$this->createUrl('eleme/index' , array('companyId'=>$this->companyId)))),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('waimai/list' , array('companyId' => $this->companyId,'type' => '0')))));?>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet purple box">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-cogs"></i><?php echo yii::t('app','饿了么外卖');?></div>
					<?php if(!$models) :?>
					<div class="actions">
						<a href="<?php echo $this->createUrl('eleme/dpsq',array('companyId'=>$this->companyId));?>" class="btn blue"><i class="fa fa-plus"></i> <?php echo yii::t('app','店铺授权');?></a>
					</div>
					<?php endif;?>
				</div>
				<div class="portlet-body">
				<div class="dataTables_wrapper form-inline">
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr >
								<th ><?php echo yii::t('app','授权时间');?></th>
								<th ><?php echo yii::t('app','授权到期时间');?></th>
								<th ><?php echo yii::t('app','操作');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td width="33%"><?php echo $model->create_at;?></td>
                                <td width="33%"><?php echo date('Y-m-d H:i:s', $model->expires_in);?></td>
								<td width="33%">
									<?php if(empty($dp)):?>
									<a class = "btn green"  href="<?php echo $this->createUrl('eleme/dpdy',array('companyId'=>$this->companyId));?>">店铺对应</a>
									<?php else:?>
									<a class = "btn payonline" href="javascript:;" style="color: #000;">店铺已对应</a>
									<a class = "btn yellow" href="<?php echo $this->createUrl('eleme/cpdy',array('companyId'=>$this->companyId));?>">菜品对应</a>
									<?php endif;?>
									<a class="btn red a" dpid="<?php echo $this->companyId;?>">解绑</a>
								</td>
							</tr>
						<?php endforeach;?>
						<?php else:?>
						<tr class="odd gradeX"><td colspan="3">暂无数据</td></tr>
						<?php endif;?>
						</tbody>
					</table>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$('.a').click(function(){
        	var dpid = $(this).attr('dpid');
        	// alert(dpid);
        	if(confirm('是否确认解绑？')){
				$.ajax({
					url:'<?php echo $this->createUrl('eleme/dpjb',array('companyId'=>$this->companyId));?>',
					data:{dpid:dpid},
					success:function(data){
						var msg = eval("("+data+")");
						if(msg.status=='success'){
							layer.msg(msg.msg);
						}else{
							alert('失败');
						}
						history.go(0);
					}
				});
			}
        });
	</script>