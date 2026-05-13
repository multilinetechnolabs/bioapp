<script type="text/javascript">
    $(document).ready(function() {
        function padDatePart(value) {
            return ('0' + value).slice(-2);
        }

        function formatDateForDisplay(value) {
            if (!value) {
                return '';
            }

            var isoMatch = value.match(/^(\d{4})-(\d{2})-(\d{2})$/);
            if (isoMatch) {
                return isoMatch[2] + '/' + isoMatch[3] + '/' + isoMatch[1];
            }

            var date = new Date(value);
            if (!isNaN(date.getTime())) {
                return padDatePart(date.getMonth() + 1) + '/' + padDatePart(date.getDate()) + '/' + date.getFullYear();
            }

            return value;
        }

        function normalizeDateForRequest(value) {
            if (!value) {
                return '';
            }

            var trimmed = $.trim(value);
            var isoMatch = trimmed.match(/^(\d{4})-(\d{2})-(\d{2})$/);
            if (isoMatch) {
                return trimmed;
            }

            var usMatch = trimmed.match(/^(\d{1,2})[\/-](\d{1,2})[\/-](\d{4})$/);
            if (usMatch) {
                return usMatch[3] + '-' + padDatePart(usMatch[1]) + '-' + padDatePart(usMatch[2]);
            }

            var date = new Date(trimmed);
            if (!isNaN(date.getTime())) {
                return date.getFullYear() + '-' + padDatePart(date.getMonth() + 1) + '-' + padDatePart(date.getDate());
            }

            return null;
        }

        function resetClientMessages() {
            $('.client-info-feedback').hide();
            $('.client-info-error-list').empty();
        }

        function titleTranslation(title) {
            var translations = {
                'Edit Client': 'Editar cliente',
                'Add Client': 'Agregar cliente',
                'New Client': 'Nuevo cliente',
                'Client Info': 'Información del cliente'
            };

            return translations[title] || 'Información del cliente';
        }

        function setClientModalTitle(title) {
            var resolvedTitle = title || 'Edit Client';

            $('#clientInfoModal .modal-title-en').text(resolvedTitle);
            $('#clientInfoModal .modal-title-es').text(titleTranslation(resolvedTitle));
        }

        function showClientErrors(messages) {
            var $feedback = $('.client-info-feedback');
            var $list = $('.client-info-error-list');
            var entries = $.isArray(messages) ? messages : [messages];

            $list.empty();

            $.each(entries, function(_, message) {
                if (message) {
                    $list.append($('<li>').text(message));
                }
            });

            if ($list.children().length === 0) {
                $list.append($('<li>').text('An unexpected error occurred. Please try again.'));
            }

            $feedback.show();
        }

        function collectClientErrors(payload) {
            var messages = [];

            function appendError(value) {
                if (!value) {
                    return;
                }

                if ($.isArray(value)) {
                    $.each(value, function(_, nestedValue) {
                        appendError(nestedValue);
                    });

                    return;
                }

                if ($.isPlainObject(value)) {
                    $.each(value, function(_, nestedValue) {
                        appendError(nestedValue);
                    });

                    return;
                }

                messages.push(value);
            }

            if (payload && payload.responseJSON && payload.responseJSON.error) {
                var errorPayload = payload.responseJSON.error;

                appendError(errorPayload.errors);

                if ($.isEmptyObject(errorPayload.errors || {}) || errorPayload.message !== 'Unprocessable Entity') {
                    appendError(errorPayload.message);
                }
            }

            if (messages.length === 0 && payload && payload.statusText) {
                messages.push(payload.statusText);
            }

            if (messages.length === 0) {
                messages.push('An unexpected error occurred. Please try again.');
            }

            return messages;
        }

        function setClientBusy(isBusy, message, buttonLabel) {
            var $saveButton = $('#clientInfoModal .save-btn');
            var $saveButtonLabelEn = $('#clientInfoModal .save-btn-label-en');
            var $saveButtonLabelEs = $('#clientInfoModal .save-btn-label-es');
            var $clearButton = $('#clientInfoModal .clear-btn');

            var resolvedEnglish = buttonLabel || 'Working...';
            var resolvedSpanish = resolvedEnglish === 'Loading...' ? 'Cargando...' : 'Guardando...';

            if (isBusy) {
                $saveButton.addClass('is-loading');
            } else {
                $saveButton.removeClass('is-loading');
            }

            $saveButton.prop('disabled', isBusy);
            $saveButtonLabelEn.text(isBusy ? resolvedEnglish : 'Save');
            $saveButtonLabelEs.text(isBusy ? resolvedSpanish : 'Guardar');
            $clearButton.prop('disabled', isBusy);
        }

        function resetClientForm() {
            $('#client_id').val('');
            $('#mode').val('');
            $('#first_name').val('');
            $('#last_name').val('');
            $('#email').val('');
            $('#address').val('');
            $('#phone_no').val('');
            $('#date_of_birth').val('');
            $('#emergency_contact_person').val('');
            $('#emergency_contact_number').val('');
            $('#session_cost_type').val('normal');
            $('#session_cost').val('');
            $('#session_paid').prop('checked', false);
            $('#gender').val('female');
        }

        if ($('#date_of_birth').hasClass('hasDatepicker')) {
            $('#date_of_birth').datepicker('destroy');
        }

        $('#date_of_birth').datepicker({
            dateFormat: 'mm/dd/yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1900:+0',
            beforeShow: function(input, inst) {
                setTimeout(function() {
                    inst.dpDiv.addClass('client-info-datepicker');
                }, 0);
            },
            onClose: function(_, inst) {
                inst.dpDiv.removeClass('client-info-datepicker');
            }
        });

        $('#clients').DataTable({
            autoWidth: false,
            deferRender: true,
            processing: true,
            searchDelay: 300,
            serverSide: true,
            ajax: { url : '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION" )}}/clients/datatables?user_id={{ Auth::user()->id }}' },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'first_name', name: 'first_name' },
                { data: 'last_name', name: 'last_name' },
                { data: 'email', name: 'email' },
                { data: 'address', name: 'address' },
                { data: 'phone_no', name: 'phone_no' },
                { data: 'date_of_birth', name: 'date_of_birth' },
                { data: 'age', name: 'age', searchable: false, sortable: false },
                { data: 'emergencyDetails', name: 'emergencyDetails', searchable: false, sortable: false },
                { data: 'sessionDetails', name: 'sessionDetails', searchable: false, sortable: false },
                {
                    data: 'id',
                    className: "dt-body-center",
                    "orderable": false,
                    render: function ( data, type, row, meta ) {
                        return '<div class="data-cache-action-group">'
                            + '<button type="button" class="editor_show data-cache-action-btn data-cache-view-btn fa fa-eye" title="View client" aria-label="View client" data-id="'+data+'"></button>'
                            + '<button type="button" class="editor-edit data-cache-action-btn data-cache-edit-btn fa fa-edit" title="Edit client" aria-label="Edit client" data-toggle="modal" data-target="#clientInfoModal" data-title="Edit Client" data-id="'+data+'"></button>'
                            + '<button type="button" class="editor-remove data-cache-action-btn data-cache-delete-btn fa fa-trash-o" title="Delete client" aria-label="Delete client" data-id="'+data+'"></button>'
                            + '</div>';
                    }
                }
            ]
        });

        // Edit record
        $('#clientInfoModal').on('show.bs.modal', function (e) {
            var trigger = $(e.relatedTarget)
            var clientId = trigger.data('id');
            var mode = trigger.data('mode') || '';
            var modalTitle = trigger.data('title');

            resetClientMessages();
            setClientBusy(false);
            setClientModalTitle(modalTitle || (clientId ? 'Edit Client' : 'New Client'));
            resetClientForm();
            $('#mode').val(mode);

            // Retrieve client 
            if (clientId != null) {
                $(".clear-btn").css("display", "none");
            } else {
                $('#mode').val(mode);
                $(".clear-btn").css("display", "initial");
            }

            if (clientId != undefined) {
                setClientBusy(true, 'Loading client information...', 'Loading...');

                $.ajax({
                    url: '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION" )}}/clients/' + clientId,
                    type: 'GET',
                    success: function(result) {
                        $('#client_id').val(result.id);
                        $('#first_name').val(result.first_name);
                        $('#last_name').val(result.last_name);
                        $('#email').val(result.email);
                        $('#address').val(result.address);
                        $('#phone_no').val(result.phone_no);
                        $('#date_of_birth').val(formatDateForDisplay(result.date_of_birth));
                        $('#emergency_contact_person').val(result.emergency_contact_person);
                        $('#emergency_contact_number').val(result.emergency_contact_number);
                        $('#session_cost_type').val(result.session_cost_type);
                        $('#session_cost').val(result.session_cost);
                        $('#gender').val(result.gender);
                        if (result.session_paid == 1) {
                            $('#session_paid').prop('checked', true);
                        } else {
                            $('#session_paid').prop('checked', false);
                        }
                    },
                    error: function(xhr) {
                        showClientErrors(collectClientErrors(xhr));
                    },
                    complete: function() {
                        setClientBusy(false);
                    }
                });
            }
        });

        $(".clear-btn").click(function(e){
            e.preventDefault();
            resetClientMessages();
            resetClientForm();
        });

        // Save a record
        $("#clientInfoModal .save-btn").click(function(e){
            e.preventDefault();

            resetClientMessages();

            var $client_id = $('#client_id').val()
            var $user_id = $('#user_id').val()

            var session_paid;
            if ($('#session_paid').is(":checked")){
                session_paid = 1
            } else {
                session_paid = 0
            }

            var normalizedDateOfBirth = normalizeDateForRequest($('#date_of_birth').val());

            var data = {
                user_id: parseInt($user_id),
                first_name: $("#first_name").val(),
                last_name: $("#last_name").val(),
                email: $("#email").val(),
                address: $("#address").val(),
                phone_no: $("#phone_no").val(),
                date_of_birth: normalizedDateOfBirth,
                emergency_contact_person: $("#emergency_contact_person").val(),
                emergency_contact_number: $("#emergency_contact_number").val(),
                session_cost_type: $("#session_cost_type").val(),
                session_cost: parseFloat($("#session_cost").val()),
                session_paid: session_paid,
                gender: $("#gender").val()
            };

            if ($("#first_name").val() == '' || $("#first_name").val() == null) {
                alert('First Name must be filled out.')
                $("#first_name").focus()
            } else if ($("#last_name").val() == '' || $("#last_name").val() == null) {
                alert('Last Name must be filled out.')
                $("#last_name").focus()
            } else if ($("#email").val() == '' || $("#email").val() == null) {
                alert('Email must be filled out.')
                $("#email").focus()
            } else if ($("#address").val() == '' || $("#address").val() == null) {
                alert('Address must be filled out.')
                $("#address").focus()
            } else if ($("#gender").val() == '' || $("#gender").val() == null) {
                alert('Gender must be filled out.')
                $("#gender").focus()
            } else if ($("#phone_no").val() == '' || $("#phone_no").val() == null) {
                alert('Phone Number must be filled out.')
                $("#phone_no").focus()
            } else if ($("#emergency_contact_person").val() == '' || $("#emergency_contact_person").val() == null) {
                alert('Emergency Contact Person must be filled out.')
                $("#emergency_contact_person").focus()
            } else if ($("#emergency_contact_number").val() == '' || $("#emergency_contact_number").val() == null) {
                alert('Emergency Contact Phone Number must be filled out.')
                $("#emergency_contact_number").focus()
            } else if ($("#date_of_birth").val() == '' || $("#date_of_birth").val() == null) {
                alert('Date of birth must be filled out.')
                $("#date_of_birth").focus()
            } else if (normalizedDateOfBirth == null) {
                alert('Date of birth must be a valid date.')
                $("#date_of_birth").focus()
            } else if ($("#session_cost_type").val() == '' || $("#session_cost_type").val() == null) {
                alert('Session Cost Type must be filled out.')
                $("#session_cost_type").focus()
            } else if ($("#session_cost").val() == '' || $("#session_cost").val() == null) {
                alert('Session Cost must be filled out.')
                $("#session_cost").focus()
            } else {
                setClientBusy(true, 'Saving client information...', 'Saving...');

                if ($client_id != undefined && $client_id != '') {
                    $.ajax({
                        url: '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION" )}}/clients/'+$client_id,
                        type: 'PUT',
                        data: data,
                        dataType: 'JSON',
                        success: function (data) { 
                            location.reload();
                        },
                        error: function(xhr) {
                            showClientErrors(collectClientErrors(xhr));
                        },
                        complete: function() {
                            setClientBusy(false);
                        }
                    });
                } else {
                    $.ajax({
                        url: '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION" )}}/clients',
                        type: 'POST',
                        data: data,
                        dataType: 'JSON',
                        success: function (data) { 
                            if ($("#mode").val() == 'bio' || $("#mode").val() == 'chakra') {
                                window.location.href = "/data_cache/clients/"+data.id
                            } else {
                                location.reload();
                            }
                        },
                        error: function(xhr) {
                            showClientErrors(collectClientErrors(xhr));
                        },
                        complete: function() {
                            setClientBusy(false);
                        }
                    });
                }
            }
        });

        // Delete a record
        $('#clients').on('click', 'button.editor-remove', function (e) {
            e.preventDefault();

            $client_id = $(this).attr('data-id')

            var confirmDialog = confirm("Are you sure you wish to delete this client?");
            if (confirmDialog == true) {
                $.ajax({
                    url: '{{ env("APP_WEB_API_URL") }}/{{ env("APP_WEB_API_VERSION" )}}/clients/'+$client_id,
                    type: 'DELETE',
                    success: function(result) {
                        location.reload();
                    }
                });
            }
        } );

        $('#clients').on('click', 'button.editor_show', function (e) {
            e.preventDefault();

            $client_id = $(this).attr('data-id')

            window.location.href = '/data_cache/clients/' + $client_id
        } );
    });
</script>
