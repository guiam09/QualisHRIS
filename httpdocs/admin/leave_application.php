<?php
include_once ('../includes/configuration.php');

include ('../db/connection.php');


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// include login checker
$page_title="Admin";
$access_type ="Admin";

// include login checker
$require_login=true;
include_once "../includes/loginChecker.php";

include ('../includes/header.php');
include ('../includes/navbar.php');
$CURRENT_PAGE="Leave Application";
include ('../includes/sidebar.php');
include ('../includes/fetchData.php');

?>
<style>
    thead input {
        width: 80%;
    }
</style>
<style>
    .modal {
        z-index: 1950;
    }

    .select2-container {
        z-index: 9999;
    }

    .select2-container--open {
        z-index: 99999
    }

    .swal2-container {
        z-index: 999999;
    }
    .dropdown-menu {
        z-index: 2000 !important;
    }
</style>
<!-- Page -->
<div class="page" ng-app="hris" ng-controller="LeaveController">
    <div class="page-header">
        <h1 class="page-title"><i>Leave</i></h1>
    </div>
    <!-- Leave navigation tabs -->
    <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
        <li class="nav-item" style="margin-left:10px">
            <a class="nav-link active" id="custom-content-below-leave-monitoring-tab" data-toggle="pill" href="#custom-content-below-leave-monitoring" role="tab" aria-controls="custom-content-below-leaveApproval" aria-selected="true">
                Requests
            </a>
        </li>
        <!-- debug: modify access level in database -->
        <li ng-if="employee.positionID == 15 || employee.departmentID <= 2" class="nav-item" style="margin-left:10px">
            <a class="nav-link" id="custom-content-below-leaveApproval-tab" data-toggle="pill" href="#custom-content-below-leaveApproval" role="tab" aria-controls="custom-content-below-leaveApproval" aria-selected="true">
                Approval
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" id="custom-content-below-leaveCount-tab" data-toggle="pill" href="#custom-content-below-leaveCount" role="tab" aria-controls="custom-content-below-leaveCount" aria-selected="false">
                Balance
            </a>
        </li>
    </ul>
    <!-- end Leave navigation tabs -->

    <div class="tab-content" id="custom-content-below-tabContent">
        <!-- Leave Approval Tab -->
        <div class="tab-pane" id="custom-content-below-leaveApproval" role="tabpanel" aria-labelledby="custom-content-below-leaveApproval-tab">
            <div class="page-content container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel">
                            <div class="panel-heading">
                                <h3 class="panel-title text-danger" id="displayResult"></h3>
                            </div>
                            <div class="panel-body container-fluid">
                                <form id="populateLeaveTable">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3" ng-if="employee.positionID == 15 || employee.departmentID <= 2">
                                                <div class="form-group form-material col-md-12">
                                                    <label class="form-control-label">Employee</label>
                                                    <select class="form-control col-md-6" data-plugin="select2"  id="employeeId" name="employeeId" data-placeholder="Select Employee">
                                                        <?php include ('searchEmployeeList.php'); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group form-material col-md-12">
                                                    <label class="form-control-label">Status</label><br />
                                                    <select class="form-control col-md-12" id="showStatus">
                                                        <option value=""></option>
                                                        <option>Pending</option>
                                                        <option>Approved</option>
                                                        <option>Declined</option>
                                                        <option>Retracted Pending</option>
                                                        <option>Retracted Approved</option>
                                                        <option>Retracted Declined</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="form-group form-material col-md-12">
                                                    <label class="form-control-label">Range</label><br />
                                                    <select class="form-control col-md-12" data-plugin="select" data-minimum-results-for-search="Infinity" data-placeholder="Select Range" tab-index="-1" width="auto" id="showDateRange">
                                                        <option value=" "></option>
                                                        <option value="currentWeek">Current Week</option>
                                                        <option value="lastWeek">Last Week</option>
                                                        <option value="lastTwoWeeks">Last 2 Weeks</option>
                                                        <option value="lastMonth">Last Month</option>
                                                        <option value="custom">Custom</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--<div class="date-range">-->
                                            <div class="col-md-3" id='fromDateGroup' style=display:none>
                                                <div class="form-group form-material col-md-12">
                                                    <label class="form-control-label">From</label>
                                                    <div class="col-md-12 input-group input-daterange" id="fromleavedatepicker" data-plugin="datepicker">
                                                        <input type="text" name="leave_from_date" id="leave_from_date" class="form-control datepicker" autocomplete="off" style="border:1px solid #e4eaec; border-radius:0.215rem">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3" id='toDateGroup' style=display:none>
                                                <div class="form-group form-material col-md-12">
                                                    <label class="form-control-label">To</label>
                                                    <div class="col-md-12 input-group input-daterange" id="toleavedatepicker" data-plugin="datepicker">
                                                        <input type="text" name="leave_to_date" id="leave_to_date" class="form-control datepicker" autocomplete="off" style="border:1px solid #e4eaec; border-radius:0.215rem">
                                                    </div>
                                                </div>
                                            </div>
                                            <!--</div>-->
                                            <div ng-if="employee.positionID != 15 && employee.departmentID > 2" class="col-md-3"></div>
                                        </div>
                                    </div>
                                </form>
                                <br />
                                <div class="row">
                                    <table id="showLeaveTable" class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Date Filed</th>
                                                <th>Name</th>
                                                <th>Leave Type</th>
                                                <th>Leave Date</th>
                                                <th>Duration</th>
                                                <th>Status</th>
                                                <th>Date Approved/Declined</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Leave Approval Tab -->

        <!-- Leave Application: Balance Tab -->
    
        <div class="tab-pane fade" id="custom-content-below-leaveCount" role="tabpanel" aria-labelledby="custom-content-below-leaveCount-tab">
            <div class="page-content container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!---->
                        <div class="panel">
                            <div class="panel-body container-fluid">
                                <table id='example3' class='table table-hover table-striped'>
                                    <thead>
                                        <tr>
                                            <th>Leave Type</th>
                                            <th>Leave Credits</th>
                                            <th>Used</th>
                                            <th>Leave Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="balance in leaveBalance" ng-if="leaveBalance.length > 0">
                                            <td>{{balance.leaveName}}</td>
                                            <td>{{balance.leaveCount}}</td>
                                            <td>{{balance.leaveUsed}}</td>
                                            <td>{{balance.leaveRemaining}}</td>
                                        </tr>
                                        <div ng-if="leaveBalance.length == 0" class='alert alert-secondary'>No Results Found!</div>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!---->
            </div>
        </div>

        <!-- End Leave Tab -->

        <div class="tab-pane fade active show" id="custom-content-below-leave-monitoring" role="tabpanel" aria-labelledby="custom-content-below-leave-monitoring-tab">
            <div class="page-content container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!---->
                        <div class="panel">
                            <div class="panel-body container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form id="populateLeaveTable2">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group form-material col-md-12">
                                                        <label class="form-control-label">Status</label><br />
                                                        <select ng-model="filter.status" ng-change="filterLeaveRequests()" class="form-control col-md-12" id="showStatus2">
                                                            <option value=""></option>
                                                            <option>Pending</option>
                                                            <option>Approved</option>
                                                            <option>Declined</option>
                                                            <option>Retracted Pending</option>
                                                            <option>Retracted Approved</option>
                                                            <option>Retracted Declined</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group form-material col-md-12">
                                                        <label class="form-control-label">Range</label><br />
                                                        <select ng-model="filter.dateRange" ng-change="filterLeaveRequests()" class="form-control col-md-12" data-plugin="select" data-minimum-results-for-search="Infinity" data-placeholder="Select Range" tab-index="-1" width="auto" id="showDateRange2">
                                                            <option value=" "></option>
                                                            <option value="currentWeek">Current Week</option>
                                                            <option value="lastWeek">Last Week</option>
                                                            <option value="lastTwoWeeks">Last 2 Weeks</option>
                                                            <option value="lastMonth">Last Month</option>
                                                            <option value="custom">Custom</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <!--<div class="date-range">-->
                                                <div class="col-md-3" id='fromDateGroup2' style=display:none>
                                                    <div class="form-group form-material col-md-12">
                                                        <label class="form-control-label">From</label>
                                                        <div class="col-md-12 input-group input-daterange" id="fromleavedatepicker2" data-plugin="datepicker">
                                                            <input ng-model="filter.dateFrom" ng-change="filterLeaveRequests()" type="text" name="leave_from_date2" id="leave_from_date2" class="form-control datepicker" autocomplete="off" style="border:1px solid #e4eaec; border-radius:0.215rem">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3" id='toDateGroup2' style=display:none>
                                                    <div class="form-group form-material col-md-12">
                                                        <label class="form-control-label">To</label>
                                                        <div class="col-md-12 input-group input-daterange" id="toleavedatepicker2" data-plugin="datepicker">
                                                            <input ng-model="filter.dateTo" ng-change="filterLeaveRequests()" type="text" name="leave_to_date2" id="leave_to_date2" class="form-control datepicker" autocomplete="off" style="border:1px solid #e4eaec; border-radius:0.215rem">
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--</div>-->
                                                <div class="col-md-3" ng-if="employee.positionID != 15 && employee.departmentID > 2"></div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-2">
					                    <button type="button" ng-click="getLeaveInfo(employee.employeeID)" data-toggle="modal" data-target="#applyLeaveModal" class="btn btn-block btn-info">
                                            File Leave
                                        </button>
                                    </div>
                                </div>
                                <br />
                                <table id="datatable1" datatable="ng" dt-instance="dtInstance" dt-options="dtOptions" class='table table-hover table-striped'>
                                    <thead>
                                        <tr>
                                            <th>Date Filed</th>
                                            <th>Leave Type</th>
                                            <th>Leave Date</th>
                                            <th>Duration</th>
                                            <th>Status</th>
                                            <th>Date Approved/Declined</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="leaveRequest in leaveRequests" ng-if="leaveRequests.length > 0">
                                            <td>{{leaveRequest.leaveGroup_dateFiled}}</td>
                                            <td>
                                                <span ng-repeat="l in leaveRequest.leaveName.split('<br/>') track by $index">
                                                    {{l}}
                                                    <br/>
                                                </span>
                                            </td>
                                            <td>
                                                <span ng-repeat="l in leaveRequest.leavedetails_leaveFrom.split('<br/>') track by $index">
                                                    {{l}}
                                                    <br/>
                                                </span>
                                            </td>
                                            <td>{{leaveRequest.leaveGroup_duration}}</td>
                                            <td>{{leaveRequest.leaveGroup_status}}</td>
                                            <td>
                                                <span ng-if="leaveRequest.leaveGroup_dateApproved != ''">{{leaveRequest.leaveGroup_dateApproved}}</span>
                                                <span ng-if="leaveRequest.leaveGroup_dateApproved == ''">----------------------</span>
                                            </td>
                                            <td>
                                                <button ng-click="cancelLeaveRequest(leaveRequest)" type='button' id='cancelLeaveRequest{{leaveRequest.leavedetails_ID}}' class='btn btn-block btn-danger'>
                                                    Cancel
                                                </button>
                                                <button type='button' data-target='#{{getViewEditButton(leaveRequest.leaveGroup_status) + leaveRequest.leaveGroup_ID}}' data-toggle='modal' class='btn btn-block btn-success'
                                                    ng-click="loadViewEditModalData(leaveRequest)">
                                                    <span ng-if="leaveRequest.leaveGroup_status === 'Approved' || leaveRequest.leaveGroup_status === 'Declined'">View</span>
                                                    <span ng-if="leaveRequest.leaveGroup_status !== 'Approved' && leaveRequest.leaveGroup_status !== 'Declined'">Edit</span>
                                                </button>
                                            </td>
                                        </tr>
                                        <div ng-if="leaveRequests.length == 0" class='alert alert-secondary'>No Results Found!</div>
                                    </tbody>
                                </table>

                                <!-- EDIT MODAL -->
                                <div ng-repeat="leaveRequest in leaveRequests" ng-if="leaveRequests.length > 0" class="modal fade" id="edit{{leaveRequest.leaveGroup_ID}}" tabindex="-1" role="dialog" aria-labelledby="viewLeaveModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel"> View Leave</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="update-form-{{leaveRequest.leaveGroup_ID}}">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="showLeaveDate" class="col-sm-12 control-label">
                                                                    <h6>Leave Type</h6>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="showLeaveDate" class="col-sm-12 control-label">
                                                                    <h6>Date</h6>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="showLeaveDate" class="col-sm-12 control-label">
                                                                    <h6>Days</h6>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="showLeaveDate" class="col-sm-12 control-label">
                                                                    <h6>Reason</h6>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div ng-repeat="leaveDetail in leaveDetails" class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <input name="leave_delail_id[]" ng-value="leaveDetail.leavedetails_ID" type="hidden">
                                                                <select class="form-control col-md-12" id="leaveTypeUpdate{{leaveDetail.leavedetails_ID}}" name="leaveType[]">
                                                                    <option ng-repeat="leaveType in leaveTypes" ng-if="checkIfAllowedLeaveType(leaveType.leaveID)" ng-selected="leaveType.leaveID == leaveDetail.leave_id" ng-value='leaveType.leaveID'>
                                                                        {{leaveType.leaveName}}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <div class="col-md-12 input-group input-daterange preferredleaveDate apply-leave-date" data-plugin="datepicker" >
                                                                    <input type="date" id="leaveDateUpdate{{leaveDetail.leavedetails_ID}}" name="leaveDate[]" class="form-control datepicker" autocomplete="off" ng-value="leaveDetail.leavedetails_leaveFrom" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="<i class='fas fa-exclamation-circle' style='color:yellow'></i>{{leaveType.leaveName}}">
                                                                    <select class="form-control col-md-12" id="leaveDurationUpdate{{leaveDetail.leavedetails_ID}}" name="leaveDuration[]">
                                                                        <option value="1" ng-selected="leaveDetail.leavedetails_duration == 1">1</option>
                                                                        <option value="0.5" ng-selected="leaveDetail.leavedetails_duration == 0.5">0.5</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <div class="col-md-12 input-group">
                                                                    <input type="text" id="leaveReasonUpdate{{leaveDetail.leavedetails_ID}}" name="leaveReason[]" class="form-control" autocomplete="off" ng-value="leaveDetail.leavedetails_reason" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <input type="hidden" ng-value="leaveRequest.leaveGroup_ID" name="leave_group_id">
                                                </form>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="form-control" readonly>
                                                                {{approver.lastName}}, {{approver.firstName}} {{approver.middleName}}
                                                            </div>
                                                        </div>
                                                        <input type="hidden" id="leavedetailsIdUpdate{{leaveRequest.leavedetails_ID}}" ng-value="leaveRequest.leavedetails_ID">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" id="closeEdit{{leaveRequest.leavedetails_ID}}" class="btn btn-default btn-outline" data-dismiss="modal">
                                                    <i class="fa fa-close"></i> 
                                                    Close
                                                </button>
                                                <button ng-click="updateLeaveRequest()" type="button" class="btn btn-primary" name="updateLeaveRequestButton" id="updateLeaveRequestButton{{leave.Request.leavedetails_ID}}">
                                                    <i class="fa fa-check-square-o"></i> 
                                                    Update
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                        
                                <!-- View MODAL-->
                                <div ng-repeat="leaveRequest in leaveRequests" ng-if="leaveRequests.length > 0" class="modal fade" id="view{{leaveRequest.leaveGroup_ID}}" tabindex="-1" role="dialog" aria-labelledby="viewLeaveModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel"> View Leave</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="reviewLeaveType" class="col-sm-12 control-label">
                                                                <h6>Leave Type</h6>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="reviewLeaveDate" class="col-sm-12 control-label">
                                                                <h6>Date</h6>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="reviewLeaveDuration" class="col-sm-12 control-label">
                                                                <h6>Days</h6>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="reviewLeaveReason" class="col-sm-12 control-label">
                                                                <h6>Reason</h6>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div ng-repeat="leaveDetail in leaveDetails" class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="form-control" readonly>{{leaveDetail.leaveName}}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="form-control" readonly>{{leaveDetail.leavedetails_leaveFrom}}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="form-control" readonly>{{leaveDetail.leavedetails_duration}}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="form-control" readonly>{{leaveDetail.leavedetails_reason}}</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="showLeaveApprover" class="col-sm-12 control-label">
                                                                <h6>Approver</h6>
                                                            </label>
                                                            <div class="form-control" readonly>
                                                                {{ approver.lastName }}, {{approver.firstName}} {{approver.middleName}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="showLeaveNotes" class="col-sm-12 control-label">
                                                                <h6>Notes</h6>
                                                            </label>
                                                            <textarea class="form-control" readonly>{{leaveRequest.leaveGroup_notes}}</textarea>
                                                            <input type="hidden" id="approveLeaveGroupReviewID{{leaveRequest.leavedetails_ID}}" name="approveLeaveGroupID" value="{{leaveRequest.leaveGroup_ID}}" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!---->
            </div>
        </div>
    </div>
    <!-- MODALS -->
    <div class="modal fade" id="applyLeaveModal">
        <div class="modal-dialog modal-lg">
            <form id='confirmLeaves'>
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="card-title">Apply Leave</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <!--<h4 class="modal-title"><b><span class="employee_id"></span></b></h4>-->
                    </div>
                    <div class="modal-body ">
                        <div class='row'>
                            <div class='col-md-12'>
                                <input type="hidden" id="leave_form_row_count" value="1">
                                <table class="table dataTable" id="updateLeaveTable">
                                    <tr>
                                        <th>Leave Type</th>
                                        <th>Date</th>
                                        <th>Days</th>
                                        <th>Reason</th>
                                    </tr>
                                    <tr class="leaveData filled first-row-data" data-rowID="#dataRow1" id="dataRow1">
                                        <td class="hasInputData">
                                            <select class="form-control col-md-12" name="leave_type[]" id="applyLeaveType">
                                                <option ng-repeat="leaveType in leaveTypes" ng-if="checkIfAllowedLeaveType(leaveType.leaveID)" ng-value='leaveType.leaveID'>
                                                    {{leaveType.leaveName}}
                                                </option>
                                                <!-- End Query Leave Types-->
                                            </select>
                                        </td>
                                        <td class="hasInputData">
                                            <div class="col-md-12 input-group input-daterange preferredleaveDate apply-leave-date" data-plugin="datepicker" id="currentDate">
                                                <input onchange="row_check($(this))" type="date" id="applyLeaveDate" name="leave_date[]" class="form-control datepicker" autocomplete="off" readonly/>
                                            </div>
                                        </td>
                                        <td class="hasInputData">
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<i class='fas fa-exclamation-circle' style='color:yellow'></i>">
                                                <select onchange="row_check($(this))" class="form-control col-md-12" name="leave_duration[]" id="applyLeaveDuration">
                                                    <option value="1">1</option>
                                                    <option value="0.5">0.5</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td class="hasInputData leaveReason">
                                            <div class="col-md-12 input-group">
                                                <input onchange="row_check($(this))" type="text" id="applyLeaveReason" name="leave_reason[]" class="form-control" autocomplete="off" />
                                            </div>
                                        </td>
                                        <td class="hasInputData">
                                            <!--<a href="#"><i class="fas fa-minus-circle" style="color:red"></i></a>-->
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <label class="form-control-label">Approver</label>
                                <select class="form-control col-md-12" data-plugin="selectpicker" id="applyLeaveApprover" name="adminIdApply" data-placeholder="Select Employee">
                                    <?php include ('searchAdminList.php');?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-outline" data-dismiss="modal">
                            <i class="fa fa-close"></i> 
                            Close
                        </button>
                        <button type="button" ng-click="applyLeave()" class="btn btn-primary" name="applyLeaveButton" id="applyLeaveButton">
                            <i class="fa fa-check-square-o"></i> 
                            Apply Leave
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- END MODALS -->
</div>
<!-- END Page -->



<!-- FOOTER -->
<?php
include ('../includes/footer.php');
include ('../includes/scripts.php');
 ?>

<script type="text/javascript" src="../node_modules/angular/angular.min.js"></script>
<script type="text/javascript" src="../node_modules/angularjs-datatables/src/angular-datatables.js"></script>
<script type="text/javascript" src="LeaveApplication/leaveCtrl.js"></script>

<script>
    var date = new Date();
    date.setDate(date.getDate() + 7);
    $('#currentDate').datepicker({
        startDate: date
    });
    $('#leaveDate').datepicker({
        startDate: date
    });
    $(document).ready(function() {
        $('#showLeaveTable').DataTable();
        
        // showing custom date range
        $('#showDateRange').on('change', function() {
            //        function showDateRange(value) {
            var leaveType = $(this).val();
            if (leaveType == "custom") {
                document.getElementById('fromDateGroup').style.display = 'block';
                document.getElementById('toDateGroup').style.display = 'block';
            } else {
                document.getElementById('fromDateGroup').style.display = 'none';
                document.getElementById('toDateGroup').style.display = 'none';
            }
        });

        $('#showDateRange2').on('change', function() {
            //        function showDateRange(value) {
            var leaveType = $(this).val();
            if (leaveType == "custom") {
                document.getElementById('fromDateGroup2').style.display = 'block';
                document.getElementById('toDateGroup2').style.display = 'block';
            } else {
                document.getElementById('fromDateGroup2').style.display = 'none';
                document.getElementById('toDateGroup2').style.display = 'none';
            }
        });
        // end showing custom date range

        // $('#populateLeaveTable2').on('change',function(){
        //     var range = $('#showDateRange2').val();
        //     var from_date = document.getElementById('leave_from_date2').value;
        //     var to_date = document.getElementById('leave_to_date2').value;
        //     var status = document.getElementById('showStatus2').value;

        //     $.ajax({
        //          url: "searchLeaveMonitoringTable.php",
        //          method: "post",
        //          data: {range:range, from_date:from_date, to_date:to_date, status:status},
        //          dataType: "text",
        //          success:function(search_result) {
        //            var search_data = $.trim(search_result);
        //            if (range == ' ' && status == '') {
        //             $('#tableContainer1').css('display', 'block');
        //             $('#tableContainer2').html('');
        //            } else {
        //             $('#tableContainer1').css('display', 'none');
        //             $('#tableContainer2').html(search_data);
        //            }
        //         }
        //     });
        // });

        $('#populateLeaveTable').on('change',function(){
             var employeeID = $('#employeeId').val();
            
             var range = $('#showDateRange').val();
             var from_date = document.getElementById('leave_from_date').value;
             var to_date = document.getElementById('leave_to_date').value;
             var status = document.getElementById('showStatus').value;

             if(range || employeeID){
                $.ajax({
                     url: "searchLeaveTable.php",
                     method: "post",
                     data: {employeeID:employeeID, range:range, from_date:from_date, to_date:to_date, status:status},
                     dataType: "text",
                     success:function(search_result) {
                       var search_data = $.trim(search_result);
                       $('#showLeaveTable').html(search_data);
                  }
                });
             }
        }); 

        //auto add leave input fields
        var leaveRowCtr = 1;

        // bug fix for closing calendar after selection
        $('#currentDate').datepicker({
            autoclose: true
        });
        $('#leaveDate').datepicker({
            autoclose: true
        });
    });

    function search_table()
    {
        var employeeID = $('#employeeId').val();
            
         var range = $('#showDateRange').val();
         var from_date = document.getElementById('leave_from_date').value;
         var to_date = document.getElementById('leave_to_date').value;
         var status = document.getElementById('showStatus').value;

         if(range || employeeID){
            $.ajax({
                 url: "searchLeaveTable.php",
                 method: "post",
                 data: {employeeID:employeeID, range:range, from_date:from_date, to_date:to_date, status:status},
                 dataType: "text",
                 success:function(search_result) {
                   var search_data = $.trim(search_result);
                   $('#showLeaveTable').html(search_data);
              }
            });
         }
    }

    function add_row()
    {
        var rows = parseInt($('#leave_form_row_count').val()) + 1;
        $('#updateLeaveTable').append(
            '<tr id="leave_row_' + rows + '">' +
                $('.first-row-data').html() +
                '<td class="hasInputData">\
                    <a href="#"><i class="fas fa-minus-circle" style="color:red" onclick="delete_row('+rows+')"></i></a>\
                </td>'+
            '</tr>');
        $('#leave_form_row_count').val(rows);

        var date = new Date();
        date.setDate(date.getDate() + 7);
        $('.apply-leave-date').datepicker({
            startDate: date
        });
    }

    function delete_row(id)
    {
        $('#leave_row_'+id).remove();
    }

    function row_check(elem)
    {
        var leave_type = $(elem).parent().parent().parent().find('select[name="leave_type[]"]').val();
        var leave_date = $(elem).parent().parent().parent().find('input[name="leave_date[]"]').val();
        var leave_duration = $(elem).parent().parent().parent().find('select[name="leave_duration[]"]').val();
        var leave_reason = $(elem).parent().parent().parent().find('input[name="leave_reason[]"]').val();

        if ($(elem).parent().parent().parent().is(':last-child')) {
            if ($.trim(leave_type) !== "" && $.trim(leave_date) !== "" && $.trim(leave_duration) !== "" && $.trim(leave_reason) !== "") {
                add_row();
            }
        }
    }

</script>