<?php include 'timesheet.php'; ?>
<?php
include_once ('../includes/configuration.php');

include ('../db/connection.php');



// include login checker
$page_title="Admin";
$access_type ="Admin";

// include login checker
$require_login=true;
include_once "../includes/loginChecker.php";

include ('../includes/header.php');
include ('../includes/navbar.php');
$CURRENT_PAGE="Timesheet Application";
include ('../includes/sidebar.php');
include ('../includes/fetchData.php');
include ('get_week_range.php');
?>
<!--<!DOCTYPE html>-->
<!--<html lang="en">-->
<!--<head>-->
<!--	<meta charset="UTF-8">-->
<!--	<title>Weekly Timesheet</title>-->
<!--	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">-->
<!--	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">-->
	<style type="text/css">
	.border-3 {
		border-width:3px !important;
	}
</style>
<!--</head>-->
<!--<body>-->
	<?php 
	$weeks = $class->getWeeks();
	$timesheet = $class->getUserTimesheet(1);
	$projects = $class->getProjects();
	$totalHours = 0;
	$dateData = array();
	$rejected = 0;
	$approved = 0;
	?>
	<div class="page">
	    <div class="page-header">
	        <h1 class="page-title"><i>Timesheets Application</i></h1>
	    </div>
	<div class="container border">
		<div class="text-right">
			<div class="form-group">
				<a href="<?= $base_url; ?>timesheet_application.php" class="btn btn-link"><i class="fa fa-home"></i> Home</a>
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
			<div class="col-2 p-0">
				<label>Employees Timesheet</label>
				<form action="<?= $base_url; ?>view_my_timesheet.php">
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
							<th class="text-center">Comment</th>
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
								<td>
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
								<td>
									<?= $row['comment']; ?>
								</td>
								<td align="center">
									<?= ucfirst($row['status']); ?>
								</td>
								<td align="center">
									<div class="form-inline">
										<?php if ($row['status'] == 'pending') : ?>
											<form action="<?= $base_url; ?>view_edit_timesheet.php">
												<input type="hidden" name="date" value='<?= $row['date']; ?>'>
												<input type="hidden" name="timeid" value='<?= $row['id']; ?>'>
												<button type="submit" class="btn btn-sm btn-success rounded-0" title="Edit this item"><i class="fa fa-edit"></i></button>
											</form>
											<?php $approved++; ?>
										<?php endif; ?>
										<?php if ($row['status'] == 'pending') : ?>
											<form action="<?= $base_url; ?>timesheet_model.php/removeTimesheet" method="post" id="form-delete">
												<input type="hidden" name="date" value='["<?= $row['date']; ?>"]'>
												<input type="hidden" name="timeid" value='<?= $row['id']; ?>'>
												<button type="button" class="btn btn-sm btn-danger rounded-0" id="btn-delete" title="Remove this item"><i class="fa fa-trash"></i></button>
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
						    <?php include ('searchEmployeeList.php')?>
							<!--<option value="1">Test User</option>-->
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
	<?php
include ('../includes/footer.php');
include ('../includes/scripts.php');
//------
include ('includes/form_scripts.php');
//------ 
 ?>
<!--</body>-->
<!--</html>-->