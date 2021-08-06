<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["employee_salary_payment_manage"]["add"])){
	extract($_SESSION["employee_salary_payment_manage"]["add"]);	
}
else{
	$employee_id="";
	$datetime_added=date("d/m/Y H:i A");
	$amount="";
	$account_id="";
    $details="";
    $project_id="";
    $cheque_number="";
}
?>
<div class="page-header">
	<h1 class="title">Add New Employee Salary Payment</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Employee Salary Payment</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="employee_salary_payment_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form class="form-horizontal form-horizontal-left form-employee_salary_payment" role="form" action="employee_salary_payment_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();">
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
           
                <label class="col-sm-2 control-label no-padding-right" for="datetime_added">DateTime</label>
          
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
            <label class="col-sm-2 control-label no-padding-right" for="account_id">Paid By </label>
            <div class="col-sm-10">
                <select name="account_id" id="account_id" class="col-xs-12" title="Choose Option">
                    <option value="0">Select Account</option>
                    <?php
                    $res=doquery("Select * from account order by title",$dblink);
                    if(numrows($res)>0){
                        while($rec=dofetch($res)){
                        ?>
                        <option value="<?php echo $rec["id"]?>"<?php echo($account_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
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
            <label class="col-sm-2 control-label no-padding-right" for="cheque_number">Cheque Number</label>
            <div class="col-sm-10">
                <input type="text" title="Enter Cheque Number" value="<?php echo $cheque_number; ?>" name="cheque_number" id="cheque_number" class="form-control" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <label class="col-sm-2 control-label no-padding-right" for="details">Details </label>
            <div class="col-sm-10">
                 <textarea title="Enter Details" value="" name="details" id="details" class="col-xs-12" /><?php echo $details; ?></textarea>
            </div>
        </div>
    </div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-10">
                <input type="submit" value="SUBMIT" class="btn btn-default btn-l" name="employee_salary_payment_add" title="Submit Record" />
            </div>
        </div>
  	</div>  
</form>