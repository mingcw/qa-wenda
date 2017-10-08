<?php

/**
 * 自定义标签库 Wenda
 */
class TagLibWenda extends TagLib{

	/**
	 * 定义标签
	 * @var array
	 */
	protected $tags = array(
		'topcate' => array('attr' => 'limit'), //定义topcate标签，'attr'指定属性，'close'默认为1是块标签
		'userinfo' => array('attr' => 'id'),
		'location' => array('attr' => 'cid'),
		'todayAnsMostUser' => array('attr' => ''),
		'historyAnsMostUser' => array('attr' => ''),
		'honor' => array('attr' => 'limit')
		);

	/**
	 * 顶级分类 块标签
	 * @param  [type] $attr    [description]
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	public function _topcate($attr, $content){

		$attr = $this->parseXmlAttr($attr);
		$limit = isset($attr['limit']) ? $attr['limit'] : '';

		$str = '<?php ';
		$str .= '$_topcate_cate = M("category")->where(array("pid" => 0))->limit(' . $limit . ')->select();';//在ThinkPHP里limit(0, 10)等效于limit(10)
		$str .= 'foreach($_topcate_cate as $v):';
		$str .= 'extract($v);?>';
		$str .= $content;
		$str .= '<?php endforeach; ?>';

		return $str;
	}

	/**
	 * 用户信息 块标签
	 * @param  [type] $attr    [description]
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	public function _userinfo($attr, $content){

		$attr = $this->parseXmlAttr($attr);
		$id = $attr['id'];

		$str = <<<str
<?php
	\$field = array("id", "username", "face", "answer", "adopt", "ask", "point", "exp");
	\$_userinfo_result =  M("user")->field(\$field)->find({$id});
	extract(\$_userinfo_result);
	\$face = empty(\$face) ? '__PUBLIC__/Images/noface.jpg' : '__ROOT__/Uploads/Face/' . \$face;
	\$adopt = floor(\$adopt / \$answer * 100) . "%";
	\$level = exp_to_level(\$exp);
?>
str;

		$str .= $content;
		return $str;
	}

	/**
	 * 列表页 当前位置 块标签
	 * @param  [type] $attr    [description]
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	public function _location($attr, $content){
		$attr = $this->parseXMLAttr($attr);
		$cid = $attr['cid'];

		$str = <<<str
<?php
	if(!\$_location_category = S('\$_location_category' . {$cid})){
		\$_location_category = M('category')->select();
		\$_location_category = getParents(\$_location_category, {$cid});
		S('\$_location_category' . {$cid}, \$_location_category, 3600 * 24); // 缓存一天
	}
	foreach(\$_location_category as \$v):
		extract(\$v);
?>
str;
		$str .= $content;
		$str .= '<?php endforeach; ?>';
		return $str;
	}

	/**
	 * 本日回答问题最多的人
	 * @param  [type] $attr    [description]
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	public function _todayAnsMostUser($attr, $content){
		$str = <<<str
<?php
	\$today = strtotime(date('Y-m-d'));
	\$_todayAnsMostUser_sql = 'select user.id, user.face, user.username, user.exp, user.answer, user.adopt, count(user.id) as most FROM hd_answer answer LEFT JOIN hd_user user ON answer.uid = user.id where time > ' . \$today . ' GROUP BY user.id ORDER BY most DESC LIMIT 1';
	\$_todayAnsMostUser_result = M()->query(\$_todayAnsMostUser_sql);
	\$_todayAnsMostUser_star = \$_todayAnsMostUser_result[0];
	\$_todayAnsMostUser_star['level'] = exp_to_level(\$_todayAnsMostUser_star['exp']);
?>
str;
		$str .= $content;
		return $str;
	}

	/**
	 * 历史回答最多的人
	 * @param  [type] $attr    [description]
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	public function _historyAnsMostUser($attr, $content){
		$str = <<<str
<?php
	\$field = array('id', 'username', 'face', 'exp', 'answer', 'adopt');
	\$_historyAnsMostUser_star = M('user')->field(\$field)->order('answer DESC')->find();
	\$_historyAnsMostUser_star['level'] = exp_to_level(\$_historyAnsMostUser_star['exp']);
?>
str;
		$str .= $content;
		return $str;
	}

	/**
	 * 问答助人光荣榜
	 * @param  [type] $attr    [description]
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	public function _honor($attr, $content){
		$attr = $this->parseXmlAttr($attr);
		$limit = $attr['limit'];

		$str = <<<str
<?php
	\$field = array('id', 'username', 'answer');
	\$_honor_list = M('user')->field(\$field)->order('answer DESC')->limit({$limit})->select();
	foreach(\$_honor_list as \$k => \$v):
		extract(\$v);
?>
str;
		$str .= $content;
		$str .= '<?php endforeach; ?>';
		return $str;

	}
}
?>
