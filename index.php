<?php

define( 'WMSCHOLS', true );

$BASEDIR= dirname(__FILE__);

require_once( "$BASEDIR/includes/init.php" );

$page = 'apply';

if (isset($_GET['page'])) {
	$page = $_GET['page'];
}

if ( $page == 'apply' ) {
	include $TEMPLATEDIR . 'apply.php';
} else if (time() < $open_time) {
	include $TEMPLATEDIR . 'apply_not_yet.php';
} else if (time() > $close_time) {
        if ($chapters_application) {
#               $COUNTRY_NAMES = $COUNTRY_NAMES_CHAPTERS;
		include $TEMPLATEDIR . 'apply.php';
        } else {
                include $TEMPLATEDIR . 'apply_done.php';
#                exit;
        }
} else {
	include $TEMPLATEDIR . 'apply.php';
}



?>
