<ul class="mui-table-view">
	<?php foreach ($admindpids as $dp):?>
    <li class="mui-table-view-cell mui-media">
        <a href="javascript:;">
            <img class="mui-media-object mui-pull-left" src="<?php echo $dp['logo'];?>">
            <div class="mui-media-body">
         		<?php echo $dp['company_name']?>
                <p class='mui-ellipsis'><?php echo $dp['address']?></p>
            </div>
        </a>
    </li>
    <?php endforeach;?>
</ul>