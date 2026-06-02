function DataCacheClientShowChakraCtrl($scope, $filter, $timeout, $window, Client, Pair, ScanSession) {
    var _this = this

    this.sortBy = { column: 'name' }
    this.scan_type = 'chakra_scan'
    this.loaded = false
    this.searchText = ''
    this.searchTextPair = ''
    this.pairs = []
    this.filteredPairs = []
    this.pairDropdownOpen = false
    this.activePairIndex = -1

    this.rebuildFilteredPairs = function() {
        var searchTextPair = _this.searchTextPair || ''
        if (searchTextPair && $scope.pair && $scope.pair.name === searchTextPair) {
            searchTextPair = ''
        }

        var sortedPairs = $filter('orderBy')(_this.pairs || [], 'name')
        var searchedPairs = $filter('filter')(sortedPairs, { name: searchTextPair })
        var selectablePairs = $filter('filter')(searchedPairs, function(pair) {
            return pair._selectable
        })

        _this.filteredPairs = selectablePairs
        _this.activePairIndex = _this.filteredPairs.length ? 0 : -1
    }

    this.openPairDropdown = function() {
        _this.pairDropdownOpen = true
        _this.rebuildFilteredPairs()
    }

    this.closePairDropdown = function(delay) {
        if (delay) {
            $timeout(function() {
                _this.pairDropdownOpen = false
            }, 150)

            return
        }

        _this.pairDropdownOpen = false
    }

    this.closePairDropdownOnBlur = function() {
        $timeout(function() {
            var activeElement = document.activeElement
            var insideDropdown = activeElement && activeElement.closest && activeElement.closest('.modern-data-cache-combobox')

            if (!insideDropdown) {
                _this.closePairDropdown()
            }
        }, 150)
    }

    this.selectPair = function(pair) {
        $scope.pair = pair
        _this.searchTextPair = pair.name
        _this.closePairDropdown()
    }

    this.onPairSearchKeydown = function($event) {
        if (!_this.pairDropdownOpen && ($event.which === 13 || $event.which === 38 || $event.which === 40)) {
            _this.openPairDropdown()
        }

        if ($event.which === 40 && _this.filteredPairs.length) {
            $event.preventDefault()
            _this.activePairIndex = Math.min(_this.activePairIndex + 1, _this.filteredPairs.length - 1)
        } else if ($event.which === 38 && _this.filteredPairs.length) {
            $event.preventDefault()
            _this.activePairIndex = Math.max(_this.activePairIndex - 1, 0)
        } else if ($event.which === 13 && _this.activePairIndex >= 0) {
            $event.preventDefault()
            _this.selectPair(_this.filteredPairs[_this.activePairIndex])
        } else if ($event.which === 27) {
            _this.closePairDropdown()
        }
    }

    $scope.$watch(function() {
        return _this.searchTextPair
    }, function(newValue) {
        var pairWasSelected = $scope.pair && $scope.pair.name === newValue

        if (!pairWasSelected) {
            $scope.pair = null
        }

        _this.rebuildFilteredPairs()

        if (newValue && !pairWasSelected) {
            _this.pairDropdownOpen = true
        }
    })

    function closePairDropdownOnOutsideClick(event) {
        var clickedElement = event.target

        if (event.clientX != null && event.clientY != null) {
            clickedElement = document.elementFromPoint(event.clientX, event.clientY) || clickedElement
        }

        var clickedInsideDropdown = clickedElement && clickedElement.closest && clickedElement.closest('.modern-data-cache-combobox')

        if (!clickedInsideDropdown) {
            $scope.$applyAsync(function() {
                _this.closePairDropdown()
            })
        }
    }

    angular.element(document).on('mousedown.chakraPairDropdown', closePairDropdownOnOutsideClick)

    $scope.$on('$destroy', function() {
        angular.element(document).off('mousedown.chakraPairDropdown')
    })
    
    this.scan_session_id = $("#scanSessionId").data("value")
    if (this.scan_session_id != undefined || this.scan_session_id != '') { 
        ScanSession.get({ id: this.scan_session_id }, function(scan_session){
            _this.scan_session = scan_session
            _this.client = scan_session.client
            _this.scan_type = scan_session.scan_type

            Pair.query({ scan_type: _this.scan_type }, function(pairs){
                _this.pairs = pairs
        
                if (_this.pairs.length > 0) {
                    angular.forEach(_this.pairs, function(pair) {
                        if (!scan_session.pairIds.includes(pair.id)) { pair._selectable = true }
                    });
                }

                _this.rebuildFilteredPairs()
            })
        })

        ScanSession.prototype.Pair.query({ scan_session_id: this.scan_session_id }, function(pairs){
            _this.displayed_pairs = pairs

            _this.loaded = true
        })
    } else {
        _this.loaded = true
    }

    this.addPair = function(pair) {
        _this = this;
        var confirmDialog = confirm("Are you sure you wish to add this pair?");
        if (confirmDialog == true) {
            _this.loaded = false
            scan_session_pair = new ScanSession.prototype.ScanSessionPair({ scan_session_id: _this.scan_session.id, pair_id: pair.id });
            scan_session_pair.$save(function(_scan_session_pair){
                $scope.pair = null
                _this.searchTextPair = ''
                _this.scan_session = _scan_session_pair.scanSession
                
                _this.displayed_pairs.push(_scan_session_pair.pair)

                _this.reloadPairs()
                _this.loaded = true
            })
        }
    }

    this.removePair = function(pair) {
        _this = this;
        var confirmDialog = confirm("Are you sure you wish to remove this pair?");
        if (confirmDialog == true) {
            _this.loaded = false
            ScanSession.prototype.ScanSessionPair.delete({ scan_session_id: this.scan_session.id, id: pair.id}, function() {
                index = _this.displayed_pairs.indexOf(pair)
                _this.displayed_pairs.splice(index, 1)

                index = _this.scan_session.pairIds.indexOf(pair.id)
                _this.scan_session.pairIds.splice(index, 1)

                _this.reloadPairs()
                _this.loaded = true
            })
        }
    }

    this.toggleSortBy = function(column) {
        this.sortBy.column = column
    }

    this.refreshData = function() {
        _this = this
        _this.loaded = false

        ScanSession.get({ id: this.scan_session.id }, function(scan_session){
            _this.scan_session = scan_session
            _this.client = scan_session.client
            _this.scan_type = scan_session.scan_type
        })

        ScanSession.prototype.Pair.query({ scan_session_id: _this.scan_session_id }, function(pairs){
            _this.displayed_pairs = pairs

            _this.loaded = true
        })

        _this.reloadPairs()
    }

    this.reloadPairs = function() {
        _this = this

        Pair.query({ scan_type: this.scan_type }, function(pairs){
            _this.pairs = pairs

            if (_this.pairs.length > 0) {
                angular.forEach(_this.pairs, function(pair) {
                    if (!_this.scan_session.pairIds.includes(pair.id)) { pair._selectable = true }
                });
            }

            _this.rebuildFilteredPairs()
        })
    }

    this.markDoneScanSession = function(session) {
        scan_session = { client_id: session.client_id, id: session.id }
        scan_session.date_ended = $filter('date')(new Date(), 'yyyy-MM-dd')
        var confirmDialog = confirm("Are you sure you wish to mark as done this scan session?")
        if (confirmDialog == true) {
            _this.loaded = false
            Client.prototype.ScanSession.update(scan_session, function(_session){
                session.date_ended = scan_session.date_ended
                _this.loaded = true
            })
        }
    }

    this.markUndoneScanSession = function(session) {
        scan_session = { client_id: session.client_id, id: session.id, date_ended: null }
        var confirmDialog = confirm("Are you sure you wish to mark as undone this scan session?")
        if (confirmDialog == true) {
            _this.loaded = false
            Client.prototype.ScanSession.update(scan_session, function(_session){
                session.date_ended = null
                _this.loaded = true
            })
        }
    }

    this.emailScanSession = function(session) {
        var confirmDialog = confirm("Are you sure you wish to email this scan session?")
        if (confirmDialog == true) {
            _this.loaded = false
            ScanSession.mail({ id: session.id }, function(_session){
                _this.loaded = true
                alert('Email sent successfully.')
            }, function(response) {
                _this.loaded = true
                var message = 'Unable to email the client right now.'
                if (response && response.data && response.data.error && response.data.error.message) {
                    message = response.data.error.message
                }
                alert(message)
            })
        }
    }

    this.printScanSession = function(session) {
        var confirmDialog = confirm("Are you sure you wish to print this scan session?")
        if (confirmDialog == true) {
            _this.loaded = false
            $window.open('/scan_sessions/'+session.id+'/print', '_blank')
            _this.loaded = true
        }
    }
}
DataCacheClientShowChakraCtrl.$inject = ['$scope', '$filter', '$timeout', '$window', 'Client', 'Pair', 'ScanSession'];

angular.module('AnewApp').controller('DataCacheClientShowChakraCtrl', DataCacheClientShowChakraCtrl);
