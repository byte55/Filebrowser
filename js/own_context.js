var items = {};

function contextMenu($this,e)
{
	//e.preventDefault();
	//e.stopPropagation();
	var img  = false;
	var video = false;
	var audio = false;
	var ext = '';
	var type = '';
	var type2 = '';
	items = {};
	
	if($this.prop("tagName") == 'TR')
	{
		if(e.ctrlKey)
		{
			$this.addClass('selected');
		}
		else
		{
			$('#tablesort tbody tr').removeClass('selected');
			$this.addClass('selected');
		}
	}
	else
		$('#tablesort tbody tr').removeClass('selected');
	count = $('#viewer .selected').length;
	console.log(count);
	if(count == 0)
	{
		items['upload'] = {name: "Upload here", icon: "upload", callback: contextDelete};
		
		items['paste'] = {name: "Paste", icon: "paste", callback: contextDelete};
		items['new-file'] = {name: "New File", icon: "new-file", callback: contextDelete};
		items['new-folder'] = {name: "New Folder", icon: "new-folder", callback: contextDelete};
	}
	else if(count == 1)
	{
		tmp = getFileExtension($this.find('.name').html());
		if(typeof tmp == 'undefined')
			type = 'folder';
		else
		{
			ext = tmp[0];
			if(isImage(ext))
				type = 'image';
			else if(isVideo(ext))
				type = 'video';
			else if(isAudio(ext))
				type = 'audio';
			else
				type = 'other';
		}
		
		if(type == 'folder')
			items['downloadAsZip'] = {name: "Download Folder as ZIP", icon: "download", callback: contextDownloadAsZip};
		else
			items['download'] = {name: "Download File", icon: "download", callback: contextDownloadFile};
		if(type == 'image')
			items['edit'] = {name: "Edit", icon: "edit", callback: contextEditImage};
		items['copy'] = {name: "Copy", icon: "copy", callback: contextCopy};
		items['cut'] = {name: "Cut", icon: "cut", callback: contextCut};
		items['refresh'] = {name: "Refresh", icon: "refresh", callback: contextRefresh};
		items['delete'] = {name: "Delete", icon: "delete", callback: contextDelete};
	}
	else if(count > 1)
	{
		$('#tablesort tbody tr').each(function(){
			tmp = getFileExtension($(this).find('.name').html());
			if(typeof tmp == 'undefined')
				type2 = 'folder';
			else
			{
				ext = tmp[0];
				if(isImage(ext))
					type2 = 'image';
				else if(isVideo(ext))
					type2 = 'video';
				else if(isAudio(ext))
					type2 = 'audio';
				else
					type2 = 'other';
			}
			if(type == '')
				type = type2;
			else if(type != 'type2')
			{
				type = 'mixed';
				return false;
			}
		});
		
	}
}

function isImage(ext)
{
	switch(ext)
	{
		case "jpeg":
		case "jpg":
		case "png":
		case "bmp":
		case "gif":
			return true;
		default:
			return false;
	}
}

function isVideo(ext)
{
	switch(ext)
	{
		case "mov":
		case "avi":
		case "mk4":
		case "mp4":
		case "flv":
		case "wmv":
			return true;
		default:
			return false;
	}
}

function isAudio(ext)
{
	switch(ext)
	{
		case "mp3":
		case "wav":
		case "wma":
		case "3gp":
		case "ogg":
			return true;
		default:
			return false;
	}
}

function contextDownloadAsZip()
{
	alert("download as zip");
}

function contextDownloadFile()
{
	//alert("download file");
	file_path = current_dir + $('#viewer .selected').find('.name').text();
	//alert(file_path);
	downloadFile(file_path);
}

function contextCopy()
{
	alert("copy");
}
function contextCut()
{
	alert("cut");
}

function contextDelete()
{
	alert("delete");
}

function contextEditImage()
{
	file_path = current_dir + $('#viewer .selected').find('.name').text();
	$.post('ajax.php', {action: 'getImage', file_path: file_path},function(data){
		if(typeof data != 'object')
			data = $.parseJSON(data);		
		$('#image_editor img').attr('src',data.src);
		$('#image_editor').show();
	});
	
}

function contextRefresh()
{
	$('#explorer tr.selected').click();
}