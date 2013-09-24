/**
Unsaved Changes
**/
$(function(){
	$("#saveChanges").dialog({
	  resizable: false,
	  height:140,
	  title: 'Unsafed changes',
	  modal: true,
	  autoOpen:false,
	  buttons: {
		"Yes": function() {
			$.post('ajax.php',{file_path : file_path, data: editor.getValue(), action : 'saveContent'},function(data){
				if(typeof data != 'object')
					data = $.parseJSON(data);
				if(data.error == '')
					alert_glow('save');
				else
					alert(data.error);
			});
			$( this ).dialog( "close" );
			editor.setValue('');
			file_content_org = '';
			file_md5_org = '';
			$('#editor').hide();
		},
		"No": function() {
			$( this ).dialog( "close" );
			editor.setValue('');
			file_content_org = '';
			file_md5_org  = '';
			$('#editor').hide();
		},
		Cancel: function() {
		  $( this ).dialog( "close" );
		}
	  }
	});
	
	$("#noValidViewer").dialog({
	  resizable: false,
	  title: 'No valid viewer',
	  modal: true,
	  width: "380px",
	  autoOpen: false,
	  buttons: {
		"Open as Text-File": function() {
			editor.getSession().setMode("ace/mode/plain_text");
			$('#loader').show();
			$.post('ajax.php',{file_path : file_path, action : 'getContent'},function(data){
				if(data != 'File not found' && data != 'File could not be read')
				{
					editor.setValue(data);
					editor.navigateFileStart();
					editor.focus();
					file_content_org = editor.getValue();
					$('#loader').hide();
					$('#editor').show();
				}
				else
				{
					$('#loader').hide();
					alert(data);
				}
			},'html');
			$( this ).dialog( "close" );
		},
		"Donwload file": function() {
			downloadFile(file_path);
			$( this ).dialog( "close" );
		},
		Cancel: function() {
		  $( this ).dialog( "close" );
		}
	  }
	});
	
	$('#extractArchiv').dialog({
	  resizable: false,
	  title: 'Extract file',
	  modal: true,
	  width: "380px",
	  autoOpen:false,
	  buttons: {
		"Extract": function() {
			$('#loader').show();
			$.post('ajax.php',{folder: current_dir, file : file_path, dest: $('#dest input').val(), ext: ext, action : 'extractFile'},function(data){
				if(typeof data != 'object')
					data = $.parseJSON(data);
				if(data.error == '')
				{
					$('#explorer .selected').trigger('click');
					$('#loader').hide();
				}
				else
				{
					$('#loader').hide();
					alert(data.error);
				}
				
			},'json');
			$( this ).dialog( "close" );
		},
		"Download": function(){
			downloadFile(file_path);
		},
		Cancel: function() {
		  $( this ).dialog( "close" );
		}
	  }
	});
});