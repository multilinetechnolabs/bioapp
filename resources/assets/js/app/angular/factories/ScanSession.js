function ScanSessionFactory($resource, API_PREFIX) {
    var ScanSession;
    
    ScanSession = $resource(API_PREFIX + '/scan_sessions/:id', { id: '@id' }, { 
        active: {
            url: API_PREFIX + '/scan_sessions/active',
            method: 'GET',
            isArray: false
        },
        mail: {
            method: 'GET',
            url: API_PREFIX + '/scan_sessions/:id/mail',
            isArray: false
        }
    });
    
    ScanSession.prototype.Pair = $resource(API_PREFIX + '/scan_sessions/:scan_session_id/pairs/:id', 
        { scan_session_id: '@scan_session_id', id: '@id' }, {});
    
    ScanSession.prototype.ScanSessionPair = $resource(API_PREFIX + '/scan_sessions/:scan_session_id/scan_session_pairs/:id', 
    { scan_session_id: '@scan_session_id', id: '@id' }, {});

    return ScanSession;
}
ScanSessionFactory.$inject = ['$resource', 'API_PREFIX'];

angular.module('AnewApp').factory('ScanSession', ScanSessionFactory);
