<?php
    include_once ('../../includes/configuration.php');
    include ('../../db/connection.php');
 
    $query="SELECT 
            *,
            SUM(weekly_sunday) + SUM(weekly_monday) + SUM(weekly_tuesday) + SUM(weekly_wednesday) + SUM(weekly_thursday) + SUM(weekly_friday) + SUM(weekly_saturday) AS `total_hours`,
            'normal' as `type`
        FROM
            tbl_weeklyutilization
                INNER JOIN
            tbl_employees ON tbl_weeklyutilization.employeeCode = tbl_employees.employeeCode
        WHERE
            weekly_approval IN ('Approved' , 'Declined')
        GROUP BY weekly_startDate , tbl_weeklyutilization.employeeCode 
        UNION ALL SELECT 
            *,
            SUM(weekly_sunday) + SUM(weekly_monday) + SUM(weekly_tuesday) + SUM(weekly_wednesday) + SUM(weekly_thursday) + SUM(weekly_friday) + SUM(weekly_saturday) AS `total_hours`,
            'history' as `type`
        FROM
            tbl_weeklyutilization_history
                INNER JOIN
            tbl_employees ON tbl_weeklyutilization_history.employeeCode = tbl_employees.employeeCode
        where is_shown = 1
        GROUP BY weekly_startDate , tbl_weeklyutilization_history.employeeCode
        ORDER BY weekly_endDate DESC";

    $statement = $con->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($result);
?>