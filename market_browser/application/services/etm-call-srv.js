app.factory('etmFact', function($http) {

    var etmFact = {};

    etmFact.request = function(request) {
        var promise = $http.get(request)
            .then(function (response) {
                if (response) {
                    return response.data;
                }
            });

        return promise;
    }

    //pagination later

    return etmFact;
});