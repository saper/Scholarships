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
                $req = explode($BASEURL, $_SERVER['REQUEST_URI']);

                if ( isset($req[1] ) ) {
			$page = $req[1];
		} else { 
			$page = null;
		}

		if ( isset( $req[2] ) ) {
			$action = $req[2];
		} else {
			$action = null;
		}

		if ( isset( $req[3] ) ) {
			$action2 = $req[3];
		} else {
			$action2 = null;
		}

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
		} else {
			return $defaultRoute;
		}
	}	
}	
