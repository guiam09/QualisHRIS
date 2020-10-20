<?php
include_once ('../includes/configuration.php');
include ('../db/connection.php');
include ('../includes/fetchImage.php');
$getData = getImage($con, $_SESSION['user_id']);
?>

<nav class="site-navbar navbar navbar-default navbar-fixed-top" role="navigation">
  <!-- navbar header -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggler hamburger hamburger-close navbar-toggler-left hided"
      data-toggle="menubar">
      <span class="sr-only">Toggle navigation</span>
      <span class="hamburger-bar"></span>
    </button>
    <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-collapse"
      data-toggle="collapse">
      <i class="icon md-more" aria-hidden="true"></i>
    </button>
    <div style="background-color:hsla(0,0%,0%,0.8) !important" class="navbar-brand navbar-brand-center site-gridmenu-toggle" >
      <img class="navbar-brand-logo" src="../images/qualisQ_trans.png" title="Qualis">
      <span class="navbar-brand-text hidden-xs-down"> Qualis Consulting, Inc.</span>
    </div>
    <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-search"
      data-toggle="collapse">
      <span class="sr-only">Toggle Search</span>
      <i class="icon md-search" aria-hidden="true"></i>
    </button>
    
  </div>


  <div class="navbar-container container-fluid">
    <!-- Navbar Collapse -->
    <div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
        
      <!-- Navbar Toolbar -->
      
      <!-- July 10, 2019 UPDATE: toggle Menu collapse relocated to sidebar -->
      
      <!--<ul class="nav navbar-toolbar">-->
      <!--  <li class="nav-item hidden-float" id="toggleMenubar">-->
      <!--    <a class="nav-link" data-toggle="menubar" href="#" role="button">-->
      <!--      <i class="icon hamburger hamburger-arrow-left">-->
      <!--        <span class="sr-only">Toggle menubar</span>-->
      <!--        <span class="hamburger-bar"></span>-->
      <!--      </i>-->
      <!--    </a>-->
      <!--  </li>-->
        <!-- <li class="nav-item hidden-sm-down" id="toggleFullscreen">
      <!--    <a class="nav-link icon icon-fullscreen" data-toggle="fullscreen" href="#" role="button">-->
      <!--      <span class="sr-only">Toggle fullscreen</span>-->
      <!--    </a>-->
      <!--  </li> -->
        <!--<li class="nav-item hidden-float">-->
        <!--  <a class="nav-link icon md-search" data-toggle="collapse" href="#" data-target="#site-navbar-search"-->
        <!--    role="button">-->
        <!--    <span class="sr-only">Toggle Search</span>-->
        <!--  </a>-->
        <!--</li>-->
           
      <!--</ul>-->
      
      <!-- End of July 10, 2019 UPDATE: toggle Menu collapse relocated to sidebar -->
      
      <!-- End Navbar Toolbar -->

      <!-- Navbar Toolbar Right -->
        
      <ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">
          <a class="nav-link font-size-14 ">Location: <?php $loc = ($_SERVER['REMOTE_ADDR'] == '116.50.246.10' ? 'Office' : 'WR');
          echo '<span class="text-info"><b>'.$loc.'</b></span>';?></a>
        <li class="nav-item dropdown">
          <a class="nav-link navbar-avatar" data-toggle="dropdown" href="#" aria-expanded="false"
            data-animation="scale-up" role="button">
            <span class="avatar ">
              <img  src="../images/<?php echo $getData['photo'] ?>" height="900" width="900" alt="Current User">
             <!--  -->
              <!--<i></i>--> <!-- July 10, 2019 UPDATE: Remove online indicators -->
            </span>
          </a>
          <div class="dropdown-menu" role="menu">
              <a class="dropdown-item" style="text-align:center">
                  <?php
                    $userID = $_SESSION['user_id'];
                    
                    $query_employeeName = "SELECT * from tbl_employees WHERE employeeCode = '$userID'";
                    $stmt_employeeName = $con->prepare($query_employeeName);
                    $stmt_employeeName->execute();
                    
                    while ($row_employeeName = $stmt_employeeName->fetch(PDO::FETCH_ASSOC)){
                        echo $row_employeeName ['firstName'].' '.$row_employeeName ['lastName'];
                    }
                  ?>
              </a>
            <div class="dropdown-divider" role="presentation"></div>
            <a class="dropdown-item" href="../admin/account_settings.php" role="menuitem"><i class="icon fa-id-card" aria-hidden="true"></i> Account Settings</a>

            <div class="dropdown-divider" role="presentation"></div>
            
            <!-- July 11, 2019 UPDATE: Logout Confirmation -->
            <form id='confirmLogOut' method='POST' action='../includes/logout.php'>
                <a class="dropdown-item" href="#" onclick="logoutConfirmation()" role="menuitem"><i class="icon fa-sign-out" aria-hidden="true"></i> Logout</a>
            </form>
            
            <!-- ORIGINAL -->
            <!--<a class="dropdown-item" href="../includes/logout.php"  role="menuitem"><i class="icon fa-sign-out" aria-hidden="true"></i> Logout</a>-->
            <!-- END ORIGINAL -->
            
            <!-- END July 11, 2019 UPDATE: Logout Confirmation -->
          </div>
        </li>
 

      </ul>
      <!-- End Navbar Toolbar Right -->
    </div>
    <!-- End Navbar Collapse -->

    <!-- Site Navbar Seach -->
    <!--<div class="collapse navbar-search-overlap" id="site-navbar-search">-->
    <!--  <form role="search">-->
    <!--    <div class="form-group">-->
    <!--      <div class="input-search">-->
    <!--        <i class="input-search-icon md-search" aria-hidden="true"></i>-->
    <!--        <input type="text" class="form-control" name="site-search" placeholder="Search...">-->
    <!--        <button type="button" class="input-search-close icon md-close" data-target="#site-navbar-search"-->
    <!--          data-toggle="collapse" aria-label="Close"></button>-->
    <!--      </div>-->
    <!--    </div>-->
    <!--  </form>-->
    <!--</div>-->
    <!-- End Site Navbar Seach -->
  </div>
</nav>

<!-- July 11, 2019 UPDATE: Logout Confirmation Script -->
<!--<form name="logoutform" method="post" action="../includes/logout.php" id="logoutform">-->
<!--    <input type="hidden" name="form_name" value="logoutform">-->
<!--    <button class="logoutform_button" type="submit" name="logout" value="signOut" id="logout"/></button>-->
<!--</form>-->
<style>
    .swal-overlay {
  background-color: rgba(0, 128, 128, 0.7);
}
</style>

<script>
function logoutConfirmation()   {
  
    Swal.fire({
        title:'Are you sure you want to logout?',
        type:'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, I\'m sure'
    }).then((result) => {
        if(result.value){
            swal(
                "Signing Out. . .",{
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                    buttons: false
                }
            )
            document.getElementById('confirmLogOut').submit();
        }
    });
}


</script>
<!-- End July 11, 2019 UPDATE: Logout Confirmation Script -->