me.controller('homeCtrl', [
    '$state',
    function ($state) {
        "use strict";

        let params = window.location.search.split('&'),
            tradefinder;
        for (let i of params) {
            if (i === 'tf=1') {
                tradefinder = true;
            }
        }

        if (tradefinder) {
            $state.transitionTo('tradefinder');
        } else {
            $state.transitionTo('marketexplorer');
        } 
	}
]);