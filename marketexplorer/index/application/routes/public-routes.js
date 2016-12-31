app.config([
	'$stateProvider',
	'$urlRouterProvider',
	'config',
	function($stateProvider, $urlRouterProvider, config) {
		$urlRouterProvider.otherwise('/');

		$stateProvider
		.state('home', {
			url: '/',
			controller: 'appCtrl',
			templateUrl: config.dist + '/home/main-list-view.html'
		})
	}
]);