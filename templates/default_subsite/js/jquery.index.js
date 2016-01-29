function index(dir,templatedir)
{
	$(".lazyload div img").lazyload({ placeholder: templatedir+"images/index/84.gif", effect:"fadeIn" });
	// 首页中部格子广告
	$(".comimgtip").hover(function() {
		$(this).css("zIndex",2).find('.ad-more-info').show();
	}, function() {
		$(this).css("zIndex",0).find('.ad-more-info').hide();
	});
	// 格子广告位最右侧的广告去除右边距
	$(".ad-row .ad-31").each(function(index, el) {
		if ((index+1)%3 == 0) {
			$(this).addClass('nomr');
		};
	});
	$(".ad-row .ad-51").each(function(index, el) {
		if ((index+1)%5 == 0) {
			$(this).addClass('nomr');
		};
	});
	// 判断格子广告是否显示，添加对应的样式控制上边距
	if ($(".ad-row .ad-51").length > 0 || $(".ad-row .ad-31").length > 0) {
		$(".index-data-wrap-i7").addClass('index-data-wrap-nmt');
	};
	if ($(".ad-row .ad-31").length > 0) {
		if ($(".a23058d")) {
			$(".a23058d").addClass('ad-job-list-i7');
		};
	};
	// 回车搜索
	$('#keyForIndexSearch').keydown(function(e) {
		if (e.keyCode==13) {
			search_location(dir);
		}
	});
	// 搜索按钮点击
	$("#btnForIndexSearch").click(function() {
		search_location(dir);
	});
}
// 搜索跳转
function search_location(dir) {
	generateBackground();
	var listype = 'QS_jobslist';
	var key=$("input[name=key]").val();
	var page=1;
	$.get(dir+"plus/ajax_search_location.php", {"act":listype,"key":key,"page":page},
		function (data,textStatus)
		 {	
			 window.location.href=data;
		 },"text"
	);
}
// 正在加载
function generateBackground() {
	var backgroundHtm = '<div id="bonfire-pageloader"><div class="bonfire-pageloader-icon"></div></div>';
	var html = jQuery('body');
	html.append(backgroundHtm);
	jQuery(window).resize(function(){
		 resizenow();
	});
	function resizenow() {
		var browserwidth = jQuery(window).width();
		var browserheight = jQuery(window).height();
		jQuery('.bonfire-pageloader-icon').css('right', ((browserwidth - jQuery(".bonfire-pageloader-icon").width())/2)).css('top', ((browserheight - jQuery(".bonfire-pageloader-icon").height())/2 + $(document).scrollTop() - 109));
	};
	resizenow();
}