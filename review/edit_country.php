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

if ($_GET['id']) {
	$id = $_GET['id'];
} else {
	$id = $_POST['id'];
}

if (isset($_POST['save'])) {
	$dal->UpdateCountryRank( $id , $_POST['rank'] );
	$id++;
}

$country = $dal->GetCountryInfo($id);

?>
<?php include "$BASEDIR/templates/header.php" ?>

<form method="post" action="edit_country.php">
<h1>Edit Country Info</h1>
<?php include "$BASEDIR/templates/admin_nav.php" ?>
<div style="align: center; font-size: 0.84em;"><a
	href="country_grid.php">Return to country grid</a></div>
<fieldset><input type="hidden" name="id" id="id"
	value="<?= $country['id'] ?>" />
<p><span style="font-size: 150%">ID: <strong><?= $country['id'] ?></strong></span></p>
<p>Country Name: <strong><?= $country['country_name'] ?></strong></p>
<p>Rank: <input type="text" name="rank" id="rank"
	value="<?= $country['country_rank'] ?>"/ ></p>
</fieldset>
<input type="submit" id="save" name="save" value="Save"
	style="width: 10em" /></form>
<?php include "$BASEDIR/templates/footer.php" ?>
