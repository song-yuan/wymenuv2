<?php
class SocksvrController extends Controller
{
	public function actionIndex()
	{
                echo("test1");
		$socksvr=new SocketServer();
                $socksvr->restart();
	}	
}