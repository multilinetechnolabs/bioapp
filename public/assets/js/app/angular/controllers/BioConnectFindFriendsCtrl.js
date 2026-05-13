function BioConnectFindFriendsCtrl($scope, $filter, User) {
    _this = this
    _this.usersLoaded = false
    _this.filterUserIds = []

    User.prototype.Me.query(function(user) {
        _this.user = user

        angular.forEach(user.friendIds, function(value, key) {
            _this.filterUserIds.push(value)
        })

        angular.forEach(user.requestedFriendIds, function(value, key) {
            _this.filterUserIds.push(value)
        })
    })

    User.prototype.Me.friends_available(function(users) {
        _this.users = users
        _this.usersLoaded = true
    })

    this.addFriend = function(user) {
        _this = this;

        var confirmDialog = confirm("Are you sure you wish to invite this person?")
        if (confirmDialog == true) {
            _this.usersLoaded = false

            User.prototype.Me.add_friend({ friend_id: user.id }, function(friend) { 
                _this.filterUserIds.push(user.id);

                index = _this.users.indexOf(user);
                _this.users.splice(index, 1);

                _this.usersLoaded = true
            })
        }
    }
}
BioConnectFindFriendsCtrl.$inject = ['$scope', '$filter', 'User'];

angular.module('AnewApp').controller('BioConnectFindFriendsCtrl', BioConnectFindFriendsCtrl);
