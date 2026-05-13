function LogoFactory($resource, API_PREFIX) {
    return $resource(API_PREFIX + '/logos/:id', { id: '@id' }, {});
}
LogoFactory.$inject = ['$resource', 'API_PREFIX'];

angular.module('AnewApp').factory('Logo', LogoFactory);
