me.directive('searchBar', [
    'config',
    '$http',
    'regionListFact', 
    'marketLookupFact',
    '$filter',
    '$timeout',
    '$interval',
    function(config, $http, regionListFact, marketLookupFact, $filter, $timeout, $interval) {
        "use strict";

        return {
            templateUrl: config.dist + '/partials/me/searchbar/search-bar-view.html',
            restrict: 'E',
            scope: {
                item: '=',
                region: '='
            },
            controller: ['$scope', function($scope) {
                $scope.search = {
                    region: 10000002,
                    item: ''
                };

                $scope.regions = [];
                getAllRegions();

                $scope.$watch('item', function(newValue, oldValue) {
                    if (newValue) {
                        $scope.search.item = newValue.name;
                    }
                }, true);

                // item autocomplete
                $scope.getItems = (val) => {
                    return $http.get(config.autocomplete, {
                        params: {
                            term: val
                        }
                    })
                    .then(function(response) {
                        return response.data.map(function(item){
                            return item;
                        });
                    });
                };

                // update search result
                $scope.itemSelected = (item) => {
                    $scope.item = {
                        name: item.value,
                        id: item.id
                    };
                };

                $scope.regionSelected = (region) => {
                    $scope.region = region;
                };

                function getAllRegions() {
                    regionListFact
                    .getAll()
                    .then(function(result) {
                        //console.log(result);
                        angular.forEach(result, function(cValue, cKey) {
                            if (cValue.id < 11000000) {
                                $scope.regions.push(cValue);
                            }
                        });
                        $scope.isLoadedRegions = true;
                    });
                }
            }],

            link: function() {
            }
        };
    }
]);