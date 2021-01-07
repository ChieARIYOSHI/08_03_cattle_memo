$(function() {
  $('.navi_icon').click(function() {
    $(this).toggleClass('active');
    if ($(this).hasClass('active')) {
      $('.navi_menu').addClass('active');
    } else {
      $('.navi_menu').removeClass('active');
    }
  });
});