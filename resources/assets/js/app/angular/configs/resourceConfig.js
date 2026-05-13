
function resourceConfig($resourceProvider, $httpProvider, HEADERS) {
    $httpProvider.defaults.headers.common = HEADERS
    
    $resourceProvider.defaults.stripTrailingSlashes = true;
    $resourceProvider.defaults.actions.create = {
        method: 'POST'
    };
    $resourceProvider.defaults.actions.destroy = {
        method: 'DELETE'
    };
    $resourceProvider.defaults.actions.update = {
        method: 'PUT'
    };
}

resourceConfig.$inject = ["$resourceProvider", "$httpProvider", "HEADERS"]

angular.module('AnewApp').config(resourceConfig);
