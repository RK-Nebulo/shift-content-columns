(function() {
    tinymce.create('tinymce.plugins.columns', {
        init : function(ed, url) {
            ed.addButton('columns', {
                title : 'Add Columns',
                onclick : function() {
                     ed.selection.setContent('[columns]<p>' + ed.selection.getContent() + '&nbsp;</p>[new-column]<p>&nbsp;</p>[/columns]<br>');
 
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('columns', tinymce.plugins.columns);
})();