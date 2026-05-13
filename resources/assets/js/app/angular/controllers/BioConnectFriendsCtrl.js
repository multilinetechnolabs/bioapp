function BioConnectFriendsCtrl($scope, $timeout, $window, User) {
    var _this = this;

    _this.user_friends = [];
    _this.userFriendsLoaded = false;
    _this.userFriendsLoading = false;
    _this.userFriendsLoadingMore = false;
    _this.userFriendsHasMore = true;
    _this.userFriendsPage = 1;
    _this.userFriendsPerPage = 12;

    function isNearBottom() {
        var documentElement = $window.document.documentElement;
        var scrollTop = $window.pageYOffset || documentElement.scrollTop || 0;
        var viewportBottom = scrollTop + $window.innerHeight;
        var documentHeight = Math.max(documentElement.scrollHeight, $window.document.body.scrollHeight);

        return viewportBottom >= documentHeight - 280;
    }

    function maybeLoadMore() {
        if (_this.userFriendsHasMore && !_this.userFriendsLoading && isNearBottom()) {
            loadFriends(false);
        }
    }

    function loadFriends(reset) {
        var page = reset ? 1 : _this.userFriendsPage;

        if (_this.userFriendsLoading || (!_this.userFriendsHasMore && !reset)) {
            return;
        }

        _this.userFriendsLoading = true;
        _this.userFriendsLoadingMore = !reset;

        if (reset) {
            _this.userFriendsLoaded = false;
            _this.userFriendsHasMore = true;
        }

        User.prototype.Me.friends({
            page: page,
            per_page: _this.userFriendsPerPage
        }, function(response) {
            var records = response.data || [];

            if (reset) {
                _this.user_friends = records;
            } else {
                Array.prototype.push.apply(_this.user_friends, records);
            }

            _this.userFriendsHasMore = !!(response.meta && response.meta.has_more);
            _this.userFriendsPage = _this.userFriendsHasMore && response.meta ? response.meta.next_page : page + 1;
            _this.userFriendsLoaded = true;
            _this.userFriendsLoading = false;
            _this.userFriendsLoadingMore = false;

            $timeout(maybeLoadMore, 0);
        }, function() {
            _this.userFriendsLoaded = true;
            _this.userFriendsLoading = false;
            _this.userFriendsLoadingMore = false;
        });
    }

    function handleScroll() {
        if (!_this.userFriendsHasMore || _this.userFriendsLoading) {
            return;
        }

        if (isNearBottom()) {
            $scope.$applyAsync(function() {
                loadFriends(false);
            });
        }
    }

    this.deleteFriend = function(record) {
        var confirmDialog = confirm("Are you sure you wish to unfriend this user?");

        if (confirmDialog === true) {
            User.prototype.Me.delete_friend({ id: record.id }, function() {
                var index = _this.user_friends.indexOf(record);

                if (index > -1) {
                    _this.user_friends.splice(index, 1);
                }

                $timeout(maybeLoadMore, 0);
            });
        }
    };

    angular.element($window).on('scroll', handleScroll);

    $scope.$on('$destroy', function() {
        angular.element($window).off('scroll', handleScroll);
    });

    loadFriends(true);
}

function BioConnectFriendRequestsCtrl($scope, $timeout, $window, User) {
    var _this = this;

    _this.friend_requests = [];
    _this.friendRequestsLoaded = false;
    _this.friendRequestsLoading = false;
    _this.friendRequestsLoadingMore = false;
    _this.friendRequestsHasMore = true;
    _this.friendRequestsPage = 1;
    _this.friendRequestsPerPage = 12;

    function isNearBottom() {
        var documentElement = $window.document.documentElement;
        var scrollTop = $window.pageYOffset || documentElement.scrollTop || 0;
        var viewportBottom = scrollTop + $window.innerHeight;
        var documentHeight = Math.max(documentElement.scrollHeight, $window.document.body.scrollHeight);

        return viewportBottom >= documentHeight - 280;
    }

    function maybeLoadMore() {
        if (_this.friendRequestsHasMore && !_this.friendRequestsLoading && isNearBottom()) {
            loadFriendRequests(false);
        }
    }

    function loadFriendRequests(reset) {
        var page = reset ? 1 : _this.friendRequestsPage;

        if (_this.friendRequestsLoading || (!_this.friendRequestsHasMore && !reset)) {
            return;
        }

        _this.friendRequestsLoading = true;
        _this.friendRequestsLoadingMore = !reset;

        if (reset) {
            _this.friendRequestsLoaded = false;
            _this.friendRequestsHasMore = true;
        }

        User.prototype.Me.friend_requests({
            page: page,
            per_page: _this.friendRequestsPerPage
        }, function(response) {
            var records = response.data || [];

            if (reset) {
                _this.friend_requests = records;
            } else {
                Array.prototype.push.apply(_this.friend_requests, records);
            }

            _this.friendRequestsHasMore = !!(response.meta && response.meta.has_more);
            _this.friendRequestsPage = _this.friendRequestsHasMore && response.meta ? response.meta.next_page : page + 1;
            _this.friendRequestsLoaded = true;
            _this.friendRequestsLoading = false;
            _this.friendRequestsLoadingMore = false;

            $timeout(maybeLoadMore, 0);
        }, function() {
            _this.friendRequestsLoaded = true;
            _this.friendRequestsLoading = false;
            _this.friendRequestsLoadingMore = false;
        });
    }

    function handleScroll() {
        if (!_this.friendRequestsHasMore || _this.friendRequestsLoading) {
            return;
        }

        if (isNearBottom()) {
            $scope.$applyAsync(function() {
                loadFriendRequests(false);
            });
        }
    }

    this.acceptFriendRequest = function(record) {
        var confirmDialog = confirm("Are you sure you wish to accept this friend request?");

        if (confirmDialog === true) {
            User.prototype.Me.accept_friend_request({ id: record.id }, function() {
                var index = _this.friend_requests.indexOf(record);

                if (index > -1) {
                    _this.friend_requests.splice(index, 1);
                }

                $timeout(maybeLoadMore, 0);
            });
        }
    };

    this.rejectFriendRequest = function(record) {
        var confirmDialog = confirm("Are you sure you wish to reject this friend request?");

        if (confirmDialog === true) {
            User.prototype.Me.reject_friend_request({ id: record.id }, function() {
                var index = _this.friend_requests.indexOf(record);

                if (index > -1) {
                    _this.friend_requests.splice(index, 1);
                }

                $timeout(maybeLoadMore, 0);
            });
        }
    };

    angular.element($window).on('scroll', handleScroll);

    $scope.$on('$destroy', function() {
        angular.element($window).off('scroll', handleScroll);
    });

    loadFriendRequests(true);
}

BioConnectFriendsCtrl.$inject = ['$scope', '$timeout', '$window', 'User'];
BioConnectFriendRequestsCtrl.$inject = ['$scope', '$timeout', '$window', 'User'];

angular.module('AnewApp').controller('BioConnectFriendsCtrl', BioConnectFriendsCtrl);
angular.module('AnewApp').controller('BioConnectFriendRequestsCtrl', BioConnectFriendRequestsCtrl);
