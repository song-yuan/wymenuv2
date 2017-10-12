<script type="text/javascript">
	mui.init()
</script>
<script type="text/javascript">
			function startTime()   
            {   
                var today=new Date();//定义日期对象   
                var yyyy = today.getFullYear();//通过日期对象的getFullYear()方法返回年    
                var MM = today.getMonth()+1;//通过日期对象的getMonth()方法返回年    
                var dd = today.getDate();//通过日期对象的getDate()方法返回年     
                var hh=today.getHours();//通过日期对象的getHours方法返回小时   
                var mm=today.getMinutes();//通过日期对象的getMinutes方法返回分钟   
                var ss=today.getSeconds();//通过日期对象的getSeconds方法返回秒   
                // 如果分钟或小时的值小于10，则在其值前加0，比如如果时间是下午3点20分9秒的话，则显示15：20：09   
                MM=checkTime(MM);
//              dd=checkTime(dd);
//              mm=checkTime(mm);   
//              ss=checkTime(ss);    
                var day; //用于保存星期（getDay()方法得到星期编号）
                if(today.getDay()==0)   day   =   "星期日 " 
                if(today.getDay()==1)   day   =   "星期一 " 
                if(today.getDay()==2)   day   =   "星期二 " 
                if(today.getDay()==3)   day   =   "星期三 " 
                if(today.getDay()==4)   day   =   "星期四 " 
                if(today.getDay()==5)   day   =   "星期五 " 
                if(today.getDay()==6)   day   =   "星期六 " 
                document.getElementById('nowDateTimeSpan').innerHTML=yyyy+"-"+MM +"-"+ dd +" " + hh+":"+mm+":"+ss; 
                document.getElementById('nowDateTimeSpan1').innerHTML=yyyy+"-"+MM +"-"+ dd;
                document.getElementById('nowDateTimeSpan2').innerHTML=yyyy+"-"+MM +"-"+ dd;
//              document.getElementById('nowDateTimeSpan').innerHTML=MM +"-"+ dd;
//              setTimeout('startTime()',1000);//每一秒中重新加载startTime()方法 
            }   
             
            function checkTime(i)   
            {   
                if (i<10){
                    i="0" + i;
                }   
                  return i;
            }
            window.onload = startTime();
        </script>
<style>
	body{
		margin: 0px;
		padding: 0px;
		background-color: #CCCCCC;
		width: 100%;
		height: 100%;
	}
	body .div{
		background-color: #FFFFFF;
		line-height: 40px;
	}
</style>
<div class="div">
	<table cellpadding="0" cellspacing="0" width="100%" style="text-align: center;">
		<tr>
			<td><h4>上海斗石</h4></td>
			<td><h4><span id="nowDateTimeSpan"></span></h4></td>
		</tr>
		<tr>
			<td colspan="2"><h4>原料消耗报表</h4></td>
		</tr>
		<tr>
			<td>开始时间</td>
			<td><span id="nowDateTimeSpan1"></span> 00:00:00</td>
		</tr>
		<tr>
			<td>结束时间</td>
			<td><span id="nowDateTimeSpan2"></span> 23:59:59</td>
		</tr>
	</table>
	<table cellpadding="0" cellspacing="0" width="100%" style="text-align: center;">
		<tr>
			<td colspan="2"><h4>原料消耗</h4></td>
		</tr>
		<tr>
			<td width="50%">原料名称</td>
			<td width="50%">消耗量</td>
		</tr>
	</table>
	<div style="overflow: auto;height: 465px;">
		<table cellpadding="0" cellspacing="0" width="100%" style="text-align: center;">
			<?php foreach($materials as $material):?>
				<tr>
					<td width="50%"><?php echo $material['material_name'];?></td>
					<td width="50%"><?php echo $material['stock_num'];?></td>
				</tr>
			<?php endforeach;?>
		</table>
	</div>
</div>
<div style="background-color: #FFFFFF;width: 100%;text-align: right;line-height: 30px;">
	<button><a href="<?php echo $this->createUrl('shoujiduan/index',array('companyId'=>$this->companyId));?>#yclxh">返回</a></button>
</div>