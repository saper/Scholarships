<?php

$submitted = FALSE;
$lang = 'en';

if (isset($_POST['submit'])) {
	$app = new Application();
	$app->submit($_POST);
	$submitted = TRUE;
}

if ( ( isset($_GET['uselang']) ) or ( isset( $_POST['uselang']) ) ) {
	// set lang
	$res = array_merge( $_GET, $_POST );
	$lang = $wgLang->setLang($res);
}

?>

<?php include( 'header.php' ); ?>

<h2><?php echo $wgLang->message('TEXT_PAGE_HEADER'); ?></h2>

<?php if ($mock) {?>
<p class="notice">
This is a mock scholarship application site only, use it just for testing.<br/>
Please <a href="mailto:wikimania-scholarships@wikimedia.org">e-mail us</a>
about any problems you encounter with it.</p>
<?php }?>
<?php 
if ($app->success) {
  echo $wgLang->message('TEXT_APPLICATION_RESPONSE');
}
?>
<?php 
if ($app->haserrors) {
  echo $wgLang->message('TEXT_FORM_ERRORS');
}
?>
<?php if (!$app->success): ?>
<?php echo $wgLang->message('TEXT_INTRO'); ?>
<form action="/?page=apply" method="post">
<input type="hidden" name="lang" id="lang" value="<?php echo $lang; ?>" />
<fieldset>
<legend><?php echo $wgLang->message('TEXT_CONTACT_LEGEND'); ?></legend>
<p <?= $classdivfname; ?>><?php echo $wgLang->message('TEXT_FIRST'); ?> <input type="text" id="fname" name="fname" <?= isset($_POST['fname'])?'value="' .$_POST['fname'] . '"':''; ?> /></p>
<p <?= $classdivlname; ?>><?php echo $wgLang->message('TEXT_LAST'); ?> <input type="text" id="lname" name="lname" <?= isset($_POST['lname'])?'value="' .$_POST['lname'] . '"':''; ?> /></p>
<p <?= $classdivemail; ?>><?php echo $wgLang->message('TEXT_EMAIL'); ?> <input type="text" id="email" name="email" <?= isset($_POST['email'])?'value="' .$_POST['email'] . '"':''; ?> /></p>
<p><?php echo $wgLang->message('TEXT_TELEPHONE'); ?> <input type="text" id="telephone" name="telephone"  <?= isset($_POST['telephone'])?'value="' .$_POST['telephone'] . '"':''; ?> /></p>
<p><?php echo $wgLang->message('TEXT_MAILING'); ?><br/><textarea id="address" name="address" cols="40" rows="3" ><?= isset($_POST['address'])?$_POST['address']:''; ?></textarea></p> 
<p><?php echo $wgLang->message('TEXT_COUNTRY'); ?>
<select id="residence" name="residence">
    <option><?= $wgLang->message('TEXT_SELECT'); ?></option>
    <?php foreach (range(0, count($COUNTRY_NAMES)-1) as $i)
    if ($_POST['residence']==$i+1) {
        printf('<option value="%d" selected="selected">%s</option>\r\n', $i+1, $COUNTRY_NAMES[$i]);
    } else {
        printf('<option value="%d">%s</option>\r\n', $i+1, $COUNTRY_NAMES[$i]);
    }
?>
</select>
</p>  

</fieldset>

<fieldset>
<legend><?php echo $wgLang->message('TEXT_LEGEND_PERSONAL_INFO'); ?></legend>

<p><?php echo $wgLang->message('TEXT_HASPASSPORT'); ?> <input type="radio" id="haspassport" name="haspassport" value="1" <?= ($_POST['haspassport']==1)?'checked = "checked" ':''; ?> /><?php echo $TEXT_YES; ?> <input type="radio" id="haspassport" name="haspassport" value="0" <?= ($_POST['haspassport']==0)?'checked = "checked" ':''; ?> /><?php echo $TEXT_NO; ?></p> 
<p><?php echo $wgLang->message('TEXT_NATIONALITY'); ?>
<select id="nationality" name="nationality">
    <option><?= $wgLang->message('TEXT_SELECT'); ?></option>
    <?php foreach (range(0, count($COUNTRY_NAMES)-1) as $i)
    if ($_POST['nationality']==$i+1) {
        printf('<option value="%d" selected="selected">%s</option>\r\n', $i+1, $COUNTRY_NAMES[$i]);
    } else {
        printf('<option value="%d">%s</option>\r\n', $i+1, $COUNTRY_NAMES[$i]);
    }
?></select>
</p>  

<p><?php echo $wgLang->message('TEXT_AIRPORT'); ?> <input type="text" id="airport" name="airport" <?= isset($_POST['airport'])?'value="' .$_POST['airport'] . '"':''; ?> /></p>
<p><?php echo $wgLang->message('TEXT_LANGUAGE'); ?> <input type="text" id="languages" name="languages" <?= isset($_POST['languages'])?'value="' .$_POST['languages'] . '"':''; ?> /></p>

<p><?php echo $wgLang->message('TEXT_DOB'); ?>
<select id="dd" name="dd">
    <?php foreach (range(1, 31) as $i)
    if ($_POST['dd']==$i) {
    	printf('<option value="%02d" selected="selected">%d</option>', $i, $i); 
    } else {
    	printf('<option value="%02d">%d</option>', $i, $i);
    }
?></select>
<select id="mm" name="mm">
    <?php foreach (range(0, 11) as $i)
    if ($_POST['mm']==$i+1) {
        printf('<option value="%02d" selected="selected">%s</option>', $i+1, $MONTH_NAMES[$i]);
    } else {
        printf('<option value="%02d">%s</option>', $i+1, $MONTH_NAMES[$i]);
    }
	?>
</select> 19<input type="text" id="yy" name="yy" size="1" <?= isset($_POST['yy'])?'value="' .$_POST['yy'] . '"':''; ?> /></p>

<p><?php echo $wgLang->message('TEXT_GENDER'); ?>
	<select id="sex" name="sex">
		<option value="m" <?= ($_POST['sex']=='m')?'selected = "selected" ':''; ?>><?php echo $wgLang->message('TEXT_GENDER_MALE'); ?></option>
		<option value="f" <?= ($_POST['sex']=='f')?'selected = "selected" ':''; ?>><?php echo $wgLang->message('TEXT_GENDER_FEMALE'); ?></option>
		<option value="d" <?= ($_POST['sex']=='d')?'selected = "selected" ':''; ?>><?php echo $wgLang->message('TEXT_GENDER_UNSPECIFIED'); ?></option>
	</select>
</p>
<p><?php echo $wgLang->message('TEXT_OCCUPATION'); ?> <input type="text" id="occupation" name="occupation" <?= isset($_POST['occupation'])?'value="' .$_POST['occupation'] . '"':''; ?> /></p>
<p><?php echo $wgLang->message('TEXT_STUDY_AREA'); ?> <input type="text" id="areaofstudy" name="areaofstudy" <?= isset($_POST['areaofstudy'])?'value="' .$_POST['areaofstudy'] . '"':''; ?> /></p>
</fieldset>

<fieldset>
<legend><?php echo $wgLang->message('TEXT_LEGEND_INTEREST'); ?></legend>
<p><?php echo $wgLang->message('TEXT_ATTENDED_BEFORE'); ?><br/>
<input type="checkbox" id="wm05" name="wm05" value="1" <?= ($_POST['wm05']==1)?'checked = "checked" ':''; ?> style="margin-left: 1em"/> 2005<br />
<input type="checkbox" id="wm06" name="wm06" value="1" <?= ($_POST['wm06']==1)?'checked = "checked" ':''; ?> style="margin-left: 1em"/> 2006<br />
<input type="checkbox" id="wm07" name="wm07" value="1" <?= ($_POST['wm07']==1)?'checked = "checked" ':''; ?> style="margin-left: 1em"/> 2007<br />
<input type="checkbox" id="wm08" name="wm08" value="1" <?= ($_POST['wm08']==1)?'checked = "checked" ':''; ?> style="margin-left: 1em"/> 2008<br />
<input type="checkbox" id="wm09" name="wm09" value="1" <?= ($_POST['wm09']==1)?'checked = "checked" ':''; ?> style="margin-left: 1em"/> 2009<br />
<input type="checkbox" id="wm10" name="wm10" value="1" <?= ($_POST['wm10']==1)?'checked = "checked" ':''; ?> style="margin-left: 1em"/> 2010</p>

<p><input type="checkbox" id="presentation" name="presentation" value="1" <?= ($_POST['presentation']==1)?'checked = "checked" ':''; ?> /><?php echo $wgLang->message('TEXT_SUBMITTED_PRESENTATION'); ?></p>

<p><?php echo $wgLang->message('TEXT_HOW_HEARD'); ?><br/>
<select id="howheard" name="howheard">
<option value="email" <?= ($_POST['howheard']=='email')?'selected = "selected" ':''; ?>><?php echo $wgLang->message('TEXT_HOW_HEARD_1'); ?></option>
<option value="project"" <?= ($_POST['howheard']=='project')?'selected = "selected" ':''; ?>><?php echo $wgLang->message('TEXT_HOW_HEARD_2'); ?>
</option>
<option value="vp"" <?= ($_POST['howheard']=='vp')?'selected = "selected" ':''; ?>><?php echo $wgLang->message('TEXT_HOW_HEARD_3'); ?>
</option>
<option value="wom"" <?= ($_POST['howheard']=='wom')?'selected = "selected" ':''; ?>><?php echo $wgLang->message('TEXT_HOW_HEARD_4'); ?>
</option>
<option value="other"" <?= ($_POST['howheard']=='other')?'selected = "selected" ':''; ?>><?php echo $wgLang->message('TEXT_HOW_HEARD_5'); ?>
</option>
</select></p>

<p <?= $classdivwhy; ?>>
<?php echo $wgLang->message('TEXT_ENRICHMENT'); ?><br/>
<textarea id="why" name="why" cols="80" rows="3"><?= isset($_POST['why'])?$_POST['why']:''; ?></textarea></p>
<p <?= $classdivfuture; ?>>
<?php echo $wgLang->message('TEXT_FUTURE_EXPLANATION'); ?><br />
<textarea id="future" name="future" cols="80" rows="3"><?= isset($_POST['future'])?$_POST['future']:''; ?></textarea></p>
</fieldset>

<fieldset>
<legend><?php echo $wgLang->message('TEXT_LEGEND_PARTICIPATION'); ?></legend>
<p><?php echo $wgLang->message('TEXT_USERNAME'); ?> <input type="text" id="username" name="username" <?= isset($_POST['username'])?'value="' .$_POST['username'] . '"':''; ?> /></p>
<p><?php echo $wgLang->message('TEXT_PRIMARY_PROJECT'); ?> <input type="text" id="project" name="project" <?= isset($_POST['project'])?'value="' .$_POST['project'] . '"':''; ?> /></p>
<p><?php echo $wgLang->message('TEXT_LANGUAGE_VERSION'); ?> <input type="text" id="projectlangs" name="projectlangs" <?= isset($_POST['projectlangs'])?'value="' .$_POST['projectlangs'] . '"':''; ?> /></p>
<p><?php echo $wgLang->message('TEXT_EXTENT_EXPLANATION'); ?><br />
<textarea id="involvement" name="involvement" cols="80" rows="3"><?= isset($_POST['involvement'])?$_POST['involvement']:''; ?></textarea></p>
<p><?php echo $wgLang->message('TEXT_CONTRIBUTION_EXPLANATION'); ?><br />
<textarea id="contribution" name="contribution" cols="80" rows="3"><?= isset($_POST['contribution'])?$_POST['contribution']:''; ?></textarea></p>
</fieldset>

<fieldset>
<legend><?php echo $wgLang->message('TEXT_LEGEND_PARTIAL');?></legend>
<?php echo $wgLang->message('TEXT_PARTIAL_EXPLANATION');?><br />
<p><?php echo $wgLang->message('TEXT_WANTS_PARTIAL'); ?> <input type="radio" id="wantspartial" name="wantspartial" value="1" <?= ($_POST['wantspartial']==1)?'checked = "checked" ':''; ?> /><?php echo $wgLang->message('TEXT_YES'); ?> <input type="radio" id="wantspartial" name="wantspartial" value="0" <?= ($_POST['wantspartial']==0)?'checked = "checked" ':''; ?> /><?php echo $wgLang->message('TEXT_NO'); ?></p>
<p><input type="checkbox" id="canpaydiff" name="canpaydiff" value="1" <?= ($_POST['canpaydiff']==1)?'checked = "checked" ':''; ?> /> <?php echo $wgLang->message('TEXT_CAN_PAY_DIFFERENCE'); ?></p>
</fieldset>

<fieldset>
<legend><?php echo $wgLang->message('TEXT_LEGEND_AGREEMENT'); ?></legend>
<p><input type="checkbox" id="sincere" name="sincere" value="1" <?= ($_POST['sincere']==1)?'checked = "checked" ':''; ?> /> <?php echo $wgLang->message('TEXT_SINCERE'); ?></p>
<p><input type="checkbox" id="willgetvisa" name="willgetvisa" value="1" <?= ($_POST['willgetvisa']==1)?'checked = "checked" ':''; ?> /> <?php echo $wgLang->message('TEXT_WILL_GET_VISA'); ?></p>
<p><input type="checkbox" id="willpayincidentals" name="willpayincidentals" value="1" <?= ($_POST['willpayincidentals']==1)?'checked = "checked" ':''; ?> /> <?php echo $wgLang->message('TEXT_WILL_PAY_INCIDENTALS'); ?></p>
<p><input type="checkbox" id="agreestotravelconditions" name="agreestotravelconditions" value="1" <?= ($_POST['agreestotravelconditions']==1)?'checked = "checked" ':''; ?> /> <?php echo $wgLang->message('TEXT_TRAVEL_CONDITIONS'); ?></p>
<p><?php echo $wgLang->message('TEXT_REVIEW'); ?></p>

<div align="center"><p><?php echo $wgLang->message('TEXT_FAQ'); ?></p></div>
<p><input type="submit" id="submit" name="submit" value="<?php echo $wgLang->message('TEXT_SUBMIT'); ?>" /></p>
</fieldset>
</form>
<?php endif; ?>
</body>
</html>
