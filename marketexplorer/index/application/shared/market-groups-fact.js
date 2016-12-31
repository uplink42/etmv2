app.factory('marketGroupsFact', [
    'crestFact', 
    'config',
    function(crestFact, config) {

        var marketGroupsFact = {};

        marketGroupsFact.getAll = function () {
            var request = config.crest.base + 'market/groups/';

            return new crestFact.loadAllData(request);
        };

        marketGroupsFact.getOne = function (id) {
            var request = 'market/groups/' + id + '/';

            return crestFact.loadAllData(request);
        };

        return marketGroupsFact;
    }
]);