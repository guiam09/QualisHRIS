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
		// return $this->uri_segments[2];
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
		session_start();
		$date = isset($_POST['date']) ? explode(" - ", urldecode($_POST['date'])) : array(date('Y-m-d'), date('Y-m-d', strtotime(date('Y-m-d'). '+1 day')));
		$date1 = date('Y-m-d', strtotime($date[0]));
		$date2 = date('Y-m-d', strtotime($date[1]));
		$project = isset($_POST['project']) ? $_POST['project'] : '';
		$work_type = isset($_POST['work_type']) ? urldecode($_POST['work_type']) : '';
		$location = isset($_POST['location']) ? urldecode($_POST['location']) : '';
		$taskCode = isset($_POST['taskCode']) ? urldecode($_POST['taskCode']) : '';
		$userid = isset($_POST['userid']) ? trim(urldecode($_POST['userid'])) : '';

		$and1 = "";
		$and2 = "";
		$and3 = "";
		$and4 = "";
		$user = "";
		$count = 0;
		if (!empty($userid)) {
			$user = "AND a.user_id = '$userid'";
			$count++;
		}
		$projectname = '';
		if (!empty($project)) {
			$and1 = "AND a.project_code = '$project'";
			$count++;
			$query2 = $this->conn->prepare("
				SELECT
				*
				FROM
				projects
				WHERE project_id = '$project'
				");

			$query2->execute();
			$result2 = $query2->fetch();
			$projectname = $result2['project_code'];
		}

		if (!empty($location)) {
			$and2 = "AND a.location LIKE '%$location%'";
			$count++;
		}

		if (!empty($work_type)) {
			$and3 = "AND a.work_type LIKE '%$work_type%'";
			$count++;
		}

		if (!empty($taskCode)) {
			$and4 = "AND a.task_code LIKE '%$taskCode%'";
			$count++;
		}

		$query = $this->conn->prepare("
			SELECT
			SUM(a.hours) as total
			FROM timesheet a
			LEFT JOIN projects b
			ON a.project_code = b.project_id
			WHERE a.date BETWEEN '$date1' AND '$date2'
			".$user."
			".$and1."
			".$and2."
			".$and3."
			".$and4."
			");

		// session_destroy();
		$query->execute();
		$result = $query->fetch();
		$insert = TRUE;
		if ($count == 0) {
			unset($_SESSION["query"]);
		}
		if(!isset($_SESSION["query"])){
			if (empty($project)) {
				$_SESSION["query"][99999] = array("id" => (!empty($project)) ? $project : 0, 'result' => @$result, 'post' => array('userid' => $userid, 'work_type' => $work_type, 'location' => $location, 'taskCode' => $taskCode, 'project_code' => $projectname, 'date' => $date1." - ".$date2));
			} else {
				$_SESSION["query"][] = array("id" => (!empty($project)) ? $project : 0, 'result' => @$result, 'post' => array('userid' => $userid, 'work_type' => $work_type, 'location' => $location, 'taskCode' => $taskCode, 'project_code' => $projectname, 'date' => $date1." - ".$date2));
			}
		} else {
			foreach ($_SESSION["query"] as $i => $row) {
				if (empty($project)) {
					unset($_SESSION["query"]);
					$_SESSION["query"][99999] = array("id" => (!empty($project)) ? $project : 0, 'result' => @$result, 'post' => array('userid' => $userid, 'work_type' => $work_type, 'location' => $location, 'taskCode' => $taskCode, 'project_code' => $projectname, 'date' => $date1." - ".$date2));
					$insert = FALSE;
				} else {
					unset($_SESSION["query"][99999]);
					if ($project == $row['id'] || $project == "" || empty($row['result'])) {
						$_SESSION["query"][$i] = array("id" => (!empty($project)) ? $project : 0, 'result' => @$result, 'post' => array('userid' => $userid, 'work_type' => $work_type, 'location' => $location, 'taskCode' => $taskCode, 'project_code' => $projectname, 'date' => $date1." - ".$date2));
						$insert = FALSE;
					}
				}
			}
			if ($insert == TRUE) {
				$_SESSION["query"][] = array("id" => (!empty($project)) ? $project : 0, 'result' => @$result, 'post' => array('userid' => $userid, 'work_type' => $work_type, 'location' => $location, 'taskCode' => $taskCode, 'project_code' => $projectname, 'date' => $date1." - ".$date2));
			}
		}
		
		return $_SESSION["query"];
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


	public function getUserTimesheet($userid = "")
	{
		$date = isset($_GET['date']) ? date('d-m-Y', strtotime($_GET['date'])) : date('d-m-Y');
		$userid = isset($_GET['userid']) ? $_GET['userid'] : ((!empty($userid)) ? $userid : '');
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
		$query = $this->conn->prepare("
			SELECT
			a.id,
			a.user_id,
			a.date,
			a.task_code,
			a.work_type,
			a.location,
			a.hours,
			a.comment,
			a.status,
			b.project_code
			FROM
			timesheet a
			LEFT JOIN projects b
			ON b.project_id = a.project_code
			WHERE a.user_id = '$userid'
			AND a.date BETWEEN '$dates[0]' AND '$dates[6]'"
		);
		$query->execute();
		if($result = $query->fetchAll()) {
			return $result;
		}
		return array();
	}

	public function geteditTimesheet($id)
	{
		$query = $this->conn->prepare("SELECT
			*
			FROM
			timesheet
			WHERE id = '$id'"
		);
		$query->execute();
		if($result = $query->fetch()) {
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