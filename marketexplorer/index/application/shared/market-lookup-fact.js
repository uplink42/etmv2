app.factory('marketLookupFact', [
    'crestFact', 
    'config',
    function(crestFact, config) {

        var marketLookupFact = {};

        marketLookupFact.queryItem = function (regionId, orderType, itemId) {
            var request = config.crest.base + 'market/' + regionId + "/orders/" + orderType + 
            "/?type=" + config.crest.base + 'inventory/types/' + itemId + "/";

            return crestFact.loadAllData(request, true);
        };

        return marketLookupFact;
    }
]);