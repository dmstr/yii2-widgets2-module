$(document).on('ready cookieUpdate',function () {

   // TODO: check value
   if ($.cookie('app-frontend-view-mode')) {
      console.log($.cookie('app-frontend-view-mode'));
      $('.hrzg-widget-container-controls, .hrzg-widget-widget-controls, .hrzg-widget-widget-invisible-frontend, .hrzg-widget-widget-info').hide();
      $('.hrzg-widget-widget, .hrzg-widget-widget-container').css('outline', '0px');
   } else {
      $('.hrzg-widget-container-controls, .hrzg-widget-widget-controls, .hrzg-widget-widget-invisible-frontend, .hrzg-widget-widget-info').show();
      $('.hrzg-widget-widget, .hrzg-widget-widget-container').css('outline', '');
   }

});
