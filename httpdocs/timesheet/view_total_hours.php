<?php include 'timesheet.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Weekly Timesheet</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="datepicker/daterangepicker.css">
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
				<a href="<?= $base_url; ?>view_total_hours.php" class="btn btn-link"><i class="fa fa-clock-o"></i> View Project Hours</a>
				<a href="<?= $base_url; ?>view_project.php" class="btn btn-link"><i class="fa fa-book"></i> View Employees Timesheet</a>
				<a href="<?= $base_url; ?>view_my_timesheet.php" class="btn btn-link"><i class="fa fa-book"></i> View My Timesheet</a>
			</div>
		</div>
	</div>
	<div class="container border">
		<div class="row p-3">
			<div class="col-6 p-0">
				<label>Total hours per project</label>
				<br>
				<label>Filter: </label>
				<form action="<?= $base_url; ?>view_total_hours.php" method="POST">
					<div class="row">
						<div class="col-6">
							<div class="form-group">
								<label>Date: </label>
								<input type="text" name="date" class="form-control rounded-0" id="dateRange" value="<?= (isset($_POST['date'])) ? urldecode($_POST['date']) : date("m/d/Y").' - '.date('m/d/Y', strtotime(date('Y-m-d'). '+1 day')); ?>" required>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>User: </label>
								<select class="form-control rounded-0" name="userid">
									<option selected value>All</option>
									<option value="1" <?= (@$_POST['userid'] == '1') ? 'selected' : ''; ?>>Test User</option>
								</select>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Project: </label>
								<select class="form-control rounded-0" name="project">
									<option selected value>All</option>
									<?php foreach ($projects as $key => $row) : ?>
										<option value="<?= $row['project_id']; ?>" <?= (@$_POST['project'] == $row['project_id']) ? 'selected' : ''; ?>><?= $row['project_code']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Work Type: </label>
								<select class="form-control rounded-0" name="work_type">
									<option selected value>All</option>
									<option <?= (@$_POST['work_type'] == 'Regular') ? 'selected' : ''; ?>>Regular</option>
									<option <?= (urldecode(@$_POST['work_type']) == 'Regular OT') ? 'selected' : ''; ?>>Regular OT</option>
									<option <?= (urldecode(@$_POST['work_type']) == 'Holiday OT') ? 'selected' : ''; ?>>Holiday OT</option>
								</select>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Location: </label>
								<select class="form-control rounded-0" name="location">
									<option selected value>All</option>
									<option <?= (urldecode(@$_POST['location']) == 'On-Site') ? 'selected' : ''; ?>>On-Site</option>
									<option <?= (@$_POST['location'] == 'Home') ? 'selected' : ''; ?>>Home</option>
								</select>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Task Code: </label>
								<textarea class="form-control rounded-0" name="taskCode"><?= urldecode(@$_POST['taskCode']); ?></textarea>
							</div>
						</div>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary rounded-0">Submit</button>
					</div>
				</form>
			</div>
			<div class="col-6">
				<?php foreach ($project as $key => $row) : ?>
					<h5>Date: <?= $row['post']['date']; ?></h5>
					<?php 
					break; 
				endforeach; 
				?>
				<?php foreach ($project as $key => $row) : ?>
					<div class="card">
						<?php if (!empty(@$row['post']['userid']) || !empty(@$row['post']['project_code']) || !empty(@$row['post']['work_type']) || !empty(@$row['post']['location']) || !empty(@$row['post']['taskCode'])) : ?>
							<div class="card-body">
								<div class="text-right">
									<input type="hidden" name="key" value="<?= $key; ?>">
									<!-- <button class="btn btn-link text-danger p-0" type="submit" title="Remove"><i class="fa fa-times"></i></button> -->
								</div>
								<?php if (!empty(@$row['post']['userid'])) : ?>
									<span class="font-weight-bold">Employee Name: </span><?= $row['post']['userid']; ?>
									<br>
								<?php endif; ?>
								<?php if (!empty(@$row['post']['project_code'])) : ?>
									<span class="font-weight-bold">Project Name: </span><?= $row['post']['project_code']; ?>
									<br>
								<?php endif; ?>
								<?php if (!empty(@$row['post']['work_type'])) : ?>
									<span class="font-weight-bold">Work Type: </span><?= $row['post']['work_type']; ?>
									<br>
								<?php endif; ?>

								<?php if (!empty(@$row['post']['location'])) : ?>
									<span class="font-weight-bold">Location: </span><?= $row['post']['location']; ?>
									<br>
								<?php endif; ?>

								<?php if (!empty(@$row['post']['taskCode'])) : ?>
									<span class="font-weight-bold">Task Code: </span><?= $row['post']['taskCode']; ?>
									<br>
								<?php endif; ?>
							</div>
						<?php endif; ?>
						<div class="card-footer"><span class="font-weight-bold">Total Hours: </span><?= (isset($row['result']['total']) && $row['result']['total'] > 0) ? $row['result']['total'] : '0'; ?></div> 
						<!-- <div class="card-footer">Footer</div> -->
					</div>
				<?php endforeach; ?>
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
	<div class="modal" id="assignProject">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="<?= $base_url; ?>timesheet_model.php/assignProject" method="post">
					<!-- Modal Header -->
					<div class="modal-header">
						<h4 class="modal-title">Assign Project</h4>
					</div>

					<!-- Modal body -->
					<div class="modal-body">
						<label>Project Name</label>
						<select class="form-control rounded-0" name="project" required="">
							<option selected value></option>
							<?php foreach ($projects as $key => $row) : ?>
								<option value="<?= $row['project_id']; ?>"><?= $row['project_code']; ?></option>
							<?php endforeach; ?>
						</select>
						<label>Employee</label>
						<select class="form-control rounded-0" name="userid">
							<option value="1">Test User</option>
						</select>
					</div>

					<!-- Modal footer -->
					<div class="modal-footer">
						<button type="submit" class="btn btn-success">Assign</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script type="text/javascript" src="datepicker/moment.min.js"></script>
	<script type="text/javascript" src="datepicker/daterangepicker.js"></script>
	<script type="text/javascript">
		$('#dateRange').daterangepicker();
	</script>
	<script type="text/javascript"></script>
</body>
</html>