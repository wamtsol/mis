<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-header">
	<h1 class="title">Employee's Attendance</h1>
  	<ol class="breadcrumb">
    	<li class="active">Employee Attendance</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="employee_attendance_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form action="employee_attendance_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd"  class="form-horizontal form-horizontal-left">
	<input type="hidden" name="id" id="id" value="<?php echo $id;?>">
    <input type="hidden" name="date" id="date" value="<?php echo $date;?>">
	<style>
    .student_list{
        padding:10px;
        border:solid 1px;
        border-radius: 5px;
    }
    .student_item{
        display:block;
        padding:5px;
        border-bottom: solid 1px;
        cursor:pointer;
    }
    #present_list .student_item{ background-color:#CFF;}
    #absent_list .student_item{ background-color:#FCF;}
    </style>
    <div class="form-group" ng-app="attendance" ng-controller="attendanceController">
        <input type="hidden" name="employees" id="present" value="{{employees}}">
        <div class="row">
            <div class="col-sm-6">
                <h2>Present Employees</h2>
                <div class="student_list" id="present_list">
                    <div ng-repeat="employee in employees|filter:{status:true}" class="student_item" ng-dblclick="employee.status=!employee.status">
                        <span>{{ $index+1 }}.</span> {{ employee.name }}
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <h2>Absent Employees</h2>
                <div class="student_list" id="absent_list">
                    <div ng-repeat="employee in employees|filter:{status:false}" class="student_item" ng-dblclick="employee.status=!employee.status">
                        <span>{{ $index+1 }}</span> {{ employee.name }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group form-actions">
        <div class="row">
            <div class="col-md-offset-3 col-md-9">
                <button class="btn btn-default btn-l" type="submit" name="employee_attendance_save" title="Save Attendance">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    Save Attendance
                </button>
            </div>
        </div>
    </div>
</form>