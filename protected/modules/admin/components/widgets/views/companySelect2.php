<select  class="form-control <?php if($multiple!=''){ echo 'input-xlarge';}else{ echo 'input-medium';}?> select2me" name="selectDpid" <?php echo $multiple;?> data-placeholder="请选择店铺...">
	<option value=""></option>
	<?php foreach ($companys as $company):?>
	<option value="<?php echo $company['dpid'];?>" <?php if(strpos($selectDpid,$company['dpid'])!==false){ echo 'selected="selected"';}?>><?php echo $company['company_name'];?></option>
	<?php endforeach;?>
</select>
