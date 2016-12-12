var app = angular.module("app", [
    'ngAnimate',
    'ui.bootstrap',
    'angular-loading-bar',
    'ngSanitize',
    'toastr',
    'ui.router',
    'rt.debounce',
    ])

.constant("config", {
    crest: {
        base: 'https://crest-tq.eveonline.com/',
    }
})

.config(['$qProvider', function ($qProvider) {
    $qProvider.errorOnUnhandledRejections(false);
}])

.controller('appCtrl', [
    '$scope',
    'crestFact',
    'itemListFact',
    'config', 
    function($scope, crestFact, itemListFact, config) {
        //$scope.$storage = $localStorage;
        $scope.home = "application/home/main-list-view.html";

        $scope.item = {};

        $scope.buyorders = {
            items: [],
            total: ''
        };
        $scope.sellorders = {
            items: [],
            total: ''
        };

        $scope.pagination = {
            sell: 1,
            buy: 1
        };

        $scope.itemImg = "https://image.eveonline.com/Type/";

        /*if (angular.isUndefined($localStorage.items)) {
            itemListFact
            .getAll()
            .then(function(result) {
                $localStorage.items = result;
            });
        }*/

        
    /*regionListFact.getAll()
        .then(function(result) {
            $rootScope.regions = result;
    });*/

}]);
