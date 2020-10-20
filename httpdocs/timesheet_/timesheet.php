<?php
include 'config.php';
class timesheet {
	function __construct() {
		global $uri_segments;
		global $conn;
		global $base_url;
		$this->uri_segments =& $uri_segments;
		$this->conn =& $conn;
		$this->base_url =& $base_url;

		// echo $uri_segments[3];

		// include 'view.php';

		// if (!isset($this->uri_segments[3])) {
		// 	echo '404, Page Not Found';
		// 	exit;
		// }

		// if (method_exists($this, $this->uri_segments[3])) {
		// 	// echo self::$uri_segments[3]();
		// } else {
		// 	echo '404, Page Not Found';
		// 	exit;
		// }
	}

	public function uri_segments(){
		return $this->uri_segments[3];
	}

	public function weekly()
	{
		echo $this->getWeeks();
	}

	public function getProjectsAssigned($userid)
	{
		// $userid = isset($_GET['userid']) ? $_GET['userid'] : '';
		$query = $this->conn->prepare("SELECT
			*
			FROM
			projects_assigned a
			LEFT JOIN projects b
			ON a.project_id = b.project_id
			WHERE a.user_id = '$userid'"
		);
		$query->execute();
		if($result = $query->fetchAll()) {
			return $result;
		}
		return array();
	}

	public function getProject()
	{
		$date = isset($_GET['date']) ? date('Y-m-d', strtotime($_GET['date'])) : date('Y-m-d');
		$project = isset($_GET['project']) ? $_GET['project'] : '';
		$query = $this->conn->prepare("
			SELECT
			b.project_code,
			SUM(a.hours) as total,
			a.date
			FROM timesheet a
			LEFT JOIN projects b
			ON a.project_code = b.project_id
			WHERE a.project_code = '$project'
			AND a.date = '$date'
			GROUP BY a.project_code, a.hours, a.date
			");
		$query->execute();
		if($result = $query->fetch()) {
			return $result;
		}
		return array();
	}

	public function getProjects()
	{
		$query = $this->conn->prepare("
			SELECT
			*
			FROM
			projects
			");
		$query->execute();
		if($result = $query->fetchAll()) {
			return $result;
		}
		return array();
	}


	public function getUserTimesheet()
	{
		$date = isset($_GET['date']) ? date('d-m-Y', strtotime($_GET['date'])) : date('d-m-Y');
		$userid = isset($_GET['userid']) ? $_GET['userid'] : '';
		// Assuming $date is in format DD-MM-YYYY
		list($day, $month, $year) = explode("-", $date);
		
    	// Get the weekday of the given date
		$wkday = date('l',mktime('0','0','0', $month, $day, $year));

		switch($wkday) {
			case 'Monday': $numDaysToMon = 0; break;
			case 'Tuesday': $numDaysToMon = 1; break;
			case 'Wednesday': $numDaysToMon = 2; break;
			case 'Thursday': $numDaysToMon = 3; break;
			case 'Friday': $numDaysToMon = 4; break;
			case 'Saturday': $numDaysToMon = 5; break;
			case 'Sunday': $numDaysToMon = 6; break;   
		}

   	 	// Timestamp of the monday for that week
		$monday = mktime('0','0','0', $month, $day-$numDaysToMon, $year);

		$seconds_in_a_day = 86400;

    	// Get date for 7 days from Monday (inclusive)
		for($i=0; $i<7; $i++)
		{
			$dates[$i] = date('Y-m-d',$monday+($seconds_in_a_day*$i));
		}
		$query = $this->conn->prepare("SELECT
			*
			FROM
			timesheet
			WHERE user_id = '$userid'
			AND `date` BETWEEN '$dates[0]' AND '$dates[6]'"
		);

		$query->execute();
		if($result = $query->fetchAll()) {
			return $result;
		}
		return array();
	}


	public function getWeeks()
	{
		$date = isset($_GET['date']) ? date('d-m-Y', strtotime($_GET['date'])) : date('d-m-Y');
		$userid = isset($_GET['userid']) ? $_GET['userid'] : '';
		// Assuming $date is in format DD-MM-YYYY
		list($day, $month, $year) = explode("-", $date);
		
    	// Get the weekday of the given date
		$wkday = date('l',mktime('0','0','0', $month, $day, $year));

		switch($wkday) {
			case 'Monday': $numDaysToMon = 0; break;
			case 'Tuesday': $numDaysToMon = 1; break;
			case 'Wednesday': $numDaysToMon = 2; break;
			case 'Thursday': $numDaysToMon = 3; break;
			case 'Friday': $numDaysToMon = 4; break;
			case 'Saturday': $numDaysToMon = 5; break;
			case 'Sunday': $numDaysToMon = 6; break;   
		}

   	 	// Timestamp of the monday for that week
		$monday = mktime('0','0','0', $month, $day-$numDaysToMon, $year);

		$seconds_in_a_day = 86400;

    	// Get date for 7 days from Monday (inclusive)
		for($i=0; $i<7; $i++)
		{
			$dates[$i] = date('Y-m-d',$monday+($seconds_in_a_day*$i));
		}

		return $dates;
	}
}

$class = new timesheet();