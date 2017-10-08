<?php

/**
 * 展示页 问题详细 视图模型
 */
class AskInfoViewModel extends ViewModel{

	public $viewFields = array(
		'ask' => array(
			'id', 'content', 'reward', 'solve', 'time', 'cid', 'answer',
			'_type' => 'LEFT'
			),
		'user' => array(
			'id' => 'uid', 'username', 'exp',
			'_on' => 'ask.uid=user.id'
			)
		);
	// SELECT ask.id, ask.content, ask.reward, ask.solve, ask.time, ask.answer ask.cid, user.id as uid, user.username, user.exp FROM hd_ask ask LEFT JOIN hd_user user ON ask.uid = user.id;
}
?>