app.config(function($stateProvider, $urlRouterProvider) {
    $urlRouterProvider.otherwise('/');

	$stateProvider
	.state('home', {
		url: '/',
		controller: 'appCtrl',
		templateUrl: 'application/home/main-list-view.html'
	})
});