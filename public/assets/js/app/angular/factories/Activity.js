function ActivityFactory($resource, API_PREFIX) {
    return $resource(API_PREFIX + '/activities/:id', { id: '@id' }, {});
}
ActivityFactory.$inject = ['$resource', 'API_PREFIX'];

angular.module('AnewApp').factory('Activity', ActivityFactory);
