<?php
/**
 * 用户表操作模型
 */
class UserModel extends Model {

	// 字段映射
	protected $_map = array(
		'pwd' => 'password'
	);

	// 自动验证
	protected $_validate = array(
		array('account', 'require', '账号不能为空'),
		array('account', '/^[a-zA-Z]\w{4,19}$/i', '账号由5到20个字母、数字、下划线组成，必须以字母开头', 1),
		array('account', '', '账号已存在', 1, 'unique'),
		array('username', 'require', '用户名不能为空'),
		array('username', '/^[\x80-\xff|\w]{2,29}$/', '用户名由2-14位字母、数字、或中文组成', 1, 'regex'), //PHP中中文字符占2个字节
		array('username', '', '用户名已存在', 1, 'unique'),
		array('password', 'require', '密码不能为空'),
		array('password', '/^[\w]{6,20}$/i', '密码格式不正确'),
		array('pwded', 'password', '两次密码不一致', 1, 'confirm')
	);

	// 自动完成（自动填充字段）
	protected $_auto = array(
		array('password', 'md5', 1, 'function'),
		array('logintime', 'time', 1, 'function'),
		array('loginip', 'get_client_ip', 1, 'function'),
		array('registime', 'time', 1, 'function')
		);

	// 需要Model类的create()操作出发自动验证和自动完成，成功会返回自动填充后的数据对象，自动验证失败可以用getError()取得错误消息
}
?>