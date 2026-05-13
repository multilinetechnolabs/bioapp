function UserFactory($resource, API_PREFIX) {
    var User;
    User = $resource(API_PREFIX + '/users/:id', { id: '@id' }, {});
    User.prototype.Me = $resource(API_PREFIX + '/users/me', {}, 
    {
        query: {
            method: 'GET',
            isArray: false
        },
        friends: {
            method: 'GET',
            url: API_PREFIX + '/users/me/friends',
            isArray: true
        },
        friends_available: {
            method: 'GET',
            url: API_PREFIX + '/users/me/friends/available',
            isArray: true
        },
        add_friend: {
            method: 'POST',
            url: API_PREFIX + '/users/me/friends'
        },
        delete_friend: {
            method: 'DELETE',
            url: API_PREFIX + '/users/me/friends/:id',
            params: { id: '@id' }
        },
        friend_requests: {
            method: 'GET',
            url: API_PREFIX + '/users/me/friend_requests',
            isArray: true
        },
        accept_friend_request: {
            method: 'PUT',
            url: API_PREFIX + '/users/me/friend_requests/:id',
            params: { id: '@id' }
        }, 
        reject_friend_request: {
            method: 'DELETE',
            url: API_PREFIX + '/users/me/friend_requests/:id',
            params: { id: '@id' }
        }, 
        bookmarks: {
            method: 'GET',
            url: API_PREFIX + '/users/me/bookmarks',
            isArray: true
        }, 
        create_bookmark: {
            method: 'POST',
            url: API_PREFIX + '/users/me/bookmarks'
        }, 
        update_bookmark: {
            method: 'PUT',
            url: API_PREFIX + '/users/me/bookmarks/:id',
            params: { id: '@id' }
        }, 
        delete_bookmark: {
            method: 'DELETE',
            url: API_PREFIX + '/users/me/bookmarks/:id',
            params: { id: '@id' }
        }
    });
    User.prototype.Friend = $resource(API_PREFIX + '/users/:user_id/friends/:id', 
        { user_id: '@user_id', id: '@id' }, {});
    User.prototype.Bookmark = $resource(API_PREFIX + '/users/:user_id/bookmarks',
        { user_id: '@user_id', id: '@id' }, {});

    return User;
}
UserFactory.$inject = ['$resource', 'API_PREFIX'];

angular.module('AnewApp').factory('User', UserFactory);
