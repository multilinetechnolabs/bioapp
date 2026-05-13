function DataCacheClientAddPairsCtrl($scope, $filter, $window, Pair, ScanSession) {
    this.pairs = []
    this.sortBy = { column: 'name' }
    this.searchText = ''
    this.loaded = false
    
    this.loadScanSession = function(scan_session_id) {
        _this = this
        _this.loaded = false

        ScanSession.get({ id: scan_session_id }, function(scan_session) {
            if (scan_session.date_ended == null) {
                _this.scan_session = scan_session
                _this.scan_type = scan_session.scan_type
            
                Pair.query({ scan_type: scan_session.scan_type }, function(pairs) {
                    _this.pairs = pairs
        
                    if (_this.scan_session != undefined) {
                        angular.forEach(pairs, function(pair) {
                            if (_this.scan_session.pairIds.includes(pair.id)) { pair._delete = true }
                        });
                    }  

                    _this.loaded = true
                })

                
            } else {
                if (scan_session.scan_type == 'body_scan') { scan_type = 'bio' } else { scan_type = 'chakra'}
                $window.location.href= '/data_cache/clients/' + scan_session.client_id + '/' + scan_type + '?ssid=' + scan_session.id
            }
        })
    }

    this.scan_session_id = $("#scanSessionId").data("value")
    if (this.scan_session_id != undefined) { this.loadScanSession(this.scan_session_id) }

    this.addPair = function(pair) {
        _this = this

        var confirmDialog = confirm("Are you sure you wish to add this pair?");
        if (confirmDialog == true) {
            _this.loaded = false
            scan_session_pair = new ScanSession.prototype.ScanSessionPair({ scan_session_id: _this.scan_session.id, pair_id: pair.id });
            scan_session_pair.$save(function(){
                pair._delete = true
                _this.loaded = true
            })
        }
    }

    this.removePair = function(pair) {
        _this = this

        var confirmDialog = confirm("Are you sure you wish to remove this pair?");
        if (confirmDialog == true) {
            _this.loaded = false
            ScanSession.prototype.ScanSessionPair.delete({ scan_session_id: _this.scan_session.id, id: pair.id}, function() {
                pair._delete = false
                _this.loaded = true
            })
        }
    }

    this.toggleSortBy = function(column) {
        this.sortBy.column = column
    }

    this.refresh = function() {
        this.loadScanSession(this.scan_session_id)
    }

}
DataCacheClientAddPairsCtrl.$inject = ['$scope', '$filter', '$window', 'Pair', 'ScanSession'];

angular.module('AnewApp').controller('DataCacheClientAddPairsCtrl', DataCacheClientAddPairsCtrl);
