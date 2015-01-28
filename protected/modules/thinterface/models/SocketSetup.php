<?php
//单例连接socket
class SocketSetup{
	const HOST='203.156.196.182';
	const PORT='50613';
	static private $_instance=null;
	private $result;
	private $socket;
	static public function getInstance(){
		if(! self::$_instance){
			self::$_instance=new self;
		}
		return self::$_instance;
	}

	private function __construct(){
		$socket = socket_create(AF_INET,SOCK_STREAM,0);
		if ($socket < 0){
			$this->socket=null;
			$this->result=null;
		}else{
			$this->socket=$socket;
			$result = @socket_connect($socket,self::HOST,self::PORT);
			if ($result == false){
				$this->result=null;
			}else{
				$this->result=$result;
			}
		}
	}
	public function __destruct(){
		if($this->socket){
			socket_close ($this->socket);
		}
	}

	function conct($str){
		if($this->result){
			socket_write($this->socket,$str,strlen($str));
			$input = socket_read($this->socket,1024); 
			return $input;
		}else{
			return $this->result;
		}
		 
	}
}
?>
