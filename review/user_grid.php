<?php
require_once('init.php');

	session_start();

	if (!isset($_SESSION['user_id']))
	{
		header('location: login.php');
		exit();
	}

	$state = (isset($_GET['state']))?$_GET['state']:'all';

	$dal = new DataAccessLayer();
	$users = $dal->GetListofUsers($state);
	
        $rowstyleeven = 0;
?>
<?php include "$BASEDIR/includes/header.php" ?>
	<form method="post" action="grid.php">
	<h1>Applications</h1>
<?php include "$BASEDIR/includes/admin_nav.php" ?>
	<table style="width: 100%">
		<tr>
			<th>id</th>
			<th>username</th>
			<th>email</th>
			<?php if ($state=="reviewer") { ?><th>remaining</th><?php
			 } else { ?><th>valid?</th><?php } ?>
		</tr>
		<?php foreach ($users as $row): ?>
		<tr class="<?php echo ($rowstyleeven==1)?"evenrow":"oddrow"; ?>">
			<td><?= $row['id']; ?></td>
			<td><a href="view_user.php?id=<?= $row['id'] ?>"><?= $row['username']; ?></a></td>
			<td><?= $row['email']; ?></td>
			<?php if ($state=="reviewer") { ?><td><?php $outs = $dal->GetCountAllUnscored($row['id']); echo $outs['COUNT(*)']; ?></td><?php
			 } else { ?><td><?= $row['isvalid']; ?></th><?php } ?>
		</tr>
		<?php	$rowstyleeven=($rowstyleeven==1)?0:1; 
			endforeach; ?>
	</table>
	</form>
<?php include "$BASEDIR/includes/footer.php" ?>
