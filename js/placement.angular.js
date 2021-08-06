angular.module('placement', ['ngAnimate', 'angularMoment', 'ui.bootstrap', 'angularjs-datetime-picker', 'localytics.directives']).controller('placementController', 
	function ($scope, $http, $interval, $filter) {
		$scope.items = [];
		$scope.locations = [];
		$scope.errors = [];
		$scope.processing = false;
		$scope.placement_id = 0;
		$scope.placement = {
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
			$scope.placement.date = $(".angular-datetimepicker").val();
			$scope.$apply();
		}
		angular.element(document).ready(function () {
			$scope.wctAJAX( {action: 'get_location'}, function( response ){
				$scope.locations = response;
			});
			$scope.wctAJAX( {action: 'get_item'}, function( response ){
				$scope.items = response;
			});
			if( $scope.placement_id > 0 ) {
				$scope.wctAJAX( {action: 'get_placement', id: $scope.placement_id}, function( response ){
					$scope.placement = response;
				});
			}
			else {
				$scope.wctAJAX( {action: 'get_date'}, function( response ){
					$scope.placement.date = JSON.parse( response );
				});
				$scope.placement.items.push( angular.copy( $scope.item ) );
			}
		});
		
		$scope.get_action = function(){
			if( $scope.placement_id > 0 ) {
				return 'Edit';
			}
			else {
				return 'Add New';
			}
		}
		
		$scope.add = function( position ){
			$scope.placement.items.splice(position+1, 0, angular.copy( $scope.item ) );
			$scope.update_grand_total();
		}
		
		$scope.remove = function( position ){
			if( $scope.placement.items.length > 1 ){
				$scope.placement.items.splice( position, 1 );
			}
			else {
				$scope.placement.items = [];
				$scope.placement.items.push( angular.copy( $scope.item ) );
			}
			$scope.update_grand_total();
		}
		$scope.update_grand_total = function(){
            total = 0;
            for( i = 0; i < $scope.placement.items.length; i++ ) {
                total += parseFloat( $scope.placement.items[ i ].quantity );
			}
			return total;
        }
		$scope.wctAJAX = function( wctData, wctCallback ) {
			wctData.tab = 'addedit';
			wctRequest = {
				method: 'POST',
				url: 'placement_manage.php',
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
		$scope.save_placement = function () {
			$scope.errors = [];
			if( $scope.processing == false ){
				$scope.processing = true;
				data = {action: 'save_placement', placement: JSON.stringify( $scope.placement )};
				$scope.wctAJAX( data, function( response ){
					$scope.processing = false;
					if( response.status == 1 ) {
						window.location.href='placement_manage.php?tab=addedit&id='+response.id;
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