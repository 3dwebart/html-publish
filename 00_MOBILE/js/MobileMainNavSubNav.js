(function($) {
  $('[data-toggle="offcanvas"]').on('click', function () {
    $('#panel-list').addClass('open');
  });
  $('.close').on('click', function () {
    $('#panel-list').removeClass('open');
  });
  var x = 0;
  $('#show-hide-info-btn').on('click', function () {
    x = (x - 1) * -1;
    if(x == 0) {
      $(this).find('.show-hide').text('show');
      $(this).find('.fas').attr('class', 'fas fa-angle-down');
    } else {
      $(this).find('.show-hide').text('hide');
      $(this).find('.fas').attr('class', 'fas fa-angle-up');
    }
  });
  $('.dropdown').on('show.bs.dropdown', function(e){
    $(this).find('.dropdown-menu').first().stop(true, true).slideDown(300);
  });

  $('.dropdown').on('hide.bs.dropdown', function(e){
    $(this).find('.dropdown-menu').first().stop(true, true).slideUp(200);
  });
})(jQuery);