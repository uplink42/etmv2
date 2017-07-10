me.directive('marketGroups', [
    'config',
    'marketGroupsFact',
    'marketTypesFact',
    function(config, marketGroupsFact, marketTypesFact) {
        "use strict";
        
        return {
            templateUrl: config.dist + '/partials/me/marketgroups/market-groups-view.html',
            restrict: 'E',
            scope: {
                region: '=',
                item: '=',
                name: '='
            },
            controller: ['$scope', function($scope) {
                let allGroups,
                    allTypes       = [];
                $scope.subcatItems = {};
                $scope.item        = {};
                $scope.subcatsView = config.dist + '/partials/me/marketgroups/cats/market-sub-cats-view.html';

                marketGroupsFact
                .getAll()
                .then(function(response) {
                    recursiveGrpSorting(response);
                })
                .catch(function(error) {
                    console.error(error.stack);
                });

                //get all items by market type
                marketTypesFact
                .getAll()
                .then(function(response) {
                    angular.forEach(response, function(cValue, cKey) {
                        let key = cValue.marketGroup.id;
                        if (angular.isUndefined(allTypes[key])) {
                            allTypes[key] = [];
                        }
                        allTypes[key].push(cValue.type);
                    });
                    $scope.isLoadedItems = true;
                })
                .catch(function(error) {
                    console.error(error.stack);
                });


                function recursiveGrpSorting(result) {
                    allGroups = result;
                    $scope.groups = [];
                    for (let key in result) {
                        if (!result[key].parentGroup) {
                            if (result[key].id < 300000) {
                                $scope.groups.push(result[key]);
                            }
                        }
                    }
                }

                function populateCat(id) {
                    angular.forEach(allGroups, function(cValue, cKey) {
                        if (cValue.parentGroup) {
                            if (cValue.parentGroup.id == id) {
                                $scope.subcatItems[id].items.push(cValue);
                            }
                        }
                    });

                    if ($scope.subcatItems[id].items.length < 1) {
                        $scope.subcatItems[id] = {
                            items: []
                        };
                        angular.forEach(allTypes[id], function(cValue, cKey) {
                            $scope.subcatItems[id].items.push(cValue);
                            $scope.subcatItems[cValue.id] = {
                                final: true,
                                id: cValue.id,
                            };
                        });
                    } 
                }

                $scope.openSubCat = function(id) {
                    if (!$scope.subcatItems[id] || $scope.subcatItems[id].length < 1) {
                        $scope.subcatItems[id] = {
                            items: []
                        };
                        populateCat(id);
                    } else if (!$scope.subcatItems[id].final) {
                        $scope.subcatItems[id] = [];
                    } else {
                        $scope.item.id = $scope.subcatItems[id].id;
                        getItemName(id);
                    }
                };

                function getItemName(id) {
                    angular.forEach(allTypes, function(cValue, cKey) {
                        angular.forEach(cValue, function(iValue, iKey) {
                            if (iValue.id == id) {
                                $scope.item.name = iValue.name;
                            }
                        });
                    });
                }
            }],

            link: function() {

            }
        };
    }
]);