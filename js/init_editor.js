$(function(){
	var editor = ace.edit("editor");
	editor.setTheme("ace/theme/chrome");
	editor.commands.addCommand({
		name: 'saveFile',
		bindKey: {win: 'Ctrl-S',  mac: 'Command-S'},
		exec: function(editor) {
			alert('save File');
		},
		readOnly: true // false if this command should not apply in readOnly mode
	});
	editor.commands.addCommand({
		name: 'closeFile',
		bindKey: {win: 'Esc',  mac: 'Esc'},
		exec: function(editor) {
			alert('close File');
		},
		readOnly: true // false if this command should not apply in readOnly mode
	});
	editor.commands.addCommand({
		name: 'redo',
		bindKey: {win: 'Ctrl-R',  mac: 'Command-R'},
		exec: function(editor) {
			alert('redo');
		},
		readOnly: true // false if this command should not apply in readOnly mode
	});
});