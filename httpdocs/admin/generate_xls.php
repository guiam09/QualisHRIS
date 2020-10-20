<?php 
	require '../vendor/autoload.php';
	include ('../db/connection.php');
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();

	$query = "SELECT * FROM tbl_weeklyutilization
	    INNER JOIN tbl_project ON tbl_weeklyutilization.project_ID = tbl_project.project_ID
	    INNER JOIN tbl_worktype ON tbl_weeklyutilization.work_ID = tbl_worktype.work_ID
	    WHERE employeeCode = '". $_GET['employee_id'] ."' AND weekly_startDate='" . $_GET['weekly_enddate'] . "'";

    $stmt_table = $con->prepare($query);
	$stmt_table->execute();

	$sheet->setCellValue('A1', 'Project Code');
	$sheet->setCellValue('B1', 'Work Type');
	$sheet->setCellValue('C1', 'Task Code');
	$sheet->setCellValue('D1', 'Remarks');
	$sheet->setCellValue('E1', 'Mon');
	$sheet->setCellValue('F1', 'Tue');
	$sheet->setCellValue('G1', 'Wed');
	$sheet->setCellValue('H1', 'Thu');
	$sheet->setCellValue('I1', 'Fri');
	$sheet->setCellValue('J1', 'Sat');
	$sheet->setCellValue('K1', 'Sun');
	$sheet->setCellValue('L1', 'Total');

	$count = 2;
	$mondayTotal = $tuesdayTotal = $wednesdayTotal = $thursdayTotal = $fridayTotal = $saturdayTotal = $sundayTotal = $total = 0;
	while ($row_table = $stmt_table->fetch(PDO::FETCH_ASSOC)) {
        $sundayTotal    = $sundayTotal+ $row_table['weekly_sunday'];
        $mondayTotal    = $mondayTotal+ $row_table['weekly_monday'];
        $tuesdayTotal   = $tuesdayTotal+ $row_table['weekly_tuesday'];
        $wednesdayTotal = $wednesdayTotal+ $row_table['weekly_wednesday'];
        $thursdayTotal  = $thursdayTotal+ $row_table['weekly_thursday'];
        $fridayTotal    = $fridayTotal+ $row_table['weekly_friday'];
        $saturdayTotal  = $saturdayTotal+ $row_table['weekly_saturday'];
        $overallTotal   = $row_table['weekly_overallTotal'];

        $subtotal   = $row_table['weekly_sunday'] + $row_table['weekly_monday'] + $row_table['weekly_tuesday'] + $row_table['weekly_wednesday'] + $row_table['weekly_thursday'] + $row_table['weekly_friday'] + $row_table['weekly_saturday'];
        $total      = $total + $subtotal;

        $sheet->setCellValue('A'.$count, $row_table['project_name']);
		$sheet->setCellValue('B'.$count, $row_table['work_name']);
		$sheet->setCellValue('C'.$count, $row_table['weekly_taskCode']);
		$sheet->setCellValue('D'.$count, $row_table['location_ID']);
		$sheet->setCellValue('E'.$count, $row_table['weekly_monday']);
		$sheet->setCellValue('F'.$count, $row_table['weekly_tuesday']);
		$sheet->setCellValue('G'.$count, $row_table['weekly_wednesday']);
		$sheet->setCellValue('H'.$count, $row_table['weekly_thursday']);
		$sheet->setCellValue('I'.$count, $row_table['weekly_friday']);
		$sheet->setCellValue('J'.$count, $row_table['weekly_saturday']);
		$sheet->setCellValue('K'.$count, $row_table['weekly_sunday']);
		$sheet->setCellValue('L'.$count, $subtotal);
		$count++;
    }

    $sheet->setCellValue('E'.$count, $mondayTotal);
	$sheet->setCellValue('F'.$count, $tuesdayTotal);
	$sheet->setCellValue('G'.$count, $wednesdayTotal);
	$sheet->setCellValue('H'.$count, $thursdayTotal);
	$sheet->setCellValue('I'.$count, $fridayTotal);
	$sheet->setCellValue('J'.$count, $saturdayTotal);
	$sheet->setCellValue('K'.$count, $sundayTotal);
	$sheet->setCellValue('L'.$count, $total);


	$writer = new Xlsx($spreadsheet);
	// $writer->save('hello world.xlsx');

	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="file.xlsx"');
    $writer->save("php://output");
?>