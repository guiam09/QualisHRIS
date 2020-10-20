<?php 
	require '../vendor/autoload.php';
	include ('../db/connection.php');
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();

	$empID = $_GET['employee_id'];

    $leaveID = '';
    if (!empty($_GET['leave_id'])) {
      $leaveID = ' AND tbl_leave.leaveID = "'.$_GET['leave_id'].'"';
    }

    $query = "SELECT * FROM tbl_leaveinfo JOIN tbl_employees ON tbl_leaveinfo.employeeID = tbl_employees.employeeID 
                                     JOIN tbl_leave ON tbl_leaveinfo.leaveID = tbl_leave.leaveID
                                     JOIN tbl_position ON tbl_employees.positionID = tbl_position.positionID
                                      WHERE tbl_leaveinfo.employeeID = '$empID' ".$leaveID." ORDER BY firstName ASC";

    $stmt_table = $con->prepare($query);
	$stmt_table->execute();

	$sheet->setCellValue('A1', 'Employee Code');
	$sheet->setCellValue('B1', 'Employee Name');
	$sheet->setCellValue('C1', 'Position');
	$sheet->setCellValue('D1', 'Leave Type');
	$sheet->setCellValue('E1', 'Leave Credits');
	$sheet->setCellValue('F1', 'Used');
	$sheet->setCellValue('G1', 'Leave Balance');

	$count = 2;
	while ($row_table = $stmt_table->fetch(PDO::FETCH_ASSOC)) {

        $sheet->setCellValue('A'.$count, $row_table['employeeCode']);
		$sheet->setCellValue('B'.$count, $row_table["firstName"] . ' ' . $row_table['lastName']);
		$sheet->setCellValue('C'.$count, $row_table['positionName']);
		$sheet->setCellValue('D'.$count, $row_table['leaveName']);
		$sheet->setCellValue('E'.$count, $row_table['allowedLeave']);
		$sheet->setCellValue('F'.$count, $row_table['leaveUsed']);
		$sheet->setCellValue('G'.$count, $row_table['leaveRemaining']);
		$count++;
    }


	$writer = new Xlsx($spreadsheet);

	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="file.xlsx"');
    $writer->save("php://output");
?>