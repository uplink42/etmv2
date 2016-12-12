app.directive('searchBar', [
    '$http',
    'config',
    'regionListFact',
    'itemListFact',
    'marketLookupFact',
    '$filter',
    function($http, config, regionListFact, itemListFact, marketLookupFact, $filter) {
        "use strict";

        return {
            templateUrl: 'application/partials/searchbar/search-bar-view.html',
            restrict: 'E',
            scope: {
                //region: '=',
                item: '=',
                buyorders: '=',
                sellorders: '=',
            },
            controller: function($scope) {

                $scope.search = {
                    region: 10000002,
                    item: ''
                }
                $scope.regions = [];

                $scope.$watch('search.region', function(newi, old) {
                    if (newi) {
                        updateItem($scope.item);
                    }
                    //update watches
                });


                $scope.itemSelected = function($item) {
                    console.log($item);

                    $scope.item = {
                        name: $item.value,
                        id: $item.id
                    }
                    console.log ('itemselected');
                    //getItemOrders($item.id);
                }

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


                $scope.getItems = function(val) {
                    return $http.get('https://www.evetrademaster.com/v2/Stocklists/searchItems', {
                        params: {
                            term: val
                        }
                    })
                    .then(function(response) {
                        return response.data.map(function(item){
                            return item;
                        });
                    });
                }


                $scope.$watch('item', function(newValue, oldValue) {
                    updateItem(newValue);
                }, true);

                function updateItem(newValue) {
                    if (newValue.id) {
                        console.log ('item');
                        getItemOrders(newValue.id);
                        $scope.search.item = newValue.name;
                    }
                }

                /*$scope.$watch('name', function(newValue, oldValue) {
                    if (newValue) {
                        console.log ('name');
                        $scope.search.item = newValue;
                    }
                });*/


                function getItemOrders(id) {
                    console.log ('getting orders', id);
                    //sell
                    marketLookupFact
                    .queryItem($scope.search.region, 'sell', id)
                    .then(function(responseSell) {
                        //$scope.sellorders = response;
                        var totalSell = 0;
                        angular.forEach(responseSell, function(cValue, cKey) {
                            totalSell += cValue.volume;
                        })
                        $scope.sellorders.total = totalSell;
                        $scope.sellorders.items = $filter('orderBy')(responseSell, 'price');
                        //orderby price
                    });

                    //buy
                    marketLookupFact
                    .queryItem($scope.search.region, 'buy', id)
                    .then(function(responseBuy) {
                        var totalBuy = 0;
                        angular.forEach(responseBuy, function(cValue, cKey) {
                            totalBuy += cValue.volume;
                        })
                        $scope.buyorders.total = totalBuy;
                        //$scope.buyorders = response;
                        $scope.buyorders.items = $filter('orderBy')(responseBuy, '-price');
                    });
                }
            },

            link: function() {

            }
        };
    }
]);