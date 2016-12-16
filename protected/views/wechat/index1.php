<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1 user-scalable=no">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
 <?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/bootstrapp/bootstrap.min.css');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-1.7.1.min.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/bootstrap/bootstrap.min.js');?>

    <style type="text/css">
        .selected{
            background-color: red;
        }
    </style>
    <script type="text/javascript">
       $(function(){
           $("#chanel_demo2").on("show.bs.collapse",function(){
              // alert("chanel_demo2");
           });
       });
    </script>
  </head>
    <body>

        <div class="panel panel-default" >
                    <div class="panel-heading"><a href="#" data-toggle="collapse" data-target="#chanel_demo1" data-parent="#panel_container">栏目管理</a></div>
                    <div class=" collapse panel-collapse" id="chanel_demo1">
                            <ul class="list-group">
                                <li class="list-group-item"><a href="#">增加栏目</a></li>
                                <li class="list-group-item"><a href="#">增加栏目</a></li>
                                <li class="list-group-item"><a href="#">增加栏目</a></li>
                                <li class="list-group-item"><a href="#">增加栏目</a></li>
                                <li class="list-group-item"><a href="#">增加栏目</a></li>
                            </ul>
                        <div class="panel-footer"> 页脚</div>
                    </div>
        </div>
       
    </body>
</html>
