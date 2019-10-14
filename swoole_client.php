<?php

class Client
{
	$handleFun = '';
	
	public function __construct(SwooleServer $SwooleServer,$data=[])
	{
		$this->handleFun = $handleFun;
 
	}
	
	$msg = json_encode($data);

    public function send($msg){
        $client = new swoole_client(SWOOLE_SOCK_TCP);

        //连接到服务器
        if (!$client->connect('127.0.0.1', 9501, 0.5))
        {
            $this->write("connect failed.");

        }

        //向服务器发送数据
        if (!$client->send($msg))
        {
			$SwooleServer;
           
        }

        //关闭连接
        $client->close();

    }
   
}
$data = [['mes'=>'消息1'], ['mes'=>'消息2'], ['mes'=>'消息3']];//接口数据

include('swoole_server2.php');
$server_swoole = new Swoole_SwooleServer($data)

$client = new Client($server_swoole);
$client->send($data);




