angular
.module('hris', [])
.controller('TimesheetSummaryController', function ($scope, $http, $filter, $timeout) { 
    console.log('Start TimesheetSummaryController');
    document.title = "Timesheet Summary"

    var timesheetTable = null;

    $scope.processedTimeSheets = [];
    $scope.timesheets = [];
    // GetProcessedTimesheets();
    GetProcessedTimeSheetDetails();

    function GetProcessedTimesheets() {
        $http.get("TimeSheetManagement/getProcessedTimeSheets.php")
            .then(function (response) {
                $scope.processedTimeSheets = response.data;
                console.log($scope.processedTimeSheets);

                // for (var i = 0; i < $scope.processedTimeSheets.length; i++) {
                //     // $scope.processedTimeSheets[i].Details = GetProcessedTimeSheetDetails($scope.processedTimeSheets[i]);
                // }

                // $(document).ready(function() {
                //     var table = $('#example3').DataTable({
                //         "searching": false,
                //         orderCellsTop: true,
                //         fixedHeader: true
                //     });        
                // });

                DestoryTable();
                var timesheet = GetProcessedTimeSheetDetails();
                
                ApplyTimesheetTableData(timesheet);
                InitializeTable();
        });
    }
    function GetProcessedTimeSheetDetails() {
        var type = "normal";
        var parameters = "type=" + type;
        $http.get("TimeSheetManagement/getProcessedTimeSheetDetails.php?" + parameters)
            .then(function (response) {
                for (var i = 0; i < response.data.length; i++) {
                    response.data[i].employeeName = response.data[i].lastName + ", " + response.data[i].firstName + " " + response.data[i].middleName;
                    // Compute Total
                    response.data[i].weekly_total = parseFloat(response.data[i].weekly_monday) + 
                        parseFloat(response.data[i].weekly_tuesday) +
                        parseFloat(response.data[i].weekly_wednesday) +
                        parseFloat(response.data[i].weekly_thursday) +
                        parseFloat(response.data[i].weekly_friday) +
                        parseFloat(response.data[i].weekly_saturday) +
                        parseFloat(response.data[i].weekly_sunday);
                }
                console.log(response.data);
                
                DestoryTable();
                ApplyTimesheetTableData(response.data);
                InitializeTable();

                // $(document).ready(function() {
                //     var table = $('#example3').DataTable({
                //         "searching": false,
                //         orderCellsTop: true,
                //         fixedHeader: true
                //     });        
                // });
        });
    }

    function InitializeTable() {
        $(document).ready(function(){
            timesheetTable = jQuery('#timesheetTable').DataTable({
                // "searching": false,
                "order": [],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 
                    'csv', 
                    {
                        extend: 'excelHtml5',
                    },
                    {
                        extend: 'pdfHtml5',
                    }
                ]
            });
            console.log(timesheetTable);
        });
    }
    function ApplyTimesheetTableData(timesheets) {
        $(document).ready(function(){
            $scope.timesheets = timesheets;
            $scope.$apply();
        });
    }
    function DestoryTable() {
        $(document).ready(function(){
            if (timesheetTable !== null) {
                timesheetTable.clear();
                timesheetTable.destroy();
            }
        });
    }

    console.log('End TimesheetSummaryController');
});