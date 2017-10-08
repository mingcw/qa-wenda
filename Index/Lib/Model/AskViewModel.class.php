<?php

/**
 * 会员中心（列表页）问题列表 视图模型
 */
class AskViewModel extends ViewModel{

	public $viewFields = array(
		'ask' => array(
			'id', 'content', 'reward', 'answer', 'time',
			'_type' => 'LEFT'
			),
		'category' => array(
			'name',
			'_on' => 'ask.cid=category.id'
			)
		);
	// SELECT ask.content as cotent, ask.answer as answer, ask.time as time, category.name as name FROM hd_ask ask LEFT JOIN hd_category category ON ask.cid = category.id;
}
?>

