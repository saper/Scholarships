<?php
require_once('init.php');

	function YearsOld($date) {
		$born = preg_replace('/^([^-]*).*$/', '\1', $date);
		return 2011 - $born;
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

	if (isset($_POST['up'])) {
		$dal->InsertOrUpdateRanking($user_id, $_POST['last_id'], 'valid', 1);
	} else if (isset($_POST['down'])) {
		$dal->InsertOrUpdateRanking($user_id, $_POST['last_id'], 'valid', -1);
	} else if (isset($_POST['skip'])) {
		$dal->InsertOrUpdateRanking($user_id, $_POST['last_id'], 'valid',  0);
	} else if (isset($_POST['save'])) {
		$dal->UpdateField('notes', $_POST['last_id'], $_POST['notes']);
		$dal->UpdateField('residence', $_POST['last_id'], $_POST['residence']);
		$dal->UpdateField('nationality', $_POST['last_id'], $_POST['nationality']);
		$dal->UpdateField('exclude', $_POST['last_id'], $_POST['exclude']);
		$dal->UpdateField('address', $_POST['last_id'], $_POST['address']);
	}

	if (isset($_POST['save']))
		$schol = $dal->GetScholarship($_POST['last_id']);
	else $schol = ($id == 'unranked')
		? $dal->GetNextPhase1($user_id)
		: $dal->GetScholarship($id);
	$rankings = $dal->GetPhase1Rankings($schol['id']);

	$COUNTRY_NAMES = array("Afghanistan","Albania","Algeria","Andorra","Angola","Antigua and Barbuda","Argentina","Armenia","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belgium","Belize","Belorus","Benin","Bhutan","Bolivia","Bosnia and Herzegovina","Botswana","Brazil","Brunei","Bulgaria","Burkina Faso","Burma","Burundi","Cambodia","Cameroon","Canada","Cape Verde","Central African Republic","Chad","Chile","China","Colombia","Comoros","Costa Rica","Côte d'Ivoire","Croatia","Cuba","Cyprus","Czech Republic","Democratic Republic of the Congo","Denmark","Djibouti","Dominica","Dominican Republic","East Timor","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Federated States of Micronesia","Fiji","Finland","France","Gabon","Gambia","Georgia","Germany","Ghana","Greece","Grenada","Guatemala","Guinea","Guinea-Bissau","Guyana","Haiti","Honduras","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland","Israel","Italy","Jamaica","Japan","Jordan","Kazakhstan","Kenya","Kiribati","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Mauritania","Mauritius","Mexico","Moldova","Monaco","Mongolia","Montenegro","Morocco","Mozambique","Namibia","Nauru","Nepal","Netherlands","New Zealand","Nicaragua","Niger","Nigeria","North Korea","Norway","Oman","Pakistan","Palau","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal","Qatar","Republic of Congo","Romania","Russia","Rwanda","Saint Kitts and Nevis","Saint Lucia","Saint Vincent and the Grenadines","Samoa","San Marino","São Tomé and Príncipe","Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Korea","Spain","Sri Lanka","Sudan","Suriname","Swaziland","Sweden","Switzerland","Syria","Thailand","Tajikistan","Tanzania","Togo","Tonga","Trinidad and Tobago","Tunisia","Turkey","Turkmenistan","Tuvalu","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States of America","Uruguay","Uzbekistan","Vanuatu","Vatican","Venezuela","Vietnam","Yemen","Zambia","Zimbabwe","Taiwan (Republic of China)","Hong Kong","Macao");

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

	<form method="post" action="edit.php">
	<h1>View application</h1>
<?php include "$BASEDIR/templates/admin_nav.php" ?>
	<div style="position: fixed; top: 20%; left: 3%">
	<p><input type="submit" id="up" name="up" value="+" style="color: green; font-size: 300%; width: 50px; height: 50px" /><br/>
	<input type="submit" id="skip" name="skip" value="Skip" style="width: 50px; height: 50px" /><br/>
	<input type="submit" id="down" name="down" value="-" style="color: red; font-size: 300%; width: 50px; height: 50px" /></p>
	</div>

	<fieldset>
	<div style="float: right; text-align: right; margin-right: 1em">
	<input type="button" id="stamp" name="stamp" value="Insert stamp" onclick="insertStamp();" style="width: 10em" /><br />
	<textarea id="notes" name="notes" style="width: 30em; height: 15ex"><?= $schol['notes']; ?></textarea><br />
	<p>Update address:<textarea id="address" name="address"><?= $schol['address']?></textarea>
	</p>
	<p>Update residence:<select id="residence" name="residence">
	<option>Select one:</option>
    	<?php foreach (range(0, 195) as $i)
    	if ($schol['residence']==$i+1) {
        	printf('<option value="%d" selected="selected">%s</option>', $i+1, $COUNTRY_NAMES[$i]);
	    } else {
	        printf('<option value="%d">%s</option>', $i+1, $COUNTRY_NAMES[$i]);
	    }
	?></select>
	</p>
	<p>Update nationality:<select id="nationality" name="nationality">
	<option>Select one:</option>
    	<?php foreach (range(0, 195) as $i)
    	if ($schol['nationality']==$i+1) {
        	printf('<option value="%d" selected="selected">%s</option>', $i+1, $COUNTRY_NAMES[$i]);
	    } else {
	        printf('<option value="%d">%s</option>', $i+1, $COUNTRY_NAMES[$i]);
	    }
	?></select>
	</p>
        <p>Exclude this application? <input type="checkbox" id="exclude" name="exclude" value="1" <?= $schol['exclude']==1?'checked="checked" ':''; ?>/>  
	<input type="submit" id="save" name="save" value="Save" style="width: 10em" />
	</div>

	<?php if (($schol['sincere']==0)||($schol['agreestotravelconditions']==0)||($schol['willgetvisa']==0)||($schol['willpayincidentals']==0)) {  // non-editable Answerbox  ?>
  	<div style="float: right; margin-right: 1em; border: 1px #33dd88 solid; width: 20em; padding 2em;"><?php if ($schol['sincere']==0) { ?>
        <p style="font-weight:bold;">Did not agree that they understood application.</p><?php } ?>
<?php if ($schol['agreestotravelconditions']==0) { ?>
        <p style="font-weight:bold;">Did not agree to travel conditions.</p><?php } ?>
<?php if ($schol['willgetvisa']==0) { ?>
        <p style="font-weight:bold;">Did not agree to get own visa.</p><?php } ?>
<?php if ($schol['willpayincidentals']==0) { ?>
        <p style="font-weight:bold;">Did not agree to pay incidentals</p><?php } ?>
	</div><div style="clear:left;">&nbsp;</div>
<?php } ?>	

	<p><span style="font-size: 200%"><?= $schol['fname'] . ' ' . $schol['lname'] ?></span><br/><span style="font-size: 125%;">User:<a href="http://toolserver.org/~vvv/sulutil.php?user=<?= $schol['username'] ?>" target="_blank" style="color:#000088;"><?= $schol['username'] ?></a></span> (Click to view cross-wiki contributions)</p>
	<p>Address (Country): <?= $schol['country'] ?> <br/>
	Residence: <?= $schol['residence'] ?> - <?= $schol['residence_name'] ?><br/>
	Citizenship: <?= $schol['nationality'] ?> - <?= $schol['country_name'] ?><br/>
	Email: <a href="mailto:<?= $schol['email'] ?>"><?= $schol['email'] ?></a>&emsp;Phone: <?= $schol['telephone'] ?></p>
	<p>Date of birth: <?= $schol['dob'] ?> (~<?= YearsOld($schol['dob']) ?> years old)</p>
	<p>Sex: <?= Sex($schol['sex']) ?></p>
	<p>Speaks <?= $schol['languages'] ?></p>

	<?php if (strlen($schol['occupation']) > 0): ?>
	<p>Occupation: <?= $schol['occupation'] ?></p>
	<?php endif; ?>
	<?php if (strlen($schol['areaofstudy']) > 0): ?>
	<p>Area of study: <?= $schol['areaofstudy'] ?></p>
	<?php endif; ?>
	<?php if (strlen($schol['occupation']) == 0 && strlen($schol['areaofstudy']) == 0): ?>
	<p>Did not give an occupation or area of study.</p>
	<?php endif; ?>
	<table border="1"><tr>
		<th>2005</th>
		<th>2006</th>
		<th>2007</th>
		<th>2008</th>
		<th>2009</th>
		<th>2010</th>
	</tr><tr>
		<td><?= $schol['wm05'] == 1 ? 'X' : '&nbsp;' ?></td>
		<td><?= $schol['wm06'] == 1 ? 'X' : '&nbsp;' ?></td>
		<td><?= $schol['wm07'] == 1 ? 'X' : '&nbsp;' ?></td>
		<td><?= $schol['wm08'] == 1 ? 'X' : '&nbsp;' ?></td>
		<td><?= $schol['wm09'] == 1 ? 'X' : '&nbsp;' ?></td>
		<td><?= $schol['wm10'] == 1 ? 'X' : '&nbsp;' ?></td>
	</tr></table>
	</fieldset>

	<fieldset>
	<legend>Why do you want to attend?</legend>
	<?php if (strlen($schol['why']) > 0): ?>
	<p><?= $schol['why'] ?></p>
	<?php else: ?>
	<p>Declined to state.</p>
	<?php endif; ?>
	</fieldset>
	
	<fieldset>
	<legend>How will Wikimania affect your future involvement?</legend>
	<?php if (strlen($schol['future']) > 0): ?>
	<p><?= $schol['future'] ?></p>
	<?php else: ?>
	<p>Declined to state.</p>
	<?php endif; ?>
	</fieldset>

	<fieldset>
	<legend>What is your involvement?</legend>
	<?php if (strlen($schol['involvement']) > 0): ?>
	<p><?= $schol['involvement'] ?></p>
	<?php else: ?>
	<p>Declined to state.</p>
	<?php endif; ?>
	</fieldset>

	<fieldset>
	<legend>What contributions have you made to free knowledge and free software?</legend>
	<?php if (strlen($schol['contribution']) > 0): ?>
	<p><?= $schol['contribution'] ?></p>
	<?php else: ?>
	<p>Declined to state.</p>
	<?php endif; ?>
	</fieldset>

	<fieldset>
	<legend><a href="#" onClick="toggleDump();">Show full dump</a></legend>
	<div id="dump">
	<?php foreach ($schol as $k => $v): ?>
	<p><?= $k ?>: <?= $v ?></p>
	<?php endforeach; ?>
	</div>
	</fieldset>

	<fieldset>
	<legend>Rankings</legend>
	<?php if (count($rankings) > 0): foreach ($rankings as $r): ?>
	<p><?= $r['username'] ?> voted <?= sprintf('%+d', $r['rank']) ?></p>
	<?php endforeach; else: ?>
	<p>This application has not been ranked.</p>
	<?php endif; ?>
	</fieldset>

	<input type="hidden" id="id" name="id" value="<?= $id ?>"/>
	<input type="hidden" id="last_id" name="last_id" value="<?= $schol['id'] ?>"/>
	</form>
<?php include "$BASEDIR/templates/footer.php" ?>
