var app = angular.module('app', [
    'tf',
    'me',
    'ngAnimate',
    'ui.bootstrap',
    'angular-loading-bar',
    'ui.router',
])

.constant('config', {
    crest: {
        base: 'https://crest-tq.eveonline.com/',
    },
    dist: '../../dist/me/app',
    autocomplete: base + 'Stocklists/searchItems'
})

.config(['$qProvider', function ($qProvider) {
    $qProvider.errorOnUnhandledRejections(false);
}])

.config(['$httpProvider', function($httpProvider) {
        $httpProvider.defaults.useXDomain = true;
        delete $httpProvider.defaults.headers.common['X-Requested-With'];
}]);