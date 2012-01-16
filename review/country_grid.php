<?php
require_once('init.php');

session_start();

if (!isset($_SESSION['user_id']))
{
	header('location: ' . $BASEURL . 'user/login');
	exit();
}

$order = (isset($_GET['order']))?$_GET['order']:'';

$dal = new DataAccessLayer();
$users = $dal->GetListofCountries($order);

$rowstyleeven = 0;
?>
<?php include "$BASEDIR/templates/header_review.php" ?>
<form method="post" action="grid.php">
<h1>Applications</h1>
<?php include "$BASEDIR/templates/admin_nav.php" ?>
<table id="country-grid" style="width: 100%">
	<tr>
		<th>id</th>
		<th>country name</th>
		<th>rank</th>
		<th>scholarship count</th>
	</tr>
	<?php foreach ($users as $row): ?>
	<tr class="<?php echo ($rowstyleeven==1)?"evenrow":"oddrow"; ?>">
		<td><?= $row['id']; ?></td>
		<td><a href="<?php echo $BASEURL; ?>review/country/edit?id=<?= $row['id'] ?>"><?= $row['country_name']; ?></a></td>
		<td><?= $row['country_rank']; ?></td>
		<td><?= $row['sid']; ?></td>
	</tr>
	<?php	$rowstyleeven=($rowstyleeven==1)?0:1;
	endforeach; ?>
</table>
</form>
	<?php include "$BASEDIR/templates/footer.php" ?>
