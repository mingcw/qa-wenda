<?php
/**
 * 用户管理控制器
 */
Class UserAction extends CommonAction{

	/**
	 * 所有用户列表视图
	 * @return [type] [description]
	 */
	Public function index(){
		$type = isset($_GET['type']);
		$where = $type ? array('lock' => 1) : array();

		import('ORG.Util.Page');
		$db = M('user');
		$count = $db->where($where)->count('id');
		$page = new Page($count, 20);
		$limit = $page->firstRow . ',' . $page->listRows;
		$field = array('account', 'password', 'face');

		$this->user = $db->where($where)->field($field, true)->order('registime DESC')->limit($limit)->select();
		$this->page = $page->show();
		$this->type = $type ? 1 : 0;
		$this->display();
	}

	/**
	 * 锁定用户/解锁
	 * @return [type] [description]
	 */
	Public function lock(){
		$id = I('id', 0 , 'intval');
		$lock = I('lock', 0, 'intval');

		$msg = $lock ? '锁定' : '解锁';
		if(M('user')->where(array('id' => $id))->setField('lock', $lock)){
			$this->success($msg . '成功', U('index'));
		}
		else{
			$this->error($msg. '失败');
		}
	}

	/**
	 * 添加新用户视图
	 * @return [type] [description]
	 */
	Public function add(){
		$this->display();
	}

	/**
	 * 添加用户表单处理
	 * @return [type] [description]
	 */
	Public function addHandle(){
		$data = array(
			'account' => I('account', ''),
			'username' => I('username', ''),
			'password' => I('password', ''),
			'logintime' => time(),
			'loginip' => get_client_ip(),
			'registime' => time()
			);
		if($uid = M('user')->add($data)){
			$this->success('添加成功', U('index'));
		}
		else{
			$this->error('添加失败');
		}
	}
}
?>