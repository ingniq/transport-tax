(function( $ ) {
	'use strict';

  tinymce.PluginManager.add('transport_tax_mce_button', function( editor, url ) {
    editor.addButton('transport_tax_mce_button', {
      title: 'Форма расчета налога на транспорт',
      icon: 'transport-tax-icon',
      onclick: function() {
        editor.insertContent('[trans_tax]');
      }
    });
  });
  
})( jQuery );
