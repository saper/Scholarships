<?php

class Router {

	public function isValid($page) {
		global $routes;
		if ( array_key_exists( $page, $routes ) ) {
			return true;
		} 
		return false;
	}

	public function route() {
		global $defaultRoute, $routes, $BASEURL;
		$parts = explode('?', $_SERVER['REQUEST_URI']);
                $mainreq = explode($BASEURL, $parts[0]);
		$req = explode('/', $mainreq[1]);
		if ($req[0] == "index.php")
			array_shift($req);

		$page = isset($req[0]) ? $req[0] : null;
		$action = isset($req[1]) ? $req[1] : null;
		$action2 = isset($req[2]) ? $req[2] : null;

		$path = '';		
		if ( strlen( $page ) > 0 ) {
			$path = $path . $page;
		}
		if ( strlen( $action ) > 0 ) {
			$path = $path . '/' . $action;
		}

		if ( strlen( $action2 ) > 0 ) {
			$path = $path . '/' . $action2;
		}

		if ( $this->isValid( $path ) ) {
			return $routes[$path];
		} elseif ( $page == 'review' ) {
			return $routes['user/login'];
		} else {
			return $defaultRoute;
		}
	}	
}	
