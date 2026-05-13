function ClientFactory($resource, API_PREFIX) {
    var Client;
    Client = $resource(API_PREFIX + '/clients/:id', { id: '@id' }, {});
    Client.prototype.Pair = $resource(API_PREFIX + '/clients/:client_id/pairs/:id', 
        { client_id: '@client_id', id: '@id' }, {});
    Client.prototype.ClientPair = $resource(API_PREFIX + '/clients/:client_id/client_pairs/:id', 
        { client_id: '@client_id', id: '@id' }, {});
    Client.prototype.ConsentForm = $resource(API_PREFIX + '/clients/:client_id/consent_forms/:id', 
        { client_id: '@client_id', id: '@id' }, {});
    Client.prototype.MedicalNote = $resource(API_PREFIX + '/clients/:client_id/medical_notes/:id', 
        { client_id: '@client_id', id: '@id' }, {});
    Client.prototype.ScanSession = $resource(API_PREFIX + '/clients/:client_id/scan_sessions/:id', 
        { client_id: '@client_id', id: '@id' }, 
        {
            latest: {
                method: 'GET',
                url: API_PREFIX + '/clients/:client_id/scan_sessions/latest',
                isArray: false
            }
        }
    );
    return Client;
}
ClientFactory.$inject = ['$resource', "API_PREFIX"];

angular.module('AnewApp').factory('Client', ClientFactory);
