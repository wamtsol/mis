angular.module('supply', ['ngAnimate', 'angularMoment', 'ui.bootstrap', 'angularjs-datetime-picker', 'localytics.directives']).controller('supplyController', 
	function ($scope, $http, $interval, $filter) {
		$scope.items = [];
		$scope.locations = [];
		$scope.errors = [];
		$scope.processing = false;
		$scope.supply_id = 0;
		$scope.supply = {
			id: 0,
			date: '',
			location_id: 0,
			items: [],
			note: ''
		};
		$scope.item = {
			"id": 0,
			"item_id": "",
			"quantity": 0
		};
		$scope.updateDate = function(){
			$scope.supply.date = $(".angular-datetimepicker").val();
			$scope.$apply();
		}
		angular.element(document).ready(function () {
			$scope.wctAJAX( {action: 'get_location'}, function( response ){
				$scope.locations = response;
			});
			$scope.wctAJAX( {action: 'get_item'}, function( response ){
				$scope.items = response;
			});
			if( $scope.supply_id > 0 ) {
				$scope.wctAJAX( {action: 'get_supply', id: $scope.supply_id}, function( response ){
					$scope.supply = response;
				});
			}
			else {
				$scope.wctAJAX( {action: 'get_date'}, function( response ){
					$scope.supply.date = JSON.parse( response );
				});
				$scope.supply.items.push( angular.copy( $scope.item ) );
			}
		});
		
		$scope.get_action = function(){
			if( $scope.supply_id > 0 ) {
				return 'Edit';
			}
			else {
				return 'Add New';
			}
		}
		
		$scope.add = function( position ){
			$scope.supply.items.splice(position+1, 0, angular.copy( $scope.item ) );
			$scope.update_grand_total();
		}
		
		$scope.remove = function( position ){
			if( $scope.supply.items.length > 1 ){
				$scope.supply.items.splice( position, 1 );
			}
			else {
				$scope.supply.items = [];
				$scope.supply.items.push( angular.copy( $scope.item ) );
			}
			$scope.update_grand_total();
		}
		$scope.update_grand_total = function(){
            total = 0;
            for( i = 0; i < $scope.supply.items.length; i++ ) {
                total += parseFloat( $scope.supply.items[ i ].quantity );
			}
			return total;
        }
		$scope.wctAJAX = function( wctData, wctCallback ) {
			wctData.tab = 'addedit';
			wctRequest = {
				method: 'POST',
				url: 'supply_manage.php',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				transformRequest: function(obj) {
					var str = [];
					for(var p in obj){
						str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
					}
					return str.join("&");
				},
				data: wctData
			}
			$http(wctRequest).then(function(wctResponse){
				wctCallback(wctResponse.data);
			}, function () {
				console.log("Error in fetching data");
			});
		}
		$scope.save_supply = function () {
			$scope.errors = [];
			if( $scope.processing == false ){
				$scope.processing = true;
				data = {action: 'save_supply', supply: JSON.stringify( $scope.supply )};
				$scope.wctAJAX( data, function( response ){
					$scope.processing = false;
					if( response.status == 1 ) {
						window.location.href='supply_manage.php?tab=addedit&id='+response.id;
					}
					else{
						$scope.errors = response.error;
					}
				});
			}
		}
	}
).filter('trusted_html', ['$sce', function($sce){
	return function(text) {
		return $sce.trustAsHtml(text);
	};
}]).directive('ngEnter', function() {
	return function(scope, element, attrs) {
		element.bind("keydown keypress", function(event) {
			if(event.which === 13) {
				scope.$apply(function(){
					scope.$eval(attrs.ngEnter, {'event': event});
				});

				event.preventDefault();
			}
		});
	};
}).directive('convertToNumber', function() {
	return {
		require: 'ngModel',
		link: function(scope, element, attrs, ngModel) {
			ngModel.$parsers.push(function(val) {
				return val != null ? parseInt(val, 10) : null;
			});
			ngModel.$formatters.push(function(val) {
				return val != null ? '' + val : null;
			});
		}
	};
}).directive('chosenNg', function($compile) {
	return {
		restrict: 'A',
		replace: false,
		link: function($scope, element, attrs) {
			element.trigger("chosen:update");
		}
	};
});