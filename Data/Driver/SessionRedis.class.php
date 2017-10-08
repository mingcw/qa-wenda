<?php

/**
 * 自定义SESSION驱动 Redis存储
 */
class SessionRedis{

	// redis实例对象
	protected $redis;

	// redis有效期
	protected $expire;


	public function execute(){
		session_set_save_handler(
			array(&$this, 'open'),
			array(&$this, 'close'),
			array(&$this, 'read'),
			array(&$this, 'write'),
			array(&$this, 'destroy'),
			array(&$this, 'gc')
			);
	}


	/**
	 * 打开
	 * @param  [type] $savePath    [description]
	 * @param  [type] $sessionName [description]
	 * @return [type]              [description]
	 */
	public function open($savePath, $sessionName){
		$this->expire = intval(C('SESSION_EXPIRE') ? C('SESSION_EXPIRE') : ini_get('session.gc_maxlifetime'));
		$this->redis = new Redis();
		return $this->redis->connect(C('REDIS_HOST'), C('REDIS_PORT'));
	}

	/**
	 * 关闭
	 * @return [type] [description]
	 */
	public function close(){
		return $this->redis->close();
	}

	/**
	 * 读session
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function read($id){
		$id = C('SESSION_PREFIX') . $id;
		$value = $this->redis->get($id);
		return $value ? $value : '';
	}

	/**
	 * 写session
	 * @param  [type] $id   [description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function write($id, $data){
		$id = C('SESSION_PREFIX') . $id;
		// $data = addslashes($data); //redis数据库进行了字符转义，这里不再转义
		return $this->redis->set($id, $data, $this->expire);
	}

	/**
	 * 销毁session
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function destroy($id){
		$id = C('SESSION_PREFIX') . $id;
		return $this->redis->delete($id);
	}

	/**
	 * 垃圾处理
	 * @param  [type] $maxLifeTime [description]
	 * @return [type]              [description]
	 */
	public function gc($maxLifeTime){
		return true; // 在write()方法中,set session时已经写入了有效期，redis会自动过期回收，这里直接返回
	}
}
?>
