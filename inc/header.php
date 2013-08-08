<?php
$header = '200';
header($_SERVER['SERVER_PROTOCOL'].' 200 OK');

$pFolder = 'page/';
$login_error  = '';
if(isset($_POST['login']))
{
	$sql = "SELECT `id`,`password` FROM `users` where `name` = '".$db->escape($_POST['name'])."'";
	$user = $db->one($sql);
	if($user)
	{
		$password = $user['password'];
		unset($user['password']);
//		if($user['validated'] == 0) $login_error = $l->login_user_not_validated;
//		else if($user['locked'] == 1) $login_error = $l->login_user_locked;
		if($password == md5($_POST['pw'])) $_SESSION['user'] = $user;
		else $login_error = $l->login_wrong_user_password;
		echo $password.' / '.md5($_POST['pw']);
	}
	else
		$login_error = $l->login_wrong_user_password;
	
	if($login_error != '')
	{
		$include = 'login.php';
		goto end;
	}
}
if(!empty($_SESSION['user'])) $logged = true;
if($page[0] == '' or $page[0] == 'logout')
{
	if($page[0] == 'logout')
	{
		$_SESSION['user'] = array();
		unset($_SESSION['user']);
		$logged = false;
	}
	$include = 'start.php';
}
else if($page[0] == 'add')
{
	$include = 'add.php';
}
else if($page[0] == 'incomplete')
{
	$include = 'incomplete.php';
}
else if($page[0] == 'search')
{
	$include = 'search.php';
}
else if($page[0] == 'insertions')
{
	if($logged)
		$include = 'insertions.php';
	else $include = 'login.php';
}
else if($page[0] == 'profile')
{
	if($logged)
	{
		if(isset($page[1]))
		{
			if($page[1] == 'edit') $include = 'profile_edit.php';
			if($page[1] == 'places')
			{
				if(isset($page[2]))
				{
					if($page[2] == 'create') $include = 'profile_places_create.php';
					if($page[2] == 'edit') $include = 'profile_places_edit.php';
				}
			}
		}
		else $include = 'profile.php'; 
	}
	else $include = 'login.php';
}
else if($page[0] == 'register')
{
	$include = 'register.php';
}
else if($page[0] == 'validate')
{
	$include = 'validate.php';
}
else if($page[0] == 'test')
{
	$include = 'test.php';
}
else $include = '404.php';
if($include == '404.php')
{
	$header = '404';
	if(basename ($_SERVER['SCRIPT_NAME']) != 'ajax.php') header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
}

end:
$include = $pFolder.$include;
$page_checked = true;
?>