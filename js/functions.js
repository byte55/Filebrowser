window.alert_glow = function(text,type){
	type = type || 'ok';
	if($('#alert_glow').length == 0) $('body').append('<div id="alert_glow" class="alert_glow"></div>');
	$('#alert_glow').removeClass('ok').removeClass('error');
	$('#alert_glow').addClass(type);
	$('#alert_glow').html(text);
	$('#alert_glow').fadeIn(1000).delay(500).fadeOut(1000);
};

function resizeBrwoser()
{
	$('#viewer').css('width', $(window).width() - $('#explorer').width() - 7 + 'px');
	$("#explorer").resizable({ handles: "e" , minWidth: 100,maxWidth: $(window).width() - 625, resize: function(event, ui){
		left_width = $('#explorer').width();
		$('#viewer').css('width', $(window).width() - $('#explorer').width() - 7 + 'px');
	}});
}

function getFileExtension(file)
{
	return (/[.]/.exec(file)) ? /[^.]+$/.exec(file) : undefined;
}

function getMD5(file_path)
{
	$.post('ajax.php',{file_path: file_path, action : 'getMD5'},function(data){
		if(typeof data != 'object')
			data = $.parseJSON(data);
		return data.md5;
	});	
}

function refreshAll()
{
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
}

function downloadFile(file_path)
{
	$('#download_file iframe').attr('src','download.php');
	$('#download_file iframe').contents().find('.file_path').attr('value',file_path);
	$('#download_file iframe').contents().find('form').submit();
}

function saveEditor(file_path,value)
{
	$.post('ajax.php',{file_path : file_path, data: value, action : 'saveContent'},function(data){
		if(typeof data != 'object')
			data = $.parseJSON(data);
		if(data.error == '')
		{
			alert_glow('save');
			return true;
		}
		else
		{
			error = data.error;
			return false;
		}
	});
}

function closeImageEditor(e)
{
	e.preventDefault();
	if($('#image_editor').is(':visible') && e.keyCode == '27')
		$('#image_editor').hide();
}

function showDialog(id)
{
	$('#'+id).dialog('open');
}

function applyFilter(filter)
{
	if($("#image_editor img").length > 0)
	{
		Caman("#image_editor img", function () {
			// If such an effect exists, use it:
			if( filter in this){
				//NOTE THIS IS THE LINE THAT I ADD FOR YOU:
				this.revert();
				this[filter]();
				this.render();
			}
			else 
				alert('mist:' + filter);
		});
	}
	else if($("#image_editor canvas").length > 0)
	{
		Caman("#image_editor canvas", function () {
			// If such an effect exists, use it:
			if( filter in this){
				//NOTE THIS IS THE LINE THAT I ADD FOR YOU:
				this.revert();
				this[filter]();
				this.render();
			}
			else 
				alert('mist:' + filter);
		});
	}
}