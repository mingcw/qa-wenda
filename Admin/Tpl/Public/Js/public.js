$(function(){

/****************************** 回到顶部 ********************************/

	$('a[target!="iframe"]').click(function () {
		var obj = $(":root");
		if(obj.scrollTop() > 0 ){
			obj.animate({
				scrollTop: 0
			}, 600);
		}
	})

/******************************* 表格 jQ ********************************/

	// 表格隔行变色
	$('tr:eq(0)').addClass('tr_header');	//标题行背景色
	$('tr:even:gt(0)').addClass('tr_even');	//偶数行背景色，无视标题行用:gt(0)
	$('tr:odd').addClass('tr_odd');			//奇数行背景色

	// 鼠标悬停变色
	$('tr:gt(0)').hover(
		function(){
		$(this).addClass('tr_hover');
	},  function(){
		$(this).removeClass('tr_hover');
	});
});