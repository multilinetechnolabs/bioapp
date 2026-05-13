function interpolateConfig($interpolateProvider) {
    $interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');
}
interpolateConfig.$inject = ['$interpolateProvider'];

angular.module('AnewApp').config(interpolateConfig);
