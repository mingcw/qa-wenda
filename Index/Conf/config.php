<?php
$config = array(
	// 页面Trace
	'SHOW_PAGE_TRACE' => true,

	// URL rewrite
	'URL_CASE_INSENSITIVE' => true,
	'URL_ROUTER_ON' => true,
	'URL_MODEL' => 2,

	// 自定义标签
	'APP_AUTOLOAD_PATH' => '@.TagLib', //加载当前Index项目下的TagLib目录
	'TAGLIB_BUILD_IN' => 'Cx,Wenda',   //Cx是Think内置标签库，Wenda是自定义标签库

	// 自定义session驱动 redis存储
	'SESSION_TYPE' => 'Redis',
	'SESSION_PREFIX' => 'sess_',
	'SESSION_EXPIRE' => 1800,
	
	// REDIS的连接参数
	'REDIS_HOST' => '127.0.0.1',
	'REDIS_PORT' => '6379',

	// 加密KEY
	'ENCRYPT_KEY' => md5('vNPD3L3iw9yjrNPAuuO1xLuwzOJeX14'),
	// COOKIE参数
	'COOKIE_EXPIRE' => time() + 3600 * 24 * 7, // 有效期一周
);
return array_merge(require './Conf/config.php', $config);
?>