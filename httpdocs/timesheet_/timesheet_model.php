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

		if (!isset($this->uri_segments[3])) {
			echo '404, Page Not Found';
			exit;
		}

		if (method_exists($this, $this->uri_segments[3])) {
			self::$uri_segments[3]();
		} else {
			echo '404, Page Not Found';
			exit;
		}
	}

	public function saveForm()
	{
		foreach ($_POST['date'] as $key => $row) {
			$date = date("Y-m-d", strtotime($row));
			$regHours = $_POST['regHours'][$key];
			$work_type = $_POST['work_type'][$key];
			$taskCode = $_POST['taskCode'][$key];
			$projCode = $_POST['projCode'][$key];
			$location = $_POST['location'][$key];
			$comment = $_POST['comment'][$key];
			$query = $this->conn->prepare("
				INSERT INTO timesheet
				(user_id, `date`, hours, project_code, task_code, location, work_type, comment)
				VALUES
				('1', '$date', '$regHours', '$projCode', '$taskCode', '$location', '$work_type', '$comment')
				");

			$query->execute();
		}

		echo "<script>alert('Save Success!');window.location.href='".$this->base_url."view.php?date=".$date."'</script>";
	}

	public function updateTimesheet()
	{
		$status = $_POST['status'];
		$date = array_unique(json_decode($_POST['date']));
		$userid = $_POST['userid'];
		foreach ($date as $row) {
			if (isset($_POST['timeid'])) {
				$id = $_POST['timeid'];
				$query = $this->conn->prepare("
					UPDATE timesheet
					SET status = '$status'
					WHERE id = '$id'
					");

				$query->execute();
			} else {
				
				$query = $this->conn->prepare("
					UPDATE timesheet
					SET status = '$status'
					WHERE `date` = '$row'
					AND user_id = '$userid'
					");

				$query->execute();
			}
		}

		echo "<script>alert('Success!');window.location.href='".$this->base_url."view_project.php?date=".$date[0]."&userid=".$userid."'</script>";
	}

	public function addProject()
	{
		$name = $_POST['name'];
		$userid = 1;
		
		$query = $this->conn->prepare("
			INSERT INTO
			projects
			(project_code, project_owner)
			VALUES
			('$name', '$userid');
			");

		$query->execute();
		echo "<script>alert('Success!');window.location.href='".$this->base_url."view_project.php?date=".date('Y-m-d')."&userid=".$userid."'</script>";
	}

	public function assignProject()
	{
		$project = $_POST['project'];
		$userid = $_POST['userid'];
		$query = $this->conn->prepare("
			INSERT INTO
			projects_assigned
			(project_id, user_id)
			VALUES
			('$project', '$userid')
			");

		$query->execute();
		echo "<script>alert('Success!');window.location.href='".$this->base_url."view_project.php'</script>";
	}
}

$class = new timesheet();