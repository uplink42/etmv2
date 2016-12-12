app.directive('marketGroups', [
    'config',
    'marketGroupsFact',
    'marketTypesFact',
    function(config, marketGroupsFact, marketTypesFact) {

        return {
            templateUrl: 'application/partials/marketgroups/market-groups-view.html',
            restrict: 'E',
            scope: {
                region: '=',
                item: '=',
                name: '='
            },
            controller: function($scope) {

                var allGroups;
                var allTypes = [];
                $scope.subcatItems = {};
                $scope.item = {};
                $scope.subcatsView = 'application/partials/marketgroups/cats/market-sub-cats-view.html';

                marketGroupsFact
                .getAll()
                .then(function(response) {
                    //console.log(response);
                    recursiveGrpSorting(response);
                })
                .catch(function(error) {
                    console.error(error.stack);
                });

                //get all items by market type
                marketTypesFact
                .getAll()
                .then(function(response) {
                    //console.log(response);
                    angular.forEach(response, function(cValue, cKey) {
                        var key = cValue.marketGroup.id;
                        //allTypes[key] = [];
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
                    for (var key in result) {
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

                    if ($scope.subcatItems[id].items.length == 0) {
                        $scope.subcatItems[id] = {
                            items: []
                        };

                        console.log(allTypes[id]);
                        angular.forEach(allTypes[id], function(cValue, cKey) {
                            $scope.subcatItems[id].items.push(cValue);
                            $scope.subcatItems[cValue.id] = {
                                final: true,
                                id: cValue.id,
                            }
                        });
                    } 
                }


                $scope.openSubCat = function(id) {
                    console.log(id);
                    if (!$scope.subcatItems[id] || $scope.subcatItems[id].length == 0) {
                        $scope.subcatItems[id] = {
                            items: []
                        };

                        populateCat(id);
                    } else if (!$scope.subcatItems[id].final) {
                        $scope.subcatItems[id] = [];
                    } else {
                        $scope.isSelected = {};
                        $scope.isSelected[id] = true;
                        $scope.item.id = $scope.subcatItems[id].id;
                        getItemName(id);
                    }
                }


                function getItemName(id) {
                    angular.forEach(allTypes, function(cValue, cKey) {
                        angular.forEach(cValue, function(iValue, iKey) {
                            if (iValue.id == id) {
                                console.log(iValue.name);
                                $scope.item.name = iValue.name;
                            }
                        })
                    });
                }
            },

            link: function() {

            }
        };
    }
]);