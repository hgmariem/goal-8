CKEDITOR.editorConfig = function( config ) {
	

	config.toolbarGroups = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'about', groups: [ 'about' ] }
	];

	config.removeButtons = 'Source,Save,Templates,Cut,Undo,Find,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Subscript,Superscript,CopyFormatting,RemoveFormat,Blockquote,CreateDiv,BidiLtr,BidiRtl,Language,Link,Unlink,Anchor,Image,Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,Maximize,About,ShowBlocks,NewPage,Preview,Print,Copy,Paste,PasteText,PasteFromWord,Redo,Replace';
	//config.extraPlugins = 'autogrow,pastefromgdocs';
	config.removePlugins = 'elementspath,magicline';
	config.enterMode = CKEDITOR.ENTER_P;
	config.autoParagraph = false;
	config.fontSize_defaultLabel = '16px';
	config.font_defaultLabel = 'Arial';
	config.extraAllowedContent = 'dl dt dd';
	config.hidpi = true;
	//config.fillEmptyBlocks = false;
	//config.extraPlugins = 'indent';
	


};
