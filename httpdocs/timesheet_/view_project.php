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
	$timesheet = $class->getUserTimesheet();
	$projects = $class->getProjects();
	$totalHours = 0;
	$dateData = array();
	$rejected = 0;
	$approved = 0;
	?>
	<div class="container border">
		<div class="text-right">
			<div class="form-group">
				<button class="btn btn-link" data-toggle="modal" data-target="#addProject"><i class="fa fa-plus"></i> Add Project</button>
				<button class="btn btn-link" data-toggle="modal" data-target="#assignProject"><i class="fa fa-user"></i>  Assign Project</button>
				<a href="<?= $base_url; ?>view_total_hours.php" class="btn btn-link"><i class="fa fa-clock-o"></i> View Project Hours</a>
				<a href="<?= $base_url; ?>view_project.php" class="btn btn-link"><i class="fa fa-book"></i> View Employees Timesheet</a>
			</div>
		</div>
	</div>
	<div class="container border">
		<div class="row p-3">
			<div class="col-2 p-0">
				<label>Employees Timesheet</label>
				<form action="<?= $base_url; ?>view_project.php">
					<div class="form-group">
						<label>User: </label>
						<select class="form-control rounded-0" name="userid">
							<option value="1">Test User</option>
						</select>
					</div>
					<div class="form-group">
						<label>Week of: </label>
						<input type="date" name="date" class="form-control rounded-0" value="<?= (isset($_GET['date'])) ? date('Y-m-d', strtotime($_GET['date'])) : date("Y-m-d"); ?>" required>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary rounded-0">Submit</button>
					</div>
				</form>
			</div>
			<div class="col-10">
				<table class="table table-sm table-bordered">
					<thead>
						<tr>
							<th class="text-center">Date</th>
							<th class="text-center">Project Code</th>
							<th class="text-center">Task Code</th>
							<th class="text-center">Work Type</th>
							<th class="text-center">Location</th>
							<th class="text-center">Hours</th>
							<th class="text-center">Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody class="table-date">
						<?php foreach ($timesheet as $key => $row) : ?>
							<tr>
								<td align="center">
									<?= $row['date']; ?>
								</td>
								<td align="center">
									<?= $row['project_code']; ?>
								</td>
								<td align="center">
									<?= $row['task_code']; ?>
								</td>
								<td align="center">
									<?= $row['work_type']; ?>
								</td>
								<td align="center">
									<?= $row['location']; ?>
								</td>
								<td align="center">
									<?= $row['hours']; ?>
								</td>
								<td align="center">
									<?= ucfirst($row['status']); ?>
								</td>
								<td align="center">
									<div class="form-inline">
										<?php if ($row['status'] != 'approved') : ?>
											<form action="<?= $base_url; ?>timesheet_model.php/updateTimesheet" method="post">
												<input type="hidden" name="status" value="approved">
												<input type="hidden" name="date" value='["<?= $row['date']; ?>"]'>
												<input type="hidden" name="userid" value="<?= $_GET['userid']; ?>">
												<input type="hidden" name="timeid" value='<?= $row['id']; ?>'>
												<button type="submit" class="btn btn-sm btn-success rounded-0" title="Approve this item"><i class="fa fa-check"></i></button>
											</form>
											<?php $approved++; ?>
										<?php endif; ?>
										<?php if ($row['status'] != 'rejected') : ?>
											<form action="<?= $base_url; ?>timesheet_model.php/updateTimesheet" method="post">
												<input type="hidden" name="status" value="rejected">
												<input type="hidden" name="date" value='["<?= $row['date']; ?>"]'>
												<input type="hidden" name="userid" value="<?= $_GET['userid']; ?>">
												<input type="hidden" name="timeid" value='<?= $row['id']; ?>'>
												<button type="submit" class="btn btn-sm btn-danger rounded-0" title="Reject this item"><i class="fa fa-times"></i></button>
											</form>
											<?php $rejected++; ?>
										<?php endif; ?>
									</div>
								</td>
							</tr>
							<?php 
							$dateData[] = $row['date'];
							$totalHours += $row['hours']; 
							?>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr>
							<th colspan="5" class="text-right">Total Weekly Hours</th>
							<th class="text-center">
								<label id="totalweekregHours"><?= $totalHours; ?></label>
							</th>
							<th colspan="2">
							</th>
						</tr>
					</tfoot>
				</table>
				<div class="form-inline">
					<?php if ($approved > 0) : ?>
						<form action="<?= $base_url; ?>timesheet_model.php/updateTimesheet" method="post">
							<input type="hidden" name="status" value="approved">
							<input type="hidden" name="date" value='<?= json_encode($dateData); ?>'>
							<input type="hidden" name="userid" value="<?= $_GET['userid']; ?>">
							<button type="submit" class="btn btn-success rounded-0 mr-3">Approved All</button>
						</form>
					<?php endif; ?>
					<?php if ($rejected > 0) : ?>
						<form action="<?= $base_url; ?>timesheet_model.php/updateTimesheet" method="post">
							<input type="hidden" name="status" value="rejected">
							<input type="hidden" name="date" value='<?= json_encode($dateData); ?>'>
							<input type="hidden" name="userid" value="<?= $_GET['userid']; ?>">
							<button type="submit" class="btn btn-danger rounded-0">Reject All</button>
						</form>
					<?php endif; ?>
				</div>
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

</body>
</html>