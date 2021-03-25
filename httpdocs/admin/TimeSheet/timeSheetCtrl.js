angular
.module('hris', [])
.controller('TimeSheetController', function ($scope, $http) {
    // Data Binding
    $scope.warningMessages = [];

    // Method Binding
    $scope.saveConfirm = SaveConfirm;

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip({
            html: true,
            template: '<div class="tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
        });   
    });

    // Functions
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

    function ValidateTimeSheet() {
        $scope.warningMessages = [];
        var projectCodesElements = document.getElementsByName("new_project_name[]");
        var taskCodes = document.getElementsByName("new_task_code[]");

        for (var i = 0; i < projectCodesElements.length; i++){
            if (projectCodesElements[i].value == ""){
                $scope.warningMessages.push("Please select Project Code on row " + (i+1));
            }
            
        }
        for (var i = 0; i < taskCodes.length; i++){
            if (taskCodes[i].value == "") {
                $scope.warningMessages.push("Please enter Task Code on row " + (i+1));
            }
        }
    }
});