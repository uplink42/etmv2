app.config(
    ['$stateProvider', 
    '$urlRouterProvider', 
    'config',
    function($stateProvider, $urlRouterProvider, config) {
        $urlRouterProvider.otherwise('/');
        $stateProvider
        .state('home', {
            url: '/',
            abstract: true,
            templateUrl: config.dist + '/templates/public-template.html'
        })
        .state('home.register', {
            url: '/register',
            controller: 'registerCtrl',
            templateUrl: config.dist + '/partials/register/register-view.html'
        })
        .state('home.login', {
            url: '/login',
            controller: 'loginCtrl',
            templateUrl: config.dist + '/partials/login/login-view.html'
        });
    }
]);