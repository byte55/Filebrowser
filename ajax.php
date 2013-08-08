<?php
error_reporting(-1 ^ E_NOTICE);
ini_set("display_errors",1);
$page = explode("_",$_GET['page']);
session_set_cookie_params(time()+60*60*24*30);
session_name('file');
session_start();
include "inc/functions.php";
if(!empty($_POST)) array_walk_recursive($_POST,'trim_post');
include "inc/c_locale.php";
include "inc/config.php";
include "inc/c_db.php";
$db = new db($db_location,$db_user,$db_password,$db_name);

if(!empty($_SESSION['user'])) $logged = true;
$json = array();
$json['error'] = '';
if($_POST['action'] == 'getFiles') // for viewer
{
	$files = array();
	$dir = ROOT.$_POST['folder'];
	if ($handle = opendir($dir))
	{
		while (false !== ($file = readdir($handle))) {
		    if($file != '.' and $file != '..')
			{
				if(filetype($dir.$file) == 'dir')
					$files['dir'][] = $file;
				else if(filetype($dir.$file) == 'file')
					$files['file'][] = $file;
			}
		}
	}
	closedir($handle);
	if(!empty($files))
	{
		$json['html'] = '<table id="tablesort" class="tablesorter" style="width:100%">';
		$json['html'] .= '<thead>';
		$json['html'] .= '<tr>';
		$json['html'] .= '<th style="width:70%">Name</th>';
		$json['html'] .= '<th style="width:10%">Size</th>';
		$json['html'] .= '<th style="width:20%">last change</th>';
		$json['html'] .= '<th class="hide"></th>';
		$json['html'] .= '</tr>';
		$json['html'] .= '</thead>';
		$json['html'] .= '<tbody class="scrollTable">';
		if(!empty($files['dir'])) 
		{
			natsort($files['dir']);
			
			foreach($files['dir'] as $file)
			{
				$json['html'] .= '<tr class="dir">';
				$json['html'] .= '<td><img class="icn" src="'.$imgs['controls']['folder_open'].'" alt="" /><span class="name">'.$file.'</span></td>';  // Name
				$json['html'] .= '<td></td>';  // Size
				$json['html'] .= '<td>'.date('d.m.Y H:i',filemtime($dir.$file)).'</td>';  // change date
				$json['html'] .= '<td class="hide">dir</td>';  // hidden type for sorting
				$json['html'] .= '</tr>';
			}
		}
		if(!empty($files['file']))
		{
			natsort($files['file']);
			foreach($files['file'] as $file)
			{
				$ext = pathinfo($file, PATHINFO_EXTENSION);
				if(file_exists('img/icn/file_extension_'.$ext.'.png'))
					$ext =     'img/icn/file_extension_'.$ext.'.png';
				else 
					$ext = 'img/icn/document_empty.png';
				$json['html'] .= '<tr class="file">';
				$json['html'] .= '<td><img class="icn" src="'.$ext.'" alt="" /><span class="name">'.$file.'</span></td>';  // Name
				$json['html'] .= '<td>'.bytesToSize(filesize($dir.$file)).'</td>';  // Size
				$json['html'] .= '<td>'.date('d.m.Y H:i',filemtime($dir.$file)).'</td>';  // change date
				$json['html'] .= '<td class="hide">file</td>';  // hidden type for sorting
				$json['html'] .= '</tr>';
			}
		}
		$json['html'] .= '</tbody>';
		$json['html'] .= '</table>';
	}
	else $json['html'] .= 'No files in here';
}
else if($_POST['action'] == 'getSubDirs') // for explorer
{
	$files = array();
	$dir = ROOT.$_POST['folder'];
	if ($handle = opendir($dir))
	{
		$i = 0;
		while (false !== ($file = readdir($handle))) {
		    if($file != '.' and $file != '..')
			{
				if(filetype($dir.$file) == 'dir')
				{
					#$files[$i]['name'] = $file;
					#$files[$i]['subs'] = hasSubDirs($dir.$file."\\");
					#$i++;
					$empty = hasSubDirs($dir.$file) ? '' : 'empty';
					$subdirs = hasSubDirs($dir.$file) ? '<img class="icn" src="'.$imgs['controls']['arrow_right'].'" alt="" />' : '';
					$json['html'] .= '
						<div class="dir '.$empty.'" title="'.$file.'">
						  <div>
						    '.$subdirs.'
						    <img class="icn" src="'.$imgs['controls']['folder_open'].'" alt="" />&nbsp;
						    '.$file.'
						    <br class="cb" />
						  </div>
						</div>
					';
				}
			}
		}
	}
	closedir($handle);
	#natsort($files['dir']);
	
}
else if($_POST['action'] == 'extractFile') // for explorer
{
	if(file_exists(ROOT.$_POST['file']))
	{
		if($_POST['ext'] == 'zip')
		{
			$zip = new ZipArchive;
			if ($zip->open(ROOT.$_POST['file']) === TRUE) {
				$zip->extractTo(ROOT.$_POST['folder'].$_POST['dest']);
				$zip->close();
			} else {
				$json['error'] = 'File could not be open';
			}
		}
	}
	else
		$json['error'] = 'File not found';

}
else if($_POST['action'] == 'saveContent')
{
	if(file_exists(ROOT.$_POST['file_path']))
	{
		$content = file_put_contents(ROOT.$_POST['file_path'],$_POST['data']);
		if($content === false)
			$json['error'] =  'File could not be saved';
	}
	else
		echo 'File not found';
}
else if($_POST['action'] == 'getContent')
{
	unset($json);
	header('Content-Type: text/plain');
	if(file_exists(ROOT.$_POST['file_path']))
	{
		$content = file_get_contents(ROOT.$_POST['file_path']);
		if($content === false)
			echo 'File could not be read';
		else
			echo $content;
	}
	else
		echo 'File not found';
}
if($json)
	echo json_encode($json);
?>
