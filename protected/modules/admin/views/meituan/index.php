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
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','外卖设置'),'url'=>$this->createUrl('waimai/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','美团外卖'),'url'=>$this->createUrl('waimai/index' , array('companyId'=>$this->companyId)))),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('waimai/list' , array('companyId' => $this->companyId,'type' => '0')))));?>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet purple box">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-cogs"></i><?php echo yii::t('app','美团外卖');?></div>
					<?php if(!$models) :?>
					<div class="actions">
						<a href="<?php echo $this->createUrl('waimai/dpbd',array('companyId'=>$this->companyId));?>" class="btn blue"><i class="fa fa-plus"></i> <?php echo yii::t('app','店铺授权');?></a>
					</div>
					<?php endif;?>
				</div>
				<div class="portlet-body">
				<div class="dataTables_wrapper form-inline">
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr >
								<th ><?php echo yii::t('app','门店名称');?></th>
								<th><?php echo yii::t('app','城市');?></th>
								<th ><?php echo yii::t('app','接入美团配送');?></th>
								<th><?php echo yii::t('app','门店信息状态');?></th>
								<th><?php echo yii::t('app','营业状态');?></th>
								<th><?php echo yii::t('app','上下线');?></th>
								<th><?php echo yii::t('app','创建时间');?></th>
								<th><?php echo yii::t('app','更新时间');?></th>
								<th><?php echo yii::t('app','操作');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
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