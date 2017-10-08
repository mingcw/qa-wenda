<?php

/**
 * 展示页 回答详细 视图模型
 */
class AnswerInfoViewModel extends ViewModel{

	public $viewFields = array(
		'answer' => array(
			'id', 'content', 'time',
			'_type' => 'LEFT'
			),
		'user' => array(
			'id' => 'uid', 'username', 'face', 'exp', 'adopt', 'answer',
			'_on' => 'answer.uid=user.id'
			)
		);
	// SELECT answer.id, answer.content, answer.time, user.id as uid, user.username, user.face FROM hd_answer answer LEFT JOIN hd_user user ON answer.uid = user.id;
}
?>