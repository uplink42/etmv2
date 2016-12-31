app.factory('regionListFact', [
    'crestFact', 
    'config',
    function(crestFact, config) {
        var regionListFact = {};

        regionListFact.getAll = function () {
            var request =  'regions/';

            return crestFact.loadAllData(request)
        };

        return regionListFact;
    }
]);