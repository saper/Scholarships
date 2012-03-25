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
<?php include TEMPLATEPATH . "header_review.php" ?>
<form method="post" action="grid.php">
<h2>Applications</h2>
<div id="form-container" class="fourteen columns">
<?php include TEMPLATEPATH . "admin_nav.php" ?>
<table id="country-grid" class="grid">
	<tr>
		<th style='width: 8%;'>id</th>
		<th style='width: 42%;'>country name</th>
		<th style='width: 25%;'>rank</th>
		<th style='width: 25%;'>scholarship count</th>
	</tr>
	<?php foreach ($users as $row): ?>
	<tr>
		<td><?= $row['id']; ?></td>
		<td><a href="<?php echo $BASEURL; ?>review/country/edit?id=<?= $row['id'] ?>"><?= $row['country_name']; ?></a></td>
		<td><?= $row['country_rank']; ?></td>
		<td><?= $row['sid']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>
</form>
</div>
	<?php include TEMPLATEPATH . "footer.php" ?>
