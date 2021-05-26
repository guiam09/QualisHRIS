angular
.module('hris', [])
.controller('LeaveController', function ($scope, $http, $filter, $timeout) {
    var leaveRequestTable = null;

    $scope.approver = {};
    $scope.employee = {};
    $scope.leaveRequests = [];
    $scope.leaveBalance = [];
    $scope.leaveDetails = [];
    $scope.leaveInfo = [];
    $scope.leaveTypes = [];

    $scope.filter = {
        status: "",
        dateRange: "custom",
        dateTo: null,
        dateFrom: null
    };

    $scope.selectedLeaveRequest = {};

    $scope.applyLeave = ApplyLeave;
    $scope.cancelLeaveRequest = CancelLeaveRequest;
    $scope.checkIfAllowedLeaveType = CheckIfAllowedLeaveType;
    $scope.filterLeaveRequests = FilterLeaveRequests;
    $scope.formatDate = FormatDate;
    $scope.getApprover = GetApprover;
    $scope.getLeaveDetails = GetLeaveDetails;
    $scope.getLeaveInfo = GetLeaveInfo;
    $scope.getLeaveTypes = GetLeaveTypes;
    $scope.getViewEditButton = GetViewEditButton;
    $scope.loadViewEditModalData = LoadViewEditModalData;
    $scope.updateLeaveRequest = UpdateLeaveRequest;   

    GetWeekRange();
    GetEmployee();
    GetLeaveRequests();
    GetLeaveBalance();
    GetLeaveTypes();

    $timeout(function () {
        jQuery('#employeeId').select2();
    }, 100);

    function ApplyLeave() {
        var leaveType = $('#applyLeaveType').val();
        var leaveDate = $('#applyLeaveDate').val();
        var leaveDuration = $('#applyLeaveDuration').val();
        var leaveReason = $('#applyLeaveReason').val();
        var leaveApprover = $('#applyLeaveApprover').val();
        
        var respond = "Leave request sent.";

        $.ajax({
            method: 'POST',
            url: 'leave_applyLeaveProcess.php',
            data: $('#confirmLeaves').serialize(),
            dataType: 'text',
            success: function(response) {
                $('#displayResult').html(response);
                // alert("inside");
                // respond = JSON.stringify(response);
                location.reload(true);
                swal('Leave Request Sent', {
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                    buttons: false
                }, 'success');
            }
        });

        $('#applyLeaveModal').modal('toggle');
    }
    function CancelLeaveRequest(leaveRequest) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to cancel this leave request?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.value) {
                var leavedetailsID = $('#approveLeaveGroupReviewID' + leaveRequest.leavedetails_ID).val(); 
                
                $.ajax({
                    method: 'POST',
                    url: 'leave_cancelLeaveProcess.php',
                    data: {
                        leavedetailsID: leavedetailsID
                    },
                    dataType: 'html',
                    success: function(response) {
                        $('#displayResult').html(response);
                        $('html, body').animate({ scrollTop: 0 }, 'fast');
                        location.reload(true);
                    }
                });
            }
        });
        // location.reload(true);
        // $('#showLeaveTable').load('leave_application.php #showLeaveTable');
    }

    function CheckIfAllowedLeaveType(leaveTypeId) {
        for (var i = 0; i < $scope.leaveInfo.length; i++){
            if (leaveTypeId == $scope.leaveInfo[i].leaveID) {
                return true;
            }
        }
        return false;
    }

    function FilterLeaveRequests() {

        console.log('filter');
        var from_date = document.getElementById('leave_from_date2').value;
        var to_date = document.getElementById('leave_to_date2').value;
        var status = document.getElementById('showStatus2').value;
        console.log(from_date);
        console.log(to_date);

        $.ajax({
            url: "LeaveApplication/filterLeaveRequests.php",
            method: "post",
            data: { 
                range: "custom", 
                from_date: from_date, 
                to_date: to_date, 
                status: status
            },
            dataType: "text",
            success: function(response) {               
                DestoryLeaveRequestTable();
                ApplyLeaveRequestData(JSON.parse(response)[0]);
                InitializeLeaveRequestTable();
                
                
            }
        });
    }

    function FormatDate(date) {
        console.log(date);
        console.log(new Date(date, "m-d-Y"));
        return new Date(date, "m-d-Y");
        // return date("m-d-Y", strtotime(date));
    }

    function GetApprover (employeeId) {
        $http.get("LeaveApplication/getEmployee.php?id=" + employeeId)
            .then(function (response) {
                $scope.approver = response.data[0];
                console.log(response.data[0]);
            });
    }

    function GetEmployee() {
        $http.get("LeaveApplication/getEmployee.php?id=")
            .then(function (response) {
                $scope.employee = response.data[0];
                console.log(response.data[0]);
                return $scope.employee;
            });
    }
    
    function GetLeaveTypes() {
        $http.get("LeaveApplication/getLeaves.php")
            .then(function (response) {
                console.log(response.data[0]);
                $scope.leaveTypes = response.data[0];
        });
    }

    function GetLeaveBalance() {
        $http.get("LeaveApplication/getLeaveBalance.php")
            .then(function (response) {
                $scope.leaveBalance = response.data[0];
                console.log($scope.leaveBalance);

                $(document).ready(function() {
                    var table = $('#example3').DataTable({
                        "searching": false,
                        orderCellsTop: true,
                        fixedHeader: true
                    });        
                });
        });
    }

    function GetLeaveDetails(leaveGroupID) {
        $http.get("LeaveApplication/getLeaveDetails.php?leaveGroupID=" + leaveGroupID)
            .then(function (response) {
                $scope.leaveDetails = response.data[0];
                console.log(response.data[0]);
                GetLeaveInfo($scope.employee.employeeID);
            });
    }

    function GetLeaveInfo(employeeId) {
        $http.get("LeaveApplication/getLeaveInfo.php?employeeId=" + employeeId)
            .then(function (response) {
                $scope.leaveInfo = response.data[0];
                console.log($scope.leaveInfo);
            });
    }

    function GetLeaveRequests() {
        $http.get("LeaveApplication/getLeaveRequests.php")
            .then(function (response) {
                $scope.leaveRequests = response.data[0];
                console.log($scope.leaveRequests);
                InitializeLeaveRequestTable();
                FilterLeaveRequests();
            });
    }

    function GetViewEditButton(status) {
        var viewEditButton = "";
        if (status == 'Approved' || status == 'Declined') {
            viewEditButton = "view";
        } else {
            viewEditButton = "edit";
        }
        return viewEditButton;
    }

    function GetWeekRange() {
        var startDate = new Date();
        var currentDate = new Date();
        var diff = currentDate.getDay() - 1;
        startDate.setDate(currentDate.getDate() - diff);
        var endDate = new Date(startDate);
        endDate.setDate(startDate.getDate() + 6);
        $scope.filter.dateFrom = $filter('date')(startDate, 'MM/dd/yyyy');
        $scope.filter.dateTo = $filter('date')(endDate, 'MM/dd/yyyy');
    }

    function LoadViewEditModalData(leaveRequest) {
        GetLeaveDetails(leaveRequest.leaveGroup_ID); 
        GetApprover(leaveRequest.leaveGroup_approver); 
        $scope.selectedLeaveRequest = leaveRequest;
        console.log($scope.selectedLeaveRequest);
        $timeout(function () {    
            var date = new Date();
            date.setDate(date.getDate());
            jQuery('#currentDate').datepicker({
                startDate: date,
                autoclose: true
            });
            jQuery('.apply-leave-date').datepicker({
                startDate: date,
                autoclose: true
            });
        }, 100);
    }
    
    function UpdateLeaveRequest() {
        $.ajax({
            method: 'POST',
            url: 'leave_updateLeaveRequestProcess.php',
            data: $('#update-form-' + $scope.selectedLeaveRequest.leaveGroup_ID).serialize(),
            dataType: 'text',
            success: function(response) {
                location.reload(true);
            }
        });
    }

    $scope.dtInstance = {};
    $scope.dtOptions = {
      dom: 'Irtip',
      paging: true,
      autoWidth: false,
      responsive: true,
      scroller: false,
      processing: true,
      info: true, //Displays "Showing 1 to 2 of 2 entries"
      
      
      language: {
        "emptyTable": "No Records Available",
        "zeroRecords": "No matching records found"
      }

      //For more options, look here: https://datatables.net/reference/option/
    };

    function InitializeLeaveRequestTable() {
        $(document).ready(function(){
            leaveRequestTable = $('#datatable1').DataTable({
                "searching": false
            });
        });
    }
    function ApplyLeaveRequestData(leaveRequests) {
        $(document).ready(function(){
            $scope.leaveRequests = leaveRequests;
            $scope.$apply();
            console.log($scope.leaveRequests);
        });
    }
    function DestoryLeaveRequestTable() {
        $(document).ready(function(){
            leaveRequestTable.clear();
            leaveRequestTable.destroy();
        });
    }
});