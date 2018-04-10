$(document).on('ready cookieUpdate',function () {

   // TODO: check value
   if ($.cookie('hrzg-widget_view-mode')) {
      $('.hrzg-widget-container-controls, .hrzg-widget-widget-controls, .hrzg-widget-widget-invisible-frontend').hide();
      $('.hrzg-widget-widget, .hrzg-widget-widget-container').css('outline', '0px');
   } else {
      $('.hrzg-widget-container-controls, .hrzg-widget-widget-controls, .hrzg-widget-widget-invisible-frontend').show();
      $('.hrzg-widget-widget, .hrzg-widget-widget-container').css('outline', '');
   }

});
