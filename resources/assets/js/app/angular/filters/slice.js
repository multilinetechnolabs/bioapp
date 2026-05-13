function sliceFilter() {
    return function(array, start, end) {
        return array.slice(start, end);
    };
}
sliceFilter.$inject = [];

angular.module('AnewApp').filter('slice', sliceFilter);
