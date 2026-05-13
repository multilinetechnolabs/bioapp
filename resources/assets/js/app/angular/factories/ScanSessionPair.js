function ScanSessionPairFactory($resource, API_PREFIX) {
    return $resource(API_PREFIX + '/scan_session_pairs/:id', { id: '@id' }, {});
}
ScanSessionPairFactory.$inject = ['$resource', 'API_PREFIX'];

angular.module('AnewApp').factory('ScanSessionPair', ScanSessionPairFactory);
