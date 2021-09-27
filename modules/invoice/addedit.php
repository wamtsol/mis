<?php
if(!defined("APP_START")) die("No Direct Access");
if( isset( $_GET[ "id" ] ) ) {
	$id = slash( $_GET[ "id" ] );
}
else {
	$id = 0;
}
?>
<div ng-app="invoice" ng-controller="invoiceController" id="invoiceController">
    <div style="display:none">{{invoice_id=<?php echo $id?>}}</div>
    <div class="page-header">
        <h1 class="title">{{get_action()}} Invoice</h1>
        <ol class="breadcrumb">
            <li class="active">Manage Invoice</li>
        </ol>
        <div class="right">
            <div class="btn-group" role="group" aria-label="..."> <a href="invoice_manage.php" class="btn btn-light editproject">Back to List</a> </div>
        </div>
    </div>
	<?php
        $i=0;
    ?>
    <div class="form-horizontal">
    <div class="form-group">
        <div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label">Project <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <select class="margin-btm-5" ng-model="invoice.project_id">
                    <option value="">Select Project</option>
                   	<option ng-repeat="project in projects" value="{{ project.id }}">{{ project.title }}</option>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label">Work Order Number</label>
            </div>
            <div class="col-sm-10">
            	<input ng-model="invoice.work_order_number" class="form-control" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="invoice_date">Invoice Date <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
            	<input ng-model="invoice.invoice_date" class="form-control date-picker" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label">Due Date <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
            	<input ng-model="invoice.due_date" class="form-control date-picker" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label">Type</label>
            </div>
            <div class="col-sm-10">
                <select class="margin-btm-5" ng-model="invoice.type">
                    <option value="">Select Invoice Type</option>
                   	<option value="0">Sales Tax Invoice</option>
                    <option value="1">Commercial Invoice</option>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label">Items <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <div class="panel-body table-responsive">
                    <table class="table table-hover list">
                        <thead>
                            <tr>
                                <th width="2%" class="text-center">S.no</th>
                                <th width="48%">Details *</th>
                                <th width="10%">Rate *</th>
                                <th colspan="2">Quantity *</th>
                                <th width="10%">Amount *</th>
                                <th width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="item in invoice.items">
                                <td class="text-center serial_number">{{ $index+1 }}</td>
                                <td>
                                    <textarea ng-model="invoice.items[ $index ].details" class="form-control"></textarea>
                                </td>
                                <td class="text-right"><input type="text" style="text-align:right" ng-model="invoice.items[ $index ].rate" ng-change="update_total( $index )" /></td>
                                <td class="text-right" width="7%"><input type="text" style="text-align:right" ng-model="invoice.items[ $index ].quantity" ng-change="update_total( $index )" /></td>
                                <td class="text-right">
                                	<select ng-model="invoice.items[ $index ].quantity_unit">
                                    	<option value="0">Nos.</option>
                                    </select>
                                </td>
                                <td class="text-right"><input type="text" ng-model="invoice.items[ $index ].amount" style="text-align:right" /></td>
                                <td class="text-center"><a href="" ng-click="add( $index )">Add</a> - <a href="" ng-click="remove( $index )">Delete</a></td>
                            </tr>
                            <tr>
                                <th colspan="5" class="text-right">Total</th>
                                <th class="text-right"><input type="text" style="text-align:right" ng-model="invoice.total_amount" ng-change='update_net_total()' /></th>
                                <th class="text-right">&nbsp;</th>
                            </tr>
                            <tr ng-show="invoice.type=='0'">
                                <th colspan="5" class="text-right">Sales Tax</th>
                                <th class="text-right"><input type="text" style="text-align:right" ng-model="invoice.sales_tax" ng-change='update_net_total()' /></th>
                                <th class="text-right">&nbsp;</th>
                            </tr>
                            <tr>
                                <th colspan="5" class="text-right">Discount</th>
                                <th class="text-right"><input type="text" style="text-align:right" ng-model="invoice.discount" ng-change='update_net_total()' /></th>
                                <th class="text-right">&nbsp;</th>
                            </tr>
                            
                            <tr>
                                <th colspan="5" class="text-right">Net Amount</th>
                                <th class="text-right"><input type="text" style="text-align:right" ng-model="invoice.net_amount" /></th>
                                <th class="text-right">&nbsp;</th>
                            </tr>
                            <tr>
                                <th colspan="5" class="text-right">W.H.T</th>
                                <th class="text-right"><input type="text" style="text-align:right" ng-model="invoice.wht" /></th>
                                <th class="text-right">&nbsp;</th>
                            </tr>
                            <tr>
                            	<th colspan="5" class="text-right">Payment Account</th>
                                <th colspan="2">
                                	<select ng-model="invoice.payment_account_id">
                                    	<option value="">Select Account</option>
                   						<option ng-repeat="account in accounts" value="{{ account.id }}">{{ account.title }}</option>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                            	<th colspan="5" class="text-right">Payment Amount</th>
                                <th>
                                	<input type="text" title="Payment Amount" ng-model="invoice.payment_amount"  class="form-control" style="text-align:right" />
                                </th>
                                <th class="text-right">&nbsp;</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-offset-2 col-sm-10">
            	<div class="alert alert-danger" ng-show="errors.length > 0">
                	<p ng-repeat="error in errors">{{error}}</p>
                </div>
                <button type="submit" ng-disabled="processing" class="btn btn-default btn-l" ng-click="save_invoice()" title="Submit Record"><i class="fa fa-spin fa-gear" ng-show="processing"></i> SUBMIT</button>
            </div>
        </div>
    </div>
    </div>
</div>
<style>input,select{ width:100%;}</style>