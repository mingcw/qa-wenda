$(function () {
	$( '#sel-cate' ).click( function () {
		dialog($( '#category' ));
	} );

	$( 'textarea[name=content]' ).keyup( function () {
		var content = $( this ).val();
		//调用check函数取得当前字数
		var lengths = check(content);
		//最大允许输入50字个
		if (lengths[0] >= 50) {
			$( this ).val(content.substring(0, Math.ceil(lengths[1])));
		}
		var num = 50 - Math.ceil(lengths[0]);
		var msg = num < 0 ? 0 : num;
		//当前字数同步到显示提示
		$( '#num' ).html( msg );
	} );

	// 禁用超出的悬赏金额选项
	var option = $('select[name="reward"] option');
	$.each(option, function(i){
		if(parseInt($(this).val()) > point){ //超过用户金币的金额禁用
			$(this).attr('disabled', 'disabled');
		}
	});

	// 选择分类
	var cid = 0; //保存分类的ID，默认为0
	$('select[name="cate-one"]').change(function(){
		var obj = $(this);
		var pid = $(this).val();

		if(obj.index() < 3){ // 前2个分类列表框获取子级（index为0的是select[name='reward']元素）
			$.getJSON(getCate, {pid: pid}, function(data){
				var option = '';
				if(data){
					$.each(data, function(k, v){
						option += '<option value="' + v.id + '">' + v.name + '</option>';
					});
				}
				obj.next().html(option).show();
			}, 'json');
		}

		cid = obj.val();
	});

	// 确认分类
	$('#ok').click(function(){
		if(!cid){
			alert('请选择一个分类');
		}
		else{
			$('input[name="cid"]').val(cid);
			$('.close-window').trigger('click');
		}
	});

	// 提交问题 预处理
	$('.send-btn').click(function(){
		var cons = $('textarea[name="content"]');

		if(cons.val().trim() == ''){
			alert('请输入问题');
			cons.focus();
			return false;
		}

		if(!cid){
			alert('请选择问题分类');
			return false;
		}

		if(!on){
			$('.login').trigger('click');
			return false;
		}

	});
});


/**
 * 统计字数
 * @param  字符串
 * @return 数组[当前字数, 最大字数]
 */
function check (str) {
	var num = [0, 50];
	for (var i=0; i<str.length; i++) {
		
		if (str.charCodeAt(i) >= 0 && str.charCodeAt(i) <= 255){//字符串不是中文时
			num[0] = num[0] + 0.5;//当前字数增加0.5个
			num[1] = num[1] + 0.5;//最大输入字数增加0.5个
		} else {//字符串是中文时
			num[0]++;//当前字数增加1个
		}
	}
	return num;
}