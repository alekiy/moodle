$(function(){
	if(!$('body').hasClass('notloggedin') || !$('body').is('#page-site-index')) {
		var footerHeight = $("#page-footer").height();
		$("#top").css("padding-bottom", footerHeight);
		$("#page-footer").css("margin-top", footerHeight);
	}
});