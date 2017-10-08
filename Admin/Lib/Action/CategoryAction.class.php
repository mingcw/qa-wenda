<?php
/**
 * 问题分类控制器
 */
class CategoryAction extends CommonAction{

	/**
	 * 分类列表视图
	 * @return [type] [description]
	 */
	public function index(){
		$cate = M('category')->select();
		$this->cate = unlimitedForLevel($cate);
		$this->display();
	}

	/**
	 * 添加顶级分类视图
	 */
	public function addTop(){
		$this->display();
	}

	/**
	 * 添加子级分类视图
	 */
	public function addChild(){
		$pid = I('pid', 0, 'intval');
		$this->cate = M('category')->where(array('id' => $pid))->find();
		$this->display();
	}

	/**
	 * 添加分类表单处理
	 */
	public function addCate(){
		if(M('category')->add($_POST)){
			$this->success('添加成功', U('index'));
		}
		else{
			$this->error('添加失败');
		}
	}

	/**
	 * 修改分类视图
	 * @return [type] [description]
	 */
	public function edit(){
		$id = intval(I('id'));
		$this->cate = M('category')->where(array('id' => $id))->find();
		$this->display();
	}

	/**
	 * 修改分类操作
	 * @return [type] [description]
	 */
	public function editCate(){
		if(M('category')->save($_POST)){
			$this->success('修改成功', U('index'));
		}
		else{
			$this->error('修改失败');
		}
	}

	/**
	 * 删除分类
	 * @return [type] [description]
	 */
	public function del(){
		$id = $this->_get('id', 'intval');
		$db =  M('category');
		$cate = $db->field('name', true)->select();
		$cateId = getChildrenId($cate, $id);
		$cateId[] = $id;
		if(!$db->where(array('id' => array('IN', $cateId)))->delete()){
			$this->error('删除失败');
		}
		else{
			$this->success('删除成功', U('index'));
		}
	}
}
?>