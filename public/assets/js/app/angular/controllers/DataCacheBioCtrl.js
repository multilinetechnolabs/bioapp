function DataCacheBioCtrl($scope, $filter, Client, ScanSession, ScanSessionPair) {
    _this = this
    _this.scan_type = 'body_scan'
    _this.searchText = ''
    _this.loaded = false
    
    ScanSessionPair.query({ scan_type: this.scan_type }, function(scan_session_pairs) {
        _this.displayed_pairs = scan_session_pairs;
        _this.loaded = true
    });

    Client.query({}, function(clients){
        _this.clients = clients
    })

    this.refreshScanSession = function() {
        _this = this
        _this.loaded = false

        ScanSessionPair.query({ scan_type: this.scan_type }, function(scan_session_pairs) {
            _this.displayed_pairs = scan_session_pairs;
            _this.loaded = true
        });
    }
}
DataCacheBioCtrl.$inject = ['$scope', '$filter', 'Client', 'ScanSession', 'ScanSessionPair'];

angular.module('AnewApp').controller('DataCacheBioCtrl', DataCacheBioCtrl);
