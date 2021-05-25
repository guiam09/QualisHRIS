angular
.module('hris', [])
.controller('TimeSheetController', function ($scope, $http, $filter) {
    const urlParams = new URLSearchParams(window.location.search);
    
    // Data Binding
    $scope.dailyTotal = {
        Monday: 0,
        Tuesday: 0,
        Wednesday: 0,
        Thursday: 0,
        Friday: 0,
        Saturday: 0,
        Sunday: 0,
        Overall: function () {
            return $scope.dailyTotal.Monday + 
            $scope.dailyTotal.Tuesday + 
            $scope.dailyTotal.Wednesday + 
            $scope.dailyTotal.Thursday + 
            $scope.dailyTotal.Friday + 
            $scope.dailyTotal.Saturday + 
            $scope.dailyTotal.Sunday;
        }
    };
    $scope.projects = [];
    $scope.weekStartDate = new Date();
    $scope.weekEndDate = new Date();
    $scope.weeklyApproval = '';
    $scope.weeklyStatus = '';
    $scope.weeklyUtilization = [];
    $scope.warningMessages = [];
    $scope.workTypes = [];

    // Method Binding
    $scope.addTask = AddTask;
    $scope.saveConfirm = SaveConfirm;
    $scope.submitConfirm = SubmitConfirm;

    $(document).ready(function(){
        var weekpicker, start_date, end_date;

        function set_week_picker(date) {
            start_date = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 1);
            end_date = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 7);
            weekpicker.datepicker('update', start_date);
            weekpicker.val((start_date.getMonth() + 1) + '/' + start_date.getDate() + '/' + start_date.getFullYear() + ' - ' + (end_date.getMonth() + 1) + '/' + end_date.getDate() + '/' + end_date.getFullYear());
            
        }

        weekpicker = $('.week-picker');

        weekpicker.datepicker({
            autoclose: true,
            forceParse: false,
            container: '#week-picker-wrapper',
             weekStart: 1
        }).on("changeDate", function(e) {
            set_week_picker(e.date);
            window.location.href = window.location.href.replace( /[\?#].*|$/, "?date="+start_date.getFullYear() + '-' + ("0" + (start_date.getMonth() + 1)).slice(-2) + '-' + ("0" + (start_date.getDate())).slice(-2));
        });
        $('.week-prev').on('click', function() {
            var prev = new Date(start_date.getTime());
            prev.setDate(prev.getDate() - 7);
            set_week_picker(prev);
            window.location.href = window.location.href.replace( /[\?#].*|$/, "?date="+start_date.getFullYear() + '-' +("0" + (start_date.getMonth() + 1)).slice(-2)  + '-' + ("0" + (start_date.getDate())).slice(-2));
        });
        $('.week-next').on('click', function() {
            var next = new Date(end_date.getTime());
            next.setDate(next.getDate() + 1);
            set_week_picker(next);
            window.location.href = window.location.href.replace( /[\?#].*|$/, "?date="+start_date.getFullYear() + '-' +("0" + (start_date.getMonth() + 1)).slice(-2)  + '-' + ("0" + (start_date.getDate())).slice(-2));
        });

        var date = urlParams.get('date');
        if (date) {
            var year = parseInt(date.substring(0,4));
            var month = parseInt(date.substring(5,7) - 1);
            var day = parseInt(date.substring(8));
            set_week_picker(new Date(year, month, day));
        } else {
            set_week_picker(new Date());
        }
    });

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip({
            html: true,
            template: '<div class="tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
        });
    });
    
    $(document).on('keyup click', '.monday', 'monday', CalculateTotals);
    $(document).on('keyup click', '.tuesday', 'tuesday', CalculateTotals);
    $(document).on('keyup click', '.wednesday', 'wednesday', CalculateTotals);
    $(document).on('keyup click', '.thursday', 'thursday', CalculateTotals);
    $(document).on('keyup click', '.friday', 'friday', CalculateTotals);
    $(document).on('keyup click', '.saturday', 'saturday', CalculateTotals);
    $(document).on('keyup click', '.sunday', 'sunday', CalculateTotals);
    $(document).on('click', '.remove', RemoveRow);

    GetWeekRange();
    GetUserInfo();
    GetProjects();
    GetWorkTypes();
    GetWeeklyUtilization();

    // Functions
    function AddTask () {
        console.log($scope.weeklyUtilization);
        var employeeCode = $scope.user;

        $scope.weeklyUtilization.push({
            "weekly_ID": $.now(),
            "employeeCode": employeeCode,
            "weekly_startDate": $filter('date')($scope.weekStartDate, 'yyyy-MM-dd'),
            "weekly_endDate": $filter('date')($scope.weekEndDate, 'yyyy-MM-dd'),
            "project_ID": "",
            "work_ID": "1",
            "activityOthers_ID": null,
            "activityAdmin_ID": null,
            "weekly_description": null,
            "weekly_sunday": "0.0",
            "weekly_monday": "0.0",
            "weekly_tuesday": "0.0",
            "weekly_wednesday": "0.0",
            "weekly_thursday": "0.0",
            "weekly_friday": "0.0",
            "weekly_saturday": "0.0",
            "weekly_total": null,
            "weekly_overallTotal": null,
            "location_ID": "",
            "weekly_timeSubmitted": null,
            "weekly_dateProcessed": null,
            "weekly_status": $scope.weeklyStatus,
            "weekly_approval": $scope.weeklyApproval,
            "weekly_dateSubmitted": null,
            "weekly_taskCode": "",
            "weekly_saturdayComment": null,
            "weekly_sundayComment": null,
            "weekly_mondayComment": null,
            "weekly_tuesdayComment": null,
            "weekly_wednesdayComment": null,
            "weekly_thursdayComment": null,
            "weekly_fridayComment": null,
            "weekly_timesheetCode": null,
            "is_shown": null,
            "project_name": "",
            "work_name": "Regular Hour"
        });
    }
    function CalculateTotals(event) {        
        var day = event.data;
        
        var inputValue = $(this).val();
        if (IsValidInput(inputValue) === false) {
            $(this).addClass("invalid");
        } else {
            $(this).removeClass("invalid");
        }
        
        // Compute Daily Total
        var dailyTotal = 0;
        $('.' + day).each(function(){
            if (IsValidInput($(this).val()))
                dailyTotal += parseFloat($(this).val());
            else
                dailyTotal += parseFloat(0);
        });
        $('.' + day + 'Total').val($filter('number')(dailyTotal, 1));
        
        // Compute Total per task
        var weeklyTotal = 0;
        var id = $(this).closest('input').attr('id');
        $('.'+id).each(function(){
            if (IsValidInput($(this).val()))
                weeklyTotal += parseFloat($(this).val());
            else 
                weeklyTotal += parseFloat(0); 
        });
        $('.totalWeeklyWorkedHours'+id).val($filter('number')(weeklyTotal, 1));

        // Compute Overall Total
        var overallTotal = 0;
        $('.dailyWorkedHours').each(function(){
            if (IsValidInput($(this).val()))
                overallTotal += parseFloat($(this).val());
            else
                overallTotal += parseFloat(0);
        });
        $('.overallTotal').val($filter('number')(overallTotal, 1));
    }

    function IsValidInput (value) {
        var isValid = false;
        if (value.trim() === '') {
            isValid = false;
        } else if (value.toLowerCase().includes('e')) {
            isValid = false;
        } 
        // Input value should be divisible by 0.5
        else if (parseFloat(value) % 0.5 != 0) {
            isValid = false;
        } else {
            isValid = true;
        }
        return isValid;
    }

    function GetProjects() {
        $http.get("TimeSheet/getProjects.php")
        .then(function (response) {
            $scope.projects = response.data;
            console.log($scope.projects);
        });
        
    }

    function GetUserInfo() {
        $http.get("Employee/getUserInfo.php")
        .then(function (response) {
            $scope.user = response.data;
            console.log($scope.user);
        });
    }

    function ComputeTaskHoursUtilized(task) {
        var total = 0;
        total += parseFloat(task.weekly_monday);
        total += parseFloat(task.weekly_tuesday);
        total += parseFloat(task.weekly_wednesday);
        total += parseFloat(task.weekly_thursday);
        total += parseFloat(task.weekly_friday);
        total += parseFloat(task.weekly_saturday);
        total += parseFloat(task.weekly_sunday);
        return total;
    }

    function GetWeekRange() {
        var startDate = new Date();
        if (urlParams.get('date') !== null) {
            startDate = new Date(urlParams.get('date'));
        } else {
            var currentDate = new Date();
            var diff = currentDate.getDay() - 1;
            startDate.setDate(currentDate.getDate() - diff);
        }
        var endDate = new Date(startDate);
        endDate.setDate(startDate.getDate() + 6);

        $scope.weekStartDate = startDate;
        $scope.weekEndDate = endDate;
    }


    function GetWeeklyUtilization () {
        var startDate = $filter('date')($scope.weekStartDate, 'yyyy-MM-dd');
        var endDate = $filter('date')($scope.weekEndDate, 'yyyy-MM-dd');
        $http.get("TimeSheet/getWeeklyUtilization.php?startDate=" + startDate + "&endDate=" + endDate)
            .then(function (response) {
                $scope.weeklyUtilization = response.data;

                for (var i = 0; i < $scope.weeklyUtilization.length; i++){
                    var item = $scope.weeklyUtilization[i];
                    item.weekly_total = ComputeTaskHoursUtilized(item);
                    $scope.weeklyApproval = item.weekly_approval;
                    $scope.weeklyStatus = item.weekly_status;
                    $scope.dailyTotal.Monday += parseFloat(item.weekly_monday);
                    $scope.dailyTotal.Tuesday += parseFloat(item.weekly_tuesday);
                    $scope.dailyTotal.Wednesday += parseFloat(item.weekly_wednesday);
                    $scope.dailyTotal.Thursday += parseFloat(item.weekly_thursday);
                    $scope.dailyTotal.Friday += parseFloat(item.weekly_friday);
                    $scope.dailyTotal.Saturday += parseFloat(item.weekly_saturday);
                    $scope.dailyTotal.Sunday += parseFloat(item.weekly_sunday);
                    
                }
            });
    }

    function GetWorkTypes() {
        $http.get("TimeSheet/getWorkTypes.php")
        .then(function (response) {
            $scope.workTypes = response.data;
            console.log($scope.workTypes);
        });
        
    }

    function RemoveRow () {
        $(this).closest('tr').remove();
        
        var childSelectors = [
            ".monday",
            ".tuesday",
            ".wednesday",
            ".thursday",
            ".friday",
            ".saturday",
            ".sunday"
        ];

        for (var i = 0; i < childSelectors.length; i++) {
            var dailySum = 0;
            $(childSelectors[i]).each(function(){
                dailySum += parseFloat($(this).val());  
            });
            
            $(childSelectors[i] + 'Total').val(dailySum);
        }
        
        var overallTotal = 0;
        $('.dailyWorkedHours').each(function(){
            overallTotal += parseFloat($(this).val()); 
        });
        $('.overallTotal').val(overallTotal);
    }

    function SaveConfirm()
    {
        ValidateTimeSheet();

        if ($scope.warningMessages.length > 0) {
            $('#warningModal').modal('show');
        } else {
            Swal.fire({
                title:'Are you sure you want to save the timesheet?',
                type:'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, I\'m sure'
            }).then((result) => {
                if(result.value){
                    swal(
                        "Saving. . .",{
                            closeOnClickOutside: false,
                            closeOnEsc: false,
                            buttons: false
                        }
                    )
                    $('#insert_form').submit();
                }
            });
        }
    }

    function SubmitConfirm()
    {
        Swal.fire({
            title:'Are you sure you want to submit the timesheet?',
            type:'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, I\'m sure'
        }).then((result) => {
            if(result.value){
                swal(
                    "Saving. . .",{
                        closeOnClickOutside: false,
                        closeOnEsc: false,
                        buttons: false
                    }
                )
                // $('#submit-timesheet-form').submit();
                $('#save_and_submit').val('yes');
                $('#insert_form').submit();
            }
        });
    }

    function ValidateTimeSheet() {
        $scope.warningMessages = [];
        
        var projectCodesElements = document.getElementsByName("new_project_name[]");
        var recordCount = projectCodesElements.length;

        for (var i = 0; i < recordCount; i++){
            if (projectCodesElements[i].value == ""){
                $scope.warningMessages.push("Please select Project Code on row " + (i+1));
            }
            
        }

        var taskCodes = document.getElementsByName("new_task_code[]");
        for (var i = 0; i < recordCount; i++){
            if (taskCodes[i].value == "") {
                $scope.warningMessages.push("Please enter Task Code on row " + (i+1));
            }
        }

        var names = [
            "new_monday[]",
            "new_tuesday[]",
            "new_wednesday[]",
            "new_thursday[]",
            "new_friday[]",
            "new_saturday[]",
            "new_sunday[]"
        ];
        var days = [
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
            "Saturday",
            "Sunday"
        ];

        for (var i = 0; i < recordCount; i++) {
            for (var j = 0; j < names.length; j++) {
                var input = document.getElementsByName(names[j])[i];
                var isInvalid = parseFloat(input.value) % 0.5 != 0;
                if (isInvalid || input.value.toLowerCase().includes('e')) {
                    $scope.warningMessages.push("Invalid work hours on row " + (i+1) + ", " + days[j]);
                }
            }
        }
    }
});