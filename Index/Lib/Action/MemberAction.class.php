<?php
/**
 * 会员中心控制器
 */
Class MemberAction extends CommonAction {

	Public function _initialize(){//初始化分配角色
		$this->role = session('uid') && session('uid') == $_GET['id'] ? '我' : 'TA';
	}

	/**
	 * 我的首页视图
	 * @return [type] [description]
	 */
	Public function index(){
		$id = $this->_get('id', 'intval'); //用户ID

		// 用户信息
		$user = M('user')->field(array('point', 'exp', 'adopt', 'ask', 'answer'))->find($id);
		if(!$user){
			redirect(__APP__);
		}
		$this->user = $user;

		// 问题列表
		$this->askList = D('AskView')->where(array('uid' => $id))->limit(10)->order('time DESC')->select();

		// 回答列表
		$this->answerList = D('AnswerView')->where(array('answer.uid' => $id))->limit(10)->order('time DESC')->select();

		$this->display();
	}

	/**
	 * 我的提问 视图
	 * @return [type] [description]
	 */
	Public function myAsk(){
		$id = $this->_get('id', 'intval'); //用户ID

		// 待解决问题
		$where = array('uid' => $id, 'solve' => 0);
		$this->myWait = D('AskView')->where($where)->limit(10)->order('time DESC')->select();

		// 已解决问题
		$where['solve'] = 1;
		$this->mySolved = D('AskView')->where($where)->limit(10)->order('time DESC')->select();

		$this->display();
	}

	/**
	 * 我的回答 视图
	 * @return [type] [description]
	 */
	Public function myAnswer(){
		$id = I('id', 0, 'intval'); //用户id

		// 全部回答
		$where = array('answer.uid' => $id);
		$this->myAnswer = D('AnswerView')->where($where)->order('time DESC')->select();

		// 被采纳数
		$where = array('answer.uid' => $id, 'adopt' => 1);
		$this->count = D('AnswerView')->where($where)->order('time DESC')->count();

		$this->display();
	}

	/**
	 * 我的等级 视图
	 * @return [type] [description]
	 */
	Public function myLevel(){
		$id = I('id', 0, 'intval'); //用户ID

		// 我的经验
		$this->myExp = M('user')->where(array('id' => $id))->getField('exp');
		// 我的等级
		$this->myLevel = exp_to_level($this->myExp);

		$this->display();
	}

	/**
	 * 我的金币 视图
	 * @return [type] [description]
	 */
	Public function myPoint(){
		$id = I('id', 0, 'intval'); //用户ID

		// 我的金币
		$this->point = M('user')->where(array('id' => $id))->getField('point');

		$this->display();
	}

	/**
	 * 我的头像
	 * @return [type] [description]
	 */
	Public function myInfo(){
		$id = I('id', 0, 'intval');

		// 我的头像
		$this->myFace = M('user')->where(array('id' => $id))->getField('face');

		$this->display();
	}

	/**
	 * 头像上传表单处理
	 * @return [type] [description]
	 */
	Public function upload(){
		if(!IS_POST) halt('页面不存在');

		$id = I('id', 0, 'intval');

		import('ORG.Net.UploadFile');
		$upload = new UploadFile();
		$upload->autoSub = true;
		$upload->subType = 'date';
		$upload->dateFormat = 'Ym';
		$upload->maxSize  = 3145728 ;
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');


		if($upload->upload('./Uploads/Face/')){ // 上传成功

			$info = $upload->getUploadFileInfo();
			$faceName = $info[0]['savename']; // 头像文件名
			M('user')->where(array('id' => $id))->setField('face', $faceName); // 更新数据库

			redirect($_SERVER['HTTP_REFERER']);
		}
		else{// 失败
			$this->error($upload->getErrorMsg());
		}
	}

}
?>