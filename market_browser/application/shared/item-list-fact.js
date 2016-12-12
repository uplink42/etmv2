app.factory('itemListFact', [
    'etmFact', 
    'config',
    function(etmFact, config) {

        var itemListFact = {};

        itemListFact.getLikeName = function (string) {
            var request = 'https://www.evetrademaster.com/v2/Stocklists/searchItems/?term=' + string;

            return crestFact.loadAllData(request);
        };

        return itemListFact;
    }
]);