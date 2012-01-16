<?php

define( 'WMSCHOLS', true );

$BASEDIR= dirname(__FILE__);

require_once( "$BASEDIR/includes/init.php" );

$path = $wgRouter->route();
include $path;
?>
