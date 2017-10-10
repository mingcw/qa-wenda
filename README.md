## qa-wenda

基于 ThinkPHP 3.1.3 的问答系统 - QA 问答

## 系统介绍

这是一个使用 ThinkPHP 3.1.3 开发的问答系统。游客或注册用户可以浏览或发起提问、回答问题。每天登录会获取相关的经验和金币，发起提问或帮助别人回答问题也可以获取一定的经验和金币。
经验的增加会伴随等级的上升，金币则可以用来发起悬赏，提高问题的回答率。被采纳的回答会获取更高的经验和金币。而一旦用户的回答或提问涉及违法违规等，将会被管理员删除，并扣除一定的经验和金币。核心服务就是这样，是一个可以扩展的简易问答系统。

## 会话相关

* 前台使用`Redis`存储 Session，配合 Cookie 实现记住登录。
* 后台使用数据库`Db`存储 Session。

## 安装说明

1. 下载一份 ThinkPHP3.1.3 的完整包。把解压后的`ThinkPHP/`目录拷贝到项目根目录下。（[官网下载地址](http://www.thinkphp.cn/down/338.html)）

2. 导入数据库。把数据库文件`Data/SQL/qa-wenda.sql`导入到本地 MySQL 数据库。（已定义数据库名为`qa-wenda`）

3. 配置数据库。打开`Conf/config.php`配置前 5 行。

4. 添加`hd_session`表。表结构如下：
```
CREATE TABLE hd_session (
  session_id varchar(255) NOT NULL,
  session_expire int(11) NOT NULL,
  session_data blob,
  UNIQUE KEY `session_id` (`session_id`)
); 
```
其中，`hd_`是本地配置的表前缀。（表结构参考自官方数据库驱动`Session`相关文件注释：`ThinkPHP/Extend/Driver/Session/SessionDb.class.php`）

5. 添加`Redis`驱动。把`Data/Driver/SessionRedis.class.php`文件拷贝到`ThinkPHP/Extend/Driver/Session/`目录下。

6. 配置`Redis`连接参数。在`Index/Conf/config.php`第 21 、22 行配置。

## 管理员账号

账号|密码|角色
----|----|----
admin|admin|管理员

## 协议

MIT