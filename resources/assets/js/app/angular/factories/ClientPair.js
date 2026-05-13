function ClientPairFactory($resource, API_PREFIX) {
    return $resource(API_PREFIX + '/clients/:client_id/client_pairs/:id', { client_id: '@client_id', id: '@id' }, {});
}
ClientPairFactory.$inject = ['$resource', 'API_PREFIX'];

angular.module('AnewApp').factory('ClientPair', ClientPairFactory);
