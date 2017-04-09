app.factory('marketHistoryFact', [
    'crestFact', 
    'config',
    function(crestFact, config) {

        var marketHistoryFact = {};

        marketHistoryFact.queryItem = function (regionId, itemId) {
            var request = config.crest.base + 'market/' + regionId + "/history/?type=" + 
            config.crest.base + 'inventory/types/' + itemId + "/";

            return crestFact.loadAllData(request, true);
        };

        return marketHistoryFact;
    }
]);