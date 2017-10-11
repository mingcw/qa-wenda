<?php
/**
 * 关键字搜索控制器
 */
class SearchAction extends CommonAction{

	public function index(){
		$wd = urldecode(I('wd', '', 'trim'));
		$cid = I('cid', 0, 'intval');
		$filter = I('filter', 0, 'intval');

		$where = array();

		// 关键字
		if(!empty($wd)){
			$where['content'] = array('LIKE', '%' . $wd . '%');
		}
		// 筛选条件
		switch($filter){
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

		$count = M('Ask')->where($where)->count('id');
		import('ORG.Util.Page');
		$page = new Page($count, 12);
		$limit = $page->firstRow . ',' . $page->listRows;
		$askList = D('AskView')->where($where)->order('time DESC')->limit($limit)->select();		
		
		$this->wd = $wd;
		$this->askList = $askList;
		$this->page = $page->show();
		$this->filter = $filter;

		$this->display();
	}
}