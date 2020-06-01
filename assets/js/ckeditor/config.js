/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function (config) {
	config.toolbar = [
		['Maximize', 'Source'],
		['NumberedList', 'BulletedList'],
		['Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript', 'Superscript', 'RemoveFormat'],
		['Paste', 'PasteText', 'Image', 'Table', 'HorizontalRule'],
		['Link', 'Unlink'],
		['Find', 'Replace', '-', 'Undo', 'Redo'],
	];
};