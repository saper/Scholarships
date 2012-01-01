<?php 
require_once('init.php');

	if (isset($_POST['username']) && isset($_POST['password']))
	{
		$dal = new DataAccessLayer();
		$res = $dal->GetUser($_POST['username']);
		if (md5($_POST['password']) == $res['password']) {
			session_start();
			$_SESSION['user_id'] = $res['id'];
			header('location: grid.php');
			exit();
		}
		else {
			$error = "Invalid credentials.";
		}
	}
?>
<?php include "$BASEDIR/templates/header.php" ?>
	<form method="post" action="login.php">
	<h1>Log in</h1>
	<?php if (isset($error)) print "<p>" . $error . "</p>\n"; ?>
	<fieldset>
	<p>Username: <input type="text" id="username" name="username" /></p>
	<p>Password: <input type="password" id="password" name="password" /></p>
	<p><input type="submit" value="Log in" />
	</fieldset>
	</form>
<?php include "$BASEDIR/templates/footer.php" ?>
