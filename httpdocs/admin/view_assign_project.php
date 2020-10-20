<?php include 'timesheet.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Weekly Timesheet</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<style type="text/css">
	.border-3 {
		border-width:3px !important;
	}
</style>
</head>
<body>
	<?php 
	$weeks = $class->getWeeks();
	$projects = $class->getProjects();
	$project = $class->getProject();
	$timesheet = $class->getUserTimesheet();
	$totalHours = 0;
	$dateData = array();
	$rejected = 0;
	$approved = 0;
	?>
	<div class="container border">
		<div class="text-right">
			<div class="form-group">
				<a href="<?= $base_url; ?>view.php" class="btn btn-link"><i class="fa fa-home"></i> Home</a>
				<button class="btn btn-link" data-toggle="modal" data-target="#addProject"><i class="fa fa-plus"></i> Add Project</button>
				<button class="btn btn-link" data-toggle="modal" data-target="#assignProject"><i class="fa fa-user"></i>  Assign Project</button>
				<a href="<?= $base_url; ?>view_total_hours.php" class="btn btn-link"><i class="fa fa-clock"></i> View Project Hours</a>
				<a href="<?= $base_url; ?>view_project.php" class="btn btn-link"><i class="fa fa-book"></i> View Employees Timesheet</a>
				<a href="<?= $base_url; ?>view_my_timesheet.php" class="btn btn-link"><i class="fa fa-book"></i> View My Timesheet</a>
			</div>
		</div>
	</div>
	<div class="container border">
		<div class="row p-3">
			<div class="col-2 p-0">
				<label>Daily total hours per project</label>
				<form action="<?= $base_url; ?>view_total_hours.php">
					<div class="form-group">
						<label>Date: </label>
						<input type="date" name="date" class="form-control rounded-0" value="<?= (isset($_GET['date'])) ? date('Y-m-d', strtotime($_GET['date'])) : date("Y-m-d"); ?>" required>
					</div>
					<div class="form-group">
						<label>Project: </label>
						<select class="form-control rounded-0" name="project" required="">
							<option selected value></option>
							<?php foreach ($projects as $key => $row) : ?>
								<option value="<?= $row['project_id']; ?>" <?= $_GET['project'] == $row['project_id'] ? 'selected' : ''; ?>><?= $row['project_code']; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary rounded-0">Submit</button>
					</div>
				</form>
			</div>
			<div class="col-10">
				<?php if (!empty($project)) : ?>
					<div class="card">
						<div class="card-header"><span class="font-weight-bold">Project Name: </span><?= $project['project_code']; ?></div>
						<div class="card-body"><span class="font-weight-bold">Total Hours: </span><?= $project['total']; ?></div> 
						<!-- <div class="card-footer">Footer</div> -->
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="modal" id="addProject">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="<?= $base_url; ?>timesheet_model.php/addProject" method="post">
					<!-- Modal Header -->
					<div class="modal-header">
						<h4 class="modal-title">Add Project</h4>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>

					<!-- Modal body -->
					<div class="modal-body">
						<label>Project Code</label>
						<input type="text" name="name" class="form-control rounded-0" required>
					</div>

					<!-- Modal footer -->
					<div class="modal-footer">
						<button type="submit" class="btn btn-success">Save</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>
</html>