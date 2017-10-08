<?php
/**
 * 后台登录控制器
 */
class LoginAction extends Action{

	/**
	 * 登录页视图
	 * @return [type] [description]
	 */
	public function index(){
		$this->display();
	}

	/**
	 * 登录表单处理
	 * @return [type] [description]
	 */
	public function login(){
		// 提交的数据
		$account = I('username', '');
		$password = I('password', '', 'md5');
		$verify = I('code', '', 'md5');

		// 验证验证码
		if($verify != session('verify')){
			$this->error('验证码错误');
		}

		// 验证账号和密码
		$where = array('account' => $account);
		$db = M('admin');
		$admin = $db->where($where)->find();
		if(!$admin || $password != $admin['password']){
			$this->error('账号或密码错误');
		}

		// 是否锁定
		if($admin['lock']){
			$this->error('用户被锁定');
		}

		// 更新数据库
		$data = array(
			'id' => $admin['id'], //id是主键，更新时作为where条件
			'logintime' => time(),
			'loginip' => get_client_ip()
			);
		$db->save($data);

		// 写session
		session('uid', $admin['id']);
		session('uname', $admin['account']);
		session('logintime', date('Y-m-d H:i', $admin['logintime']));
		session('loginip', $admin['loginip']);

		redirect(__APP__); //定向到当前项目入口路径，执行默认Index控制器的Index操作
	}

	/**
	 * 异步验证管理员账号
	 * @return [type] [description]
	 */
	public function checkAccount(){
		if(!IS_AJAX){
			halt('页面不存在');
		}
		$account = I('username', '');
		$where = array('account' => $account);
		if(M('admin')->where($where)->count('id')){
			echo 1;	//正确
		}
		else{
			echo 0;	//错误
		}
	}

	/**
	 * 异步验证管理员密码
	 * @return [type] [description]
	 */
	public function checkPassword(){
		if(!IS_AJAX) {
			halt('页面不存在');
		}
		$account = I('username', '');
		$where = array('account' => $account);
		$password = M('admin')->where($where)->getField('password');
		if($password && $password == I('password', '', 'md5')){
			echo 1; //正确
		}
		else{
			echo 0; //有误
		}
	}

	/**
	 * 异步验证验证码
	 * @return [type] [description]
	 */
	public function checkVerify(){
		if(!IS_AJAX){
			halt('页面不存在');
		}
		$verify = I('code', '', 'md5');
		if($verify == session('verify')){
			echo 1;	//正确
		}
		else{
			echo 0; //有误
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