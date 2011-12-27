<?php



if (!($db = mysql_connect($db_host, $db_user, $db_pass)))
	die('Could not connect: ' . mysql_error());
if (!mysql_select_db($db_name))
	die('Could not select DB: ' . mysql_error());

if ($_POST['delete']) {
	$id = $_POST['id'];
	$dstring = 'delete from `scholarships` where `id` = "'.$id.'"';
	if (!mysql_query($dstring)){
	    echo "DB Error, could not query the database\n";
	    echo 'MySQL Error: ' . mysql_error();
 	    exit;
	}
	$dmessage = "<h5>Record No. $id has been deleted from the database</h5>";
}

$query = "select * from `scholarships`";
$result = mysql_query($query);
if (!$result) {
    echo "DB Error, could not query the database\n";
    echo 'MySQL Error: ' . mysql_error();
    exit;
}


?>

<?php include 'header.php'; ?>

<table id="scholresults" style="border:1px solid black" >
<thead>
	<tr valign="top">
		<td width = "10em">Delete?</td>
		<td width = "5em">ID</td>
		<td width = "32em">First Name</td>
		<td width = "32em">Last Name</td>
		<td width = "64em">email</td>
		<td width = "16em">telephone</td>
		<td width = "64em">address<br /><img src="blank.png" width="140px" height="1px" /></td>
		<td width = "32em">country</td>
		<td width = "32em">nationality</td>
		<td width = "64em">languages</td>
		<td width = "16em">dob</td>
		<td width = "8em">gender</td>
		<td width = "32em">occupation</td>
		<td width = "32em">area of study</td>
		<td width = "4em">wm05?</td>
		<td width = "4em">wm06?</td>
		<td width = "4em">wm07?</td>
		<td width = "4em">wm08?</td>
		<td width = "16em">prior?</td>
		<td width = "16em">presentation?</td>
		<td width = "16em">howheard</td>
		<td width = "64em">why<img src="blank.png" width="300px" height="1px" /></td>
		<td width = "32em">username</td>
		<td width = "32em">project</td>
		<td width = "32em">projlangs</td>
		<td width = "32em">involvement</td>
		<td width = "32em">contribution<img src="blank.png" width="300px" height="1px" /></td>
		<td width = "8em">sincere</td>
		<td width = "8em">honest</td>
		<td width = "8em">agrees</td>
		<td width = "8em">get visa</td>
		<td width = "8em">pay incedentals</td>
		<td width = "8em">rank</td>
		<td width = "8em">notes</td>
		<td width = "8em">confirmed</td>
		<td width = "8em">confhash</td>
	</tr>
</thead>
<tbody><?
while ($row = mysql_fetch_assoc($result)) {
?>
	<tr valign="top">

		<? foreach ($row as $key => $value) { ?>
	<? if ($key=='id') { ?><td><form action="results.php" method="POST"><input type="submit" name="delete" value="delete"><input type="hidden" name="id" value="<? echo $value; ?>"></form></td><? } ?>
		<td><? echo $value; ?></td>
	<? } ?></tr>
<? } ?>
</tbody>
</table>


