function PairFactory($resource, API_PREFIX) {
    return $resource(API_PREFIX + '/pairs/:id', { id: '@id' }, {});
}
PairFactory.$inject = ['$resource', 'API_PREFIX'];

angular.module('AnewApp').factory('Pair', PairFactory);
