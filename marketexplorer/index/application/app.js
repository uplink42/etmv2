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
    dist: "../../dist/me/app",
    autocomplete: base + "Stocklists/searchItems"
})
.config(['$qProvider', function ($qProvider) {
    $qProvider.errorOnUnhandledRejections(false);
}])
.config(['$httpProvider', function($httpProvider) {
        $httpProvider.defaults.useXDomain = true;
        delete $httpProvider.defaults.headers.common['X-Requested-With'];
    }
])

.controller('appCtrl', [
    '$scope',
    'config', 
    function($scope, config) {
        $scope.item = {};

        $scope.region = {};

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

        $scope.time = "";

        $scope.itemImg = "https://image.eveonline.com/Type/";
}]);
