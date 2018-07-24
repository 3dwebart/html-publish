(function($) {
	/* BIGIN :: 모바일 메뉴 클릭시 좌에서 우로 슬라이드 되는 메뉴 */
	// 메뉴 open
	$('[data-toggle="offcanvas"]').on('click', function () {
		$('#panel-list').addClass('open');
	});
	// 메뉴 close
	$('.close').on('click', function () {
		$('#panel-list').removeClass('open');
	});
	/* BIGIN :: 모바일 메뉴 클릭시 좌에서 우로 슬라이드 되는 메뉴 */

	/* BIGIN :: 모바일 메뉴 오픈시 하단 언어 선택부분 애니메이션 스크립트 */
	$('.dropdown').on('show.bs.dropdown', function(e){
		$(this).find('.dropdown-menu').first().stop(true, true).slideDown(300);
	});

	$('.dropdown').on('hide.bs.dropdown', function(e){
		$(this).find('.dropdown-menu').first().stop(true, true).slideUp(200);
	});
	/* END :: 모바일 메뉴 오픈시 하단 언어 선택부분 애니메이션 스크립트 */
})(jQuery);