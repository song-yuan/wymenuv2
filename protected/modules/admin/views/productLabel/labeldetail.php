<style>
table tr td{border: 10px solid white;}
.radio{padding-top: 0px!important;}
#btn1{margin-left:45%;}
label{padding-right:30px;}
</style>
	<!-- BEGIN PAGE -->
		<div class="page-content">
			<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title">Modal title</h4>
						</div>
						<div class="modal-body">
							Widget settings form goes here
						</div>
						<div class="modal-footer">
							<button type="button" class="btn blue">Save changes</button>
							<button type="button" class="btn default" data-dismiss="modal">Close</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			<!-- /.modal -->
			<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader',
	array('breadcrumbs'=>array(
			array('word'=>yii::t('app','打印设置'),'url'=>$this->createUrl('product/list' , array('companyId'=>$this->companyId,'type' =>2))),
			array('word'=>yii::t('app','标签打印'),'url'=>$this->createUrl('productLabel/index' , array('companyId'=>$this->companyId))),
			array('word'=>yii::t('app','编辑标签'),'url'=>''),
		),
		'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('productLabel/index' , array('companyId' => $this->companyId)))
	));?>

			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row ">
				<div class="col-md-12 ">
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-reorder"></i><?php echo yii::t('app','编辑标签');?></div>
						</div>
						<div class="portlet-body form">
							<!-- BEGIN FORM-->
							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'Product-label-form',
									'action' => $this->createUrl('productLabel/labeldetail' , array('companyId' => $this->companyId,'product_id'=>$plid)),
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							)); ?>

							<table style="width: 90%;margin-left: 5%;">
							<caption><h1><b><?php echo $pname; ?></b></h1></caption>
							<?php if ($models) : ?>
							<tr >
								<td style="width: 15%;">是否打印条形码 : </td>
								<td style="width: 65%;">
								
								<input type="radio" name="print_bar" id="baryes" value="1" <?php if($models[0]['is_print_bar']==1) echo 'checked'; ?> ><label for="baryes">是</label>

								
								<input type="radio" name="print_bar" id="barno" value="0" <?php if($models[0]['is_print_bar']==0) echo 'checked'; ?> ><label for="barno">否</label>
								</td>
								<td><input type='hidden' name='llid' value="<?php echo $models[0]['lid']; ?>"></td>
							</tr>
							<tr >
								<td>是否打印生产日期 : </td>
								<td>
								
								<input type="radio" name="print_date" id="dateyes" value="1" <?php if($models[0]['is_print_date']==1) echo 'checked'; ?> ><label for="dateyes">是</label>

								
								<input type="radio" name="print_date" id="dateno" value="0" <?php if($models[0]['is_print_date']==0) echo 'checked'; ?>><label for="dateno">否</label><span style="color:red;"> 注意:若打印条码与生产日期,则生产日期会在第 4 行开头,第 4 行内容请注意长度</span>
								</td>
								<td></td>
							</tr>

							<?php foreach($models as $key => $model): ?>
								<tr class='indexx'>
									<td>第<span class="nums" style="color:red;font-weight: 900;"><?php echo $key+2; ?></span>行内容 : </td>
									<td>
									<input type="text" name="content[]" value="<?php echo $model['content']; ?>" class="form-control">
									<input type='hidden' name='lid[]' value="<?php echo $model['dlid']; ?>">
									</td>
									<td>字体 : </td>
									<td>
										<select name="font_size[]" id="" class="btn form-control">
											<option value="11" <?php if($model['font_size']==11) echo "selected"; ?> >小</option>
											<option value="21" <?php if($model['font_size']==21) echo "selected"; ?> >中</option>
											<option value="22" <?php if($model['font_size']==22) echo "selected"; ?> >大</option>
										</select>
									</td>
									<td>
									<i class="fa fa-plus btn btn-xs green add_btn"></i>
									<i class="fa fa-times btn btn-xs red btn_delete del" val="<?php echo $model['dlid']; ?>"></i>
									</td>
								</tr>
							<?php endforeach; ?>





							<!-- 新添加标签 -->
							<?php else: ?>
							<tr >
								<td style="width: 15%;">是否打印条形码 : </td>
								<td style="width: 65%;">
								
								<input type="radio" name="print_bar" id="baryes" value="1" checked ><label for="baryes">是</label>
								
								<input type="radio" name="print_bar" id="barno" value="0"  ><label for="barno">否</label>
								</td>
								<td><input type='hidden' name='llid' value=""></td>
							</tr>
							<tr >
								<td>是否打印生产日期 : </td>
								<td>
								
								<input type="radio" name="print_date" id="dateyes" value="1" checked><label for="dateyes">是</label>
								
								<input type="radio" name="print_date" id="dateno" value="0"><label for="dateno">否</label>
								<span style="color:red;">注意:若打印条码与生产日期,则生产日期会在第 4 行开头,第 4 行内容请注意长度</span>
								</td>
							</tr>
							<tr class='indexx'>
								<td>第<span class="nums" style="color:red;font-weight: 900;">2</span>行内容 : </td>
								<td><input type="text" name="content[]" value="<?php  ?>" class="form-control"><input type='hidden' name='lid[]' value=""></td>
								<td>字体 : </td>
								<td>
									<select name="font_size[]" id="" class="btn form-control">
										<option value="11">小</option>
										<option value="21" selected>中</option>
										<option value="22" >大</option>
									</select>
								</td>
								<td>
								<i class="fa fa-plus btn btn-xs green add_btn"></i>
								<i class="fa fa-times btn btn-xs red btn_delete" val=''></i>
								</td>
							</tr>
							<?php endif; ?>

							</table>
							<input type="submit" value="提交保存" class="btn blue" id="btn1">
							<?php $this->endWidget(); ?>
							<!-- END FORM-->
						</div>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->
		</div>
		<!-- END PAGE -->
<script>
	i=0;
	y =$('.indexx').length;
$(".add_btn").live('click',function() {
	var x=0;
	if ($('input:radio[name="print_bar"]:checked').val()==1) {
		x = 1;
	}
	if ($('input:radio[name="print_date"]:checked').val()==1) {
		x = x + 1;
	}
	i++;
	// alert('y = '+y);
	// alert('i = '+i);
	// alert('x = '+x);
	if (x+y+i<6) {
		$("table").append(
		"<tr class='indexx'><td>第<span style='color:red;font-weight:900;'>"+(x+i+y-1)+"</span>行内容 : </td><td><input type='text' name='content[]' value='' class='form-control'><input type='hidden' name='lid[]' value=''></td><td>字体 : </td><td><select name='font_size[]' class='btn form-control'><option value='11'>小</option><option value='21' selected>中</option><option value='22' >大</option></select></td><td><i class='fa fa-times btn btn-xs red btn_delete' val=''></i></td></tr>"
					);
	}else{
		--i;
		layer.msg('标签行数不能超过5个!',{icon: 5});
	}
	
});
$(".btn_delete").live('click',function(){
	// $(this).parent().parent().empty();
	if (confirm('确认要删除这一列吗?')) {
		var lid = $(this).attr('val');
		// alert(lid);
		if (lid!='') {
			location.href="<?php echo $this->createUrl('productLabel/delete' , array('companyId'=>$this->companyId,'product_id'=>$plid,'product_name'=>$pname)) ?>"+"/lid/"+lid;
		}else{
			--i;
			$(this).parent().parent().remove();

		}
	}
});
</script>