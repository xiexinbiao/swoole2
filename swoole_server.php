<?php


class SwooleServer
{
	private $this->serverer = null;
 
	public function __construct($data)
	{
		 
		$this->server = new swoole_server('127.0.0.1', 9502);
 
		$this->server->set([
				'worker_num' => 4,
				'daemonize' => false,
				'max_request' => 10000,
				'dispatch_mode' => 2,
				'debug_mode'=> 1,
				]);
 
		$this->server->on('Start', [$this, 'onStart']);
		$this->server->on('Connect', [$this, 'onConnect']);
		$this->server->on('Receive', [$this, 'onReceive']);
		$this->server->on('Close', [$this, 'onClose']);
 
		//$this->server->start();
	}
	

	//设置异步任务的工作进程数量
	//$this->server->set(array('task_worker_num' =>16));
	 

	//监听数据接收事件

	$this->server->on('receive', function($this->server, $fd, $from_id, $data='') {

		//投递异步任务

		$task_id = $this->server->task($data);//非阻塞

		echo "同步代码执行完成\n";

	});

	 
	//处理异步任务
	$this->server->on('task', function ($this->server, $task_id, $from_id, $data) {

		$this->handleFun($data);

		//返回任务执行的结果

		$this->server->finish("finish");

	});

	 
	//处理异步任务的结果

	$this->server->on('finish', function ($this->server, $task_id, $data) {

		echo "异步任务执行完成";

	});

	
	$this->server->start();


	function handleFun($data){			

		$data=json_decode($data,true);	 

		foreach ($data  as $key => $value) {		 

		 echo  json_encode($value);

		  $url="xxxx";//调用发送模板消息接口,服务端没办法直接获取微信的接口的一些数据,此处做了一些加密

			 $postUrl = $url;

			$curlPost = $value;

	 

			$ch = curl_init(); //初始化curl

			curl_setopt($ch, CURLOPT_URL, $postUrl); //抓取指定网页

			curl_setopt($ch, CURLOPT_HEADER, 0); //设置header

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上

			curl_setopt($ch, CURLOPT_POST, 1); //post提交方式

			curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);

			$data = curl_exec($ch); //运行curl

			curl_close($ch);

			

	   }

	}   

 

}