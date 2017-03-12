function initSelectize() {
    // create filepicker, with api endpoint search
    // TODO: cleanup & refactoring
    console.log('selectize: init');
    $('input[type="filefly"]').selectize({
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
                    '<img class="pull-left img-responsive" style="max-width: 100px; max-height: 70px" src="/filefly/api?action=stream&path=' + (item.path) + '" />' +
                    '<span class="">' + escape(item.path) + '</span><br/>' +
                    //'<span class="badge">#' + escape(item.access_owner) + '</span>' +
                    '</div>';
            },
            option: function (item, escape) {
                return '<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2" style="height: 150px">' +
                    '<img class="img-responsive" style="max-height: 100px" src="/filefly/api?action=stream&path=' + (item.path) + '" />' +
                    '<span class="">' + escape(item.path) + '</span>' +
                    //'<span class="badge">#' + escape(item.access_owner) + '</span>' +
                    '</div>';
            }
        },
        load: function (query, callback) {
            console.log('selectize: load (filefly API)');
            console.log(query);
            //if (!query.length) return callback();
            $.ajax({
                url: '/filefly/api',
                type: 'GET',
                dataType: 'json',
                data: {
                    action: 'search',
                    q: query,
                    page_limit: 20
                },
                error: function (e) {
                    console.log(e);
                    alert('Your request could not be processed, see log for details.');
                },
                success: function (data) {
                    console.log('selectize: success');
                    console.log(data);
                    callback(data);
                }
            });
        }
    });

    $('input[type="filefly"]').on('change', function () {
        console.log('selectize: filefly change');
        for (var name in editor.editors) {
            console.log(name);
            editor.editors[name].refreshValue();
            editor.editors[name].onChange(true);
        }
    });
}

function updateEditors() {
    // TODO: it's workaround to update all editors
    for (var name in editor.editors) {
        editor.editors[name].refreshValue();
        editor.editors[name].onChange(true);
    }
    console.log('jsoneditor: updated all editors');
}

// JSONeditor

// initialize CKeditors
editor.theme.afterInputReady = function (input) {
    if ($(input).prop('tagName') == 'TEXTAREA' && $(input).attr('data-schemaformat') == 'html') {
        console.log('input ready', $(input).prop('tagName'), input);

        CKEDITOR.replace(input);

        CKEDITOR.instances[$(input).prop('name')].on('change', function () {
            this.updateElement();
            updateEditors();
        });
    }
};

editor.on('ready', function () {
        // initialize CKeditor
        CKEDITOR.config.height = '400px';
        CKEDITOR.config.toolbar = [
            ['Format'], ['Link', 'Image', 'Table', '-', 'NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'], ['Source'],
            '/',
            ['Bold', 'Italic', 'Underline', 'StrikeThrough', '-', 'RemoveFormat', '-', 'Undo', 'Redo', '-', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Cut', 'Copy', 'Find', 'Replace', '-', 'Outdent', 'Indent', '-', 'Print']
        ];

        initSelectize();

        console.log('jsoneditor: ready');
    }
);

// TODO: notice is a workaround for broken editor display on move, delete
// TODO: replace/refresh CKeditor instances after change
var notice = false;

editor.on('change', function () {

    console.log('jsoneditor: change', $('textarea[data-schemaformat="html"]'));

    initSelectize();

    // TODO: workaround for ckeditor init after adding a new block
    $('.json-editor-btn-add').on('click', function () {
        editor.trigger('change');
    });

    // TODO: workaround for broken ckeditor after move/delete
    $('.json-editor-btn-delete, .json-editor-btn-movedown, .json-editor-btn-moveup').on('click', function () {
        for (name in CKEDITOR.instances) {
            if (!notice) {
                alert('NOTICE: Due to data updates, HTML editors will be disabled until changes have been saved.')
                notice = true
            }
            CKEDITOR.instances[name].setReadOnly(true);
            console.log('TODO', 'ckeditor: disabled', name);
        }
    })

});


$(document).on('pjax:complete', function () {
    console.log('template: reload success');
    editor.trigger('change');
});
