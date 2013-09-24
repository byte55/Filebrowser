<?php
date_default_timezone_set('Europe/Berlin');
setlocale(LC_TIME,'de_DE.utf8');
define("ROOT_DIR",pathinfo($_SERVER['SCRIPT_FILENAME'],PATHINFO_DIRNAME)."/files/");

$website_basedir = 'http://mcgee/work/cine/';
$db_location = 'localhost';
$db_user = 'root';
$db_password = 'webio87!';
$db_name = 'projekte_theatre';


if($_SERVER["HTTP_HOST"] == "mcgee" or $_SERVER["HTTP_HOST"] == "work.walter-it.de")
{
	$db_location = 'localhost';
	$db_user = 'root';
	$db_password = 'webio87!';
	$db_name = 'projekte_file';
	$mail_mysql_error = 'mysql@walter-it.de';
	$website_basedir = 'http://'.$_SERVER['HTTP_HOST'].'/work/Filebrowser/';
	if($_SERVER["HTTP_HOST"] == "work.walter-it.de")
		$website_basedir = 'http://'.$_SERVER['HTTP_HOST'].'/Filebrowser/';
}
else
{
	$db_user = 'root';
	$db_password = '';
	$db_location = 'localhost';
	$db_name = 'projekte_file';
	$mail_mysql_error = 'mysql@walter-it.de';
	$website_basedir = 'http://'.$_SERVER['HTTP_HOST'].'/Filebrowser/';
}


$langs[] = 'de';
$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
if (!in_array($lang, array_keys($langs))) $lang = 'en';
$l = new locale($lang);

$icn_dir = 'img/icn/';
define("ROOT","C:/xampp/htdocs/Filebrowser/files/");
// controls
$imgs['controls']['arrow_right'] = $icn_dir.'bullet_arrow_right.png';
$imgs['controls']['arrow_down'] = $icn_dir.'bullet_arrow_down.png';
$imgs['controls']['folder_open'] = $icn_dir.'folder_vertical_open.png';

$viewer['video'][] = 'mp4';
$viewer['video'][] = 'flv';
$viewer['video'][] = 'avi';
$viewer['video'][] = 'mkv';

$viewer['audio'][] = 'ogg';
$viewer['audio'][] = 'mp3';
$viewer['audio'][] = 'ac3';
$viewer['audio'][] = 'wav';
$viewer['audio'][] = 'flac';
$viewer['audio'][] = 'ogg';
$viewer['audio'][] = 'ra';
$viewer['audio'][] = 'wma';

$viewer['text'][] = 'ini';
$viewer['text'][] = 'php';
$viewer['text'][] = 'txt';
$viewer['text'][] = 'text';
$viewer['text'][] = 'bat';
$viewer['text'][] = 'cmd';
$viewer['text'][] = 'ini';
$viewer['text'][] = 'css';
$viewer['text'][] = 'csv';


?>
