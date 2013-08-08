<div id="login">
  <?if($login_error) echo $login_error?>
  <form action="index.php" method="post">
    <input type="text" name="nick" value="" placeholder="username" />
    <input type="password" name="password" value="" placeholder="password" />
    <input type="submit" name="login" value="login" />
  </form>
</div>
<?echo_array($_SESSION);?>