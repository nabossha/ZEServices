/**
 * Created by nabossha on 03.09.2015.
 */
var vehiclestatusApp = angular.module('vehiclestatusApp', ['elif']);

vehiclestatusApp.controller('VehicleListCtrl', ['$scope', '$http', function ($scope, $http) {

    $scope.loadServerData = function(event) {
        url = 'application.php?returnJSON=true';
        // $http.get('test.json').
        $http.get(url).
            success(function (data) {
                // Save the data to local storage
                //localStorage[url] = data;
                console.log(data);
                $scope.callBack(data);
            }).
            error(function () {
                console.log("AJAX-Fehler");
                if (!!localStorage[url]) {
                    // We have some data cached, return that to the callback.
                    $scope.callback(localStorage[url]);
                    return;
                }
            });
    };
    $scope.callBack = function(data) {
        console.log(data);
        try {
            $('#page').data('plugin_xpull').reset();
        } catch (err) {
            //
        }
        $scope.vehicles = data.vehicles;
        $scope.LastUpdateFromAPI = data.LastUpdateFromAPI;

        $scope.setChargeDisplay = function (percentage) {
            return {width: percentage + "%"}
        };
        $scope.setChargeDisplayColor = function (percentage) {
            if (percentage <= 19 && percentage > 9) {
                return "medium";
            }
            else if (percentage <= 9) {
                return "low";
            }
            else {
                return "high";
            }
        };
    };

    // init on page load:
    angular.element(document).ready(function () {
        $scope.loadServerData("initial");
    });

    //$scope.orderProp = 'id';
}]);

/*
 Xpull - pull to refresh jQuery plugin as Angular directive
 */
vehiclestatusApp.directive("ngXpull", function() {
    return function(scope, elm, attr) {
        return $(elm[0]).xpull({
            'spinnerTimeout': 0,
            'callback': function() {
                scope.loadServerData("refresh");
                return scope.$apply(attr.ngXpull);
            }
        });
    };
});