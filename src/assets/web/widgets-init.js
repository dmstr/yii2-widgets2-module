// Add a resolver function to the beginning of the resolver list
// This will make it run before any other ones

JSONEditor.defaults.resolvers.unshift(function (schema) {
    if (schema.type === "string" && schema.format === "filefly") {
        return "http://placehold.it/320/200";
    }
    // If no valid editor is returned, the next resolver function will be used
});

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
                    '<img class="pull-left img-responsive" style="max-width: 100px; max-height: 70px" src="/filefly/api?action=download&path=' + (item.path) + '" />' +
                    '<span class="">' + escape(item.path) + '</span><br/>' +
                    //'<span class="badge">#' + escape(item.access_owner) + '</span>' +
                    '</div>';
            },
            option: function (item, escape) {
                return '<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2" style="height: 150px">' +
                    '<img class="img-responsive" style="max-height: 100px" src="/filefly/api?action=download&path=' + (item.path) + '" />' +
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
                    page_limit: 50
                },
                error: function (e) {
                    console.log(e);
                    alert('Your request could not be processed, see log for details.');
                },
                success: function (data) {
                    console.log('selectize: success');
                    console.log(data.result);
                    callback(data.result);
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

editor.on('ready', function () {
        // initialize CKeditor
        CKEDITOR.config.height = '400px';
        CKEDITOR.config.toolbar = [
            ['Format'], ['Link', 'Image', 'Table', '-', 'NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'], ['Source'],
            '/',
            ['Bold', 'Italic', 'Underline', 'StrikeThrough', '-', 'RemoveFormat', '-', 'Undo', 'Redo', '-', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Cut', 'Copy', 'Find', 'Replace', '-', 'Outdent', 'Indent', '-', 'Print']
        ];
        console.log('jsoneditor: ready');
        initSelectize();
    }
);

// replace/refresh CKeditor instances after change
// TODO: refresh filepicker
editor.on('change', function () {

    console.log('jsoneditor: change');

    $.each($('textarea[data-schemaformat="html"]'), function (key, obj) {

        if (!CKEDITOR.instances[$(obj).attr('name')]) {

            CKEDITOR.replace(obj);
            console.log('replaced ' + $(obj).attr('name'));

            CKEDITOR.instances[$(obj).attr('name')].on('change', function () {
                console.log(this.name);
                // TODO: if we have only one editor (root) we need to save - FIX ME (!!!)
                // Editors can not be updated; detect if current editor is missing (usually "blocks")
                if (Object.keys(editor.editors).length == 1) {
                    alert('Data structure has been changed, please save before continuing...');
                }

                this.updateElement();
                console.log('ckeditor change: ' + this.name);
                // TODO: it's workaround to update all editors
                for (var name in editor.editors) {
                    console.log(name);
                    editor.editors[name].refreshValue();
                    editor.editors[name].onChange(true);
                }
            });
        }
    });
    initSelectize();

    // TODO: workaround for ckeditor init after adding a new block
    $('.json-editor-btn-add').on('click', function () {
        editor.trigger('change');
    })

});


$(document).on('pjax:complete', function () {
    console.log('template: reload success');
    editor.trigger('change');
})

