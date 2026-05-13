capitalizeFilter = function() {
  return function(str) {
    if (str == null) {  return; }
    return str.charAt(0).toUpperCase() + str.slice(1);
  };
};
capitalizeFilter.$inject = [];

angular.module('AnewApp').filter('capitalize', capitalizeFilter);
