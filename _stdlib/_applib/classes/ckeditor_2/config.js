/**
 * @license Copyright (c) 2003-2021, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	config.baseFloatZIndex = 1000005;
	config.filebrowserBrowseUrl 		= '_applib/classes/kcfinder/browse.php?opener=ckeditor&type=files';
	config.filebrowserImageUploadUrl 	= '_applib/classes/kcfinder/upload.php?opener=ckeditor&type=images';
	config.filebrowserImageBrowseUrl 	= '_applib/classes/kcfinder/browse.php?opener=ckeditor&type=images';

	config.contentsCss = 'http://localhost/sharpe-maths/_stdlib/_applib/classes/ckeditor/_style_CK.css';



	config.keystrokes = [
		[ CKEDITOR.ALT + 90 /*Z*/, 'source' ],
		[ CKEDITOR.CTRL + 81 /*Q*/, 'blockquote' ]
	];

};
