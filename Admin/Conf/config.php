<?php
$config = array(
	// 模板替换字符串
	'TMPL_PARSE_STRING' => array(
			'__PUBLIC__' => __ROOT__ . '/' . APP_NAME . '/Tpl/Public'
		),
	// URL伪静态后缀
	'URL_HTML_SUFFIX' => '',

	// 自定义SESSION存储 Db驱动
	'SESSION_TYPE' => 'Db',
	'SESSION_PREFIX' => 'sess_',
	'SESSION_EXPIRE' => 1800,
);
return array_merge(require './Conf/config.php', $config);
?>