var initJSONEditorsPlugins = function () {

  // ------------------------------------------------------------- trigger event

  var trigger = function (event, element) {
    setTimeout(function () {
      var e = document.createEvent("HTMLEvents");
      e.initEvent(event, false, true);
      element.dispatchEvent(e);
    }, 0);
  };

  // --------------------------------------------------------------- to ckeditor

  var toCKEditor = function (input) {
    var instance = CKEDITOR.replace(input, window.CKCONFIG);
    instance.on('change', function () {
      this.updateElement();
      trigger('change', input);
    });
  };

  // -------------------------------------------------------------- to selectize

  var selectizeInstances = [];

  var toSelectize = function (input) {

    // build the image paths by moduleID (default: filefly => /filefly/api )
    var moduleId = input.getAttribute('module-id');
    if (typeof moduleId === 'undefined' || !moduleId) {
      console.log('no "module-id" attribute Found. Defaulting to "filefly"');
      moduleId = 'filefly';
    }
    var path = '/' + moduleId + '/api';

    var selectizeInstance = $(input).selectize({
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
    selectizeInstances.push(selectizeInstance[0].selectize);
  };

  // ------------------------------------------------------- init all selectizes

  var initSelectizes = function () {
    var selectizes = [].slice.call(document.querySelectorAll('[data-schemaformat="filefly"]'));
    selectizes.forEach(function (selectize) {
      toSelectize(selectize);
    });
  };

  // -------------------------------------------------------- init all ckeditors

  var initCKEditors = function () {
    var ckeditors = [].slice.call(document.querySelectorAll('[data-schemaformat="html"]'));
    ckeditors.forEach(function (ckeditor) {
      toCKEditor(ckeditor);
    });
  };

  // ----------------------------------------------------- get editor from input

  var getEditorFromInput = function (input) {
    var editor = null;
    var name = input.getAttribute('name');
    if (name) {
      var path = name.replace(/\[/g, '.').replace(/]/g, '');
      window.jsonEditors.forEach(function (jsonEditor) {
        editor = typeof jsonEditor.getEditor(path) !== 'undefined' ? jsonEditor.getEditor(path) : null;
      });
    }
    return editor;
  };

  // ---------------------------------------------------------- refresh ckeditor

  var refreshCKEditors = function () {
    for (var i in CKEDITOR.instances) {
      if (CKEDITOR.instances.hasOwnProperty(i)) {
        var ckinstance = CKEDITOR.instances[i];
        var input = ckinstance.element.$;
        var editor = getEditorFromInput(input);
        if (editor) {
          ckinstance.setData(editor.getValue());
        }
      }
    }
  };

  // -------------------------------------------------------- refresh selectizes

  var refreshSelectizes = function () {
    selectizeInstances.forEach(function (selectize) {
      selectize.destroy();
      selectizeInstances = [];
    });
    var selectizes = [].slice.call(document.querySelectorAll('[data-schemaformat="filefly"]'));
    selectizes.forEach(function (selectize) {
      toSelectize(selectize);
    });
  };

  // ----------------- refresh ckeditors and selectizes when moved into an array

  var onArrayItemMoved = function () {
    refreshCKEditors();
    refreshSelectizes();
  };

  // ----------------------- add events to delete, move up and move down buttons

  var arrayMovementButtons = [];

  var initArrayMovementEvents = function () {
    var moveUpButtons = [].slice.call(document.querySelectorAll('.json-editor-btn-moveup'));
    var moveDownButtons = [].slice.call(document.querySelectorAll('.json-editor-btn-movedown'));
    var deleteButtons = [].slice.call(document.querySelectorAll('.json-editor-btn-delete'));
    arrayMovementButtons = [].concat(moveUpButtons).concat(moveDownButtons).concat(deleteButtons);
    arrayMovementButtons.forEach(function (button) {
      button.addEventListener('click', onArrayItemMoved)
    });
  };

  // --------------------------------- init ckeditors and selectizes at creation

  window.jsonEditors.forEach(function (jsonEditor) {
    jsonEditor.theme.afterInputReady = function (input) {
      var dataAttribute = input.getAttribute('data-schemaformat');
      switch(dataAttribute) {
        case 'html':
          toCKEditor(input);
          break;
        case 'filefly':
          toSelectize(input);
          break;
      }
    };

    // --------------- init events to array movement buttons when editor changes

    jsonEditor.on('change', function () {
      initArrayMovementEvents();
    });
  });

  initSelectizes();
  initCKEditors();
  initArrayMovementEvents();

};

// --------------------------------------- init JSONEditors Plugins on page load

window.addEventListener('load', function () {
  initJSONEditorsPlugins()
});

// --------------------- init JSONEditors Plugins when switching widget template

$(document).on('pjax:complete', function () {
  initJSONEditorsPlugins()
});