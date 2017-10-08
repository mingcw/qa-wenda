<?php
/**
 * 后台首页控制器
 */
class IndexAction extends CommonAction{

	/**
	 * 首页视图
	 * @return [type] [description]
	 */
	public function index(){
		$this->display();
	}

	/**
	 * 用户信息
	 * @return [type] [description]
	 */
	public function copy(){

		$uname = session('uname');
		$logintime = session('logintime');
		$time = date('Y-m-d H:i', time());
		$loginip = session('loginip');
		$ip = get_client_ip();

		$apache = apache_get_version();
		$obj = new mysqli(C('DB_HOST'), C('DB_USER'), C('DB_PWD'));
		$mysql = $obj->get_server_info();

		$str = <<<EOF
<div style="color:#744D4D;">
	<p><strong style="color: red; font: 24px 'Arial', '宋体'";>{$uname}</strong>，您好！</p>
	<p>您上一次的登录时间是：<span style='color: #079F0D;'>{$logintime}</span></p>
	<p>您本次的登录时间是：<span style="color: #0A79C7">{$time}</span></p>
	<p>您上一次的登录IP是：<span style='color: #079F0D;'>{$ip}</span></p>
	<p>您本次的登录IP是：<span style="color: #0A79C7">{$ip}</span></p>
	<p>网站环境：{$apache} MySQL/{$mysql}</p>
</div>
EOF;
		echo $str;
	}

	/**
	 * 退出登录
	 * @return [type] [description]
	 */
	public function logOut(){
		session_unset();
		session_destroy();
		$this->redirect('Login/index');
	}
}
?>