<?php

class Application {

	public $haserrors;
	public $numerrors;
	public $errors;
	public $success;

	function __construct() {
		$this->haserrors = FALSE;
		$this->numerrors = 0;
		$this->errors = array();
		$this->success = FALSE;
	}

	function validate($data) {
		$this->errors = array();

		if (isset($data['submit'])) {
			if ($data['fname'] == '' && $_POST['lname'] == '') {
				if ( $data['fname'] == '' ) {
					array_push( $this->errors, 'fname' );
				}
				if ( $data['lname'] == '') {
					array_push( $this->errors, 'lname' );
				}			
			}
			if ($data['why'] == '') {
				array_push( $this->errors, 'why' );
			}
			if ($data['future'] == '') {
				array_push( $this->errors, 'future' );
			}
			if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/', $data['email'])) {
				array_push( $this->errors, 'email' );
			}
		}

		$this->numerrors = count($this->errors);
		if ($this->numerrors > 0 ) {
			$this->haserrors = TRUE;
		}
		return $this->haserrors;
	}

	function submit($data) {
                global $db_user, $db_pass, $db_host, $db_name, $FIELDS, $columns;

		$db = "mysql://$db_user:$db_pass@$db_host/$db_name";

		$haserrors = $this->validate($data);

		if ((isset($data['submit'])) && ($haserrors === FALSE) ) {
			if (!($db = mysql_connect($db_host, $db_user, $db_pass))) {
				print mysql_error();
				die('Could not connect: ' . mysql_error());
			}
			if (!mysql_select_db($db_name)) {
				print mysql_error();
				die('Could not select DB: ' . mysql_error());
			}

                       $colnames = array("fname", "lname", "email", "telephone", "address", "residence", "nationality", "haspassport", "airport", "languages", "sex", "occupation", "areaofstudy", "wm05", "wm06", "wm07", "wm08", "wm09", "wm10", "wm11", "presentation", "howheard", "why", "username", "project", "projectlangs", "involvement", "contribution", "sincere", "willgetvisa", "willpayincidentals", "agreestotravelconditions", "dob", "rank");

			foreach ($colnames as $i) {
				if ( ( isset( $data[$i] ) ) or ( $i == 'dob' ) ) {
					if ( ( $i == 'dob' ) && ( isset( $data['yy'] ) ) && ( isset( $data['mm'] ) ) && ( isset( $data['dd'] ) ) ) {
						$date = sprintf("19%d-%d-%d", $data['yy'], $data['mm'], $data['dd']);
						$time = strtotime($date);
						if ( ( $time < time() ) && ( $time > strtotime( '1875-01-01' ) ) ) {
							$answers['dob'] = $date;
						} else {
							$answers['dob'] = NULL;
						}
					} elseif ( strlen($data[$i]) > 0 ) {
						$answers[$i] = mysql_real_escape_string($data[$i]);
					} elseif ( is_int( $data[$i] ) ) {
						$answers[$i] = $data[$i];
					} else {
						$answers[$i] = NULL;
					}
				}
			}

			$answers['rank'] = 1;

			$fields = array();
			$subst = '';
			$values = array();
			foreach ( $colnames as $c ) {
				if ( isset( $answers[$c] ) ) {
					if ( ( $columns[$c]['type'] == 'varchar' ) or ( $columns[$c]['type'] == 'text' )  or ( $columns[$c]['type'] == 'date' ) ) {
						array_push( $fields, $c );
						$subst .= "'%s', ";
						array_push( $values, $answers[$c] );
					} elseif ( ( $columns[$c]['type'] == 'tinyint' ) or ( $columns[$c]['type'] == 'int' ) ) {
						array_push( $fields, $c );
						$subst .= "%d, ";
						array_push( $values, $answers[$c] );
					} elseif ( ( $columns[$c]['type'] == 'enum' ) and ( in_array( trim($answers[$c]), $columns[$c]['options'] ) ) ) {
						array_push( $fields, $c );
						$subst .= "'%s', ";
						array_push( $values, $answers[$c] );
					}
				}
			}
			$fieldnames = join($fields, ", ");
			$subst = rtrim($subst, ', ');
			$prepare = "insert into scholarships (%s) values($subst)";

			$query = vsprintf($prepare,
			  array_merge(array($fieldnames), $values));

			if (!mysql_query($query)) die('Could not perform query: ' . mysql_error());
			mysql_close($db);

			$this->emailResponse($answers);

			$this->success = TRUE;
		}
	}

	function emailResponse($answers) {
		global $wgLang;


        	$message = $wgLang->message('TEXT_EMAIL_RESPONSE');
                $message = preg_replace('/\$1/',$answers['fname'] . ' ' . $answers['lname'], $message);
        
                mail($answers['email'],
                 $wgLang->message('TEXT_EMAIL_SUBJECT'),
                 wordwrap($message, 72),
                 "From: Wikimania Scholarships <wikimania-scholarships@wikimedia.org>\r\n" .
                 "MIME-Version: 1.0\r\n" . "X-Mailer: Wikimania registration system\r\n" .
                 "Content-type: text/plain; charset=utf-8\r\n" .
                 "Content-Transfer-Encoding: 8bit");
	}

	function isOpen() {
		$close_time = gmmktime(0, 0, 0, /*february*/ 2, /*1st*/ 1, 2012);
		if (time() > $close_time) {
			if ($chapters_application) {
				$COUNTRY_NAMES = $COUNTRY_NAMES_CHAPTERS;
			} else 	{
				return 'done';
			}
		}
	}

}

?>
