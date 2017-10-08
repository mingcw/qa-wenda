<?php

/**
 * 展示页 控制器
 */
class ShowAction extends CommonAction{

	/**
	 * 展示页 视图
	 * @return [type] [description]
	 */
	public function index(){
		$id = I('id', 0, 'intval');

		// 问题详细
		$askInfo = D('AskInfoView')->where(array('id' => $id))->find();
		if(!$askInfo){
			redirect(U('List/index'));
		}
		$askInfo['level'] = exp_to_level($askInfo['exp']);

		// 满意回答
		$db = D('AnswerInfoView');
		$where = array('answer.aid' => $id, 'answer.adopt' => 1); //加表名防止二义性
		$satisfy = $db->where($where)->find();

		// 全部回答
		$where = array('aid' => $id, 'adopt' => 0);
		$count = M('answer')->where($where)->count('id');
		import('ORG.Util.Page');
		$page = new Page($count, 10);
		$limit = $page->firstRow . ',' . $page->listRows;
		$where = array('answer.aid' => $id, 'answer.adopt' => 0);
		$answerInfo = $db->where($where)->limit($limit)->order('time DESC')->select();

		// 待解决的相关问题
		$where = array('cid' => $askInfo['cid'], 'solve' => 0, 'id' => array('neq', $id));
		$field = array('id', 'content', 'answer', 'time');
		$wait = M('ask')->where($where)->field($field)->limit(5)->select();

		// 赋值
		$this->askInfo = $askInfo;
		$this->satisfy = $satisfy;
		$this->answerInfo = $answerInfo;
		$this->wait = $wait;
		$this->page = $page->show();
		$this->display();
	}

	/**
	 * 提交回答表单处理
	 * @return [type] [description]
	 */
	public function answer(){
		if(!IS_POST) halt('页面不存在');

		$data = array(
			'content' => I('content', ''),
			'time' => time(),
			'uid' => session('uid'),
			'aid' => I('aid', 0, 'intval')
			);

		if($answerId = M('answer')->data($data)->add()){//成功
			// 更新问题信息
			$db = M('ask');
			$where = array('id' => $data['aid']);
			$db->where($where)->setInc('answer'); // 回答数 + 1

			//更新回答者信息
			$db = M('user');
			$where = array('id' => $data['uid']);
			$db->where($where)->setInc('answer'); // 回答数 + 1
			$db->where($where)->setInc('exp', C('LV_ANSWER')); // 加经验
			$db->where($where)->setInc('point', C('ANSWER')); // 加金币

			$this->success('回答成功', $_SERVER['HTTP_REFERER']);
		}
		else{
			$this->error('回答失败，请重试...');
		}
	}

	/**
	 * 采纳回答
	 * @return [type] [description]
	 */
	public function adopt(){
		$id = I('id', 0, 'intval'); //回答id
		$aid = I('aid', 0, 'intval'); // 问题id
		$uid = I('uid', 0, 'intval'); // 回答者id
		$uidAsk = session('uid'); // 提问者id

		if(M('answer')->where(array('id' => $id))->setField('adopt', 1)){// 成功
			// 更新问题信息
			M('ask')->where(array('id' => $aid))->setField('solve', 1);

			// 更新回答者信息
			$data = array(
				'adopt' => '+1', // 采纳数 + 1
				'point' => C('ADOPT'), // 加金币
				);
			$db = M('user');
			$db->where(array('id' => $uid))->data($data)->save();

			// 更新提问者信息
			$db->where(array('id' => $uidAsk))->setInc('exp', C('LV_ADOPT')); //加经验

			// 处理悬赏
			$reward = M('ask')->where(array('id' => $aid))->field(array('reward', 'uid'))->find();
			if($reward['reward']){ //如果有悬赏
				$db = M('user');
				$db->where(array('id' => $uid))->setInc('point', $reward['reward']); //增加回答者金币
				$db->where(array('id' => $reward['uid']))->setDec('point', $reward['reward']); //减少提问者金币
			}

			$this->success("已采纳", $_SERVER['HTTP_REFERER']);
		}
		else{
			$this->error('采纳失败，请重试...');
		}

	}
}
?>