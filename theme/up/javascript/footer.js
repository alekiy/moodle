$(function(){
	if($('body').hasClass('notloggedin')== false) {
		var footerHeight = $("#page-footer").height();
		$("#top").css("padding-bottom", footerHeight);
		$("#page-footer").css("margin-top", footerHeight);
	}
});