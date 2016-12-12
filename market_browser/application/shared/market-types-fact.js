app.factory('marketTypesFact', [
    'crestFact', 
    'config',
    function(crestFact, config) {

        var marketTypesFact = {};

        marketTypesFact.getAll = function () {
            var request = 'market/types/';

            return crestFact.loadAllData(request);
        };

        marketTypesFact.getOne = function (id) {
            var request = 'market/types/' + id + '/';

            return crestFact.loadAllData(request);
        };

        return marketTypesFact;
    }
]);