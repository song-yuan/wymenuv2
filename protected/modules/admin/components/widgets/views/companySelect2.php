<select  class="form-control input-medium select2me" name="selectDpid" data-placeholder="请选择店铺...">
	<option value=""></option>
	<?php foreach ($companys as $company):?>
	<option value="<?php echo $company['dpid'];?>" <?php if($company['dpid']==$selectDpid){ echo 'selected="selected"';}?>><?php echo $company['company_name'];?></option>
	<?php endforeach;?>
</select>
