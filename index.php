<?
error_reporting(-1 ^ E_NOTICE);
ini_set("display_errors",1);
$page = explode("_",$_GET['page']);
session_set_cookie_params(time()+60*60*24*30);
session_name('Filebrowser');
session_start();
header('Content-Type: text/html; charset=utf-8');
include "inc/functions.php";
if(!empty($_POST)) array_walk_recursive($_POST,'trim_post');
include "inc/c_locale.php";
include "inc/config.php";
include "inc/c_db.php";
$db = new db($db_location,$db_user,$db_password,$db_name);
if(isset($_POST['login']))
{
	$sql = "SELECT `id`,`password` FROM `users` where `nick` = '".$db->escape($_POST['nick'])."'";
	$user = $db->one($sql);
	if($user)
	{
		$password = $user['password'];
		unset($user['password']);
//		if($user['validated'] == 0) $login_error = $l->login_user_not_validated;
//		else if($user['locked'] == 1) $login_error = $l->login_user_locked;
		if($password == md5($_POST['password'])) $_SESSION['user'] = $user;
		else 
			$login_error = $l->login_wrong_user_password;
	}
	else
		$login_error = $l->login_wrong_user_password;
}
if(!empty($_SESSION['user'])) $logged = true;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="content-language" content="en" />
    <title><?=(isset($title) ? $title : ucfirst($page[0]))." - FileBrowser"?></title>
    <base href="<?=$website_basedir?>" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	<link rel="icon" href="favicon.ico" type="image/x-icon" />
    <link href="css/blitzer/jquery-ui-1.10.2.custom.min.css" rel="stylesheet" type="text/css" />
    <link href="css/reset.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
	<link href="css/jquery.context.css" rel="stylesheet" type="text/css" />
    <!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.js"></script>-->
	<script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.ui.min.js"></script>
	<script type="text/javascript" src="js/tablesort/jquery.tablesorter.js"></script>
	<script type="text/javascript" src="js/tinyscrollbar.js"></script> 
    <script type="text/javascript" src="js/functions.js?t=<?=filemtime("js/functions.js")?>"></script>
	<script type="text/javascript" src="js/jquery.contextMenu.js?t=<?=filemtime("js/functions.js")?>"></script>
	<!--<script type="text/javascript" src="js/jquery.position.js?t=<?=filemtime("js/functions.js")?>"></script>-->
	<script type="text/javascript" src="js/ace/ace.js" charset="utf-8"></script>
	<script type="text/javascript" src="js/own_context.js" charset="utf-8"></script>
	<script type="text/javascript" src="js/p_context.js" charset="utf-8"></script>
	<script type="text/javascript" src="js/CamanJS-4.1.1/dist/caman.full.js" charset="utf-8"></script>
	<script type="text/javascript" src="js/dialogs.js" charset="utf-8"></script>
  </head>
  <body>
    <?if($logged) include "start.php"; else include "login.php"?>
	<div id="editor">
	  <div class="head"><input type="button" value="Save" /><input type="button" value="Close" /></div>
	  <div id="ace_edit"></div>
	</div>
	<div id="loader"><img src="img/ajax-loader.gif" alt="" /></div>
	<div id="extractArchiv"><span class="extract"><b>Choose a destination</b> <br /><input type="text" style="width:98%;" value="" /></span></div>
	<div class="alert_glow"></div>
	<div id="image_editor">
	  <img src="" alt="" /><br />
	  <input type="button" value="sepia" onclick="applyFilter(this.value)"/>
	  <input type="button" value="sinCity" onclick="applyFilter(this.value)"/>
	  <input type="button" value="crossProcess" onclick="applyFilter(this.value)"/>
	  <input type="button" value="love" onclick="applyFilter(this.value)"/>
	  <input type="button" value="glowingSun" onclick="applyFilter(this.value)"/>
	  <input type="button" value="pinhole" onclick="applyFilter(this.value)"/>
	  <input type="button" value="vintage" onclick="applyFilter(this.value)"/>
	  <input type="button" value="orangePeel" onclick="applyFilter(this.value)"/>
	  <input type="button" value="nostalgia" onclick="applyFilter(this.value)"/>
	  <input type="button" value="grungy" onclick="applyFilter(this.value)"/>
	  
	</div>
	<div id="video_viewer"><iframe src=""></iframe></div>
	<div id="download_file"><iframe src="download.php"></iframe></div>
	<div id='saveChanges'>Do you want to save your changes?</div>
	<div id="noValidViewer">There is no valid viewer for this file. What do you want to do?</div>
  </body>
</html>