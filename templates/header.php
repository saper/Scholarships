<?php

if ( ( isset($_GET['uselang']) ) or ( isset( $values['uselang']) ) ) {
	// set lang
	$res = array_merge( $_GET, $_POST );
	$lang = $wgLang->setLang($res);
}

?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?= $lang ?>" lang="<?= $lang ?>" class="no-js">
<head>
	<meta http-equiv="Content-language" content="<?= $lang ?>"/>
	<meta http-equiv="Content-type" content="application/xhtml+xml; charset=utf-8"/>
	<title><?php echo $wgLang->message('header-title');?></title>
	<link rel="stylesheet" type="text/css" href="<?= $TEMPLATEBASE ?>css/style.css"/>
	<link rel="shortcut icon" type="image/png" href="/favicon.png"/>
</head>
<body>
<div id="langbar">
<ul class="langlist">
<li><a href="<?= $basepath ?>?uselang=de">de</a></li>
<li><a href="<?= $basepath ?>?uselang=en">en</a></li>
<li><a href="<?= $basepath ?>?uselang=pl">pl</a></li>
<li class="last"><a href="<?= $BASEURL ?>translate"><?= $wgLang->message('help-translate') ?></a></li>
</ul>
</div>
<h1><a id="banner" href="<?= $BASEURL ?>" title="Wikimania 2012"><img src="<?php echo $TEMPLATEBASE;?>images/wm2012banner.png" alt="Wikimania 2012"/></a></h1>
