<?php
	
class DataAccessLayer {
	protected $db;

	function __construct() {
		global $db_driver, $db_user, $db_pass, $db_host, $db_name;
		$dsn = $db_driver . '://' . $db_user . ':' . $db_pass . '@'
			. $db_host . '/' . $db_name;
		$this->db = &DB::Connect($dsn);
		if (PEAR::isError($this->db)) 
   			die($this->db->getMessage());
		$this->db->setFetchMode(DB_FETCHMODE_ASSOC);
	}

	function __destruct() {
		if (DB::isError($this->db))
			die('Could not connect to database!');
		$this->db->disconnect();
	}


	function GetPhase1GridData($min, $max) {
		return $this->db->getAll("select s.id, s.fname, s.lname, s.email, s.residence, s.exclude, s.sex, 2011-year(s.dob) as age, (s.canpaydiff*s.wantspartial) as partial, c.country_name, coalesce(p1score,0) as p1score from scholarships s 
			left outer join (select *, sum(rank) as p1score from rankings where criterion = 'valid' group by scholarship_id) r2 on s.id = r2.scholarship_id
			left outer join countries c on s.residence = c.id      
			group by s.id, s.fname, s.lname, s.email, s.residence 
			having p1score >= ? and p1score <= ? and s.exclude = 0", array($min, $max)); 
        }

	function GetPhase2GridData($min, $max) {
		$res = $this->db->getAll("select s.id, s.fname, s.lname, s.email, s.residence, s.exclude, s.sex, 2011-year(s.dob) as age, (s.canpaydiff*s.wantspartial) as partial, c.country_name, coalesce(p1score,0) as p1score, coalesce(p2score,0) as p2score, coalesce(nscorers,0) as nscorers from scholarships s 
			left outer join (select scholarship_id, sum(rank) as p2score from rankings where criterion in ('onwiki','offwiki', 'future') group by scholarship_id) r on s.id = r.scholarship_id
			left outer join (select scholarship_id, sum(rank) as p1score from rankings where criterion = 'valid' group by scholarship_id) r2 on s.id = r2.scholarship_id
			left outer join (select scholarship_id, count(distinct user_id) as nscorers from rankings where criterion in ('onwiki','offwiki', 'future') group by scholarship_id) r3 on s.id = r3.scholarship_id			
			left outer join countries c on s.residence = c.id      
			group by s.id, s.fname, s.lname, s.email, s.residence 
			having (p1score >= 3 or s.id > 1152) and p2score >= ? and p2score <= ? and s.exclude = 0
			order by s.id", array($min, $max));
		if (PEAR::isError($res)) 
    			die($res->getMessage());
    		return $res;
	}
		

	function GetScholarship($id) {
		return $this->db->query('select *, s.id, s.residence as acountry, c.country_name, r.country_name as residence_name from scholarships s 
		left outer join countries c on s.nationality = c.id 
		left outer join countries r on s.residence = r.id 
		where s.id = ?', array($id))->fetchRow();
	}

	function GetNextPhase1($id) {
		return $this->db->query("select *, s.id, s.residence as acountry, c.country_name, res.country_name as residence_name, p1score from scholarships s 
			left outer join (select *, sum(rank) as p1self from rankings where criterion = 'valid' and user_id = ? group by scholarship_id) r on s.id = r.scholarship_id
			left outer join (select *, sum(rank) as p1score from rankings where criterion = 'valid' group by scholarship_id) r2 on s.id = r2.scholarship_id
			left outer join countries c on s.nationality = c.id 
			left outer join countries res on s.residence = res.id
			where p1self is null and s.exclude = 0 and ((p1score < 3 and p1score > -3) or p1score is null) 
			order by rand() 
			limit 1;", array($id))->fetchRow();
	}
		
	function GetNextPhase2($id) {
		return $this->db->query("select *, s.id, s.residence as acountry, c.country_name, res.country_name as residence_name, p2self, coalesce(p1score,0) as p1score from scholarships s 
			left outer join (select scholarship_id, sum(rank) as p2self from rankings where criterion in ('offwiki', 'onwiki', 'future') and user_id = ? group by scholarship_id) r on s.id = r.scholarship_id
			left outer join (select scholarship_id, sum(rank) as p1score from rankings where criterion = 'valid' group by scholarship_id) r3 on s.id = r3.scholarship_id
			left outer join countries c on s.nationality = c.id 
			left outer join countries res on s.residence = res.id
			where p2self is null and s.exclude = 0 and (p1score >= 3) 
			order by rand() 
			limit 1;", array($id))->fetchRow();
	}

	function GetCountAllUnrankedPhase1($id) {
		return $this->db->query("select COUNT(*), coalesce(p1self,0) as p1self, coalesce(p1score,0) as p1score from scholarships s 
				left outer join (select scholarship_id, sum(rank) as p1self from rankings where criterion = 'valid' and user_id = ? group by scholarship_id) r on s.id = r.scholarship_id		
				left outer join (select scholarship_id, sum(rank) as p1score from rankings where criterion = 'valid' group by scholarship_id) r2 on s.id = r2.scholarship_id				
				where p1self is null and s.rank >=0 and ((p1score < 3 and p1score > -3)) and s.exclude = 0;", array($id))->fetchRow();
	}		

	function GetCountAllUnrankedPhase2($id) {
		return  $this->db->query("select COUNT(*), p2self, coalesce(p1score,0) as p1score from scholarships s 
				left outer join (select scholarship_id, sum(rank) as p2self from rankings where criterion in ('offwiki', 'onwiki', 'future') and user_id = ? group by scholarship_id) r on s.id = r.scholarship_id
				left outer join (select scholarship_id, sum(rank) as p1score from rankings where criterion = 'valid' group by scholarship_id) r3 on s.id = r3.scholarship_id 
				where p2self is null and (p1score >= 3 or s.id > 1152) and s.exclude = 0;", array($id))->fetchRow();
	}
		
	function GetCountAllPhase1() {
		return $this->db->query("select COUNT(*) from scholarships s where s.exclude = 0;")->fetchRow();
	}
		
	function GetCountAllPhase2() {
		return  $this->db->query("select COUNT(*), coalesce(p1score,0) as p1score from scholarships s 
				left outer join (select scholarship_id, sum(rank) as p1score from rankings where criterion = 'valid' group by scholarship_id) r3 on s.id = r3.scholarship_id 
				where (p1score >= 3 or s.id > 1152) and s.exclude = 0;")->fetchRow();
	}

	function InsertOrUpdateRanking($user_id, $scholarship_id, $criterion, $rank) {
		if ($this->db->query("select * from rankings where user_id = ? and scholarship_id = ? and criterion = ?", array($user_id, $scholarship_id, $criterion))->numRows() > 0) {
			$this->db->query("update rankings set rank = ? where user_id = ? and scholarship_id = ? and criterion = ?", array($rank, $user_id, $scholarship_id, $criterion));
		} else {
			$this->db->query("insert into rankings (rank, user_id, scholarship_id, criterion) values (?, ?, ?, ?)", array($rank, $user_id, $scholarship_id, $criterion));
		}
	}

	function GetPhase1Rankings($id) {
		return $this->db->getAll('select r.scholarship_id, u.username, r.rank, r.criterion from rankings r inner join users u on r.user_id = u.id where r.criterion = "valid" and r.scholarship_id = ?', array($id));
	}
		
	function GetPhase2RankingOfUser($user_id, $scholarship_id, $criterion) {
		$r = $this->db->query("select rank from rankings where user_id = ? and scholarship_id = ? and criterion = ?", array($user_id, $scholarship_id, $criterion));
		$ret = $r->numRows() > 0 ? $r->fetchRow(DB_FETCHMODE_ORDERED) : array(0);
		return $ret[0];
	}
		
	function GetPhase2Rankings($id) {
		return $this->db->getAll('select r.scholarship_id, u.username, r.rank, r.criterion from rankings r inner join users u on r.user_id = u.id where r.scholarship_id = ? and r.criterion in ("onwiki","offwiki","future")', array($id));
	}

	function UpdateNotes($id, $notes) {
		$this->db->query("update scholarships set notes = ? where id = ?", array($notes, $id));
	}

	function UpdateField($field, $id, $value) {
		$query = "update scholarships set " . $field . " = ? where id  = ?";
		$this->db->query($query, array($value, $id));
	}

// Bulk mailing

	function GetPhase1EarlyRejects($start, $howmany) {
		$res = $this->db->getAll("select s.id, s.fname, s.lname, s.email, s.exclude, coalesce(p1score,0) as p1score from scholarships s 
			left outer join (select scholarship_id, sum(rank) as p1score from rankings where criterion = 'valid' group by scholarship_id) r2 on s.id = r2.scholarship_id					     
			group by s.id, s.fname, s.lname, s.email 
			having p1score < 3 and s.exclude = 0
			limit ?,?", array($start, $howmany));
		if (PEAR::isError($res)) 
   			die($res->getMessage());
    		return $res;
	}

// Final scoring 

	function GetFinalScoring($partial) {
		$res = $this->db->getAll("select s.id, s.fname, s.lname, s.email, s.residence, s.exclude, s.sex, 2011-year(s.dob) as age, (s.canpaydiff*s.wantspartial) as partial, c.country_name, coalesce(p1score,0) as p1score, coalesce(nscorers,0) as nscorers, r.onwiki as onwiki, r2.offwiki as offwiki, r3.future as future, 0.5*r.onwiki + 0.15*r2.offwiki + 0.35*r3.future as p2score from scholarships s 
			left outer join (select scholarship_id, avg(rank) as onwiki from rankings where criterion = 'onwiki' group by scholarship_id) r on s.id = r.scholarship_id
			left outer join (select scholarship_id, avg(rank) as offwiki from rankings where criterion = 'offwiki' group by scholarship_id) r2 on s.id = r2.scholarship_id
			left outer join (select scholarship_id, avg(rank) as future from rankings where criterion = 'future' group by scholarship_id) r3 on s.id = r3.scholarship_id
			left outer join (select scholarship_id, sum(rank) as p1score from rankings where criterion = 'valid' group by scholarship_id) r4 on s.id = r4.scholarship_id
			left outer join (select scholarship_id, count(distinct user_id) as nscorers from rankings where criterion in ('onwiki','offwiki', 'future') group by scholarship_id) r5 on s.id = r5.scholarship_id			
			left outer join countries c on s.residence = c.id      
			group by s.id, s.fname, s.lname, s.email, s.residence 
			having p1score >= 3 and s.exclude = 0 and partial = ?
			order by p2score desc", array($partial));
		if (PEAR::isError($res)) 
   			die($res->getMessage());
    		return $res;
	}
		
//		function GetFinalScoring($method = "coalesce(sum(r.onrank),0) + coalesce(sum(r.offrank),0) + (c.country_rank * 6)") {
//			$query = "select s.id, s.fname, s.lname, c.country_name, s.airport, coalesce(sum(r.onrank),0) as sumonrank, coalesce(sum(r.offrank),0) as sumoffrank, c.country_rank, " . $method . " as score from scholarships s
//left outer join (select *, if(criterion='onwiki',rank,0) as onrank, if(criterion='offwiki',rank,0) as offrank from rankings) r on s.id = r.scholarship_id
//left join countries c on s.residence = c.id
//where s.rank =1 and s.exclude = 0
//group by s.id, s.fname, s.lname, c.country_name, s.airport, c.country_rank
//order by score desc;";
//			return $this->db->getAll($query);
//		}

// User administration

	function GetUser($username) {
		return $this->db->query('select id, username, password, email, reviewer from users where username = ? and isvalid = 1', array($username))->fetchRow();
	}

	function GetUsername($id) {
		return $this->db->query('select username from users where id = ?', array($id))->fetchRow();
	}
		
	function GetListofUsers($state) {
		switch ($state) {
			case "all":
				return $this->db->getAll("select * from users");
				break;
			case "reviewer": 
				return $this->db->getAll("select * from users where `reviewer` = 1");
				break;
		}
	}

	function GetUserInfo($user_id) {
		return $this->db->query("select * from `users` where `id` = ?", array($user_id))->fetchrow();
	}

	function IsSysAdmin($user_id) {
		$res = $this->db->query("select `isadmin` from `users` where `id` = ?", array($user_id))->fetchrow();
		return $res['isadmin'];
	}

	function NewUserCreate($answers) {
		$fieldnames = array("username","password","email","reviewer","isvalid","isadmin");
		$this->db->query(
			sprintf("insert into users (%s) values ('%s')", join($fieldnames, ', '), join($answers, "', '")));
		$res = $this->db->insert_id;
		return $res;
	}

	function UpdateUserInfo($answers, $id) {
		$fieldnames = array("username","email","reviewer","isvalid","isadmin");
		$query = "update users set ";
		for ($i=0 ; $i <= 4 ; $i++) {
			$field = $fieldnames[$i];
			$query .= ($i==0) ? '' : ',';
			$query .= '`' . $field . '`="' . $answers[$field] . '" ';
		}
		$query .= "where `id` = ?";
		$this->db->query($query, array($id));
	}
	
	function UpdatePassword($oldpw, $newpw, $id, $force = NULL) {
		if ($force==1) {
			$this->db->query("update users set password = ? where id = ?", array(sha1($newpw),$id));
			return 1;
		} else {
			$userdata = $this->db->query('select password from users where id = ?', array($id))->fetchRow();
			if ($userdata['password'] == sha1($oldpw)) {
				$this->db->query("update users set password = ? where id = ?", array(sha1($newpw),$id));
				return 1;
			} else {
				return 0;
			}
		}
	}

// Country administration
		
	function GetListofCountries($order = "country_name") {
            return $this->db->getAll("select c.id, c.country_name, c.country_rank, s.sid from countries c left join (select count(`id`) as sid, residence as attendees from scholarships where rank = 1 and exclude = 0 group by residence) s on c.id = s.attendees order by ?;", array($order));
	}
	
	function UpdateCountryRank($id, $newrank) {
		$this->db->query("update countries set country_rank = ? where id = ?", array($newrank, $id));
	}

	function GetCountryInfo($country_id) {
		return $this->db->query("select * from `countries` where `id` = ?", array($country_id))->fetchrow();
	}

		
// not in use in regular review system

//		function GetRawGridData($min, $max) {
//			return $this->db->getAll('select s.id, s.fname, s.lname, s.email, s.nationality, s.rank from scholarships s');
//		}
//		function GetAltGridData($min, $max) {
//			return $this->db->getAll('select s.id, s.fname, s.lname, s.nationality, s.country, s.languages, coalesce(sum(r.rank), 0) as rank, s.rank as autorank from scholarships s left outer join rankings r on s.id = r.scholarship_id where r.criterion in ("onwiki" , "offwiki" , "nationality" , "representation", "special") group by s.id, s.fname, s.lname, s.email, s.nationality having coalesce(sum(r.rank), 0) >= ? and coalesce(sum(r.rank), 0) <= ? and s.rank >= 0', array($min, $max));
//		}
//
//		function GetRankedGridData($min, $max) {
//			return $this->db->getAll('select s.id, s.fname, s.lname, s.email, s.nationality, coalesce(sum(r.rank), 0) as rank, s.rank as autorank from scholarships s left outer join rankings r on s.id = r.scholarship_id where r.criterion = "valid" group by s.id, s.fname, s.lname, s.email, s.nationality having coalesce(sum(r.rank), 0) >= ? and coalesce(sum(r.rank), 0) <= ? and s.rank >= 0 order by coalesce(sum(r.rank), 0) desc', array($min, $max));
//		}
		
}
?>
