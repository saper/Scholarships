<?php
require_once('init.php');

session_start();

if (!isset($_SESSION['user_id']))
{
	header('location: login.php');
	exit();
}

$min = isset( $_GET['min'] ) ? $_GET['min'] : -2;
$max = isset( $_GET['max'] ) ? $_GET['max'] : 999;

$dal = new DataAccessLayer();
$schols = $dal->GetPhase1GridData($min, $max);
$rowstyleeven = 0;
?>
<?php include "$BASEDIR/templates/header.php" ?>
<form method="post" action="grid.php">
<h1>Applications</h1>
<?php include "$BASEDIR/templates/admin_nav.php" ?>
<table style="width: 100%">
	<tr>
		<th>id</th>
		<th>name</th>
		<th>email</th>
		<th>residence</th>
		<th>sex</th>
		<th>age</th>
		<th>partial?</th>
		<th>p1 score</th>
		<th># p2 scorers</th>
		<th>p2 score</th>
	</tr>
	<?php foreach ($schols as $row): ?>
	<tr class="<?php echo ($rowstyleeven==1)?"evenrow":"oddrow"; ?>">
		<td><?= $row['id']; ?></td>
		<td width=25%><a href="view.php?id=<?= $row['id'] ?>"><?= $row['fname'] . ' ' . $row['lname']; ?></a></td>
		<td width=20%><?= $row['email']; ?></td>
		<td width=25%><?= $row['country_name']; ?></td>
		<td width=8%><?= $row['sex']; ?></td>
		<td width=8%><?= $row['age']; ?></td>
		<td width=8%><?= $row['partial']; ?></td>
		<td width=8%><?= $row['p1score']; ?></td>
		<td width=8%><?= $row['nscorers']; ?></td>
		<td width=8%><?= "Hidden"/*$row['p2score']; */?></td>
	</tr>
	<?php	$rowstyleeven=($rowstyleeven==1)?0:1;
	endforeach; ?>
</table>
</form>
<?php include "$BASEDIR/templates/footer.php" ?>
