<?php

/**
 * 会员中心 回答列表 视图模型
 */
class AnswerViewModel extends ViewModel{

	public $viewFields = array(
		'answer' => array(
			'adopt',
			'_type' => 'LEFT'
			),
		'ask' => array(
			'id', 'content', 'time', 'answer',
			'_on' => 'answer.aid=ask.id',
			'_type' => 'LEFT'
			),
		'category' => array(
			'name',
			'_on' => 'ask.cid=category.id'
			)
		);
// SELECT ask.id, ask.content, ask.time, ask.answer, category.name FROM hd_answer answer LEFT JOIN hd_ask ask ON answer.aid = ask.id LEFT JOIN hd_category category ON ask.cid=category.id;
}
?>