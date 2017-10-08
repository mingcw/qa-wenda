<?php
/**
 * 前台首页控制器
 */
class IndexAction extends CommonAction {

	/**
	 * 首页视图
	 * @return [type] [description]
	 */
	public function index(){
		// 问题分类
		if(!$cate = S('cate')){
			$cate = M('category')->select();
			$cate = getChildsNlevel($cate);
			S('cate', $cate, 3600 * 24);	//生成缓存：栏目分类
		}

		// 待解决问题
		$where = array('solve' => 0, 'reward' => 0);
		$waitAsk = M('ask')->where($where)->field(array('id', 'content', 'answer'))->order('time DESC')->select();

		// 高悬赏问题
		$where = array('solve' => 0, 'reward' => array('GT', 0));
		$rewardAsk = M('ask')->where($where)->field(array('id', 'content', 'answer', 'reward'))->order(array('reward DESC', 'time DESC'))->select();

		$this->cate = $cate;
		$this->waitAsk = $waitAsk;
		$this->rewardAsk = $rewardAsk;
		$this->display();
	}
}
?>