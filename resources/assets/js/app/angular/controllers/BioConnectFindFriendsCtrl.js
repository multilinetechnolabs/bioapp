function BioConnectFindFriendsCtrl($scope, $timeout, $window, User) {
    var _this = this;
    var searchDebounce;
    var requestSequence = 0;

    _this.searchText = '';
    _this.users = [];
    _this.usersLoaded = false;
    _this.usersLoading = false;
    _this.usersLoadingMore = false;
    _this.usersHasMore = true;
    _this.usersPage = 1;
    _this.usersPerPage = 12;

    function isNearBottom() {
        var documentElement = $window.document.documentElement;
        var scrollTop = $window.pageYOffset || documentElement.scrollTop || 0;
        var viewportBottom = scrollTop + $window.innerHeight;
        var documentHeight = Math.max(documentElement.scrollHeight, $window.document.body.scrollHeight);

        return viewportBottom >= documentHeight - 280;
    }

    function maybeLoadMore() {
        if (_this.usersHasMore && !_this.usersLoading && isNearBottom()) {
            loadUsers(false);
        }
    }

    function currentSearchText() {
        return (_this.searchText || '').trim();
    }

    function loadUsers(reset) {
        var page = reset ? 1 : _this.usersPage;
        var requestId;

        if ((_this.usersLoading && !reset) || (!_this.usersHasMore && !reset)) {
            return;
        }

        _this.usersLoading = true;
        _this.usersLoadingMore = !reset;

        if (reset) {
            _this.users = [];
            _this.usersLoaded = false;
            _this.usersHasMore = true;
            _this.usersPage = 1;
        }

        requestId = ++requestSequence;

        User.prototype.Me.friends_available({
            page: page,
            per_page: _this.usersPerPage,
            search: currentSearchText()
        }, function(response) {
            if (requestId !== requestSequence) {
                return;
            }

            var records = response.data || [];

            if (reset) {
                _this.users = records;
            } else {
                Array.prototype.push.apply(_this.users, records);
            }

            _this.usersHasMore = !!(response.meta && response.meta.has_more);
            _this.usersPage = _this.usersHasMore && response.meta ? response.meta.next_page : page + 1;
            _this.usersLoaded = true;
            _this.usersLoading = false;
            _this.usersLoadingMore = false;

            $timeout(maybeLoadMore, 0);
        }, function() {
            if (requestId !== requestSequence) {
                return;
            }

            _this.usersLoaded = true;
            _this.usersLoading = false;
            _this.usersLoadingMore = false;
        });
    }

    function handleScroll() {
        if (!_this.usersHasMore || _this.usersLoading) {
            return;
        }

        if (isNearBottom()) {
            $scope.$applyAsync(function() {
                loadUsers(false);
            });
        }
    }

    this.addFriend = function(user) {
        var confirmDialog = confirm("Are you sure you wish to invite this person?");

        if (confirmDialog === true) {
            User.prototype.Me.add_friend({ friend_id: user.id }, function() {
                var index = _this.users.indexOf(user);

                if (index > -1) {
                    _this.users.splice(index, 1);
                }

                $timeout(maybeLoadMore, 0);
            });
        }
    };

    $scope.$watch(function() {
        return _this.searchText;
    }, function(newValue, oldValue) {
        if (newValue === oldValue) {
            return;
        }

        if (searchDebounce) {
            $timeout.cancel(searchDebounce);
        }

        searchDebounce = $timeout(function() {
            loadUsers(true);
        }, 250);
    });

    angular.element($window).on('scroll', handleScroll);

    $scope.$on('$destroy', function() {
        if (searchDebounce) {
            $timeout.cancel(searchDebounce);
        }

        angular.element($window).off('scroll', handleScroll);
    });

    loadUsers(true);
}

BioConnectFindFriendsCtrl.$inject = ['$scope', '$timeout', '$window', 'User'];

angular.module('AnewApp').controller('BioConnectFindFriendsCtrl', BioConnectFindFriendsCtrl);
