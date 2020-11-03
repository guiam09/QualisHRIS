<?php include ('page_title.php')?>
<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="hris">
    <meta name="author" content="jhanz">

    <title><?php echo $PAGE_TITLE ?></title>

    <link rel="apple-touch-icon" href="../assets/images/apple-touch-icon.png">
    <link rel="shortcut icon" href="../images/qualisQ_trans.png">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="../global/css/bootstrap.min.css">
    <link rel="stylesheet" href="../global/css/bootstrap-extend.min.css">
    <link rel="stylesheet" href="../assets/css/site.min.css">

    <!-- Plugins -->
    <link rel="stylesheet" href="../global/vendor/animsition/animsition.css">
    <link rel="stylesheet" href="../global/vendor/asscrollable/asScrollable.css">
    <link rel="stylesheet" href="../global/vendor/switchery/switchery.css">
    <link rel="stylesheet" href="../global/vendor/intro-js/introjs.css">
    <link rel="stylesheet" href="../global/vendor/slidepanel/slidePanel.css">
    <link rel="stylesheet" href="../global/vendor/flag-icon-css/flag-icon.css">
        <!-- DATATABLES -->
        <link rel="stylesheet" href="../global/vendor/datatables.net-bs4/dataTables.bootstrap4.css">
        <link rel="stylesheet" href="../global/vendor/datatables.net-fixedheader-bs4/dataTables.fixedheader.bootstrap4.css">
        <link rel="stylesheet" href="../global/vendor/datatables.net-fixedcolumns-bs4/dataTables.fixedcolumns.bootstrap4.css">
        <link rel="stylesheet" href="../global/vendor/datatables.net-rowgroup-bs4/dataTables.rowgroup.bootstrap4.css">
        <link rel="stylesheet" href="../global/vendor/datatables.net-scroller-bs4/dataTables.scroller.bootstrap4.css">
        <link rel="stylesheet" href="../global/vendor/datatables.net-select-bs4/dataTables.select.bootstrap4.css">
        <link rel="stylesheet" href="../global/vendor/datatables.net-responsive-bs4/dataTables.responsive.bootstrap4.css">
        <link rel="stylesheet" href="../global/vendor/datatables.net-buttons-bs4/dataTables.buttons.bootstrap4.css">
        <link rel="stylesheet" href="../assets/examples/css/tables/datatable.css">
        <!-- END OF DATATABLES -->
        <link rel="stylesheet" href="../global/vendor/select2/select2.css">
        <link rel="stylesheet" href="../global/vendor/bootstrap-tokenfield/bootstrap-tokenfield.css">
        <link rel="stylesheet" href="../global/vendor/bootstrap-tagsinput/bootstrap-tagsinput.css">
        <link rel="stylesheet" href="../global/vendor/bootstrap-select/bootstrap-select.css">
        <link rel="stylesheet" href="../global/vendor/icheck/icheck.css">
        <link rel="stylesheet" href="../global/vendor/switchery/switchery.css">
        <link rel="stylesheet" href="../global/vendor/asrange/asRange.css">
        <link rel="stylesheet" href="../global/vendor/ionrangeslider/ionrangeslider.min.css">
        <link rel="stylesheet" href="../global/vendor/asspinner/asSpinner.css">
        <link rel="stylesheet" href="../global/vendor/clockpicker/clockpicker.css">
        <link rel="stylesheet" href="../global/vendor/ascolorpicker/asColorPicker.css">
        <link rel="stylesheet" href="../global/vendor/bootstrap-touchspin/bootstrap-touchspin.css">
        <link rel="stylesheet" href="../global/vendor/jquery-labelauty/jquery-labelauty.css">
        <link rel="stylesheet" href="../global/vendor/bootstrap-datepicker/bootstrap-datepicker.css">
        <link rel="stylesheet" href="../global/vendor/bootstrap-maxlength/bootstrap-maxlength.css">
        <link rel="stylesheet" href="../global/vendor/timepicker/jquery-timepicker.css">
        <link rel="stylesheet" href="../global/vendor/jquery-strength/jquery-strength.css">
        <link rel="stylesheet" href="../global/vendor/multi-select/multi-select.css">
        <link rel="stylesheet" href="../global/vendor/typeahead-js/typeahead.css">
        <link rel="stylesheet" href="../assets/examples/css/forms/advanced.css">
        <link rel="stylesheet" href="../global/vendor/formvalidation/formValidation.css">
        <link rel="stylesheet" href="../assets/examples/css/forms/validation.css">
        <link rel="stylesheet" href="../global/vendor/ladda/ladda.css">
        <link rel="stylesheet" href="../assets/examples/css/uikit/buttons.css">
        <link rel="stylesheet" href="../assets/examples/css/uikit/modals.css">
        <link rel="stylesheet" href="../assets/examples/css/forms/masks.css">
          <link rel="stylesheet" href="../assets/examples/css/structure/alerts.css">
        <link rel="stylesheet" href="https://www.google.com/fonts/specimen/Roboto+Mono">
        <link rel="stylesheet" href="../global/vendor/footable/footable.core.css">
        <link rel="stylesheet" href="../assets/examples/css/tables/footable.css">
            <link rel="stylesheet" href="../assets/examples/css/widgets/statistics.css">
              <link rel="stylesheet" href="../global/vendor/editable-table/editable-table.css">
                <link rel="stylesheet" href="../global/vendor/asrange/asRange.css">
        <link rel="stylesheet" href="../assets/examples/css/uikit/icon.css">
         <link rel="stylesheet" href="../global/vendor/dropify/dropify.min.css?v4.0.2">
    <!-- Fonts -->
        <link rel="stylesheet" href="../global/fonts/font-awesome/font-awesome.css">
    <link rel="stylesheet" href="../global/fonts/web-icons/web-icons.min.css">
    <link rel="stylesheet" href="../global/fonts/brand-icons/brand-icons.min.css">
    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>
    
    <!-- Toastr -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css'>
    
    
    
    
    
    
    <!--SCRIPTS-->
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-beta1/jquery.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    
    <!--[if lt IE 9]>
    <script src="global/vendor/html5shiv/html5shiv.min.js"></script>
    <![endif]-->

    <!--[if lt IE 10]>
    <script src="global/vendor/media-match/media.match.min.js"></script>
    <script src="global/vendor/respond/respond.min.js"></script>
    <![endif]-->

    <!-- Scripts -->
    <script src="../global/vendor/breakpoints/breakpoints.js"></script>
    <script>
      Breakpoints();
    </script>
    <style>
    .clsDatePicker {
        z-index: 100000;
    }
    </style>
    <style>
      .datepicker-popover {
        z-index: 999999 !important; /* has to be larger than 1050 */
      }

  </style>
  <style>
  .timepicker{
     z-index: 99999!important;
  }
  </style>
  <style media="screen">
  .clockpicker-popover {
z-index: 999999 !important;
}
  </style>
  </head>
  <body class="animsition">
