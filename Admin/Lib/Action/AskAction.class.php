<?php
/**
 * 问题管理控制器
 */
class AskAction extends CommonAction{

	/**
	 * 问题列表视图
	 * @return [type] [description]
	 */
	public function index(){
		$type = I('type', 0, 'intval');

		switch ($type) {
			case 1: // 待解决
				$where = array('solve' => 0);
				break;
			case 2: // 已解决
				$where = array('solve' => 1);
				break;
			case 3: // 零回答
				$where = array('answer' => 0);
				break;
			default: // 所有
				$where = array();
				break;
		}

		import('ORG.Util.Page');
		$db = M('ask');
		$count = $db->where($where)->count('id');
		$page = new Page($count, 20);
		$limit = $page->firstRow . ',' . $page->listRows;

		$this->ask = $db->where($where)->order('time DESC')->limit($limit)->select();
		$this->page = $page->show();
		$this->type = $type;
		$this->display();
	}


	/**
	 * 删除问题
	 * @return [type] [description]
	 */
	public function delAsk(){
		$id = I('id', 0, 'intval');
		$askInfo = M('ask')->field(array('uid', 'solve'))->find($id);

		$where = array('aid' => $id, 'adopt' => 1);
		$answerUid = M('answer')->where($where)->getField('uid'); // 该问题的回答被采纳者ID

		if(D('AskRelation')->relation(true)->delete($id)){

			$db = M('user');
			$db->where(array('id' => $askInfo['uid']))->setDec('point', C('DEL_ASK')); //扣提问者金币

			if($askInfo['solve']){ //如果已解决
				$db->where(array('id' => $answerUid))->setDec('point', C('DEL_ADOPT_ANSWER')); //扣被采纳回答者金币
				$db->where(array('id' => $askInfo['uid']))->setDec('point', C('DEL_ADOPT_ASK')); //扣提问者金币
			}

			$this->success('删除成功', U('index'));
		}
		else{
			$this->error('删除失败，请重试...');
		}
	}
}
?>