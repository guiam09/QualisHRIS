angular
.module('hris', [])
.controller('TimeSheetManagementController', function ($filter, $http, $scope, $timeout) {
    // Data-binding
    $scope.employees = [];
    $scope.statuses = [
        'Pending',
        'Approved',
        'Amended Pending',
        'Amended Approved',
        'Declined',
        'Amended Declined'
    ]

    // Method-binding
    $scope.clearFilters = ClearFilters;
    $scope.formatEmployeeName = FormatEmployeeName;

    // Initialization
    GetEmployeeList();

    // Events

    // Methods
    function ClearFilters() {
        $('#datepicker').datepicker('setDate', null);
        $('#employee_name').val('');
        $('#employee_name').trigger('change');
        $('#status_filter').val('');
        $('#status_filter').trigger('change');
    }
    function FormatEmployeeName (employee) {
        return employee.lastName + ', ' + employee.firstName + ' ' + employee.middleName;
    }
    function GetEmployeeList() {
        $http.get("Employee/getEmployeeList.php")
            .then(function (response) {
                $scope.employees = response.data;
                $timeout(function () {
                    jQuery('#employee_name').select2({
                        allowClear: true,
                        placeholder: 'Select Employee'
                    });
                    $('#employee_name').val('');
                    $('#employee_name').trigger('change');

                    jQuery('#status_filter').select2({
                        allowClear: true,
                        placeholder: 'Select status'
                    });
                    $('#status_filter').val('');
                    $('#status_filter').trigger('change');
                }, 100);
            });
    }
});