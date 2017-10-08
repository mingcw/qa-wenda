<?php
/**
 * 回答管理控制器
 */
class AnswerAction extends CommonAction{

	/**
	 * 回答列表视图
	 * @return [type] [description]
	 */
	public function index(){
		$type = I('type', 0, 'intval');

		switch ($type) {
			case 1: // 未采纳
				$where = array('adopt' => 0);
				break;
			case 2: // 已采纳
				$where = array('adopt' => 1);
				break;
			default: // 所有
				$where = array();
				break;
		}

		import('ORG.Util.Page');
		$db = M('answer');
		$count = $db->where($where)->count('id');
		$page = new Page($count, 20);
		$limit = $page->firstRow . ',' . $page->listRows;

		$this->answer = $db->where($where)->order('time DESC')->limit($limit)->select();
		$this->page = $page->show();
		$this->type = $type;
		$this->display();
	}

	/**
	 * 删除回答
	 * @return [type] [description]
	 */
	public function delAns(){
		$id = I('id', 0, 'intval'); // 回答ID

		$dbAnswer = M('answer');
		$dbUser = M('user');

		$ansInfo = $dbAnswer->field(array('uid', 'adopt', 'aid'))->find($id); // 回答者ID，是否被采纳，问题ID

		$askUid = M('ask')->where(array('id' => $ansInfo['aid']))->getField('uid'); // 提问者ID


		if($dbAnswer->delete($id)){
			// 删除成功扣金币

			$dbUser->where(array('id' => $ansInfo['uid']))->setDec('point', C('DEL_ANSWER')); // 回答者扣金币

			if($ansInfo['adopt']){ // 如果被采纳

				$dbUser->where(array('id' => $ansInfo['uid']))->setDec('point', C('DEL_ADOPT_ANSWER')); //扣回答者金币
				$dbUser->where(array('id' => $askUid))->setDec('point', C('DEL_ADOPT_ASK')); //扣提问者金币
			}

			$this->success('删除成功', $_SERVER['HTTP_REFERER']);
		}
		else{
			$this->error('删除失败，请重试...');
		}
	}
}
?>