	<script type="text/javascript">
		var swfu<?php echo $threadId?>;		
		$(function () {
			swfu<?php echo $threadId?> = new SWFUpload({
				// Backend Settings
				upload_url: '<?php echo $uploadUrl?>',
				post_params: <?php echo $postParams?>,				
			
				file_size_limit : '<?php echo $fileSizeLimit;?>MB',	
				file_types : "<?php echo  $fileTypes;?>",
				file_types_description : "<?php echo $fileTypes;?>文件",
				file_upload_limit : 0,
				file_queue_limit : <?php echo $fileQuenueLimit;?>, 
			
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,

				// Button Settings
				button_image_url : "<?php echo $baseUrl; ?>/<?php echo $buttonImg;?>",
				button_placeholder_id : "button_placeholder_<?php echo $threadId?>",
				button_width: <?php echo $buttonWidth;?>,
				button_height: <?php echo $buttonHeight;?>,
				button_text : '<span class="button"><?php echo $buttonText;?></span>',
				button_text_style : '.button {font-family: Arial,Helvetica,sans-serif; font-size: 13pt; } .buttonSmall { font-size: 10pt; }',
				button_text_top_padding: 2,
				button_text_left_padding: 28,
				button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
				button_cursor: SWFUpload.CURSOR.HAND,
				
				// Flash Settings
				flash_url : "<?php echo $baseUrl?>/swfupload.swf",

				custom_settings : {
					upload_target : "fileProgressContainer_<?php echo $threadId?>",
					thumbnails	: "thumbnails_<?php echo $threadId?>",
					thread_id   :"<?php echo $threadId?>"
				},
				
				// Debug Settings
				debug: <?php echo $debug?>
			});
		}
		);
		</script>	
	<style>
	.swfupload_button {
		clear:both;border:none;padding:10px 0 0 0;
	}
	.fileupload .thumbnail {
		float:left;
		width: 330px;
	}
	</style>
	<div class="cl"></div>
	<div <?php if(!$showMessage):?>style="display:none;"<?php endif;?> id="fileProgressContainer_<?php echo $threadId?>"></div>
	<div class="fileupload">
		<?php if(empty($imgUrlList)):?>
		<div id="thumbnails_<?php echo $threadId?>" class="thumbnail" >
		<input style="display: inline-block;max-height: 100%;vertical-align: middle;margin-left:auto:margin-right:auto;max-width: 100%;" value="./images/200x150.gif" alt="">
		</div>
		<?php else:?>
		<?php foreach ($imgUrlList as $img):?>
		<div id="thumbnails_<?php echo $threadId?>" class="thumbnail" >
		<input class="form-control" style="margin:1px;opacity:1;" value="<?php echo $img?>" disabled=true>
		</div>
		<?php endforeach;?>
		<?php endif;?>
	</div>
	<div class="swfupload_button form-control" <?php if(!$showButton):?>style="display:none;"<?php endif;?>>
			<div id="button_placeholder_<?php echo $threadId?>">&nbsp;</div>
	</div>