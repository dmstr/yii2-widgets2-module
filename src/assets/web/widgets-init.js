// Add a resolver function to the beginning of the resolver list
// This will make it run before any other ones

JSONEditor.defaults.resolvers.unshift(function(schema) {
 if(schema.type === "string" && schema.format === "filefly") {
 return "http://placehold.it/320/200";
 }
 // If no valid editor is returned, the next resolver function will be used
 });

function initSelectize(){
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
            item : function (item, escape) {
                return '<div class="" style="height: 70px">' +
                    '<img class="pull-left img-responsive" style="max-width: 100px; max-height: 70px" src="/en/filefly/api?action=download&path=' + (item.path) + '" />' +
                    '<span class="">' + escape(item.path) + '</span><br/>' +
                    //'<span class="badge">#' + escape(item.access_owner) + '</span>' +
                    '</div>';
            },
            option: function (item, escape) {
                return '<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2" style="height: 150px">' +
                    '<img class="img-responsive" style="max-height: 100px" src="/en/filefly/api?action=download&path=' + (item.path) + '" />' +
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
                url: '/en/filefly/api',
                type: 'GET',
                dataType: 'json',
                data: {
                    action: 'search',
                    q: query,
                    page_limit: 50,
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

    $('input[type="filefly"]').on('change',function(){
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
            ['Styles', 'Format'], ['Link', 'Image', 'Table', '-', 'NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'], ['Source'],
            '/',
            ['Bold', 'Italic', 'Underline', 'StrikeThrough', '-', 'RemoveFormat', '-', 'Undo', 'Redo', '-', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Cut', 'Copy', 'Find', 'Replace', '-', 'Outdent', 'Indent', '-', 'Print']
        ];
        CKEDITOR.replaceAll();
        for (var i in CKEDITOR.instances) {
            CKEDITOR.instances[i].on('change', function () {
                this.updateElement()
                for (var name in editor.editors) {
                    console.log(name);
                    editor.editors[name].refreshValue();
                    editor.editors[name].onChange(true);
                }
            });
        }
        console.log('editor: ready');
        initSelectize();
    }
);

// replace/refresh CKeditor instances after change
// TODO: refresh filepicker
editor.on('change', function () {

    console.log('editor: change');

    $.each($('textarea'), function (key, obj) {
        // workaround: visible textareas have not been replaced yet
        if ($(obj).is(":visible")) {
            CKEDITOR.replace(obj);
        }
    });
    initSelectize();

    // TODO: workaround for ckeditor init after adding a new block
    $('.json-editor-btn-add').on('click', function () {
        editor.trigger('change');
    })

});


$(document).on('pjax:complete', function() {
    console.log('template: reload success');
    editor.trigger('change');
})

