<?php
 
/**
 * Swoole服务器
 */
class Swoole_SwooleServer
{
	private $logger = null;
	private $server = null;
	private $smtpConfig;
	private static $app;
	private $options = array();
 
	public function __construct($options = '')
	{
		$this->options = $options ?: array();
 
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
 
		$this->server->start();
	}
 
	public function onStart()
	{
 
	}
 
	public function onConnect($server, $descriptors, $fromId)
	{
 
	}
 
	public function onReceive(swoole_server $server, $descriptors, $fromId, $data)
	{
		$sent = $this->send($data);// Swoole Server 接受到任务后调用发送动作，这是Yaf 框架下的使用方式，其它框架需要修改
 
		//printf("%s mail is sent.\n", $sent);
	}
 
	public function onClose($server, $descriptors, $fromId)
	{
 
	}
 
	public function send($data)
	{
		if (empty(self::$app)) {
			define("GUARD", 1);
			if (!defined("APP_PATH")) {
				define("APP_PATH", realpath(dirname(__FILE__) . '/../../../'));
			}
			define("APP", "yafapp");
 
			self::$app = new Yaf_Application(APP_PATH . "/conf/application.ini");
		}
 
		$config = Common::getConfig(APP);
		date_default_timezone_set($config->timezone);
 
		$con = $this->options['c'];
		$act = $this->options['a'];
		$params = json_decode($data, true);
 
		$request = new Yaf_Request_Simple("CLI", APP, $con, $act, $params);// Yaf 命令行
		unset($params);
 
		self::$app->getDispatcher()->dispatch($request);
		Yaf_Dispatcher::getInstance()->autoRender(false);
	}
 
}
 
if ($argc < 3) {
	echo "php a.php -c controller -a action\n" . 
		"-c controller 控制器" .
		"-a action 动作";
		exit(-1);
}
set_time_limit(0); // 设置超时时间为0
$opts = 'c:a:';
$options = getopt($opts);
 
$server = new Swoole_SwooleServer($options);