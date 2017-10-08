<?php
/**
 * 列表页控制器
 */
class ListAction extends CommonAction{

	/**
	 * 列表页视图
	 * @return [type] [description]
	 */
	public function index(){
		$id = I('id', 0, 'intval');
		$filter = I('filter', 0, 'intval');

		// 分类列表

		// 子级分类
		$db = M('category');
		$cate = $db->where(array('pid' => $id))->select();
		$cid = one_field($cate, 'id');
		$cid[] = $id;

		if(!$cate){
			// 兄弟分类
			$pid = $db->where(array('id' => $id))->getField('pid');
			$cate = $db->where(array('pid' => $pid))->select();

			 // 顶级分类
			if(!$cate){
				$cate = $db->where(array('pid' => 0))->select();
				$cid = one_field($cate, 'id');
			}
		}


		// 问题列表
		$where = array('cid' => array('IN', $cid));

		// 筛选条件
		switch ($filter) {
			case 1:
				$where['solve'] = 1;
				break;
			case 2:
				$where['reward'] = array('GT', 0);
				break;
			case 3:
				$where['answer'] = 0;
				break;
			default:
				$where['solve'] = 0;
		}

		// 分页
		$count = M('ask')->where($where)->count('id');
		import('ORG.Util.Page');
		$page = new Page($count, 12);
		$limit = $page->firstRow . ',' . $page->listRows;
		$askList = D('AskView')->where($where)->order('time DESC')->limit($limit)->select();

		// 赋值
		$this->cate = $cate;
		$this->askList = $askList;
		$this->page = $page->show();
		$this->getId = $id;
		$this->filter = $filter;

		$this->display();
	}
}
?>