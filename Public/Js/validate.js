$(function(){

	// 刷新验证码
	$('#verify-img').click(function(){
		$(this).attr('src', control + 'verify/' + Math.random());
	});

	// 有效性验证标志位
	var validate = {
		account: false,
		username: false,
		pwd: false,
		pwded: false,
		verify: false,
		login_account: false,
		login_pwd: false
	}

	// 提示消息
	var msg = '';

	// 注册验证
	var register = $('form[name="register"]');
	register.submit(function(){
		
		if(validate.account && validate.username && validate.pwd
		 && validate.pwded && validate.verify){
			return true;
		}
		else{
			$('input[name="account"]', register).trigger('blur');
			$('input[name="username"]', register).trigger('blur');
			$('input[name="pwd"]', register).trigger('blur');
			$('input[name="pwded"]', register).trigger('blur');
			$('input[name="verify"]', register).trigger('blur');
			return false;
		}
	});

	// 验证账号
	$('input[name="account"]', register).blur(function(){

		var account = $(this).val().trim();
		var span = $(this).next();

		if(account == ""){
			msg = '请填写账号';
			span.html(msg).addClass('error');
			validate.account = false;
			return;
		}

		if(!/^[a-zA-Z]\w{4,19}$/g.test(account)){
			msg = '请以5-20位字母、数字、下划线填写，字母开头';
			span.html(msg).addClass('error');
			validate.account = false;
			return;
		}

		$.post(control + 'checkAccount', {account: account}, function(status){
			if(status){
				msg = '';
				span.html(msg).removeClass('error');
				validate.account = true;
			}
			else{
				msg = '账号已存在';
				span.html(msg).addClass('error');
				validate.account = false;
			}
		}, 'json');
	});

	// 验证用户名
	$('input[name="username"]', register).blur(function(){

		var username = $(this).val().trim();
		var span = $(this).next();

		if(username == ""){
			msg = '请填写用户名';
			span.html(msg).addClass('error');
			validate.username = false;
			return;
		}

		if(!/^[\u2E80-\u9FFF|\w]{2,14}$/g.test(username)){
			msg = '请以2-14位字母、数字、或中文填写';
			span.html(msg).addClass('error');
			validate.username = false;
			return;
		}

		$.post(control + 'checkUsername', {username: username}, function(status){
			if(status){
				msg = '';
				span.html(msg).removeClass('error');
				validate.username = true;
			}
			else{
				msg = '用户名已存在';
				span.html(msg).addClass('error');
				validate.username = false;
			}
		}, 'json');
	});

	// 验证密码
	$('input[name="pwd"]', register).blur(function(){

		var pwd = $(this).val().trim();
		var span = $(this).next();

		if(pwd == ''){
			msg = '请填写密码';
			span.html(msg).addClass('error');
			validate.pwd = false;
			return;
		}

		if(!/^[\w]{6,20}$/g.test(pwd)){
			msg = '密码必须由6到20位字母、数字、下划线组成';
			span.html(msg).addClass('error');
			validate.pwd = false;
			return;
		}

		msg = '';
		span.html(msg).remove('error');
		validate.pwd = true;
	});

	// 验证确认密码
	$('input[name="pwded"]', register).blur(function(){

		var pwded = $(this).val().trim();
		var span = $(this).next();
		var pwd = $('input[name="pwd"]', register).val().trim();

		if(pwded == ""){
			msg = '请再次确认密码';
			span.html(msg).addClass('error');
			validate.pwded = false;
			return;
		}

		if(pwded != pwd){
			msg = '两次密码不一致';
			span.html(msg).addClass('error');
			validate.pwded = false;
			return;
		}

		msg = '';
		span.html(msg).removeClass('error');
		validate.pwded = true;
	});

	// 验证验证码
	$('input[name="verify"]', register).blur(function(){

		var verify = $(this).val().trim();
		var span = $(this).next().next();

		if(verify == ''){
			msg = '请填写验证码';
			span.html(msg).addClass('error');
			validate.verify = false;
			return;
		}

		$.post(control + 'checkVerify', {verify: verify}, function(status){
			if(status){
				msg = '';
				span.html(msg).removeClass('error');
				validate.verify = true;
			}
			else{
				msg = '验证码有误';
				span.html(msg).addClass('error');
				validate.verify = false;
			}
		}, 'json');
	});

	var login = $('form[name="login"]'); //登录表单

	login.submit(function(){
		if(validate.login_account && validate.login_pwd){
			return true;
		}
		else{
			$('input[name="account"]', login).trigger('blur');
			$('input[name="pwd"]', login).trigger('blur');
			return false;
		}
	});

	var span = $('#login-msg');	// 登录提示
	// 验证账号
	$('input[name="account"]', login).blur(function(){

		var account = $(this).val().trim();

		if(account == ''){
			msg = '请填写账号';
			span.html(msg);
			validate.login_account = false;
			return;
		}
	});

	// 验证密码
	$('input[name="pwd"]', login).blur(function(){

		var pwd = $(this).val().trim();
		var account = $('input[name="account"]', login).val().trim();

		if(pwd == ''){
			msg = '请填写密码';
			span.html(msg);
			validate.login_pwd = false;
			return;
		}

		if(account == ''){
			msg = '请填写账号';
			span.html('msg');
			validate.login_account = false;
			return;
		}

		var data = {
			account: account,
			password: pwd
		};

		$.post(control + 'checkLogin', data, function(status){
			if(status){
				msg = '';
				span.html(msg);
				validate.login_pwd = true;
				validate.login_account = true;
			}
			else{
				msg = '账号或密码有误';
				span.html(msg);
				validate.login_pwd = false;
				validate.login_account = false;
			}
		}, 'json');

	});
});