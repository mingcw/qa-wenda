<?php
/**
 * 奖励规则控制器
 */
class RewardAction extends CommonAction{

	/**
	 * 金币奖励规则视图
	 * @return [type] [description]
	 */
	public function index(){
		$this->display();
	}

	/**
	 * 修改金币奖励规则 / 经验级别规则 / System控制器->网站设置
	 * @return [type] [description]
	 */
	public function edit(){
		$filename = './Conf/config.php';
		$config = array_merge(require $filename, array_change_key_case($_POST, CASE_UPPER));
		if(file_put_contents($filename, "<?php\r\nreturn " . var_export($config, true) . ";\r\n?>")){
			$this->success('修改成功', $_SERVER['HTTP_REFERER']);
		}
		else{
			$this->error('修改失败');
		}
	}

	/**
	 * 经验级别规则视图
	 * @return [type] [description]
	 */
	public function level(){
		$this->display();
	}
}
?>