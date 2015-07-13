    
    <?php foreach ($models_category as $mc):?>
        <div class="client_category_title"><?php echo $mc->name; ?></div>
        <div class="client_sitelist_in">
            <ul>
            <?php foreach ($mc->site as $mcsl):?>
                <li class="siteaction <?php if($mcsl->status=='1') echo 'bg-yellow'; elseif($mcsl->status=='2') echo 'bg-blue'; elseif($mcsl->status=='3') echo 'bg-green';?>" istemp="1" status=<?php echo $mcsl->status;?> sid=<?php echo $mcsl->lid;?> ><span style="font-size: 25px;"><?php echo $mcsl->serial;?>&nbsp;</span><br><?php echo $mcsl->create_at;?></li>        
            <?php endforeach;?>
            </ul>
        </div>
    <?php endforeach;?>
    
     <div class="client_category_title">临时台</div>
    <div class="client_sitelist_in">
        <ul>
            <li class="siteaction bg_add" istemp="1" status="0" sid="0"></li>
            <?php foreach ($models_temp as $mt):?>
            <li class="siteaction <?php if($mt->status=='1') echo 'bg-yellow'; elseif($mt->status=='2') echo 'bg-blue'; elseif($mt->status=='3') echo 'bg-green';?>" istemp="1" status=<?php echo $mt->status;?> sid=<?php echo $mt->site_id;?> ><span style="font-size: 25px;"><?php echo $mt->site_id%1000;?>&nbsp;</span><br><?php echo $mt->create_at;?></li>
            <?php endforeach;?>    

        </ul>
    </div>
     <div id="client_open_site" style="display:none">				
            <div style="font-size: 1.5em;padding: 5px;margin-top: 16px;"><?php echo yii::t('app','请输入人数：');?></div>
            <div style="margin:7px;">
            <input type="text" style="font-size:1.5em; padding: 5px;margin: 6px;" name="siteNumber" id="site_number" maxlength="2" size="5" value="2">
            <input type="button" style="font-size:1.5em; padding: 5px;margin: 6px;" id="site_open" value="<?php echo yii::t('app','开 台');?>" >
            </div>
            
    </div>
<script type="text/javascript">
	$('.siteaction').on(event_clicktouchstart, function(){
               
               layer.open({
                    type: 1,
                    shade: false,
                    title: false, //不显示标题
                    content: $('#client_open_site'), //捕获的元素
                    cancel: function(index){
                        layer.close(index);
//                        this.content.show();
//                        layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构',{time: 5000});
                    }
                });
                 var sno=$("#site_number");
                sno.focus();
//                if(sno.length > 0)
//                {
//                    sno[0].focus();
//                }
            });      
        
       $('#site_open').on(event_clicktouchstart,function(){
           alert(11);return;
            var siteNumber=$('#site_number').val();                               
            var sid = $(this).attr('sid');
            var istemp = $(this).attr('istemp');
            if(!isNaN(siteNumber) && siteNumber>0 && siteNumber < 99)
            {
                //alert(!isNaN(siteNumber));
                 $.ajax({
                     'type':'POST',
                     'dataType':'json',
                     'data':{"sid":sid,"siteNumber":siteNumber,"companyId":'<?php echo $this->companyId; ?>',"istemp":'<?php echo ""; ?>'},
                     'url':'<?php echo $this->createUrl('defaultSite/opensite',array());?>',
                     'success':function(data){
                             
                     },
                     'error':function(e){
                         return false;
                     }
                 });

            }else{
                alert("<?php echo yii::t('app','输入合法人数');?>");
                return false;
            }                               
        });
</script>