<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'productCategory-form',
				'action'=>$action,
				'enableAjaxValidation'=>false,
				'enableClientValidation'=>true,
				'clientOptions'=>array(
					'validateOnSubmit'=>false,
				),
				'htmlOptions'=>array(
					'class'=>'form-horizontal'
				),
			)); ?>
<h5 style="margin-left: 150px;">选择<span style="color: red;"><?php echo $name?><input type="hidden" name="eleme[elemeId]" value="<?php echo $elemeId?>"></span>关联菜品</h5>
<div class="form-group">
     <label class="col-md-3 control-label" for="ProductCategory_show_type">关联菜品</label>
     <div class="col-md-4">
        <select class="form-control" placeholder="关联菜品" name="eleme[phs_code]">
	        <option value="">选择菜品</option>
	        <?php foreach ($models as $model):?>
			<option value="<?php echo $model->phs_code?>"><?php echo $model->product_name?></option>
			<?php endforeach;?>    
        </select>
     </div>
</div>
<div class="form-group">
     <label class="col-md-3 control-label" for="ProductCategory_show_type">套餐关联</label>
     <div class="col-md-4">
         <select class="form-control" placeholder="套餐关联" name="eleme[phps_code]">
         <option value="">选择套餐</option>
            <?php foreach ($modelsets as $set):?>
					<option value="<?php echo $set->pshs_code;?>"><?php echo $set->set_name;?></option>
				<?php endforeach;?> 
          </select>
     </div>
</div>
<div class="modal-footer">
	<input type="submit" class="btn green" id="create_btn" value="<?php echo yii::t('app','确 定');?>">
</div>
<?php $this->endWidget(); ?>