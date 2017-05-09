app.config(
    ['$stateProvider', 
    '$urlRouterProvider', 
    'config',
    function($stateProvider, $urlRouterProvider, config) {
        $urlRouterProvider.otherwise('/');
        $stateProvider
        .state('home', {
            url: '/',
            controller: 'homeCtrl',
        })
        .state('marketexplorer', {
            url: '/marketexplorer',
            controller: 'marketExplorerCtrl',
            templateUrl: config.dist + '/home/main-list-view.html'
        })
        .state('tradefinder', {
            url: '/tradefinder',
            controller: 'tradeFinderCtrl',
            templateUrl: config.dist + '/home/trade-finder-view.html'
        });
    }
]);