<?php

class Application {

	public $haserrors;
	public $success;

	function __construct() {
		$this->haserrors = FALSE;
		$this->success = FALSE;
	}

	function validate($data) {
		$haserrors = false;

		if (isset($data['submit'])) {
			if ($data['fname'] == '' && $_POST['lname'] == '') {
				if($data['fname'] == '') $classdivfname = ' class="fieldWithErrors"';
				if($data['lname'] == '') $classdivlname = ' class="fieldWithErrors"';
				$haserrors = "true";
			}
			if ($data['why'] == '') {
				$classdivwhy = ' class="fieldWithErrors"';
#				$haserrors = "true";
			}
			if ($data['future'] == '') {
				$classdivfuture = ' class="fieldWithErrors"';
#				$haserrors = "true";
			}
			if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/', $data['email'])) {
				$classdivemail = ' class="fieldWithErrors"';
				$haserrors = "true";
			}
		}

		$this->haserrors = $haserrors;
		return $haserrors;
	}

	function submit($data) {
                global $db_user, $db_pass, $db_host, $db_name, $FIELDS;

		$db = "mysql://$db_user:$db_pass@$db_host/$db_name";

		$haserrors = $this->validate($data);

		if ((isset($data['submit'])) && (!$haserrors) ) {
			if (!($db = mysql_connect($db_host, $db_user, $db_pass))) {
				die('Could not connect: ' . mysql_error());
			}
			if (!mysql_select_db($db_name)) {
				die('Could not select DB: ' . mysql_error());
			}

			foreach ($FIELDS as $i)
				$answers[$i] = mysql_real_escape_string($data[$i]);

				$answers['dob'] = sprintf("19%s-%s-%s", $data['yy'], $_POST['mm'], $_POST['dd']);
				$rank = 1;
				if ($answers['fname'] == '' && $answers['lname'] == '')
					$rank--;
				if ($answers['why'] == '')
					$rank--;
				if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/',$answers['email']))
					$rank--;
					$answers['rank'] = $rank;  

				$query = sprintf("insert into scholarships (%s) values ('%s')",
				  join($FIELDS, ', '), join($answers, "', '"));

				if (!mysql_query($query)) die('Could not perform query: ' . mysql_error());
					mysql_close($db);

//				self::emailResponse($answers);

				$submitted = true;
			}
		$this->success = TRUE;
	}

	function emailResponse($answers) {
        	$message = <<<EOT
                 $TEXT_EMAIL_RESPONSE
EOT;

                $message = preg_replace('/\$1/',$answers['fname'] . ' ' . $answers['lname'], $message);
                $message = preg_replace('/\$2/',$answers['fname'], $message);
                $message = preg_replace('/\$3/',$answers['lname'], $message);
                $message = preg_replace('/\$4/',$answers['email'], $message);
        
                mail($answers['email'],
                 $TEXT_EMAIL_SUBJECT,
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
