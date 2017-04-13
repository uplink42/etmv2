var crestFact = app.factory('crestFact', ['$q', '$http', 'config',
    function($q, $http, config) {
        var loadData = function(url, aggregateData) {
            var deferred = $q.defer();

            function loadAll() {
                $http.get(url).then(function(d) {
                    // grab all pagination results
                    angular.forEach(d.data.items, function(cValue, cKey) {
                        aggregateData.value.push(cValue);
                    });
                    if (!angular.isUndefined(d.data.next)) {
                        url = d.data.next.href;
                        loadAll();
                    } else {
                        deferred.resolve(aggregateData.value);
                    }
                })
                .catch(function(error) {
                    alert('Unable to retrieve market data from CREST. The server might be down.');
                    throw new Error("Something went wrong!");
                });
            }
            loadAll();
            return deferred.promise;
        };
        return {
            // public, load all data serially 
            loadAllData: function(request, full) {
                var aggregateData = {
                    isLoaded: false,
                    value: []
                };
                var deferred = $q.defer();
                
                if (full) {
                    request = request;
                } else {
                    request = config.crest.base + request;
                }
                
                loadData(request, aggregateData).then(function(d) {
                    deferred.resolve(aggregateData.value);
                });
                return deferred.promise;
            },
            getData: function() {
                return aggregateData.value;
            },
            isDataLoaded: function() {
                return aggregateData.isLoaded;
            }
        };
    }
]);