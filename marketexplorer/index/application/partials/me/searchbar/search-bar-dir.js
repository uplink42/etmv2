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
                region: '=',
                regions: '=',
            },
            controller: ['$scope', function($scope) {
                const ignoreRegions = [10000019, 10000017, 10000004];
                
                $scope.search = {
                    region: 10000002,
                    item: ''
                };

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
                        $scope.regions.push({ id: '-1', name: 'ALL' });
                        //console.log(result);
                        angular.forEach(result, function(cValue, cKey) {
                            if (cValue.id < 11000000 && !ignoreRegions.includes(cValue.id)) {
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