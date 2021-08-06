<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["expense_manage"]["add"])){
	extract($_SESSION["expense_manage"]["add"]);	
}
else{
	$employee_id="";
	$category_id="";
	$datetime_added=date("d/m/Y H:i A");
	$details="";
	$amount="";
	$payment="";
}
?>
<div class="page-header">
	<h1 class="title">Add New Salary</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Salary</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="salary_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form class="form-horizontal form-horizontal-left form-salary" role="form" action="salary_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();">
    <?php
        $i=0;
    ?>
    <div class="form-group">
        <div class="row">
        	 <div class="col-sm-2 control-label">
            	<label class="form-label" for="">Employee Name </label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Emloyee" value="" name="" id="" class="form-control" />
            </div>
        </div>
    </div>
    <div class="form-group">
    <div class="row">
    <div class="col-sm-12">
    <table class="table table-hover list sales-table">
    	<thead>
        	<tr>
            	<th class="text-center" width="5%">S.NO</th>
                <th width="15%">Date</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
        	<tr>
            	<td class="text-center">1</td>
                <td>08-06-2017-Thu</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr bgcolor="#fff">
            	<td class="text-center">2</td>
                <td>09-06-2017-Fri</td>
                <th colspan="3" class="text-center">Holiday</th>
            </tr>
            <tr>
            	<td class="text-center">1</td>
                <td>08-06-2017-Thu</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
            	<td class="text-center">1</td>
                <td>08-06-2017-Thu</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
            	<td class="text-center">1</td>
                <td>08-06-2017-Thu</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr bgcolor="#fff">
            	<td class="text-center">2</td>
                <td>09-06-2017-Fri</td>
                <th colspan="3" class="text-center">Holiday</th>
            </tr>
            <tr>
            	<td class="text-center">1</td>
                <td>08-06-2017-Thu</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
            	<td class="text-center">1</td>
                <td>08-06-2017-Thu</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
            	<th colspan="2" class="text-right">Advance</th>
                <td colspan="3"><input type="text" title="Enter Advance" value="" name="" id="" class="form-control" /></td>
            </tr>
            <tr>
            	<th colspan="2" class="text-right">Total Amount</th>
                <td colspan="3"><input type="text" title="Enter Total Amount" value="" name="" id="" class="form-control" /></td>
            </tr>
        </tbody>
    </table>
    </div>
    </div>
    </div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-10">
                <input type="submit" value="SUBMIT" class="btn btn-default btn-l" name="salary_add" title="Submit Record" />
            </div>
        </div>
  	</div>  
</form>