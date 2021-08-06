<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["employee_manage"]["add"])){
	extract($_SESSION["employee_manage"]["add"]);	
}
else{
    $designation_id="";
    $project_ids=array();
	$date_of_appointment=date("d/m/Y");
	$name="";
	$father_name="";
	$contact_number="";
    $cnic_number="";
    $gender="";
    $address="";
    $salary="";
    $bank_account_number="";
}
?>
<div class="page-header">
	<h1 class="title">Add New Employee</h1>
  	<ol class="breadcrumb">
    	<li class="active">
        	Manage Employee
        </li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="employee_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form class="form-horizontal form-horizontal-left" role="form" action="employee_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();">
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="project_id">Project </label>
            </div>
            <div class="col-sm-10">
                <select name="project_ids[]" multiple="multiple" class="select_multiple" title="Choose Option">
                    <?php
                    $res=doquery("select * from project where status=1 order by title", $dblink);
                    if(numrows($res)>0){
                        while($rec=dofetch($res)){
                        ?>
                        <option value="<?php echo $rec["id"]?>"<?php echo(isset($project_ids) && in_array( $rec["id"], $project_ids))?"selected":"";?>><?php echo unslash($rec["title"]);?></option>
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
            <div class="col-sm-2 control-label">
                <label class="form-label" for="designation_id">Designation</label>
            </div>
            <div class="col-sm-10">
                <select name="designation_id" title="Choose Option">
                    <option value="0">Select Designation</option>
                    <?php
                    $res=doquery("select * from designation where status=1 order by title", $dblink);
                    if(numrows($res)>0){
                        while($rec=dofetch($res)){
                        ?>
                        <option value="<?php echo $rec["id"]?>"<?php echo($designation_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"]); ?></option>
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
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="name">Name <span class="manadatory">*</span> </label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Name" value="<?php echo $name; ?>" name="name" id="name" class="form-control" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="father_name">Father Name </label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Father Name" value="<?php echo $father_name; ?>" name="father_name" id="father_name" class="form-control" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="contact_number">Contact Number </label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Contact Number" value="<?php echo $contact_number; ?>" name="contact_number" id="contact_number" class="form-control" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="cnic_number">CNIC </label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter CNIC" value="<?php echo $cnic_number; ?>" name="cnic_number" id="cnic_number" class="form-control" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="gender">Gender </label>
            </div>
            <div class="col-sm-10">
                <select name="gender" id="gender" title="Choose Option">
                    <option value="0"<?php echo $gender==0?" selected":""?>>Male</option>
                    <option value="1"<?php echo $gender==1?" selected":""?>>Female</option>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
        	 <div class="col-sm-2 control-label">
            	<label class="form-label" for="date_of_appointment">Date of Appointment</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Date" value="<?php echo $date_of_appointment; ?>" name="date_of_appointment" id="date_of_appointment" class="form-control date-picker" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="address">Address </label>
            </div>
            <div class="col-sm-10">
                 <textarea title="Enter Address" name="address" id="address" class="form-control"><?php echo $address; ?></textarea>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="salary">Salary </label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Salary" value="<?php echo $salary; ?>" name="salary" id="salary" class="form-control" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="bank_account_number">Bank Account Number </label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Bank Account Number" value="<?php echo $bank_account_number; ?>" name="bank_account_number" id="bank_account_number" class="form-control" />
            </div>
        </div>
    </div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label for="company" class="form-label"></label>
            </div>
            <div class="col-sm-10">
                <input type="submit" value="SUBMIT" class="btn btn-default btn-l" name="employee_add" title="Submit Record" />
            </div>
        </div>
  	</div>  
</form>