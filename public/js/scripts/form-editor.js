/*=========================================================================================
	File Name: editor-quill.js
	Description: Quill is a modern rich text editor built for compatibility and extensibility.
	----------------------------------------------------------------------------------------
	Item Name: Materialize Admin Template
	Version: 1.0
	Author: GeeksLabs
	Author URL: http://www.themeforest.net/user/geekslabs
==========================================================================================*/
(function (window, document, $) {
  'use strict';

  var Font = Quill.import('formats/font');
  Font.whitelist = ['sofia', 'slabo', 'roboto', 'inconsolata', 'ubuntu'];
  Quill.register(Font, true);

  // Full Editor

  var fullEditor = new Quill('#full-container .editor', {
    bounds: '#full-container .editor',
    modules: {
      'formula': true,
      'syntax': true,
      'toolbar': [
        [{
          'font': []
        }, {
          'size': []
        }],
        ['bold', 'italic', 'underline', 'strike'],
        [{
          'color': []
        }, {
          'background': []
        }],
        [{
          'script': 'super'
        }, {
          'script': 'sub'
        }],
        [{
          'header': '1'
        }, {
          'header': '2'
        }, 'blockquote', 'code-block'],
        [{
          'list': 'ordered'
        }, {
          'list': 'bullet'
        }, {
          'indent': '-1'
        }, {
          'indent': '+1'
        }],
        ['direction', {
          'align': []
        }],
        ['link', 'image', 'video', 'formula'],
        ['clean']
      ],
    },
    theme: 'snow'
  });
  // add browser default class to quill select 
  var quillSelect = $("select[class^='ql-'], input[data-link]" );
  quillSelect.addClass("browser-default");

  var editors = [fullEditor];

})(window, document, jQuery);
