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
        
        $scope.item = {};
        $scope.region = {};
        $scope.buyorders = {
            items: [],
            total: ''
        };
        $scope.sellorders = {
            items: [],
            total: ''
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

        function getItemOrders(id) {
            //sell
            marketLookupFact
            .queryItem($scope.region, 'sell', id)
            .then(function(responseSell) {
                let totalSell = 0;
                angular.forEach(responseSell, function(cValue, cKey) {
                    totalSell += cValue.volume;
                });
                $scope.sellorders.total = totalSell;
                $scope.sellorders.items = $filter('orderBy')(responseSell, 'price');
                if ($scope.sellorders.items.length) $scope.spread.minSell = $scope.sellorders.items[0].price;
                
                // recent orders
                $http.get(config.crest.base + 'time/', {})
                .then(function(response) {
                    let time = response.data.time,
                        oneHourAgo = moment(time).subtract(1, 'hour');

                    $scope.sellorders.recent = 0;
                    angular.forEach($scope.sellorders.items, function(cValue, cKey) {
                        if (moment(cValue.issued).isAfter(oneHourAgo)) {
                            $scope.sellorders.recent++;
                        }
                    });
                });
            })
            .catch(function(error) {
                console.error(error.stack);
            });

            //buy
            marketLookupFact
            .queryItem($scope.region, 'buy', id)
            .then(function(responseBuy) {
                let totalBuy = 0;
                angular.forEach(responseBuy, function(cValue, cKey) {
                    totalBuy += cValue.volume;
                });
                $scope.buyorders.total = totalBuy;
                $scope.buyorders.items = $filter('orderBy')(responseBuy, '-price');
                if ($scope.buyorders.items.length) $scope.spread.maxBuy = $scope.buyorders.items[0].price;
                
                // recent
                $http.get(config.crest.base + 'time/', {})
                .then(function(response) {
                    let time = response.data.time,
                        oneHourAgo = moment(time).subtract(1, 'hour');

                    //recent orders
                    $scope.buyorders.recent = 0;
                    angular.forEach($scope.buyorders.items, function(cValue, cKey) {
                        if (moment(cValue.issued).isAfter(oneHourAgo)) {
                            $scope.buyorders.recent++;
                        }
                    });
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