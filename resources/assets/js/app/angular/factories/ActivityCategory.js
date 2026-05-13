function ActivityCategoryFactory($resource, API_PREFIX) {
    return $resource(API_PREFIX + '/activity_categories/:id', { id: '@id' }, {});
}
ActivityCategoryFactory.$inject = ['$resource', 'API_PREFIX'];

angular.module('AnewApp').factory('ActivityCategory', ActivityCategoryFactory);
