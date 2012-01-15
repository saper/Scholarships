<?php

$INCLUDEDIR = $BASEDIR . '/includes/';

require_once( "$INCLUDEDIR/config.php" );
require_once( "$INCLUDEDIR/db.php" );
require_once( "$INCLUDEDIR/variables.php" );
require_once( "$INCLUDEDIR/schema.php" );
require_once( "$INCLUDEDIR/Lang.php" );
require_once( "$INCLUDEDIR/Application.php" );
require_once( "$INCLUDEDIR/Router.php" );

// PEAR DB
require_once( 'DB.php' );

$wgLang = new Lang();
$wgRouter = new Router();

?>
