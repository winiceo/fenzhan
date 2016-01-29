// ò?D?jsμ??ˉo?
function allaround(dir) {
	// ???÷°′?￥μ??÷
	$("#searcnbtn").click(function() {
		search_location(dir);
	});
}
// 搜索跳转
function search_location(dir) {
	generateBackground();
	var listype = $("#searcnbtn").attr('detype');
	var key=$("input[name=key]").val();
	var keytype=1;
	var citycategory=$("input[name=citycategory]").val();
	var page=1;
	$.get(dir+"plus/ajax_search_location.php", {"act":listype,"key":key,"keytype":keytype,"citycategory":citycategory,"page":page},
		function (data,textStatus)
		 {	
			 window.location.href=data;
		 },"text"
	);
}
// 正在加载
function generateBackground() {
	var backgroundHtm = '<div id="bonfire-pageloader"><div class="bonfire-pageloader-icon"></div></div>';
	var html = jQuery('html');
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
/* ?à3?êD°?μ???é??? */
$(function(){
	var subsiteId = $("#citycategory").data('code'),
		districtIdArr = $("#citycategory").val().split('.'),
		subsiteHtm = '';
	if (subsiteId) {
		if (QS_city[subsiteId]) {
			var subsiteArr = QS_city[subsiteId].split('|');
			getDistrict(subsiteArr, subsiteId, 1);
		};
	} else {
		getDistrict(QS_subsite, subsiteId, 0);
	};
	$("#menu3").html(subsiteHtm);
	menuDown("#city_menu","#citycategory","#menu3","215px");

	function getDistrict(arr, parentid, position) {
		subsiteHtm += '<ul>';
		if (arr) {
			$.each(arr, function(index, val) {
				subsiteHtm += '<li';
				if (position == 1) {
					subsiteHtm += ' code="' + parentid + '.' + val.split(',')[0] + '">' + val.split(',')[1] + '</li>';
				} else {
					subsiteHtm += ' code="' + val.split(',')[0] + '.0">' + val.split(',')[1] + '</li>';
				};
				if (districtIdArr[position] == val.split(',')[0]) {
					$("#city_menu").html(val.split(',')[1]);
				};
			});
		};
		subsiteHtm += '</ul>';
	}

	function menuDown(menuId,input,menuList,width){
		$(menuId).on('click', function(event) {
			$(menuList).css({
				"width":width,
				"top": '34px',
				"left": '0px'
			});
			$(menuList).slideDown('fast');
			//éú3é±3?°
			$(menuId).parent("div").before("<div class=\"menu_bg_layer\"></div>");
			$(".menu_bg_layer").height($(document).height());
			$(".menu_bg_layer").css({ width: $(document).width(), position: "absolute", left: "0", top: "0" , "z-index": "0", "background-color": "#ffffff"});
			$(".menu_bg_layer").css("opacity","0");
			$(".menu_bg_layer").click(function(){
				$(".menu_bg_layer").remove();
				$(menuList).slideUp("fast");
			});
		});

		$(menuList+" li").click(function(){
			$(input).val($(this).attr('code'));
			$(menuId).html($(this).text());
			$(menuList).slideUp('fast');
			$(".menu_bg_layer").remove();
		});
	}
});