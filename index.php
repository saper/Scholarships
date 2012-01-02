<?php

define( 'WMSCHOLS', true );

$BASEDIR= dirname(__FILE__);

require_once( "$BASEDIR/includes/init.php" );

if (time() < $open_time) {
	include $TEMPLATEDIR . 'apply_not_yet.php';
} else if (time() > $close_time) {
        include $TEMPLATEDIR . 'apply_done.php';
} else {
	include $TEMPLATEDIR . 'apply.php';
}

?>
