<?php

	/**
	 * 格式化打印数组
	 * @param  [type] $arr [description]
	 * @return [type]      [description]
	 */
	function p($arr){
		dump($arr, true, '', false);
	}

	/**
	 * 提取记录数组中的一个字段，返回一维索引数组
	 * @param  [type] $cate  [description]
	 * @param  [type] $field [description]
	 * @return [type]        [description]
	 */
	function one_field($cate, $field){
		$arr = array();

		foreach($cate as $v){
			$arr[] = $v[$field];
		}

		return $arr;
	}
	
	/**
	 * 格式化一个时间字符串
	 * @param  [type] $time 发布时间
	 * @return [type]       [description]
	 */
	function time_formate($time){
		$now = time();	//当前时间
		$diff = $now - $time;	//发布后到现在经过的秒数
		$today  = strtotime(date('Y-m-d')); //今天凌晨
		$yestoday = strtotime('-1 day', $today); //昨天凌晨

		$str = '';
		switch(true){
			case $diff < 60:
				$str = '刚刚';
				break;
			case $diff < 3600:
				$str = floor($diff / 60) . '分钟前';
				break;
			case $diff < (3600 * 8):
				$str = floor($diff / 3600) . '时' . floor($diff % 3600 / 60) . '分前';
				break;
			case $time > $today:
				$str = '今天' . floor(($time - $today) / 3600) . ':' . floor(($time - $today) % 3600 / 60);
				break;
			case $time > $yestoday:
				$str = '昨天' . date('H:i', $time);
				break;
			default:
				$str = date('Y-m-d H:i', $time);
		}

		return $str;


	}
	/**
	 * 获取子级分类，返回多维数组
	 * @param  [type]  $cate        [description]
	 * @param  integer $target_levl 传递2表示获取到第2级分类为止，默认为2
	 * @param  [type]  $now_level   [description]
	 * @return [type]               [description]
	 */
	function getChildsNlevel($cate, $pid = 0, $target_levl = 2, $now_level = 1) {
		$arr = array();

		if($now_level <= $target_levl){
			foreach ($cate as $v){
				if($v['pid'] == $pid){
					$v['child'] = getChildsNlevel($cate, $v['id'], $target_levl, $now_level + 1);
					$arr[] = $v;
				}
			}
		}
		return $arr;
	}

	/**
	 * 获取所有父级，返回一维数组
	 * @param  [type] $cate [description]
	 * @param  [type] $id   [description]
	 * @return [type]       [description]
	 */
	function getParents($cate, $id){
		$arr = array();

		foreach($cate as $v){
			if($v['id'] == $id){
				$arr = getParents($cate, $v['pid']);
				$arr[] = $v;
			}
		}

		return $arr;
	}

	/**
	 * 加密运算
	 * @param  [type]  $value 1:加密，0：解密
	 * @param  integer $type  [description]
	 * @return [type]         [description]
	 */
	function encrypt($value,  $type = 1){
		$key = C('ENCRYPT_KEY');
		if($type){
		 	return str_replace('=', '', base64_encode($value ^ $key));
		}
		else{
			return base64_decode($value) ^ $key;
		}
	}

	/**
	 * 经验值到等级
	 * @param  [type] $exp [description]
	 * @return [type]      [description]
	 */
	function exp_to_level($exp){
		switch (true) {
			case $exp >= C('LV_LV20'):
				return 20;
			case $exp >= C('LV_LV19'):
				return 19;
			case $exp >= C('LV_LV18'):
				return 18;
			case $exp >= C('LV_LV17'):
				return 17;
			case $exp >= C('LV_LV16'):
				return 16;
			case $exp >= C('LV_LV15'):
				return 15;
			case $exp >= C('LV_LV14'):
				return 14;
			case $exp >= C('LV_LV13'):
				return 13;
			case $exp >= C('LV_LV12'):
				return 12;
			case $exp >= C('LV_LV11'):
				return 11;
			case $exp >= C('LV_LV10'):
				return 10;
			case $exp >= C('LV_LV9'):
				return 9;
			case $exp >= C('LV_LV8'):
				return 8;
			case $exp >= C('LV_LV7'):
				return 7;
			case $exp >= C('LV_LV6'):
				return 6;
			case $exp >= C('LV_LV5'):
				return 5;
			case $exp >= C('LV_LV4'):
				return 4;
			case $exp >= C('LV_LV3'):
				return 3;
			case $exp >= C('LV_LV2'):
				return 2;
			default:
				return 1;
		}
	}
?>