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


    <!-- Page -->
    <div class="page">
      <div class="page-header">
          <h1 class="page-title"><i>Timesheets Application</i></h1>
      </div>
      <div class="page-content container-fluid">
          <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <!--<h1>Time sheets</h1>-->
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <div class="row">
      <div class="col-12">
        <!-- Custom Tabs -->
        <div class="card">
          <div class="card-header d-flex p-0">
            <ul class="nav nav-pills  p-2">
              <!--<li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Enter Weekly Time</a></li>-->
<!--              <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">View Timesheets</a></li>-->
            </ul>
          </div><!-- /.card-header -->
          <div class="card-body">
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">

                  <?php

                  //addding engagements
                if(isset($_POST['addEngagement'])){

                    try{
                    //insert query
                        $query = "INSERT INTO tbl_weeklyutilization SET
                        employeeCode=:employeeID,
                        weekly_startDate=:weekStart,
                        weekly_endDate=:weekEnd,
                        project_ID=:projectName,
                        work_ID=:work,
                        activityOthers_ID=:actOthers,
                        activityAdmin_ID=:actAdmin,
                        weekly_description=:description,
                        weekly_sunday=:sunday,
                        weekly_monday=:monday,
                        weekly_tuesday=:tuesday,
                        weekly_wednesday=:wednesday,
                        weekly_thursday=:thursday,
                        weekly_friday=:friday,
                        weekly_saturday=:saturday,
                        weekly_total=:total,
                        location_ID=:location,
                        weekly_status=:status
 
                        ";

                        $stmt = $con->prepare($query);

                        //posted values
                        $employeeID=htmlspecialchars(strip_tags($_SESSION['user_id']));
                       // $weekStart=htmlspecialchars(strip_tags($_POST['weekStart']));
                        //$weekStart=htmlspecialchars(strip_tags(date('Y-m-d')));
                        //$weekEnd=htmlspecialchars(strip_tags($_POST['weekEnd']));
                        //$weekEnd=htmlspecialchars(strip_tags(date('Y-m-d')));
                       // $projectName=htmlspecialchars(strip_tags($_POST['projectName']));
                        $projectName=htmlspecialchars(strip_tags($_POST['projectName']));
                        //$work=htmlspecialchars(strip_tags($_POST['work']));
                        $work=1;

                       if($projectName=="Admin"){
                           //$actAdmin=htmlspecialchars(strip_tags($_POST['actAdmin']));
                           $actAdmin=1;
                           $stmt->bindParam(':actAdmin', $actAdmin);

                           $actOthers=htmlspecialchars(strip_tags(" "));
                           $stmt->bindParam(':actOthers', $actOthers);

                       } else {
                           //$actOthers=htmlspecialchars(strip_tags($_POST['actOthers']));
                           $actAdmin=1;
                           $stmt->bindParam(':actAdmin', $actAdmin);
                           $actOthers=1;
                           $stmt->bindParam(':actOthers', $actOthers);
                       }

                        //$description=htmlspecialchars(strip_tags($_POST['description']));
                        $description=htmlspecialchars(strip_tags(""));
                        //$sunday=htmlspecialchars(strip_tags($_POST['sunday']));
                        $sunday=htmlspecialchars(strip_tags(0));
                       // $monday=htmlspecialchars(strip_tags($_POST['monday']));
                        $monday=htmlspecialchars(strip_tags(0));
                        //$tuesday=htmlspecialchars(strip_tags($_POST['tuesday']));
                        $tuesday=htmlspecialchars(strip_tags(0));
                       // $wednesday=htmlspecialchars(strip_tags($_POST['wednesday']));
                        $wednesday=htmlspecialchars(strip_tags(0));
                        //$thursday=htmlspecialchars(strip_tags($_POST['thursday']));
                        $thursday=htmlspecialchars(strip_tags(0));
                       // $friday=htmlspecialchars(strip_tags($_POST['friday']));
                        $friday=htmlspecialchars(strip_tags(0));
                       // $saturday=htmlspecialchars(strip_tags($_POST['saturday']));
                        $saturday=htmlspecialchars(strip_tags(0));
                        //$total=htmlspecialchars(strip_tags($_POST['total']));
                        $total=htmlspecialchars(strip_tags(0));
                        //$location=htmlspecialchars(strip_tags($_POST['location']));
                        $location=1;

                        //converting one date format into another
                        //also included in thead th. used in query and foreach. binding parameters above. card title
                        //include '../getWeekRange.php';
                        $weekStart = date("Y-m-d", strtotime($weekStart));
                        $weekEnd = date("Y-m-d", strtotime($weekEnd));
                        $status = "Not Submitted";


                        //bind parameters
                        $stmt->bindParam(':employeeID', $employeeID);
                        $stmt->bindParam(':weekStart', $weekStart);
                        $stmt->bindParam(':weekEnd', $weekEnd);
                        $stmt->bindParam(':projectName', $projectName);
                        $stmt->bindParam(':work', $work);
                        //$stmt->bindParam(':actAdmin', $actAdmin); **CHECK POST VALUES ARE FOR BINDING **
                        //$stmt->bindParam(':actOthers', $actOthers); **CHECK POST VALUES ARE FOR BINDING **
                        $stmt->bindParam(':description', $description);
                        $stmt->bindParam(':sunday', $sunday);
                        $stmt->bindParam(':monday', $monday);
                        $stmt->bindParam(':tuesday', $tuesday);
                        $stmt->bindParam(':wednesday', $wednesday);
                        $stmt->bindParam(':thursday', $thursday);
                        $stmt->bindParam(':friday', $friday);
                        $stmt->bindParam(':saturday', $saturday);
                        $stmt->bindParam(':total', $total);
                        $stmt->bindParam(':location', $location);
                        $stmt->bindParam(':status', $status);


                        //Execute query see addUser.php line 159 if error
                        if($stmt->execute()){
                            echo "<div class='alert alert-success'>New Engagement Added!</div>";
                        }else{
                        echo
                            "<div class='alert alert-danger'>Unable to add engagement.</div>";
                    }

                    }

                     // show error
                    catch(PDOException $exception){
                        die('ERROR: ' . $exception->getMessage());
                    }

                    }

                //timsheet submission
                elseif (isset($_POST['submitTimesheet'])){
                    $utilization=$_POST['utilization'];


                    try{
//
//                        $query = "INSERT INTO tbl_submittedTimesheets SET
//                        employeeCode=:employeeID,
//                        timesheet_endDate=:weekEnd,
//                        timesheet_dateSubmitted=:dateSubmitted,
//                        timesheet_dateProcessed=:dateProcessed,
//                        timesheet_status=:status,
//                        timesheet_approval=:approval,
//                        timesheet_totalHours=:totalHours
//                        ";
//
//
//                        weekly_timeSubmitted=:timeSubmitted,
                          foreach($utilization as $id){
                        $ctr=0;


                        $query = "UPDATE tbl_weeklyutilization SET
                        weekly_dateSubmitted=:dateSubmitted,
                        weekly_timeSubmitted=:timeSubmitted,
                        weekly_dateProcessed=:dateProcessed,
                        weekly_status=:status,
                        weekly_approval=:approval,
                        weekly_overallTotal=:totalHours
                        WHERE
                        weekly_ID=:weeklyID
                        ";

                        $stmt = $con->prepare($query);

                        //posted values

                        $dateSubmitted=date("Y-m-d");
                        $dateProcessed=date("Y-m-d");
                        $status = "Submitted";
                        $approval = "Pending";
                        $totalHours = $_POST['overall_total'];
                        $dateSubmitted = date("Y-m-d", strtotime($dateSubmitted));
                        $time = date('H:i:s');
                        $weeklyID = $id;

                        //bind parameters
                        $stmt->bindParam(':dateSubmitted', $dateSubmitted);
                        $stmt->bindParam(':dateProcessed', $dateProcessed);
                        $stmt->bindParam(':status', $status);
                        $stmt->bindParam(':approval', $approval);
                        $stmt->bindParam(':totalHours',$totalHours);
                        $stmt->bindParam(':timeSubmitted',$time);
                        $stmt->bindParam(':weeklyID',$weeklyID);

                        $stmt->execute();

                          }
                        if($stmt->execute()){



                            echo "<div class='alert alert-success'>Timesheet Submitted!</div>";
//                            print_r ($utilization);
                        }
                        else {
//                             echo "
//        <script>
//            window.open('timesheets.php?addproject_result=failed','_self');
//        </script>
//    ";
//

                            echo "<div class='alert alert-danger'>Unable to submit timesheet.</div>";
                        }
                    }
                    catch(PDOException $exception)
                    {
                          die('ERROR: ' . $exception->getMessage());
                      }
                }

                  //edit daily time
                  elseif (isset($_POST['updateTimesheets'])) {
                    try{


                        $query ="UPDATE tbl_weeklyutilization
                                SET
                                    work_ID=:workName,
                                    activityAdmin_ID=:activity,
                                    weekly_description=:description,
                                    location_ID=:location,
                                    weekly_saturday=:saturday,
                                    weekly_sunday=:sunday,
                                    weekly_monday=:monday,
                                    weekly_tuesday=:tuesday,
                                    weekly_wednesday=:wednesday,
                                    weekly_thursday=:thursday,
                                    weekly_friday=:friday,
                                    weekly_total=:total

                                    WHERE weekly_ID=:weeklyID
                        ";

                        $stmt = $con->prepare($query);

                        //posting values
                        $weeklyID = htmlspecialchars(strip_tags($_POST['weeklyID']));
                        $workName = htmlspecialchars(strip_tags($_POST['workType']));
                        $activity = htmlspecialchars(strip_tags($_POST['activity']));
                        $location = htmlspecialchars(strip_tags($_POST['location']));
                        $description = htmlspecialchars(strip_tags($_POST['description']));
                        $saturday = htmlspecialchars(strip_tags($_POST['saturday']));
                        $sunday = htmlspecialchars(strip_tags($_POST['sunday']));
                        $monday = htmlspecialchars(strip_tags($_POST['monday']));
                        $tuesday = htmlspecialchars(strip_tags($_POST['tuesday']));
                        $wednesday = htmlspecialchars(strip_tags($_POST['wednesday']));
                        $thursday = htmlspecialchars(strip_tags($_POST['thursday']));
                        $friday = htmlspecialchars(strip_tags($_POST['friday']));
                        $weeklyTotal = $saturday + $sunday + $monday + $tuesday + $wednesday + $thursday + $friday;


                        $stmt->bindParam(':workName', $workName);
                        $stmt->bindParam(':activity', $activity);
                        $stmt->bindParam(':location', $location);
                        $stmt->bindParam(':description', $description);
                        $stmt->bindParam(':saturday', $saturday);
                        $stmt->bindParam(':sunday', $sunday);
                        $stmt->bindParam(':monday', $monday);
                        $stmt->bindParam(':tuesday', $tuesday);
                        $stmt->bindParam(':wednesday', $wednesday);
                        $stmt->bindParam(':thursday', $thursday);
                        $stmt->bindParam(':friday', $friday);
                        $stmt->bindParam(':weeklyID', $weeklyID);
                        $stmt->bindParam(':total', $weeklyTotal);

                        if($stmt->execute()){



                            echo "<div class='alert alert-success'>Timesheet Updated!</div>";
                            ?>
                  <!-- <script>
                    alert(<?php
                    // echo $weeklyID;
                    ?>);
                  </script> -->
                              <?php

                        }
                        else {
                             echo "
       <script>
            window.open('timesheets.php?update_result=failed','_self');
        </script>
    ";


                            echo "<div class='alert alert-danger'>Unable to update timesheet.</div>";
                        }

                    
                  }
                  
                    catch(PDOException $exception){
                        die('ERROR: ' . $exception->getMessage());
                    }
                }

        elseif(isset($_POST['deleteProject'])) {
            try{
            $query = "DELETE FROM tbl_weeklyutilization
                    WHERE weekly_ID=:weeklyID
            ";
            $stmt = $con->prepare($query);


            $weeklyID = $_POST['weeklyID'];

            $stmt->bindParam(':weeklyID', $weeklyID);

            if($stmt->execute()){



                            echo "<div class='alert alert-success'>Project Removed!</div>";
                        }
                        else {
                             echo "
       <script>
            window.open('timesheets.php?update_result=failed','_self');
        </script>
    ";


                            echo "<div class='alert alert-danger'>Unable to update timesheet.</div>";
                        }

                    }

                    catch(PDOException $exception){
                        die('ERROR: ' . $exception->getMessage());
                    }

        }


                    ?>

                  <!-- Main content -->
                  <section class="content">
                      <div class="container-fluid">
                        <div class="row">
                          <!-- left column -->
                          <div class="col-md-12">
                            <!-- general form elements -->
                            <div class="panel">
                              <div class="panel-heading">
           <div class="row">
             <div class="col-md-5">
               <h3 class="card-title">Weekly Time (
                   <?php
                   //also included in thead th. used in query and foreach. binding parameters above
                   //include '../getWeekRange.php';
                   //display active week range
                   echo  $displayWeekStart . " - " . $displayWeekEnd; ?>)</h3>
             </div>
             <div class="col-md-2 offset-md-5">
                <button type="button"  data-toggle="modal" data-target="#addEngagements" class="btn btn-info"><i class="fa fa-edit"></i> Add Engagements</button>
             </div>
           </div>
         </div>
          <!--  <?php
//$userID = $_SESSION['user_id'];
//  $query2 = "SELECT * FROM tbl_weeklyutilization WHERE employee_ID = '$userID' AND weekly_startDate = '$weekStart' AND weekly_endDate = '$weekEnd' ";

//$stmt = $con->prepare($query2);
 // $stmt->execute();
  //$num = $stmt->rowCount();
                    // check if more than 0 record found
 // if($num>0){


                //   $userID = $row['employee_ID'];
                //   $weekStart = $row['week_startDate'];
                //   $weekEnd = $row['week_endDate'];
                                ?>-->
                                <div class="card-body table-responsive p-0">
                                <!-- Start Utilization table -->
                                    <form method='POST' action='<?php echo $_SERVER['PHP_SELF']?> '>
                                <table id='example1' class='table table-hover table-striped'>

   <?php
            //////////////////////////////////////////////

//            $query_work = "SELECT * FROM tbl_worktype";
//                                $stmt_work = $con->prepare($query_work);
//                                    $stmt_work->execute();
//                                    $num_work = $stmt_work->rowCount();
//                                    echo "<select>";
//                                    while($row_work = $stmt_work->fetch(PDO::FETCH_ASSOC)){
//                                        echo '<option value='. $row_work['work_ID'] . '>' . $row_work['work_name'] . '</option>';
//                                    }



            ///////////////////////////////////////////////
            $userID = $_SESSION['user_id'];
            $query = "SELECT * FROM tbl_weeklyutilization

            INNER JOIN tbl_project ON tbl_weeklyutilization.project_ID = tbl_project.project_ID

            INNER JOIN tbl_worktype ON tbl_weeklyutilization.work_ID = tbl_worktype.work_ID

            INNER JOIN tbl_activityadmin ON tbl_weeklyutilization.activityAdmin_ID = tbl_activityadmin.activityAdmin_ID

            INNER JOIN tbl_activityothers ON tbl_weeklyutilization.activityOthers_ID = tbl_activityothers.activityOthers_ID

            INNER JOIN tbl_location ON tbl_weeklyutilization.location_ID = tbl_location.location_ID

            WHERE employeeCode = '$userID' AND weekly_startDate = '$weekStart' AND weekly_endDate = '$weekEnd';
             ";

        //echo "$userID $weekStart $weekEnd";
            $stmt = $con->prepare($query);
            $stmt->execute();
            $num = $stmt->rowCount();

            //query for worktype


            ////////////////////////
            //while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
//                print_r($row);
            //}
            ////////////////////////   WHERE employee_ID = '$userID' AND weekly_startDate = \''$weekStart'\' AND weekly_endDate = \''$weekEnd'\' ";

            if($num>0){
            echo " <tr>
                        <th>Project Code</th>
                        <th>Work Type</th>
                        <th>Task Code</th>
                        <th>Comments</th>
                        <th>Location</th> ";

                foreach($period as $date)
                {
                        echo "<th>" . $date->format('d D') . "</th>";
                }
            echo "<th>Total</th>
                    <th>Options</th>";

                $total_saturday=0;
                $total_sunday=0;
                $total_monday=0;
                $total_tuesday=0;
                $total_wednesday=0;
                $total_thursday=0;
                $total_friday=0;
                $overall_total = 0;
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            ?>



            <?php

            if($row['project_name'] == "Admin")    {
                $activityName = $row['activityAdmin_name'];
            } else {
                $activityName = $row['activityOthers_name'];
            }
            echo "<tr>
                    <td><div id='projectName'>" . $row['project_name'] . "</div></td>

                    <td><div name='current_workname'>" . $row['work_name'] . "</div>
                    <select hidden name='change_workname' class='form-control'>

                    </td>

                    <td>" . $activityName . "</td>
                    <td>" . $row['weekly_description'] . "</td>
                    <td>" . $row['location_name'] . "</td>";

            //getting weekly total
            $weekly_total = $row['weekly_saturday'] + $row['weekly_sunday'] + $row['weekly_monday'] + $row['weekly_tuesday'] + $row['weekly_wednesday'] + $row['weekly_thursday'] + $row['weekly_friday'];

            //getting daily totals

                    $total_saturday=$row['weekly_saturday']+$total_sunday;
                    $total_sunday=$row['weekly_sunday']+$total_sunday;
                    $total_monday=$row['weekly_monday']+$total_monday;
                    $total_tuesday=$row['weekly_tuesday']+$total_tuesday;
                    $total_wednesday=$row['weekly_wednesday']+$total_wednesday;
                    $total_thursday=$row['weekly_thursday']+$total_thursday;
                    $total_friday=$row['weekly_friday']+$total_friday;
                    $overall_total=$overall_total+$weekly_total;

                    if ($day == "Saturday"){
                        echo "
                            <td>
                            <input type='text' class='personal-style-input' id='change_day' DISABLED size='1'  value='" . $row['weekly_saturday'] . "'></td>
                            <td><div></div><input type='text' id='change_sunday' size='1' DISABLED value='" . $row['weekly_sunday'] . "'></td>
                            <td><div></div><input type='text' id='change_monday' size='1' DISABLED value='" . $row['weekly_monday'] . "'></td>
                            <td><div></div><input type='text' id='change_tuesday' size='1' DISABLED value='" . $row['weekly_tuesday'] . "'></td>
                            <td><div></div><input type='text' id='change_wednesday' size='1' DISABLED value='" . $row['weekly_wednesday'] . "'></td>
                            <td><div></div><input type='text' id='change_thursday' size='1' DISABLED  value='" . $row['weekly_thursday'] . "'></td>
                            <td><div></div><input type='text' id='change_friday' size='1' DISABLED  value='" . $row['weekly_friday'] . "'></td>

                            ";
                    } else if ($day == "Sunday") {
                        echo "
                            <td><div>" . $row['weekly_sunday'] . "</div><input type='text' id='change_sunday' size='1' hidden value='" . $row['weekly_sunday'] . " '></td>
                            <td><div>" . $row['weekly_monday'] . "</div><input type='text' id='change_monday' size='1' hidden value='" . $row['weekly_monday'] . "'></td>
                            <td><div>" . $row['weekly_tuesday'] . "</div><input type='text' id='change_tuesday' size='1' hidden value='" . $row['weekly_tuesday'] . "'></td>
                            <td><div>" . $row['weekly_wednesday'] . "</div><input type='text' id='change_wednesday' size='1' hidden value='" . $row['weekly_wednesday'] . "'></td>
                            <td><div>" . $row['weekly_thursday'] . "</div><input type='text' id='change_thursday' size='1' hidden value='" . $row['weekly_thursday'] . "'></td>
                            <td><div>" . $row['weekly_friday'] . "</div><input type='text' id='change_friday' size='1' hidden value='" . $row['weekly_friday'] . "'></td>
                            <td><div>" . $row['weekly_saturday'] . "</div><input type='text' id='change_saturday' size='1' hidden value='" . $row['weekly_saturday'] . "'></td>

                        ";
                    }else if ($day == "Monday") {
                        echo "
                            <td><div>" . $row['weekly_monday'] . "</div><input type='text' id='change_monday' size='1' hidden value='" . $row['weekly_monday'] . "'></td>
                            <td><div>" . $row['weekly_tuesday'] . "</div><input type='text' id='change_tuesday' size='1' hidden value='" . $row['weekly_tuesday'] . "'></td>
                            <td><div>" . $row['weekly_wednesday'] . "</div><input type='text' id='change_wednesday' size='1' hidden value='" . $row['weekly_wednesday'] . "'></td>
                            <td><div>" . $row['weekly_thursday'] . "</div><input type='text' id='change_thursday' size='1' hidden value='" . $row['weekly_thursday'] . "'></td>
                            <td><div>" . $row['weekly_friday'] . "</div><input type='text' id='change_friday' size='1' hidden value='" . $row['weekly_friday'] . "'></td>
                            <td><div>" . $row['weekly_saturday'] . "</div><input type='text' id='change_saturday' size='1' hidden value='" . $row['weekly_saturday'] . "'></td>
                            <td><div>" . $row['weekly_sunday'] . "</div><input type='text' id='change_sunday' size='1' hidden value='" . $row['weekly_sunday'] . "'></td>

                        ";
                    }else if ($day == "Tuesday") {
                        echo "
                            <td><div>" . $row['weekly_tuesday'] . "</div><input type='text' id='change_tuesday' size='1' hidden value='" . $row['weekly_tuesday'] . "'></td>
                            <td><div>" . $row['weekly_wednesday'] . "</div><input type='text' id='change_wednesday' size='1' hidden value='" . $row['weekly_wednesday'] . "'></td>
                            <td><div>" . $row['weekly_thursday'] . "</div><input type='text' id='change_thursday' size='1' hidden value='" . $row['weekly_thursday'] . "'></td>
                            <td><div>" . $row['weekly_friday'] . "</div><input type='text' id='change_friday' size='1' hidden value='" . $row['weekly_friday'] . "'></td>
                            <td><div>" . $row['weekly_saturday'] . "</div><input type='text' id='change_saturday' size='1' hidden value='" . $row['weekly_saturday'] . "'></td>
                            <td><div>" . $row['weekly_sunday'] . "</div><input type='text' id='change_sunday' size='1' hidden value='" . $row['weekly_sunday'] . "'></td>
                            <td><div>" . $row['weekly_monday'] . "</div><input type='text' id='change_monday' size='1' hidden value='" . $row['weekly_monday'] . "'></td>
                            <td>" . $row['weekly_total'] . "</td>
                             <td><div>" . $row['weekly_wednesday'] . "</div><input type='text' id='change_wednesday' size='1' hidden value='" . $row['weekly_wednesday'] . "'></td>
                            <td><div>" . $row['weekly_thursday'] . "</div><input type='text' id='change_thursday' size='1' hidden value='" . $row['weekly_thursday'] . "'></td>
                            <td><div>" . $row['weekly_friday'] . "</div><input type='text' id='change_friday' size='1' hidden value='" . $row['weekly_friday'] . "'></td>
                            <td><div>" . $row['weekly_saturday'] . "</div><input type='text' id='change_saturday' size='1' hidden value='" . $row['weekly_saturday'] . "'></td>
                            <td><div>" . $row['weekly_sunday'] . "</div><input type='text' id='change_sunday' size='1' hidden value='" . $row['weekly_sunday'] . "'></td>
                            <td><div>" . $row['weekly_monday'] . "</div><input type='text' id='change_monday' size='1' hidden value='" . $row['weekly_monday'] . "'></td>

                        ";
                    }else if ($day == "Wednesday") {
                        echo "
                            <td><div>" . $row['weekly_wednesday'] . "</div><input type='text' id='change_wednesday' size='1' hidden value='" . $row['weekly_wednesday'] . "'></td>
                            <td><div>" . $row['weekly_thursday'] . "</div><input type='text' id='change_thursday' size='1' hidden value='" . $row['weekly_thursday'] . "'></td>
                            <td><div>" . $row['weekly_friday'] . "</div><input type='text' id='change_friday' size='1' hidden value='" . $row['weekly_friday'] . "'></td>
                            <td><div>" . $row['weekly_saturday'] . "</div><input type='text' id='change_saturday' size='1' hidden value='" . $row['weekly_saturday'] . "'></td>
                            <td><div>" . $row['weekly_sunday'] . "</div><input type='text' id='change_sunday' size='1' hidden value='" . $row['weekly_sunday'] . "'></td>
                            <td><div>" . $row['weekly_monday'] . "</div><input type='text' id='change_monday' size='1' hidden value='" . $row['weekly_monday'] . "'></td>
                            <td>" . $row['weekly_total'] . "</td>
                             <td><div>" . $row['weekly_wednesday'] . "</div><input type='text' id='change_wednesday' size='1' hidden value='" . $row['weekly_wednesday'] . "'></td>
                            <td><div>" . $row['weekly_thursday'] . "</div><input type='text' id='change_thursday' size='1' hidden value='" . $row['weekly_thursday'] . "'></td>
                            <td><div>" . $row['weekly_friday'] . "</div><input type='text' id='change_friday' size='1' hidden value='" . $row['weekly_friday'] . "'></td>
                            <td><div>" . $row['weekly_saturday'] . "</div><input type='text' id='change_saturday' size='1' hidden value='" . $row['weekly_saturday'] . "'></td>
                            <td><div>" . $row['weekly_sunday'] . "</div><input type='text' id='change_sunday' size='1' hidden value='" . $row['weekly_sunday'] . "'></td>
                            <td><div>" . $row['weekly_monday'] . "</div><input type='text' id='change_monday' size='1' hidden value='" . $row['weekly_monday'] . "'></td>
                            <td><div>" . $row['weekly_tuesday'] . "</div><input type='text' id='change_tuesday' size='1' hidden value='" . $row['weekly_tuesday'] . "'></td>

                        ";
                    }else if ($day == "Thursday") {
                        echo "
                            <td><div>" . $row['weekly_thursday'] . "</div><input type='text' id='change_thursday' size='1' hidden value='" . $row['weekly_thursday'] . "'></td>
                            <td><div>" . $row['weekly_friday'] . "</div><input type='text' id='change_friday' size='1' hidden value='" . $row['weekly_friday'] . "'></td>
                            <td><div>" . $row['weekly_saturday'] . "</div><input type='text' id='change_saturday' size='1' hidden value='" . $row['weekly_saturday'] . "'></td>
                            <td><div>" . $row['weekly_sunday'] . "</div><input type='text' id='change_sunday' size='1' hidden value='" . $row['weekly_sunday'] . "'></td>
                            <td><div>" . $row['weekly_monday'] . "</div><input type='text' id='change_monday' size='1' hidden value='" . $row['weekly_monday'] . "'></td>
                            <td>" . $row['weekly_total'] . "</td>
                             <td><div>" . $row['weekly_wednesday'] . "</div><input type='text' id='change_wednesday' size='1' hidden value='" . $row['weekly_wednesday'] . "'></td>
                            <td><div>" . $row['weekly_thursday'] . "</div><input type='text' id='change_thursday' size='1' hidden value='" . $row['weekly_thursday'] . "'></td>
                            <td><div>" . $row['weekly_friday'] . "</div><input type='text' id='change_friday' size='1' hidden value='" . $row['weekly_friday'] . "'></td>
                            <td><div>" . $row['weekly_saturday'] . "</div><input type='text' id='change_saturday' size='1' hidden value='" . $row['weekly_saturday'] . "'></td>
                            <td><div>" . $row['weekly_sunday'] . "</div><input type='text' id='change_sunday' size='1' hidden value='" . $row['weekly_sunday'] . "'></td>
                            <td><div>" . $row['weekly_monday'] . "</div><input type='text' id='change_monday' size='1' hidden value='" . $row['weekly_monday'] . "'></td>
                            <td><div>" . $row['weekly_tuesday'] . "</div><input type='text' id='change_tuesday' size='1' hidden value='" . $row['weekly_tuesday'] . "'></td>
                            <td><div>" . $row['weekly_wednesday'] . "</div><input type='text' id='change_wednesday' size='1' hidden value='" . $row['weekly_wednesday'] . "'></td>

                        ";
                    }else {
                        echo "
                            <td><div>" . $row['weekly_friday'] . "</div><input type='text' id='change_friday' size='1' hidden value='" . $row['weekly_friday'] . "'></td>
                            <td><div>" . $row['weekly_saturday'] . "</div><input type='text' id='change_saturday' size='1' hidden value='" . $row['weekly_saturday'] . "'></td>
                            <td><div>" . $row['weekly_sunday'] . "</div><input type='text' id='change_sunday' size='1' hidden value='" . $row['weekly_sunday'] . "'></td>
                            <td><div>" . $row['weekly_monday'] . "</div><input type='text' id='change_monday' size='1' hidden value='" . $row['weekly_monday'] . "'></td>
                            <td>" . $row['weekly_total'] . "</td>
                             <td><div>" . $row['weekly_wednesday'] . "</div><input type='text' id='change_wednesday' size='1' hidden value='" . $row['weekly_wednesday'] . "'></td>
                            <td><div>" . $row['weekly_thursday'] . "</div><input type='text' id='change_thursday' size='1' hidden value='" . $row['weekly_thursday'] . "'></td>
                            <td><div>" . $row['weekly_friday'] . "</div><input type='text' id='change_friday' size='1' hidden value='" . $row['weekly_friday'] . "'></td>
                            <td><div>" . $row['weekly_saturday'] . "</div><input type='text' id='change_saturday' size='1' hidden value='" . $row['weekly_saturday'] . "'></td>
                            <td><div>" . $row['weekly_sunday'] . "</div><input type='text' id='change_sunday' size='1' hidden value='" . $row['weekly_sunday'] . "'></td>
                            <td><div>" . $row['weekly_monday'] . "</div><input type='text' id='change_monday' size='1' hidden value='" . $row['weekly_monday'] . "'></td>
                            <td><div>" . $row['weekly_tuesday'] . "</div><input type='text' id='change_tuesday' size='1' hidden value='" . $row['weekly_tuesday'] . "'></td>
                            <td><div>" . $row['weekly_wednesday'] . "</div><input type='text' id='change_wednesday' size='1' hidden value='" . $row['weekly_wednesday'] . "'></td>
                            <td><div>" . $row['weekly_thursday'] . "</div><input type='text' id='change_thursday' size='1' hidden value='" . $row['weekly_thursday'] . "'></td>

                        ";


                    }
                echo "<th>" . $weekly_total . "<input type='text' name='weekly_total' value='" . $weekly_total ."' hidden></th><td>
                <button type='button'  data-toggle='modal' data-target= '#updatetimesheet". $row['weekly_ID'] ."' id='edit_row' class='btn btn-primary btn-block' onclick='allowEdit()' value=' " . $row['weekly_ID'] . " '><i class='fa fa-edit'></i> Edit</button>
                <button type='button' data-toggle='modal' data-target= '#verifyDelete". $row['weekly_ID'] ."'  id='deleteProject' class='btn btn-primary btn-block' value=' " . $row['weekly_ID'] . " ' name='weeklyID'><i class='fa fa-close'></i> Delete</button>
                <button type='button'  data-toggle='modal' data-target= '#updatetimesheet' id='edit_row' class='btn btn-primary btn-block' onclick='allowEdit()' hidden><i class='fa fa-save'></i> Save</button>
                <button type='button'  data-toggle='modal' data-target= '#updatetimesheet' id='edit_row' class='btn btn-primary btn-block' onclick='allowEdit()' hidden><i class='fa fa-close'></i> Cancel</button>



                </td>";
            ?>
                                   <td> <input type="checkbox" name="utilization[]" value="<?php echo $row['weekly_ID']; ?>" checked hidden>
<!--                                       <?php
//            echo $row['weekly_ID'];
?>-->
                                    <td>
                                    <?php
                    ?>

<!--_______________________MODALS IN LOOP________________________________                                    -->


<div class="modal fade example-modal-lg" aria-hidden="true" aria-labelledby="exampleOptionalLarge"
    role="dialog" tabindex="-1" id="updatetimesheet<?php echo $row['weekly_ID']; ?>">
    <div class="modal-dialog modal-simple modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="exampleOptionalLarge">Update Utilizations</h4>
        </div>
        <div class="modal-body">
                            <form class="form-horizontal" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="edit_contact" class="col-sm-12 control-label"><i class="fa fa-book mr-1"></i>Project Code </label>
                          <div class="col-sm-12">
                            <input type="text" class="form-control" readonly value='<?php echo $row ['project_name'];?>'><!--akjhgkjadhflkjsadhflka-->
                          </div>
                        </div>
                      </div>
                    </div>
                                     <div class="row">
                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="work" class="col-sm-6 control-label"><i class="fa fa-book mr-1"></i>Work Type</label>
                            <div class="col-sm-12">
                            <select name="workType" class="form-control">
                            <?php
                        $query_work = "SELECT * FROM tbl_worktype";
                                $stmt_work = $con->prepare($query_work);
                                    $stmt_work->execute();
                                    $num_work = $stmt_work->rowCount();

                                    while($row_work = $stmt_work->fetch(PDO::FETCH_ASSOC)){
                                        echo '<option value='. $row_work['work_ID'] . '>' . $row_work['work_name'] . '</option>';
                                    }
                            ?>
                                </select>
                                </div>
                        </div>
                      </div>
                      <div class="col-md-6">

                        <label for="activity" class="col-sm-12 control-label"><i class="fa fa-book mr-1"></i>Task Code</label>
                            <div class="col-sm-12">
                                <select name="activity" class="form-control">
                                    <?php
                                    if($row['project_name'] == "Admin") {
                                        $query_work = "SELECT * FROM tbl_activityadmin";
                                    $stmt_work = $con->prepare($query_work);
                                    $stmt_work->execute();
                                    $num_work = $stmt_work->rowCount();

                                    while($row_work = $stmt_work->fetch(PDO::FETCH_ASSOC)){
                                        echo '<option value='. $row_work['activityAdmin_ID'] . '>' . $row_work['activityAdmin_name'] . '</option>';

                                    }

                                    } else {
                                     $query_work = "SELECT * FROM tbl_activityothers";
                                $stmt_work = $con->prepare($query_work);
                                    $stmt_work->execute();
                                    $num_work = $stmt_work->rowCount();

                                    while($row_work = $stmt_work->fetch(PDO::FETCH_ASSOC)){
                                        echo '<option value='. $row_work['activityOthers_ID'] . '>' . $row_work['activityOthers_name'] . '</option>';
                                    }
                                    }

                                ?>
                                </select>

                        </div>
                    </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description" class="col-sm-12"><i class="fa fa-book mr-1"></i>Comments</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="description" name="description" value="<?php echo $row['weekly_description'];?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_contact" class="col-sm-12 control-label"><i class="fa fa-book mr-1"></i>Location</label>
                                <div class="col-sm-12">



                                <select name="location" class="form-control">
                                    <?php
                                     $query_work = "SELECT * FROM tbl_location";
                                $stmt_work = $con->prepare($query_work);
                                    $stmt_work->execute();
                                    $num_work = $stmt_work->rowCount();

                                    while($row_work = $stmt_work->fetch(PDO::FETCH_ASSOC)){
                                        echo '<option value='. $row_work['location_ID'] . '>' . $row_work['location_name'] . '</option>';
                                    }

                                ?>
                                </select>

                        </div>
                            </div>
                        </div>
                                        <div class="row">

                            <div class="col-md-12">
<!--                                <div class="form-group">-->
                                    <label for="edit_contact" class="col-sm-6 control-label"><i class="fa fa-book mr-1"></i><b>Days</b></label>
                            </div>
                                    <div class="col-md-2">
                                    <div class="col-sm-12">
                                        Mon
                                        <input type="number" min="0" max="12" step="0.5" class="form-control" id="monday" name="monday" placeholder="Mon" value="<?php echo $row['weekly_monday']?>">
                                    </div>
                                </div>

                                    <div class="col-md-2">
                                    <div class="col-sm-12">
                                        Tue
                                        <input type="number" min="0" max="12" step="0.5" class="form-control" id="tuesday" name="tuesday" placeholder="Tue" value="<?php echo $row['weekly_tuesday']?>">
                                    </div>
                                </div>

                                    <div class="col-md-2">
                                    <div class="col-sm-12">
                                        Wed
                                        <input type="number" min="0" max="12" step="0.5" class="form-control" id="wednesday" name="wednesday" placeholder="Wed" value="<?php echo $row['weekly_wednesday']?>">
                                    </div>
                                </div>

                                    <div class="col-md-2">
                                    <div class="col-sm-12">
                                        Thu
                                        <input type="number" min="0" max="12" step="0.5" class="form-control" id="thursday" name="thursday" placeholder="Thu" value="<?php echo $row['weekly_thursday']?>">
                                    </div>
                                </div>

                                    <div class="col-md-2">
                                    <div class="col-sm-12">
                                        Fri
                                        <input type="number" min="0" max="12" step="0.5" class="form-control" id="friday" name="friday" placeholder="Fri" value="<?php echo $row['weekly_friday']?>">
                                    </div>
                                </div>
                            

<!--                                </div>-->

<!--                            </div>-->
                      </div>
                      <br>
                                                      <div class="row">
                                    <div class="col-md-2">
                                    <div class="col-sm-12">
                                        Sat
                                        <input type="number" min="0" max="12" step="0.5" class="form-control" id="saturday" name="saturday" placeholder="Sat" value="<?php echo $row['weekly_saturday'];?>">
                                    </div>
                                </div>

                                    <div class="col-md-2">
                                    <div class="col-sm-12">
                                        Sun
                                        <input type="number" min="0" max="12" step="0.5" class="form-control" id="sunday" name="sunday" placeholder="Sun" value="<?php echo $row['weekly_sunday'];?>">
                                    </div>
                                </div>
                                </div>
                                    <input type="text" name="weeklyID" hidden value="<?php echo $row['weekly_ID']?>">
        </div>
                        <div class="modal-footer">
                  <button type="button" class="btn btn-default  pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                  <button type="submit" class="btn btn-success " name="updateTimesheets"> <i class="fa fa-check-square-o" ></i> Update</button>

                </div>
                      </form>
      </div>
    </div>
  </div>

    </div>


<div class="modal fade" id="verifyDelete<?php echo $row['weekly_ID']; ?>">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                 <h4 class="modal-title">Delete Project</h4>
                     
                <h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
            </div>
             <div class="modal-body">
                 <label class=" col-form-label">Are you sure you want to delete this project? </label>
            </div>
            <div class="modal-footer">
                 <form class="form-horizontal" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
              <button type="button"class="btn btn-default btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> No</button>
              <button type="submit" class="btn btn-primary" name="deleteProject"> <i class="fa fa-check-square-o" ></i> Delete</button>
               <input type="text" name="weeklyID" hidden value="<?php echo $row['weekly_ID']?>">
              </form>
            </div>
            </div>
            </div>
            </div>


<!--    END MODALS IN LOOP TABLE                               -->










<!--__________________________________________________________________                                    -->
                                    <?php

        }

                echo "<tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <tfooter>
                            <th>Total</th>

                            <th>" . $total_monday . "</th>
                            <th>" . $total_tuesday . "</th>
                            <th>" . $total_wednesday . "</th>
                            <th>" . $total_thursday . "</th>
                            <th>" . $total_friday . "</th>
                            <th>" . $total_saturday . "</th>
                            <th>" . $total_sunday . "</th>
                            <th>" . $overall_total . "<input type='text' name='overall_total' value='" . $overall_total ."' hidden></th>
                            <th>
                <button type='button' class='btn btn-success ' data-toggle='modal' data-target= '#submitVerify''><i class='fa fa-edit'></i> Submit</button>
             </th>
                        </tfooter>
                      </tr>";
      }
        //    }

    else {
        echo "<br/><br/><thead><h3><center>Please ADD an engagement</center></thead></h3><br/><br/>";
    }
?>




                                    <?php
//            $userID = $_SESSION['user_id'];
//            $query = "SELECT * FROM tbl_weeklyutilization WHERE employee_ID = '$userID' AND weekly_dateStart = '$weekStart' weekly_dateEnd = '$weekEnd' ";
//
//            $stmt = $con->prepare($query);
//            $stmt->execute();
//            $num = $stmt->rowCount();
//
//        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
//            echo "<tr>
//                    <td>" . $row['project_name'] . "</td>
//                    <td>" . $row['work_name'] . "</td
//                    <td>" . $row['activityOthers_name/activityAdmin_name'] . "</td
//                    <td>" . $row['description'] . "</td
//                    <td>" . $row['location'] . "</td
//
//
//            ";
//
//        }
                                        ?>


                                    </table>
                                        </form>
                            </div>
                    </div>
                </div>
            </div>
        </div>


    </section>

    <!-- Modals Engagements-->
   <div class="modal fade" id="addEngagements" aria-hidden="false" aria-labelledby="exampleFormModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="card-title">Available Engagements</h3>

                </div>
                <div class="modal-body">
                    <form class="form-horizontal" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">


                            <?php
      // select all data
      $query = "SELECT * FROM tbl_project";
      $stmt = $con->prepare($query);
      $stmt->execute();
      $num = $stmt->rowCount();
      // check if more than 0 record found
      if($num>0){
          echo '<select name="projectName" id="projectName" class="form-control select2" style="width: 100%">';
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          ?>

                            <option value="<?php echo $row['project_ID'];?>"><?php echo $row['project_name'];?></option>
        <?php
                            }
          echo '</select>';
      }
                            else{
                                echo "No available projects found";
                            }

                ?>


                </div>
                  <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-success " name="addEngagement"> <i class="fa fa-check-square-o" ></i> Add</button>
                      </div>
              </form>

                </div>
            </div>
        </div>

</div>

        </div>
    </div>

              <!--added divs-->
            </div>
          </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>

        <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane active" id="tab_2">

<!--            </div>-->
             <div class="card-body table-responsive p-0">
                                <!-- Start Viewing timehseets table -->
                                <table id='example2' class='table table-hover table-striped'>
    <?php
        $userID = $_SESSION['user_id'];
                                    echo $userID;
        $query =
            "SELECT * FROM tbl_submittedtimesheets INNER JOIN tbl_weeklyutilization ON tbl_submittedtimesheets.weekly_endDate = tbl_weeklyutilization.weekly_endDate WHERE tbl_submittedtimesheets.employee_ID = ' . $userID . ' ";


            $stmt = $con->prepare($query);
            $stmt->execute();
//            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $num = $stmt->rowCount();

            if($num>0){

                //while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                        <thead>
                        <th>Week Ending</th>
                        <th>Total Hours</th>
                        <th>Date Submitted</th>
                        <th>Status</th>
                        <th>Date Processed</th>
                        </thead>
                    </tr>
                  <?php
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                ?>
                    <tr>
                        <td><div data-toggle='modal' data-target=''> <?php $row['weekly_endDate'];?></div></td>
                        <td> <?php $row['weekly_totalHours'];?></td>
                        <td> <?php $row['timesheet_dateSubmitted'];?></td>
                        <td> <?php $row['timesheet_status'];?></td>
                        <td> <?php $row['timesheet_dateProcessed'];?></td>
                    </tr>
    <?php


                }


            } else {
//                echo "No Timesheets found.";
            }

    ?>
                 </table>
            </div>
            </div>

<!--
                  <script>
                  $(function() {
        $('#edit_row').on('click', function() {
            $('#current_saturday').hide();
            $('#change_saturday').show();
        });

        $('#change_saturday').on('blur', function() {
            var that = $(this);
            $('#current_saturday').text(that.val()).show();
            that.hide();
        });
    });
                  </script>
-->


<!-- MODALS -->


<div class="modal fade" id="submitVerify">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="card-title">Submit Timesheets?</h3>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b><span class="employee_id"></span></b></h4>
            </div>
                <div class="modal-footer">
                      <form class="form-horizontal" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <div class="row">
<!--                          <div class="col-md-6">-->
                            <div class="form-group">

                              <div class="col-md-3">

<!--HIDDEN CODE-->

                    <?php
    $userID = $_SESSION['user_id'];
    $query_hidden = "SELECT * FROM tbl_weeklyutilization
                    WHERE employeeCode = '$userID' AND weekly_startDate = '$weekStart' AND weekly_endDate = '$weekEnd';
                    ";
    $stmt = $con->prepare($query_hidden);
            $stmt->execute();

     while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    ?>

    <input type="check" checked name="utilization[]" hidden value="<?php echo $row['weekly_ID'];?>">


    <?php
     }
    ?>

<!--END HIDDEN CODE-->


                                <button type="submit" class="btn btn-success btn-flat btn-lg "  name="submitTimesheet"><i class="fa fa-check-square-o"></i> Yes</button>
<!--                                      </div>-->
<!--                                   <div class="col-md-3 ml-auto">-->
                                  <button type="submit" class="btn btn-danger btn-flat  btn-lg" data-dismiss="modal" ><i class="fa fa-times-circle-o"></i> No</button>
                               <input type="text" name="overall_total" hidden value="<?php echo $overall_total;?>">
<!--                                </div>-->

                              </div>




<!--        </div>-->

    </div>
                          </form>
</div>
<!--end modals-->


                    </div>
                 	
        </div>
      </div>
    </div>
    <!-- End Page -->


<!-- ADD MODAL -->
<div class="modal fade" id="editor-modal" tabindex="-1" role="dialog" aria-labelledby="editor-title">
	<style scoped>
		/* provides a red astrix to denote required fields - this should be included in common stylesheet */
		.form-group.required .control-label:after {
			content:"*";
			color:red;
			margin-left: 4px;
		}
	</style>
	<div class="modal-dialog" role="document">
		<form class="modal-content form-horizontal" id="editor">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="editor-title">Add Row</h4>
			</div>
			<div class="modal-body">
				 <!--<input type="number" id="id" name="id" class="hidden"/> -->
				<div class="form-group required">
					<label for="firstName" class="col-sm-3 control-label">Project Code</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="projectCode" name="projectCode" placeholder="Project Code" required>
					</div>
				</div>
				<div class="form-group required">
					<label for="lastName" class="col-sm-3 control-label">Work Type</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="worktype" name="worktype" placeholder="Work Type" required>
					</div>
				</div>
				<div class="form-group">
					<label for="jobTitle" class="col-sm-3 control-label">Task Code</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="taskCode" name="taskCode" placeholder="Task Code">
					</div>
				</div>
				<div class="form-group required">
					<label for="startedOn" class="col-sm-3 control-label">Comments</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="comments" name="comments" placeholder="Comments" required>
					</div>
				</div>
				<div class="form-group">
					<label for="dob" class="col-sm-3 control-label">Location</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="location" name="location" placeholder="Location" required>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">Save changes</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			</div>
		</form>
	</div>
</div>
<!-- END ADD MODAL-->

    <!-- Footer -->
<?php
include ('../includes/footer.php');
include ('../includes/scripts.php');
//------
include ('includes/form_scripts.php');
//------ 
 ?>
 
 <script>
    function allowEdit(){
        document.getElementById("change_day").disabled = 'false';
    }
</script>


//  <script>

// var $modal = $('#editor-modal'),
// 	$editor = $('#editor'),
// 	$editorTitle = $('#editor-title'),
// 	ft = FooTable.init('#editing-example', {
// 		editing: {
// 			enabled: true,
// 			addRow: function(){
// 				$modal.removeData('row');
// 				$editor[0].reset();
// 				$editorTitle.text('Add a new row');
// 				$modal.modal('show');
// 			},
// 			editRow: function(row){
// 				var values = row.val();
// 				$editor.find('#id').val(values.id);
// 				$editor.find('#projectCode').val(values.projectCode);
// 				$editor.find('#worktype').val(values.worktype);
// 				$editor.find('#taskCode').val(values.taskCode);
// 				$editor.find('#comments').val(values.comments);
// 				$editor.find('#location').val(values.location);

// 				$modal.data('row', row);
// 				$editorTitle.text('Edit row #' + values.id);
// 				$modal.modal('show');
// 			},
// 			deleteRow: function(row){
// 				if (confirm('Are you sure you want to delete the row?')){
// 					row.delete();
// 				}
// 			}
// 		}
// 	}),
// 	uid = 10;

// $editor.on('submit', function(e){
// 	if (this.checkValidity && !this.checkValidity()) return;
// 	e.preventDefault();
// 	var row = $modal.data('row'),
// 		values = {
// 			id: $editor.find('#id').val(),
// 			projectCode: $editor.find('#projectCode').val(),
// 			worktype: $editor.find('#worktype').val(),
// 			taskCode: $editor.find('#taskCode').val(),
// 			comments: $editor.find('#comments').val(),
// 		    location: $editor.find('#location').val(),
			
// 		};

// 	if (row instanceof FooTable.Row){
// 		row.val(values);
// 	} else {
// 		values.id = uid++;
// 		ft.rows.add(values);
// 	}
// 	$modal.modal('hide');
// });
//  </script>

// <script type="text/javascript">
// $(document).ready(function ($) {
    
//     ////
//     //// try code
// //source link = https://www.youtube.com/watch?v=vYoGKtdl7dQ

//   //ajax row data
   
// 	var ajax_data =
// 	[
// 		{fname:"Code", lname:"With Mark", email:"mark@codewithmark.com"}, 
// 		{fname:"Mary", lname:"Moe", email:"mary@gmail.com"},
// 		{fname:"John", lname:"Doe", email:"john@yahoo.com"},
// 		{fname:"Julie", lname:"Dooley", email:"julie@gmail.com"},
// 	]
	
// 	  var random_id = function  () 
// 	{
// 		var id_num = Math.random().toString(9).substr(2,3);
// 		var id_str = Math.random().toString(36).substr(2);
		
// 		return id_num + id_str;
// 	}


// 	//--->create data table > start
// 	var tbl = '';
// 	tbl +='<table class="table table-hover">'

// 		//--->create table header > start
// 		tbl +='<thead>';
// 			tbl +='<tr>';
// 			tbl +='<th>First Name</th>';
// 			tbl +='<th>Last Name</th>';
// 			tbl +='<th>Email</th>';
// 			tbl +='<th>Options</th>';
// 			tbl +='</tr>';
// 		tbl +='</thead>';
// 		//--->create table header > end

		
// 		//--->create table body > start
// 		tbl +='<tbody>';

// 			//--->create table body rows > start
// 			$.each(ajax_data, function(index, val) 
// 			{
// 				//you can replace with your database row id
// 				var row_id = random_id();

// 				//loop through ajax row data
// 				tbl +='<tr row_id="'+row_id+'">';
// 					tbl +='<td ><div class="row_data" edit_type="click" col_name="fname">'+val['fname']+'</div></td>';
// 					tbl +='<td ><div class="row_data" edit_type="click" col_name="lname">'+val['lname']+'</div></td>';
// 					tbl +='<td ><div class="row_data" edit_type="click" col_name="email">'+val['email']+'</div></td>';

// 					//--->edit options > start
// 					tbl +='<td>';
					 
// 						tbl +='<span class="btn_edit" > <a href="#" class="btn btn-link " row_id="'+row_id+'" > Edit</a> </span>';

// 						//only show this button if edit button is clicked
// 						tbl +='<span class="btn_save"> <a href="#" class="btn btn-link"  row_id="'+row_id+'"> Save</a> | </span>';
// 						tbl +='<span class="btn_cancel"> <a href="#" class="btn btn-link" row_id="'+row_id+'"> Cancel</a> | </span>';

// 					tbl +='</td>';
// 					//--->edit options > end
					
// 				tbl +='</tr>';
// 			});

// 			//--->create table body rows > end

// 		tbl +='</tbody>';
// 		//--->create table body > end

// 	tbl +='</table>'	
// 	//--->create data table > end
	
	
// 	//out put table data
// $(document).find('.tbl_user_data').html(tbl);

// $(document).find('.btn_save').hide();
// $(document).find('.btn_cancel').hide(); 

// //--->make div editable > start
// $(document).on('click', '.row_data', function(event) 
// {
// 	event.preventDefault(); 

// 	if($(this).attr('edit_type') == 'button')
// 	{
// 		return false; 
// 	}

// 	//make div editable
// 	$(this).closest('div').attr('contenteditable', 'true');
// 	//add bg css
// 	$(this).addClass('bg-warning').css('padding','5px');

// 	$(this).focus();
// })	
// //--->make div editable > end


// //--->button > edit > start	
// $(document).on('click', '.btn_edit', function(event) 
// {
// 	event.preventDefault();
// 	var tbl_row = $(this).closest('tr');

// 	var row_id = tbl_row.attr('row_id');

// 	tbl_row.find('.btn_save').show();
// 	tbl_row.find('.btn_cancel').show();

// 	//hide edit button
// 	tbl_row.find('.btn_edit').hide(); 

// 	//make the whole row editable
// 	tbl_row.find('.row_data')
// 	.attr('contenteditable', 'true')
// 	.attr('edit_type', 'button')
// 	.addClass('bg-warning')
// 	.css('padding','3px')

// 	//--->add the original entry > start
// 	tbl_row.find('.row_data').each(function(index, val) 
// 	{  
// 		//this will help in case user decided to click on cancel button
// 		$(this).attr('original_entry', $(this).html());
// 	}); 		
// 	//--->add the original entry > end

// });
// //--->button > edit > end


//  //--->button > cancel > start	
// $(document).on('click', '.btn_cancel', function(event) 
// {
// 	event.preventDefault();

// 	var tbl_row = $(this).closest('tr');

// 	var row_id = tbl_row.attr('row_id');

// 	//hide save and cacel buttons
// 	tbl_row.find('.btn_save').hide();
// 	tbl_row.find('.btn_cancel').hide();

// 	//show edit button
// 	tbl_row.find('.btn_edit').show();

// 	//make the whole row editable
// 	tbl_row.find('.row_data')
// 	.attr('edit_type', 'click')	 
// 	.removeClass('bg-warning')
// 	.css('padding','') 

// 	tbl_row.find('.row_data').each(function(index, val) 
// 	{   
// 		$(this).html( $(this).attr('original_entry') ); 
// 	});  
// });
// //--->button > cancel > end


// //--->save whole row entery > start	
// $(document).on('click', '.btn_save', function(event) 
// {
// 	event.preventDefault();
// 	var tbl_row = $(this).closest('tr');

// 	var row_id = tbl_row.attr('row_id');

	
// 	//hide save and cacel buttons
// 	tbl_row.find('.btn_save').hide();
// 	tbl_row.find('.btn_cancel').hide();

// 	//show edit button
// 	tbl_row.find('.btn_edit').show();


// 	//make the whole row editable
// 	tbl_row.find('.row_data')
// 	.attr('edit_type', 'click')	
// 	.removeClass('bg-warning')
// 	.css('padding','') 

// 	//--->get row data > start
// 	var arr = {}; 
// 	tbl_row.find('.row_data').each(function(index, val) 
// 	{   
// 		var col_name = $(this).attr('col_name');  
// 		var col_val  =  $(this).html();
// 		arr[col_name] = col_val;
// 	});
// 	//--->get row data > end

// 	//use the "arr"	object for your ajax call
// 	$.extend(arr, {row_id:row_id});

// 	//out put to show
// 	$('.post_msg').html( '<pre class="bg-success">'+JSON.stringify(arr, null, 2) +'</pre>')
	 

// });
// //--->save whole row entery > end


// //--->save single field data > start
// $(document).on('focusout', '.row_data', function(event) 
// {
// 	event.preventDefault();

// 	if($(this).attr('edit_type') == 'button')
// 	{
// 		return false; 
// 	}

// 	var row_id = $(this).closest('tr').attr('row_id'); 
	
// 	var row_div = $(this)			
// 	.removeClass('bg-warning') //add bg css
// 	.css('padding','')

// 	var col_name = row_div.attr('col_name'); 
// 	var col_val = row_div.html(); 

// 	var arr = {};
// 	arr[col_name] = col_val;

// 	//use the "arr"	object for your ajax call
// 	$.extend(arr, {row_id:row_id});

// 	//out put to show
// 	$('.post_msg').html( '<pre class="bg-success">'+JSON.stringify(arr, null, 2) +'</pre>');
	
// })	
// //--->save single field data > end


// ////end try code
    

//     var counter = 0;

//     $("#addrow").on("click", function () {
//         var newRow = $("<tr>");
//         var cols = "";

//         // cols += '<td><input type="text" class="form-control" name="name' + counter + '"/></td>';
//         // cols += '<td><input type="text" class="form-control" name="mail' + counter + '"/></td>';
//         // cols += '<td><input type="text" class="form-control" name="phone' + counter + '"/></td>';
//         cols += '<td>test</td>';
//           cols += '<td>test</td>';
//           cols += '<td>test</td>';
//             cols += '<td>test</td>';
//              cols += '<td>test</td>';
//               cols += '<td>test</td>';
//               cols += '<td>test</td>';
//                 cols += '<td>test</td>';
//                  cols += '<td>test</td>';
//                   cols += '<td>test</td>';
//                   cols += '<td>test</td>';
        

//         cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
//         newRow.append(cols);
//         $("table.editable-table").append(newRow);
//         counter++;
//     });



//     $("table.editable-table").on("click", ".ibtnDel", function (event) {
//         $(this).closest("tr").remove();
//         counter -= 1
//     });


// });





// </script>

  </body>
</html>
