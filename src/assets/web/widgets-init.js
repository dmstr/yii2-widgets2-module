window.addEventListener('load', function () {

  var trigger = function (event, element) {
    setTimeout(function () {
      var e = document.createEvent("HTMLEvents");
      e.initEvent(event, false, true);
      element.dispatchEvent(e);
    }, 0);
  };

  var initCKEditor = function (input) {
    // CKCONFIG is defined in crud/WidgetController
    var instance = CKEDITOR.replace(input, window.CKCONFIG);
    instance.on('change', function () {
      this.updateElement();
      trigger('change', input);
    });
  };

  var initSelectizeEditor = function (input) {

    // build the image paths by moduleID (default: filefly => /filefly/api )
    var moduleId = input.getAttribute('module-id');
    if (typeof moduleId === 'undefined' || !moduleId) {
      console.log('no "module-id" attribute Found. Defaulting to "filefly"');
      moduleId = 'filefly';
    }
    var path = '/' + moduleId + '/api';

    $(input).selectize({
      valueField: 'path',
      labelField: 'path',
      searchField: 'path',
      placeholder: 'Select a file...',
      maxItems: 1,
      preload: true,
      options: [],
      create: false,
      render: {
        item: function (item, escape) {
          return '<div class="" style="height: 70px">' +
            '<img class="pull-left img-responsive" style="max-width: 100px; max-height: 70px" src="' + path + '?action=stream&path=' + (item.path) + '" />' +
            '<span class="">' + escape(item.path) + '</span><br/>' +
            '</div>';
        },
        option: function (item, escape) {
          return '<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2" style="height: 150px">' +
            '<img class="img-responsive" style="max-height: 100px" src="' + path + '?action=stream&path=' + (item.path) + '" />' +
            '<span class="">' + escape(item.path) + '</span>' +
            '</div>';
        }
      },
      load: function (query, callback) {
        $.ajax({
          url: path,
          type: 'GET',
          dataType: 'json',
          data: {
            action: 'search',
            q: query,
            page_limit: 20
          },
          error: function (e) {
            console.log(e);
          },
          success: function (data) {
            callback(data);
          }
        });
      },
      onItemAdd: function () {
        trigger('change', input);
      },
      onItemRemove: function () {
        trigger('change', input);
      }
    });
  };

  // init editors when the page is loaded.
  var selectizeEditors = [].slice.call(document.querySelectorAll('[data-schemaformat="filefly"]'));
  selectizeEditors.forEach(function (input) {
    initSelectizeEditor(input);
  });

  var CKEditors = [].slice.call(document.querySelectorAll('[data-schemaformat="html"]'));
  CKEditors.forEach(function (input) {
    initCKEditor(input);
  });

  // Init editors as they are added.
  window.jsonEditors.forEach(function (jsonEditor) {
    jsonEditor.theme.afterInputReady = function (input) {
      var dataAttribute = input.getAttribute('data-schemaformat');
      switch(dataAttribute) {
        case 'html':
          initCKEditor(input);
          break;
        case 'filefly':
          initSelectizeEditor(input);
          break;
      }
    };
  });

});