<ul class="hnav">
	<li><a href="<?php echo $BASEURL; ?>review/grid">Grid</a></li>
<!--	<li><a href="grid.php?min=2">2+</a></li>-->
	<li><a href="<?php echo $BASEURL; ?>review/view?id=unranked">Queue</a></li>
	<li><a href="<?php echo $BASEURL; ?>review/grid/score?partial=0">Final: Full</a></li>
	<li><a href="<?php echo $BASEURL; ?>review/grid/score?partial=1">Final: Partial</a></li>
	<?php
	$isadmin = $dal->IsSysAdmin($_SESSION['user_id']);
	if ($isadmin == 1) { ?>
		<li><a href="<?php echo $BASEURL; ?>user/list">Users</a></li>
		<li><a href="<?php echo $BASEURL; ?>review/country/grid">Countries</a></li>
	<? } ?>
</ul>
<hr />
<?php

$username = $dal->GetUsername($_SESSION['user_id']);
$userunranked = $dal->GetCountAllUnrankedPhase1($_SESSION['user_id']);  //phase 1
$userunscored = $dal->GetCountAllUnrankedPhase2($_SESSION['user_id']);    //phase 2
$totalphase1 = $dal->GetCountAllPhase1();  //phase 1
$totalphase2 = $dal->GetCountAllPhase2();  //phase 2

echo '<p style="text-align: center; font-size: x-small;">'.$username['username'].
' (user id '.$_SESSION['user_id'].'): <span style="color:red;">'
.$userunscored['COUNT(*)'].'</span> outstanding applications of <span style="color:#000088;">'
.$totalphase2['COUNT(*)'].'</span> total.</p>';
?>

