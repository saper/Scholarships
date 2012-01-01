<?php

$submitted = FALSE;
$lang = 'en';
$app = new Application();

function haserror( $field, $app ) {
	if ( in_array( $field, $app->errors ) ) {
		return ' class="fieldWithErrors"';
	} else {
		return '';
	}
}

if (isset($_POST['submit'])) {
	$app->submit($_POST);
	if ($app->success === TRUE) {
		$submitted = TRUE;
	}
}

if ( ( isset($_GET['uselang']) ) or ( isset( $values['uselang']) ) ) {
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
if ($app->success) {?>
<h1><?php echo $wgLang->message('TEXT_THANKS'); ?></h1>
<div id="appresponse">
<?php  echo $wgLang->message('TEXT_APPLICATION_RESPONSE'); ?>
</div>
<?
}
?>
<?php 
if ($app->haserrors) {
  echo '<div class="errors">';
  echo $wgLang->message('TEXT_FORM_ERRORS');
  echo '</div>';
}
?>
<?php 
$defaults = array(
        'fname' => '',
        'lname' => '',
        'email' => '',
        'telephone' => '',
        'address' => '',
        'residence' => 1,
	'haspassport'=> 0,
	'nationality' => 1,
	'airport' => '',
	'language' => '',
	'yy' => '',
	'sex' => 'd',
	'occupation' => '',
	'areaofstudy' => '',
	'wm05' => 0,
	'wm06' => 0,
	'wm07' => 0,
	'wm08' => 0,
	'wm09' => 0,
	'wm10' => 0,
	'wm11' => 0,
	'presentation' => 0,
	'howheard' => '',
	'why' => '',
	'future' => '',
	'username' => '',
	'project' => '',
	'projectlangs' => '',
	'involvement' => '',
	'contribution' => '',
	'wantspartial' => 0,
	'canpaydiff' => 0,
	'sincere' => 0,
	'willgetvisa' => 0,
	'willpayincidentals' => 0,
	'agreestotravelconditions' => 0,
	'mm' => 1,
	'dd' => 1
);
$values = array_merge( $defaults, $_POST );
?>
<?php 
if ($submitted != TRUE) { 
?>
<?php echo $wgLang->message('TEXT_INTRO'); ?>
<form action="/?page=apply" method="post">
<label class="required">Required field</label><br/><br/>
<input type="hidden" name="lang" id="lang" value="<?php echo $lang; ?>" />
<fieldset>
<legend><?php echo $wgLang->message('TEXT_CONTACT_LEGEND'); ?></legend>
<p <?php echo haserror('fname', $app); ?>><label class='required'><?php echo $wgLang->message('TEXT_FIRST'); ?></label> <input type="text" id="fname" name="fname" <?= isset($values['fname'])?'value="' . $values['fname'] . '"':''; ?> /></p>
<p <?php echo haserror('lname', $app); ?>><label class='required'><?php echo $wgLang->message('TEXT_LAST'); ?></label> <input type="text" id="lname" name="lname" <?= isset($values['lname'])?'value="' . $values['lname'] . '"':''; ?> /></span></p>
<p <?php echo haserror('email', $app); ?>><label class='required'><?php echo $wgLang->message('TEXT_EMAIL'); ?></label> <input type="text" id="email" name="email" <?= isset($values['email'])?'value="' . $values['email'] . '"':''; ?> /></span></p>
<p><?php echo $wgLang->message('TEXT_TELEPHONE'); ?> <input type="text" id="telephone" name="telephone"  <?= isset($values['telephone'])?'value="' . $values['telephone'] . '"':''; ?> /></p>
<p><?php echo $wgLang->message('TEXT_MAILING'); ?><br/><textarea id="address" name="address" cols="40" rows="3" ><?= isset($values['address'])? $values['address']:''; ?></textarea></p> 
<p><?php echo $wgLang->message('TEXT_COUNTRY'); ?>
<select id="residence" name="residence">
    <option><?= $wgLang->message('TEXT_SELECT'); ?></option>
    <?php foreach (range(0, count($COUNTRY_NAMES)-1) as $i)
    if ($values['residence']==$i+1) {
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

<p><?php echo $wgLang->message('TEXT_HASPASSPORT'); ?> <input type="radio" id="haspassport" name="haspassport" value="1" <?= ($values['haspassport']==1)?'checked = "checked" ':''; ?> /><?php echo $wgLang->message('TEXT_YES'); ?> <input type="radio" id="haspassport" name="haspassport" value="0" <?= ($values['haspassport']==0)?'checked = "checked" ':''; ?> /><?php echo $wgLang->message('TEXT_NO'); ?></p> 
<p><?php echo $wgLang->message('TEXT_NATIONALITY'); ?>
<select id="nationality" name="nationality">
    <option><?= $wgLang->message('TEXT_SELECT'); ?></option>
    <?php foreach (range(0, count($COUNTRY_NAMES)-1) as $i)
    if ($values['nationality']==$i+1) {
        printf('<option value="%d" selected="selected">%s</option>\r\n', $i+1, $COUNTRY_NAMES[$i]);
    } else {
        printf('<option value="%d">%s</option>\r\n', $i+1, $COUNTRY_NAMES[$i]);
    }
?></select>
</p>  

<p><?php echo $wgLang->message('TEXT_AIRPORT'); ?> <input type="text" id="airport" name="airport" <?= isset($values['airport'])?'value="' .$values['airport'] . '"':''; ?> /></p>
<p><?php echo $wgLang->message('TEXT_LANGUAGE'); ?> <input type="text" id="languages" name="languages" <?= isset($values['languages'])?'value="' .$values['languages'] . '"':''; ?> /></p>

<p><?php echo $wgLang->message('TEXT_DOB'); ?>
<select id="dd" name="dd">
    <?php foreach (range(1, 31) as $i)
    if ($values['dd']==$i) {
    	printf('<option value="%02d" selected="selected">%d</option>', $i, $i); 
    } else {
    	printf('<option value="%02d">%d</option>', $i, $i);
    }
?></select>
<select id="mm" name="mm">
    <?php foreach (range(0, 11) as $i)
    if ($values['mm']==$i+1) {
        printf('<option value="%02d" selected="selected">%s</option>', $i+1, $MONTH_NAMES[$i]);
    } else {
        printf('<option value="%02d">%s</option>', $i+1, $MONTH_NAMES[$i]);
    }
?>
</select> 19<input type="text" id="yy" name="yy" size="1" <?= isset($values['yy'])?'value="' .$values['yy'] . '"':''; ?> /></p>

<p><?php echo $wgLang->message('TEXT_GENDER'); ?>
	<select id="sex" name="sex">
		<option value="m" <?= ($values['sex']=='m')?'selected = "selected" ':''; ?>><?php echo $wgLang->message('TEXT_GENDER_MALE'); ?></option>
		<option value="f" <?= ($values['sex']=='f')?'selected = "selected" ':''; ?>><?php echo $wgLang->message('TEXT_GENDER_FEMALE'); ?></option>
		<option value="d" <?= ($values['sex']=='d')?'selected = "selected" ':''; ?>><?php echo $wgLang->message('TEXT_GENDER_UNSPECIFIED'); ?></option>
	</select>
</p>
<p><?php echo $wgLang->message('TEXT_OCCUPATION'); ?> <input type="text" id="occupation" name="occupation" <?= isset($values['occupation'])?'value="' .$values['occupation'] . '"':''; ?> /></p>
<p><?php echo $wgLang->message('TEXT_STUDY_AREA'); ?> <input type="text" id="areaofstudy" name="areaofstudy" <?= isset($values['areaofstudy'])?'value="' .$values['areaofstudy'] . '"':''; ?> /></p>
</fieldset>

<fieldset>
<legend><?php echo $wgLang->message('TEXT_LEGEND_INTEREST'); ?></legend>
<p><?php echo $wgLang->message('TEXT_ATTENDED_BEFORE'); ?><br/>
<input type="checkbox" id="wm05" name="wm05" value="1" <?= ($values['wm05']==1)?'checked = "checked" ':''; ?> style="margin-left: 1em"/> 2005<br />
<input type="checkbox" id="wm06" name="wm06" value="1" <?= ($values['wm06']==1)?'checked = "checked" ':''; ?> style="margin-left: 1em"/> 2006<br />
<input type="checkbox" id="wm07" name="wm07" value="1" <?= ($values['wm07']==1)?'checked = "checked" ':''; ?> style="margin-left: 1em"/> 2007<br />
<input type="checkbox" id="wm08" name="wm08" value="1" <?= ($values['wm08']==1)?'checked = "checked" ':''; ?> style="margin-left: 1em"/> 2008<br />
<input type="checkbox" id="wm09" name="wm09" value="1" <?= ($values['wm09']==1)?'checked = "checked" ':''; ?> style="margin-left: 1em"/> 2009<br />
<input type="checkbox" id="wm10" name="wm10" value="1" <?= ($values['wm10']==1)?'checked = "checked" ':''; ?> style="margin-left: 1em"/> 2010<br />
<input type="checkbox" id="wm10" name="wm10" value="1" <?= ($values['wm11']==1)?'checked = "checked" ':''; ?> style="margin-left: 1em"/> 2011</p>

<p><input type="checkbox" id="presentation" name="presentation" value="1" <?= ($values['presentation']==1)?'checked = "checked" ':''; ?> /><?php echo $wgLang->message('TEXT_SUBMITTED_PRESENTATION'); ?></p>

<p><?php echo $wgLang->message('TEXT_HOW_HEARD'); ?><br/>
<select id="howheard" name="howheard">
<option value="email" <?= ($values['howheard']=='email')?'selected = "selected" ':''; ?>><?php echo $wgLang->message('TEXT_HOW_HEARD_1'); ?></option>
<option value="project"" <?= ($values['howheard']=='project')?'selected = "selected" ':''; ?>><?php echo $wgLang->message('TEXT_HOW_HEARD_2'); ?>
</option>
<option value="vp"" <?= ($values['howheard']=='vp')?'selected = "selected" ':''; ?>><?php echo $wgLang->message('TEXT_HOW_HEARD_3'); ?>
</option>
<option value="wom"" <?= ($values['howheard']=='wom')?'selected = "selected" ':''; ?>><?php echo $wgLang->message('TEXT_HOW_HEARD_4'); ?>
</option>
<option value="other"" <?= ($values['howheard']=='other')?'selected = "selected" ':''; ?>><?php echo $wgLang->message('TEXT_HOW_HEARD_5'); ?>
</option>
</select></p>

<p <?php echo haserror('why', $app); ?>><label class='required'>
<?php echo $wgLang->message('TEXT_ENRICHMENT'); ?></label><br/>
<textarea id="why" name="why" cols="80" rows="3"><?= isset($values['why'])?$values['why']:''; ?></textarea></p>
<p <?php echo haserror('future', $app); ?>><label class='required'>
<?php echo $wgLang->message('TEXT_FUTURE_EXPLANATION'); ?></label><br />
<textarea id="future" name="future" cols="80" rows="3"><?= isset($values['future'])?$values['future']:''; ?></textarea></span></p>
</fieldset>

<fieldset>
<legend><?php echo $wgLang->message('TEXT_LEGEND_PARTICIPATION'); ?></legend>
<p><?php echo $wgLang->message('TEXT_USERNAME'); ?> <input type="text" id="username" name="username" <?= isset($values['username'])?'value="' .$values['username'] . '"':''; ?> /></p>
<p><?php echo $wgLang->message('TEXT_PRIMARY_PROJECT'); ?> <input type="text" id="project" name="project" <?= isset($values['project'])?'value="' .$values['project'] . '"':''; ?> /></p>
<p><?php echo $wgLang->message('TEXT_LANGUAGE_VERSION'); ?> <input type="text" id="projectlangs" name="projectlangs" <?= isset($values['projectlangs'])?'value="' .$values['projectlangs'] . '"':''; ?> /></p>
<p><?php echo $wgLang->message('TEXT_EXTENT_EXPLANATION'); ?><br />
<textarea id="involvement" name="involvement" cols="80" rows="3"><?= isset($values['involvement'])?$values['involvement']:''; ?></textarea></p>
<p><?php echo $wgLang->message('TEXT_CONTRIBUTION_EXPLANATION'); ?><br />
<textarea id="contribution" name="contribution" cols="80" rows="3"><?= isset($values['contribution'])?$values['contribution']:''; ?></textarea></p>
</fieldset>

<fieldset>
<legend><?php echo $wgLang->message('TEXT_LEGEND_PARTIAL');?></legend>
<?php echo $wgLang->message('TEXT_PARTIAL_EXPLANATION');?><br />
<p><?php echo $wgLang->message('TEXT_WANTS_PARTIAL'); ?> <input type="radio" id="wantspartial" name="wantspartial" value="1" <?= ($values['wantspartial']==1)?'checked = "checked" ':''; ?> /><?php echo $wgLang->message('TEXT_YES'); ?> <input type="radio" id="wantspartial" name="wantspartial" value="0" <?= ($values['wantspartial']==0)?'checked = "checked" ':''; ?> /><?php echo $wgLang->message('TEXT_NO'); ?></p>
<p><input type="checkbox" id="canpaydiff" name="canpaydiff" value="1" <?= ($values['canpaydiff']==1)?'checked = "checked" ':''; ?> /> <?php echo $wgLang->message('TEXT_CAN_PAY_DIFFERENCE'); ?></p>
</fieldset>

<fieldset>
<legend><?php echo $wgLang->message('TEXT_LEGEND_AGREEMENT'); ?></legend>
<p><input type="checkbox" id="sincere" name="sincere" value="1" <?= ($values['sincere']==1)?'checked = "checked" ':''; ?> /> <?php echo $wgLang->message('TEXT_SINCERE'); ?></p>
<p><input type="checkbox" id="willgetvisa" name="willgetvisa" value="1" <?= ($values['willgetvisa']==1)?'checked = "checked" ':''; ?> /> <?php echo $wgLang->message('TEXT_WILL_GET_VISA'); ?></p>
<p><input type="checkbox" id="willpayincidentals" name="willpayincidentals" value="1" <?= ($values['willpayincidentals']==1)?'checked = "checked" ':''; ?> /> <?php echo $wgLang->message('TEXT_WILL_PAY_INCIDENTALS'); ?></p>
<p><input type="checkbox" id="agreestotravelconditions" name="agreestotravelconditions" value="1" <?= ($values['agreestotravelconditions']==1)?'checked = "checked" ':''; ?> /> <?php echo $wgLang->message('TEXT_TRAVEL_CONDITIONS'); ?></p>
<p><?php echo $wgLang->message('TEXT_REVIEW'); ?></p>

<div align="center"><p><?php echo $wgLang->message('TEXT_FAQ'); ?></p></div>
<p><input type="submit" id="submit" name="submit" value="<?php echo $wgLang->message('TEXT_SUBMIT'); ?>" /></p>
</fieldset>
</form>
<?php 
} 
?>
</body>
</html>
