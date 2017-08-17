$(document).on('ready cookieUpdate',function () {

   // TODO: check value
   if ($.cookie('hrzg-widget_view-mode')) {
      $('.hrzg-widget-container-controls, .hrzg-widget-widget-controls').hide();
      $('.hrzg-widget-widget, .hrzg-widget-widget-container').css('outline', '0px');
   } else {
      $('.hrzg-widget-container-controls, .hrzg-widget-widget-controls').show();
      $('.hrzg-widget-widget, .hrzg-widget-widget-container').css('outline', '');
   }

});

$(document).ready(function () {
    var stickyContainer = $('.sticky-controls');
    if(stickyContainer.length) {
        var top = stickyContainer.offset().top;
        $(window).scroll(function (event) {
            var y = $(this).scrollTop();
            if (y >= top) {
                stickyContainer.addClass('sticky-fixed');
            } else {
                stickyContainer.removeClass('sticky-fixed');
            }
            stickyContainer.width(stickyContainer.parent().width());
        });
    }
});