angular
.module('hris', [])
.controller('TimesheetSummaryController', function ($scope, $http, $filter, $timeout) { 
    console.log('Start TimesheetSummaryController');
    document.title = "Timesheet Summary"

    var days = ["weekly_monday", "weekly_tuesday", "weekly_wednesday", "weekly_thursday", "weekly_friday", "weekly_saturday", "weekly_sunday"];
    var timesheetTable = null;

    $scope.processedTimeSheets = [];
    // $scope.selectedMonth = new Date();
    $scope.filteredTimeSheets = [];
    $scope.timesheets = [];

    // Method-binding
    $scope.filterTimeSheets = FilterTimeSheets;
    
    // Initialization
    // GetProcessedTimesheets();
    GetProcessedTimeSheetDetails();

    $timeout(function () {
        jQuery("#selectedMonth").datepicker({
            autoclose: true,
            defaultDate: new Date(2021, 08, 1),
            format: 'MM yyyy',
            minViewMode: "months",
            startView: "months",
            zIndexOffset: 1500
        });
        var date = new Date();
        var currentMonth = new Date(date.getFullYear(), date.getMonth(), 1);
        jQuery('.datepicker-timesheet-summary').datepicker('update', currentMonth);        
    }, 100);


    function FilterTimeSheets() {
        var date = jQuery('.datepicker-timesheet-summary').datepicker('getDate');
        var monthStart = new Date(date.getFullYear(), date.getMonth(), 1);
        var monthEnd = new Date(date.getFullYear(), date.getMonth() + 1, 0);

        var timesheets = angular.copy($scope.timesheets);
        var filteredTimeSheets = timesheets.filter(function (timesheet) {
            var weekStart = new Date(timesheet.weekly_startDate);
            var weekEnd = new Date(timesheet.weekly_endDate);

            return ((weekStart >= monthStart && weekStart <= monthEnd) || (weekEnd >= monthStart && weekEnd <= monthEnd));
        });
        
        for (var i = 0; i < filteredTimeSheets.length; i++) {
            var weekStart = new Date(filteredTimeSheets[i].weekly_startDate);
            var weekEnd = new Date(filteredTimeSheets[i].weekly_endDate);

            if (weekStart.getMonth() != date.getMonth()) {
                var monthEnd = new Date(weekStart.getFullYear(), weekStart.getMonth() + 1, 0);

                var d = 0;
                for (var j = weekStart.getDate(); j <= monthEnd.getDate(); j++) {
                    filteredTimeSheets[i][days[d]] = "";
                    d++;
                }
            } 
            else if (weekEnd.getMonth() != date.getMonth()) {
                var monthStart = new Date(weekEnd.getFullYear(), weekStart.getMonth(), 1);

                var d = 6;
                for (var j = monthStart.getDate(); j <= weekEnd.getDate(); j++) {
                    filteredTimeSheets[i][days[d]] = "";
                    d--;
                }
            }
        }

        DestoryTable();
        ApplyTimesheetTableData(filteredTimeSheets);
        InitializeTable();
    }

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
                $scope.timesheets = response.data;

                DestoryTable();
                ApplyTimesheetTableData($scope.timesheets);
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
                "searching": false,
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
        });
    }
    function ApplyTimesheetTableData(timesheets) {
        $(document).ready(function(){
            $scope.filteredTimeSheets = timesheets;
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