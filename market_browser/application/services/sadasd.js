/*app.factory('crestFact', function($http) {

    var crestFact = {};

    crestFact.request = function(request) {
        var promise = $http.get(request)
            .then(function (response) {
                if (response.status == 200) {
                    if (response.pageCount <= 1) {
                        return response.data;
                    } else {
                        //pagination results
                        var pageCount = response.data.pageCount;
                        var results = [];
                        results = response.data.items;

                        for (var i = 2; i <= pageCount; i++) {
                            var next = $http.get(request + '?page=' + i)
                            .then(function (nextResponse) {
                                if (response.status == 200) {
                                    angular.forEach(nextResponse.data.items, function(cValue, cKey) {
                                        results.push(cValue);
                                    });
                                }
                            });
                        }
                        return results;
                    }
                    
                }
            });

        return promise;
    }

    //pagination later

    return crestFact;
});*/