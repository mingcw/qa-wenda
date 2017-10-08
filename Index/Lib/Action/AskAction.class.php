<?php
/**
 * 提问控制器
 */
class AskAction extends CommonAction {

	/**
	 * 提问视图
	 * @return [type] [description]
	 */
	public function index(){
		$this->cate = M('category')->where(array('pid' => 0))->select();

		if(isset(session('uid') && session('username')){
			$this->point = M('user')->where(array('id' => session('uid')))->getField('point');
		}
		$this->display();
	}

	/**
	 * 发布问题表单处理
	 * @return [type] [description]
	 */
	public function send(){
		if(!IS_POST) halt('页面不存在');

		$id = (int)session('uid');
		$data = array(
			'content' => I('content', ''),
			'reward' => I('reward', 0),
			'time' => time(),
			'uid' => $id,
			'cid' => I('cid', 0, 'intval')
		);
		if($aid = M('ask')->add($data)){
			$db = M('user');
			$where = array('id' => $id);
			$db->where($where)->setInc('ask');
			$db->where($where)->setInc('exp', C('LV_ASK'));

			redirect(U('Member/index', array('id' => $id)));
		}
		else{
			$this->error('发布失败，请重试...');
		}
	}

	/**
	 * 异步获取子分类
	 * @return [type] [description]
	 */
	public function getCate(){
		if(!IS_AJAX) halt('页面不存在');

		$pid = $this->_get('pid', 'intval');

		$cate = M('category')->where(array('pid' => $pid))->select();

		if(!$cate){
			echo 0;
		}
		else{
			$this->ajaxReturn($cate);
		}
	}
}
?>