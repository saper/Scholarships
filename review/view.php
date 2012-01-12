<?php
require_once('init.php');

function YearsOld($date) {
	$born = preg_replace('/^([^-]*).*$/', '\1', $date);
	return 2012 - $born;
}

function Sex($sex) {
	switch ($sex) {
		case 'm':
			return 'male';
		case 'f':
			return 'female';
		default:
			return 'declined to state';
	}
}

function GetScholarshipId() {
	$ret = '';

	if ($_POST['last_id'] > 0)
	$ret = $_POST['last_id'];
	else if ($_GET['id'] > 0)
	$ret = $_GET['id'];

	return $ret;
}

function RankDropdownList($criterion,$scholarship_id) {
	global $user_id;
	$dal = new DataAccessLayer();
	$rank = $dal->GetPhase2RankingOfUser($user_id, $scholarship_id, $criterion);
	$ret = sprintf('<select id="%s" name="%s">', $criterion, $criterion);

	for ($i = 4; $i >= 0; $i--)
	$ret .= sprintf('<option value="%d"%s>%d</option>',
	$i, $i == $rank ? ' selected="selected"' : '', $i);

	$ret .= '</select>';
	return $ret;
}

//function GetUserTable($username,$minedits) { // CB added
//	$uname = preg_replace('/\s/', '_', $username);
//	$ret = file_get_contents('http://toolserver.org/~vvv/sultable.php?user=' . $uname. '&editcount=' . $minedits);
//	//$ret = file_get_contents('http://toolserver.org/~vvv/usertable.php?user=' . $uname. '&editcount=' . $minedits);
//	//	$ret = 'http://toolserver.org/~vvv/usertable.php?user=' . $uname. '&editcount=' . $minedits;
//	return $ret;
//}

//function GetStaggeredUserData($username) {
//	if ($username != "") {
//		$ret = GetUserTable($username,50);
//		if ($ret == "<strong class='error'>Returned no results</strong>") {
//			$ret = GetUserTable($username,10);
//			if ($ret == "<strong class='error'>Returned no results</strong>") {
//				$ret = GetUserTable($username,0);
//			}
//		}
//	} else {
//		$ret = "No username listed";
//	}
//	return $ret;
//}

session_start();

if (!isset($_SESSION['user_id']))
{
	header('location: login.php');
	exit();
}

$user_id = $_SESSION['user_id'];

if ($_GET['id'])
$id = $_GET['id'];
else if ($_POST['id'])
$id = $_POST['id'];
else
die("No ID supplied!");

$dal = new DataAccessLayer();
$username = $dal->GetUsername($_SESSION['user_id']);

if (isset($_POST['rank'])) {
	$CRITERIA = array('onwiki', 'offwiki', 'future'); //, 'special');
	foreach ($CRITERIA as $c)
	if (isset($_POST[$c]))
	$dal->InsertOrUpdateRanking($user_id, $_POST['last_id'], $c, $_POST[$c]);
} else if (isset($_POST['save']) && isset($_POST['notes']) && strlen($_POST['notes']) > 0) {
	$dal->UpdateNotes($_POST['last_id'], $_POST['notes']);
}

if (isset($_POST['save']))
$schol = $dal->GetScholarship($_POST['last_id']);
else if ($id == 'unranked')
$schol = $dal->GetNextPhase1($user_id);
else
$schol = $dal->GetScholarship($id);

$scorings = $dal->GetPhase1Rankings($schol['id']);
?>
<?php include "$BASEDIR/templates/header.php" ?>
<script type="text/javascript">
		function toggleDump() {
			var dump = document.getElementById('dump');
			if (dump.style.display == 'block')
				dump.style.display = 'none';
			else
				dump.style.display = 'block';
		}

		function insertStamp() {
			notes = document.getElementById('notes');
			now = new Date();
			year = now.getUTCFullYear();
			month = now.getUTCMonth() + 1;
			day = now.getUTCDate();
			hours = now.getUTCHours();
			minutes = now.getUTCMinutes();
			notes.value = month + '/' + day + ' '
				+ hours + ':' + (minutes < 10 ? '0' + minutes : minutes)
				+ ' ' + "<?= $username['username'] ?>"
				+ ": \n\n" + notes.value;
		}
</script>

<form method="post" action="view.php">
<h1>View application</h1>
<?php include "$BASEDIR/templates/admin_nav.php" ?>
<div style="position: fixed; top: 20%; right: 3%; font-size: 150%">
<table
	style="text-align: right; background-color: #ccc; border: solid 1px #333">
	<tr>
		<td>Future promise:</td>
		<td><?= RankDropdownList('future',$schol['id']) ?></td>
	</tr>
	<tr>
		<td>In Wikimedia movement:</td>
		<td><?= RankDropdownList('onwiki',$schol['id']) ?></td>
	</tr>
	<tr>
		<td>Outside Wikimedia movement:</td>
		<td><?= RankDropdownList('offwiki',$schol['id']) ?></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" id="rank" name="rank" value="Rank" s /></td>
	</tr>
</table>
</div>

<fieldset>
<div style="float: right; text-align: right; margin-right: 1em"><input
	type="button" id="stamp" name="stamp" value="Insert stamp"
	onclick="insertStamp();" style="width: 10em" /><br />
<textarea id="notes" name="notes" style="width: 30em; height: 15ex"><?= $schol['notes'] ?></textarea><br />
<input type="submit" id="save" name="save" value="Save"
	style="width: 10em" /></div>
<br style="clear: left" />
</div>

<p><span style="font-size: 200%"><?= $schol['fname'] . ' ' . $schol['lname'] ?></span><br /><br/>
<?php if ( isset( $schol['username'] ) ): ?>
User: <?= $schol['username'] ?> (<a
	href="http://toolserver.org/~vvv/sulutil.php?user=<?= $schol['username'] ?>"
	target="_blank" style="color: #000088;">cross-wiki contribs</a></span>)
<?php else: ?>
User: no username
<?php endif; ?>
<br/><br/>
Residence: <?= $schol['residence_name'] ?><br />
Citizenship: <?= $schol['country_name'] ?></p>
Email: <a href="mailto:<?= $schol['email'] ?>"><?= $schol['email'] ?></a>&emsp;Phone:
<?= $schol['telephone'] ?>
</p>
<p>Date of birth: 
<?php
if ( ( strtotime( $schol['dob'] ) > strtotime( '1875-01-01' ) ) &&
  ( strtotime( $schols['dob'] ) < time() ) ) {
	echo $schol['dob'] . ' - ' . YearsOld($schol['dob']) . ' years old';
} else {
	echo 'Not specified';
} ?>
</p>
<p>Sex: <?= Sex($schol['sex']) ?></p>
<p>Speaks <?= $schol['languages'] ?></p>

<?php if (strlen($schol['occupation']) > 0): ?>
<p>Occupation: <?= $schol['occupation'] ?></p>
<?php endif; ?> <?php if (strlen($schol['areaofstudy']) > 0): ?>
<p>Area of study: <?= $schol['areaofstudy'] ?></p>
<?php endif; ?> <?php if (strlen($schol['occupation']) == 0 && strlen($schol['areaofstudy']) == 0): ?>
<p>Did not give an occupation or area of study.</p>
<?php endif; ?>
<table border="1">
	<tr>
		<th>2005</th>
		<th>2006</th>
		<th>2007</th>
		<th>2008</th>
		<th>2009</th>
		<th>2010</th>
		<th>2011</th>		
	</tr>
	<tr>
		<td><?= $schol['wm05'] == 1 ? 'X' : '&nbsp;' ?></td>
		<td><?= $schol['wm06'] == 1 ? 'X' : '&nbsp;' ?></td>
		<td><?= $schol['wm07'] == 1 ? 'X' : '&nbsp;' ?></td>
		<td><?= $schol['wm08'] == 1 ? 'X' : '&nbsp;' ?></td>
		<td><?= $schol['wm09'] == 1 ? 'X' : '&nbsp;' ?></td>
		<td><?= $schol['wm10'] == 1 ? 'X' : '&nbsp;' ?></td>
		<td><?= $schol['wm11'] == 1 ? 'X' : '&nbsp;' ?></td>
	</tr>
</table>

<?php if (($schol['sincere']==0)||($schol['agreestotravelconditions']==0)||($schol['willgetvisa']==0)||($schol['willpayincidentals']==0)) {  // non-editable Answerbox  ?>
<div style="float: right; margin-right: 1em; border: 1px #33dd88 solid; width: 20em;"><?php if ($schol['sincere']==0) { ?>
<p style="font-weight: bold;">Did not agree that they understood
application.</p>
<?php } ?> <?php if ($schol['agreestotravelconditions']==0) { ?>
<p style="font-weight: bold;">Did not agree to travel conditions.</p>
<?php } ?> <?php if ($schol['willgetvisa']==0) { ?>
<p style="font-weight: bold;">Did not agree to get own visa.</p>
<?php } ?> <?php if ($schol['willpayincidentals']==0) { ?>
<p style="font-weight: bold;">Did not agree to pay incidentals</p>
<?php } ?></div>
<div style="clear: left;">&nbsp;</div>
<?php } ?>

<p>Partial scholarships: <br/>
<p><?= $schol['wantspartial'] ? 'Wants partial scholarship' : 'Doesn\'t want partial scholarship' ?><br/>
<?= $schol['canpaydiff'] ? 'Can pay the rest of the sum if awarded partial scholarship' : 'Cannot pay the rest of the sum if awarded partial scholarship'?></p>
</fieldset>

<fieldset><legend>Why do you want to attend?</legend> <?php if (strlen($schol['why']) > 0): ?>
<p><?= $schol['why'] ?></p>
<?php else: ?>
<p>Declined to state.</p>
<?php endif; ?></fieldset>

<fieldset><legend>How will Wikimania affect your future involvement?</legend> <?php if (strlen($schol['future']) > 0): ?>
<p><?= $schol['future'] ?></p>
<?php else: ?>
<p>Declined to state.</p>
<?php endif; ?></fieldset>

<fieldset><legend>What is your involvement with Wikimedia?</legend> <?php if (strlen($schol['involvement']) > 0): ?>
<p><?= $schol['involvement'] ?></p>
<?php else: ?>
<p>Declined to state.</p>
<?php endif; ?></fieldset>

<fieldset><legend>What contribution have you made to free knowledge and free software?</legend>
<?php if (strlen($schol['contribution']) > 0): ?>
<p><?= $schol['contribution'] ?></p>
<?php else: ?>
<p>Declined to state.</p>
<?php endif; ?></fieldset>

<fieldset><legend><a href="#" onClick="toggleDump();">Show full dump</a></legend>
<div id="dump"><?php foreach ($schol as $k => $v): ?>
<p><?= $k ?>: <?= $v ?></p>
<?php endforeach; ?></div>
</fieldset>

<fieldset><legend>Scorings</legend> <?php if (count($scorings) > 0): foreach ($scorings as $r): ?>
<p><?= $r['username'] ?> voted 
<?= "" /*sprintf('%+d', $r['rank'])*/ ?> 
for <?= $r['criterion'] ?></p>
<?php endforeach; else: ?>
<p>This application has not been ranked.</p>
<?php endif; ?></fieldset>

<input type="hidden" id="id" name="id" value="<?= $id ?>" /> <input
	type="hidden" id="last_id" name="last_id" value="<?= $schol['id'] ?>" />
</form>
<?php include "$BASEDIR/templates/footer.php" ?>
