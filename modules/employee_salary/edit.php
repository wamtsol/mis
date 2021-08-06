<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-header">
	<h1 class="title">Edit Employee Salary</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Employee Salary</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="employee_salary_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>        	
<form class="form-horizontal form-horizontal-left" role="form" action="employee_salary_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd">
    <input type="hidden" name="id" value="<?php echo $id;?>">
    <div class="form-group">
        <div class="row">
            <label class="col-sm-2 control-label no-padding-right" for="employee_id">Employee <span class="red">*</span></label>
            <div class="col-sm-10">
                <select name="employee_id" id="employee_id" class="col-xs-12" title="Choose Option">
                    <option value="0">Select Employee</option>
                    <?php
                    $res=doquery("Select * from employee where status = 1 order by name",$dblink);
                    if(numrows($res)>0){
                        while($rec=dofetch($res)){
                        ?>
                        <option value="<?php echo $rec["id"]?>"<?php echo($employee_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["name"]); ?></option>
                        <?php			
                        }			
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <label class="col-sm-2 control-label no-padding-right" for="project_id">Project</label>
            <div class="col-sm-10">
                <select name="project_id" id="project_id" class="col-xs-12" title="Choose Option">
                    <option value="0">Select Project</option>
                    <?php
                    $res=doquery("Select * from project where status = 1 order by title",$dblink);
                    if(numrows($res)>0){
                        while($rec=dofetch($res)){
                        ?>
                        <option value="<?php echo $rec["id"]?>"<?php echo($project_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
                        <?php			
                        }			
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <label class="col-sm-2 control-label no-padding-right" for="month">Month </label>
            <div class="col-sm-10">
                <select name="month" title="Choose Option">
                    <option value="0">Select Month</option>
                    <?php
                    foreach ($month_array as $key=>$value) {
                        ?>
                        <option value="<?php echo $key?>"<?php echo $key==$month?' selected="selected"':""?>><?php echo $value ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <label class="col-sm-2 control-label no-padding-right" for="year">Year</label>
            <div class="col-sm-10">
                <input type="text" title="Enter Year" value="<?php echo $year; ?>" name="year" id="year" class="form-control" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-2 control-label no-padding-right">
                <label class="form-label" for="datetime_added">DateTime</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter DateTime" value="<?php echo $datetime_added; ?>" name="datetime_added" id="datetime_added" class="form-control date-timepicker" >
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <label class="col-sm-2 control-label no-padding-right" for="amount">Amount</label>
            <div class="col-sm-10">
                <input type="text" title="Enter Amount" value="<?php echo $amount; ?>" name="amount" id="amount" class="form-control" />
            </div>
        </div>
    </div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label for="company" class="form-label"></label>
            </div>
            <div class="col-sm-10">
                <input type="submit" value="Update" class="btn btn-default btn-l" name="employee_salary_edit" title="Update Record" />
            </div>
        </div>
  	</div>
</form>