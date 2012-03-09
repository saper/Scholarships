<?php
require_once('init.php');

session_start();

if (!isset($_SESSION['user_id'])) {
	header('location: ' . $BASEURL . 'user/login');
	exit();
}

if ( isset( $_GET['items'] ) )  {
        $items = intval($_GET['items']);
} else { 
	$items = 100;
}

if ( isset( $_GET['p'] ) )  {
        $p = intval($_GET['p']);
} else {
	$p = 0;
}

$dal = new DataAccessLayer();
$params = array(
        'min' => isset( $_GET['min'] ) ? $_GET['min'] : -2,
        'max' => isset( $_GET['max'] ) ? $_GET['max'] : 999,
        'phase' => 2,
	'items' => $items,
	'offset' => $p
);

$schols = $dal->gridData($params);
?>
<?php include "$BASEDIR/templates/header_review.php" ?>
<h2>Scholarship Applications</h2>
<div id="form-container" class="fourteen columns">
<?php include "$BASEDIR/templates/admin_nav.php" ?>
<form method="post" action="<?php echo $BASEURL; ?>review/grid">
<table id="grid" style="width: 100%;">
	<tr>
		<th style="width: 4%;">id</th>
		<th style="width: 16%;">name</th>
		<th style="width: 28%;">email</th>
		<th style="width: 10%;">residence</th>
		<th style="width: 5%;">sex</th>
		<th style="width: 4%;">age</th>
		<th style="width: 6%;">partial</th>
		<th style="width: 5%;"># p2</th>
		<th style="width: 8%;">p2</th>
	</tr>
	<?php foreach ($schols as $row): ?>
	<tr>
		<td><?= $row['id']; ?></td>
		<td><a href="<?php echo $BASEURL; ?>review/view?id=<?= $row['id'] ?>&phase=2"><?= $row['fname'] . ' ' . $row['lname']; ?></a></td>
		<td><?= $row['email']; ?></td>
		<td><?= $row['country_name']; ?></td>
		<td><?= $row['sex']; ?></td>
		<td><?= $row['age']; ?></td>
		<td><?= $row['partial']; ?></td>
		<td><?
if ( $row['nscorers'] == 0 ) {
        echo "-";
} else {
	echo $row['nscorers']; 
}
?></td>
		<td>Hidden</td>
	</tr>
	<?php endforeach; ?>
</table>
</form>
<a href="<?php echo $BASEURL; ?>review/phase2?items=<?php echo $items; ?>&p=<?php echo $p - 1; ?>" id="prev">Prev</a>
<a href="<?php echo $BASEURL; ?>review/phase2?items=<?php echo $items; ?>&p=<?php echo $p + 1; ?>" id="next">Next</a>
</div>
<?php include "$BASEDIR/templates/footer.php" ?>
