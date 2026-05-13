function ModelLabelFactory($resource, API_PREFIX) {
    return $resource(API_PREFIX + '/model_labels/:id', { id: '@id' }, {});
}
ModelLabelFactory.$inject = ['$resource', 'API_PREFIX'];

angular.module('AnewApp').factory('ModelLabel', ModelLabelFactory);
