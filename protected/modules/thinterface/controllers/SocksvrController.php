<?php
class SocksvrController extends CController
{
	public function actions()
        {
            return array(
                'quote'=>array(
                    'class'=>'CWebServiceAction',
                ),
            );
        }
    
        public function actionIndex()
	{
                echo("test1");
		$socksvr=new SocketServer();
                $socksvr->restart();
	}	
}