function BioConnectFriendsCtrl($scope, $filter, User) {
    _this = this
    _this.userFriendsLoaded = false
    _this.friendRequestsLoaded = false

    User.prototype.Me.friends(function(user_friends) {
        _this.user_friends = user_friends
        _this.userFriendsLoaded = true
    })

    User.prototype.Me.friend_requests(function(friend_requests) {
        _this.friend_requests = friend_requests
        _this.friendRequestsLoaded = true
    })

    this.acceptFriendRequest = function(record) {
        var confirmDialog = confirm("Are you sure you wish to accept this friend request?");
        if (confirmDialog == true) {
            _this.friendRequestsLoaded = false
            User.prototype.Me.accept_friend_request({ id: record.id }, function() {
                index = _this.friend_requests.indexOf(record)
                _this.friend_requests.splice(index, 1)
                _this.friendRequestsLoaded = true
            })
        }
    }

    this.deleteFriend = function(record) {
        var confirmDialog = confirm("Are you sure you wish to unfriend this user?");
        if (confirmDialog == true) {
            _this.userFriendsLoaded = false
            User.prototype.Me.delete_friend({ id: record.id }, function() {
                index = _this.user_friends.indexOf(record)
                _this.user_friends.splice(index, 1)
                _this.userFriendsLoaded = true
            })
        }
    }

    this.rejectFriendRequest = function(record) {
        var confirmDialog = confirm("Are you sure you wish to reject this friend request?");
        if (confirmDialog == true) {
            _this.friendRequestsLoaded = false
            User.prototype.Me.reject_friend_request({ id: record.id }, function() {
                index = _this.friend_requests.indexOf(record)
                _this.friend_requests.splice(index, 1)
                _this.friendRequestsLoaded = true
            })
        }
    }
}
BioConnectFriendsCtrl.$inject = ['$scope', '$filter', 'User'];

angular.module('AnewApp').controller('BioConnectFriendsCtrl', BioConnectFriendsCtrl);
