<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SocketServer
 *
 * @author Administrator
 */
class SocketServer {
        
    //设置IP和端口号  
    private $address = "127.0.0.1";  
    private $port = 5062; //调试的时候，可以多换端口来测试程序！
    private $socket;
    /** 
     * 创建一个SOCKET  
     * AF_INET=是ipv4 如果用ipv6，则参数为 AF_INET6 
     * SOCK_STREAM为socket的tcp类型，如果是UDP则使用SOCK_DGRAM 
      
    private function __construct(){
        
    }*/
    
    public function __destruct(){
            if($this->socket){
                    socket_close($this->socket);
            }
    }
    
     public function restart(){
            //echo("test controller");
            if($this->socket){
                    socket_close($this->socket);
            }
            $this->start();
    }
    
    public function start(){
             //确保在连接客户端时不会超时
        echo ("1");
            set_time_limit(0);
            $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) 
                    or die("socket_create() 建立失败的原因是:" . socket_strerror(socket_last_error()) . "/n"); 
            //阻塞模式  
            echo ("2");
            socket_set_block($this->socket) 
                or die("socket_set_block() 阻塞失败的原因是:" . socket_strerror(socket_last_error()) . "/n");  
            //绑定到socket端口 
            echo ("3");
            $result = socket_bind($this->socket, $this->address, 5062) 
                    or die("socket_bind() 绑定失败的原因是:" . socket_strerror(socket_last_error()) . "/n");  
            //开始监听  
            echo ("4");
            $result = socket_listen($this->socket, 4) 
                    or die("socket_listen() 监听失败的原因是:" . socket_strerror(socket_last_error()) . "/n");  
            
            echo "Begin listining";  
            do { // never stop the daemon  
                    //它接收连接请求并调用一个子连接Socket来处理客户端和服务器间的信息  
                    $msgsock = socket_accept($this->socket) 
                            or  die("socket_accept() failed: reason: " . socket_strerror(socket_last_error()) . "/n");  

                    //读取客户端数据  
                    echo "Read client data \n";  
                    //socket_read函数会一直读取客户端数据,直到遇见\n,\t或者\0字符.PHP脚本把这写字符看做是输入的结束符.  
                    $buf = socket_read($msgsock, 21000);  
                    echo "Received msg: $buf   \n";  

                    //数据传送 向客户端写入返回结果  
                    $msg = "welcome \n";  
                    socket_write($msgsock, $msg, strlen($msg)) 
                            or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");
                    
                    //一旦输出被返回到客户端,父/子socket都应通过socket_close($msgsock)函数来终止  
                    socket_close($msgsock);  
            } while (true);  
            socket_close($this->socket);
    }
}

$socksvr=new SocketServer();
$socksvr->restart();