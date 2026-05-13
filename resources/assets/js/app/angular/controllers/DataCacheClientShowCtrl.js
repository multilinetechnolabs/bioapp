function DataCacheClientShowCtrl($scope, $filter, Client, ClientPair) {
    this.sortBy = { column: 'name' }
    this.medical_note = { date_noted: new Date() }
    this.scan_session = { date_started: new Date(), scan_type: null, cost: 0 }
    this.loaded = false

    if ($("#clientId").data("value") != undefined || $("#clientId").data("value") != '') {
        _this = this

        Client.get({ id: $("#clientId").data("value") }, function(client) {
            _this.client = client
            _this.scan_session.cost = client.session_cost

            Client.prototype.ConsentForm.query({ client_id: client.id }, function(consent_forms){
                _this.consent_forms = consent_forms
            })

            Client.prototype.MedicalNote.query({ client_id: client.id }, function(medical_notes){
                _this.medical_notes = medical_notes
            })

            Client.prototype.ScanSession.query({ client_id: client.id }, function(scan_sessions){
                _this.scan_sessions = scan_sessions

                _this.loaded = true
            })
        })
    }

    this.scan_type = 'body_scan'
    if ($("#scanType").data("value") != 'body_scan') {
        this.scan_type = 'chakra_scan'
    }

    this.deleteConsentForm = function(consent_form) {
        _this = this
        var confirmDialog = confirm("Are you sure you wish to delete this consent form?")
        if (confirmDialog == true) {
            Client.prototype.ConsentForm.delete({ client_id: this.client.id, id: consent_form.id }, function() {
                index = _this.consent_forms.indexOf(consent_form)
                _this.consent_forms.splice(index, 1)
            })
        }
    }
    
    this.addMedicalNote = function(med_note) {
        _this = this
        if (med_note.id != undefined) {
            medical_note = med_note
            medical_note.date_noted = $filter('date')($("#medical_note_date_noted").val(), 'yyyy-MM-dd')

            Client.prototype.MedicalNote.update(medical_note, function(note){
                index = _this.medical_notes.indexOf(med_note)
                _this.medical_notes.splice(index, 1)
                _this.medical_notes.push(note);
                _this.medical_note = { date_noted: new Date() }
            })
        } else {
            medical_note = new Client.prototype.MedicalNote({ client_id: this.client.id })
            medical_note.date_noted = $filter('date')($("#medical_note_date_noted").val(), 'yyyy-MM-dd')
            medical_note.description = med_note.description
            
            medical_note.$save(medical_note, function(note){
                note.date_noted = new Date(note.date_noted)
                _this.medical_notes.push(note);
                _this.medical_note = { date_noted: new Date() }
            })
        }
    }

    this.editMedicalNote = function(med_note) {
        this.medical_note = med_note
        this.medical_note.date_noted = new Date(med_note.date_noted)
    }

    this.cancelMedicalNote = function(med_note) {
        this.medical_note = { date_noted: new Date() }
    }

    this.deleteMedicalNote = function(medical_note) {
        _this = this
        var confirmDialog = confirm("Are you sure you wish to delete this medical note?")
        if (confirmDialog == true) {
            Client.prototype.MedicalNote.delete({ client_id: this.client.id, id: medical_note.id }, function() {
                index = _this.medical_notes.indexOf(medical_note)
                _this.medical_notes.splice(index, 1)
            })
        }
    }

    this.addScanSession = function(session) {
        scan_session = new Client.prototype.ScanSession({ client_id: this.client.id })
        scan_session.date_started = $filter('date')(new Date($("#scan_session_date_started").val()), 'yyyy-MM-dd')
        scan_session.scan_type = session.scan_type
        scan_session.cost = session.cost
        
        _this = this
        scan_session.$save(scan_session, function(_session){
            _this.scan_sessions.push(_session);
            _this.scan_session = { date_started: new Date(), scan_type: null, cost: _this.client.session_cost }
        })
    }

    this.markDoneScanSession = function(session) {
        scan_session = { client_id: session.client_id, id: session.id }
        scan_session.date_ended = $filter('date')(new Date(), 'yyyy-MM-dd')
        var confirmDialog = confirm("Are you sure you wish to mark as done this scan session?")
        if (confirmDialog == true) {
            Client.prototype.ScanSession.update(scan_session, function(_session){
                session.date_ended = scan_session.date_ended
            })
        }
    }

    this.markUndoneScanSession = function(session) {
        scan_session = { client_id: session.client_id, id: session.id, date_ended: null }
        var confirmDialog = confirm("Are you sure you wish to mark as undone this scan session?")
        if (confirmDialog == true) {
            Client.prototype.ScanSession.update(scan_session, function(_session){
                session.date_ended = null
            })
        }
    }

    this.deleteScanSession = function(scan_session) {
        _this = this
        var confirmDialog = confirm("Are you sure you wish to delete this scan session?")
        if (confirmDialog == true) {
            Client.prototype.ScanSession.delete({ client_id: this.client.id, id: scan_session.id }, function() {
                index = _this.scan_sessions.indexOf(scan_session)
                _this.scan_sessions.splice(index, 1)
            })
        }
    }

    this.toggleSortBy = function(column) {
        this.sortBy.column = column
    }

    this.refreshClient = function() {
        _this = this
        _this.loaded = false

        Client.get({ id: this.client.id }, function(client) {
            _this.client = client

            Client.prototype.ConsentForm.query({ client_id: client.id }, function(consent_forms){
                _this.consent_forms = consent_forms
            })

            Client.prototype.MedicalNote.query({ client_id: client.id }, function(medical_notes){
                _this.medical_notes = medical_notes
            })

            Client.prototype.ScanSession.query({ client_id: client.id }, function(scan_sessions){
                _this.scan_sessions = scan_sessions
                _this.loaded = true
            })
        })
    }
}
DataCacheClientShowCtrl.$inject = ['$scope', '$filter', 'Client', 'ClientPair'];

angular.module('AnewApp').controller('DataCacheClientShowCtrl', DataCacheClientShowCtrl);
