<?php
/**
 * 后台公共控制器
 */
class CommonAction extends Action {

	public function _initialize(){

		if(!session('uid') || !session('uname')){
			$this->redirect('Login/index');
		}
	}
}
?>