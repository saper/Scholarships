<?php

if ( ( isset($_GET['uselang']) ) or ( isset( $values['uselang']) ) ) {
	// set lang
	$res = array_merge( $_GET, $_POST );
	$lang = $wgLang->setLang($res);
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-us">
<head>
	<meta http-equiv="Content-language" content="en-us"/>
	<meta http-equiv="Content-type" content="application/xhtml+xml; charset=utf-8"/>
	<title><?php echo $wgLang->message('header-title');?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo $TEMPLATEBASE; ?>css/global.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo $TEMPLATEBASE; ?>css/attendee.css"/>
	<link rel="shortcut icon" type="image/png" href="/favicon.png"/>
</head>
<body>
<div id="langbar">
<ul class="langlist">
<li><a href="<?php echo $BASEURL; ?>?uselang=en">en</a></li>
<li><a href="<?php echo $BASEURL; ?>?uselang=pl">pl</a></li>
<li class="last"><a href="<?php echo $BASEURL; ?>translate">help translate</a></li>
</ul>
</div>
<h1><a id="banner" href="<?php echo $BASEURL; ?>" title="Wikimania 2012"><img src="<?php echo $TEMPLATEBASE;?>images/wm2012banner.png" alt="Wikimania 2012"/></a></h1>
