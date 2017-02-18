var app = angular.module("app", [
    'ngAnimate',
    'ui.bootstrap',
    'angular-loading-bar',
    'ui.router',
    ])

.constant("config", {
    crest: {
        base: 'https://crest-tq.eveonline.com/',
    },
    dist: "dist/app"
})

.config(['$qProvider', function ($qProvider) {
    $qProvider.errorOnUnhandledRejections(false);
}])

.controller('appCtrl', [
    '$scope',
    'config', 
    function($scope, config) {

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
}]);
