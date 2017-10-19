<style>
.portlet-body>.row{
    margin:15px 0 30px 0;
}
.item-header{
       font-size: 14px;
       text-align: center;
}

.radio-inline{
    padding-left:0!important;
}
.form-group{
    width:33.333%!important;
}
.radios{
    display: block;
    min-height: 20px;
    margin-bottom: 10px;
    vertical-align: middle;
}
.items{
    border:.1rem solid rgba(0,0,0,.3);
    padding:20px;
    background-color: #f2f2f2;
}
.options-warp{
    margin:10px;
}
input[type='button']{
    margin-left: 30px;
    width:36px;
}
@media (max-width: 768px) {
    .item-header{
        text-align: left;
        font-size:15px;
        margin-bottom: 10px;
        background-color:#f9f9f9;
        padding:10px;
    }
    .form-group{
        width:66.666%!important;
}
}
.group1{
  margin-left: 40px; 
 color:#2d78f4;  
}
.group2{
    margin-left: 100px;
}
.item {
 
    margin-bottom: 30px !important;
}
.radio, .form-horizontal .radio-inline{
            padding-top: 0px !important;
    }
    
</style>

<div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
    <div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                    <div class="modal-content">
                            <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h4 class="modal-title">Modal title</h4>
                            </div>
                            <div class="modal-body">
                                    Widget settings form goes here
                            </div>
                            <div class="modal-footer">
                                    <button type="button" class="btn blue">Save changes</button>
                                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                            </div>
                    </div>
                    <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
    </div>
 <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','微信会员'),'url'=>$this->createUrl('wechatMember/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','会员商城'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('wechatMember/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
<div class="row">   
<?php $form=$this->beginWidget('CActiveForm', array(
               'id' => 'wxMemberShop-form',
               'action' => $this->createUrl('WxMemberShop/delete', array('companyId' => $this->companyId)),
               'errorMessageCssClass' => 'help-block',
               'htmlOptions' => array(
                       'class' => 'form-horizontal',
                       'enctype' => 'multipart/form-data'
               ),
)); ?>
    <div class="col-md-12">
    <div class="portlet purple box">
      
               
           
         
        <div class="portlet-body" id="table-manage" >
                <div class="row ">
                        <div class="col-xs-12 col-sm-2 item-header">功能状态 ：</div>
                        <div class="col-xs-12 col-sm-10">
                            <label class="radio-inline">
                                 <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1"> 开启
                            </label>
                            <label class="radio-inline">
                                 <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> 关闭
                            </label>
                        </div>
                </div>
            <div class="item">
                <div class="pull-left group1">
                    
                        <a  class=' btn blue'  href="<?php echo $this->createUrl('wxMemberShop/create',array('companyId' => $this->companyId));?>">
                            添加商品
                        </a>
                        <a  class=' btn blue'  href="<?php echo $this->createUrl('wechatMember/addFood',array('companyId' => $this->companyId));?>">
                            商品类别管理
                        </a>
                        <a  class=' btn blue'  href="<?php echo $this->createUrl('wechatMember/addFood',array('companyId' => $this->companyId));?>">
                            商品排序
                        </a> 
                </div>
                <div class="pull-left group2">
                        <input class="form-control pull-left" style="width:100px;"/>
                        <div class="dropdown pull-left">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
                                  全部状态
                                  <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                                   
                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">已上架</a></li>
                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">未上架</a></li>
                                    
                                </ul>
                        </div>
                        <div class="dropdown pull-left">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
                                  全部商品类别
                                  <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="#">1</a></li>
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="#">2</a></li>
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="#">3</a></li>
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="#">4</a></li>
                                </ul>
                        </div>
                        <button type="submit" class="btn blue">搜索</button>   
                </div>
                
            </div>
				<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="sample_1">
                        <thead>
                            <tr>
                               <th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
                               
                                <th>lid</th>
                                <th><?php echo yii::t('app','商品图片');?></th>
                                <th><?php echo yii::t('app','价格');?></th>
                                <th><?php echo yii::t('app','商品名称');?></th> 
                                <th><?php echo yii::t('app','商品类别');?></th>
                                 <th><?php echo yii::t('app','库存');?></th>
                                 <th><?php echo yii::t('app','总销量');?></th> 
                                <th><?php echo yii::t('app','当前状态');?></th>
                                 <th><?php echo yii::t('app','创立时间');?></th>
                                 
                                 <th>
                                        <div class="actions">
                                            <a href="<?php echo $this->createUrl('wxMemberShop/create', array('companyId' => $this->companyId));?>" class="btn blue">
                                               <i class="fa fa-pencil"></i> 
                                               <?php echo yii::t('app','添加');?>
                                           </a>
                                           <div class="btn-group">
                                                   <button type="submit"  class="btn red" >
                                                   <i class="fa fa-ban"></i> 
                                                   <?php echo yii::t('app','删除');?>
                                                   </button>
                                           </div>
               
                                        </div>
                                 </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if($models) :?>
                        <?php foreach ($models as $model):?>
                            <tr class="odd gradeX">
                                <td>
                                       
                                        <input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="shopIds[]" />
                                        
                                    </td>
                                
                                <td ><?php echo $model->lid;?></td>
                                
                                <td ><img width="100" src="<?php echo $model->goods_img;?>" /></td>
                                <td> <?php echo $model->price;?></td>
                                <td ><?php echo $model->goods_name;?></td>
                                <td ><?php echo $model->goods_category;?></td>
                                <td ><?php echo $model->stock;?></td>
                                <td><?php echo $model->sale;?></td>
                                <td ><?php echo $model->state;?></td>
                                <td ><?php echo $model->create_at;?></td>
                                
                                
                                <td class="center">
                                <div class="actions">
                                            <a href="<?php echo $this->createUrl('wxMemberShop/update', array('companyId' => $this->companyId));?>" class="btn blue">
                                               <i class="fa fa-pencil"></i> 
                                               <?php echo yii::t('app','编辑');?>
                                           </a>
                                          
               
                                </div>	
                                </td>
                            </tr>
                        <?php endforeach;?>
                        <?php endif;?>
                        </tbody>
                    </table>
                    </div>
        </div> 
    </div>
        
</div>
    <?php $this->endWidget(); ?>
</div>
</div>
