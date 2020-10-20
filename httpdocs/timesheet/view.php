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
	$proj = $class->getProjectsAssigned('1');
	$projects = $class->getProjects();
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
	<div class="container">
		<div class="row">
			<div class="col-2 p-0">
				<form action="<?= $base_url; ?>view.php">
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
				<form action="<?= $base_url; ?>timesheet_model.php/saveForm" method="post">
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
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody class="table-date">
							
						</tbody>
						<tfoot>
							<tr>
								<th class="text-center"><button type="button" id="addMore" class="btn btn-success"><i class="fa fa-plus"></i></button></th>
								<th colspan="4" class="text-right">Total Weekly Hours</th>
								<th class="text-center">
									<label id="totalweekregHours">0</label>
								</th>
								<th colspan="2"></th>
							</tr>
						</tfoot>
					</table>
					<button type="submit" class="btn btn-success pull-right	">Save</button>
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
	<script type="text/javascript">
		var counter = 1;
		appendTable(counter);
		$(document).on("click", "#addMore", function(e){
			e.preventDefault();
			counter++;
			appendTable(counter);
			// if (counter == 7) {
			// 	$(this).addClass('d-none');
			// 	return;
			// }
		});

		$(document).on("click", "#remove", function(e){
			e.preventDefault();
			$(this).parent().parent().remove();
			// if (counter == 7) {
			// 	$(this).addClass('d-none');
			// 	return;
			// }
		});

		function appendTable(counter) {
			var table = '';
			var weeks = <?= json_encode($weeks); ?>;
			$.each(weeks, function( index, value ) {
				table += `
				<tr>
				<td align="center">
				<select class="form-control rounded-0"  name="date[` + counter + `]" required>
				<option value="" selected>-Select-</option>
				<?php foreach ($weeks as $key => $row) : ?>
				<option value="<?= $row; ?>"><?= $row; ?></option>
				<?php endforeach; ?>
				</select>
				</td>
				<td align="center">
				<select class="form-control rounded-0" name="projCode[` + counter + `]" required>
				<option value="" selected>-Select-</option>
				<?php foreach ($proj as $key => $value) : ?>
				<option value="<?= $value['project_id']; ?>"><?= $value['project_code']; ?></option>
				<?php endforeach; ?>
				</select>
				</td>
				<td align="center">
				<textarea class="form-control rounded-0"  name="taskCode[` + counter + `]" required></textarea>
				</td>
				<td align="center">
				<select class="form-control rounded-0"  name="work_type[` + counter + `]" required>
				<option value="" selected>-Select-</option>
				<option>Regular</option>
				<option>Regular OT</option>
				<option>Holiday OT</option>
				</select>
				</td>
				<td align="center">
				<select class="form-control rounded-0"  name="location[` + counter + `]" required>
				<option value="" selected>-Select-</option>
				<option>On-Site</option>
				<option>Home</option>
				</select>
				</td>
				<td align="center">
				<input type="number" class="form-control rounded-0 ` + value + `regHours regHours" data-date="` + value + `" name="regHours[` + counter + `]" step="0.5" min="0.5" value="" style="width: 80px;" required>
				</td>
				<td align="center">
				<textarea class="form-control rounded-0"  name="comment[` + counter + `]" required></textarea>
				</td>
				<td align="center">`;
				if (counter != 1) {
					table += `
					<button type="button" id="remove" class="btn btn-danger"><i class="fa fa-minus"></i></button>
					`;
				}
				table += `
				</td>
				</tr>
				`;

				// if (counter > index) {
					return false;
				// }
			});
			$(".table-date").append(table);
		}
	</script>
	<script type="text/javascript">
		grandtotalHours2();

		$(document).on("change", ".regHours", function(){
			grandtotalHours2();
		});
		function settotalweekregHours() {
			var inputsReg = $(".regHours");
			var regHourstotalweek = 0;
			for(var i = 0; i < inputsReg.length; i++){
				regHourstotalweek += parseFloat($(inputsReg[i]).val());
			}
			// console.log(regHourstotalweek)

			// grandtotalHours += regHourstotalweek;
			$("#totalweekregHours").html(regHourstotalweek);
			return regHourstotalweek;
		}

		function grandtotalHours2() {
			var regular = settotalweekregHours();


			$("#grandtotalHours").html(regular);
		}
	</script>
	
</body>
</html>