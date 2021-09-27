<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-header">
	<h1 class="title">Edit Project Payment</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Project Payment</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="project_payment_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form action="project_payment_manage.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd"  class="form-horizontal form-horizontal-left">
	<input type="hidden" name="id" value="<?php echo $id;?>">
  	<div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="project_id">Project <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <select name="project_id" id="project_id" class="margin-btm-5 select_supplier">
                	<option value="">Select Project</option>
                    <?php
                    $rs = doquery( "select * from project where status=1 order by title", $dblink );
					if( numrows( $rs ) > 0 ) {
						while( $r = dofetch( $rs ) ) {
							?>
							<option value="<?php echo $r["id"]?>"<?php echo($project_id==$r["id"])?"selected":"";?>><?php echo unslash($r["title"]); ?></option>
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
                <label class="form-label" for="datetime_added">Datetime</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter datetime" value="<?php echo $datetime_added; ?>" name="datetime_added" id="datetime_added" class="form-control date-timepicker" >
            </div>
        </div>
  	</div>
  	<div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="amount">Amount</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter amount" value="<?php echo $amount; ?>" name="amount" id="tax_amount" class="form-control" >
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="sales_tax">Sales Tax</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Sales Tax" value="<?php echo $sales_tax; ?>" name="sales_tax" id="sales_tax" class="form-control" >
            </div>
        </div>
    </div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="gst_withheld">Gst Withheld</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Gst Withheld" value="<?php echo $gst_withheld; ?>" name="gst_withheld" id="gst_withheld" class="form-control" >
            </div>
        </div>
    </div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="invoice_amount">Invoice Amount</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Invoice Amount" value="<?php echo $invoice_amount; ?>" name="invoice_amount" id="invoice_amount" class="form-control" >
            </div>
        </div>
  	</div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="account_id">Paid By </label>
            </div>
            <div class="col-sm-10">
                <select name="account_id" title="Choose Option">
                    <option value="0">Select Account</option>
                    <?php
                    $res=doquery("select * from account where status=1 order by title", $dblink);
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
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="details">Details </label>
            </div>
            <div class="col-sm-10">
                 <textarea title="Enter Details" value="" name="details" id="details" class="form-control" /><?php echo $details; ?></textarea>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="exempt_tax">Exempt Tax </label>
            </div>
            <div class="col-sm-10">
                <select name="exempt_tax" id="exempt_tax">
                    <option value="0"<?php echo $exempt_tax=="0"?' selected="selected"':''?>>No</option>
                    <option value="1"<?php echo $exempt_tax=="1"?' selected="selected"':''?>>Yes</option>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="total_applications">Total Applications</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Total applications" value="<?php echo $total_applications; ?>" name="total_applications" id="total_applications" class="form-control" >
            </div>
        </div>
  	</div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="noc_delivery">Noc Delivery</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Noc delivery" value="<?php echo $noc_delivery; ?>" name="noc_delivery" id="noc_delivery" class="form-control" >
            </div>
        </div>
  	</div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="disabled">Disabled</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter disabled" value="<?php echo $disabled; ?>" name="disabled" id="disabled" class="form-control" >
            </div>
        </div>
  	</div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="discount">Discount</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter discount" value="<?php echo $discount; ?>" name="discount" id="discount" class="form-control" >
            </div>
        </div>
  	</div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="credit">Credit</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Credit" value="<?php echo $credit; ?>" name="credit" id="credit" class="form-control">
            </div>
        </div>
  	</div>
      <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="correction">Correction</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Correction" value="<?php echo $correction; ?>" name="correction" id="correction" class="form-control">
            </div>
        </div>
  	</div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="correction_returned">Credit Returned</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Credit returned" value="<?php echo $correction_returned; ?>" name="correction_returned" id="correction_returned" class="form-control">
            </div>
        </div>
  	</div>
      <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="home_delivery">Home Delivery</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Home Delivery" value="<?php echo $home_delivery; ?>" name="home_delivery" id="home_delivery" class="form-control">
            </div>
        </div>
  	</div>
  	<div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label for="company" class="form-label"></label>
            </div>
            <div class="col-sm-10">
                <input type="submit" value="UPDATE" class="btn btn-default btn-l" name="project_payment_edit" title="Update Record" />
            </div>
        </div>
  	</div>
</form>