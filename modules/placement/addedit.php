<?php
if(!defined("APP_START")) die("No Direct Access");
if( isset( $_GET[ "id" ] ) ) {
	$id = slash( $_GET[ "id" ] );
}
else {
	$id = 0;
}
?>
<div ng-app="placement" ng-controller="placementController" id="placementController">
    <div style="display:none">{{placement_id=<?php echo $id?>}}</div>
    <div class="page-header">
        <h1 class="title">{{get_action()}} Placement</h1>
        <ol class="breadcrumb">
            <li class="active">Manage Placement</li>
        </ol>
        <div class="right">
            <div class="btn-group" role="group" aria-label="..."> <a href="placement_manage.php" class="btn btn-light editproject">Back to List</a> </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="date">Date <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
            	<input ng-model="placement.date" data-controllerid="placementController" class="form-control date-picker angular-datetimepicker" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="supplier_id">Location </label>
            </div>
            <div class="col-sm-10">
                <select class="margin-btm-5" ng-model="placement.location_id" convert-to-number="" ng-options="location.id as location.title
      for location in locations">
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label">Note </label>
            </div>
            <div class="col-sm-10">
                <textarea title="Enter Note" name="note" id="note" class="form-control" ng-model="placement.note">{{ placement.note }}</textarea>
            </div>
        </div>
    </div>
    <div class="form-group tble-items">
        <div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label">Items <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <div class="panel-body table-responsive">
                    <table class="table table-hover list">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">S.no</th>
                                <th>Item</th>
                                <th width="10%" class="text-right">Quantity</th>
                                <th class="text-center" width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="item in placement.items">
                                <td class="text-center serial_number">{{ $index+1 }}</td>
                                <td>
                                    <select title="Choose Option" ng-model="placement.items[ $index ].item_id" chosen convert-to-number="" ng-options="item.id as item.title for item in items">
                                    </select>
                                </td>
                                <td class="text-right"><input type="text" ng-model="placement.items[ $index ].quantity" ng-change='update_grand_total( $index )' /></td>
                                <td class="text-center"><a href="" ng-click="add( $index )">Add</a> - <a href="" ng-click="remove( $index )">Delete</a></td>
                            </tr>
                            <tr>
                                <th colspan="2" class="text-right">Total Items</th>
                                <th class="text-right">{{ update_grand_total() }}</th>
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
                <button type="submit" ng-disabled="processing" class="btn btn-default btn-l" ng-click="save_placement()" title="Submit Record"><i class="fa fa-spin fa-gear" ng-show="processing"></i> SUBMIT</button>
            </div>
        </div>
    </div>
</div>