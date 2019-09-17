<?php $basePath = Yii::app()->baseUrl;?>
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/app.css">
<link rel="stylesheet" type="text/css" href="<?php echo $basePath;?>/css/appreport/mui.picker.min.css" />
<script src="<?php echo $basePath;?>/js/mall/jquery-1.9.1.min.js"></script>
<header class="mui-bar mui-bar-nav">
	<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left" href="<?php echo $this->createUrl('appReport/index',array('companyId'=>$this->companyId));?>"></a>
	<h1 class="mui-title">添加厂商信息</h1>
</header>
<div class="sd">
	
</div>
<div class="dp">
	<form class="mui-input-group" method="POST" onsubmit="return validate()">
		<div class="mui-input-row">
			<label>厂商类别</label>
			<select id="classification_id" name="ManufacturerInformation[classification_id]">
				<option value="0">---请选择---</option>
				<?php foreach ($classifications as $class):?>
				<option value="<?php echo $class['lid'];?>" <?php if($model->lid==$class['lid']){ echo 'selected';}?>><?php echo $class['classification_name'];?></option>
				<?php endforeach;?>
			</select>
		</div>
		<div class="mui-input-row">
			<label>厂商编号</label>
			<input id="manufacturer_code" type="text" name="ManufacturerInformation[manufacturer_code]" placeholder="厂商编号" value="<?php echo $model->manufacturer_code;?>">
		</div>
		<div class="mui-input-row">
			<label>厂商名称</label>
			<input id="manufacturer_name" type="text" name="ManufacturerInformation[manufacturer_name]" placeholder="厂商名称" value="<?php echo $model->manufacturer_name;?>">
		</div>
		<div class="mui-input-row">
			<label>邮编</label>
			<input id="post_code" type="text" name="ManufacturerInformation[post_code]" placeholder="邮编" value="<?php echo $model->post_code;?>">
		</div>
		<div class="mui-input-row">
			<label>公司地址</label>
			<input id="address" type="text" name="ManufacturerInformation[address]" placeholder="公司地址" value="<?php echo $model->address;?>">
		</div>
		<div class="mui-input-row">
			<label>联系人</label>
			<input id="contact_name" type="text" name="ManufacturerInformation[contact_name]" placeholder="联系人" value="<?php echo $model->contact_name;?>">
		</div>
		<div class="mui-input-row">
			<label>联系电话</label>
			<input id="contact_tel" type="text" name="ManufacturerInformation[contact_tel]" placeholder="联系电话" value="<?php echo $model->contact_tel;?>">
		</div>
		<div class="mui-input-row">
			<label>传真</label>
			<input id="contact_fax" type="text" name="ManufacturerInformation[contact_fax]" placeholder="传真" value="<?php echo $model->contact_fax;?>">
		</div>
		<div class="mui-input-row">
			<label>电子邮箱</label>
			<input id="email" type="text" name="ManufacturerInformation[email]" placeholder="电子邮箱" value="<?php echo $model->email;?>">
		</div>
		<div class="mui-input-row">
			<label>开户银行</label>
			<input id="bank" type="text" name="ManufacturerInformation[bank]" placeholder="开户银行" value="<?php echo $model->bank;?>">
		</div>
		<div class="mui-input-row">
			<label>开户账号</label>
			<input id="bank_account" type="text" name="ManufacturerInformation[bank_account]" placeholder="开户账号" value="<?php echo $model->bank_account;?>">
		</div>
		<div class="mui-input-row">
			<label>纳税账号</label>
			<input id="tax_account" type="text" name="ManufacturerInformation[tax_account]" placeholder="纳税账号" value="<?php echo $model->tax_account;?>">
		</div>
		<div class="mui-input-row" style="height:auto;">
			<label>备注</label>
			<textarea id="remark" name="ManufacturerClassification[remark]" rows="5" placeholder="输入备注"><?php echo $model->remark;?></textarea>
		</div>
		<div class="mui-button-row">
			<button type="submit" class="mui-btn mui-btn-primary">确认</button>&nbsp;&nbsp;
			<button type="button" class="mui-btn mui-btn-danger" onclick="back()">取消</button>
		</div>
	</form>
</div>
<script>
function validate(){
	var cid = $('#classification_id').val();
	if(cid=='0'){
		mui.alert('厂商类别必须选择', '提示');
		return false;
	}
	var cname = $('#manufacturer_name').val();
	if(cname==''){
		mui.alert('厂商名称必须填写', '提示');
		return false;
	}
	var contname = $('#contact_name').val();
	if(contname==''){
		mui.alert('联系人必须填写', '提示');
		return false;
	}
	var conttel = $('#contact_tel').val();
	if(conttel==''){
		mui.alert('联系电话必须填写', '提示');
		return false;
	}
	return true;
}
function back(){
	location.href = "<?php echo $this->createUrl('appReport/csxx',array('companyId'=>$this->companyId));?>";
}
</script>