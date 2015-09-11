/**
 * Created by nabossha on 03.09.2015.
 */
var vehiclestatusApp = angular.module('vehiclestatusApp', ['elif']);

vehiclestatusApp.controller('VehicleListCtrl', ['$scope', '$http', function ($scope, $http) {

    $scope.loadServerData = function(event) {
        url = '/application.php?returnJSON=true';
        $('.loadbutton').addClass('loadingdata');
        $('#errorwindow').hide();
        $http.get(url).
            success(function (data) {
                // Save the data to local storage
                //localStorage[url] = data;
                $scope.callBack(data);
            }).
            error(function (error) {
                $('#errorwindow').show().html(error);
                $('.loadbutton').removeClass('loadingdata');
                if (!!localStorage[url]) {
                    // We have some data cached, return that to the callback.
                    $scope.callback(localStorage[url]);
                }
            });
    };
    $scope.callBack = function(data) {
        //console.log(data);
        try {
            $('#page').data('plugin_xpull').reset();
            $('.loadbutton').removeClass('loadingdata');
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