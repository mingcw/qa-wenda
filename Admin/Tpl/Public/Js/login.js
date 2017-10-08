$(function(){

	//登录验证 0有误 1正确
	var validate = {username: 0, password: 0, code: 0}

	// 验证用户名
	$('input[name="username"]').blur(function(){

		var username = $(this);
		var username_val = username.val().trim();

		if(username_val == ""){
			hightlight(username, true);
			return false;
		}
		$.post(_control + '/checkAccount', {username: username_val}, function(stat){
			if(stat){//正确
				hightlight(username, false);
				validate.username = 1;
				return true;
			}
			else{//有误
				hightlight(username, true);
				return false;
			}
		}, 'json');
	});

	// 验证密码
	$('input[name="password"]').blur(function(){

		var username = $('input[name="username"]');
		var password = $('input[name="password"]');
		var username_val = username.val().trim();
		var password_val = password.val().trim();

		if(password_val == ""){
			hightlight(password, true);
			return false;
		}
		if(username_val == ""){
			return false;
		}
		$.post(_control + '/checkPassword', {username: username_val, password: password_val}, function(stat){
			if(stat){//正确
				hightlight(password, false);
				validate.password = 1;
				return true;
			}
			else{//有误
				hightlight(password, true);
				return false;
			}
		}, 'json');
	});

	// 验证验证码
	$('input[name="code"]').blur(function(){

		var code = $(this);
		var code_val = code.val().trim();

		if(code_val == ""){
			hightlight(code, true);
			return false;
		}
		$.post(_control + '/checkVerify', {code: code_val}, function(stat){
			stat = parseInt(stat);//必须转换。stat是字符串，0"字符串为真。不过有点奇怪，验证账号和密码时stat是数值型）
			if(stat){//正确
				hightlight(code, false);
				validate.code = 1;
				return true;
			}
			else{//有误
				hightlight(code, true);
				return false;
			}
		});
	});

	//提交表单
	$('#login-form').submit(function(){
		if(validate.username  == 1 && validate.password == 1 && validate.code == 1){
			return true;
		}
		else{
			$('input[name="username"]').trigger('blur');
			$('input[name="password"]').trigger('blur');
			$('input[name="code"]').trigger('blur');
			return false;
		}
	});
});


// 函数

/**
 * 功能：元素高亮或恢复
 * 参数1：obj 目标元素
 * 参数2：hightOrResume 1高亮，0恢复
 */
function hightlight(obj, highOrResume){
	if(highOrResume){
		obj.css('border-color', '#f71c79');
	}
	else{
		obj.css('border-color', '#ccc');
	}
}

/**
 * 刷新验证码
 */
function change_code(){
	$("#img-code").attr('src', _control + '/verify/'  + Math.random());
}

