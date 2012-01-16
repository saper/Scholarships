<?php 
require_once('init.php');

	if (isset($_POST['username']) && isset($_POST['password']))
	{
		$user = new DataAccessLayer();
		$res = $user->GetUser($_POST['username']);
		if (md5($_POST['password']) == $res['password']) {
			session_start();
			$_SESSION['user_id'] = $res['id'];
			print $res['id'];
			header('location: ' . $BASEURL . 'review/grid');
			print 'login';
			exit();
		}
		else {
			$error = "Invalid credentials.";
		}
	}
?>
<?php include "$BASEDIR/templates/header_review.php" ?>
	<form method="post" action="<?php echo $BASEURL; ?>user/login" >
	<h1>Log in</h1>
	<?php if (isset($error)) print "<p>" . $error . "</p>\n"; ?>
	<fieldset>
	<p>Username: <input type="text" id="username" name="username" /></p>
	<p>Password: <input type="password" id="password" name="password" /></p>
	<p><input type="submit" value="Log in" />
	</fieldset>
	</form>
<?php include "$BASEDIR/templates/footer.php" ?>
