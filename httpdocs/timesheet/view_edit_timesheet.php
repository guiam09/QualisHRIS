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
	$timesheet = $class->geteditTimesheet($_GET['timeid']);
	$projects = $class->getProjects();
	$proj = $class->getProjectsAssigned('1');
	$totalHours = 0;
	$dateData = array();
	$rejected = 0;
	$approved = 0;
	// echo "<pre>";
	// print_r($timesheet);
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
			<div class="col-12">
				<form action="<?= $base_url; ?>timesheet_model.php/updateForm" method="post">
					<table class="table table-sm table-bordered">
						<thead>
							<tr>
								<th class="text-center">Date</th>
								<th class="text-center">Project Code</th>
								<th class="text-center">Task Code</th>
								<th class="text-center">Work Type</th>
								<th class="text-center">Location</th>
								<th class="text-center">Hours</th>
								<th class="text-center">Comment</th>
							</tr>
						</thead>
						<tbody class="table-date">
							<tr>
								<td>
									<input type="hidden" name="date" value="<?= $timesheet['date']; ?>">
									<input type="hidden" name="id" value="<?= $timesheet['id']; ?>">
									<?= $timesheet['date']; ?>
								</td>
								<td>
									<select class="form-control rounded-0" name="projCode" required>
										<option value="" selected>-Select-</option>
										<?php foreach ($proj as $key => $value) : ?>
											<option value="<?= $value['project_id']; ?>" <?= ($value['project_id'] == $timesheet['project_code']) ? 'selected' : ''; ?>><?= $value['project_code']; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
								<td>
									<textarea class="form-control rounded-0"  name="taskCode" required><?= $timesheet['task_code']; ?></textarea>
								</td>
								<td>
									<select class="form-control rounded-0"  name="work_type" required>
										<option value="" selected>-Select-</option>
										<option <?= ($timesheet['work_type'] == 'Regular') ? 'selected' : ''; ?>>Regular</option>
										<option <?= ($timesheet['work_type'] == 'Regular OT') ? 'selected' : ''; ?>>Regular OT</option>
										<option <?= ($timesheet['work_type'] == 'Holiday OT') ? 'selected' : ''; ?>>Holiday OT</option>
									</select>
								</td>
								<td>
									<select class="form-control rounded-0"  name="location" required>
										<option value="" selected>-Select-</option>
										<option <?= ($timesheet['location'] == 'On-Site') ? 'selected' : ''; ?>>On-Site</option>
										<option <?= ($timesheet['location'] == 'Home') ? 'selected' : ''; ?>>Home</option>
									</select>
								</td>
								<td>
									<input type="number" class="form-control rounded-0" name="regHours" step="0.5" min="0.5" value="<?= $timesheet['hours']; ?>" style="width: 80px;" required>
								</td>
								<td>
									<textarea class="form-control rounded-0"  name="comment" required><?= $timesheet['comment']; ?></textarea>
								</td>
							</tr>
						</tbody>
					</table>
					<div class="text-right">
						<button type="submit" class="btn btn-success rounded-0">Save</button>
						<a href="<?= $base_url; ?>view_my_timesheet.php/<?= $_GET['date']; ?>" class="btn btn-secondary rounded-0">Back</a>
					</div>
				</form>
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
	<script type="text/javascript">
		$(document).on("click", "#btn-delete", function(e){
			e.preventDefault();
			if(confirm("Remove this item?")){
				$("#form-delete").submit();
			}
		});
	</script>
</body>
</html>