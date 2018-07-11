(function($) {
  $('[data-toggle="offcanvas"]').on('click', function () {
    $('.offcanvas-collapse').toggleClass('open');
    $('.offcanvas-collapse-bg').toggleClass('open');
    $('.m-nav-close').addClass('open');
  });
  $('.offcanvas-collapse-bg').on('click', function () {
    $('.offcanvas-collapse').removeClass('open');
    $('.offcanvas-collapse-bg').removeClass('open');
    $('.m-nav-close').removeClass('open');
  });
  $('.m-nav-close').on('click', function () {
    $('.offcanvas-collapse').removeClass('open');
    $('.offcanvas-collapse-bg').removeClass('open');
    $('.m-nav-close').removeClass('open');
  });

  $('.dropdown').on('show.bs.dropdown', function(e){
    $(this).find('.dropdown-menu').first().stop(true, true).slideDown(300);
  });

  $('.dropdown').on('hide.bs.dropdown', function(e){
    $(this).find('.dropdown-menu').first().stop(true, true).slideUp(200);
  });
})(jQuery);