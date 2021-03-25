angular
.module('hris', [])
.controller('TimeSheetController', function ($scope, $http) {
    // Data Binding
    $scope.warningMessages = [];

    // Method Binding
    $scope.saveConfirm = SaveConfirm;
    $scope.submitConfirm = SubmitConfirm;

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

    // Functions
    function CalculateTotals(event) {        
        var day = event.data;
        
        // Input value should be divisible by 0.5
        var isInvalid = parseFloat($(this).val()) % 0.5 != 0;
        if (isInvalid || $(this).val().toLowerCase().includes('e')) {
            $(this).addClass("invalid");
        } else {
            $(this).removeClass("invalid");
        }
        
        // Compute Daily Total
        var dailyTotal = 0;
        $('.' + day).each(function(){
            dailyTotal += parseFloat($(this).val()); 
        });
        $('.' + day + 'Total').val(dailyTotal);
        
        // Compute Weekly Total
        var weeklyTotal = 0;
        var id = $(this).closest('input').attr('id');
        $('.'+id).each(function(){
            weeklyTotal += parseFloat($(this).val()); 
        });
        $('.totalWeeklyWorkedHours'+id).val(weeklyTotal);
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