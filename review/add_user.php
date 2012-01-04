<?php
require_once('init.php');

session_start();

if (!isset($_SESSION['user_id']))
{
	header('location: login.php');
	exit();
}

$user_id = $_SESSION['user_id'];

$dal = new DataAccessLayer();
$username = $dal->GetUsername($_SESSION['user_id']);

include "$BASEDIR/templates/header.php";
include "$BASEDIR/templates/admin_nav.php";

if (isset($_POST['save'])) {
	$data = $_POST;
	if ( !isset( $data['isadmin'] ) ) { $data['isadmin'] = 0; }
	if ( !isset( $data['isvalid'] ) ) { $data['isvalid'] = 0; }
	if ( !isset( $data['reviewer'] ) ) { $data['reviewer'] = 0; }

	if ( ( !isset( $data['email'] ) ) or ( !isset( $data['password'] ) ) or ( !isset( $data['username'] ) ) ) {
		echo "Empty fields.  Please try again";
	} else {
		$fieldnames = array("username","password","email","reviewer","isvalid","isadmin");
		foreach ($fieldnames as $i) {
			$answers[$i] = mysql_real_escape_string($_POST[$i]);
		}
		$answers['password'] = md5($_POST['password']);
		$id = $dal->NewUserCreate($answers);
		if ( !$id ) {
			echo "<strong>Error: User already exists</strong>";
		} else {
			echo "<strong>" . $data['username'] . ' added' . "</strong>"; 
		}?>
		<form method="post" action="add_user.php">
		<h1>Add another user</h1>
<?
	}
} else {

?>
<form method="post" action="add_user.php">
<h1>Add new user</h1>
<?
} ?>
<?php 
$isadmin = $dal->IsSysAdmin($_SESSION['user_id']); 
if ($isadmin == 1) { ?>
<fieldset><input type="hidden" name="id" id="id"
	value="" /> <input type="hidden" name="formstate"
	id="formstate" value="new" />

<p>Username: <input type="text" name="username" id="username"
	value="" /></p>
<p>Password: <input type="password" name="password" id="password"
	value="" /></p>
<p>Email: <input type="text" name="email" id="email"
	value=""/ ></p>
<p>Is Reviewer?: <input type="checkbox" name="reviewer" id="reviewer"
	value="1" /></p>
<p>Is Valid?: <input type="checkbox" name="isvalid" id="isvalid"
	value="1" /></p>
<p>Is Admin?: <input type="checkbox" name="isadmin" id="isadmin"
	value="1" /></p>
</fieldset>
<input type="submit" id="save" name="save" value="Save"
	style="width: 10em" /></form>
<?php 
} else {
  print "Permission denied for this page.";
}
include "$BASEDIR/templates/footer.php" 
?>
