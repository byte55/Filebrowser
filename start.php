<script type="text/javascript">
  $(function() {
	resizeBrwoser()	;
	$(window).resize(function(){
		resizeBrwoser()	;
	});
  });
</script>
<div class="head">
FileBrowser
</div>

  <div id="explorer">
    <div id="dirs">
	<?
	$sql = "SELECT `folder` FROM `users_root_folders` WHERE `users_id` = ".$db->escape($_SESSION['user']['id']);
	$result = $db->query($sql);
	if($db->num($result) > 0)
	{
		while($dir = $db->assoc($result))
		{
			#$dir = basename();
			$subdirs = hasSubDirs(ROOT.$dir['folder']);
			$name = basename($dir['folder'],'/');
			?>
			<div class="root dir <?if(!$subdirs) echo 'empty';?>" title="<?=$name?>">
			  <div class="select">
			    <?if($subdirs === true) echo '<img class="icn" src="'.$imgs['controls']['arrow_right'].'" alt="" />';?>
			    <img class="icn" src="<?=$imgs['controls']['folder_open']?>" alt="" />&nbsp;
			    <?=$name?>
			    <br class="cb" />
			  </div>
			</div>
			<?
		}
	}
	else echo 'no folders found';
	?>
	</div>
  </div>
  <div id="viewer">Select a folder on the right side</div>
  <br class="cb" />
  <script type="text/javascript">
    var current_dir = '';
	var file_content_org = '';
	var editor;
	var file_path = '';
    $(function(){
	    window.onbeforeunload = function() {
			if(file_content_org != editor.getValue())
			{
				console.log(file_content_org);
				console.log(editor.getValue());
				return "You have unsaved changes in your current file. Do you want to leave/refresh the page?^\n\n" + editor.getValue() + "\n\n\n\n" + file_content_org;
			}
		}
		editor = ace.edit("editor");
		editor.setTheme("ace/theme/chrome");
		editor.setValue('');
		editor.commands.addCommand({
			name: 'saveFile',
			bindKey: {win: 'Ctrl-S',  mac: 'Command-S'},
			exec: function(editor) {
				if(editor.getReadOnly())
					alert('File is readonly');
				else
				{
					$.post('ajax.php',{file_path : file_path, data: editor.getValue(), action : 'saveContent'},function(data){
						if(typeof data != 'object')
							data = $.parseJSON(data);
						if(data.error == '')
						{
							file_content_org = editor.getValue();
							alert_glow('save');
						}
						else
							alert(data.error);
					});
				}
			},
			readOnly: true // false if this command should not apply in readOnly mode
		});
		editor.commands.addCommand({
			name: 'closeFile',
			bindKey: {win: 'Esc',  mac: 'Esc'},
			exec: function(editor) {
				if(file_content_org != editor.getValue())
				{
					$("<div>Do you want to save your changes?</div>").dialog({
					  resizable: false,
					  height:140,
					  title: 'Unsafed changes',
					  modal: true,
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
							$('#editor').hide();
						},
						"No": function() {
							$( this ).dialog( "close" );
							editor.setValue('');
							file_content_org = '';
							$('#editor').hide();
						},
						Cancel: function() {
						  $( this ).dialog( "close" );
						}
					  }
					});
				}
				else
				{
					editor.setValue('');
					file_content_org = '';
					$('#editor').hide();
				}
			},
			readOnly: true // false if this command should not apply in readOnly mode
		});
		$(document).on('click', '#explorer .dir', function(event){
			folder = '';
		    event.stopPropagation();
			var $div = $(this);
			cur = $div.attr('title') + '/';
			//alert(cur);
		    $('div').removeClass('selected');
			$div.find('div:gt(0)').remove();
			$div.find(">:first-child").addClass('selected');
			$div.parents().each(function(){
			    if($(this).hasClass('dir'))
				{
					folder = $(this).attr('title') + '/' + folder;
				}
			});
			folder += cur;
			current_dir = folder;
			//alert(folder);
			
			$.post('ajax.php',{folder : folder, action : 'getSubDirs'},function(data){
			    if(typeof data != 'object')
					data = $.parseJSON(data);
				$div.append(data.html);
			});
			$.post('ajax.php',{folder : folder, action : 'getFiles'},function(data){
			    if(typeof data != 'object')
					data = $.parseJSON(data);
				$('#viewer').html(data.html);
				$('#viewer table').length;
				$('#tablesort').tablesorter({
					//sortForce: [[3,0]],
					sortList: [[3,0],[0,0]] 
				}).bind("sortStart",function(e,ui){
					//console.log($(e.target).find('.headerSortUp'));
				});
				$('#tablesort tbody tr').click(function(e){
					e.preventDefault();
					if(e.ctrlKey)
					{
						$(this).addClass('selected');
					}
					else
					{
						$('#tablesort tbody tr').removeClass('selected');
						$(this).addClass('selected');
					}
					
				});
			});
		});
		
		$(document).on('dblclick', '#tablesort .dir', function(e){
			e.preventDefault();
			//alert(current_dir);
			folder = $(this).find('.name').text();
			current_dir = current_dir + folder + '/';
			tmp = current_dir.split('/');
			console.log(tmp);
			search = '';
			$.each(tmp,function(index, value){
				if(value != '')
					search += 'div[title="' + value + '"] ';
			});
			$(search).trigger('click');
			
		});
		$(document).on('dblclick', '#tablesort .file', function(e){
			file_content_org = '';
			editor.setValue('');
			e.preventDefault();
			file = $(this).find('.name').text();
			file_path = current_dir + file;
			//alert(file);
			tmp = getFileExtension(file);
			ext = tmp[0]
			//alert(typeof ext);
			//console.log(ext);
			hl = "";
			extern = '';
			switch(ext)
			{
				case "php":
				  hl = "php";
				  break;
				case "html":
				case "htm":
				case "xhtml":
				case "stm":
				case "mhtml":
				  hl = "html";
				  break;
				case "cmd":
				case "bat":
					hl = "batchfile";
					break;
				case "js":
					hl = "javascript";
					break;
				case "lua":
					hl = "lua";
					break;
				case "json":
					hl = "json";
					break;
				case "txt":
					hl = "text";
					break;
				case "ini":
				  hl = "ini";
				  break;
				case "zip":
				case "rar":
					extern = 'archive';
					break;
				default:
				  hl = "na"; // not avaiable
			}
			if(extern == '')
			{
				if(hl != 'na')
				{
					editor.getSession().setMode("ace/mode/" + hl);
					$('#loader').show();
					$.post('ajax.php',{file_path : file_path, action : 'getContent'},function(data){
						if(data != 'File not found' && data != 'File could not be read')
						{
							editor.setValue(data);
							editor.navigateFileStart();
							editor.focus();
							file_content_org = editor.getValue();
							$('#editor').show();
						}
						else
						{
							$('#loader').hide();
							alert(data);
						}
							
					},'html');
					$('#loader').hide();
				}
				else 
				{
					$("<div>There is no valid viewer for this file. What do you want to do?</div>").dialog({
					  resizable: false,
					  title: 'No valid viewer',
					  modal: true,
					  width: "380px",
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
				}
			}
			else
			{
				if(extern == 'archive')
				{
					$('#dest input').val(file.slice(0,(ext.length + 1) * -1));
					$('#dest').dialog({
					  resizable: false,
					  title: 'Extract file',
					  modal: true,
					  width: "380px",
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
						Cancel: function() {
						  $( this ).dialog( "close" );
						}
					  }
					});
				}
				else if(extern == 'video')
				{
					
				}
			}
		});
		$(document).on('click', '#editor input[value="Save"]', function(e){
			alert('save');
		});
		$(document).on('click', '#editor input[value="Close"]', function(e){
			$('#editor').hide();
		});
		
	});
  </script>
	