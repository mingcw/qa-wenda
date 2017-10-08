<?php

/**
 * 公共控制器
 */
class CommonAction extends Action{

	protected function _initialize(){
		if(!C('WEB_STATE')) halt('网站正在维护中');

		// 处理自动登录
		if(isset($_COOKIE['auto']) && !session('uid')){

			$value = explode('|', encrypt($_COOKIE['auto'], 0));

			if($value[1] == get_client_ip()){
				session('uid', $value[0]);
				session('username', $value[2]);
			}
		}
	}

	/**
	 * 退出登录
	 * @return [type] [description]
	 */
	public function logout(){
		session_unset();
		session_destroy();
		setcookie("auto", "", time() - 3600, '/'); // cookie有效期为1小时前，触发客户端的移除机制
		redirect(__APP__);
	}

	/**
	 * 异步登录验证账号和密码
	 * @return [type] [description]
	 */
	public function checkLogin(){
		if(!IS_AJAX) halt('页面不存在');

		$account = $_POST['account'];

		$where = array('account' => $account);
		$password = M('user')->where($where)->getField('password');
		if(!$password || $password !=  $this->_post('password', 'md5')){
			echo 0;
		}
		else{
			echo 1;
		}
	}

	/**
	 * 登录表单处理
	 * @return [type] [description]
	 */
	public function login(){
		if(!IS_POST) halt('页面不存在');

		$account = I('account', '');

		$db = M('user');
		$where = array('account' => $account);
		$field = array('id' , 'account', 'password', 'lock');
		$user = $db->where($where)->find();

		// 验证账号、密码
		if(!$user || $user['password'] != I('pwd', '', 'md5')){
			$this->error('账号或密码错误');
		}

		// 是否锁定
		if($user['lock']){
			$this->error('用户已锁定');
		}

		// 写cookie
		if(isset($_POST['auto'])){
			$value = $user['id'] . '|' . get_client_ip() . '|' . $user['username'];
			$value = encrypt($value);
			@setcookie('auto', $value, C('COOKIE_EXPIRE'), '/');
		}

		// 每天登录增加经验
		$today = strtotime(date('Y-m-d')); // 今天凌晨时间戳
		$where = array('id' => $user['id']);
		if($user['logintime'] < $today){
			$db->where($where)->setInc('exp', C('LV_LOGIN'));
		}

		// 更新数据库
		$data = array(
			'id'=> $user['id'], // Think将主键id作为where条件
			'logintime' => time(),
			'loginip' => get_client_ip()
		);
		$db->save($data);

		// 写session
		session('uid', $user['id']);
		session('username', $user['username']);

		redirect($_SERVER['HTTP_REFERER']);
	}

	/**
	 * 注册表单处理
	 * @return [type] [description]
	 */
	public function register(){
		if(!IS_POST) halt('页面不存在');

		if(!C('WEB_OPEN_REGISTER')){
			halt('暂时未开放会员注册功能');
		}
		
		if(I('verify', '', 'md5') != session('verify')){
			$this->error('验证码有误');
		}

		$db = D('User');

		// 根据$_POST创建数据对象
		if (!$user = $db->create()){
			//create()操作会触发Think的字段映射和自动验证和自动完成
			//成功返回数据对象，失败可以getError()取得错误消息
			$this->error($db->getError());
		}

		// 写数据库
		if(!$uid = $db->add()) $this->error('注册失败，请重试...');

		// 写session
		session('uid', $uid);
		session('username', $user['username']);

		$this->success('注册成功，正在为您跳转...', __APP__);
	}

	/**
	 * 异步验证账号
	 * @return [type] [description]
	 */
	public function checkAccount(){
		if(!IS_AJAX) halt('页面不存在');

		$account = I('account', '');
		$where = array('account' => $account);
		if(M('user')->where($where)->getField('id')){
			echo 0;	// 账号已存在
		}
		else{
			echo 1; // ok
		}
	}

	/**
	 * 异步验证用户名
	 * @return [type] [description]
	 */
	public function checkUsername(){
		if(!IS_AJAX) halt('页面不存在');

		$username = I('username', '');
		$where = array('username' => $username);
		if(M('user')->where($where)->getField('id')){
			echo 0; // 用户名已存在
		}
		else{
			echo 1; // ok
		}
	}

	/**
	 * 异步验证验证码
	 * @return [type] [description]
	 */
	public function checkVerify(){
		if(!IS_AJAX) halt('页面不存在');

		$verify = I('verify', '', 'md5');
		if($verify == session('verify')){
			echo 1; // ok
		}
		else{
			echo 0;	// 验证码有误
		}
	}

	/**
	 * 生成验证码
	 * @return [type] [description]
	 */
	public function verify(){
		import('ORG.Util.Image');
		Image::buildImageVerify();
	}
}
?>