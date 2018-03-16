<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>壹点吃</title>

    <!-- Bootstrap CSS -->
	<!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
	<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<style>
		.type{margin-top:6px;}
		.container{padding-top: 20px;background: #eef;}
		.form-control-static{border:1px solid #ccc; border-radius: 3px;padding-left: 4px;}
		.formc{border:1px solid #ccc; border-radius: 3px;padding-left: 4px;min-height: 34px;padding-top: 7px;padding-bottom: 7px;margin-bottom: 0;}
		.active {
		    border-color: #ccc;
		    border-color: rgba(82,168,236,.8);
		    outline: 0;
		    outline: thin dotted\9;
		    -webkit-box-shadow: 0 0 8px rgba(82,168,236,.6);
		    box-shadow: 0 0 8px rgba(82,168,236,.6);
		}
		.no{width: 50px;font-weight:900; }
		.show{height: 37px;}
		table{width: 90%!important;margin:0 auto;}
	</style>
  </head>
  <body>
    <div class="container">
    <div class="form-horizontal">
		<div class="form-group">
			<label class="col-sm-2 control-label">客户 : </label>
			<div class="col-sm-3">
				<p class="form-control-static">email@example.com</p>
			</div>
			<div class="col-sm-2">
				<button class="btn btn-primary">选择</button>
			</div>
		</div>
		<div  class="form-group">
			<label class="col-sm-2 control-label">服务类型 : </label>
			<span class="col-sm-2 type">
			<input type="radio" name="PON" value="EPON" id="EPON">
			<label  for="EPON">EPON</label>
			</span>
			<span class="col-sm-2 type">
			<input type="radio" name="PON" value="GPON" id="GPON" checked="checked">
			<label  for="GPON">GPON</label>
			</span>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">MAC : </label>
			<div class="col-sm-2" style="min-width: 150px;">
				<input type="text" name="" class="formc" id="MAC2">
			</div>
			<label class="col-sm-1 control-label show" id="GPSN1">GPSN :</label>
			<div class="col-sm-3 show" >
				<input type="text" name="" class="formc" id="GPSN2">
			</div>
			<label class="col-sm-1 control-label">SN : </label>
			<div class="col-sm-3">
				<input type="text" name="" class="formc" id="SN2">
			</div>
		</div>
		<div class="form-group">
			<table class="table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<th>NO.</th>
					<th>MAC</th>
					<th class="show">GPON</th>
					<th>EPON</th>
				</tr>
				</thead>
				<tbody>
				<!-- <tr>
					<td class="no">1</td>
					<td id="m"></td>
					<td id="g" class="show"></td>
					<td id="s"></td>
				</tr> -->
				</tbody>
			</table>
		</div>
		<div class="row">
			<button class="btn btn-primary col-md-offset-9 ">下一箱</button>
			<button class="btn btn-primary" style="margin-left: 30px;">查重</button>
		</div>
	</div>
	</div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
	<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script>
	$("#MAC2").addClass('active').focus();
	$("input[type='radio']").change(function(event) {
		/* Act on the event */
		if ($('#GPSN1').attr('class') == 'col-sm-1 control-label show') {
			$('.show').removeClass('show').addClass('hide');
			//焦点事件
			$("#MAC2").addClass('active').focus();
		} else {
			$('.hide').removeClass('hide').addClass('show');
			//焦点事件
			$("#MAC2").addClass('active').focus();
		}
	});

	// // 刷卡 或者 扫码输入
 //    $(document).on('keydown',function(event){
 //        var keycode=parseInt(event.which)-48;
 //        console.log(keycode);
 //        if(keycode==142 ||( keycode>=0 && keycode <10))
 //        {
 //            if(keycode==142)
 //            {
 //                keycode = ".";
 //            }
 //        }
 //        //删除键
 //        if(event.which==8)
 //        {

 //        }
 //        //回车键
 //        if(event.which==13)
 //        {

 //        }
 //    });
	$("#MAC2").change(function(event) {
		/* Act on the event */
		// alert($("input[type='radio']:checked").attr('id'));
		if ($("input[type='radio']:checked").attr('id') == 'GPON') {
			$('#MAC2').removeClass('active');
			//焦点事件
			$("#GPSN2").addClass('active').focus();
		} else {
			$('#MAC2').removeClass('active');
			//焦点事件
			$("#SN2").addClass('active').focus();
		}
	});

	$("#GPSN2").change(function(event) {
		/* Act on the event */
			$('#GPSN2').removeClass('active');
			//焦点事件
			$("#SN2").addClass('active').focus();
	});

	var no = 1;
	$("#SN2").change(function(event) {
		/* Act on the event */
		// alert($("input[type='radio']:checked").attr('id'));
		if ($("input[type='radio']:checked").attr('id') == 'GPON') {
			$('#SN2').removeClass('active');
			//在table中添加行数据
			var mac = $('#MAC2').val();
			var gpsn = $('#GPSN2').val();
			var sn = $('#SN2').val();
			if(no > 0 && no < 21){
				var str = "<tr><td class='no'>"+ no +"</td><td>"+ mac +"</td><td class='show'>"+ gpsn +"</td><td>"+ sn +"</td></tr>";
				$('tbody').append(str);
				no++;
			} else if(no > 20){
				alert('已经满箱，请点击查重或者点击下一箱！');
			}
			//焦点事件
			$("#MAC2").addClass('active').focus();
			$('#MAC2').val("");
			$('#GPSN2').val("");
			$('#SN2').val("");
		} else {
			$('#SN2').removeClass('active');
			//在table中添加行数据
			var mac = $('#MAC2').val();
			var sn = $('#SN2').val();
			if(no > 0 && no < 21){
				var str = "<tr><td class='no'>"+ no +"</td><td>"+ mac +"</td><td>"+ sn +"</td></tr>";
				$('tbody').append(str);
				no++;
			} else if(no > 20){
				alert('已经满箱，请点击查重或者点击下一箱！');
			}
			//焦点事件
			$("#MAC2").addClass('active').focus();
			$('#MAC2').val("");
			$('#SN2').val("");
		}
	});
	if ($("#MAC2").val().length == 17) {
		if ($("input[type='radio']:checked").attr('id') == 'GPON') {
			$('#MAC2').removeClass('active');
			//焦点事件
			$("#GPSN2").addClass('active').focus();
		} else {
			$('#MAC2').removeClass('active');
			//焦点事件
			$("#SN2").addClass('active').focus();
		}
	}
	if ($("#GPSN2").val().length == 12) {
		$('#GPSN2').removeClass('active');
		//焦点事件
		$("#SN2").addClass('active').focus();
	}
	if ($("#SN2").val().length == 16) {
		if ($("input[type='radio']:checked").attr('id') == 'GPON') {
			$('#SN2').removeClass('active');
			//在table中添加行数据
			var mac = $('#MAC2').val();
			var gpsn = $('#GPSN2').val();
			var sn = $('#SN2').val();
			if(no > 0 && no < 21){
				var str = "<tr><td class='no'>"+ no +"</td><td>"+ mac +"</td><td class='show'>"+ gpsn +"</td><td>"+ sn +"</td></tr>";
				$('tbody').append(str);
				no++;
			} else if(no > 20){
				alert('已经满箱，请点击查重或者点击下一箱！');
			}
			//焦点事件
			$("#MAC2").addClass('active').focus();
			$('#MAC2').val("");
			$('#GPSN2').val("");
			$('#SN2').val("");
		} else {
			$('#SN2').removeClass('active');
			//在table中添加行数据
			var mac = $('#MAC2').val();
			var sn = $('#SN2').val();
			if(no > 0 && no < 21){
				var str = "<tr><td class='no'>"+ no +"</td><td>"+ mac +"</td><td>"+ sn +"</td></tr>";
				$('tbody').append(str);
				no++;
			} else if(no > 20){
				alert('已经满箱，请点击查重或者点击下一箱！');
			}
			//焦点事件
			$("#MAC2").addClass('active').focus();
			$('#MAC2').val("");
			$('#SN2').val("");
		}
	}

	</script>
  </body>
</html>