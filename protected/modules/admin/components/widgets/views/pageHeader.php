			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN PAGE TITLE & BREADCRUMB-->			
					<h3 class="page-title">
						<?php echo $head;?>
						<?php if($subhead):?>
						<small><?php echo $subhead;?></small>
						<?php endif;?>
					</h3>
					<ul class="page-breadcrumb breadcrumb">
						<?php if($back):?>
						<li class="btn-group">
							<div class="actions">
								<?php echo CHtml::link('<i class="m-icon-swapleft"></i> '.$back['word'],$back['url'],array('class'=>'btn blue'));?>
							</div>
						</li>
						<?php endif;?>
						<li>
							<i class="fa fa-home"></i>
							<!-- <a href="<?php echo Yii::app()->createUrl('admin/default',array('companyId'=>Yii::app()->controller->companyId));?>"><?php echo yii::t('app','首页'); ?></a> --> 
							<a href='javescript:;'><?php echo yii::t('app','首页');?></a>
							<i class="fa fa-angle-right"></i>
						</li>
						<?php for($i = 0,$count = count($breadcrumbs);$i < $count;$i++):?>
						<li>
							<?php echo CHtml::link($breadcrumbs[$i]['word'],$breadcrumbs[$i]['url']);?>
							<?php if($i < $count-1):?>
							<i class="fa fa-angle-right"></i>
							<?php endif;?>
						</li>
						<?php endfor;?>
					</ul>
					<!-- END PAGE TITLE & BREADCRUMB-->
				</div>
			</div>
				<?php if(Yii::app()->user->hasFlash('success')): ?>
				<div class="mymodel" id="message">
					<div class="alert alert-success col-md-12">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
						<div class="text">
							<strong><?php echo Yii::app()->user->getFlash('success'); ?></strong>
						</div>
					</div>
				</div>
	  			<?php Yii::app()->clientScript->registerScript('myHideEffect','$("#message").animate({opacity: 0}, 2000).fadeOut(2000);',CClientScript::POS_READY);?>
				<?php elseif(Yii::app()->user->hasFlash('error')):?>
				<div class="mymodel" id="message">
					<div class="alert alert-danger col-md-12">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
						<div class="text">
							<strong><?php echo Yii::app()->user->getFlash('error'); ?></strong>
						</div>
					</div>
				</div>
	  			<?php Yii::app()->clientScript->registerScript('myHideEffect','$("#message").animate({opacity: 0}, 2000).fadeOut(2000);',CClientScript::POS_READY);?>
				<?php endif;?>
			