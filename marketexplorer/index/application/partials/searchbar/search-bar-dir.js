app.directive('searchBar', [
    'config',
    '$http',
    'regionListFact', 
    'marketLookupFact',
    '$filter',
    function(config, $http, regionListFact, marketLookupFact, $filter) {
        "use strict";

        return {
            templateUrl: config.dist + '/partials/searchbar/search-bar-view.html',
            restrict: 'E',
            scope: {
                item: '=',
                region: '=',
                buyorders: '=',
                sellorders: '=',
            },
            controller: ['$scope', function($scope) {
                $scope.search = {
                    region: 10000002,
                    item: ''
                };

                $scope.regions = [];
                getAllRegions();

                $scope.$watch('search.region', function(newi, old) {
                    if (newi) {
                        $scope.region = newi;
                        updateItem($scope.item);
                    }
                });

                $scope.getItems = function(val) {
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

                $scope.$watch('item', function(newValue, oldValue) {
                    updateItem(newValue);
                }, true);

                function updateItem(newValue) {
                    if (newValue.id) {
                        getItemOrders(newValue.id);
                        $scope.search.item = newValue.name;
                    }
                }

                $scope.itemSelected = function($item) {
                    $scope.item = {
                        name: $item.value,
                        id: $item.id
                    };
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

                function getItemOrders(id) {
                    //sell
                    marketLookupFact
                    .queryItem($scope.search.region, 'sell', id)
                    .then(function(responseSell) {
                        //$scope.sellorders = response;
                        var totalSell = 0;
                        angular.forEach(responseSell, function(cValue, cKey) {
                            totalSell += cValue.volume;
                        });
                        $scope.sellorders.total = totalSell;
                        $scope.sellorders.items = $filter('orderBy')(responseSell, 'price');
                    });

                    //buy
                    marketLookupFact
                    .queryItem($scope.search.region, 'buy', id)
                    .then(function(responseBuy) {
                        var totalBuy = 0;
                        angular.forEach(responseBuy, function(cValue, cKey) {
                            totalBuy += cValue.volume;
                        });
                        $scope.buyorders.total = totalBuy;
                        $scope.buyorders.items = $filter('orderBy')(responseBuy, '-price');
                    });
                }
            }],

            link: function() {
            }
        };
    }
]);