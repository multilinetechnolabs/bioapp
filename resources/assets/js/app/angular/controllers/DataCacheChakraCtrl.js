function DataCacheChakraCtrl($scope, $filter, Client, ScanSession, ScanSessionPair) {
    _this = this
    _this.scan_type = 'chakra_scan'
    _this.searchText = ''
    _this.loaded = false
    _this.displayed_pairs = []
    _this.errorMessage = ''

    this.loadScanSessionPairs = function() {
        _this.loaded = false
        _this.errorMessage = ''

        ScanSessionPair.query({ scan_type: _this.scan_type }, function(scan_session_pairs) {
            _this.displayed_pairs = scan_session_pairs || []
            _this.loaded = true
        }, function() {
            _this.displayed_pairs = []
            _this.errorMessage = 'Unable to load the Chakra Data Cache right now. Please refresh and try again.'
            _this.loaded = true
        })
    }

    this.loadScanSessionPairs()

    Client.query({}, function(clients){
        _this.clients = clients
    })

    this.refreshScanSession = function() {
        this.loadScanSessionPairs()
    }
}
DataCacheChakraCtrl.$inject = ['$scope', '$filter', 'Client', 'ScanSession', 'ScanSessionPair'];

angular.module('AnewApp').controller('DataCacheChakraCtrl', DataCacheChakraCtrl);
