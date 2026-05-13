function DiscussionFactory($resource, API_PREFIX) {
    var Discussion;

    Discussion = $resource(API_PREFIX + '/discussions/:id', { id: '@id' }, {});

    Discussion.prototype.Comment = $resource(API_PREFIX + '/discussions/:discussion_id/comments/:id', 
    { discussion_id: '@discussion_id', id: '@id' }, {});

    return Discussion;
}
DiscussionFactory.$inject = ['$resource', 'API_PREFIX'];

angular.module('AnewApp').factory('Discussion', DiscussionFactory);
