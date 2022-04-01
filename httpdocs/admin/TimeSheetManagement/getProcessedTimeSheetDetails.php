<?php
    include_once ('../../includes/configuration.php');
    include ('../../db/connection.php');

    $query = "";
    if ($_GET['type'] == "normal") {
        $query = "SELECT *,
                e.lastName,
                e.firstName,
                e.middleName 
            FROM tbl_weeklyutilization 
            INNER JOIN tbl_project ON tbl_weeklyutilization.project_ID = tbl_project.project_ID
            INNER JOIN tbl_worktype ON tbl_weeklyutilization.work_ID = tbl_worktype.work_ID
            LEFT JOIN tbl_employees e ON tbl_weeklyutilization.employeeCode = e.employeeCode
            ORDER BY e.lastName, tbl_weeklyutilization.weekly_endDate DESC";
    } else {
        $query = "SELECT *,
                e.lastName,
                e.firstName,
                e.middleName 
            FROM tbl_weeklyutilization_history
            INNER JOIN tbl_project ON tbl_weeklyutilization_history.project_ID = tbl_project.project_ID
            INNER JOIN tbl_worktype ON tbl_weeklyutilization_history.work_ID = tbl_worktype.work_ID
            LEFT JOIN tbl_employees e ON tbl_weeklyutilization.employeeCode = e.employeeCode
            ORDER BY e.lastName, tbl_weeklyutilization.weekly_endDate DESC";
    }

    $stmt = $con->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($result);
?>