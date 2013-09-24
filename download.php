<?
session_set_cookie_params(time()+60*60*24*30);
session_name('Filebrowser');
session_start();
include "inc/c_locale.php";
include "inc/config.php";

include "inc/functions.php";
if(isset($_POST['file_path']))
	downloadFile(ROOT.$_POST['file_path']);
else
{
	?>
	<form action="download.php" method="post">
		<input class="file_path" type="text" name="file_path" value="" />
	</form>
	<?
}
?>