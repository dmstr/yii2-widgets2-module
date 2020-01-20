$(document).on('ready cookieUpdate',function () {

   // TODO: check value
   if ($.cookie('app-frontend-view-mode')) {
      $('.hrzg-widget-container-controls, .hrzg-widget-widget-controls, .hrzg-widget-widget-invisible-frontend, .hrzg-widget-widget-info').hide();
      $('.hrzg-widget-widget, .hrzg-widget-widget-container').css('outline', '0px');
   } else {
      $('.hrzg-widget-container-controls, .hrzg-widget-widget-controls, .hrzg-widget-widget-invisible-frontend, .hrzg-widget-widget-info').show();
      $('.hrzg-widget-widget, .hrzg-widget-widget-container').css('outline', '');
   }

});

var handleSelector = "[data-sortable='vertical']";
document.querySelectorAll("[data-widget='container']").forEach(function (el) {
   new Sortable(el, {
      handle: handleSelector,
      animation: 150,
      group: "hrzg-widgets",
      draggable: '.hrzg-widget-widget',
      onEnd: function (event) {
         var containerId = event.to.id;
         var childItems = Array.prototype.slice.call(event.to.children).filter(function (child) {
            return child.className.indexOf('hrzg-widget-widget') > -1;
         });


         var orderedWidgetData = [];
         childItems.forEach(function (item) {
            orderedWidgetData.push({
               widgetId: item.dataset.widgetId,
               requestParam: item.dataset.requestParam,
               route: item.dataset.route
            })
         });

         var url = location.origin + '/de/widgets/default/re-order';

         sortableHandleState(true);
         fetch(url, {
            method: 'POST',
            headers: {
               'Content-Type': 'application/json'
            },
            body: JSON.stringify({
               containerId: containerId.replace('cell-', ''),
               orderedWidgetData: orderedWidgetData
            })
         }).then(function (response) {
            if (!response.ok) {
               throw Error (response.statusText)
            }
         }).catch(function (error) {
            console.error(error)
         }).finally(function () {
            sortableHandleState(false)
         })
      },
   });
});

function sortableHandleState(disabled) {
   document.querySelectorAll(handleSelector).forEach(function (el) {
      el.disabled = disabled
   })
}
