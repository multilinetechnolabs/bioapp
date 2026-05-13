function DataCacheClientShowBioCtrl($scope, $filter, $window, Client, Pair, ScanSession) {
    this.sortBy = { column: 'name' }
    this.scan_type = 'body_scan'
    this.loaded = false
    this.searchText = ''
    
    this.scan_session_id = $("#scanSessionId").data("value")
    if (this.scan_session_id != undefined || this.scan_session_id != '') { 
        _this = this
        
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
            $window.open('/scan_sessions/'+session.id+'/export', '_blank')
            _this.loaded = true
        }
    }
}
DataCacheClientShowBioCtrl.$inject = ['$scope', '$filter', '$window', 'Client', 'Pair', 'ScanSession'];

angular.module('AnewApp').controller('DataCacheClientShowBioCtrl', DataCacheClientShowBioCtrl);
