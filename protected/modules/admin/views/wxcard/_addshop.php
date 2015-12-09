				<div class="modal-header">
					<h4 class="modal-title">选择门店</h4>
				</div>
				<div class="modal-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="sample_3">
							<thead>
								<tr>
									<th width="3%" class="table-checkbox"><input type="checkbox" class="group-checkable" id="select_all" data-set="#sample_3 .checkboxes" /></th>
									<th width="20%">门店名称</th>
									<th width="20%">地址</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($shops as $shop):?>
								<tr>
									<td><input type="checkbox" name = "shopIds[]" class="checkboxes" value="<?php echo $shop->wx_location_id;?>" /></td>
									<td><?php echo $shop->shop->shop_name;?></td>
									<td><?php echo $shop->shop->province.$shop->shop->city.$shop->shop->area.$shop->shop->street;?></td>
								</tr>
								<?php endforeach;?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer" style="text-align:center;">
					<button type="button" data-dismiss="modal" id="cancel_shop_btn" class="btn default">取 消</button>
					<input type="submit" class="btn green" id="add_shop_btn" value="确 定"/>
				</div>
		<script>
		$(document).ready(function(){
			App.init();
			$('#sample_3 #select_all').change(function(){
	       	  var set = $(this).attr("data-set");
                var checked = $(this).is(":checked");
                $(set).each(function () {
                    if (checked) {
                        $(this).attr("checked", true);
                    } else {
                        $(this).attr("checked", false);
                    }
                    $(this).parents('tr').toggleClass("active");
                });
                $.uniform.update(set);
	       });
		});
		</script>