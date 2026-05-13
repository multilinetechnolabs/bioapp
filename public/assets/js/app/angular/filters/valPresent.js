function valPresentFilter() {
    return function(val) {
        if (!(angular.isDefined(val) || (val != null))) {
          return false;
        }
        if ((val != null) && angular.isDefined(val.length)) {
          return val.length > 0;
        }
        return val != null;
    };
}
valPresentFilter.$inject = [];

angular.module('AnewApp').filter('valPresent', valPresentFilter);