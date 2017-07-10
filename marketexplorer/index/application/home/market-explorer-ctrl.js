me.controller('marketExplorerCtrl', [
    '$scope',
    '$timeout',
    '$interval',
    '$filter',
    '$http',
    'config',
    'marketLookupFact',
    function ($scope, $timeout, $interval, $filter, $http, config, marketLookupFact) {
        "use strict";

        $scope.item      = {};
        $scope.region    = {};
        $scope.regions   = [];

        $scope.buyorders = {
            items: [],
            total: '',
            recent: 0,
        };
        $scope.sellorders = {
            items: [],
            total: '',
            recent: 0,
        };
        $scope.pagination = {
            sell: 1,
            buy: 1
        };

        $scope.time   = "";
        $scope.itemImg = "https://image.eveonline.com/Type/";

        $scope.spread = {
            minSell: 0,
            maxBuy: 0,
            value: 0
        };

        let updateData,
            countdown,
            frequency = 310000,
            interval  = 1000;

        // orders data
        let totalSell = 0,
            totalBuy  = 0;

        let oneHourAgo = '';

        // watches
        $scope.$watch('region', function(newi, old) {
            if (newi) {
                $timeout.cancel(updateData);
                $interval.cancel(countdown);
                $scope.region = newi;
                updateItem($scope.item);
            }
        });

        $scope.$watch('item', function(newValue, oldValue) {
            $timeout.cancel(updateData);
            $interval.cancel(countdown);
            updateItem(newValue);
        }, true);

        $scope.$watchGroup(['spread.minSell', 'spread.maxBuy'], function(newValues, oldValues) {
            if (newValues[0] && newValues[1]) {
                $scope.spread.value = (parseFloat(newValues[0]) - parseFloat(newValues[1])) / newValues[0] * 100;
            }   
        });

        function updateItem(newValue) {
            if (newValue.id) {
                update(true);
            }
        }

        function getItemOrders(idItem) {
            totalBuy  = 0;
            totalSell = 0;

            $scope.sellorders.total  = 0;
            $scope.sellorders.recent = 0;
            $scope.sellorders.items  = [];

            $scope.buyorders.total   = 0;
            $scope.buyorders.recent  = 0;
            $scope.buyorders.items   = [];

            // check time and restart
            $http.get(config.crest.base + 'time/', {})
            .then(function(response) {
                oneHourAgo = moment(response.data.time).subtract(1, 'hour');

                if ($scope.region != '-1') {
                    const idRegion = angular.copy($scope.region);
                    queryRegion(idItem, idRegion);
                } else {
                    const regions = $scope.regions.filter((val) => val.id > 0);
                    regions.forEach((value, key) => {
                        queryRegion(idItem, value.id);
                    });
                }
            });
        }

        function queryRegion(idItem, idRegion) {
            //sell
            marketLookupFact
            .queryItem(idRegion, 'sell', idItem)
            .then(function(responseSell) {
                angular.forEach(responseSell, function(cValue, cKey) {
                    totalSell += cValue.volume;
                });
                $scope.sellorders.total = totalSell;

                responseSell.forEach((item) => {
                    $scope.sellorders.items.push(item);
                });
                $scope.sellorders.items = $filter('orderBy')($scope.sellorders.items, 'price');
                if ($scope.sellorders.items.length && $scope.region != '-1') {
                    $scope.spread.minSell = $scope.sellorders.items[0].price;
                } else {
                    $scope.spread.minSell = 1;
                }
                
                // recent orders
                angular.forEach($scope.sellorders.items, function(cValue, cKey) {
                    if (moment(cValue.issued).isAfter(oneHourAgo)) {
                        $scope.sellorders.recent++;
                    }
                });
            })
            .catch(function(error) {
                console.error(error.stack);
            });

            //buy
            marketLookupFact
            .queryItem(idRegion, 'buy', idItem)
            .then(function(responseBuy) {
                angular.forEach(responseBuy, function(cValue, cKey) {
                    totalBuy += cValue.volume;
                });
                $scope.buyorders.total = totalBuy;

                responseBuy.forEach((item) => {
                    $scope.buyorders.items.push(item);
                });
                $scope.buyorders.items = $filter('orderBy')($scope.buyorders.items, '-price');

                if ($scope.buyorders.items.length && $scope.region != '-1') {
                    $scope.spread.maxBuy = $scope.buyorders.items[0].price;
                } else {
                    $scope.spread.maxBuy = 1;
                }

                //recent orders
                angular.forEach($scope.buyorders.items, function(cValue, cKey) {
                    if (moment(cValue.issued).isAfter(oneHourAgo)) {
                        $scope.buyorders.recent++;
                    }
                });
            })
            .catch(function(error) {
                console.error(error.stack);
            });
        }

        // update data every 5 mins automatically
        function update(init) {
            let updateTimer  = function() {
                $scope.time -= 1;
            };

            if (init) {
                $scope.time = frequency / interval;
                getItemOrders($scope.item.id);
                countdown = $interval(updateTimer, interval);
            }
            updateData = $timeout(function() {
                $interval.cancel(countdown);
                $scope.time = frequency / interval;
                getItemOrders($scope.item.id);
                countdown = $interval(updateTimer, interval);
                update();
            }, frequency);
        }
	}
]);