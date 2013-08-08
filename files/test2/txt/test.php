<?
error_reporting(-1 ^ E_NOTICE);
ini_set("display_errors",1);
$page = explode("_",$_GET['page']);
session_set_cookie_params(time()+60*60*24*30);
session_name('theatre');
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
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.ui.min.js"></script>
	<script type="text/javascript" src="js/tablesort/jquery.tablesorter.min.js"></script>
	<script type="text/javascript" src="js/tinyscrollbar.js"></script> 
    <script type="text/javascript" src="js/functions.js?t=<?=filemtime("js/functions.js")?>"></script>
	<script type="text/javascript" src="js/jquery.contextMenu.js?t=<?=filemtime("js/functions.js")?>"></script>
	<script type="text/javascript" src="js/jquery.position.js?t=<?=filemtime("js/functions.js")?>"></script>
	<script type="text/javascript" src="js/ace-builds/src-noconflict/ace.js" charset="utf-8"></script>

  </head>
  <body>
    <?if($logged) include "start.php"; else include "login.php"?>
	<div id="editor">asd</div>
  </body>
</html>