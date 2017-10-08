<?php

	/**
	 * 格式化打印数组
	 * @param  [type] $arr [description]
	 * @return [type]      [description]
	 */
	function p($arr){
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}

	/**
	 * 无限级分类，返回一维数组
	 * @param  [type]  $cate [description]
	 * @param  integer $pid  [description]
	 * @return [type]        [description]
	 */
	function unlimitedForLevel($cate, $pid = 0, $level = 1, $html = '-'){
		$arr = array();

		foreach ($cate as $v) {
			if($v['pid'] == $pid){
				$v['level'] = $level;
				$v['html'] = str_repeat($html, $level - 1);
				$arr[] = $v;
				$arr = array_merge($arr, unlimitedForLevel($cate, $v['id'], $level + 1, $html));
			}
		}

		return $arr;
	}

	/**
	 * 获取传递ID的所有子分类ID，返回一维数组
	 * @param  [type] $cate [description]
	 * @param  [type] $pid  [description]
	 * @return [type]       [description]
	 */
	function getChildrenId($cate, $pid){
		$arr = array();

		foreach($cate as $v){
			if($v['pid'] == $pid){
				$arr[] = $v['id'];
				$arr = array_merge($arr, getChildrenId($cate, $v['id']));
			}
		}

		return $arr;
	}
?>